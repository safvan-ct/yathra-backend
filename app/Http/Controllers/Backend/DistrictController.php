<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\DistrictService;
use App\Services\StateService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DistrictController extends Controller
{
    public function __construct(
        protected DistrictService $districtService,
        protected StateService $stateService
    ) {}

    public function index()
    {
        $states = $this->stateService->list([], 1000)->getCollection();
        return view('backend.locations.district.index', compact('states'));
    }

    public function datatable(Request $request)
    {
        $filters = [];
        if ($request->filled('filter')) {
            $filters['state_id'] = $request->filter;
        }

        $query = $this->districtService->list($filters, 1000)->getCollection();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('state_name', function ($row) {
                return $row->state->name ?? 'N/A';
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
        $this->districtService->toggleStatus($id, $column);

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function form($id = 0)
    {
        $district = null;
        if ($id > 0) {
            $district = $this->districtService->get($id);
        }

        $states = $this->stateService->list([], 100)->getCollection();

        return view('backend.locations.district.form', compact('district', 'id', 'states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string',
            'local_name' => 'nullable|string',
            'code'       => 'required|string|max:5|unique:districts,code',
            'state_id'   => 'required|exists:states,id',
        ]);

        $this->districtService->create($validated);

        return response()->json(['message' => 'District created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'       => 'required|string',
            'local_name' => 'nullable|string',
            'code'       => 'required|string|max:5|unique:districts,code,' . $id,
            'state_id'   => 'required|exists:states,id',
        ]);

        $this->districtService->update($id, $validated);

        return response()->json(['message' => 'District updated successfully']);
    }

    public function destroy($id)
    {
        $this->districtService->delete($id);

        return response()->json(['message' => 'District deleted successfully']);
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

        $results = $this->districtService->validateImport($data);

        return view('backend.locations.import_preview', [
            'type'    => 'District',
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

        $this->districtService->bulkStore($data);

        return redirect()->route('backend.districts.index')->with('success', count($data) . ' Districts imported successfully');
    }
}
