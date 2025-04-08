<?php

namespace App\Http\Controllers\table1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\History;

class Table1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.table1.table1-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validateData = $request->validate([
            'track_uri' => 'required',
            't_time' => 'required',
            'platform' => 'required',
            'ms_played' => 'required',
            'track_name' => 'required',
            'artist_name' => 'required',
            'album_name' => 'required',
            'reason_start' => 'required',
            'reason_end' => 'required',
            'shuffle' => 'required',
            'skipped' => 'required',
           ]);
        //    dd($validateData);
    
           DB::beginTransaction();
           try{
           DB::table('histories')->insert([
                'track_uri' => $request->track_uri,
                't_time' => $request->t_time,
                'platform' => $request->platform,
                'ms_played' => $request->ms_played,
                'track_name' => $request->track_name,
                'artist_name' => $request->artist_name,
                'album_name' => $request->album_name,
                'reason_start' => $request->reason_start,
                'reason_end' => $request->reason_end,
                'shuffle' => $request->shuffle,
                'skipped' => $request->skipped,
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
    
            $msg = ['success', 'Sample Record is Added!'];
            return redirect()->route('dashboard-analytics')->with('success', 'Sample Record is Added!');
           }catch(\Exception $e){
            DB::rollBack();
            // dd($e);
            $msg = ['danger', 'Failed to Add Record!'];
            return redirect()->back()->with(['msg'=>$msg]);
           }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $table1 = DB::table('histories')->where('id', $id)->first();
        return view ('content.table1.table1-update', compact('table1'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $validateData = $request->validate([
            'track_uri' => 'required',
            't_time' => 'required',
            'platform' => 'required',
            'ms_played' => 'required',
            'track_name' => 'required',
            'artist_name' => 'required',
            'album_name' => 'required',
            'reason_start' => 'required',
            'reason_end' => 'required',
            'shuffle' => 'required',
            'skipped' => 'required',
           ]);
    
           DB::beginTransaction();
           try{
            DB::table('histories')
            ->where('id', $id)
            ->update([
                'track_uri' => $request->track_uri,
                't_time' => $request->t_time,
                'platform' => $request->platform,
                'ms_played' => $request->ms_played,
                'track_name' => $request->track_name,
                'artist_name' => $request->artist_name,
                'album_name' => $request->album_name,
                'reason_start' => $request->reason_start,
                'reason_end' => $request->reason_end,
                'shuffle' => $request->shuffle,
                'skipped' => $request->skipped,
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
    
            $msg = ['success', 'Sample Record is Updated!'];
            return redirect()->route('dashboard-analytics')->with('success', 'Sample Record is Updated!');;
           }catch(\Exception $e){
            DB::rollBack();
            // dd($e);
            $msg = ['danger', 'Failed to Update Record!'];
            return redirect()->back()->with(['msg'=>$msg]);
           }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('histories')->where('id', $id)->delete();
        $msg = ['danger', 'Record is Deleted!'];
        return redirect()->route('dashboard-analytics')->with('success', 'Sample Record is Deleted!');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
    
        if (!$query) {
            return response()->json([], 200);
        }
    
        DB::beginTransaction();
        try {
            $samples = DB::table('histories')
                ->where('track_uri', 'like', "%$query%")
                ->orWhere('track_name', 'like', "%$query%")
                ->orWhere('artist_name', 'like', "%$query%")
                ->paginate(5);
        
            DB::commit(); 
        } catch (\Exception $e) {
            DB::rollback(); 
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    
            return response()->json([
                'data' => $samples->items(),
                'links' => $samples->links('vendor.pagination.bootstrap-5')->render()
            ]);
            // dd($samples);
        
    }
}
