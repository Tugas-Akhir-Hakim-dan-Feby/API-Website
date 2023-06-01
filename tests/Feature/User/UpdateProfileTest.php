<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    /** @test */
    public function can_not_update_profile_before_login()
    {
        $this->postJson(route('api.user.update.profile'), [
                'new_name' => 'nama baru',
                'new_email' => 'email@email.com',
                'new_password' => 'password123',
                'current_password' => 'password'
        ])->assertUnauthorized();
    }

   /** @test */
    public function can_not_update_profile_with_empty_params()
    {
        Sanctum::actingAs(User::find(1), ['admin-app']);

        $this->postJson(route('api.user.update.profile'), [
            'new_name' => '',
            'new_email' => '',
            'new_password' => '',
            'current_password' => ''
        ])->assertBadRequest();
    }

   /** @test */
    public function can_update_profile()
    {
        Sanctum::actingAs(User::find(1), ['admin-app']);

        $response = $this->postJson(route('api.user.update.profile'), [
            'new_name' => 'nama baru',
            'new_email' => 'email@baru.com',
        ])->assertStatus(400);
    }
}
