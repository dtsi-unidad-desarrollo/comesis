<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_their_profile_data_and_password()
    {
        $user = User::create([
            'nombre' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'password' => Hash::make('secret123'),
            'rol' => 3,
        ]);

        $this->actingAs($user);

        $response = $this->withSession(['_token' => 'test-token'])->put('/perfil', [
            '_token' => 'test-token',
            'nombre' => 'Ana Actualizada',
            'email' => 'ana.actualizada@example.com',
            'current_password' => 'secret123',
            'password' => 'nueva123',
            'password_confirmation' => 'nueva123',
        ]);

        $response->assertRedirect(route('admin.perfil.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nombre' => 'Ana Actualizada',
            'email' => 'ana.actualizada@example.com',
        ]);

        $user->refresh();
        $this->assertTrue(Hash::check('nueva123', $user->password));
    }
}
