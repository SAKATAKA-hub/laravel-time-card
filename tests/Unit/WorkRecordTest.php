<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Database\Seeders\WorkRecordStatusSeeder;
use App\Models\User;




class WorkRecordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * テストデータの挿入テスト.
     * @return void
     */
    public function test_create_test_data()
    {

        # データの挿入
        $this->seed([
            \Database\Seeders\User\TestSeeder::class, //1.ユーザの新規作成
            \Database\Seeders\EmployeesSeeder::class, //2.フェイク従業員データの作成
            //3.フェイク勤務記録の作成
        ]);

        # テーブルのデータ数チェック
        // $this->assertDatabaseCount('users', 1);

        # usersテーブルにテストユーザーデータが登録されたかチェック
        $this->assertDatabaseHas('users', [
            'name' => 'テストユーザー',
            'email' => 'test@mail.co.jp',
        ]);

        # employeesテーブルに登録した従業員データ数が正しいかチェック
        $user = User::where('name','テストユーザー')->first();
        $employees = $user->employees;
        $this->assertEquals(count($employees), 7);

    }
}
