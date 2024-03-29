<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class NewPasswordTest extends TestCase
{
    /** @test */
    public function success_update_password()
    {
        $payload = [
            'email' => 'admin.app@mailinator.com',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];
        $user = User::where('email', $payload['email'])->first();

        $token = Password::createToken($user);

        $response = $this->postJson(route('api.auth.new.password'), array_merge(['token' => $token], $payload));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertTrue(Hash::check($payload['password'],  User::where('email', $payload['email'])->first()->password));
    }

    /** @test */
    public function failed_update_password_with_empty_request()
    {
        $payload = [
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $response = $this->postJson(route('api.auth.new.password'),  $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson([
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY
        ]);
    }

    /** @test */
    public function failed_update_password_with_wrong_password_reset_token()
    {
        $payload = [
            'email' => 'admin.app@mailinator.com',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];

        $token = Str::random(40);

        $response = $this->postJson(route('api.auth.new.password'), array_merge(['token' => $token], $payload));

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJson([
            'status_code' => Response::HTTP_BAD_REQUEST
        ]);
    }

    /** @test */
    public function failed_update_password_with_wrong_email()
    {
        $payload = [
            'email' => 'notfound@mailinator.com',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->postJson(route('api.auth.new.password'),  $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson([
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY
        ]);
    }

    /** @test */
    public function failed_update_password_with_minimum_password_length()
    {
        $payload = [
            'email' => 'admin.app@mailinator.com',
            'password' => 'pasword',
            'password_confirmation' => 'pasword',
        ];

        $response = $this->postJson(route('api.auth.new.password'),  $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson([
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY
        ]);
    }

    /** @test */
    public function failed_update_password_with_not_match_password_confirmation()
    {
        $payload = [
            'email' => 'admin.app@mailinator.com',
            'password' => 'password',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->postJson(route('api.auth.new.password'),  $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson([
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY
        ]);
    }
}
