<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    /** @test */
    public function can_not_update_password_before_login()
    {
        $this->postJson(route('api.user.update.password'), [
            'current_password' => 'password',
            'new_password' => 'password123',
            'confirm_password' => 'password123'
        ])->assertUnauthorized();
    }

    /** @test */
    public function can_not_update_password_with_empty_params()
    {
        Sanctum::actingAs(User::find(1), ['admin-app']);

        $this->postJson(route('api.user.update.password'), [
            'current_password' => '',
            'new_password' => '',
            'confirm_password' => ''
        ])->assertBadRequest();
    }

    /** @test */
    public function can_not_update_password_with_current_password_is_wrong()
    {
        Sanctum::actingAs(User::find(1), ['admin-app']);

        $this->postJson(route('api.user.update.password'), [
            'current_password' => 'password123',
            'new_password' => 'password',
            'confirm_password' => 'password'
        ])->assertBadRequest();
    }

    /** @test */
    public function can_not_update_password_with_not_same_new_password_and_confirm_password()
    {
        Sanctum::actingAs(User::find(1), ['admin-app']);

        $this->postJson(route('api.user.update.password'), [
            'current_password' => 'password',
            'new_password' => 'password123',
            'confirm_password' => 'password321'
        ])->assertBadRequest();
    }

    /** @test */
    public function can_update_password()
    {
        Sanctum::actingAs(User::find(1), ['admin-app']);

        $response = $this->postJson(route('api.user.update.password'), [
            'current_password' => 'new_password',
            'new_password' => 'password',
            'confirm_password' => 'password'
        ]);

        $response->assertOk();

        $user = User::find(1);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertFalse(Hash::check('new_password', $user->password));
    }
}
