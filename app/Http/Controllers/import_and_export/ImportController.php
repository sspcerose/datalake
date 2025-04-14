<?php

namespace App\Http\Controllers\import_and_export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Industry;
use App\Models\History;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\File;
//Laravel/Excel
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
// Jobs and Queues
use App\Jobs\ProcessFileUploadJob;


class ImportController extends Controller
{
    // Plain Function, working both for csv and excel file (Can't Handle Super Duper Large Data) - Table 1
    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx'
        ]);

        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }
        
        $file = $request->file('file');
        if (!$file->isValid()) {
            return response()->json(['error' => 'Invalid file upload.'], 400);
        }
    
        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $extension = $file->getClientOriginalExtension();
        $dataBatch = [];
        $chunkSize = 5000;
    
        if ($extension === 'csv') {
            // Handle CSV file
            $handle = fopen($filePath, 'r');
    
            // Skip the header row
            $header = fgetcsv($handle);
    
            while (($row = fgetcsv($handle)) !== false) {
                $dataBatch[] = [
                    'track_uri' => $row[0],
                    't_time' => $row[1] ?? null,
                    'platform' => $row[2],
                    'ms_played' => $row[3],
                    'track_name' => $row[4],
                    'artist_name' => $row[5],
                    'album_name' => $row[6],
                    'reason_start' => $row[7] ?? null,
                    'reason_end' => $row[8] ?? null,
                    'shuffle' => $row[9] ?? null,
                    'skipped' => $row[10] ?? null,
                    'created_at' => Carbon::now(), 
                    'updated_at' => Carbon::now(),
                ];
    
                // Insert in chunks
                if (count($dataBatch) == $chunkSize) {
                    DB::table('histories')->insert($dataBatch);
                    $dataBatch = [];
                }
            }
            // Insert remaining rows
            if (!empty($dataBatch)) {
                DB::table('histories')->insert($dataBatch);
            }
    
            fclose($handle);
    
        } elseif ($extension === 'xlsx') {
            // Handle Excel file
            $zip = new \ZipArchive;
        
            if ($zip->open($filePath) === true) {
                // Extract the XML files
                $zip->extractTo(storage_path('excel_temp'));
                $zip->close();
        
                $sharedStringsPath = storage_path('excel_temp/xl/sharedStrings.xml');
                $sheetPath = storage_path('excel_temp/xl/worksheets/sheet1.xml');
        
                // Parse shared strings if they exist
                $sharedStrings = [];
                if (file_exists($sharedStringsPath)) {
                    $sharedStringsXml = simplexml_load_file($sharedStringsPath);
                    foreach ($sharedStringsXml->si as $string) {
                        $sharedStrings[] = (string)$string->t;
                    }
                }
        
                // Parse sheet data
                if (file_exists($sheetPath)) {
                    $sheetData = simplexml_load_file($sheetPath);
        
                    // Skip the first row (header row)
                    $isFirstRow = true; 
        
                    foreach ($sheetData->sheetData->row as $row) {
                        if ($isFirstRow) {
                            // Skip the header row
                            $isFirstRow = false;
                            continue;
                        }
        
                        $columns = [];
                        foreach ($row->c as $cell) {
                            // Determine if the cell contains a shared string
                            $value = (string)$cell->v;
                            if (isset($cell['t']) && $cell['t'] == 's' && isset($sharedStrings[(int)$value])) {
                                $value = $sharedStrings[(int)$value];
                            }
                            $columns[] = $value;
                        }
        
                        // Map the columns to your data structure
                        $dataBatch[] = [
                            'track_uri' => $columns[0] ?? null,
                            't_time' => $columns[1] ?? null,
                            'platform' => $columns[2] ?? null,
                            'ms_played' => $columns[3] ?? null,
                            'track_name' => $columns[4] ?? null,
                            'artist_name' => $columns[5] ?? null,
                            'album_name' => $columns[6] ?? null,
                            'reason_start' => $columns[7] ?? null,
                            'reason_end' => $columns[8] ?? null,
                            'shuffle' => $columns[9] ?? null,
                            'skipped' => $columns[10] ?? null,
                            'created_at' => Carbon::now(), 
                            'updated_at' => Carbon::now(),
                        ];
        
                        // Insert in chunks
                        if (count($dataBatch) == $chunkSize) {
                            DB::table('histories')->insert($dataBatch);
                            $dataBatch = [];
                        }
                    }
        
                    // Insert remaining rows
                    if (!empty($dataBatch)) {
                        DB::table('histories')->insert($dataBatch);
                    }
                }
        
                // Cleanup extracted files
                File::deleteDirectory(storage_path('excel_temp'));
            }
        } else {
            return response()->json(['success' => false, 'error' => 'Invalid file type.'], 400);
        }
    
        // return response()->json(['message' => 'File imported successfully in batches.']);
        return response()->json(['success' => true, 'message' => 'File imported successfully.']);

    }

