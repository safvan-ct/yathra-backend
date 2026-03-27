<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['city_id', 'name', 'code', 'locale_name', 'lat', 'long', 'type', 'is_active'];

    protected $casts = [
        'lat'  => 'decimal:8',
        'long' => 'decimal:8',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function originRoutes()
    {
        return $this->hasMany(TransitRoute::class, 'origin_id');
    }

    public function destinationRoutes()
    {
        return $this->hasMany(TransitRoute::class, 'destination_id');
    }

    public function routeNodes()
    {
        return $this->hasMany(RouteNode::class);
    }
}
