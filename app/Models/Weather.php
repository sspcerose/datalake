<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Weather extends Model
{
    use HasFactory;
    
    protected $fillable = [
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
    ];
}
