<?php

namespace App\Models;

use App\Enums\TaskSeverity;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

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

    protected $hidden = [
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'status'   => TaskStatus::class,
        'severity' => TaskSeverity::class,
        'due_date' => 'date',
    ];

    protected static function booted()
    {
        static::created(function ($task) {
            $task->updateQuietly([
                'task_code' => 'TASK-'.str_pad($task->id, 4, '0', STR_PAD_LEFT),
            ]);
        });
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['task_code'] ?? null, fn ($q, $v) => $q->where('task_code', $v))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['severity'] ?? null, fn ($q, $v) => $q->where('severity', $v))
            ->when($filters['assigned_to'] ?? null, fn ($q, $v) => $q->where('assigned_to', $v))
            ->when($filters['due_date_from'] ?? null, fn ($q, $v) => $q->whereDate('due_date', '>=', $v))
            ->when($filters['due_date_to'] ?? null, fn ($q, $v) => $q->whereDate('due_date', '<=', $v));
    }

    public function scopeSummary($query, $column = 'status')
    {
        return $query->select($column, \DB::raw('count(*) as total'))
            ->groupBy($column)
            ->toBase()
            ->get()
            ->pluck('total', $column);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}