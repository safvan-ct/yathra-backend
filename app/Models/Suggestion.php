<?php
namespace App\Models;

use App\Enums\SuggestionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suggestion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'suggestable_type',
        'suggestable_id',
        'proposed_data',
        'status',
        'admin_id',
        'review_note',
    ];

    protected $casts = [
        'proposed_data' => 'array',
        'status'        => SuggestionStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'admin_id');
    }

    public function suggestable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reward(): HasOne
    {
        return $this->hasOne(UserReward::class, 'source_id');
    }
}
