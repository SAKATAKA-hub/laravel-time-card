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
     * A basic Unit test example.
     *
     * @return void
     */
    public function test_example()
    {

        # データの挿入
        $this->seed();
        // $this->seed([
        //     WorkRecordStatusSeeder::class,
        // ]);

        # テーブルのデータ数チェック
        // $this->assertDatabaseCount('users', 1);

        # 指定カラムに指定の値が存在するかチェック
        $this->assertDatabaseHas('users', [
            'name' => 'シーダー登録ユーザー',
        ]);

        # 値が等しいかのチェック
        $user = User::orderBy('id','desc')->first();
        $this->assertEquals($user->name, 'シーダー登録ユーザー');

    }
}
