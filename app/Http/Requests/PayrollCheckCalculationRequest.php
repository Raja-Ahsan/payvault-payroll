<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollCheckCalculationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'pay_frequency' => ['required', 'string', 'max:32'],
            'pay_date' => ['required', 'date'],
            'period_begin_date' => ['nullable', 'date'],
            'period_end_date' => ['nullable', 'date', 'after_or_equal:period_begin_date'],
            'check_number' => ['nullable', 'integer', 'min:1'],
            'check_memo' => ['nullable', 'string', 'max:2000'],
            'income' => ['nullable', 'array'],
            'deductions' => ['nullable', 'array'],

            /* Needed so FormRequest::validated() keeps these trees (otherwise the calculator never sees form overrides). */
            'taxes' => ['nullable', 'array'],
            'leave' => ['nullable', 'array'],
            'obb' => ['nullable', 'array'],
            'other' => ['nullable', 'array'],
            'summary' => ['nullable', 'array'],
        ];
    }
}
