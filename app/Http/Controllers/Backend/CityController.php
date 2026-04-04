<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\CityService;
use App\Services\DistrictService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    public function __construct(
        protected CityService $cityService,
        protected DistrictService $districtService
    ) {}

    public function index()
    {
        $districts = $this->districtService->list([], 1000)->getCollection();
        return view('backend.locations.city.index', compact('districts'));
    }

    public function datatable(Request $request)
    {
        $filters = [];
        if ($request->filled('filter')) {
            $filters['district_id'] = $request->filter;
        }

        $query = $this->cityService->list($filters, 1000)->getCollection();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('district_name', function ($row) {
                return $row->district->name ?? 'N/A';
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
        $this->cityService->toggleStatus($id, $column);

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function form($id = 0)
    {
        $city = null;
        if ($id > 0) {
            $city = $this->cityService->get($id);
        }

        $districts = $this->districtService->list([], 200)->getCollection();

        return view('backend.locations.city.form', compact('city', 'id', 'districts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string',
            'local_name'  => 'nullable|string',
            'code'        => 'required|string|max:5|unique:cities,code',
            'district_id' => 'required|exists:districts,id',
        ]);

        $this->cityService->create($validated);

        return response()->json(['message' => 'City created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string',
            'local_name'  => 'nullable|string',
            'code'        => 'required|string|max:5|unique:cities,code,' . $id,
            'district_id' => 'required|exists:districts,id',
        ]);

        $this->cityService->update($id, $validated);

        return response()->json(['message' => 'City updated successfully']);
    }

    public function destroy($id)
    {
        $this->cityService->delete($id);

        return response()->json(['message' => 'City deleted successfully']);
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

        $results = $this->cityService->validateImport($data);

        return view('backend.locations.import_preview', [
            'type'    => 'City',
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

        $this->cityService->bulkStore($data);

        return redirect()->route('backend.cities.index')->with('success', count($data) . ' Cities imported successfully');
    }
}
