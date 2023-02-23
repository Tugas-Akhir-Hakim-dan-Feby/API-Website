<?php

namespace Tests\Feature\Skill;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExpertTest extends TestCase
{
    /** @test */
    public function can_not_get_data_expert_skill_before_login()
    {
        $response = $this->getJson(route('api.skill.expert.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_get_data_expert_skill()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.skill.expert.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_get_data_expert_skill_with_params()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.skill.expert.index', [
            'search' => 'skill name 1',
        ]));

        $result = collect($response->json()['data']);

        $this->assertTrue($result->where('skill_name', 'skill name 1')->count() > 0);
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_store_data_expert_skill_before_login()
    {
        $response = $this->postJson(route('api.skill.expert.store'), [
            'skill_name' => 'skill name 1',
            'skill_description' => 'skill description 1',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_store_data_expert_skill_with_empty_skill_name()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->postJson(route('api.skill.expert.store'), [
            'skill_name' => '',
            'skill_description' => '',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    public function can_store_data_expert_skill()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->postJson(route('api.skill.expert.store'), [
            'skill_name' => 'skill name 1',
            'skill_description' => 'skill description 1',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function can_not_get_detail_expert_skill_before_login()
    {
        $response = $this->getJson(route('api.skill.expert.show', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_get_detail_expert_skill_with_invalid_id()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.skill.expert.show', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_get_detail_expert_skill()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.skill.expert.show', 1));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_update_data_expert_skill_before_login()
    {
        $response = $this->putJson(route('api.skill.expert.update', 1), [
            'skill_name' => 'skill name 1',
            'skill_description' => 'skill description 1',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_update_data_expert_skill_with_invalid_id()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->putJson(route('api.skill.expert.update', 999), [
            'skill_name' => 'skill name 1',
            'skill_description' => 'skill description 1',
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_not_update_data_expert_skill_with_empty_skill_name()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->putJson(route('api.skill.expert.update', 1), [
            'skill_name' => '',
            'skill_description' => '',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    public function can_update_data_expert_skill()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->putJson(route('api.skill.expert.update', 1), [
            'skill_name' => 'skill name 1',
            'skill_description' => 'skill description 1',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_delete_data_expert_skill_before_login()
    {
        $response = $this->deleteJson(route('api.skill.expert.destroy', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_delete_data_expert_skill_with_invalid_id()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->deleteJson(route('api.skill.expert.destroy', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_delete_data_expert_skill()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->deleteJson(route('api.skill.expert.destroy', 1));

        $response->assertStatus(Response::HTTP_OK);
    }
}
