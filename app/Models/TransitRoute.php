<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransitRoute extends Model
{
    use HasFactory, SoftDeletes;

    public $dontLog = ['is_active'];

    protected $table = 'routes';

    protected $fillable = [
        'origin_id',
        'destination_id',
        'route_code',
        'path_signature',
        'distance',
        'is_active',
    ];

    protected $casts = [
        'distance'  => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function origin()
    {
        return $this->belongsTo(Station::class, 'origin_id');
    }

    public function destination()
    {
        return $this->belongsTo(Station::class, 'destination_id');
    }

    public function nodes()
    {
        return $this->hasMany(RouteNode::class, 'route_id')->orderBy('stop_sequence');
    }

    public function trips()
    {
        return $this->hasMany(Trip::class, 'route_id');
    }

    public function suggestions()
    {
        return $this->morphMany(Suggestion::class, 'suggestable');
    }
}
