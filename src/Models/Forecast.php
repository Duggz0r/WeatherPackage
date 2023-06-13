<?php

namespace Duggz0r\WeatherPackage\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'location',
        'temperature',
        'description',
        'icon',
        'ip_address_id',
    ];

    public function ipAddress()
    {
        return $this->belongsTo(IPAddress::class);
    }
}
