<?php

namespace Tests\Feature;

use App\Models\DisclaimerAcceptance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DisclaimerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_must_complete_profile_before_accepting_disclaimer()
    {
        // Create a user with incomplete profile
        $user = User::factory()->create([
            'name' => '',
            'email' => 'test@example.com',
            'phone' => '',
        ]);

        // Attempt to login
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // Should be redirected to profile page
        $response->assertRedirect('/profile');
        $response->assertSessionHas('warning');
    }

    /** @test */
    public function user_with_complete_profile_must_accept_disclaimer()
    {
        // Create a user with complete profile
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '1234567890',
        ]);

        // Login
        $response = $this->actingAs($user)->get('/home');

        // Should be redirected to disclaimer page
        $response->assertRedirect('/disclaimer');
    }

    /** @test */
    public function user_can_accept_disclaimer()
    {
        // Create a user with complete profile
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '1234567890',
        ]);

        // Login
        $this->actingAs($user);

        // Accept disclaimer
        $response = $this->post('/disclaimer/accept');

        // Should be redirected to home
        $response->assertRedirect('/');

        // Check that disclaimer acceptance was recorded
        $this->assertDatabaseHas('disclaimer_acceptances', [
            'user_id' => $user->id,
        ]);

        // Check session
        $response->assertSessionHas('disclaimer_accepted', true);
    }
}