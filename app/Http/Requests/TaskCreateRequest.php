<?php

namespace App\Http\Requests;

use App\Enums\TaskSeverity;
use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskCreateRequest extends FormRequest
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
            'title' => 'required|string|max:50',
            'description' => 'nullable|string',
            // 'status'      => ['required', new Enum(TaskStatus::class)], // no need during create operation. defult value specified on db
            'severity' => ['required', new Enum(TaskSeverity::class)],
            'due_date' => 'nullable|date|after_or_equal:today',
            'assigned_to' => 'required|exists:users,id',
            'created_by' => 'sometimes|required|exists:users,id',
        ];
    }
}
