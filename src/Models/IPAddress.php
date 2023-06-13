<?php

namespace Duggz0r\WeatherPackage\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IPAddress extends Model
{
    use HasFactory;

    protected $table = 'ip_addresses';

    protected $fillable = [
        'ip_address',
        'lat',
        'lon',
        'status',
    ];

    public function scopeByIPAddress(Builder $query, string $ip): Builder
    {
        return $query->where('ip_address', $ip);
    }
}
