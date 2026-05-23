<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskWebController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        $tasks = auth()->user()->tasks()->latest()->get();

        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('is_completed', true)->count();
        $pendingTasks = $tasks->where('is_completed', false)->count();

        return view('tasks.index', compact('tasks', 'totalTasks', 'completedTasks', 'pendingTasks'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'priority' => 'required|string|in:Low,Medium,High',
            'due_date' => 'nullable|date',
        ]);

        auth()->user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'is_completed' => false,
        ]);

        return redirect()->route('dashboard')->with('status', 'Task created successfully.');
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'priority' => 'required|string|in:Low,Medium,High',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->only('title', 'description', 'category', 'priority', 'due_date'));

        return redirect()->route('dashboard')->with('status', 'Task updated successfully.');
    }

    /**
     * Toggle the completion status of the task.
     */
    public function toggle(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);

        $task->update([
            'is_completed' => !$task->is_completed
        ]);

        return redirect()->route('dashboard')->with('status', 'Task status updated.');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);

        $task->delete();

        return redirect()->route('dashboard')->with('status', 'Task deleted successfully.');
    }
}
