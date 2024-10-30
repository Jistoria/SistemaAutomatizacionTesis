<?php

namespace Modules\Auth\Tests;

use App\Models\Auth\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthServiceProviderTest extends TestCase
{
    use DatabaseTransactions;

      /** @var User */
      protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'Accept' => 'application/json',
        ]);

        // Crear un usuario para pruebas
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);
    }

    #[Test]
    public function it_logs_in_a_user_with_valid_credentials()
    {
        $response = $this->postJson(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Sesión Iniciada',
                 ])
                 ->assertJsonStructure(['data' => ['token']]);
    }

    #[Test]
    public function it_fails_to_log_in_with_invalid_credentials()
    {
        $response = $this->postJson(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Credenciales inválidas',
                 ]);
    }

    #[Test]
    public function it_logs_out_an_authenticated_user()
    {
        $token = $this->user->createToken('authToken')->accessToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson(route('auth.logout'));

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Sesión cerrada',
                 ]);
    }

    #[Test]
    public function it_gets_the_authenticated_user_information()
    {
        $token = $this->user->createToken('authToken')->accessToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson(route('auth.user'));

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Datos Usuario',
                 ])
                 ->assertJsonStructure(['data' => ['id', 'name', 'email']]);
    }
}
