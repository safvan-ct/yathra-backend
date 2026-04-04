<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\CityService;
use App\Services\StationService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StationController extends Controller
{
    public function __construct(
        protected StationService $stationService,
        protected CityService $cityService
    ) {}

    public function index()
    {
        $cities = $this->cityService->list([], 1000)->getCollection();
        return view('backend.locations.station.index', compact('cities'));
    }

    public function search(Request $request)
    {
        $term     = $request->get('q');
        $stations = $this->stationService->list(['search' => $term], 50, true)->getCollection();

        $results = $stations->map(function ($station) {
            $cityName = $station->city ? $station->city->name : 'N/A';
            return [
                'value' => $station->id,
                'label' => $station->name . " ($cityName)",
            ];
        });

        return response()->json($results);
    }

    public function datatable(Request $request)
    {
        $filters = [];
        if ($request->filled('filter')) {
            $filters['city_id'] = $request->filter;
        }

        $query = $this->stationService->list($filters, 1000)->getCollection();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('city_name', function ($row) {
                return $row->city->name ?? 'N/A';
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-primary edit-btn" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function toggleStatus(Request $request, $id)
    {
        $column = $request->get('column', 'is_active');
        $this->stationService->toggleStatus($id, $column);

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function form($id = 0)
    {
        $station = null;
        if ($id > 0) {
            $station = $this->stationService->get($id);
        }

        $cities = $this->cityService->list([], 500)->getCollection();

        return view('backend.locations.station.form', compact('station', 'id', 'cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string',
            'local_name' => 'nullable|string',
            'city_id'    => 'required|exists:cities,id',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
        ]);

        $this->stationService->create($validated);

        return response()->json(['message' => 'Station created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'       => 'required|string',
            'local_name' => 'nullable|string',
            'city_id'    => 'required|exists:cities,id',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
        ]);

        $this->stationService->update($id, $validated);

        return response()->json(['message' => 'Station updated successfully']);
    }

    public function destroy($id)
    {
        $this->stationService->delete($id);

        return response()->json(['message' => 'Station deleted successfully']);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file    = $request->file('file');
        $csvData = file_get_contents($file);
        $rows    = array_map('str_getcsv', explode("\n", $csvData));
        $header  = array_shift($rows);

        $data = [];
        foreach ($rows as $row) {
            if (count($row) == count($header)) {
                $data[] = array_combine($header, $row);
            }
        }

        $results = $this->stationService->validateImport($data);

        return view('backend.locations.import_preview', [
            'type'    => 'Station',
            'valid'   => $results['valid'],
            'invalid' => $results['invalid'],
            'headers' => $header,
        ]);
    }

    public function importCommit(Request $request)
    {
        $data = json_decode($request->input('data'), true);
        if (empty($data)) {
            return redirect()->back()->with('error', 'No valid data to import');
        }

        $this->stationService->bulkStore($data);

        return redirect()->route('backend.stations.index')->with('success', count($data) . ' Stations imported successfully');
    }
}
