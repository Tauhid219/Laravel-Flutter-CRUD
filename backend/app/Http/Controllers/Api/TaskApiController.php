<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTaskRequest;
use App\Http\Requests\Api\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskApiController extends Controller
{
    /**
     * Display a listing of the authenticated user's tasks.
     */
    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Tasks retrieved successfully.',
            'data' => TaskResource::collection($tasks)
        ]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $request->user()->tasks()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'data' => new TaskResource($task)
        ], 201);
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);

        return response()->json([
            'success' => true,
            'message' => 'Task retrieved successfully.',
            'data' => new TaskResource($task)
        ]);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);

        $task->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'data' => new TaskResource($task->fresh())
        ]);
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.'
        ]);
    }
}
