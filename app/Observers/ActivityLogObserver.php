<?php
namespace App\Observers;

use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class ActivityLogObserver
{
    public function created(Model $model): void
    {
        $data = $this->getModelAttributes($model);
        $this->log($model, 'created', ['new' => $data]);
    }

    public function updated(Model $model): void
    {
        $changes = $this->getChangedAttributes($model);
        if (! empty($changes)) {
            $this->log($model, 'updated', $changes);
        }
    }

    public function deleted(Model $model): void
    {
        $data = $this->getModelAttributes($model);
        $this->log($model, 'deleted', ['old' => $data]);
    }

    public function forceDeleted(Model $model): void
    {
        $data = $this->getModelAttributes($model);
        $this->log($model, 'force_deleted', ['old' => $data]);
    }

    protected function getChangedAttributes(Model $model): array
    {
        $changes  = [];
        $dirty    = $model->getChanges();
        $original = $model->getOriginal();

        $dontLog = (property_exists($model, 'dontLog') && is_array($model->dontLog)) ? $model->dontLog : [];
        $dontLog = array_merge($dontLog, ['updated_at', 'created_at', 'deleted_at']);

        foreach ($dirty as $key => $newValue) {
            if (in_array($key, $dontLog)) {
                continue;
            }

            $changes[$key] = ['old' => $original[$key] ?? null, 'new' => $newValue];
        }

        return $changes;
    }

    protected function getModelAttributes(Model $model): array
    {
        $attributes = $model->getAttributes();

        $dontLog = (property_exists($model, 'dontLog') && is_array($model->dontLog)) ? $model->dontLog : [];
        $dontLog = array_merge($dontLog, ['updated_at', 'created_at', 'deleted_at', 'id']);

        return array_filter($attributes, function ($key) use ($dontLog) {
            return ! in_array($key, $dontLog);
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function log(Model $model, string $action, ?array $changes = null): void
    {
        $service = App::make(ActivityLogService::class);
        $service->log($action, get_class($model), $model->getKey(), $changes);
    }
}
