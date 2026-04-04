<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bus extends Model
{
    use HasFactory, SoftDeletes;

    protected array $dontLog = [];

    protected $fillable = [
        'operator_id',
        'bus_name',
        'bus_number',
        'bus_number_code',
        'category',
        'bus_color',
        'total_seats',
        'is_active',
    ];

    protected $casts = [
        'total_seats' => 'integer',
        'is_active'   => 'boolean',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function suggestions()
    {
        return $this->morphMany(Suggestion::class, 'suggestable');
    }
}
