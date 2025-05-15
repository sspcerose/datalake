<?php

namespace App\Http\Controllers\dynamic;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ProcessFileUploadJob;

class DynamicController extends Controller
{


    public function index(Request $request, $table = null)
    {
        $tables = DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
        $tableNames = array_map(fn($t) => $t->tablename, $tables);
        
        $excludedTables = [
            'migrations', 
            'password_resets', 
            'failed_jobs', 
            'personal_access_tokens', 
            'password_reset_tokens', 
            'sessions', 'cache', 
            'cache_locks', 
            'job_batches', 
            'jobs',
            'roles', 
            'role_permission',
            'permissions',
            'users',
            'jobs_done',
            'import_status'
        ];

        // Filter excluded tables
        $filteredTableNames = array_values(array_diff($tableNames, $excludedTables));
                        
        // Current table
        $selectedTable = $table ?? ($filteredTableNames[0] ?? null);
        $columns = [];
        $rows = collect();

        // If table exist
        if ($selectedTable && in_array($selectedTable, $filteredTableNames)) {
            //get the columns of selected table
            $columns = Schema::getColumnListing($selectedTable);
            // get the rows of selected table
            $query = DB::table($selectedTable);
            
            // search
            if ($request->has('query')) {
                $searchQuery = $request->input('query');
                foreach ($columns as $column) {
                    $query->orWhere($column, 'ilike', '%' . $searchQuery . '%');
                }
            }

            // sort (column, order)
            $sortField = $request->input('sort_field');
            $sortOrder = $request->input('sort_order', 'asc');

            if ($sortField && $sortOrder !== 'none' && in_array($sortField, $columns)) {
                $query->orderBy($sortField, $sortOrder);
            }

            $rows = $query->paginate(20);

            // for data columns
            foreach ($rows as $row) {
                foreach ($columns as $col) {
                    if ($row->$col && \Carbon\Carbon::canBeCreatedFromFormat($row->$col, 'Y-m-d')) {
                        $row->$col = \Carbon\Carbon::parse($row->$col)->format('F d, Y');
                    }
                }
            }
        
            // AJAX Request (search)
            if ($request->ajax()) {
                return response()->json([
                    'data' => $rows->items(),
                    'links' => $rows->links('vendor.pagination.bootstrap-5')->render(),
                ]);
            }
        }

        return view('content.dynamic.dynamic', [
            'tableNames'    => $filteredTableNames,
            'selectedTable' => $selectedTable,
            'columns'       => $columns,
            'rows'          => $rows,
        ]);
    }
    

    public function create($table)
    {
        $tables = \DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
        $tableNames = array_map(fn($t) => $t->tablename, $tables);

        $excludedTables = [
            'migrations', 
            'password_resets', 
            'failed_jobs', 
            'personal_access_tokens', 
            'password_reset_tokens', 
            'sessions', 
            'cache', 
            'cache_locks', 
            'job_batches', 
            'jobs',
            'roles', 
            'role_permission',
            'permissions',
            'users',
            'jobs_done',
            'import_status'
        ];

        $filteredTableNames = array_values(array_diff($tableNames, $excludedTables));

        // If the table exist
        $selectedTable = $table;
        if (!$selectedTable || !in_array($selectedTable, $filteredTableNames)) {
            return redirect()->back()->withErrors(['error' => 'Table not found']);
        }

        // Get the columns of the selected table
        $columns = Schema::getColumnListing($selectedTable);
        $columnDetails = [];

        // Get the data type of each column
        foreach ($columns as $column) {
            $type = \DB::getSchemaBuilder()->getColumnType($selectedTable, $column);
            $columnDetails[] = ['name' => $column, 'type' => $type];
        }

        return view('content.dynamic.create', compact('selectedTable', 'columnDetails'));
    }

