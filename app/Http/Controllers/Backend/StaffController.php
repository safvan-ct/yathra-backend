<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use App\Services\StaffService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    public function __construct(
        protected StaffService $staffService,
        protected RoleService $roleService
    ) {}

    public function index()
    {
        $roles = $this->roleService->getAllRoles();
        return view('backend.staffs.index', compact('roles'));
    }

    public function datatable(Request $request)
    {
        $roleId = $request->get('filter');
        $query  = $this->staffService->getForDataTable($roleId);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('roles', fn($staff) => $staff->roles->pluck('display_name')->implode(', '))
            ->make(true);
    }

    public function toggleStatus(Request $request, $id)
    {
        $column = $request->get('column', 'is_active');
        $this->staffService->toggleStatus($id, $column);

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function form($id = 0)
    {
        $staff = null;
        if ($id > 0) {
            $staff = $this->staffService->findStaffById($id);
        }

        $allRoles = $this->roleService->getAllRoles();

        return view('backend.staffs.form', compact('staff', 'id', 'allRoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:staff,email',
            'password' => 'required|string|min:8',
            'roles'    => 'required|array',
            'roles.*'  => 'exists:roles,id',
        ]);

        $this->staffService->createStaff($validated);

        return response()->json(['message' => 'Staff created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:staff,email,' . $id,
            'password' => 'nullable|string|min:8',
            'roles'    => 'required|array',
            'roles.*'  => 'exists:roles,id',
        ]);

        $this->staffService->updateStaff($id, $validated);

        return response()->json(['message' => 'Staff updated successfully']);
    }

    public function destroy($id)
    {
        $this->staffService->deleteStaff($id);

        return response()->json(['message' => 'Staff deleted successfully']);
    }
}
