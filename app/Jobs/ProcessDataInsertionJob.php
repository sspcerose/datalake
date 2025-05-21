<?php

namespace App\Jobs;

use App\Events\importSuccess;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

class ProcessDataInsertionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $totalRows;
    protected $userId;
    protected $lastSegment;

    protected $columns = [];
    protected $columnTypes = [];

    public function __construct($filePath, $userId = null, $lastSegment = null)
    {
        $this->filePath = $filePath;
        $this->totalRows = $this->getTotalRows();
        $this->userId = $userId;
        $this->lastSegment = $lastSegment;
    }

    public function handle()
    {
        // time tracking
        $startTotal = microtime(true);

        if (!file_exists($this->filePath)) {
            Log::warning("File not found: {$this->filePath}");
            return;
        }

        if (!Schema::hasTable($this->lastSegment)) {
            Log::error("Table {$this->lastSegment} does not exist");
            return;
        }

        $schemaStart = microtime(true);
        // Gets the column names of slected table (lastSegment)
        $this->columns = Schema::getColumnListing($this->lastSegment);
        foreach ($this->columns as $column) {
            $this->columnTypes[$column] = Schema::getColumnType($this->lastSegment, $column);
        }
        $this->logTime('Schema load', $schemaStart);

        // Real Time increment 
        DB::table('import_status')->updateOrInsert(
            ['user_id' => $this->userId, 'task_name' => $this->lastSegment],
            ['rows_processed' => 0, 'total_rows' => $this->totalRows - 1, 'status' => 'on going', 'created_at' => now(), 'updated_at' => now()]
        );

        $chunkSize = 500;
        $insertedRows = 0;

        DB::beginTransaction();
        try {
            $readStart = microtime(true);

            LazyCollection::make(function () {
                $handle = fopen($this->filePath, 'r');
                fgetcsv($handle); // Skip header
                while (($row = fgetcsv($handle)) !== false) {
                    yield $row;
                }
                fclose($handle);
            })
            // for each chunk of rows (500)
            ->chunk($chunkSize)
            ->each(function ($rows) use (&$insertedRows) {
                $chunkStart = microtime(true);
                $dataBatch = [];

                foreach ($rows as $row) {
                    $mapped = $this->mapRowData($row);
                    if ($mapped !== null) {
                        $dataBatch[] = $mapped;
                    }
                }

                $this->logTime('Mapping chunk', $chunkStart);

                $insertStart = microtime(true);
                if (!empty($dataBatch)) {
                    // insert the batch of data into the database (calling private function insertBatch)
                    $insertedCount = $this->insertBatch($dataBatch);
                    $insertedRows += $insertedCount;
                    // Increment the import status (real time increment)
                    $this->incrementImportStatus($insertedCount);
                }
                $this->logTime('Inserting chunk', $insertStart);
            });

            $this->logTime('Total file read and insert time', $readStart);


            DB::table('import_status')
                ->where('user_id', $this->userId)
                ->where('task_name', $this->lastSegment)
                ->update(['status' => 'completed']);

            DB::table('jobs_done')->insert([
                'user_id' => $this->userId,
                'total_rows' => $insertedRows, 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            //if nothing fails, commit the transaction  
            DB::commit();
            // delete the file after processing
            unlink($this->filePath);

        } catch (\Throwable $e) {
            // if an erros occurs, rollback the transaction
            DB::rollBack();
            Log::error("CSV Import Failed for table {$this->lastSegment}: {$e->getMessage()}");
            // delete the file
            unlink($this->filePath);

            $this->ImportStatus($status = 'failed');
        }

        $this->logTime('Total job runtime', $startTotal);
    }

    // Mapping 
    private function mapRowData($row): ?array
    {
        if (empty($row)) return null;

        $mappedData = ['created_at' => now(), 'updated_at' => now()];

        foreach ($this->columns as $index => $column) {
    
            if ($column === 'created_at' || $column === 'updated_at') {
                continue;
            }

            $value = $row[$index] ?? null;

        // Check if $value is not null and trim it
        // $mappedData[$column] = !empty(trim((string)$value)) ? $this->formatValue($column, $value) : null;
        //    $mappedData[$column] = isset($value) && trim((string)$value) !== '' ? $this->formatValue($column, $value) : null;
        // $mappedData[$column] = (is_numeric($value) || (isset($value) && trim((string)$value) !== '')) 
        // ? $this->formatValue($column, $value) 
        // : null;
            $trimmed = trim((string)$value);
            $mappedData[$column] = ($trimmed !== '') ? $this->formatValue($this->columnTypes[$column], $trimmed) : null;
        }

        return $mappedData;
    }

    // Format the value based on the column type
    // This function is used to format the value before inserting it into the database
    private function formatValue($type, $value)
    {
        switch ($type) {
            case 'integer':
            case 'bigint':
                return is_numeric($value) ? (int) $value : null;
            case 'float':
            case 'decimal':
            case 'double':
                return is_numeric($value) ? (float) $value : null;
            case 'boolean':
                return (bool) $value;
            case 'date':
                return $this->fastParseDate($value);
            case 'datetime':
            case 'timestamp':
                return $this->fastParseDate($value, 'Y-m-d H:i:s');
            default:
                // return !empty($value) ? $value : null;
                 return ($value === '0' || $value === 0 || !empty($value)) ? $value : '-';
        }
    }

    //Date
    private function fastParseDate($value, $format = 'Y-m-d')
    {
        if (empty($value)) return null;

        $timestamp = strtotime($value);
        if ($timestamp === false) return null;

        return date($format, $timestamp);
    }

    // Insertion
    // public function insertBatch(array $data): int
    // {
    //     try {
    //         // insert to the selected table (lastSegment)
    //         DB::table($this->lastSegment)->UpdateOrInsert($data);
    //         return count($data);
    //     } catch (\Exception $e) {
    //         throw $e;
    //     }
    // }
    public function insertBatch(array $data): int
{
    $insertedOrUpdated = 0;
    
    try {
        foreach ($data as $record) {
            // Extract the 'id' (or your unique key) for the WHERE condition
            $id = $record['id'] ?? null;
            
            if ($id === null) {
                // If no ID, perform a simple insert (new record)
                DB::table($this->lastSegment)->insert($record);
            } else {
                // If ID exists, update or insert
                DB::table($this->lastSegment)->updateOrInsert(
                    ['id' => $id],  // WHERE condition (checks if 'id' exists)
                    $record          // Data to insert/update
                );
            }
            $insertedOrUpdated++;
        }
        return $insertedOrUpdated;
    } catch (\Exception $e) {
        Log::error("Batch insert/update failed: " . $e->getMessage());
        throw $e;
    }
}

    private function ImportStatus($status)
    {
        DB::table('import_status')
            ->where('user_id', $this->userId)
            ->where('task_name', $this->lastSegment)
            ->update(['status' => $status]);
    }
    // Real time increment
    private function incrementImportStatus($insertedCount)
    {
        DB::connection('real_time_increment')
            ->table('import_status')
            ->where('user_id', $this->userId)
            ->where('task_name', $this->lastSegment)
            ->increment('rows_processed', $insertedCount);
    }

    // Get total rows 
    private function getTotalRows()
    {
        $rowCount = 0;
        if (file_exists($this->filePath)) {
            $handle = fopen($this->filePath, 'r');
            while (fgetcsv($handle) !== false) {
                $rowCount++;
            }
            fclose($handle);
        }
        return $rowCount;
    }

    // time tracking
    private function logTime($label, $startTime)
    {
        $duration = microtime(true) - $startTime;
        Log::info("[IMPORT TIMING] {$label} took " . number_format($duration, 2) . " seconds.");
    }
}

///////////////////////JUNKS THAT MIGHT BE USEFUL LATER////////////////////////////////////
////////////////////////STATIC
//    public function handle()
//     {
//         if (!file_exists($this->filePath)) {
//             Log::warning("File not found: {$this->filePath}");
//             return;
//         }

//         DB::table('import_status')->updateOrInsert(
//             ['user_id' => $this->userId, 'task_name' => $this->lastSegment],
//             ['rows_processed' => 0, 'total_rows' => $this->totalRows - 1, 'status' => 'on going',  'created_at' => now(), 'updated_at' => now()]
//         );

//         $chunkSize = 500;
//         $insertedRows = 0;

//         DB::beginTransaction();
//         try {
//             LazyCollection::make(function () {
//                 $handle = fopen($this->filePath, 'r');
//                 fgetcsv($handle);
//                 while (($row = fgetcsv($handle)) !== false) {
//                     yield $row;
//                 }
//                 fclose($handle);
//             })
//             ->chunk($chunkSize)
//             ->each(function ($rows) use (&$insertedRows) {
//                 $dataBatch = collect($rows)->map(fn($row) => $this->mapRowData($row))->filter()->toArray();

//                 if (!empty($dataBatch)) {
//                     $insertedCount = $this->insertBatch($dataBatch);
//                     $insertedRows += $insertedCount;
                    
//                     $this->incrementImportStatus($insertedCount);
                    
//                     unset($dataBatch); 

//                 }
//             });

//              DB::table('jobs_done')->insert([
//                 'user_id' => $this->userId,
//                 'total_rows' => $insertedRows, 
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);

