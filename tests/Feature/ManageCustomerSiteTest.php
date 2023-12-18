<?php

namespace Tests\Feature;

use App\Models\CustomerSite;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageCustomerSiteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_customer_site_list_in_customer_site_index_page()
    {
        $owner = $this->loginAsUser();
        $customerSite = CustomerSite::factory()->create(['owner_id' => $owner->id]);

        $this->visitRoute('customer_sites.index');
        $this->see($customerSite->name);
    }

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'name' => 'CustomerSite 1 name',
            'url' => 'https://example.net',
        ], $overrides);
    }

    /** @test */
    public function user_can_create_a_customer_site()
    {
        $this->loginAsUser();
        $vendor = Vendor::factory()->create();
        $this->visitRoute('customer_sites.index');

        $this->click(__('customer_site.create'));
        $this->seeRouteIs('customer_sites.create');

        $this->submitForm(__('app.create'), $this->getCreateFields([
            'vendor_id' => $vendor->id,
        ]));

        $this->seeRouteIs('customer_sites.show', CustomerSite::first());

        $this->seeInDatabase('customer_sites', $this->getCreateFields([
            'vendor_id' => $vendor->id,
        ]));
    }

    /** @test */
    public function validate_customer_site_name_is_required()
    {
        $this->loginAsUser();

        // name empty
        $this->post(route('customer_sites.store'), $this->getCreateFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_customer_site_name_is_not_more_than_60_characters()
    {
        $this->loginAsUser();

        // name 70 characters
        $this->post(route('customer_sites.store'), $this->getCreateFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_customer_site_url_is_required()
    {
        $this->loginAsUser();

        // url empty
        $this->post(route('customer_sites.store'), $this->getCreateFields(['url' => '']));
        $this->assertSessionHasErrors('url');
    }

    /** @test */
    public function validate_customer_site_url_is_not_more_than_255_characters()
    {
        $this->loginAsUser();

        // url 255 characters
        $this->post(route('customer_sites.store'), $this->getCreateFields([
            'url' => 'https://'.str_repeat('perferendisliberosaeperepellatesseoditvo', 7),
        ]));
        $this->assertSessionHasErrors('url');
    }

    private function getEditFields(array $overrides = [])
    {
        return array_merge([
            'name' => 'CustomerSite 1 name',
            'url' => 'https://example.net',
            'check_interval' => 1,
            'priority_code' => 'normal',
            'warning_threshold' => 5000,
            'down_threshold' => 10000,
            'notify_user_interval' => 0,
        ], $overrides);
    }

    /** @test */
    public function user_can_edit_a_customer_site()
    {
        $owner = $this->loginAsUser();
        $vendor = Vendor::factory()->create();
        $customerSite = CustomerSite::factory()->create(['name' => 'Testing 123', 'owner_id' => $owner->id]);

        $this->visitRoute('customer_sites.show', $customerSite);
        $this->click('edit-customer_site-'.$customerSite->id);
        $this->seeRouteIs('customer_sites.edit', $customerSite);

        $this->submitForm(__('customer_site.update'), $this->getEditFields([
            'is_active' => 0,
            'vendor_id' => $vendor->id,
        ]));

        $this->seeRouteIs('customer_sites.show', $customerSite);

        $this->seeInDatabase('customer_sites', $this->getEditFields([
            'id' => $customerSite->id,
            'is_active' => 0,
            'vendor_id' => $vendor->id,
        ]));
    }

    /** @test */
    public function validate_customer_site_name_update_is_required()
    {
        $this->loginAsUser();
        $customer_site = CustomerSite::factory()->create(['name' => 'Testing 123']);

        // name empty
        $this->patch(route('customer_sites.update', $customer_site), $this->getEditFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_customer_site_name_update_is_not_more_than_60_characters()
    {
        $this->loginAsUser();
        $customer_site = CustomerSite::factory()->create(['name' => 'Testing 123']);

        // name 70 characters
        $this->patch(route('customer_sites.update', $customer_site), $this->getEditFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_customer_site_url_update_is_required()
    {
        $this->loginAsUser();
        $customer_site = CustomerSite::factory()->create(['url' => 'Testing 123']);

        // url empty
        $this->patch(route('customer_sites.update', $customer_site), $this->getEditFields(['url' => '']));
        $this->assertSessionHasErrors('url');
    }

    /** @test */
    public function validate_customer_site_url_update_is_not_more_than_255_characters()
    {
        $this->loginAsUser();
        $customer_site = CustomerSite::factory()->create(['url' => 'Testing 123']);

        // url 70 characters
        $this->patch(route('customer_sites.update', $customer_site), $this->getEditFields([
            'url' => 'https://'.str_repeat('perferendisliberosaeperepellatesseoditvo', 7),
        ]));
        $this->assertSessionHasErrors('url');
    }

    /** @test */
    public function user_can_delete_a_customer_site()
    {
        $owner = $this->loginAsUser();
        $customerSite = CustomerSite::factory()->create(['owner_id' => $owner->id]);
        CustomerSite::factory()->create();

        $this->visitRoute('customer_sites.edit', $customerSite);
        $this->click('del-customer_site-'.$customerSite->id);
        $this->seeRouteIs('customer_sites.edit', [$customerSite, 'action' => 'delete']);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('customer_sites', [
            'id' => $customerSite->id,
        ]);
    }
}
