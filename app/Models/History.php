<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'track_uri',
        't_time',
        'platform',
        'ms_played',
        'track_name',
        'artist_name',
        'album_name',
        'reason_start',
        'reason_end',
        'shuffle',
        'skipped',
    ];
}
