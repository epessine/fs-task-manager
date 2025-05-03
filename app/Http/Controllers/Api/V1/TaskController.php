<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\IndexTaskRequest;
use App\Http\Requests\Api\V1\StoreTaskRequest;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Http\Resources\V1\TaskResource;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(IndexTaskRequest $request): ResourceCollection
    {
        $tasks = Task::query()
            ->with(['category', 'user'])
            ->tap(fn (Builder $query) => match ($request->input('status')) {
                'Completed' => $query->whereNotNull('completed_at'),
                'Pending' => $query->whereNull('completed_at'),
                default => $query,
            })
            ->when($request->input('category_id'), fn (Builder $query, $categoryId) => $query->where('category_id', $categoryId))
            ->when($request->input('user_id'), fn (Builder $query, $userId) => $query->where('user_id', $userId))
            ->when(
                $request->input('sort_by'),
                fn (Builder $query, $sortBy) => $query->orderBy($sortBy, $request->input('sort', 'asc')),
                fn (Builder $query) => $query->orderBy('updated_at', $request->input('sort', 'asc')),
            )
            ->paginate(request('per_page', 10), page: request('page', 1));

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): TaskResource
    {
        $task = Task::query()->create([...$request->validated(), 'user_id' => $request->user()->id]);

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
