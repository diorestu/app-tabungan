<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class BladeIconsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_blade_icons_renders_custom_svg_and_heroicons(): void
    {
        $rendered = (string) Blade::render('<x-icon-wallet class="size-5" />');
        $this->assertStringContainsString('<svg', $rendered);
        $this->assertStringContainsString('class="size-5"', $rendered);

        $heroicon = (string) Blade::render('<x-heroicon-o-sun class="size-4" />');
        $this->assertStringContainsString('<svg', $heroicon);
        $this->assertStringContainsString('class="size-4"', $heroicon);
    }
}