    public function store(Request $request, $table)
    {
        // Get column details
        
        $columns = Schema::getColumnListing($table);
        $validationRules = [];

        foreach ($columns as $column) {
            if (!in_array($column, ['id', 'created_at', 'updated_at'])) {
                $columnType = \DB::getSchemaBuilder()->getColumnType($table, $column);

                switch ($columnType) {
                    case 'string':
                        $validationRules[$column] = 'nullable|string|max:255';
                        break;
                    case 'text':
                        $validationRules[$column] = 'nullable|string';
                        break;
                    case 'integer':
                        $validationRules[$column] = 'nullable|integer';
                        break;
                    case 'date':
                        $validationRules[$column] = 'nullable|date';
                        break;
                    case 'boolean':
                        $validationRules[$column] = 'nullable|boolean';
                        break;
                    default:
                        $validationRules[$column] = 'nullable'; // fallback
                }
            }
        }

        // Validate input
        $validatedData = $request->validate($validationRules);

        // Attempt insert
        try {
            DB::table($table)->insert($validatedData);
            return redirect()->route('table.viewer', $table)->with('success', 'Record added successfully.');
        } catch (\Exception $e) {
            \Log::error("Insert failed for table [$table]: " . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to insert the record. Please try again.']);
        }
    }

    public function edit($table, $id)
    {
        // Retrieve column details
        $columns = Schema::getColumnListing($table);
        $columnDetails = [];

        foreach ($columns as $column) {
            $type = \DB::getSchemaBuilder()->getColumnType($table, $column);
            $columnDetails[] = ['name' => $column, 'type' => $type];
        }

        // Fetch the record to be updated
        $record = DB::table($table)->find($id);

        if (!$record) {
            return redirect()->route('table.viewer', $table)->withErrors(['error' => 'Record not found.']);
        }

        return view('content.dynamic.edit', compact('table', 'columnDetails', 'record'));
    }

    public function update(Request $request, $table, $id)
    {
        $columns = Schema::getColumnListing($table);
        $validationRules = [];

        foreach ($columns as $column) {
            if (!in_array($column, ['id', 'created_at', 'updated_at'])) {
                $columnType = strtolower(DB::getSchemaBuilder()->getColumnType($table, $column));
                logger("Column: $column => Type: $columnType");

                // Validation
                switch ($columnType) {
                    case 'string':
                    case 'varchar':
                        $validationRules[$column] = 'required|string|max:255';
                        break;

                    case 'text':
                        $validationRules[$column] = 'nullable|string';
                        break;

                    case 'integer':
                    case 'bigint':
                        $validationRules[$column] = 'required|integer';
                        break;

                    case 'float':
                    case 'double':
                    case 'decimal':
                    case 'float8':
                        $validationRules[$column] = 'nullable|numeric';
                        break;

                    case 'date':
                        $validationRules[$column] = 'nullable|date';
                        break;

                    case 'boolean':
                        $validationRules[$column] = 'required|boolean';
                        break;

                    default:
                        $validationRules[$column] = 'nullable';
                        break;
                }
            }
        }

        $validatedData = $request->validate($validationRules);
        // dd($validatedData);

        try {
            DB::table($table)->where('id', $id)->update($validatedData);
            return redirect()->route('table.viewer', $table)->with('success', 'Record updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update the record. Please try again.']);
        }
    }

