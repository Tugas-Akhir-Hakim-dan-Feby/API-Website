<?php

namespace Tests\Feature;
use App\Models\AdminHub;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class HubTest extends TestCase
{
    /** @test */
    public function can_not_get_data_hub_before_login()
    {
        $response = $this->getJson(route('api.hub.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_get_data_hub_after_login()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.hub.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_store_data_hub_before_login()
    {
        $response = $this->postJson(route('api.hub.store'), [
            'user_id' => '1',
            'position' => 'Anggota',
            'phone' => '087532566',
            'address' => 'Jl. Raya No. 8',
            'status' => '1',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_store_data_hub_without_body()
    {
        Sanctum::actingAs(User::find(1), ['*']);

       $response = $this->postJson(route('api.hub.store'), [
            'user_id' => '',
            'position' => '',
            'phone' => '',
            'address' => '',
            'status' => '',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

        /** @test */
        public function can_store_data_hub()
        {
            Sanctum::actingAs(User::find(1), ['*']);

            $response = $this->postJson(route('api.hub.store'), [
                'user_id' => '1',
                'position' => 'Anggota',
                'phone' => '087532566',
                'address' => 'Jl. Raya No. 8',
                'status' => '0',
            ]);

            $adminhub = AdminHub::where('user_id', '1')->first();

            $this->assertNotNull($adminhub);
            $response->assertStatus(Response::HTTP_CREATED);
        }

    /** @test */
    public function can_not_get_detail_hub_before_login()
    {
        $response = $this->getJson(route('api.hub.show', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_get_detail_hub_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.hub.show', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_get_detail_hub()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $adminhub = AdminHub::where('user_id', '1')->first();

        $response = $this->getJson(route('api.hub.show', $adminhub->id));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_update_data_hub_before_login()
    {
        $response = $this->putJson(route('api.hub.update', 1), [
            'user_id' => '1',
            'position' => 'Anggota',
            'phone' => '087532566',
            'address' => 'Jl. Raya No. 8',
            'status' => '0',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_update_data_hub_without_body()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->putJson(route('api.hub.update', 1), [
            'user_id' => '',
            'position' => '',
            'phone' => '',
            'address' => '',
            'status' => '',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    public function can_not_update_data_hub_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->putJson(route('api.hub.update', 999), [
            'user_id' => '1',
            'position' => 'Anggota',
            'phone' => '087532566',
            'address' => 'Jl. Raya No. 8',
            'status' => '0',
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

   /** @test */
   public function can_update_data_hub()
   {
    Sanctum::actingAs(User::find(1), ['*']);

    $adminhub = AdminHub::where('user_id', '1')->first();

    $response = $this->putJson(route('api.hub.update', $adminhub->id), [
            'user_id' => '11',
            'position' => 'Anggota',
            'phone' => '087532566',
            'address' => 'Jl. Raya No. 8',
            'status' => '0',
    ]);

    $adminhub = AdminHub::where('user_id', '1')->first();
    $this->assertNull($adminhub);

    $adminhub = AdminHub::where('user_id', '11')->first();
    $this->assertNotNull($adminhub);

    $response->assertStatus(Response::HTTP_OK);
   }

   /** @test */
   public function can_not_delete_data_hub_before_login()
   {
       $response = $this->deleteJson(route('api.hub.destroy', 1));

       $response->assertStatus(Response::HTTP_UNAUTHORIZED);
   }

   /** @test */
   public function can_not_delete_data_hub_with_id_not_found()
   {
       Sanctum::actingAs(User::find(1), ['*']);

       $response = $this->deleteJson(route('api.hub.destroy', 999));

       $response->assertStatus(Response::HTTP_NOT_FOUND);
   }

   /** @test */
   public function can_delete_data_hub()
   {
       Sanctum::actingAs(User::find(1), ['*']);

       $adminhub = AdminHub::where('user_id', '11')->first();

       $response = $this->deleteJson(route('api.hub.destroy', $adminhub->id));

       $adminhub = AdminHub::where('user_id', '11')->first();
       $this->assertNull($adminhub);

       $response->assertStatus(Response::HTTP_OK);
   }

}
