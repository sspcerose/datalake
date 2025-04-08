<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class ProcessFileUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fileName;

    /**
     * Create a new job instance.
     *
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // $tempPath = storage_path("app/temp_chunks/{$this->fileName}");
        // $finalFilePath = storage_path("app/uploads/{$this->fileName}");

        // $outFile = fopen($finalFilePath, 'wb');
        // for ($i = 0; file_exists("$tempPath/part{$i}"); $i++) {
        //     fwrite($outFile, file_get_contents("$tempPath/part{$i}"));
        // }
        // fclose($outFile);

        // File::deleteDirectory($tempPath);

        // // Dispatch the insertion job
        // // ProcessDataInsertionJob::dispatch($finalFilePath)->delay(now()->addSeconds(2));
        // ProcessDataInsertionJob::dispatch($finalFilePath);

        $finalFilePath = storage_path("app/uploads/{$this->fileName}");

    // Ensure file exists before dispatching the next job
    if (!file_exists($finalFilePath)) {
        \Log::error("File {$finalFilePath} not found before processing.");
        return;
    }

    // ✅ Dispatch the insertion job with the file
    ProcessDataInsertionJob::dispatch($finalFilePath);
    }
}
