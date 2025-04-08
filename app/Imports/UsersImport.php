<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Hash;

class UsersImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    /**
     * Process rows in chunks.
     */
    protected $table;
    protected $columns;

    public function __construct($table, $columns)
    {
        $this->table = $table;
        $this->columns = $columns;
    }

    public function collection($rows)
    {
        // $data = [];

        // foreach ($rows as $row) {
        //     $data[] = [
        //         'username' => $row['username'], 
        //         'first_name' => $row['first_name'],
        //         'last_name' => $row['last_name'],
        //         'user_type' => $row['user_type'],
        //         'email' => $row['email'],
        //         'password' => Hash::make($row['password']),
        //         'status' => $row['status'],
        //     ];

        foreach ($rows as $row) {
            $entry = [];
            foreach ($this->columns as $dbColumn => $excelColumn) {
                $entry[$dbColumn] = $row[$excelColumn];
            }

            // Optionally handle special columns like hashing passwords
            if (isset($entry['password'])) {
                $entry['password'] = bcrypt($entry['password']);
            }

            // if (isset($entry['ms_played'])) {
            //     $entry['ms_played'] = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $row['ms_played'])->toDateTimeString();
            // }


            $data[] = $entry;
        }

        // Insert chunked data into the database
        if (!empty($data)) {
            DB::table($this->table)->insertOrIgnore($data);
        }
    }

    /**
     * Specify the chunk size for reading.
     */
    public function chunkSize(): int
    {
        return 1000; // Process 1000 rows per chunk
    }
}
