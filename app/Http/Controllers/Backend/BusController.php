<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\BusService;
use App\Services\OperatorService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BusController extends Controller
{
    public function __construct(
        protected BusService $busService,
        protected OperatorService $operatorService
    ) {}

    public function index()
    {
        return view('backend.buses.index');
    }

    public function datatable(Request $request)
    {
        $filters = [];
        if ($request->filled('operator_id')) {
            $filters['operator_id'] = $request->operator_id;
        }

        $query = $this->busService->list($filters, 1000)->getCollection();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('operator_name', function ($row) {
                return $row->operator->name ?? 'N/A';
            })
            ->addColumn('status', function ($row) {
                $checked = $row->is_active ? 'checked' : '';
                return '<div class="form-check form-switch cursor-pointer">
                            <input class="form-check-input" type="checkbox" ' . $checked . ' onclick="toggleActive(\'' . route('backend.buses.toggle-status', $row->id) . '\')">
                        </div>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-light-success trips-btn" data-id="' . $row->id . '" title="Manage Trips"><i class="ti ti-calendar"></i></button>
                            <button class="btn btn-sm btn-light-primary" onclick="CRUD.open(' . $row->id . ')"><i class="ti ti-edit"></i></button>
                            <button class="btn btn-sm btn-light-danger" onclick="CRUD.delete(' . $row->id . ')"><i class="ti ti-trash"></i></button>
                        </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function form($id = 0)
    {
        $bus       = $id > 0 ? $this->busService->get($id) : null;
        $operators = collect();
        if ($bus && $bus->operator) {
            $operators->push($bus->operator);
        }
        return view('backend.buses.form', compact('bus', 'id', 'operators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'operator_id' => 'required|exists:operators,id',
            'bus_name'    => 'required|string|max:255',
            'bus_number'  => 'required|string|max:5|unique:buses,bus_number',
            'category'    => 'required|string|in:Sleeper,Seater,AC,Ordinary',
            'bus_color'   => 'required|string|in:Blue,Green,Red,White',
            'total_seats' => 'required|integer|min:1',
            'is_active'   => 'boolean',
        ]);

        $this->busService->create($validated);

        return response()->json(['message' => 'Bus created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'operator_id' => 'required|exists:operators,id',
            'bus_name'    => 'required|string|max:255',
            'bus_number'  => 'required|string|max:5|unique:buses,bus_number,' . $id,
            'category'    => 'required|string|in:Sleeper,Seater,AC,Ordinary',
            'bus_color'   => 'required|string|in:Blue,Green,Red,White',
            'total_seats' => 'required|integer|min:1',
            'is_active'   => 'boolean',
        ]);

        $this->busService->update($id, $validated);

        return response()->json(['message' => 'Bus updated successfully']);
    }

    public function toggleStatus(Request $request, $id)
    {
        $bus = $this->busService->get($id);
        $this->busService->update($id, ['is_active' => ! $bus->is_active]);

        return response()->json(['message' => 'Bus status updated successfully']);
    }

    public function destroy($id)
    {
        $this->busService->delete($id);
        return response()->json(['message' => 'Bus deleted successfully']);
    }
}
