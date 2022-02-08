<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();

        $this->call([
            User\DefaultSeeder::class, //1.ユーザの新規作成
            EmployeesSeeder::class, //2.フェイク従業員データの作成
            WorkRecord\ThreeMonthsSeeder::class, //3.フェイク勤務記録の作成(3ヶ月分)
        ]);

    }
}
