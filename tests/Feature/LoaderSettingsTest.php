<?php

namespace Tests\Feature;

use App\Models\HomePage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoaderSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_loader_settings_card_appears_before_header_settings(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('header-menu.index'));

        $response->assertOk()
            ->assertSee('css/admin-fallback.css')
            ->assertSee('Loader Settings')
            ->assertSee('Loader Image')
            ->assertSee('name="loader_logo"', false)
            ->assertSee('Header Logo remains separate')
            ->assertSee('Header Settings');

        $content = $response->getContent();

        $this->assertLessThan(
            strpos($content, 'Header Settings'),
            strpos($content, 'Loader Settings')
        );
    }

    public function test_loader_settings_update_does_not_change_header_or_social_settings(): void
    {
        $home = HomePage::create([
            'header_logo' => 'header-logo.webp',
            'loader_logo' => 'loader-logo.webp',
            'header_phone' => '021-1234567',
            'header_email' => 'info@example.com',
            'header_facebook_url' => 'https://facebook.com/kasbit',
            'header_twitter_url' => 'https://x.com/kasbit',
            'header_instagram_url' => 'https://instagram.com/kasbit',
            'top_header_is_active' => true,
            'loader_is_active' => true,
            'loader_text' => 'Loading...',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('header-menu.loader-settings.update'), [
                'loader_text' => '',
                'delete_loader_logo' => '1',
            ]);

        $response->assertRedirect(route('header-menu.index'));

        $home->refresh();

        $this->assertFalse($home->loader_is_active);
        $this->assertNull($home->loader_text);
        $this->assertNull($home->loader_logo);
        $this->assertSame('header-logo.webp', $home->header_logo);
        $this->assertSame('021-1234567', $home->header_phone);
        $this->assertSame('info@example.com', $home->header_email);
        $this->assertSame('https://facebook.com/kasbit', $home->header_facebook_url);
        $this->assertSame('https://x.com/kasbit', $home->header_twitter_url);
        $this->assertSame('https://instagram.com/kasbit', $home->header_instagram_url);
        $this->assertTrue($home->top_header_is_active);
    }

    public function test_public_loader_uses_its_own_image_separately_from_header_logo(): void
    {
        HomePage::create([
            'header_logo' => 'header-logo.webp',
            'loader_logo' => 'loader-logo.webp',
            'loader_is_active' => true,
            'loader_text' => 'Please wait',
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('id="pageLoader"', false)
            ->assertSee('Please wait')
            ->assertSee('uploads/home/loader-logo.webp')
            ->assertSee('uploads/home/header-logo.webp');

        $this->assertMatchesRegularExpression(
            '/<img src="[^"]*loader-logo\.webp" alt="" class="page-loader__logo">/',
            $response->getContent()
        );
        $this->assertMatchesRegularExpression(
            '/<img src="[^"]*header-logo\.webp" alt="KASBIT logo" class="kasbit-logo">/',
            $response->getContent()
        );
    }

    public function test_public_loader_can_be_disabled(): void
    {
        HomePage::create([
            'loader_is_active' => false,
            'loader_text' => 'Loading...',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('id="pageLoader"', false);
    }

    public function test_public_loader_can_hide_its_text_and_falls_back_without_a_logo(): void
    {
        HomePage::create([
            'header_logo' => null,
            'loader_logo' => null,
            'loader_is_active' => true,
            'loader_text' => null,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('id="pageLoader"', false)
            ->assertSee('page-loader__fallback-icon', false)
            ->assertDontSee('page-loader__text', false);
    }
}
