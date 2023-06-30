<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($userId = 1; $userId <= 70; $userId++) {
            $companyId = rand(1, 20);
            DB::table('company_user')->insert([
                'user_id' => $userId,
                'company_id' => $companyId,
            ]);
        }
    }
}
