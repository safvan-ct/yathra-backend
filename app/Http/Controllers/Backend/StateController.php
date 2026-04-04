<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\StateService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StateController extends Controller
{
    public function __construct(
        protected StateService $stateService
    ) {}

    public function index()
    {
        return view('backend.locations.state.index');
    }

    public function search(Request $request)
    {
        $term   = $request->get('q');
        $states = $this->stateService->list(['search' => $term], 50)->getCollection();

        $results = $states->map(function ($state) {
            return [
                'value' => $state->id,
                'label' => $state->name,
            ];
        });

        return response()->json($results);
    }

    public function datatable()
    {
        $query = $this->stateService->list([], 1000)->getCollection(); // Get all for datatable

        return DataTables::of($query)
            ->addIndexColumn()
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
        $this->stateService->toggleStatus($id, $column);

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function form($id = 0)
    {
        $state = null;
        if ($id > 0) {
            $state = $this->stateService->get($id);
        }

        return view('backend.locations.state.form', compact('state', 'id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|unique:states,name',
            'local_name' => 'nullable|string',
            'code'       => 'required|string|max:5|unique:states,code',
        ]);

        $this->stateService->create($validated);

        return response()->json(['message' => 'State created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'       => 'required|string|unique:states,name,' . $id,
            'local_name' => 'nullable|string',
            'code'       => 'required|string|max:5|unique:states,code,' . $id,
        ]);

        $this->stateService->update($id, $validated);

        return response()->json(['message' => 'State updated successfully']);
    }

    public function destroy($id)
    {
        $this->stateService->delete($id);

        return response()->json(['message' => 'State deleted successfully']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

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

        $results = $this->stateService->validateImport($data);

        return view('backend.locations.import_preview', [
            'type'    => 'State',
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

        $this->stateService->bulkStore($data);

        return redirect()->route('backend.states.index')->with('success', count($data) . ' States imported successfully');
    }
}
