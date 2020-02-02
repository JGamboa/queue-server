<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\User::create([
            'name' => 'test user 1',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->command->info('Here is your test user 1 details to login:');
        $this->command->warn($user->email);
        $this->command->warn('Password is "password"');

        $user = \App\User::create([
            'name' => 'test user 2',
            'email' => 'testuser2@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->command->info('Here is your test user 2 details to login:');
        $this->command->warn($user->email);
        $this->command->warn('Password is "password"');
    }
}
