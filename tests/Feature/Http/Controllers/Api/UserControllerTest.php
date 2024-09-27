<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_returns_user_collection()
    {
        $users = User::factory()->count(3)->create();

        $response = $this->actingAs($users->first())
            ->getJson(route('users.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'name', 'email', // modify as per your resource fields
                    ],
                ],
            ]);
    }

    public function test_show_returns_specific_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson(route('users.show', $user->id));


        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                    'data' =>
                        [
                            'id'    => $user->id,
                            'name'  => $user->name,
                            'email' => $user->email,
                        ],
                ]
            );
    }

    public function test_token_returns_token_on_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson(route('users.token'), [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['token']);
    }

    public function test_token_returns_error_on_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson(route('users.token'), [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(['message' => 'Invalid credentials']);
    }
}
