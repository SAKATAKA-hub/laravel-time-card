<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteTest extends TestCase
{
    /**
     * 簡単ログインユーザーの作成ページの表示テスト
     *
     * @return void
     */
    public function test_create_easy_user()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
