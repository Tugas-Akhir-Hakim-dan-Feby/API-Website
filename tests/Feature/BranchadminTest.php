<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Branchadmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BranchadminTest extends TestCase
{
    /** @test */
    public function can_not_get_data_branchadmin_before_login()
    {
        $response = $this->getJson(route('api.branchadmin.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_get_data_branchadmin_after_login()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.branchadmin.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_store_data_branchadmin_before_login()
    {
        $response = $this->postJson(route('api.branchadmin.store'), [
                'name' => 'mae',
                'email' => 'mae@gmail.com',
                'position' => 'anggota',
                'phone' => '08378498323',
                'address' => 'indramayu',
                'status' => '0',
                'branch_id' => '4',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_store_data_branchadmin_without_body()
    {
        Sanctum::actingAs(User::find(1), ['*']);

       $response = $this->postJson(route('api.branchadmin.store'), [
            'name' => '',
            'email' => '',
            'position' => '',
            'phone' => '',
            'address' => '',
            'status' => '',
            'branch_id' => '',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

        /** @test */
        public function can_store_data_branchadmin()
        {
            Sanctum::actingAs(User::find(1), ['*']);

            $response = $this->postJson(route('api.branchadmin.store'), [
                'name' => 'mae',
                'email' => 'mae@gmail.com',
                'position' => 'anggota',
                'phone' => '08378498323',
                'address' => 'indramayu',
                'status' => '0',
                'branch_id' => '4',

            ]);

            $user = User::where('name', 'mae')->first();

            $this->assertNotNull($user);
            $response->assertStatus(Response::HTTP_CREATED);
        }

    /** @test */
    public function can_not_get_detail_branchadmin_before_login()
    {
        $response = $this->getJson(route('api.branchadmin.show', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_get_detail_branchadmin_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.branchadmin.show', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_get_detail_branchadmin()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $user = User::where('name', 'mae')->first();

        $response = $this->getJson(route('api.branchadmin.show', $user->id));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_update_data_branchadmin_before_login()
    {
        $response = $this->putJson(route('api.branchadmin.update', 1), [
                'name' => 'mae',
                'email' => 'mae@gmail.com',
                'position' => 'anggota',
                'phone' => '08378498323',
                'address' => 'indramayu',
                'status' => '0',
                'branch_id' => '4',

        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_update_data_branchadmin_without_body()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->putJson(route('api.branchadmin.update', 1), [
            'name' => 'mae',
            'email' => 'mae@gmail.com',
            'position' => '',
            'phone' => '',
            'address' => '',
            'status' => '',
            'branch_id' => '4',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }


   /** @test */
   public function can_update_data_branchadmin()
   {
    Sanctum::actingAs(User::find(1), ['*']);

    $user = User::where('name', 'mae')->first();

    $response = $this->putJson(route('api.branchadmin.update', $user->id), [
        'name' => 'mae',
        'email' => 'mae@gmail.com',
        'position' => 'anggota',
        'phone' => '08378498323',
        'address' => 'indramayu',
        'status' => '0',
        'branch_id' => '4',
    ]);

    $user = User::where('id', 'mae')->first();
    $this->assertNull($user);

    $user = User::where('id', 'mae')->first();
    $this->assertNull($user);

    $response->assertStatus(Response::HTTP_OK);
   }

   /** @test */
   public function can_not_delete_data_branchadmin_before_login()
   {
       $response = $this->deleteJson(route('api.branchadmin.destroy', 1));

       $response->assertStatus(Response::HTTP_UNAUTHORIZED);
   }

   /** @test */
   public function can_not_delete_data_branchadmin_with_id_not_found()
   {
       Sanctum::actingAs(User::find(1), ['*']);

       $response = $this->deleteJson(route('api.branchadmin.destroy', 999));

       $response->assertStatus(Response::HTTP_NOT_FOUND);
   }

   /** @test */
   public function can_delete_data_branchadmin()
   {
       Sanctum::actingAs(User::find(1), ['*']);

       $user = User::where('name', 'mae')->first();

       $response = $this->deleteJson(route('api.branchadmin.destroy', $user->id));

       $user = User::where('name', 'mae')->first();
       $this->assertNull($user);

       $response->assertStatus(Response::HTTP_OK);
   }
}
