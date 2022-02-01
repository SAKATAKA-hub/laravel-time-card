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




class WorkRecordStatusSeeder extends Seeder
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
        $color = ['red','blue','green'];


        $count = 5; //従業員数
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

        // データ挿入する日付
        $date = Carbon::parse('now');

        // 勤務記録の作成
        $schedules = self::getSchedules();
        $work_schedules = $schedules['work_schedules'];

        // self::createWorkRecord($date,0,$user);


        foreach ($work_schedules as $wi => $work_schedule)
        {
            self::createWorkRecord($date,$wi,$user);
        }





        return;




        # 出勤・休憩データの作成
        foreach($work_dates as $work_date)
        {

            // foreach ($employees as $e_index => $employee)
            // {
            //     /**
            //      * ----------------
            //      * 出勤当日の勤務
            //      * ----------------
            //     */

            //     # 出勤データの挿入
            //     $work_index = $e_index;
            //     $work_time = new WorkTime([
            //         'employee_id' => $employee->id,
            //         'date' => $work_date,
            //         'in' => $work_schedules[$work_index][0] === '00:00:00' ? '00:00:00':
            //             Carbon::parse($work_date.' '.$work_schedules[$work_index][0])
            //             ->subSecond(mt_rand(0, 15*60-1)) //’出勤’0～15分’前’のランダムな時間
            //             ->format('Y-m-d H:i:s'),

            //         'out' => $work_schedules[$work_index][1] === '24:00:00' ? '24:00:00':
            //             Carbon::parse($work_date.' '.$work_schedules[$work_index][1])
            //             ->addSecond(mt_rand(0, 15*60-1)) //’退勤’0～15分’後’のランダムな時間
            //             ->format('Y-m-d H:i:s'),

            //     ]);
            //     $work_time->save();


            //     # 休憩データの挿入
            //     $break_index = $e_index;
            //     if($break_schedules[$break_index] !== NULL){
            //         foreach ($break_schedules[$break_index] as $break_schedule)
            //         {
            //             $break_time = new BreakTime([
            //                 'work_time_id' => $work_time->id,
            //                 'in' =>
            //                     Carbon::parse($work_date.' '.$break_schedule[0])
            //                     ->addSecond(mt_rand(0, 5*60-1)) //’休憩開始’0～5分’後’のランダムな時間
            //                     ->format('Y-m-d H:i:s'),


            //                 'out' =>
            //                     Carbon::parse($work_date.' '.$break_schedule[1])
            //                     ->subSecond(mt_rand(0, 5*60-1)) //’休憩終了’0～5分’前’のランダムな時間
            //                     ->format('Y-m-d H:i:s'),
            //             ]);
            //             $break_time->save();

            //         }// end foreach $break_schedules[$break_index]
            //     }// end if



            //     /**
            //      * -----------------------------
            //      * 日付を跨いだ勤務時間 (0時以降)
            //      * -----------------------------
            //     */
            //     // 日付を跨いだ勤務時間が存在するとき、データの作成
            //     if( array_key_exists($night_index = $e_index+10, $work_schedules) )
            //     {
            //         # 出勤データの挿入
            //         $work_index = $night_index;
            //         $work_time = new WorkTime([
            //             'employee_id' => $employee->id,
            //             'date' => $work_date,
            //             'in' => $work_schedules[$work_index][0] === '00:00:00' ? '00:00:00':
            //                 Carbon::parse($work_date.' '.$work_schedules[$work_index][0])
            //                 ->subSecond(mt_rand(0, 15*60-1)) //’出勤’0～15分’前’のランダムな時間
            //                 ->format('Y-m-d H:i:s'),

            //             'out' => $work_schedules[$work_index][1] === '24:00:00' ? '24:00:00':
            //                 Carbon::parse($work_date.' '.$work_schedules[$work_index][1])
            //                 ->addSecond(mt_rand(0, 15*60-1)) //’退勤’0～15分’後’のランダムな時間
            //                 ->format('Y-m-d H:i:s'),

            //         ]);
            //         $work_time->save();


            //         # 休憩データの挿入
            //         $break_index = $night_index;
            //         if($break_schedules[$break_index] !== NULL){
            //             foreach ($break_schedules[$break_index] as $break_schedule)
            //             {
            //                 $break_time = new BreakTime([
            //                     'work_time_id' => $work_time->id,
            //                     'in' =>
            //                         Carbon::parse($work_date.' '.$break_schedule[0])
            //                         ->addSecond(mt_rand(0, 5*60-1)) //’休憩開始’0～5分’後’のランダムな時間
            //                         ->format('Y-m-d H:i:s'),


            //                     'out' =>
            //                         Carbon::parse($work_date.' '.$break_schedule[1])
            //                         ->subSecond(mt_rand(0, 5*60-1)) //’休憩終了’0～5分’前’のランダムな時間
            //                         ->format('Y-m-d H:i:s'),
            //                 ]);
            //                 $break_time->save();

            //             }// end foreach $break_schedules[$break_index]
            //         }// end if


            //     } //end if


            // } //end foreach $employees

        } //end foreach $work_dates


        return $user;

    }




    /**
     * ====================================================
     *  クラス内で利用するメソッド
     * ====================================================
     */

    /**
     * 各従業員の勤務スケジュールを呼び出すメソッド
     *
     * @return Array
     */
    public static function getSchedules()
    {
        // 勤務時間
        $work_schedules = [
            // 出勤当日の勤務
            0 => ['08:00:00','17:00:00'],
            1 => ['10:00:00','19:00:00'],
            2 => ['14:00:00','23:00:00'],
            3 => ['22:00:00','24:00:00'],
            4 => ['22:00:00','24:00:00'],

            //日付を跨いだ勤務(0時以降)
            13 => ['00:00:00','08:00:00'],
            14 => ['00:00:00','08:00:00'],
        ];

        // 休憩時間
        $break_schedules = [
            // 出勤当日の勤務
            0 => [ ['10:00:00','10:30:00'], ['15:00:00','15:30:00'] ],
            1 => [ ['12:00:00','12:30:00'], ['17:00:00','17:30:00'] ],
            2 => [ ['16:00:00','16:30:00'], ['21:00:00','21:30:00'] ],
            3 => [],
            4 => [],

            //日付を跨いだ勤務(0時以降)
            13 => [ ['01:00:00','02:00:00'], ['04:00:00','05:00:00'] ],
            14 => [ ['02:00:00','03:00:00'], ['05:00:00','06:00:00'] ],
        ];

        return compact('work_schedules','break_schedules');
    }




    /**
     * 勤務記録を作成するメソッド
     *
     * @param Array $date
     * @param Int $wi
     * @param App\Models\User $user
     * @return void
     */
    public static function createWorkRecord($date,$wi,$user)
    {
        #0. 変数の準備

        // 各従業員の勤務スケジュール
        $schedules = self::getSchedules();
        $work_schedule = $schedules['work_schedules'][$wi];
        $break_schedules = $schedules['break_schedules'][$wi];

        // list($work_schedules, $break_schedules ) =

        // 出勤時間
        $in = $work_schedule[0] === '00:00:00' ? '00:00:00':
            Carbon::parse( $date->format('Y-m-d').' '.$work_schedule[0] )
            ->subSecond(mt_rand(0, 15*60-1)) //’出勤’0～15分’前’のランダムな時間
            ->format('H:i:s');

        // 退勤時間
        $out = $work_schedule[1] === '24:00:00' ? '24:00:00':
            Carbon::parse( $date->format('Y-m-d').' '.$work_schedule[1] )
            ->addSecond(mt_rand(0, 15*60-1)) //’退勤’0～15分’後’のランダムな時間
            ->format('H:i:s');


        //現在日時より先の時間はNULL
        $now = Carbon::parse('now');
        if($date->isToday()){
            $in= $in < $now->format('H:i:s') ? $date->format('Y-m-d ').' '.$in : NULL;
            $out= $out < $now->format('H:i:s') ? $date->format('Y-m-d ').' '.$out : NULL;
        }


        #1. 勤務記録の作成
        if($in)
        {
            // $wi>10のとき、0時以降の勤務
            $work_time = new WorkTime([
                'employee_id' => $wi<10 ? $user->employees[$wi]->id :
                    $user->employees[$wi-10]->id,
                'date' =>  $wi<10 ? $date->format('Y-m-d') :
                    $date->copy()->addDay()->format('Y-m-d'),
                'in' => $in,
                'out' => $out,
            ]);
            $work_time->save();
        }

        #2. 休憩記録の作成

    }

}
