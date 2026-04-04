<?php
namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public function log(string $action, ?string $modelType = null, ?int $modelId = null, ?array $changes = null): ActivityLog
    {
        $actorId   = null;
        $actorType = null;

        if (Auth::guard('staff')->check()) {
            $actorId   = Auth::guard('staff')->id();
            $actorType = \App\Models\Staff::class;
        } elseif (Auth::guard('web')->check()) {
            $actorId   = Auth::guard('web')->id();
            $actorType = \App\Models\User::class;
        }

        return ActivityLog::create([
            'actor_type' => $actorType,
            'actor_id'   => $actorId,
            'action'     => $action,
            'model_type' => $modelType,
            'model_id'   => $modelId,
            'changes'    => $changes,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
