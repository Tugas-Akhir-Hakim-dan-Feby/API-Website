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
        $response = $this->getJson(route('api.user.hub.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_get_data_hub_after_login()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.user.hub.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_store_data_hub_before_login()
    {
        $response = $this->postJson(route('api.user.hub.store'), [
                'name' => 'Fara',
                'email' => 'fara@gmail.com',
                'position' => 'Sekretaris',
                'phone' => '0837464664',
                'address' => 'cirebon',
                'status' => '1',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_store_data_hub_without_body()
    {
        Sanctum::actingAs(User::find(1), ['*']);

       $response = $this->postJson(route('api.user.hub.store'), [
            'name' => '',
            'email' => '',
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

            $response = $this->postJson(route('api.user.hub.store'), [
                'name' => 'Fara',
                'email' => 'fara@gmail.com',
                'position' => 'Sekretaris',
                'phone' => '0837464664',
                'address' => 'cirebon',
                'status' => '1',
            ]);

            $user = User::where('name', 'Fara')->first();

            $this->assertNotNull($user);
            $response->assertStatus(Response::HTTP_CREATED);
        }

    /** @test */
    public function can_not_get_detail_hub_before_login()
    {
        $response = $this->getJson(route('api.user.hub.show', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_get_detail_hub_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.user.hub.show', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_get_detail_hub()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $user = User::where('name', 'Fara')->first();

        $response = $this->getJson(route('api.user.hub.show', $user->id));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_update_data_hub_before_login()
    {
        $response = $this->putJson(route('api.user.hub.update', 1), [
            'name' => 'Fara',
            'email' => 'fara@gmail.com',
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

        $response = $this->putJson(route('api.user.hub.update', 1), [
            'name' => 'Fara',
            'email' => 'fara@gmail.com',
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

        $response = $this->putJson(route('api.user.hub.update', 999), [
            'name' => 'Fara',
            'email' => 'fira@gmail.com',
            'position' => 'Anggota',
            'phone' => '0837464664',
            'address' => 'cirebon',
            'status' => '1',
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

   /** @test */
   public function can_update_data_hub()
   {
    Sanctum::actingAs(User::find(1), ['*']);

    $user = User::where('name', 'Fara')->first();

    $response = $this->putJson(route('api.user.hub.update', $user->id), [
            'name' => 'Fara',
            'email' => 'fira@gmail.com',
            'position' => 'Anggota',
            'phone' => '087532566',
            'address' => 'Jl. Raya No. 8',
            'status' => '0',
    ]);

    $user = User::where('id', 'Fara')->first();
    $this->assertNull($user);

    $user = User::where('id', 'Fara')->first();
    $this->assertNull($user);

    $response->assertStatus(Response::HTTP_OK);
   }

   /** @test */
   public function can_not_delete_data_hub_before_login()
   {
       $response = $this->deleteJson(route('api.user.hub.destroy', 1));

       $response->assertStatus(Response::HTTP_UNAUTHORIZED);
   }

   /** @test */
   public function can_not_delete_data_hub_with_id_not_found()
   {
       Sanctum::actingAs(User::find(1), ['*']);

       $response = $this->deleteJson(route('api.user.hub.destroy', 999));

       $response->assertStatus(Response::HTTP_NOT_FOUND);
   }

   /** @test */
   public function can_delete_data_hub()
   {
       Sanctum::actingAs(User::find(1), ['*']);

       $user = User::where('name', 'Fara')->first();

       $response = $this->deleteJson(route('api.user.hub.destroy', $user->id));

       $user = User::where('name', 'Fara')->first();
       $this->assertNull($user);

       $response->assertStatus(Response::HTTP_OK);
   }

}
