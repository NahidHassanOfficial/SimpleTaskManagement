<?php

namespace App\Http\Requests;

use App\Enums\TaskSeverity;
use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskUpdateRequest extends FormRequest
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
            'title'       => 'sometimes|required|string|max:50',
            'description' => 'sometimes|nullable|string',
            'status'      => ['sometimes', 'required', new Enum(TaskStatus::class)],
            'severity'    => ['sometimes', 'required', new Enum(TaskSeverity::class)],
            'due_date'    => 'sometimes|nullable|date|after_or_equal:today',
            'assigned_to' => 'sometimes|required|exists:users,id',
        ];
    }
}