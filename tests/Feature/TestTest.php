<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestTest extends TestCase
{

    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
