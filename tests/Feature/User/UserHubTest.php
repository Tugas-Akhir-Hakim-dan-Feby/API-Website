<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\User\Hub;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserHubTest extends TestCase
{
    /** @test */
    public function can_not_get_data_admin_hub_before_login()
    {
        $response = $this->getJson(route('api.user.hub.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_get_data_admin_hub_after_login()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.user.hub.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_get_data_admin_hub_with_search_params()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.user.hub.index', [
            'search' => 'coba'
        ]));
        $result = collect($response->json()['data']);

        $this->assertTrue($result->count() > 0);
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_store_data_admin_hub_before_login()
    {
        $response = $this->postJson(route('api.user.hub.store'), [
            'name' => 'Coba 2',
            'email' => 'coba2@mailinator.com',
            'phone' => '08123456789',
            'position' => 'CEO',
            'address' => 'Jl. Losarang'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_store_data_admin_hub_without_body()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->postJson(route('api.user.hub.store'), [
            'name' => '',
            'email' => '',
            'phone' => '',
            'position' => '',
            'address' => ''
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    public function can_not_store_data_admin_hub_with_email_registered()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->postJson(route('api.user.hub.store'), [
            'name' => 'Coba 2',
            'email' => 'admin.pusat@mailinator.com',
            'phone' => '08123456789',
            'position' => 'CEO',
            'address' => 'Jl. Losarang'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    public function can_store_data_admin_hub()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->postJson(route('api.user.hub.store'), [
            'name' => 'Coba 2',
            'email' => 'coba2@mailinator.com',
            'phone' => '08123456789',
            'position' => 'CEO',
            'address' => 'Jl. Losarang'
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function can_not_get_detail_admin_hub_before_login()
    {
        $response = $this->getJson(route('api.user.hub.show', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_get_detail_admin_hub_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.user.hub.show', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_get_detail_admin_hub()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $user = User::where('email', 'coba2@mailinator.com')->first();

        $response = $this->getJson(route('api.user.hub.show', $user->uuid));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_update_data_admin_hub_before_login()
    {
        $response = $this->putJson(route('api.user.hub.update', 1), [
            'name' => 'Coba 2',
            'email' => 'coba2@mailinator.com',
            'phone' => '08123456789',
            'position' => 'CEO',
            'address' => 'Jl. Losarang'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_update_data_admin_hub_without_body()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $user = User::where('email', 'coba2@mailinator.com')->first();

        $response = $this->putJson(route('api.user.hub.update', $user->uuid), [
            'name' => '',
            'email' => '',
            'phone' => '',
            'position' => '',
            'address' => ''
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    public function can_not_update_data_admin_hub_with_email_registered()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $user = User::where('email', 'coba2@mailinator.com')->first();

        $response = $this->putJson(route('api.user.hub.update', $user->uuid), [
            'name' => 'Coba 2',
            'email' => 'admin.pusat@mailinator.com',
            'phone' => '08123456789',
            'position' => 'CEO',
            'address' => 'Jl. Losarang'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    public function can_not_update_data_admin_hub_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.user.hub.update', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_update_data_admin_hub()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $user = User::where('email', 'coba2@mailinator.com')->first();

        $response = $this->putJson(route('api.user.hub.update', $user->uuid), [
            'name' => 'Coba 2 Edited',
            'email' => 'coba2@mailinator.com',
            'phone' => '08123456789',
            'position' => 'CEO',
            'address' => 'Jl. Losarang'
        ]);

        $this->assertNotNull(User::where('name', 'Coba 2 Edited')->first());

        $this->assertNull(User::where('name', 'Coba 2')->first());

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_delete_data_admin_hub_before_login()
    {
        $response = $this->deleteJson(route('api.user.hub.destroy', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_delete_data_admin_hub_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->deleteJson(route('api.user.hub.destroy', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_delete_data_admin_hub()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $user = User::where('email', 'coba2@mailinator.com')->first();

        $response = $this->deleteJson(route('api.user.hub.destroy', $user->uuid));

        $this->assertNull(Hub::where('uuid', $user->uuid)->first());
        $this->assertNull(User::where('email', 'coba2@mailinator.com')->first());

        $response->assertStatus(Response::HTTP_OK);
    }
}
