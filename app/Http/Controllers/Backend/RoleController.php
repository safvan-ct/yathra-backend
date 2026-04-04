<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService,
        protected PermissionService $permissionService
    ) {}

    public function index()
    {
        return view('backend.roles.index');
    }

    public function datatable()
    {
        $query = $this->roleService->getForDataTable();
        return DataTables::of($query)->addIndexColumn()->make(true);
    }

    public function form($id = 0)
    {
        $role = null;
        if ($id > 0) {
            $role = $this->roleService->findRoleById($id);
        }

        $allPermissions = $this->permissionService->getAllPermissions();

        $groupedPermissions = $allPermissions->groupBy(function ($item) {
            $parts = preg_split('/[_-]/', $item->name);
            return count($parts) > 1 ? ucfirst($parts[0]) : 'General';
        });

        return view('backend.roles.form', compact('role', 'id', 'groupedPermissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|unique:roles,name',
            'display_name'  => 'required|string',
            'description'   => 'nullable|string',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $this->roleService->createRole($validated);

        return response()->json(['message' => 'Role created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', Rule::unique('roles', 'name')->ignore($id)],
            'display_name'  => 'required|string',
            'description'   => 'nullable|string',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $this->roleService->updateRole($id, $validated);

        return response()->json(['message' => 'Role updated successfully']);
    }

    public function destroy($id)
    {
        $this->roleService->deleteRole($id);

        return response()->json(['message' => 'Role deleted successfully']);
    }
}
