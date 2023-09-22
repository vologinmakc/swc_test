<?php

namespace Tests\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'password' => Hash::make('secret'),
        ]);
        $this->accessToken = $this->user->createToken('test-token')->plainTextToken;
    }

    public function withHeaders(array $headers)
    {
        $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        return parent::withHeaders($headers);
    }

    public function testRegister()
    {
        // Подготовка тестовых данных
        $userData = [
            'login'      => 'test-user',
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'birth_date' => '1999-01-01',
            'password'   => 'secret123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(200);

        $response->assertJson(['error' => null]);

        $this->assertDatabaseHas('users', [
            'login'      => 'test-user',
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'birth_date' => '1999-01-01',
        ]);

        $response->assertJsonStructure(['result' => ['token']]);
    }

    public function testMe()
    {
        $response = $this->withHeaders([])->getJson('/api/me');

        $response->assertStatus(200);

        $response->assertJson(['error' => null]);

        $response->assertJsonStructure(['result' => ['user' => [
            'id',
            'login',
            'first_name',
            'last_name',
            'birth_date',
        ]]]);
    }

}
