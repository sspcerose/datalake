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
        foreach ($rows as $row) {
            $entry = [];
            foreach ($this->columns as $dbColumn => $excelColumn) {
                $entry[$dbColumn] = $row[$excelColumn];
            }

            if (isset($entry['password'])) {
                $entry['password'] = bcrypt($entry['password']);
            }

            $data[] = $entry;
        }

        if (!empty($data)) {
            DB::table($this->table)->insertOrIgnore($data);
        }
    }

    /**
     * Specify the chunk size for reading.
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
