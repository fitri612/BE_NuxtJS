<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Responsibility;
use App\Models\Role;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(70)->create();
        \App\Models\Company::factory(20)->create();
        Team::factory(30)->create();
        Role::factory(10)->create();
        Responsibility::factory(50)->create();
        Employee::factory(80)->create();
    }
}