// Using Laravel Excel (Slightly slower than the plain function) [Using Reusable Code (Export Classs)] - Users Table
    public function importUsers(Request $request)
    {
    // dd('I am here');
    $request->validate([
        'file' => 'required|mimes:csv,xlsx,xls',
    ]);

    $table = 'users'; 
    $columns = [
        'username' => 'username',
        'first_name' => 'first_name',
        'last_name' => 'last_name',
        'user_type' => 'user_type',
        'email' => 'email',
        'password' => 'password',
        'status' => 'status',
    ]; 

    Excel::import(new UsersImport($table, $columns), $request->file('file'));

    return back()->with('success', 'Data imported successfully!');
}

// Using Laravel Excel (Slightly slower than the plain function) [Using Reusable Code (Export Classs)] - Table 1
    public function importTable1(Request $request)
    {
        // dd('I am here');
    $request->validate([
        'file' => 'required|mimes:csv,xlsx,xls',
    ]);
    // dd($request->all());

    $table = 'histories'; 
    $columns = [
        'track_uri' => 'spotify_track_uri',
        't_time' => 'ts',
        'platform' => 'platform',
        'ms_played' => 'ms_played',
        'track_name' => 'track_name',
        'artist_name' => 'artist_name',
        'album_name' => 'album_name',
        'reason_start' => 'reason_start',
        'reason_end' => 'reason_end',
        'shuffle' => 'shuffle',
        'skipped' => 'skipped',
    ];

    Excel::import(new UsersImport($table, $columns), $request->file('file'));

    return back()->with('success', 'Data imported successfully!');
    }

// NOT AGAIN :(

// Using PHPSpreadSheet (Fail)
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:csv,xlsx,xls'
    ]);

    $file = $request->file('file');
    $spreadsheet = IOFactory::load($file->getRealPath());
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    array_shift($rows);

    session(['import_data' => $rows]);

    return response()->json([
        'success' => true,
        'total' => count($rows)
    ]);
}


public function processBatch(Request $request)
{
    $batchSize = 5000;  // Process 10k records per batch
    $startIndex = $request->get('startIndex', 0);

    $data = session('import_data', []);
    $batch = array_slice($data, $startIndex, $batchSize);

    if (empty($batch)) {
        // All data processed
        return response()->json(['completed' => true]);
    }

    $insertData = [];
    foreach ($batch as $row) {
        if (!array_filter($row)) continue; 

        $insertData[] = [
            'track_uri'     => $row[0] ?? null,
            't_time'        => isset($row[1]) ? \Carbon\Carbon::parse($row[1])->format('Y-m-d H:i:s') : null,
            'platform'      => $row[2] ?? null,
            'ms_played'     => $row[3] ?? null,
            'track_name'    => $row[4] ?? null,
            'artist_name'   => $row[5] ?? null,
            'album_name'    => $row[6] ?? null,
            'reason_start'  => $row[7] ?? null,
            'reason_end'    => $row[8] ?? null,
            'shuffle'       => $row[9] ?? null,
            'skipped'       => $row[10] ?? null,
        ];
    }

    if (!empty($insertData)) {
        History::insert($insertData); 
    }

    return response()->json([
        'success' => true,
        'inserted' => count($insertData),
        'nextIndex' => $startIndex + $batchSize
    ]);
}

