<?php
namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log an activity.
     */
    public function log(
        string $action, ?string $actorType = null, ?int $actorId = null, ?string $modelType = null, ?int $modelId = null, ?array $changes = null
    ): ActivityLog {
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

    /**
     * Log user action.
     */
    public function logUserAction(string $action, int $userId, ?array $changes = null): ActivityLog
    {
        return $this->log($action, 'App\\Models\\User', $userId, null, null, $changes);
    }

    /**
     * Log staff action.
     */
    public function logStaffAction(string $action, int $staffId, ?array $changes = null): ActivityLog
    {
        return $this->log($action, 'App\\Models\\Staff', $staffId, null, null, $changes);
    }
}
