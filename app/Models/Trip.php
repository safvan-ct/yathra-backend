<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bus_id',
        'route_id',
        'departure_time',
        'arrival_time',
        'days_of_week',
        'status',
    ];

    protected $casts = [
        'days_of_week' => 'json',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(TransitRoute::class, 'route_id');
    }

    public function suggestions()
    {
        return $this->morphMany(Suggestion::class, 'suggestable');
    }
}
