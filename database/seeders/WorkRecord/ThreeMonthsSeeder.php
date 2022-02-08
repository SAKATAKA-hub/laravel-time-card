<?php

namespace Database\Seeders\WorkRecord;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Database\Seeders\Common\Method;



class ThreeMonthsSeeder extends Seeder
{

    /**
     * 3.フェイク勤務記録の作成(3ヶ月の勤務データ)
     *
     * @return void
     */
    public function run()
    {
        $user = User::orderBy('id','desc')->first();

        // 勤務スケジュールの取得
        $schedules = Method::GetSchedules();
        $work_schedules = $schedules['work_schedules'];

        $now = Carbon::parse('now'); //現在の日時
        $m = 3; //$mヶ月前からデータを作成
        $d = 3; //1ヶ月に$d日分のデータを作成
        $thisMonth = Carbon::parse($now->format('Y-m-'.$d)); //今月


        for ($mi=0; $mi < $m; $mi++) { //$mヶ月の繰り返し
            $month = $thisMonth->copy()->subMonth( $m - ($mi+1) );


            for ($di=0; $di < $d; $di++) { //$d日の繰り返し
                $date = $month->format('Ym') !== $now->format('Ym') ?
                    $month->copy()->subDay( $d - ($di+1) ) :
                    $now->copy()->subDay( $d - ($di+1) )
                ;

                // 勤務データの作成
                foreach ($work_schedules as $wi => $work_schedule)
                {
                    Method::CreateWorkRecord($date,$wi,$user);
                }


            } //end for $di


        } //end for $mi


    }
}
