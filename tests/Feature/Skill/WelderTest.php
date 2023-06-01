<?php

namespace Tests\Feature\Skill;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelderTest extends TestCase
{
    /** @test */
    public function can_not_get_data_welder_skill_before_login()
    {
        $response = $this->getJson(route('api.skill.welder.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_get_data_welder_skill()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.skill.welder.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_store_data_welder_skill_before_login()
    {
        $response = $this->postJson(route('api.skill.welder.store'), [
            'name' => 'welder 3',
            'description' => 'las',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_store_data_welder_skill_with_empty_skill_name()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->postJson(route('api.skill.welder.store'), [
            'name' => '',
            'description' => '',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    public function can_store_data_welder_skill()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->postJson(route('api.skill.welder.store'), [
            'name' => 'welder 3',
            'description' => 'las',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function can_not_get_detail_welder_skill_before_login()
    {
        $response = $this->getJson(route('api.skill.welder.show', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_get_detail_welder_skill_with_invalid_id()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.skill.welder.show', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_get_detail_welder_skill()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->getJson(route('api.skill.welder.show', 1));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_update_data_welder_skill_before_login()
    {
        $response = $this->putJson(route('api.skill.welder.update', 1), [
            'name' => 'welder 3',
            'description' => 'skill welder 3',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_update_data_welder_skill_with_invalid_id()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->putJson(route('api.skill.welder.update', 999), [
            'name' => 'welder 3',
            'description' => 'las',
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_not_update_data_welder_skill_with_empty_skill_name()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->putJson(route('api.skill.welder.update', 1), [
            'name' => '',
            'description' => '',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    public function can_update_data_welder_skill()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->putJson(route('api.skill.welder.update', 1), [
            'name' => 'welder 3',
            'description' => 'welder las',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_not_delete_data_welder_skill_before_login()
    {
        $response = $this->deleteJson(route('api.skill.welder.destroy', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_delete_data_welder_skill_with_invalid_id()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->deleteJson(route('api.skill.welder.destroy', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_delete_data_welder_skill()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->deleteJson(route('api.skill.welder.destroy', 1));

        $response->assertStatus(Response::HTTP_OK);
    }
}