//             Log::info("Jobs Done Insertion: user_id=" . $this->userId . ", rows=" . $insertedRows);

//             DB::commit();
//             // event(new importSuccess("CSV import successful! {$insertedRows} rows inserted."));

           

//             unlink($this->filePath); 

//         } catch (\Throwable $e) {
//             DB::rollBack();
//             Log::error("CSV Import Failed: {$e->getMessage()}");
//             unlink($this->filePath);
//         }
//     }

//     private function incrementImportStatus($insertedCount)
//     {
//         DB::connection('real_time_increment')
//             ->table('import_status')
//             ->where('user_id', $this->userId)
//             ->increment('rows_processed', $insertedCount);
//     }

//     private function getTotalRows()
//     {
//         $rowCount = 0;
//         if (file_exists($this->filePath)) {
//             $handle = fopen($this->filePath, 'r');
//             while (fgetcsv($handle) !== false) {
//                 $rowCount++;
//             }
//             fclose($handle);
//         }
//         return $rowCount;
//     }

//     /**
//      * Inserts data into the database in a transaction.
//      */
//     private function insertBatch(array $data): int
//     {
//         try {
//             DB::table('weather')->insert($data);
//             return count($data);
//         } catch (\Exception $e) {
//             throw $e; 
//         }
//     }

