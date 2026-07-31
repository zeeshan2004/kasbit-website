<?php

namespace Tests\Feature;

use App\Models\HeaderMenu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssociateDegreeAdminNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_associate_degree_container_is_a_dropdown_not_an_admin_edit_link(): void
    {
        $admin = User::factory()->create();
        [$container] = $this->menus();
        $container->children()->update(['management_context' => null]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('data-admin-dropdown-only="'.$container->id.'"', false);
        $response->assertDontSee('href="'.route('header-menu.page.edit', $container).'"', false);
    }

    public function test_old_container_admin_url_redirects_to_computer_science_editor(): void
    {
        $admin = User::factory()->create();
        [$container, $computerScience] = $this->menus();

        $this->actingAs($admin)
            ->get(route('header-menu.page.edit', $container))
            ->assertRedirect(route('header-menu.page.edit', $computerScience));
    }

    public function test_computer_science_child_owns_the_public_page_link(): void
    {
        [$container, $computerScience] = $this->menus();
        $targetPage = $computerScience->page;

        $this->assertNull($container->link);
        $this->assertSame('/pages/'.$targetPage->slug, $computerScience->link);
        $this->assertSame('Associate Degree in Computer Science', $targetPage->title);
    }

    public function test_old_frontend_container_page_redirects_to_computer_science_page(): void
    {
        [$container, $computerScience] = $this->menus();

        $this->get(route('pages.show', $container->page))
            ->assertRedirect(route('pages.show', $computerScience->page));
    }

    private function menus(): array
    {
        $container = HeaderMenu::query()
            ->where('name', HeaderMenu::ADMIN_DROPDOWN_ONLY_MENU)
            ->firstOrFail();
        $computerScience = $container->children()
            ->where('name', HeaderMenu::ADMIN_DROPDOWN_CONTENT_TARGET)
            ->firstOrFail();

        return [$container, $computerScience];
    }
}
