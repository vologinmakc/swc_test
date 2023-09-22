<?php

namespace Tests\Http\Controllers\Api\Event;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use DatabaseMigrations;

    private \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model $user;

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

    public function testCreateEvent()
    {
        $eventData = [
            'title' => 'Sample Event',
            'text'  => 'Event description'
        ];

        $response = $this->withHeaders([])->postJson('/api/event', $eventData);

        $response->assertStatus(200);
        $response->assertJsonStructure(['result' => ['event' => ['title', 'text']]]);
    }

    public function testGetEvents()
    {
        // Создадим несколько событий
        Event::factory()->count(3)->create([
            'creator_id' => $this->user->id
        ]);

        $response = $this->withHeaders([])->getJson('/api/events');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'result.events');
        $response->assertJsonStructure([
            'result' => [
                'events' => [
                    '*' => [
                        'id',
                        'title',
                        'text',
                        'creation_date',
                        'creator_id',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Проверим присоединение к событию в качестве участника
     * @return void
     */
    public function testJoinEvent()
    {
        $event = Event::factory()->create([
            'creator_id' => $this->user->id
        ]);

        $response = $this->withHeaders([])->postJson("/api/event/{$event->id}/join");

        $response->assertStatus(200);
        $this->assertDatabaseHas('event_user', [
            'user_id' => $this->user->id,
            'event_id' => $event->id
        ]);
    }

    /**
     * Проверим что пользователь может покинуть событие
     * @return void
     */
    public function testLeaveEvent()
    {
        $event = Event::factory()->create();
        $this->user->participatingEvents()->attach($event);

        $response = $this->withHeaders([])->postJson("/api/event/{$event->id}/leave");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('event_user', [
            'user_id' => $this->user->id,
            'event_id' => $event->id
        ]);
    }

    /**
     * Если вдруг пользователь покидает событие в котором его не было
     * @return void
     */
    public function testLeaveEventUserNotParticipating()
    {
        $event = Event::factory()->create();

        $response = $this->withHeaders([])->postJson("/api/event/{$event->id}/leave");

        $response->assertStatus(403);
        $response->assertJson(['error' => 'Вы не участвуете в данном событии']);
    }

    public function testDestroyEvent()
    {
        $event = Event::factory()->create(['creator_id' => $this->user->id]);

        $response = $this->withHeaders([])->deleteJson("/api/event/{$event->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function testDestroyNotOwnEvent()
    {
        // Создаем событие от имени другого пользователя
        $event = Event::factory()->create();

        $response = $this->withHeaders([])->deleteJson("/api/event/{$event->id}");

        $response->assertStatus(403);
        $response->assertJson(['error' => 'Пользователю доступно удаление только своего события']);

        // Проверяем, что событие все еще существует в базе данных
        $this->assertDatabaseHas('events', ['id' => $event->id]);
    }

}
