<?php

namespace Tests\App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserCanAuthenticateWithValidCredentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'secret'),
        ]);

        $response = $this->post('/api/token', [
            'login'    => $user->login,
            'password' => $password,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'result' => [
                'token'
            ]
        ]);
    }
}
