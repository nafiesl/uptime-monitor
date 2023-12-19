<?php

namespace Tests\Feature;

use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageVendorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_vendor_list_in_vendor_index_page()
    {
        $vendor = Vendor::factory()->create();

        $this->loginAsUser();
        $this->visitRoute('vendors.index');
        $this->see($vendor->name);
    }

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'name' => 'Vendor 1 name',
            'description' => 'Vendor 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_create_a_vendor()
    {
        $this->loginAsUser();
        $this->visitRoute('vendors.index');

        $this->click(__('vendor.create'));
        $this->seeRouteIs('vendors.create');

        $this->submitForm(__('app.create'), $this->getCreateFields());

        $this->seeRouteIs('vendors.show', Vendor::first());

        $this->seeInDatabase('vendors', $this->getCreateFields());
    }

    /** @test */
    public function validate_vendor_name_is_required()
    {
        $this->loginAsUser();

        // name empty
        $this->post(route('vendors.store'), $this->getCreateFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_vendor_name_is_not_more_than_60_characters()
    {
        $this->loginAsUser();

        // name 70 characters
        $this->post(route('vendors.store'), $this->getCreateFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_vendor_description_is_not_more_than_255_characters()
    {
        $this->loginAsUser();

        // description 256 characters
        $this->post(route('vendors.store'), $this->getCreateFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    private function getEditFields(array $overrides = [])
    {
        return array_merge([
            'name' => 'Vendor 1 name',
            'description' => 'Vendor 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_edit_a_vendor()
    {
        $this->loginAsUser();
        $vendor = Vendor::factory()->create(['name' => 'Testing 123']);

        $this->visitRoute('vendors.show', $vendor);
        $this->click('edit-vendor-'.$vendor->id);
        $this->seeRouteIs('vendors.edit', $vendor);

        $this->submitForm(__('vendor.update'), $this->getEditFields());

        $this->seeRouteIs('vendors.show', $vendor);

        $this->seeInDatabase('vendors', $this->getEditFields([
            'id' => $vendor->id,
        ]));
    }

    /** @test */
    public function validate_vendor_name_update_is_required()
    {
        $this->loginAsUser();
        $vendor = Vendor::factory()->create(['name' => 'Testing 123']);

        // name empty
        $this->patch(route('vendors.update', $vendor), $this->getEditFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_vendor_name_update_is_not_more_than_60_characters()
    {
        $this->loginAsUser();
        $vendor = Vendor::factory()->create(['name' => 'Testing 123']);

        // name 70 characters
        $this->patch(route('vendors.update', $vendor), $this->getEditFields([
            'name' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_vendor_description_update_is_not_more_than_255_characters()
    {
        $this->loginAsUser();
        $vendor = Vendor::factory()->create(['name' => 'Testing 123']);

        // description 256 characters
        $this->patch(route('vendors.update', $vendor), $this->getEditFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_delete_a_vendor()
    {
        $this->loginAsUser();
        $vendor = Vendor::factory()->create();
        Vendor::factory()->create();

        $this->visitRoute('vendors.edit', $vendor);
        $this->click('del-vendor-'.$vendor->id);
        $this->seeRouteIs('vendors.edit', [$vendor, 'action' => 'delete']);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('vendors', [
            'id' => $vendor->id,
        ]);
    }
}
