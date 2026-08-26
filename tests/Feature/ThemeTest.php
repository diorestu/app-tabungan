<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ThemeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_can_save_light_theme_to_session(): void
    {
        $response = $this->postJson(route('theme.update'), [
            'theme' => 'light'
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'theme' => 'light'
            ]);

        $this->assertEquals('light', session('theme'));
    }

    public function test_can_save_dark_theme_to_session(): void
    {
        $response = $this->postJson(route('theme.update'), [
            'theme' => 'dark'
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'theme' => 'dark'
            ]);

        $this->assertEquals('dark', session('theme'));
    }

    public function test_renders_html_without_dark_class_when_light_mode_is_in_session(): void
    {
        $response = $this->withSession(['theme' => 'light'])->get('/');
        $response->assertOk();
        $this->assertStringNotContainsString('<html lang="en" class="dark"', $response->getContent());
    }

    public function test_admin_dashboard_renders_without_dark_class_when_light_in_session(): void
    {
        $admin = \App\Models\User::factory()->create();
        $response = $this->actingAs($admin)->withSession(['theme' => 'light'])->get(route('admin.dashboard'));
        $response->assertOk();
        $this->assertStringNotContainsString('class="dark"', $response->getContent());
    }
}


