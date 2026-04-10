<?php

use App\Models\Task;
use App\Models\User;

it('shows tasks list', function () {
    Task::factory()->count(3)->create();

    $this->get('/tasks')
        ->assertStatus(200);
});

it('creates a task successfully', function () {
    $user = User::factory()->create();
    $assignedUser = User::factory()->create();

    $this->post('/tasks', [
        'title'       => 'Test Task',
        'severity'    => 'medium',
        'status'      => 'pending',
        'due_date'    => now()->addDays(5)->format('Y-m-d'),
        'assigned_to' => $assignedUser->id,
        'created_by'  => $user->id,
    ])
        ->assertRedirect('/tasks')
        ->assertSessionHas('success');

    $this->assertDatabaseHas('tasks', [
        'title'  => 'Test Task',
        'status' => 'pending',
    ]);
});

it('validates task creation', function () {
    $this->post('/tasks', [])
        ->assertSessionHasErrors(['title']);
});

it('updates a task', function () {
    $task = Task::factory()->create(['status' => 'pending']);

    $this->put("/tasks/{$task->id}", [
        'title' => 'Updated Title',
    ])
        ->assertRedirect('/tasks');

    $this->assertDatabaseHas('tasks', [
        'id'    => $task->id,
        'title' => 'Updated Title',
    ]);
});

it('prevents updating a completed task', function () {
    $task = Task::factory()->create(['status' => 'completed']);

    $this->put("/tasks/{$task->id}", [
        'title' => 'New Title',
    ])
        ->assertSessionHasErrors('error');
});

it('deletes a pending task', function () {
    $task = Task::factory()->create(['status' => 'pending']);

    $this->delete("/tasks/{$task->id}")
        ->assertRedirect('/tasks');

    $this->assertSoftDeleted($task);
});

it('prevents deleting a non-pending task', function () {
    $task = Task::factory()->create(['status' => 'in progress']);

    $this->delete("/tasks/{$task->id}")
        ->assertSessionHasErrors('error');

    $this->assertDatabaseHas('tasks', ['id' => $task->id]);
});

it('shows a single task', function () {
    $task = Task::factory()->create();

    $this->get("/tasks/{$task->id}")
        ->assertStatus(200);
});