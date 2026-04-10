<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskFilterRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaskFilterRequest $taskFilters)
    {
        $filters = $taskFilters->validated();

        // Load task based on filters
        // Excluded large 'description' column for the optimized initial list render
        $tasks = Task::query()
            ->select(['id', 'task_code', 'title', 'status', 'severity', 'due_date', 'assigned_to', 'created_by', 'created_at'])
            ->with(['assignedUser:id,name', 'creator:id,name'])
            ->filter($filters)
            ->latest()
            ->paginate(30)->withQueryString();

        // Users list for assigned to, created by dropdown view
        $users = User::query()->orderBy('name', 'asc')->get();

        // summary of tasks list
        $totalTask = $tasks->total();
        $statusSummary = Task::query()->filter($filters)->summary('status');
        $severitySummary = Task::query()->filter($filters)->summary('severity');

        return Inertia::render('TasksList', [
            'tasks' => $tasks,
            'total_tasks' => $totalTask,
            'status_summary' => $statusSummary,
            'severity_summary' => $severitySummary,
            'users' => $users,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskCreateRequest $taskCreateRequest)
    {
        $validatedRequest = $taskCreateRequest->validated();
        try {
            Task::create($validatedRequest);

            return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
        } catch (\Throwable $th) {
            Log::error('Task creation failed: '.$th->getMessage(), ['request' => $validatedRequest]);

            return redirect()->back()->withErrors(['error' => 'Failed to create task. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load(['assignedUser:id,name', 'creator:id,name']);

        if (request()->wantsJson()) {
            return response()->json($task);
        }

        return Inertia::render('TaskDetails', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $task->load(['assignedUser:id,name', 'creator:id,name']);

        if (request()->wantsJson()) {
            return response()->json($task);
        }

        return Inertia::render('TaskEdit', [
            'task' => $task,
            'users' => User::query()->orderBy('name', 'asc')->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $taskUpdateRequest, Task $task)
    {
        $validatedRequest = $taskUpdateRequest->validated();
        // Let user not able to update task if it is already marked as completed
        if ($task->status === TaskStatus::COMPLETED) {
            return redirect()->back()->withErrors(['error' => 'Task cannot be updated as it is already in completed status.']);
        }

        try {
            $task->update($validatedRequest);

            return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Task update failed: '.$th->getMessage(), ['request' => $validatedRequest]);

            return redirect()->back()->withErrors(['error' => 'An error occurred while updating the task.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // Let user delete the mistakenly created task
        // If status != pending prevent deletion
        if ($task->status !== TaskStatus::PENDING) {
            return redirect()->back()->withErrors(['error' => 'Only pending tasks can be deleted to maintain work history.']);
        }

        try {
            $task->delete();

            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
        } catch (\Throwable $th) {
            Log::error('Task deletion failed: '.$th->getMessage(), ['task_id' => $task->id]);

            return redirect()->back()->withErrors(['error' => 'Failed to delete task. Please try again.']);
        }
    }
}
