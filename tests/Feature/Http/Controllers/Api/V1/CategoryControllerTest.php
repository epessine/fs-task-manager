<?php

use App\Models\Category;
use App\Models\Task;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    actingAs(User::factory()->create());
});

describe('index', function () {
    it('returns a 200 response', function () {
        getJson(route('api.v1.categories.index'))->assertStatus(200);
    });

    it('returns a collection of categories', function () {
        Category::factory()->count(10)->create();

        getJson(route('api.v1.categories.index'))
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ])
            ->assertJsonCount(10, 'data');
    });
});

describe('store', function () {
    it('returns a 201 response', function () {
        $category = Category::factory()->make();

        postJson(route('api.v1.categories.store'), ['name' => $category->name])->assertStatus(201);
    });

    it('creates a new category', function () {
        $category = Category::factory()->make();

        postJson(route('api.v1.categories.store'), ['name' => $category->name]);

        assertDatabaseHas(Category::class, ['name' => $category->name]);
        assertDatabaseCount(Category::class, 1);
    });

    it('returns the created category', function () {
        $category = Category::factory()->make();

        postJson(route('api.v1.categories.store'), [
            'name' => $category->name,
        ])->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'created_at',
                'updated_at',
            ],
        ]);
    });
});

describe('update', function () {
    it('returns a 200 response', function () {
        $category = Category::factory()->create();

        putJson(route('api.v1.categories.update', $category), ['name' => 'Updated Category'])->assertStatus(200);
    });

    it('updates the category', function () {
        $category = Category::factory()->create();

        putJson(route('api.v1.categories.update', $category), ['name' => 'Updated Category']);

        expect($category->fresh()->name)->toBe('Updated Category');
        assertDatabaseCount(Category::class, 1);
    });

    it('returns the updated category', function () {
        $category = Category::factory()->create();

        putJson(route('api.v1.categories.update', $category), [
            'name' => 'Updated Category',
        ])->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'created_at',
                'updated_at',
            ],
        ]);
    });
});

describe('destroy', function () {
    it('returns a 204 response', function () {
        $category = Category::factory()->create();

        deleteJson(route('api.v1.categories.destroy', $category))->assertStatus(204);
    });

    it('deletes the category', function () {
        $category = Category::factory()->create();

        deleteJson(route('api.v1.categories.destroy', $category));

        assertModelMissing($category);
    });

    it('returns a 422 response if the category has tasks', function () {
        $category = Category::factory()
            ->has(Task::factory())
            ->create();

        deleteJson(route('api.v1.categories.destroy', $category))
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Category cannot be deleted because it has associated tasks.',
            ]);
    });
});
