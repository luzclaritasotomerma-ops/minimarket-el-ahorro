<?php

namespace Tests\Feature\Auth;


use Tests\TestCase;

class RegistrationTest extends TestCase
{
    public function test_registration_screen_is_not_available(): void
    {
        $response = $this->get('/register');

        $response->assertNotFound();
    }

    public function test_new_users_cannot_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Usuario de prueba',
            'email' => 'prueba@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertNotFound();

        $this->assertGuest();
    }
}
