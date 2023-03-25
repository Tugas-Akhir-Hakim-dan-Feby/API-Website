<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BranchTest extends TestCase
{
    /** @test */
    public function can_not_get_data_branch_before_login()
    {
        $response = $this->getJson(route('api.branch.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_get_data_branch_after_login()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.branch.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_store_data_branch_before_login()
    {
        $response = $this->postJson(route('api.branch.store'), [
            'name' => 'Branch 1',
            'address' => 'Jl. Raya No. 1',
            'phone' => '08123456789',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_store_data_branch_without_body()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->postJson(route('api.branch.store'), [
            'branch_name' => '',
            'branch_address' => '',
            'branch_phone' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function can_store_data_branch()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->postJson(route('api.branch.store'), [
            'branch_name' => 'Branch 1',
            'branch_address' => 'Jl. Raya No. 1',
            'branch_phone' => '08123456789',
        ]);

        $branch = Branch::where('branch_name', 'Branch 1')->first();

        $this->assertNotNull($branch);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function can_not_get_detail_branch_before_login()
    {
        $response = $this->getJson(route('api.branch.show', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_get_detail_branch_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.branch.show', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_get_detail_branch()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $branch = Branch::where('branch_name', 'Branch 1')->first();

        $response = $this->getJson(route('api.branch.show', $branch->uuid));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_update_data_branch_before_login()
    {
        $response = $this->putJson(route('api.branch.update', 1), [
            'branch_name' => 'Branch 1',
            'branch_address' => 'Jl. Raya No. 1',
            'branch_phone' => '08123456789',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_update_data_branch_without_body()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->putJson(route('api.branch.update', 1), [
            'branch_name' => '',
            'branch_address' => '',
            'branch_phone' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function can_not_update_data_branch_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->putJson(route('api.branch.update', 999), [
            'branch_name' => 'Branch 1',
            'branch_address' => 'Jl. Raya No. 1',
            'branch_phone' => '08123456789',
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_update_data_branch()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $branch = Branch::where('branch_name', 'Branch 1')->first();

        $response = $this->putJson(route('api.branch.update', $branch->uuid), [
            'branch_name' => 'Branch 1 Edited',
            'branch_address' => 'Jl. Raya No. 1',
            'branch_phone' => '08123456789',
        ]);

        $branch = Branch::where('branch_name', 'Branch 1')->first();
        $this->assertNull($branch);

        $branch = Branch::where('branch_name', 'Branch 1 Edited')->first();
        $this->assertNotNull($branch);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_delete_data_branch_before_login()
    {
        $response = $this->deleteJson(route('api.branch.destroy', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_delete_data_branch_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->deleteJson(route('api.branch.destroy', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_delete_data_branch()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $branch = Branch::where('branch_name', 'Branch 1 Edited')->first();

        $response = $this->deleteJson(route('api.branch.destroy', $branch->uuid));

        $branch = Branch::where('branch_name', 'Branch 1 Edited')->first();
        $this->assertNull($branch);

        $response->assertStatus(Response::HTTP_OK);
    }
}
