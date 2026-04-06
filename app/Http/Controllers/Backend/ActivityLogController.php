<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    public function index()
    {
        return view('backend.activity-logs.index');
    }

    public function datatable(Request $request)
    {
        $query = ActivityLog::query()->latest();

        if ($request->has('actor_type') && ! empty($request->actor_type)) {
            $query->where('actor_type', $request->actor_type);
        }

        if ($request->has('action') && ! empty($request->action)) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        if ($request->has('model_type') && ! empty($request->model_type)) {
            $query->where('model_type', $request->model_type);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i:s');
            })
            ->addColumn('actor', function ($row) {
                $type = class_basename($row->actor_type);
                return "{$type} (#{$row->actor_id})";
            })
            ->addColumn('model', function ($row) {
                if (! $row->model_type) {
                    return 'N/A';
                }

                $type = class_basename($row->model_type);
                return "{$type} (#{$row->model_id})";
            })
            ->addColumn('action_btn', function ($row) {
                return '<button class="btn btn-sm btn-info view-btn" data-id="' . $row->id . '">
                            <i class="ti ti-eye"></i> View
                        </button>';
            })
            ->rawColumns(['action_btn'])
            ->make(true);
    }

    public function show($id)
    {
        $log = ActivityLog::findOrFail($id);

        $actorName = 'Unknown';
        if ($log->actor_type && $log->actor_id) {
            $actor     = $log->actor_type::find($log->actor_id);
            $actorName = $actor ? ($actor->name ?? $actor->username ?? 'N/A') : 'Deleted User';
        }

        $modelName = 'N/A';
        if ($log->model_type && $log->model_id) {
            $model     = $log->model_type::find($log->model_id);
            $modelName = $model ? ($model->name ?? $model->title ?? "ID: {$log->model_id}") : "Deleted #{$log->model_id}";
        }

        return view('backend.activity-logs.show', compact('log', 'actorName', 'modelName'));
    }
}
