<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionService $permissionService
    ) {}

    public function index()
    {
        return view('backend.permissions.index');
    }

    public function datatable()
    {
        $query = $this->permissionService->getForDataTable();
        return DataTables::of($query)->addIndexColumn()->make(true);
    }

    public function form($id = 0)
    {
        $permission = null;
        if ($id > 0) {
            $permission = $this->permissionService->findPermissionById($id);
        }

        return view('backend.permissions.form', compact('permission', 'id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|unique:permissions,name',
            'display_name' => 'required|string',
            'description'  => 'nullable|string',
        ]);

        $this->permissionService->createPermission($validated);

        return response()->json(['message' => 'Permission created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'         => 'required|string|unique:permissions,name,' . $id,
            'display_name' => 'required|string',
            'description'  => 'nullable|string',
        ]);

        $this->permissionService->updatePermission($id, $validated);

        return response()->json(['message' => 'Permission updated successfully']);
    }

    public function destroy($id)
    {
        $this->permissionService->deletePermission($id);

        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
