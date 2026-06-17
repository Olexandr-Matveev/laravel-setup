<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'assignee@example.com'],
            [
                'name' => 'Assignee User',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        Project::updateOrCreate(
            [
                'name' => 'Demo Project',
                'user_id' => $author->id,
            ],
            [
                'description' => 'Project for testing laboratory work 6',
            ]
        );
    }
}
