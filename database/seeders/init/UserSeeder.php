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
            "about" => "I am a super admin",
            "location" => "Uzbekistan",
            "phone" => "+998999992233",
            "birth_date" => "2004-03-30",
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

        $adminuser->assignRole('Admin');
        $adminuser->syncPermissions($permissions);

        // Manager
        $manageuser = User::create([
            "name" => "Manager",
            "email" => "manager@example.com",
            "password" => bcrypt("password")
        ]);

        $manageuser->assignRole('Manager');
        $manageuser->syncPermissions($permissions);

        // Employee
        $useremployee = User::create([
            "name" => "Employee",
            "email" => "employee@example.com",
            "password" => bcrypt("password"),
            "is_online" => 0,
        ]);

        $useremployee->assignRole('Employee');
        $useremployee->syncPermissions($permissions);

        // Generate from xlsx
        $filePath = public_path('assets/users/ХР.xlsx_Parsing.uz.xlsx');
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            $last_name = $row[0];
            $first_name = $row[1];
            $father_name = $row[2];
            $job_title = $row[3];
            $phone = $row[4];

            $fullName = $last_name . ' ' . $first_name . ' ' . ($father_name ?: '');
            $email = $this->generateUniqueEmail($first_name, $last_name);
            $randomPassword = Str::random(10);

            $user = User::create([
                'name' => $fullName,
                'email' => $email,
                'password' => Hash::make('secret'),
                'phone' => $phone,
                'about' => $job_title,
                'is_online' => 0,
            ]);

            $user->assignRole('Employee');
            $user->syncPermissions($permissions);

            $this->command->info("User: {$fullName}, Email: {$email}, Password: {$randomPassword}");
        }

        $useremployee777 = User::create([
            "name" => "aaaaempi",
            "email" => "azik@example.com",
            "password" => bcrypt("password"),
            "is_online" => 0,
        ]);

        $useremployee777->assignRole('Employee');
        $useremployee777->syncPermissions($permissions);

        // Add additional roles for departments
        $this->createDepartmentRoles();
    }

    private function generateUniqueEmail($first_name, $last_name)
    {
        $email = strtolower($first_name) . '.' . strtolower($last_name) . '@toshkentinevst.uz';
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $email = strtolower($first_name) . '.' . strtolower($last_name) . $counter . '@toshkentinevst.uz';
            $counter++;
        }
        return $email;
    }

    private function createPermissionsAndRoles()
    {
        $permissions = [
            "permission.show",
            "permission.edit",
            "permission.add",
            "permission.delete",
            "roles.show",
            "roles.edit",
            "roles.add",
            "roles.delete",
            "user.show",
            "user.edit",
            "user.add",
            "user.delete",
            // "dashboard.show",
            // "monitoring.show",
            // "left-request.add",
            // "left-request.edit",
            // "left-request.delete",
            // "category.show",
            // "category.add",
            // "category.edit",
            // "category.delete",
            // "company.show",
            // "company.add",
            // "company.edit",
            // "company.delete",
            // "driver.show",
            // "driver.add",
            // "driver.edit",
            // "driver.delete",
            // "long-text.show",
            // "long-text.add",
            // "long-text.edit",
            // "long-text.delete",
            // "employee.show",
            // "employee.add",
            // "employee.edit",
            // "employee.delete",
            // "cheque.show",
            // "control-report.show",
            // "shift.show",
            // "fines.show",
            // "bonuses.show",
            // "request-history.show",
            // "order-history.show"
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    }

    private function createDepartmentRoles()
    {
        $departments = [
            [
                'name' => 'Boshqaruv ma\'muriyati',
                'title' => 'Администрация правления',
            ],
            [
                'name' => 'Moliya boshqarmasi',
                'title' => 'правление по финансам',
            ],
            [
                'name' => 'Yuridik bo\'lim',
                'title' => 'Юридический отдел',
            ],
            [
                'name' => 'Rivojlanish va rekonstruksiya',
                'title' => 'Девелопмент и Реновация',
            ],
            [
                'name' => 'Loyihalarni boshqarish',
                'title' => 'Управление проектами',
            ],
            [
                'name' => 'Aktivlarni boshqarish bo\'limi',
                'title' => 'Департамент по управлению активами',
            ],
            [
                'name' => 'Investitsiya boshqarmasi',
                'title' => 'правление по инвестициям',
            ],
            [
                'name' => 'Davlat organlari bilan aloqa bo\'limi',
                'title' => 'Отдел по связи с государственными органами',
            ],
            [
                'name' => 'Strategik rivojlanish',
                'title' => 'Стратегическое развитие',
            ],
            [
                'name' => 'Korporativ aloqalar bo\'limi',
                'title' => 'Отдел корпоративных отношений',
            ],
            [
                'name' => 'Kadrlar bo\'limi (HR)',
                'title' => 'HR',
            ],
            [
                'name' => 'Yo\'l va uy-joy kommunal qurilishi bo\'limi',
                'title' => 'департамент дорожного и жилищно-коммунального строительного',
            ],
            [
                'name' => 'Raqamli transformatsiya va axborot texnologiyalari',
                'title' => 'Цифровая Трансформация и информационная технология',
            ],
            [
                'name' => 'Metodologiya va qonunchilik bo\'limi',
                'title' => 'Департамент методалогии и нормотворчество',
            ],
        ];

        // Create each department role with a name and title if it doesn't exist
        foreach ($departments as $department) {
            Role::firstOrCreate(
                ['name' => $department['name'], 'guard_name' => 'web'],
                ['title' => $department['title']]
            );
        }
    }
}
