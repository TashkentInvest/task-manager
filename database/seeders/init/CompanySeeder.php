<?php

namespace Database\Seeders\init;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [
            ['name' => 'Yurist', 'owner_name' => 'John Doe', 'phone' => '1234567890'],
            ['name' => 'Renatvatsa', 'owner_name' => 'Jane Smith', 'phone' => '0987654321'],
            ['name' => 'Finans', 'owner_name' => 'Alice Johnson', 'phone' => '5555555555'],
            ['name' => 'APZ', 'owner_name' => 'Alice Johnson', 'phone' => '5555555555'],
        ];

        foreach ($companies as $companyData) {
            Company::create($companyData);
        }
    }
}
