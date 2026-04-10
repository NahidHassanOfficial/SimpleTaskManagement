<?php

use App\Models\Task;

it('filters by status', function () {
    Task::factory()->create(['status' => 'pending']);
    Task::factory()->create(['status' => 'completed']);

    $tasks = Task::filter(['status' => 'pending'])->get();

    expect($tasks)->toHaveCount(1)
        ->and($tasks->first()->status->value)->toBe('pending');
});

it('generates task code on creation', function () {
    $task = Task::factory()->create();

    expect($task->task_code)->toMatch('/^TASK-\d{4}$/');
});
