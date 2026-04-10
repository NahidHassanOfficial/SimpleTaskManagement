<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'task_code',
        'title',
        'description',
        'severity',
        'status',
        'due_date',
        'completed_at',
        'assigned_to',
        'created_by',
    ];

    protected static function booted()
    {
        static::created(function ($task) {
            $task->updateQuietly([
                'task_code' => 'TASK-'.str_pad($task->id, 4, '0', STR_PAD_LEFT),
            ]);
        });
    }
}