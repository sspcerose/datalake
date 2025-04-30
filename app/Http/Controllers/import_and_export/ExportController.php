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

    // Using Laravel Excel
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function exportTable($table, $columns, $headings, $fileName)
    {
        return Excel::download(new UsersExport($table, $columns, $headings), $fileName);
    }

// Plain Function with Export Class [Reusable] - Users Table
    public function exportUsers()
        {
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

 
// Plain Function - Weather Table
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

        close($handle);
        }, 200, [
            'Content-Type' => 'xlsx/csv',
            'Content-Disposition' => 'attachment; filename="weather_' . now('Asia/Manila')->format('Y-m-d_H:i:s') . '.csv"',
        ]);

        return back()->with('success', 'File Exported successfully!');
    }
}