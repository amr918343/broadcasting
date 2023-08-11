<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    ########################SIGNUP########################
    /** @test */
    public function user_can_signup_with_valid_data()
    {
        $user_data = [
            'full_name' => 'Amr Ragab',
            'email' => 'test@test.com',
            'phone_number' => '966501234567',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ];

        $response = $this->postJson('/api/auth/register', $user_data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'is_social_login',
                    'full_name',
                    'phone_number',
                    'email',
                    'token',
                ]
            ]);

        // Ensure user was created
        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
            'phone_number' => '966501234567',
        ]);
    }

    /** @test */
    public function user_cannot_signup_with_invalid_data()
    {
        $user_data = [
            'full_name' => "",
            'email' => 'invalid_email',
            'phone_number' => '123456789012',
            'password' => 'short',
            'password_confirmation' => 'not_matching',
        ];

        $response = $this->postJson('api/auth/register', $user_data);

        $response->assertStatus(422);

        // Ensure user was not created
        $this->assertDatabaseMissing('users', [
            'email' => 'invalid_email',
            'phone_number' => '123456789012',
        ]);
    }

    ########################SIGNIN########################
    /** @test */
    public function user_can_signin_with_valid_data() {
        $user = User::factory()->create([
            'full_name' => 'Amr Ragab',
            'email' => 'test@test.com',
            'phone_number' => '966501234567',
            'password' => 'Password123',
        ]);
        $token = $user->createToken('api_token')->plainTextToken;
        data_set($user, 'token', $token);

        $user_data = [
            'email' => $user->email,
            'password' => 'Password123',
        ];

        $response = $this->postJson('/api/auth/login', $user_data);

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'is_social_login',
                    'full_name',
                    'phone_number',
                    'email',
                    'token',
                ]
            ]);

        $response_data = $response->json('data');
        $this->assertNotNull($response_data['token'], 'Token not found in response data');
        $this->assertEquals($user->id, $response_data['id'], 'User ID does not match');
    }

}
