<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends Model
{
    use HasFactory, SoftDeletes;

    public $dontLog = ['is_active'];

    protected $fillable = ['city_id', 'parent_id', 'name', 'code', 'local_name', 'lat', 'long', 'type', 'is_parent', 'is_active'];

    protected $casts = [
        'lat'  => 'decimal:8',
        'long' => 'decimal:8',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function localBody()
    {
        return $this->belongsTo(Station::class, 'parent_id', 'id');
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

    public function suggestions()
    {
        return $this->morphMany(Suggestion::class, 'suggestable');
    }
}