// /**
//  * Maps CSV row data to database columns.
//  */
//     private function mapRowData($row): ?array
//     {
//         if (empty($row)) return null;

//         return [
//             'id'                  => $row[0] ?? null,
//             'city_mun_code'       => $row[1] ?? null,
//             'ave_min'             => is_numeric($row[2]) ? (float) $row[2] : null,
//             'ave_max'             => is_numeric($row[3]) ? (float) $row[3] : null,
//             'ave_mean'            => is_numeric($row[4]) ? (float) $row[4] : null,
//             'rainfall_mm'         => $row[5] ?? null,
//             'rainfall_description'=> $row[6] ?? null,
//             'cloud_cover'         => $row[7] ?? null,
//             'humidity'            => is_numeric($row[8]) ? (float) $row[8] : null,
//             'forecast_date'       => $row[9] ?? null,
//             'date_accessed'       => $row[10] ?? null,
//             'wind_mps'            => is_numeric($row[11]) ? (float) $row[11] : null,
//             'direction'           => $row[12] ?? null,
//             'created_at'          => now(),
//             'updated_at'          => now(),
//         ];
//     }

//     /**
//      * Formats a date string from 'd/m/Y' to 'Y-m-d'.
//      */
//     private function formatDate(?string $date): ?string
//     {
//         return ($date && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date))
//             ? Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d')
//             : null;
//     }
// }

