<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;



use Database\Seeders\WorkRecordStatusSeeder;

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

        $this->seed([
            WorkRecordStatusSeeder::class,
        ]);

    }
}
