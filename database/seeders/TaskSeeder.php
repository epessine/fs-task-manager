<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::factory(15)->create([
            'user_id' => fn () => User::query()->inRandomOrder()->first(),
            'category_id' => fn () => Category::query()->inRandomOrder()->first(),
        ]);

        Task::factory(5)->completedAt(now())->create([
            'user_id' => fn () => User::query()->inRandomOrder()->first(),
            'category_id' => fn () => Category::query()->inRandomOrder()->first(),
        ]);
    }
}
