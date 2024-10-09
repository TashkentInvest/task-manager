<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoleGroup;
use Spatie\Permission\Models\Role;
use App\Services\LogWriter;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Requests\Employee\ApplicationStoreRequest;
use App\Helpers\CommonHelpers;
use Spatie\Permission\Models\Permission;


class EmployeeController extends Controller
{
    public function index()
    {
        abort_if_forbidden('employee.show');
        $users = User::with('roles')->where('id','!=',auth()->user()->id)->paginate(10);
        return view('pages.employee.index', compact('users'));
    }

    public function toggleProductActivation($id)
    {
        $user = User::where('id',$id)->first();
        $user->is_online = $user->is_online === 1 ? 0 : 1;
        $user->save();
        return [
            'is_active' => $user->is_online
        ];
    }

    public function add()
    {
        abort_if_forbidden('employee.add');
        $roles = RoleGroup::where('title', '<>', 'Super Admin')->get()->all();
        return view('pages.employee.add', compact('roles'));
    }

    private function createPermissionsAndRoles()
    {
        // Define your permissions and roles creation logic here
        // For example:
        $permissions = ["permission.show","monitoring.show", "permission.edit", "permission.add", /* other permissions */];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    }

    public function create(ApplicationStoreRequest $request)
    {
        $validated = $request->validated();
        $existingPhone = User::where('phone', $validated['phone'])->first();
        if ($existingPhone) {
            return redirect()->back()->withInput()->withErrors(['phone' => 'Phone already exists']);
        }
        
        $user = new User;
        $user->name = $validated['name'];
        $user->phone = CommonHelpers::removeHyphens($validated['phone']);
        $user->email = CommonHelpers::createUserEmail($validated['name']);
        $user->password = bcrypt(CommonHelpers::removeHyphens($validated['birth_date']));
        $user->birth_date = date('Y-m-d', strtotime($validated['birth_date']));
        $user->hire_date = date('Y-m-d', strtotime($validated['hire_date']));
        $user->save();
        
        // Assign the role to the user
        $roleId = $validated['role_id'];
        $role = Role::find($roleId);
    
        if ($role) {
            $user->assignRole($role);
        }
    
        $roleName = $role ? $role->name : null;
        if (in_array($roleName, ['Admin', 'Manager', 'Employee'])) {
            $permissions = Permission::all();
            $user->syncPermissions($permissions);
            $this->createPermissionsAndRoles();
        }
    
        $activity = "\nCreated by: " . json_encode(auth()->user()) .
            "\nNew User: " . json_encode($user) .
            "\nRole: " . ($role ? $role->name : 'N/A');
    
        LogWriter::user_activity($activity, 'AddingUsers');
    
        return redirect()->route('employeeIndex');
    }
    

    public function edit($id)
    {
        abort_if_forbidden('employee.edit');
        $user = User::with('roles')->find($id);
        $roles = RoleGroup::where('title', '<>', 'Super Admin')->get()->all();
        return view('pages.employee.edit', compact('user', 'roles'));
    }

    public function update(ApplicationStoreRequest $request, $id)
    {
        $user = User::find($id);
        $validated = $request->validated();
        
        $user->name = $validated['name'];
        $user->phone = CommonHelpers::removeHyphens($validated['phone']);
        $user->email = CommonHelpers::createUserEmail($validated['name']);
        $user->password = bcrypt(CommonHelpers::removeHyphens($validated['birth_date']));
        $user->birth_date = date('Y-m-d', strtotime($validated['birth_date']));
        $user->hire_date = date('Y-m-d', strtotime($validated['hire_date']));
        $user->save();
        $roleId = $validated['role_id']; // Assuming 'role_id' is present in the validated data
        $role = Role::find($roleId);

        if ($role) {
            $user->assignRole($role);
        }
        return redirect()->route('employeeIndex');
    }

    public function destroy($id)
    {
        abort_if_forbidden('employee.delete');

        // Retrieve the user instance
        $user = User::find($id);

        // Check if the user exists
        if (!$user) {
            // Handle the case where the user with the given ID is not found
            return redirect()->back()->withErrors(['error' => 'User not found']);
        }

        // Delete the user
        $deletedUser = User::destroy($id);

        // Check if the user has the 'Super Admin' role
        if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
            message_set("У вас нет разрешения на редактирование администратора", 'error', 5);
            return redirect()->back();
        }

        // Delete role and permission associations
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        DB::table('model_has_permissions')->where('model_id', $id)->delete();

        // Logging
        $deletedBy = logObj(auth()->user());
        $userLog = logObj($user);
        $message = "\nDeleted By: $deletedBy\nDeleted user: $userLog";
        LogWriter::user_activity($message, 'DeletingUsers');

        message_set("User deleted",'success',1);
        return redirect()->back();
    }

}
