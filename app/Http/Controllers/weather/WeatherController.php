<?php

namespace App\Http\Controllers\weather;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Weather;
use Illuminate\Support\Carbon;

class WeatherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $sortField = $request->input('sort_field', 'city_mun_code');
        $sortOrder = $request->input('sort_order', 'asc');

        // dd($sortField, $sortOrder);

        // if (Auth::user()->user_type == 'Admin' || Auth::user()->user_type == 'User Type 1') {
            $query = DB::table('weather');
    

            if ($sortOrder !== 'none') {
                $query->orderBy($sortField, $sortOrder);
            }
            
            $weatherData = $query->paginate(5)->appends($request->except('page'));
         // Get all weather data
        //  $weatherData = DB::table('weather')->paginate(5);

         // Pass the data to the view
         return view('content.weather.weather', compact('weatherData', 'sortField', 'sortOrder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.weather.weather-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'city_mun_code' => 'required|string|max:255',
            'ave_min' => 'required|numeric',
            'ave_max' => 'required|numeric',
            'ave_mean' => 'required|string',
            'rainfall_mm' => 'required|string|max:255',
            'rainfall_description' => 'required|string|max:255',
            'cloud_cover' => 'required|string|max:255',
            'humidity' => 'required|numeric|min:0|max:100',
            'forecast_date' => 'required|date',
            'date_accessed' => 'required|date',
            'wind_mps' => 'required|numeric',
            'direction' => 'required|string|max:255',
        ]);
        // dd($validatedData);
    
        DB::beginTransaction();
        // dd('I am here');
        try {
            DB::table('weather')->insert([
                'city_mun_code' => $request->city_mun_code,
                'ave_min' => $request->ave_min,
                'ave_max' => $request->ave_max,
                'ave_mean' => $request->ave_mean,
                'rainfall_mm' => $request->rainfall_mm,
                'rainfall_description' => $request->rainfall_description,
                'cloud_cover' => $request->cloud_cover,
                'humidity' => $request->humidity,
                'forecast_date' => $request->forecast_date,
                'date_accessed' => $request->date_accessed,
                'wind_mps' => $request->wind_mps,
                'direction' => $request->direction,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    
            DB::commit();
    
            // Redirect with success message
            return redirect()->route('weather.index')->with('success', 'Weather data has been added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            dd($e->getMessage());
    
            // Redirect with error message
            return redirect()->back()->with('error', 'Failed to add weather data! Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $weather = DB::table('weather')->where('id', $id)->first();
        return view ('content.weather.weather-show', compact('weather'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $weather = DB::table('weather')->where('id', $id)->first();
        return view ('content.weather.weather-update', compact('weather'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd('I am here');
        $validateData = $request->validate([
            'city_mun_code' => 'required|string|max:255',
            'ave_min' => 'required|numeric',
            'ave_max' => 'required|numeric',
            'ave_mean' => 'required|string',
            'rainfall_mm' => 'required|string|max:255',
            'rainfall_description' => 'required|string|max:255',
            'cloud_cover' => 'required|string|max:255',
            'humidity' => 'required|numeric|min:0|max:100',
            'forecast_date' => 'required|date',
            'date_accessed' => 'required|date',
            'wind_mps' => 'required|numeric',
            'direction' => 'required|string|max:255',
           ]);
    
           DB::beginTransaction();
           try{
            DB::table('weather')
            ->where('id', $id)
            ->update([
                'city_mun_code' => $request->city_mun_code,
                'ave_min' => $request->ave_min,
                'ave_max' => $request->ave_max,
                'ave_mean' => $request->ave_mean,
                'rainfall_mm' => $request->rainfall_mm,
                'rainfall_description' => $request->rainfall_description,
                'cloud_cover' => $request->cloud_cover,
                'humidity' => $request->humidity,
                'forecast_date' => $request->forecast_date,
                'date_accessed' => $request->date_accessed,
                'wind_mps' => $request->wind_mps,
                'direction' => $request->direction,
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
    
            $msg = ['success', 'Weather Record is Updated!'];
            return redirect()->route('weather.index')->with('success', 'Weather Record is Updated!');;
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
        DB::table('weather')->where('id', $id)->delete();
        $msg = ['danger', 'Weather Record is Deleted!'];
        return redirect()->route('weather.index')->with('success', 'Weather Record is Deleted!');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
    
        if (!$query) {
            return response()->json([], 200);
        }
    
        DB::beginTransaction();
        try {
            $weatherinfo = DB::table('weather')
            ->where('city_mun_code', 'like', "%$query%")
            ->orWhere('ave_min', 'like', "%$query%")
            ->orWhere('ave_max', 'like', "%$query%")
            ->orWhere('ave_mean', 'like', "%$query%")
            ->orWhere('rainfall_mm', 'like', "%$query%")
            ->orWhereRaw('LOWER(rainfall_description) like ?', ['%' . strtolower($query) . '%'])
            // Apply case-insensitive search for cloud_cover
            ->orWhereRaw('LOWER(cloud_cover) like ?', ['%' . strtolower($query) . '%'])
            ->orWhere('humidity', 'like', "%$query%")
            ->orWhere('wind_mps', 'like', "%$query%")
            ->orWhereRaw('LOWER(direction) like ?', ['%' . strtolower($query) . '%'])
            ->paginate(5);
        
            DB::commit(); 
        } catch (\Exception $e) {
            DB::rollback(); 
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    
            return response()->json([
                'data' => $weatherinfo->items(),
                'links' => $weatherinfo->links('vendor.pagination.bootstrap-5')->render()
            ]);
            // dd($samples);
        
    }
}