// Plain Function with Queues and Jobs (Working Very Well) - Weather Table
    public function weatherProcess(Request $request){
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx',
            'index' => 'required|integer',
            'totalChunks' => 'required|integer',
            'fileName' => 'required|string',
        ]);
    
        $file = $request->file('file');
        $index = $request->input('index');
        $totalChunks = $request->input('totalChunks');
        $fileName = $request->input('fileName');
    
        if (!$file || $index === null || !$fileName) {
            return response()->json(['error' => 'Invalid chunk data'], 400);
        }
    
        // Temporary folder for chunked files
        $tempPath = storage_path("app/temp_chunks/{$fileName}");
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0777, true);
        }
        // Save the chunk
        file_put_contents("$tempPath/part{$index}", file_get_contents($file->getPathname()));
    
        // Check if this is the last chunk
        if ($index + 1 == $totalChunks) {
            return $this->mergeChunksAndProcess($fileName, $tempPath);
        }
    
        return response()->json(['success' => true]);
        
    }

    // For Merging chunked files
    private function mergeChunksAndProcess($fileName, $tempPath)
    {
    $tempPath = storage_path("app/temp_chunks/{$fileName}");
    $finalFilePath = storage_path("app/uploads/{$fileName}");

    // Merge all parts into a single file
    $outFile = fopen($finalFilePath, 'wb');
    for ($i = 0; file_exists("$tempPath/part{$i}"); $i++) {
        fwrite($outFile, file_get_contents("$tempPath/part{$i}"));
    }
    fclose($outFile);

    // Delete chunk files
    File::deleteDirectory($tempPath);

    // Dispatch the Job 
    ProcessFileUploadJob::dispatch($fileName);

    // return $this->callDataInsertion($finalFilePath);
    return response()->json(['success' => true, 'message' => 'File uploaded and queued for processing.']);
}

// For Plain Function Without Jobs and Queues
private function callDataInsertion($finalFilePath){

    if (!file_exists($finalFilePath)) {
        \Log::error("File {$finalFilePath} not found before processing.");
        return response()->json(['error' => "File {$finalFilePath} not found."], 404);
    }
    // dd($finalFilePath);
    $chunkSize = 500;
    $insertedRows = 0;

    DB::beginTransaction();
    try {
        LazyCollection::make(function () use ($finalFilePath) {
            return response()->json(['error' => "File {$finalFilePath} not found I am here outside."], 404);
            $handle = fopen($finalFilePath, 'r');
            fgetcsv($handle); // Skip header row
            while (($row = fgetcsv($handle)) !== false) {
                yield $row;
            }
            fclose($handle);
        })
        ->chunk($chunkSize)
        ->each(function ($rows) use (&$insertedRows) {
            $dataBatch = collect($rows)->map(fn($row) => $this->mapRowData($row))->filter()->toArray();

            if (!empty($dataBatch)) {
                $insertedRows += $this->insertBatchWeather($dataBatch);
                unset($dataBatch);
            }
        });

        DB::commit();
        \Log::info("CSV Import Successful! {$insertedRows} rows inserted.");
        unlink($finalFilePath); // Delete file after processing
    } catch (\Throwable $e) {
        DB::rollBack();
        \Log::error("CSV Import Failed: {$e->getMessage()}");
    }

    return response()->json(['success' => true, 'message' => 'File processed successfully.']);
}
    
