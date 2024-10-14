<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Services\LogWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;



class UserController extends Controller
{
    // List of users
    public function index()
    {
        abort_if_forbidden('user.show');
        $users = User::where('id','!=',auth()->user()->id)->get();
        return view('pages.user.index',compact('users'));
    }

    // user add page
    public function add()
    {
        abort_if_forbidden('user.add');
        if (auth()->user()->hasRole('Super Admin'))
            $roles = Role::all();
        else
            $roles = Role::where('name','!=','Super Admin')->get();

        return view('pages.user.add',compact('roles'));
    }

    // user create

    private function createPermissionsAndRoles()
    {
        // Define your permissions and roles creation logic here
        // For example:
        $permissions = ["permission.show", "permission.edit", "permission.add", /* other permissions */];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    }

    public function create(Request $request)
    {
        abort_if_forbidden('user.add');
    
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['required', 'array'],
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        $role = $request->roles[0] ?? null;
    
        if (in_array($role, ['Admin', 'Manager', 'Employee'])) {
            $permissions = Permission::all();
            $user->syncPermissions($permissions);
            $this->createPermissionsAndRoles();
        }
    
        if ($role) {
            $user->assignRole($role);
        }
    
        $activity = "\nCreated by: " . json_encode(auth()->user()) .
            "\nNew User: " . json_encode($user) .
            "\nRoles: " . implode(", ", $request->roles ?? []);
    
        LogWriter::user_activity($activity, 'AddingUsers');
    
        return redirect()->route('userIndex');
    }
    
 
    // user edit page
    public function edit($id)
    {
        abort_if((!auth()->user()->can('user.edit') && auth()->id() != $id),403);

        $user = User::find($id);

        if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin'))
        {
            message_set("У вас нет разрешения на редактирование администратора",'error',5);
            return redirect()->back();
        }

        if (auth()->user()->hasRole('Super Admin'))
            $roles = Role::all();
        else
            $roles = Role::where('name','!=','Super Admin')->get();

        return view('pages.user.edit',compact('user','roles'));
    }

    // update user dates
    public function update(Request $request, $id)
    {
        // Check if the user has permission to update the user data
        abort_if((!auth()->user()->can('user.edit') && auth()->id() != $id),403);
    
        // Validate the request data
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string'],
            'birth_date' => ['nullable', 'date'],
            'hire_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string'],
            'roles' => ['nullable', 'array'], // Ensure roles are an array
            'avatar' => ['nullable', 'image', 'max:2048'] // max:2048 is for 2MB max size, you can adjust as needed

        ]);
    
        // Find the user by ID
        $user = User::findOrFail($id);
    
        // Update user fields
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->birth_date = $request->input('birth_date');
        $user->hire_date = $request->input('hire_date');
        $user->location = $request->input('location');

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public'); 
            $user->avatar = $avatarPath;
        }
    
        // Check if a new password is provided and hash it
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
    
        // Save the updated user
        $user->save();
    
        // Sync user roles0
        if(auth()->user()->roles[0]->name != 'Employee'){
                if ($request->filled('roles')) {
                    $user->syncRoles($request->input('roles'));
                } else {
                    $user->roles()->detach(); 
                }
        }
    
        // Log user activity
        $activity = "Updated by: " . auth()->user()->name . " (ID: " . auth()->id() . ")";
        LogWriter::user_activity($activity, 'EditingUsers');
    
        // Redirect based on user permissions
        return redirect()->route('userProfile');
    }
    
    // delete user by id
    public function destroy($id)
    {
        abort_if_forbidden('user.delete');

        $user = User::destroy($id);
        if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin'))
        {
            message_set("У вас нет разрешения на редактирование администратора",'error',5);
            return redirect()->back();
        }
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        DB::table('model_has_permissions')->where('model_id',$id)->delete();
        $deleted_by = logObj(auth()->user());
        $user_log = logObj(User::find($id));
        $message = "\nDeleted By: $deleted_by\nDeleted user: $user_log";
        LogWriter::user_activity($message,'DeletingUsers');
        return redirect()->route('userIndex');
    }

    public function setTheme(Request $request,$id)
    {
        $this->validate($request,[
            'theme' => 'required'
        ]);

        if (!in_array($request->theme,['default','dark','light']))
        {
            message_set("There is no theme like $request->theme!",'warning',3);
        }
        else
        {
            $user = User::findOrFail($id);
            $user->setTheme($request->theme);
            message_set("Theme `$request->theme` is installed!",'success',1);
        }

        return redirect()->back();
    }

    // User Profile
    public function userProfile()
    {
        $user = User::where('id',auth()->user()->id)->get()->first();
        $Completed_own_orders = auth()->user()->orders()->where('status', true)->count(); 
        $unCompleted_own_orders = auth()->user()->orders()->where('status', false)->count(); 
// dd($user);
        return view('pages.profile.index',compact('user','Completed_own_orders','unCompleted_own_orders'));
    }

    // offline or online
    public function toggleUserActivation($id)
    {
        $user = User::where('id', $id)->first();
        // If the bot status is 1, set it to 0; otherwise, set it to 1
        $user->is_online = $user->is_online == 1 ? 0 : 1;
        $user->last_finishedtask = now();
        $user->save();

        return [
            'is_active' => $user->is_online
        ];
    }
}
