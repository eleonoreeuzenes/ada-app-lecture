<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_a_user_successfully()
    {
        $response = $this->postJson('/api/register', [
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => 'Password:123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'email' => 'testuser@example.com',
        ]);
    }

    /** @test */
    public function it_fails_if_email_is_invalid()
    {
        $response = $this->postJson('/api/register', [
            'username' => 'testuser',
            'email' => 'invalid-email',
            'password' => 'Password:123',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Email format is invalid.',
            ]);
    }

    /** @test */
    public function it_fails_if_username_is_already_taken()
    {
        User::factory()->create(['username' => 'testuser']);

        $response = $this->postJson('/api/register', [
            'username' => 'testuser',
            'email' => 'newuser@example.com',
            'password' => 'Password:123',
        ]);

        $response->assertStatus(409)
            ->assertJson([
                'message' => 'Username already used.',
            ]);
    }

    /** @test */
    public function it_fails_if_password_does_not_meet_requirements()
    {
        $response = $this->postJson('/api/register', [
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => 'weakpass',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Password format is invalid.',
            ]);
    }
}
