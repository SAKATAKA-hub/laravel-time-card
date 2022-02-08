<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Faker\Factory;




class EmployeesSeeder extends Seeder
{
    /**
     * 2.フェイク従業員データの作成
     *
     * @return void
     */
    public function run()
    {
        $user = User::orderBy('id','desc')->first();
        $faker = Factory::create('ja_JP');
        $color = ['#0d6efd','#6610f2','#6f42c1','#d63384','#dc3545','#fd7e14','#ffc107','#198754','#20c997','#0dcaf0',];
        // ['blue','indigo','purple','pink','red','orange','yellow','green','teal','cyan']

        $count = Common\Method:: EmployeeCount(); //従業員数
        for ($i=0; $i < $count; $i++)
        {
            $employee = new Employee([
                'user_id' => $user->id,
                'name' => $faker->name(),
                'color' => $faker->randomElement($color),
            ]);
            $employee->save();
        }
    }

}
