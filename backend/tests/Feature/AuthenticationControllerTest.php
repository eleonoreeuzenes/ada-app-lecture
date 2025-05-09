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

    /** @test */
    public function it_fails_if_email_not_entered()
    {
        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'Password:123',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The email field is required.',
            ]);
    }

    /** @test */
    public function it_logs_in_a_user_with_valid_credentials()
    {
        // Arrange: Create a user
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('Password123!'),
        ]);

        // Act: Attempt to log in
        $response = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'Password123!',
        ]);

        // Assert: Check if the response is successful and contains the token
        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
            ]);
    }

    /** @test */
    public function it_fails_to_log_in_with_invalid_credentials()
    {
        // Arrange: Create a user
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('Password123!'),
        ]);

        // Act: Attempt to log in with incorrect password
        $response = $this->postJson('/api/login', [
            'email' => 'testuser@example.com',
            'password' => 'WrongPassword!',
        ]);

        // Assert: Check if the response returns a 400 status and error message
        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Login information is invalid.',
            ]);
    }

    /** @test */
    public function it_fails_to_log_in_with_nonexistent_email()
    {
        // Act: Attempt to log in with an email that doesn't exist
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'Password123!',
        ]);

        // Assert: Check if the response returns a 400 status and error message
        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Login information is invalid.',
            ]);
    }
}
