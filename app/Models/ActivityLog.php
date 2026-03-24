<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['actor_type', 'actor_id', 'action', 'model_type', 'model_id', 'changes', 'ip_address', 'user_agent'])]
class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'changes' => 'json',
        ];
    }
}
