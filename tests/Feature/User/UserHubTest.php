<?php

namespace Tests\Feature\User;

use App\Models\User;
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
}
