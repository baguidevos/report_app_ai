<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MergeReportsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'report_ids' => 'required|array|min:2',
            'report_ids.*' => 'exists:reports,id',
            'frequency' => 'required|in:daily,weekly,monthly',
        ];
    }
}
