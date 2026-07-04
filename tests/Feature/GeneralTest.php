<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeneralTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_access_for_authenticated_user()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_settings_access_restricted_to_admin()
    {
        $user = User::factory()->create(['role' => 'petugas']);
        $response = $this->actingAs($user)->get('/settings');
        $response->assertStatus(403);

        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/settings');
        $response->assertStatus(200);
    }
}
