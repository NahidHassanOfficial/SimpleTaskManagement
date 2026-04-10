<?php

namespace App\Http\Requests;

use App\Enums\TaskSeverity;
use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'task_code'     => 'sometimes|string|max:10',
            'status'        => ['sometimes', new Enum(TaskStatus::class)],
            'severity'      => ['sometimes', new Enum(TaskSeverity::class)],
            'assigned_to'   => 'sometimes|exists:users,id',
            'due_date_from' => 'sometimes|date',
            'due_date_to'   => 'sometimes|date|after_or_equal:due_date_from',
        ];
    }
}