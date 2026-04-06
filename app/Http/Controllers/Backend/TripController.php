<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\BusService;
use App\Services\TransitRouteService;
use App\Services\TripService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TripController extends Controller
{
    public function __construct(
        protected TripService $tripService,
        protected BusService $busService,
        protected TransitRouteService $routeService
    ) {}

    public function index(Request $request)
    {
        $busId = $request->query('bus_id');
        $bus   = $busId ? $this->busService->get($busId) : null;

        return view('backend.trips.index', compact('bus', 'busId'));
    }

    public function datatable(Request $request)
    {
        $filters = [];
        if ($request->filled('bus_id')) {
            $filters['bus_id'] = $request->bus_id;
        }

        $query = $this->tripService->list($filters, 1000)->getCollection();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('origin_name', function ($row) {
                return $row->route->origin->name ?? 'N/A';
            })
            ->addColumn('destination_name', function ($row) {
                return $row->route->destination->name ?? 'N/A';
            })
            ->addColumn('route_name', function ($row) {
                return $row->route->route_code ?? 'N/A';
            })
            ->addColumn('bus_number', function ($row) {
                return $row->bus->bus_number ?? 'N/A';
            })
            ->addColumn('departure', function ($row) {
                return Carbon::parse($row->departure_time)->format('h:i a');
            })
            ->addColumn('arrival', function ($row) {
                return Carbon::parse($row->arrival_time)->format('h:i a');
            })
            ->addColumn('status', function ($row) {
                $checked = $row->is_active ? 'checked' : '';
                return '<div class="form-check form-switch cursor-pointer">
                            <input class="form-check-input" type="checkbox" ' . $checked . ' onclick="toggleActive(\'' . route('backend.trips.toggle-status', $row->id) . '\')">
                        </div>';
            })
            ->addColumn('action', function ($row) {
                $busQuery = $row->bus_id ? '?bus_id=' . $row->bus_id : '';
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-light-primary" onclick="CRUD.open(' . $row->id . ', \'' . $busQuery . '\')"><i class="ti ti-edit"></i></button>
                            <button class="btn btn-sm btn-light-danger" onclick="CRUD.delete(' . $row->id . ')"><i class="ti ti-trash"></i></button>
                        </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function form(Request $request, $id = 0)
    {
        $trip   = $id > 0 ? $this->tripService->get($id) : null;
        $busId  = $request->query('bus_id', $trip?->bus_id);
        $buses  = $this->busService->list(['is_active' => true], 1000)->getCollection();
        $routes = $this->routeService->list(['is_active' => true], 1000)->getCollection();

        return view('backend.trips.form', compact('trip', 'id', 'busId', 'buses', 'routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id'         => 'required|exists:buses,id',
            'route_id'       => 'required|exists:routes,id',
            'departure_time' => 'required',
            'arrival_time'   => 'required',
            //'days_of_week'   => 'nullable|array',
            'is_active'      => 'boolean',
        ]);

        $validated['days_of_week'] = [1, 1, 1, 1, 1, 1, 1];

        $this->tripService->create($validated);

        return response()->json(['message' => 'Trip created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'bus_id'         => 'required|exists:buses,id',
            'route_id'       => 'required|exists:routes,id',
            'departure_time' => 'required',
            'arrival_time'   => 'required',
            //'days_of_week'   => 'nullable|array',
            'is_active'      => 'boolean',
        ]);

        $this->tripService->update($id, $validated);

        return response()->json(['message' => 'Trip updated successfully']);
    }

    public function toggleStatus(Request $request, $id)
    {
        $trip = $this->tripService->get($id);
        $this->tripService->update($id, ['is_active' => ! $trip->is_active]);

        return response()->json(['message' => 'Trip status updated successfully']);
    }

    public function destroy($id)
    {
        $this->tripService->delete($id);
        return response()->json(['message' => 'Trip deleted successfully']);
    }
}
