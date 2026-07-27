<?php

namespace Tests\Feature;

use App\Models\HomePage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CursorSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_cursor_settings(): void
    {
        $home = HomePage::create([
            'cursor_is_active' => true,
            'cursor_color' => '#ffcc00',
        ]);

        $this->actingAs(User::factory()->create())
            ->post(route('header-menu.settings.update'), [
                'cursor_is_active' => '1',
                'cursor_color' => '#12abef',
            ])
            ->assertRedirect(route('header-menu.index'));

        $home->refresh();

        $this->assertTrue($home->cursor_is_active);
        $this->assertSame('#12abef', $home->cursor_color);
    }

    public function test_public_cursor_uses_saved_color_and_can_be_disabled(): void
    {
        $home = HomePage::create([
            'cursor_is_active' => true,
            'cursor_color' => '#12abef',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('class="site-cursor"', false)
            ->assertSee('--site-cursor-color: #12abef', false);

        $home->update(['cursor_is_active' => false]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('class="site-cursor"', false);
    }
}
