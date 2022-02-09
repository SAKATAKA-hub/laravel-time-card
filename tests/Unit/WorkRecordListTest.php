<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;





class WorkRecordListTest extends TestCase
{

    use RefreshDatabase; //テスト用データベースの利用
    use WithoutMiddleware; //ミドルウェアの無効化


    /**
     * テスト用DBデータの作成と、テストユーザーのログイン処理
     *
     * @return App\Models\User (テストユーザーのデータ)
     */
    public function set_up()
    {
        # データの挿入
        $this->seed([
            \Database\Seeders\User\TestSeeder::class, //1.ユーザの新規作成
            \Database\Seeders\EmployeesSeeder::class, //2.フェイク従業員データの作成
            //3.フェイク勤務記録の作成
        ]);

        # ユーザーのログイン処理
        $response = $this->post('login',[
            'email' => 'test@mail.co.jp',
            'password' => 'password',
        ]);


        return User::orderBy('id','desc')->first();
    }


    /**
     * ログインテスト
     *
     * @return void
     */
    public function test_user_login()
    {
        # データのセットアップ
        Self::set_up();
        $this->assertTrue( Auth::check() );
    }


    /**
     * 日別勤怠管理表ページの表示(date_list)
     * @return void
     */
    public function test_date_list()
    {
        $user = Self::set_up();
        $response = $this->get('date_list');

        #1.ページの表示テスト
        $response->assertStatus(200);
    }


    /**
     * 月別勤怠管理表ページの表示(month_list)
     * @return void
     */
    public function test_month_list()
    {
        $user = Self::set_up();
        $response = $this->get('month_list');

        #1.ページの表示テスト
        $response->assertStatus(200);
    }


    /**
     * 個人別勤怠管理表ページの表示(parsonal_list)
     * @return void
     */
    public function test_parsonal_list()
    {
        $user = Self::set_up();
        $response = $this->get('parsonal_list');

        #1.ページの表示テスト
        $response->assertStatus(200);
    }

}
