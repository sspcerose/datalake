<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersExport implements FromQuery, WithHeadings, WithChunkReading
{
    /**
     * Query data for export using Query Builder.
     */
    protected $table;
    protected $columns;
    protected $headings;

    public function __construct($table, $columns, $headings)
    {
        $this->table = $table;
        $this->columns = $columns;
        $this->headings = $headings;
    }

    public function query()
    {
        // return DB::table('users')->select([
        //     'username',
        //     'first_name',
        //     'last_name',
        //     'user_type',
        //     'email',
        //     'status',
        // ])->orderBy('id');

        return DB::table($this->table)->select($this->columns)->orderBy('id', 'asc');;
    }

    /**
     * Define column headings for the exported file.
     */
    public function headings(): array
    {
        // return [
        //     'Username',
        //     'First Name',
        //     'Last Name',
        //     'User Type',
        //     'Email',
        //     'Status',
        // ];

        return $this->headings;
    }

    public function chunkSize(): int
    {
        return 10000; // Process 1000 rows at a time
    }
}
