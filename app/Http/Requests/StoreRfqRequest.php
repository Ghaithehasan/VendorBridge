<?php

namespace App\Http\Requests;

use App\Models\PurchaseRequestLine;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreRfqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(auth()->user()->role, ['purchasing_officer', 'admin']);
    }

    public function rules(): array
    {
        return [
            'pr_line_id' => [
                'required',
                'integer',
                Rule::exists('pr_lines', 'pr_line_id'),
                Rule::unique('rfq', 'pr_line_id'),
            ],
            'rfq_date'           => ['required', 'date'],
            'quotation_due_date' => ['required', 'date', 'after_or_equal:rfq_date'],
            'currency_id'        => ['required', 'integer', Rule::exists('currencies', 'id')], // ← أضفناها
            'payment_terms'      => ['nullable', 'string', 'max:255'],
            'delivery_location'  => ['nullable', 'string', 'max:255'],
            'vendor_ids'         => ['required', 'array', 'min:1'],
            'vendor_ids.*'       => ['required', 'integer', Rule::exists('vendors', 'vendor_id')],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->has('pr_line_id')) {
                return;
            }

            $line = PurchaseRequestLine::with('purchaseRequest')
                ->find($this->input('pr_line_id'));

            if ($line && $line->purchaseRequest->status !== 'approved') {
                $validator->errors()->add(
                    'pr_line_id',
                    'An RFQ can only be created from a Purchase Request that has been approved.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'pr_line_id.required'           => 'Please specify which PR line this RFQ is for.',
            'pr_line_id.exists'             => 'The selected PR line does not exist.',
            'pr_line_id.unique'             => 'An RFQ has already been created for this PR line.',

            'rfq_date.required'             => 'Please provide the date this RFQ is being issued.',
            'rfq_date.date'                 => 'The RFQ date must be a valid date.',

            'quotation_due_date.required'   => 'Please provide the deadline by which vendors must respond.',
            'quotation_due_date.date'       => 'The quotation due date must be a valid date.',
            'quotation_due_date.after_or_equal' => 'The quotation due date cannot be before the RFQ date.',
            'currency_id.required' => 'Please select a currency for vendor responses.',
            'currency_id.exists'   => 'The selected currency is not valid.',
            'vendor_ids.required'           => 'Please select at least one vendor to receive this RFQ.',
            'vendor_ids.min'               => 'Please select at least one vendor to receive this RFQ.',
            'vendor_ids.*.exists'           => 'One or more selected vendors do not exist in the system.',
        ];
    }
}
