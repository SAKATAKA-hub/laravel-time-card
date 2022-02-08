<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteTest extends TestCase
{
    /**
     * トップページの表示テスト
     * @return void
     */
    public function test_top()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
