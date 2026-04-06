<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    public $dontLog = ['is_active'];

    protected $fillable = ['district_id', 'name', 'local_name', 'code', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