    public function destroy($table, $id)
    {
        try {
            DB::table($table)->where('id', $id)->delete();

            // Check if the request is AJAX (from search)
            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
            }

            return redirect()->route('table.viewer', $table)->with('success', 'Record deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete record.']);
            }

            return redirect()->route('table.viewer', $table)->with('error', 'Failed to delete record.');
        }
    }

    public function view($table, $id)
    {
        $record = DB::table($table)->find($id);

        if (!$record) {
            return redirect()->route('table.viewer', $table)->withErrors(['error' => 'Record not found.']);
        }

        return view('content.dynamic.view', compact('table', 'record'));
    }

    public function export($table)
    {
        // if the tabe exist
        if (!Schema::hasTable($table)) {
            return back()->withErrors(['error' => 'The specified table does not exist.']);
        }

        // Get the column names of the table
        $columns = array_filter(Schema::getColumnListing($table), function ($column) {
            return !in_array($column, ['created_at', 'updated_at']);
        });

        return response()->stream(function () use ($table, $columns) {
            // Open output stream
            $handle = fopen('php://output', 'w');

            // Write headers (column names)
            fputcsv($handle, $columns);

            // gets the rows
            $records = DB::table($table)->select($columns)->cursor();

            foreach ($records as $row) {
                fputcsv($handle, (array) $row);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $table . '_' . now('Asia/Manila')->format('Y-m-d_H:i:s') . '.csv"',
        ]);
    }

    // Import Start
    public function importProcess(Request $request, $table)
    {
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
        
        file_put_contents("$tempPath/part{$index}", file_get_contents($file->getPathname()));

        if ($index + 1 == $totalChunks) {
            return $this->mergeChunksAndProcess($fileName, $tempPath, $table);
        }

        return response()->json(['success' => true]);  
    }

    private function mergeChunksAndProcess($fileName, $tempPath, $table)
    {
        $finalFilePath = storage_path("app/uploads/{$fileName}");

        $outFile = fopen($finalFilePath, 'wb');
        for ($i = 0; file_exists("$tempPath/part{$i}"); $i++) {
            fwrite($outFile, file_get_contents("$tempPath/part{$i}"));
        }
        fclose($outFile);

        File::deleteDirectory($tempPath);

        ProcessFileUploadJob::dispatch($fileName, Auth::id(), $table);

        return response()->json(['success' => true, 'message' => 'File uploaded and queued for processing.']);
    }

    // Insert status 
    public function importStatus($table)
    {
        $userId = Auth::id();
        
        $status = DB::table('import_status')
            ->where('user_id', $userId)
            ->where('task_name', $table)
            ->first();
        

        if (!$status) {
            return response()->noContent();
        }

        if ($status && $status->rows_processed === $status->total_rows) {
                $update_status = DB::table('import_status')
                    ->where('user_id', auth()->id())
                    ->where('task_name', $table)  
                    ->update(['status' => 'completed']);

            return response(null, 204);
        }

        return response()->json([
            'rows_processed' => $status->rows_processed,
            'total_rows' => $status->total_rows,
        ]);
    }
    // Import end

///////////////////////JUNKS THAT MIGHT BE USEFUL LATER////////////////////////////////////
//     public function index($table = null)
// {
//     // Step 1: Get all table names from PostgreSQL
//     $tables = DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
//     $tableNames = array_map(fn($t) => $t->tablename, $tables);

//     // Step 2: Define the tables you want to exclude
//     $excludedTables = ['migrations', 
//                         'password_resets', 
//                         'failed_jobs', 
//                         'personal_access_tokens', 
//                         'password_reset_tokens', 
//                         'sessions', 'cache', 
//                         'cache_locks', 
//                         'job_batches', 
//                         // 'jobs',
//                         'roles', 
//                         'role_permission',
//                         'permissions',
//                         'users',
//                         'jobs_done',
//                         // 'import_status'
//                     ];

//     // Step 3: Filter them out
//     $filteredTableNames = array_values(array_diff($tableNames, $excludedTables));

//     // Step 4: Determine which table to show
//     $selectedTable = $table ?? ($filteredTableNames[0] ?? null);
//     $columns = [];
//     $rows = collect();

//     if ($selectedTable && in_array($selectedTable, $filteredTableNames)) {
//         $columns = Schema::getColumnListing($selectedTable);

//         $sortField = request('sort_field');
//         $sortOrder = request('sort_order', 'asc');


//         if ($sortField && in_array($sortField, $columns)) {
//             $rows = DB::table($selectedTable)->orderBy($sortField, $sortOrder)->paginate(20);
//         } else {
//             $rows = DB::table($selectedTable)->paginate(20);
//         }
//     }

//     return view('content.dynamic.dynamic', [
//         'tableNames'     => $filteredTableNames,
//         'selectedTable'  => $selectedTable,
//         'columns'        => $columns,
//         'rows'           => $rows
//     ]);
// }

}