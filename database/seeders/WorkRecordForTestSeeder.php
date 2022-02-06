<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;
use App\Models\WorkTime;
use App\Models\BreakTime;
use Carbon\Carbon;
use Faker\Factory;


class WorkRecordForTestSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**
         * =================================================
         * 1.ユーザの新規作成
         * =================================================
        */

        $now = Carbon::parse('now');

        $email = $now->format('YmdHis').'@mail.co.jp';
        $user = new User([
            'name' => 'シーダー登録ユーザー',
            'email' => $email,
            'password'  =>  Hash::make('password'),
            'easy_user' => 0,
        ]);
        $user->save();




        /**
         * =================================================
         * 2.フェイク従業員データの作成
         * =================================================
        */

        $faker = Factory::create('ja_JP');
        $color = ['#0d6efd','#6610f2','#6f42c1','#d63384','#dc3545','#fd7e14','#ffc107','#198754','#20c997','#0dcaf0',];
        // ['blue','indigo','purple','pink','red','orange','yellow','green','teal','cyan']


        $count = 7; //従業員数
        for ($i=0; $i < $count; $i++)
        {
            $employee = new Employee([
                'user_id' => $user->id,
                'name' => $faker->name(),
                'color' => $faker->randomElement($color),
            ]);
            $employee->save();
        }




        /**
         * =================================================
         * 3.フェイク勤務記録の作成
         * =================================================
        */
        $user = User::orderBy('id','desc')->first();

        // 勤務スケジュールの取得
        $schedules = WorkRecordStatusSeeder::getSchedules();
        $work_schedules = $schedules['work_schedules'];

        $now = Carbon::parse('now'); //現在の日時
        $d = 3; //1ヶ月に$d日分のデータを作成


        for ($di=0; $di < $d; $di++) { //$d日の繰り返し
            $date = $now->copy()->subDay( $d - ($di+1) );

            // 勤務データの作成
            foreach ($work_schedules as $wi => $work_schedule)
            {
                WorkRecordStatusSeeder::createWorkRecord($date,$wi,$user);
            }


        } //end for $di



    }




}
