<?php
namespace App\Models;

use App\Enums\RewardActivityType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReward extends Model
{
    protected $fillable = [
        'user_id',
        'points',
        'activity_type',
        'source_id',
    ];

    protected $casts = [
        'points'        => 'integer',
        'activity_type' => RewardActivityType::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function suggestion(): BelongsTo
    {
        return $this->belongsTo(Suggestion::class, 'source_id');
    }
}
