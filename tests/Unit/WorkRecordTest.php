<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Request;
use Tests\TestCase;

use Database\Seeders\WorkRecordStatusSeeder;
use App\Models\User;




class WorkRecordTest extends TestCase
{
    use RefreshDatabase; //テスト用データベースの利用
    use WithoutMiddleware; //ミドルウェアの無効化

    public function set_up()
    {
        # データの挿入
        $this->seed([
            \Database\Seeders\User\TestSeeder::class, //1.ユーザの新規作成
            \Database\Seeders\EmployeesSeeder::class, //2.フェイク従業員データの作成
            //3.フェイク勤務記録の作成
        ]);

    }



    public function test_login()
    {
        $response = $this->post('login',[
            'email' => 'test@mail.co.jp',
            'password' => 'password',
        ]);

        # ログイン処理が完了したかチェック
        // $response->assertStatus(200);
        // $response->assertRedirect('date_list');
        # セッションにログイン情報が保存されたかチェック
        $this->assertTrue( true );
        $this->assertTrue( Auth::check() );
    }

    /**
     * テストデータの挿入テスト.
     * @return void
     */
    public function test_create_test_data()
    {
        # データのセットアップ
        Self::set_up();


        # usersテーブルにテストユーザーデータが登録されたかチェック
        $this->assertDatabaseHas('users', [
            'name' => 'テストユーザー',
            'email' => 'test@mail.co.jp',
        ]);

        # employeesテーブルに登録した従業員データ数が正しいかチェック
        $user = User::where('name','テストユーザー')->first();
        $employees = $user->employees;

    }



}
