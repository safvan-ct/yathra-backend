<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\OperatorService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OperatorController extends Controller
{
    public function __construct(
        protected OperatorService $operatorService
    ) {}

    public function index()
    {
        return view('backend.operators.index');
    }

    public function search(Request $request)
    {
        $term      = $request->get('q');
        $operators = $this->operatorService->query(['search' => $term])->limit(50)->get();

        $results = $operators->map(function ($operator) {
            return [
                'value' => $operator->id,
                'label' => $operator->name . ($operator->phone ? " ({$operator->phone})" : ''),
            ];
        });

        return response()->json($results);
    }

    public function datatable(Request $request)
    {
        $filters = [];

        $query = $this->operatorService->query($filters);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('bus_count', function ($row) {
                return $row->buses()->count();
            })
            ->addColumn('status', function ($row) {
                $checked = $row->is_active ? 'checked' : '';
                return '<div class="form-check form-switch cursor-pointer">
                            <input class="form-check-input" type="checkbox" ' . $checked . ' onclick="toggleActive(\'' . route('backend.operators.toggle-status', $row->id) . '\')">
                        </div>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-light-primary" onclick="CRUD.open(' . $row->id . ')"><i class="ti ti-edit"></i></button>
                            <button class="btn btn-sm btn-light-danger" onclick="CRUD.delete(' . $row->id . ')"><i class="ti ti-trash"></i></button>
                        </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function form($id = 0)
    {
        $operator = $id > 0 ? $this->operatorService->get($id) : null;
        return view('backend.operators.form', compact('operator', 'id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'type'      => 'required|string|in:Government,Private',
            'phone'     => 'required|string|max:20',
            'email'     => 'nullable|email|max:255',
            'address'   => 'nullable|string',
            'is_public' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $this->operatorService->create($validated);

        return response()->json(['message' => 'Operator created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'type'      => 'required|string|in:Government,Private',
            'phone'     => 'required|string|max:20',
            'email'     => 'nullable|email|max:255',
            'address'   => 'nullable|string',
            'is_public' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $this->operatorService->update($id, $validated);

        return response()->json(['message' => 'Operator updated successfully']);
    }

    public function toggleStatus(Request $request, $id)
    {
        $operator = $this->operatorService->get($id);
        $this->operatorService->update($id, ['is_active' => ! $operator->is_active]);

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function destroy($id)
    {
        $this->operatorService->delete($id);
        return response()->json(['message' => 'Operator deleted successfully']);
    }
}
