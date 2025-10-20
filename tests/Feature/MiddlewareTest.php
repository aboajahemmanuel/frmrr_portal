<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function middleware_blocks_access_to_protected_routes()
    {
        // Create a user with complete profile but no disclaimer acceptance
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '1234567890',
        ]);

        // Try to access a protected route
        $response = $this->actingAs($user)->get('/subCategory/test');

        // Should be redirected to disclaimer page
        $response->assertRedirect('/disclaimer');
    }
}