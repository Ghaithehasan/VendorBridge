<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\PurchaseRequest $pr */
        $pr   = $this->route('purchaseRequest');
        $user = $this->user();

        if ($user->role === 'admin') {
            return $pr->status === 'draft';
        }

        return $user->role === 'requester'
            && $pr->requester_id === $user->user_id
            && $pr->status === 'draft';
    }

    public function rules(): array
    {
        return [
            'request_date'                    => ['required', 'date'],
            'notes'                           => ['nullable', 'string', 'max:1000'],
            'lines'                           => ['required', 'array', 'min:1'],
            'lines.*.material_id'             => ['required', 'exists:raw_materials,material_id'],
            'lines.*.quantity'                => ['required', 'numeric', 'min:0.0001'],
            'lines.*.unit_id'                 => ['required', 'exists:units,unit_id'],
            'lines.*.required_delivery_date'  => ['required', 'date', 'after_or_equal:today'],
            'lines.*.notes'                   => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'request_date.required'                   => 'Please select the date this request is raised.',
            'lines.required'                          => 'A Purchase Request must have at least one material line.',
            'lines.min'                               => 'A Purchase Request must have at least one material line.',
            'lines.*.material_id.required'            => 'Each line must specify the raw material being requested.',
            'lines.*.material_id.exists'              => 'The selected raw material does not exist in the system.',
            'lines.*.quantity.required'               => 'Each line must specify the required quantity.',
            'lines.*.quantity.min'                    => 'Quantity must be greater than zero.',
            'lines.*.unit_id.required'                => 'Each line must specify the unit of measure.',
            'lines.*.unit_id.exists'                  => 'The selected unit of measure is not recognised.',
            'lines.*.required_delivery_date.required' => 'Each line must have a required delivery date.',
            'lines.*.required_delivery_date.after_or_equal' => 'Delivery date cannot be in the past.',
        ];
    }
}