// Plain Function (Slow)
private function processUploadedFile($filePath)
{
    set_time_limit(120);
    ini_set('memory_limit', '4G');
    DB::disableQueryLog();
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $dataBatch = [];
    $chunkSize = 3000;

    if ($extension === 'csv') {
        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $dataBatch[] = $this->mapRowData($row);
            Log::info('Mapped Data:', $mappedData);

            if (count($dataBatch) == $chunkSize) {
                DB::table('weather')->insertOrIgnore($dataBatch);
                Log::info('Inserted a batch of ' . count($dataBatch) . ' rows');
                $dataBatch = [];
            }
        }

        if (!empty($dataBatch)) {
            DB::table('weather')->insertOrIgnore($dataBatch);
        }

        fclose($handle);
    } elseif ($extension === 'xlsx') {
        // Your existing XLSX parsing logic
    } else {
        return response()->json(['success' => false, 'error' => 'Invalid file type.'], 400);
    }

    return response()->json(['success' => true, 'message' => 'File imported successfully.']);
}

// For Plain Function Without Jobs and Queues (Not Working)
private function mapRowData($row)
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

// For Plain Function Without Jobs and Queues (Not Working)
private function insertBatchWeather(array $data): int
{
    try {
        DB::table('weather')->insert($data);
        return count($data);
    } catch (\Exception $e) {
        \Log::error("Database Insert Error: {$e->getMessage()}");
        throw $e;
    }
}

///////////////////////JUNKS THAT MIGHT BE USEFUL LATER////////////////////////////////////
// public function process(Request $request)
// {
    // // dd('HELLO');
    // $request->validate([
    //     'file' => 'required|file|mimes:csv,txt',
    // ]);

    // $file = $request->file('file');
    // $path = $file->getRealPath();

    // $handle = fopen($path, 'r');
    // if (!$handle) {
    //     return back()->with('error', 'Could not open the file.');
    // }

    // $header = fgetcsv($handle); // Read header row
    // $batchSize = 5000; // Process 5000 rows at a time
    // $rows = [];
    // $totalRows = 0;

    // while (($row = fgetcsv($handle)) !== false) {
    //     $totalRows++;
    // }
    // rewind($handle); // Reset file pointer to beginning
    // fgetcsv($handle);

    // $processedRows = 0;
    // while (($row = fgetcsv($handle)) !== false) {
    //     if (empty(array_filter($row))) {
    //         continue;
    //     }

    //     // $formattedTimestamp = isset($row[1]) 
    //     // ? Carbon::createFromFormat('d/m/Y h:i:s a', $row[1])->format('Y-m-d H:i:s')
    //     // : null;

    //     $rows[] = [
    //         'track_uri'    => $row[0] ?? null,
    //         // 't_time'       => $formattedTimestamp,
    //         't_time'       =>  $row[1] ?? null,
    //         'platform'     => $row[2] ?? null,
    //         'ms_played'    => $row[3] ?? null,
    //         'track_name'   => $row[4] ?? null,
    //         'artist_name'  => $row[5] ?? null,
    //         'album_name'   => $row[6] ?? null,
    //         'reason_start' => $row[7] ?? null,
    //         'reason_end'   => $row[8] ?? null,
    //         'shuffle'      => $row[9] ?? null,
    //         'skipped'      => $row[10] ?? null,
    //     ];

    //     // Insert data in chunks
    //     if (count($rows) >= $batchSize) {
    //         $this->insertBatch($rows);
    //         $processedRows += count($rows);
    //         $rows = []; // Clear the batch
          

    //         session(['import_progress' => round(($processedRows / $totalRows) * 100)]);
    //     }
    // }

    // // Insert remaining rows
    // if (!empty($rows)) {
    //     $this->insertBatch($rows);
    //     $processedRows += count($rows);
    //     session(['import_progress' => round(($processedRows / $totalRows) * 100)]);
    // }

    // fclose($handle);
    // session(['import_progress' => 100]);

    // return response()->json(['success' => true, 'message' => 'File processed successfully.'])
   

    // return response()->json(['message' => 'File imported successfully in batches.']);
