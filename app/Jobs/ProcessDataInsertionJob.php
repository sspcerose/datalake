<?php

namespace App\Jobs;

use App\Events\importSuccess;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;
// Excel
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProcessDataInsertionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $totalRows;
    protected $userId;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     */
    public function __construct($filePath, $userId = null)
    {
        $this->filePath = $filePath;
        $this->totalRows = $this->getTotalRows();
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */

   public function handle()
    {
        if (!file_exists($this->filePath)) {
            Log::warning("File not found: {$this->filePath}");
            return;
        }

        $chunkSize = 500;
        $insertedRows = 0;

        DB::beginTransaction();
        try {
            LazyCollection::make(function () {
                $handle = fopen($this->filePath, 'r');
                fgetcsv($handle);
                while (($row = fgetcsv($handle)) !== false) {
                    yield $row;
                }
                fclose($handle);
            })
            ->chunk($chunkSize)
            ->each(function ($rows) use (&$insertedRows) {
                $dataBatch = collect($rows)->map(fn($row) => $this->mapRowData($row))->filter()->toArray();

                if (!empty($dataBatch)) {
                    $insertedRows += $this->insertBatch($dataBatch);
                    unset($dataBatch); 

                }
            });

             DB::table('jobs_done')->insert([
                'user_id' => $this->userId,
                'total_rows' => $insertedRows, 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info("Jobs Done Insertion: user_id=" . $this->userId . ", rows=" . $insertedRows);

            DB::commit();
            // event(new importSuccess("CSV import successful! {$insertedRows} rows inserted."));

           

            unlink($this->filePath); 

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("CSV Import Failed: {$e->getMessage()}");
            unlink($this->filePath);
        }
    }

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

    /**
     * Inserts data into the database in a transaction.
     */
    private function insertBatch(array $data): int
    {
        try {
            DB::table('weather')->insert($data);
            return count($data);
        } catch (\Exception $e) {
            throw $e; 
        }
    }

/**
 * Maps CSV row data to database columns.
 */
    private function mapRowData($row): ?array
    {
        if (empty($row)) return null;

        return [
            'id'                  => $row[0] ?? null,
            'city_mun_code'       => $row[1] ?? null,
            'ave_min'             => is_numeric($row[2]) ? (float) $row[2] : null,
            'ave_max'             => is_numeric($row[3]) ? (float) $row[3] : null,
            'ave_mean'            => is_numeric($row[4]) ? (float) $row[4] : null,
            'rainfall_mm'         => $row[5] ?? null,
            'rainfall_description'=> $row[6] ?? null,
            'cloud_cover'         => $row[7] ?? null,
            'humidity'            => is_numeric($row[8]) ? (float) $row[8] : null,
            'forecast_date'       => $row[9] ?? null,
            'date_accessed'       => $row[10] ?? null,
            'wind_mps'            => is_numeric($row[11]) ? (float) $row[11] : null,
            'direction'           => $row[12] ?? null,
            'created_at'          => now(),
            'updated_at'          => now(),
        ];
    }

    /**
     * Formats a date string from 'd/m/Y' to 'Y-m-d'.
     */
    private function formatDate(?string $date): ?string
    {
        return ($date && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date))
            ? Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d')
            : null;
    }
}

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
