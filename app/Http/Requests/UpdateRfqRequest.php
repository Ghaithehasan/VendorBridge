<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRfqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(auth()->user()->role, ['purchasing_officer', 'admin'], true);
    }

    public function rules(): array
    {
        return [
            'rfq_date'           => ['required', 'date'],
            'quotation_due_date' => ['required', 'date', 'after_or_equal:rfq_date'],
            'currency_id'        => ['required', 'integer', Rule::exists('currencies', 'id')], // ← أضفناها
            'payment_terms'      => ['nullable', 'string', 'max:255'],
            'delivery_location'  => ['nullable', 'string', 'max:255'],
            'vendor_ids'         => ['required', 'array', 'min:1'],
            'vendor_ids.*'       => ['required', 'integer', Rule::exists('vendors', 'vendor_id')],
        ];
    }
}
