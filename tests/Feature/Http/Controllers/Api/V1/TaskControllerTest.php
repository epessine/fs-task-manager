<?php

use App\Models\Category;
use App\Models\Task;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\freezeTime;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function (): void {
    actingAs(User::factory()->create());
});

describe('index', function (): void {
    it('returns a 200 response', function () {
        getJson(route('api.v1.tasks.index'))->assertStatus(200);
    });

    it('can get all tasks', function (): void {
        Task::factory(5)->for(auth()->user())->create();

        getJson(route('api.v1.tasks.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'completed_at',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ])
            ->assertJsonCount(5, 'data');
    });
});

describe('store', function (): void {
    it('returns a 201 response', function (): void {
        $task = Task::factory()->make();

        postJson(route('api.v1.tasks.store'), ['title' => $task->title])->assertCreated();
    });

    it('creates a new task', function (): void {
        freezeTime();

        $category = Category::factory()->create();
        $task = Task::factory()->make();

        postJson(route('api.v1.tasks.store'), [
            'title' => $task->title,
            'description' => $task->description,
            'category_id' => $category->id,
            'completed_at' => now(),
        ]);

        assertDatabaseHas(Task::class, [
            'title' => $task->title,
            'description' => $task->description,
            'completed_at' => now(),
            'user_id' => auth()->id(),
            'category_id' => $category->id,
        ]);
        assertDatabaseCount(Task::class, 1);
    });

    it('returns the created task', function (): void {
        $task = Task::factory()->make();

        postJson(route('api.v1.tasks.store'), [
            'title' => $task->title,
            'description' => $task->description,
        ])
            ->assertCreated()
            ->assertJson([
                'data' => [
                    'title' => $task->title,
                    'description' => $task->description,
                ],
            ]);
    });
});

describe('update', function (): void {
    it('returns a 200 response', function (): void {
        $task = Task::factory()->for(auth()->user())->create();

        putJson(route('api.v1.tasks.update', $task), ['title' => 'Updated Title'])->assertOk();
    });

    it('updates the task', function (): void {
        $task = Task::factory()->for(auth()->user())->create();

        putJson(route('api.v1.tasks.update', $task), [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
        ]);

        assertDatabaseHas(Task::class, [
            'id' => $task->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
        ]);
    });

    it('should prevent updating task from another user', function (): void {
        $task = Task::factory()->create();

        putJson(route('api.v1.tasks.update', $task), [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
        ])->assertForbidden();
    });
});

describe('destroy', function (): void {
    it('returns a 204 response', function (): void {
        $task = Task::factory()->for(auth()->user())->create();

        deleteJson(route('api.v1.tasks.destroy', $task))->assertNoContent();
    });

    it('deletes the task', function (): void {
        $task = Task::factory()->for(auth()->user())->create();

        deleteJson(route('api.v1.tasks.destroy', $task));

        assertModelMissing($task);
    });

    it('should prevent deleting task from another user', function (): void {
        $task = Task::factory()->create();

        deleteJson(route('api.v1.tasks.destroy', $task))->assertForbidden();
    });
});
