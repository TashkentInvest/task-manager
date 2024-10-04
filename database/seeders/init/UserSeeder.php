<?php

namespace Database\Seeders\init;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;


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


        // generate from xlsx
        $filePath = public_path('assets/users/ХР.xlsx_Parsing.uz.xlsx');
        
        // Load the Excel file
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        // Iterate through the rows
        $rows = $sheet->toArray();
        
        // Skip the header row
        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue;
            }
            
            // Map your columns to the table structure
            $last_name = $row[0];
            $first_name = $row[1];
            $father_name = $row[2];
            $job_title = $row[3];
            $phone = $row[4];
            
            // Combine first_name, last_name, and father_name for the 'name' field
            $fullName = $last_name . ' ' . $first_name . ' ' . ($father_name ?: '');
            
            // Generate a unique email
            $email = $this->generateUniqueEmail($first_name, $last_name);

            // Generate a random password
            $randomPassword = Str::random(10);  // Generates a random 10-character password
            
            // Insert into the users table
            $user = User::create([
                'name' => $fullName,
                'email' => $email,
                'password' => Hash::make('secret'),  // Store hashed password
                'phone' => $phone,
                'about' => $job_title,
                'is_online' => 0,
            ]);
            
            // Assign role and permissions (like in your example)
            $user->assignRole('Employee');
            $permissions = \Spatie\Permission\Models\Permission::all();
            $user->syncPermissions($permissions);
            
            // Optionally log the user's random password (for reference)
            $this->command->info("User: {$fullName}, Email: {$email}, Password: {$randomPassword}");
        }
        // end generate


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

    private function generateUniqueEmail($first_name, $last_name)
    {
        // Lowercase first and last name with the domain
        $email = strtolower($first_name) . '.' . strtolower($last_name) . '@toshkentinevst.uz';
        
        // Ensure the email is unique
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $email = strtolower($first_name) . '.' . strtolower($last_name) . $counter . '@toshkentinevst.uz';
            $counter++;
        }

        return $email;
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