//     return response()->json(['success' => true, 'message' => 'File imported successfully.']);

// }

// For Progress Bar (Not Working)
//    public function getProgress()
//    {
//         return response()->json(['progress' => session('import_progress', 0)]);
//    }
//    // 
//    private function insertBatch(array $rows)
//    {
//        DB::transaction(function () use ($rows) {
//            History::insert($rows);
//        });
//    }

// public function weatherProcess(Request $request){
    // \Log::info($request->all());
  
    //     $request->validate([
    //         'file' => 'required|mimes:csv,txt,xlsx'
    //     ]);
    
    //     if (!$request->hasFile('file')) {
    //         return response()->json(['error' => 'No file uploaded.'], 400);
    //     }
        
    //     $file = $request->file('file');
    //     if (!$file->isValid()) {
    //         return response()->json(['error' => 'Invalid file upload.'], 400);
    //     }
    
    //     $filePath = $file->getRealPath();
    //     $extension = $file->getClientOriginalExtension();
    //     $dataBatch = [];
    //     $chunkSize = 5000;
    
    //     if ($extension === 'csv') {
    //         $handle = fopen($filePath, 'r');
    
    //         $header = fgetcsv($handle);
    
    //         while (($row = fgetcsv($handle)) !== false) {
    //             $dataBatch[] = [
    //                 'id'                  => $row[0] ?? null,
    //                 'city_mun_code'       => $row[1] ?? null,
    //                 'ave_min'             => $row[2] ?? null,
    //                 'ave_max'             => $row[3] ?? null,
    //                 'ave_mean'            => $row[4] ?? null,
    //                 'rainfall_mm'         => $row[5] ?? null,
    //                 'rainfall_description'=> $row[6] ?? null,
    //                 'cloud_cover'         => $row[7] ?? null,
    //                 'humidity'            => $row[8] ?? null,
    //                 'forecast_date' => isset($row[9]) ? \Carbon\Carbon::createFromFormat('d/m/Y', $row[9])->format('Y-m-d') : null,
    //                 'date_accessed' => isset($row[10]) ? \Carbon\Carbon::createFromFormat('d/m/Y', $row[10])->format('Y-m-d') : null,
    //                 'wind_mps'            => $row[11] ?? null,
    //                 'direction'           => $row[12] ?? null,
    //                 'created_at' => Carbon::now(),
    //                 'updated_at' => Carbon::now(),
    //             ];
    
    //             if (count($dataBatch) == $chunkSize) {
    //                 DB::table('weather')->insertOrIgnore($dataBatch);
    //                 $dataBatch = [];
    //             }
    //         }
    
    //         if (!empty($dataBatch)) {
    //             DB::table('weather')->insertOrIgnore($dataBatch);
    //         }
    
    //         fclose($handle);
    //     } elseif ($extension === 'xlsx') {
    //         $zip = new \ZipArchive;
    
    //         if ($zip->open($filePath) === true) {
    //             $zip->extractTo(storage_path('excel_temp'));
    //             $zip->close();
    
    //             $sharedStringsPath = storage_path('excel_temp/xl/sharedStrings.xml');
    //             $sheetPath = storage_path('excel_temp/xl/worksheets/sheet1.xml');
    
    //             $sharedStrings = [];
    //             if (file_exists($sharedStringsPath)) {
    //                 $sharedStringsXml = simplexml_load_file($sharedStringsPath);
    //                 foreach ($sharedStringsXml->si as $string) {
    //                     $sharedStrings[] = (string)$string->t;
    //                 }
    //             }
    //             if (file_exists($sheetPath)) {
    //                 $sheetData = simplexml_load_file($sheetPath);
    
    //                 $isFirstRow = true;
    
    //                 foreach ($sheetData->sheetData->row as $row) {
    //                     if ($isFirstRow) {
    //                         // Skip the header row
    //                         $isFirstRow = false;
    //                         continue;
    //                     }
    
    //                     $columns = [];
    //                     foreach ($row->c as $cell) {
    //                         // Determine if the cell contains a shared string
    //                         $value = (string)$cell->v;
    //                         if (isset($cell['t']) && $cell['t'] == 's' && isset($sharedStrings[(int)$value])) {
    //                             $value = $sharedStrings[(int)$value];
    //                         }
    //                         $columns[] = $value;
    //                     }
    
    //                     // Map the columns to your data structure
    //                     $dataBatch[] = [
    //                         'id'                  => $columns[0] ?? null,
    //                         'city_mun_code'       => $columns[1] ?? null,
    //                         'ave_min'             => $columns[2] ?? null,
    //                         'ave_max'             => $columns[3] ?? null,
    //                         'ave_mean'            => $columns[4] ?? null,
    //                         'rainfall_mm'         => $columns[5] ?? null,
    //                         'rainfall_description'=> $columns[6] ?? null,
    //                         'cloud_cover'         => $columns[7] ?? null,
    //                         'humidity'            => $columns[8] ?? null,
    //                         'forecast_date' => isset($columns[9]) ? \Carbon\Carbon::createFromFormat('d/m/Y', $columns[9])->format('Y-m-d') : null,
    //                         'date_accessed' => isset($columns[10]) ? \Carbon\Carbon::createFromFormat('d/m/Y', $columns[10])->format('Y-m-d') : null,
    //                         'wind_mps'            => $columns[11] ?? null,
    //                         'direction'           => $columns[12] ?? null,
    //                         'created_at' => Carbon::now(),
    //                         'updated_at' => Carbon::now(),
    //                     ];
    
    //                     // Insert in chunks
    //                     if (count($dataBatch) == $chunkSize) {
    //                         DB::table('weather')->insertOrIgnore($dataBatch);
    //                         $dataBatch = [];
    //                     }
    //                 }
    
    //                 // Insert remaining rows
    //                 if (!empty($dataBatch)) {
    //                     DB::table('weather')->insertOrIgnore($dataBatch);
    //                 }
    //             }
    
    //             // Cleanup extracted files
    //             File::deleteDirectory(storage_path('excel_temp'));
    //         }
    //     } else {
    //         return response()->json(['success' => false, 'error' => 'Invalid file type.'], 400);
    //     }
    
    //     return response()->json(['success' => true, 'message' => 'File imported successfully.']);
    // }

    // if (!$request->hasFile('file')) {
    //     return response()->json(['error' => 'No file uploaded'], 400);
    // }

    // $file = $request->file('file');
    // $index = $request->input('index');
    // $totalChunks = $request->input('totalChunks');
    // $fileName = $request->input('fileName');

    // if (!$file || $index === null || !$fileName) {
    //     return response()->json(['error' => 'Invalid chunk data'], 400);
    // }

    // $tempPath = storage_path('app/temp_chunks');
    // if (!file_exists($tempPath)) {
    //     mkdir($tempPath, 0777, true);
    // }

    // file_put_contents($tempPath . "/{$fileName}.part{$index}", file_get_contents($file->getPathname()));

    // return response()->json(['success' => true]);
    
// }

// private function mergeChunksAndProcess($fileName, $tempPath)
// {
    // dd('I am here');
    // $tempPath = storage_path("app/temp_chunks/{$fileName}");
    // $finalFilePath = storage_path("app/uploads/{$fileName}");

    // // Merge all parts into a single file
    // $outFile = fopen($finalFilePath, 'wb');
    // for ($i = 0; file_exists("$tempPath/part{$i}"); $i++) {
    //     fwrite($outFile, file_get_contents("$tempPath/part{$i}"));
    // }
    // fclose($outFile);

    // // Delete chunk files
    // File::deleteDirectory($tempPath);

    // // Process the uploaded file (CSV/XLSX handling)
    // return $this->processUploadedFile($finalFilePath);
// }


}
