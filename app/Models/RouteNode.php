<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteNode extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'station_id',
        'stop_sequence',
        'distance_from_origin',
        'is_active',
    ];

    protected $casts = [
        'distance_from_origin' => 'decimal:2',
        'is_active'            => 'boolean',
    ];

    public function route()
    {
        return $this->belongsTo(TransitRoute::class, 'route_id');
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
