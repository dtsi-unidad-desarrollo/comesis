<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_update_creates_an_audit_log_entry()
    {
        $user = User::create([
            'nombre' => 'Auditor',
            'email' => 'auditor@example.com',
            'password' => Hash::make('secret123'),
            'rol' => 3,
        ]);

        $this->actingAs($user);

        $this->withSession(['_token' => 'test-token'])->put('/perfil', [
            '_token' => 'test-token',
            'nombre' => 'Auditor Actualizado',
            'email' => 'auditor.actualizado@example.com',
            'current_password' => 'secret123',
            'password' => 'nueva123',
            'password_confirmation' => 'nueva123',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'update_profile',
        ]);

        $this->assertTrue(AuditLog::where('user_id', $user->id)->exists());
    }
}
