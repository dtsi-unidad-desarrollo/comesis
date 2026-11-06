<?php

namespace Tests\Feature;

use App\Http\Middleware\CheckAllowedIp;
use App\Models\AllowedIp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CheckAllowedIpTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_root_users_bypass_ip_check()
    {
        // Forzar entorno de producción para activar la validación del middleware
        $this->app->detectEnvironment(fn () => 'production');

        $rootUser = new User([
            'id' => 1,
            'name' => 'Root User',
            'email' => 'root@test.com',
            'rol' => 1,
        ]);

        $this->actingAs($rootUser);

        $middleware = new CheckAllowedIp();
        $request = Request::create('/panel', 'GET', [], [], [], ['REMOTE_ADDR' => '200.10.20.30']);

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }

    public function test_non_admin_user_is_blocked_if_ip_not_allowed()
    {
        $this->app->detectEnvironment(fn () => 'production');

        $regularUser = new User([
            'id' => 2,
            'name' => 'Cajero User',
            'email' => 'cajero@test.com',
            'rol' => 3,
        ]);

        $this->actingAs($regularUser);

        $middleware = new CheckAllowedIp();
        $request = Request::create('/panel', 'GET', [], [], [], ['REMOTE_ADDR' => '192.168.1.100']);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Tu IP no está autorizada para acceder a este sistema.');

        $middleware->handle($request, function ($req) {
            return response('OK');
        });
    }

    public function test_non_admin_user_passes_if_ip_is_whitelisted()
    {
        $this->app->detectEnvironment(fn () => 'production');

        AllowedIp::create([
            'ip_address' => '192.168.3.50',
            'description' => 'Taquilla 1',
            'status' => true,
        ]);

        $regularUser = new User([
            'id' => 3,
            'name' => 'Taquilla Operator',
            'email' => 'taquilla@test.com',
            'rol' => 3,
        ]);

        $this->actingAs($regularUser);

        $middleware = new CheckAllowedIp();
        $request = Request::create('/panel', 'GET', [], [], [], ['REMOTE_ADDR' => '192.168.3.50']);

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals(200, $response->getStatusCode());
    }
}

