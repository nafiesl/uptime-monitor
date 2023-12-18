<?php

namespace Tests\Feature;

use App\Models\Vendor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageVendorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_vendor_list_in_vendor_index_page()
    {
        $vendor = Vendor::factory()->create();

        $this->loginAsUser();
        $this->visitRoute('vendors.index');
        $this->see($vendor->title);
    }

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'title'       => 'Vendor 1 title',
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
    public function validate_vendor_title_is_required()
    {
        $this->loginAsUser();

        // title empty
        $this->post(route('vendors.store'), $this->getCreateFields(['title' => '']));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_vendor_title_is_not_more_than_60_characters()
    {
        $this->loginAsUser();

        // title 70 characters
        $this->post(route('vendors.store'), $this->getCreateFields([
            'title' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('title');
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
            'title'       => 'Vendor 1 title',
            'description' => 'Vendor 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_edit_a_vendor()
    {
        $this->loginAsUser();
        $vendor = Vendor::factory()->create(['title' => 'Testing 123']);

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
    public function validate_vendor_title_update_is_required()
    {
        $this->loginAsUser();
        $vendor = Vendor::factory()->create(['title' => 'Testing 123']);

        // title empty
        $this->patch(route('vendors.update', $vendor), $this->getEditFields(['title' => '']));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_vendor_title_update_is_not_more_than_60_characters()
    {
        $this->loginAsUser();
        $vendor = Vendor::factory()->create(['title' => 'Testing 123']);

        // title 70 characters
        $this->patch(route('vendors.update', $vendor), $this->getEditFields([
            'title' => str_repeat('Test Title', 7),
        ]));
        $this->assertSessionHasErrors('title');
    }

    /** @test */
    public function validate_vendor_description_update_is_not_more_than_255_characters()
    {
        $this->loginAsUser();
        $vendor = Vendor::factory()->create(['title' => 'Testing 123']);

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
