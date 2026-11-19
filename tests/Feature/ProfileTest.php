<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        // Crear un rol para el usuario de prueba
        $role = Role::create([
            'name' => 'Administrador',
            'description' => 'Rol de administrador',
        ]);

        // Crear el usuario asignándole el rol
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        // Verificar que pueda ingresar al dashboard
        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
    }
}


