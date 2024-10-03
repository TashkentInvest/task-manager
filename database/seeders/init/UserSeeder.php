<?php

namespace Database\Seeders\init;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superuser = User::create([
            "name" => "Super Admin",
            "email" => "superadmin@example.com",
            "about"=> "I am a usepr admin ",
            "location" => "Uzbekistan",
            "phone"=> "+998999992233",
            "birth_date" => "2004-03-30 ",
            "password" => bcrypt("teamdevs")
        ]);

        $this->createPermissionsAndRoles();

        $superuser->assignRole('Super Admin');
        $permissions = Permission::all();
        $superuser->syncPermissions($permissions);

        // admin user

        $adminuser = User::create([
            "name" => "Admin",
            "email" => "admin@gmail.com",
            "password" => bcrypt("password")
        ]);

        $this->createPermissionsAndRoles();

        $adminuser->assignRole('Admin');
        $permissions = Permission::all();
        $adminuser->syncPermissions($permissions);

        // Manager

        $manageuser = User::create([
            "name" => "Manager",
            "email" => "manager@example.com",
            "password" => bcrypt("password")
        ]);

        $this->createPermissionsAndRoles();

        $manageuser->assignRole('Manager');
        $permissions = Permission::all();
        $manageuser->syncPermissions($permissions);

        // Employee

        $useremployee = User::create([
            "name" => "Employee",
            "email" => "employee@example.com",
            "password" => bcrypt("password"),
            "is_online" => 0,
        ]);


        $this->createPermissionsAndRoles();

        $useremployee->assignRole('Employee');
        $permissions = Permission::all();
        $useremployee->syncPermissions($permissions);


        $useremployee777 = User::create([
            "name" => "aaaaempi",
            "email" => "azik@example.com",
            "password" => bcrypt("password"),
            "is_online" => 0,
        ]);


        $this->createPermissionsAndRoles();

        $useremployee777->assignRole('Employee');
        $permissions = Permission::all();
        $useremployee777->syncPermissions($permissions);
    }

    /**
     * Create permissions and roles if they do not exist.
     */
    private function createPermissionsAndRoles()
    {
        // Permissions
        $permissions = ["permission.show", "permission.edit", "permission.add", "permission.delete", "roles.show", "roles.edit", "roles.add", "roles.delete", "user.show", "user.edit", "user.add", "user.delete", "dashboard.show", "monitoring.show", "left-request.add", "left-request.edit", "left-request.delete", "category.show", "category.add", "category.edit", "category.delete", "company.show", "company.add", "company.edit", "company.delete", "driver.show", "driver.add", "driver.edit", "driver.delete", "long-text.show", "long-text.add", "long-text.edit", "long-text.delete", "employee.show", "employee.add", "employee.edit", "employee.delete", "cheque.show", "control-report.show", "shift.show", "fines.show", "bonuses.show", "request-history.show", "order-history.show"];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Role
        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    }
}
