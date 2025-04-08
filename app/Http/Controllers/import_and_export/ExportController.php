<?php

namespace App\Http\Controllers\import_and_export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\History;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;


use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;


class ExportController extends Controller
{
    public function exportCsv()
    {
    //     // Streamed response for optimized export
    //     ob_end_clean();
    //     $response = new StreamedResponse(function () {
            
            
    //         // Open a file handle for output
    //         $handle = fopen('php://output', 'w');
            

    //         // Add the CSV header row
    //         fputcsv($handle, ['track_uri',
    //                         'timestamps',
    //                         'platform',
    //                         'ms_played',
    //                         'track_name',
    //                         'artist_name',
    //                         'album_name',
    //                         'reason_start',
    //                         'reason_end',
    //                         'shuffle',
    //                         'skipped']); 
            

    //         // Fetch data from the database in chunks
    //         DB::table('histories') // Replace with your table name
    //             ->select('track_uri',
    //                     'timestamps',
    //                     'platform',
    //                     'ms_played',
    //                     'track_name',
    //                     'artist_name',
    //                     'album_name',
    //                     'reason_start',
    //                     'reason_end',
    //                     'shuffle',
    //                     'skipped') // Replace with the columns to export
    //             ->chunk(10000, function ($rows) use ($handle) {
    //                 foreach ($rows as $row) {
    //                     fputcsv($handle, [
    //                         $row->track_uri,
    //                         $row->timestamps,
    //                         $row->platform,
    //                         $row->ms_played,
    //                         $row->track_name,
    //                         $row->artist_name,
    //                         $row->album_name,
    //                         $row->reason_start,
    //                         $row->reason_end,
    //                         $row->shuffle,
    //                         $row->skipped,
                            
    //                     ]);
    //                 }
    //             });

    //         // Close the handle when done
    //         fclose($handle);
    //     });

    //     // Set headers for CSV file download
    //     $response->headers->set('Content-Type', 'text/csv');
    //     $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');

    //     return $response;
    // }

    return response()->stream(function () {
        // Open output stream
        $handle = fopen('php://output', 'w');
        
        // headers
        fputcsv($handle, [
            'track_uri', 'timestamps', 'platform', 'ms_played', 'track_name',
            'artist_name', 'album_name', 'reason_start', 'reason_end', 'shuffle', 'skipped'
        ]);

        // from database
        $records = DB::table('histories')->select(
            'track_uri', 't_time', 'platform', 'ms_played', 'track_name', 
            'artist_name', 'album_name', 'reason_start', 'reason_end', 'shuffle', 'skipped'
        )->cursor(); 

        foreach ($records as $row) {
            fputcsv($handle, (array) $row);
        }

        fclose($handle);
    }, 200, [
        'Content-Type' => 'xlsx/csv',
        'Content-Disposition' => 'attachment; filename="table1_' . now('Asia/Manila')->format('Y-m-d_H:i:s') . '.csv"',
    ]);

    return back()->with('success', 'File imported successfully!');
}

public function export()
    {
        // dd('I am here');
        return Excel::download(new UsersExport, 'users.xlsx');
    }

public function exportTable($table, $columns, $headings, $fileName)
    {
        return Excel::download(new UsersExport($table, $columns, $headings), $fileName);
    }

public function exportUsers()
    {
        // dd('I am here');
        $table = 'users';
        $columns = ['username', 'first_name', 'last_name', 'user_type', 'email', 'status'];
        $headings = ['Username', 'First Name', 'Last Name', 'User Type', 'Email', 'Status'];
        $fileName = 'users.xlsx';

        return $this->exportTable($table, $columns, $headings, $fileName);
    }

// public function exportTable1()
//     {
//         dd('I am here');
//         $table = 'histories';
//         $columns = ['track_uri', 't_time', 'platform', 'ms_played', 'track_name', 'artist_name', 'album_name', 'reason_start', 'reason_end', 'shuffle', 'skipped'];
//         $headings = ['track_uri', 'timestamps', 'platform', 'ms_played', 'track_name', 'artist_name', 'album_name', 'reason_start', 'reason_end', 'shuffle', 'skipped'];
//         $fileName = 'histories.xlsx';

//         return $this->exportTable($table, $columns, $headings, $fileName);
//     }


public function exportWeather()
    {
    

    return response()->stream(function () {
        // Open output stream
        $handle = fopen('php://output', 'w');
        
        // headers
        fputcsv($handle, [
            'id',
            'city_mun_code',
            'ave_min',
            'ave_max',
            'ave_mean',
            'rainfall_mm',
            'rainfall_description',
            'cloud_cover',
            'humidity',
            'forecast_date',
            'date_accessed',
            'wind_mps',
            'direction',
        ]);

        // from database
        $records = DB::table('weather')->select(
            'id',
            'city_mun_code',
            'ave_min',
            'ave_max',
            'ave_mean',
            'rainfall_mm',
            'rainfall_description',
            'cloud_cover',
            'humidity',
            'forecast_date',
            'date_accessed',
            'wind_mps',
            'direction',
        )->cursor(); 

        foreach ($records as $row) {
            fputcsv($handle, (array) $row);
        }

        fclose($handle);
    }, 200, [
        'Content-Type' => 'xlsx/csv',
        'Content-Disposition' => 'attachment; filename="weather_' . now('Asia/Manila')->format('Y-m-d_H:i:s') . '.csv"',
    ]);

    return back()->with('success', 'File Exported successfully!');
}

}