// JUNKS (Might be useful later)
// public function handle()
    // {
    //     // $chunkSize = 3000;

    //     // if (!file_exists($this->filePath)) {
    //     //     return;
    //     // }
        
    //     // $extension = pathinfo($this->filePath, PATHINFO_EXTENSION);
    //     // // dd($extension);

    //     // if ($extension === 'csv' && file_exists($this->filePath)) {
            
    //     //     $handle = fopen($this->filePath, 'r');
    //     //     sleep(2); // Wait 2 seconds before reading
    //     //     dd(filesize($this->filePath));   
    //     //     $header = fgetcsv($handle);
    //     //     $dataBatch = [];
    
    //     //     while (($row = fgetcsv($handle)) !== false) {
                
    //     //         $mappedData = $this->mapRowData($row);
    //     //         if ($mappedData) {
    //     //             $dataBatch[] = $mappedData;
    //     //         }

    //     //         if (count($dataBatch) == $chunkSize) {
    //     //             DB::table('weather')->insertOrIgnore($dataBatch);
    //     //             $dataBatch = [];
    //     //         }
    //     //     }

    //     //     if (!empty($dataBatch)) {
    //     //         DB::table('weather')->insertOrIgnore($dataBatch);
    //     //     }

    //     //     fclose($handle);
    //     // }

    //     // Optional: Delete file after processing
    //     // unlink($this->filePath);
        
    //     if (!file_exists($this->filePath)) {

    //         return;
    //     }
    
    //     $handle = fopen($this->filePath, 'r');
    //     $header = fgetcsv($handle); // Read header but ignore it
    
    //     $dataBatch = [];
    //     $chunkSize = 3000;
    
    //     while (($row = fgetcsv($handle)) !== false) {
    //         $mappedData = $this->mapRowData($row);
    //         if ($mappedData) {
    //             $dataBatch[] = $mappedData;
    //         }
    
    //         if (count($dataBatch) == $chunkSize) {
    //             DB::table('weather')->insertOrIgnore($dataBatch);
    //             $dataBatch = [];
    //         }
    //     }
    
    //     if (!empty($dataBatch)) {
    //         DB::table('weather')->insertOrIgnore($dataBatch);
    //     }
    
    //     fclose($handle);
    
    //     // Delete chunk after processing
    //     unlink($this->filePath);
    // }

    // /**
    //  * Map CSV row to database columns.
    //  */
    // private function mapRowData($row)
    // {
    //     return [
    //         'id'                  => $row[0] ?? null,
    //         'city_mun_code'       => $row[1] ?? null,
    //         'ave_min'             => is_numeric($row[2]) ? (float) $row[2] : null,
    //         'ave_max'             => is_numeric($row[3]) ? (float) $row[3] : null,
    //         'ave_mean'            => is_numeric($row[4]) ? (float) $row[4] : null,
    //         'rainfall_mm'         => is_numeric($row[5]) ? (float) $row[5] : null,
    //         'rainfall_description'=> $row[6] ?? null,
    //         'cloud_cover'         => $row[7] ?? null,
    //         'humidity'            => is_numeric($row[8]) ? (float) $row[8] : null,
    //         'forecast_date' => isset($row[9]) && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $row[9])
    //             ? Carbon::createFromFormat('d/m/Y', $row[9])->format('Y-m-d')
    //             : null,
    //         'date_accessed' => isset($row[10]) && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $row[10])
    //             ? Carbon::createFromFormat('d/m/Y', $row[10])->format('Y-m-d')
    //             : null,
    //         'wind_mps'            => is_numeric($row[11]) ? (float) $row[11] : null,
    //         'direction'           => $row[12] ?? null,
    //         'created_at'          => Carbon::now(),
    //         'updated_at'          => Carbon::now(),
    //         // Add other mappings here
    //     ];
    // }
