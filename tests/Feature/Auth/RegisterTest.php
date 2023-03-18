<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /** @test */
    public function can_register_with_valid_request()
    {
        Notification::fake();

        $request = [
            'name' => 'John Doe',
            'email' => 'john.doe@mailinator.com',
            'password' => 'password',
            'retype_password' => 'password'
        ];

        $response = $this->postJson(route('api.auth.register'), $request);

        $response->assertStatus(201);

        $response->assertJson([
            'status_code' => 201
        ]);
    }

    /** @test */
    public function cannot_register_with_existing_email()
    {
        $request = [
            'name' => 'John Doe',
            'email' => 'john.doe@mailinator.com',
            'password' => 'password',
            'retype_password' => 'password'
        ];

        $response = $this->postJson(route('api.auth.register'), $request);

        $response->assertStatus(400);

        $response->assertJson([
            'status_code' => 400
        ]);
    }

    /** @test */
    public function cannot_register_with_empty_request()
    {
        $request = [
            'name' => '',
            'email' => '',
            'password' => '',
            'retype_password' => ''
        ];

        $response = $this->postJson(route('api.auth.register'), $request);

        $response->assertStatus(400);

        $response->assertJson([
            'status_code' => 400
        ]);
    }

    /** @test */
    public function cannot_register_with_invalid_email()
    {
        $request = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password',
            'retype_password' => 'password'
        ];

        $response = $this->postJson(route('api.auth.register'), $request);

        $response->assertStatus(400);

        $response->assertJson([
            'status_code' => 400
        ]);
    }
}
