<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\StationService;
use App\Services\TransitRouteService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransitRouteController extends Controller
{
    public function __construct(
        protected TransitRouteService $routeService,
        protected StationService $stationService
    ) {}

    public function index()
    {
        return view('backend.routes.index');
    }

    public function datatable(Request $request)
    {
        $filters = $request->only(['origin_id', 'destination_id', 'is_active', 'search']);
        $query   = $this->routeService->list($filters, 1000)->getCollection();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('origin_name', fn($row) => $row->origin->name ?? 'N/A')
            ->addColumn('destination_name', fn($row) => $row->destination->name ?? 'N/A')
            ->addColumn('stops_count', fn($row) => $row->nodes->count())
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-primary edit-btn" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function form($id = 0)
    {
        $route    = null;
        $stations = collect();

        if ($id > 0) {
            $route = $this->routeService->get($id);
            if ($route) {
                $stations->push($route->origin);
                $stations->push($route->destination);
                foreach ($route->nodes as $node) {
                    if ($node->station) {
                        $stations->push($node->station);
                    }
                }
                $stations = $stations->unique('id');
            }
        }

        return view('backend.routes.form', compact('route', 'id', 'stations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'origin_id'                    => 'required|exists:stations,id',
            'destination_id'               => 'required|exists:stations,id|different:origin_id',
            'variant'                      => 'required|string|max:50',
            'distance'                     => 'required|numeric|min:0',
            'is_active'                    => 'required|boolean',
            'nodes'                        => 'required|array|min:2',
            'nodes.*.station_id'           => 'required|exists:stations,id',
            'nodes.*.stop_sequence'        => 'required|integer|min:1',
            'nodes.*.distance_from_origin' => 'required|numeric|min:0',
            'nodes.*.is_active'            => 'nullable|boolean',
        ]);

        $this->routeService->create($validated);

        return response()->json(['message' => 'Route created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'origin_id'                    => 'required|exists:stations,id',
            'destination_id'               => 'required|exists:stations,id|different:origin_id',
            'variant'                      => 'required|string|max:50',
            'distance'                     => 'required|numeric|min:0',
            'is_active'                    => 'required|boolean',
            'nodes'                        => 'required|array|min:2',
            'nodes.*.id'                   => 'nullable|integer',
            'nodes.*.station_id'           => 'required|exists:stations,id',
            'nodes.*.stop_sequence'        => 'required|integer|min:1',
            'nodes.*.distance_from_origin' => 'required|numeric|min:0',
            'nodes.*.is_active'            => 'nullable|boolean',
        ]);

        $this->routeService->update($id, $validated);

        return response()->json(['message' => 'Route updated successfully']);
    }

    public function destroy($id)
    {
        $this->routeService->delete($id);

        return response()->json(['message' => 'Route deleted successfully']);
    }

    public function toggleStatus(Request $request, $id)
    {
        $column = $request->get('column', 'is_active');
        $this->routeService->toggleStatus($id, $column);

        return response()->json(['message' => 'Status updated successfully']);
    }
}
