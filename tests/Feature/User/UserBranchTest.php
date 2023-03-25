<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserBranchTest extends TestCase
{
    /** @test */
    public function can_not_get_data_admin_branch_before_login()
    {
        $response = $this->getJson(route('api.user.branch.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_get_data_admin_branch_after_login()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.user.branch.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_get_data_admin_branch_with_search_params()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.user.branch.index', [
            'search' => 'coba'
        ]));
        $result = collect($response->json()['data']);

        $this->assertTrue($result->count() > 0);
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_store_data_admin_branch_before_login()
    {
        $response = $this->postJson(route('api.user.branch.store'), [
            'name' => 'Coba 2',
            'email' => 'coba222@mailinator.com',
            'phone' => '08123456789',
            'position' => 'CEO',
            'address' => 'Jl. Losarang'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_store_data_admin_branch_without_body()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->postJson(route('api.user.branch.store'), [
            'name' => '',
            'email' => '',
            'phone' => '',
            'position' => '',
            'address' => ''
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function can_not_store_data_admin_branch_with_email_registered()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->postJson(route('api.user.branch.store'), [
            'name' => 'Coba 2',
            'email' => 'admin.pusat@mailinator.com',
            'phone' => '08123456789',
            'position' => 'CEO',
            'address' => 'Jl. Losarang'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function can_store_data_admin_branch()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $branch = Branch::create([
            'uuid' => Str::uuid(),
            'branch_name' => "coba",
            'branch_address' => "coba",
            'branch_phone' => 12345,
        ]);

        $response = $this->postJson(route('api.user.branch.store'), [
            'name' => 'Coba 2',
            'email' => 'coba222@mailinator.com',
            'phone' => '08123456789',
            'position' => 'CEO',
            'address' => 'Jl. Losarang',
            'status' => 1,
            'gender' => 'L',
            'birth_place' => 'Indrmayu',
            'date_birth' => '2023-10-20',
            'nip' => 12121212,
            'branch_id' => $branch->uuid
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function can_not_get_detail_admin_branch_before_login()
    {
        $response = $this->getJson(route('api.user.branch.show', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_get_detail_admin_branch_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.user.branch.show', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_get_detail_admin_branch()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $user = User::where('email', 'coba222@mailinator.com')->first();

        $response = $this->getJson(route('api.user.branch.show', $user->uuid));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_update_data_admin_branch_before_login()
    {
        $response = $this->putJson(route('api.user.branch.update', 1), [
            'name' => 'Coba 2',
            'email' => 'coba222@mailinator.com',
            'phone' => '08123456789',
            'position' => 'CEO',
            'address' => 'Jl. Losarang'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_update_data_admin_branch_without_body()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $user = User::where('email', 'coba222@mailinator.com')->first();

        $response = $this->putJson(route('api.user.branch.update', $user->uuid), [
            'name' => '',
            'email' => '',
            'phone' => '',
            'position' => '',
            'address' => ''
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function can_not_update_data_admin_branch_with_email_registered()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $user = User::where('email', 'coba222@mailinator.com')->first();

        $response = $this->putJson(route('api.user.branch.update', $user->uuid), [
            'name' => 'Coba 2',
            'email' => 'admin.pusat@mailinator.com',
            'phone' => '08123456789',
            'position' => 'CEO',
            'address' => 'Jl. Losarang'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function can_not_update_data_admin_branch_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.user.branch.update', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_update_data_admin_branch()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $user = User::where('email', 'coba222@mailinator.com')->first();
        $branch = Branch::where('branch_name', 'coba')->first();

        $response = $this->putJson(route('api.user.branch.update', $user->uuid), [
            'name' => 'Coba 2 Edited',
            'email' => 'coba222@mailinator.com',
            'phone' => '08123456789',
            'position' => 'CEO',
            'address' => 'Jl. Losarang',
            'status' => 1,
            'gender' => 'L',
            'birth_place' => 'Indrmayu',
            'date_birth' => '2023-10-20',
            'nip' => 12121212,
            'branch_id' => $branch->uuid
        ]);

        $this->assertNotNull(User::where('name', 'Coba 2 Edited')->first());

        $this->assertNull(User::where('name', 'Coba 2')->first());

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_delete_data_admin_branch_before_login()
    {
        $response = $this->deleteJson(route('api.user.branch.destroy', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_delete_data_admin_branch_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->deleteJson(route('api.user.branch.destroy', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_delete_data_admin_branch()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $user = User::where('email', 'coba222@mailinator.com')->first();

        $response = $this->deleteJson(route('api.user.branch.destroy', $user->uuid));

        $this->assertNull(Branch::where('uuid', $user->uuid)->first());
        $this->assertNull(User::where('email', 'coba222@mailinator.com')->first());

        $response->assertStatus(Response::HTTP_OK);
    }
}
