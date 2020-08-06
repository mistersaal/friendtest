<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MainTest extends TestCase
{
    use RefreshDatabase;

    public function testMainPage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testInfoData()
    {
        foreach ([env('USER_1'), env('USER_2')] as $user) {
            $response = $this->withHeaders([
                env('VK_SIGN_HEADER') => $user
            ])->getJson('/info');

            $response->assertStatus(200);
            $response->assertJsonStructure([
                'user' => [
                    'vkid',
                    'img',
                    'first_name',
                    'last_name',
                    'hasTest',
                ]
            ]);
        }
    }
}
