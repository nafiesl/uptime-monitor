<?php

namespace Tests\Feature;

use App\Models\Site;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageSiteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_site_list_in_site_index_page()
    {
        $owner = $this->loginAsUser();
        $site = Site::factory()->create(['owner_id' => $owner->id]);

        $this->visitRoute('sites.index');
        $this->see($site->name);
    }

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'name' => 'Site 1 name',
            'url' => 'https://example.net',
        ], $overrides);
    }

    /** @test */
    public function user_can_create_a_site()
    {
        $this->loginAsUser();
        $vendor = Vendor::factory()->create();
        $this->visitRoute('sites.index');

        $this->click(__('site.create'));
        $this->seeRouteIs('sites.create');

        $this->submitForm(__('app.create'), $this->getCreateFields([
            'vendor_id' => $vendor->id,
        ]));

        $this->seeRouteIs('sites.show', Site::first());

        $this->seeInDatabase('sites', $this->getCreateFields([
            'vendor_id' => $vendor->id,
        ]));
    }

    /** @test */
    public function validate_site_name_is_required()
    {
        $this->loginAsUser();

        // name empty
        $this->post(route('sites.store'), $this->getCreateFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_site_name_is_not_more_than_60_characters()
    {
        $this->loginAsUser();

        // name 70 characters
        $this->post(route('sites.store'), $this->getCreateFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_site_url_is_required()
    {
        $this->loginAsUser();

        // url empty
        $this->post(route('sites.store'), $this->getCreateFields(['url' => '']));
        $this->assertSessionHasErrors('url');
    }

    /** @test */
    public function validate_site_url_is_not_more_than_255_characters()
    {
        $this->loginAsUser();

        // url 255 characters
        $this->post(route('sites.store'), $this->getCreateFields([
            'url' => 'https://'.str_repeat('perferendisliberosaeperepellatesseoditvo', 7),
        ]));
        $this->assertSessionHasErrors('url');
    }

    private function getEditFields(array $overrides = [])
    {
        return array_merge([
            'name' => 'Site 1 name',
            'url' => 'https://example.net',
            'check_interval' => 1,
            'priority_code' => 'normal',
            'warning_threshold' => 5000,
            'down_threshold' => 10000,
            'notify_user_interval' => 0,
        ], $overrides);
    }

    /** @test */
    public function user_can_edit_a_site()
    {
        $owner = $this->loginAsUser();
        $vendor = Vendor::factory()->create();
        $site = Site::factory()->create(['name' => 'Testing 123', 'owner_id' => $owner->id]);

        $this->visitRoute('sites.show', $site);
        $this->click('edit-site-'.$site->id);
        $this->seeRouteIs('sites.edit', $site);

        $this->submitForm(__('site.update'), $this->getEditFields([
            'is_active' => 0,
            'vendor_id' => $vendor->id,
        ]));

        $this->seeRouteIs('sites.show', $site);

        $this->seeInDatabase('sites', $this->getEditFields([
            'id' => $site->id,
            'is_active' => 0,
            'vendor_id' => $vendor->id,
        ]));
    }

    /** @test */
    public function validate_site_name_update_is_required()
    {
        $this->loginAsUser();
        $site = Site::factory()->create(['name' => 'Testing 123']);

        // name empty
        $this->patch(route('sites.update', $site), $this->getEditFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_site_name_update_is_not_more_than_60_characters()
    {
        $this->loginAsUser();
        $site = Site::factory()->create(['name' => 'Testing 123']);

        // name 70 characters
        $this->patch(route('sites.update', $site), $this->getEditFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_site_url_update_is_required()
    {
        $this->loginAsUser();
        $site = Site::factory()->create(['url' => 'Testing 123']);

        // url empty
        $this->patch(route('sites.update', $site), $this->getEditFields(['url' => '']));
        $this->assertSessionHasErrors('url');
    }

    /** @test */
    public function validate_site_url_update_is_not_more_than_255_characters()
    {
        $this->loginAsUser();
        $site = Site::factory()->create(['url' => 'Testing 123']);

        // url 70 characters
        $this->patch(route('sites.update', $site), $this->getEditFields([
            'url' => 'https://'.str_repeat('perferendisliberosaeperepellatesseoditvo', 7),
        ]));
        $this->assertSessionHasErrors('url');
    }

    /** @test */
    public function user_can_delete_a_site()
    {
        $owner = $this->loginAsUser();
        $site = Site::factory()->create(['owner_id' => $owner->id]);
        Site::factory()->create();

        $this->visitRoute('sites.edit', $site);
        $this->click('del-site-'.$site->id);
        $this->seeRouteIs('sites.edit', [$site, 'action' => 'delete']);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('sites', [
            'id' => $site->id,
        ]);
    }
}
