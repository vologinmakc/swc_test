<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $count = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $admin = \App\Models\User::factory()->create([
            'login'      => 'admin',
            'first_name' => 'Admin',
            'password'   => Hash::make('123456')
        ]);

        for ($i = 1; $i < 4; $i++) {
            Event::factory()->create([
                'creator_id' => $admin->id,
                'title'      => 'Событие ' . array_shift($count)
            ]);
        }

        // Создадим еще одного пользователя
        $user = \App\Models\User::factory()->create([
            'login'      => 'user',
            'first_name' => 'Пользователь',
            'password'   => Hash::make('123456')
        ]);

        for ($i = 1; $i < 3; $i++) {
            Event::factory()->create([
                'creator_id' => $user->id,
                'title'      => 'Событие ' . array_shift($count)
            ]);
        }
    }
}
