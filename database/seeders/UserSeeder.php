<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = \App\Models\User::factory()->create(['email' => 'admin@example.com']);
        $admin->assignRole('admin');
        $user = \App\Models\User::factory()->create(['email' => 'lapa@example.com']);
        $user->assignRole('patient');
        \App\Models\Patient::factory()->create(['user_id' => $user->id]);
    }
}
