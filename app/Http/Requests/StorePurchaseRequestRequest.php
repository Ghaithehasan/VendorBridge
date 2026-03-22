<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePurchaseRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(auth()->user()->role, ['requester', 'admin']);
    }

    public function rules(): array
    {
        return [
            'request_date'                         => ['required', 'date'],
            'notes'                                => ['nullable', 'string', 'max:1000'],

            'lines'                                => ['required', 'array', 'min:1'],
            'lines.*.material_id'                  => ['required', 'integer', Rule::exists('raw_materials', 'material_id')],
            'lines.*.quantity'                     => ['required', 'numeric', 'min:0.0001'],
            'lines.*.unit_id'                      => ['required', 'integer', Rule::exists('units', 'unit_id')],
            'lines.*.required_delivery_date'       => ['required', 'date', 'after_or_equal:today'],
            'lines.*.notes'                        => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'request_date.required'                      => 'Please specify the date this request is being raised.',
            'request_date.date'                          => 'The request date must be a valid date.',

            'lines.required'                             => 'A Purchase Request must contain at least one material line.',
            'lines.min'                                  => 'A Purchase Request must contain at least one material line.',
            'lines.array'                                => 'The lines data format is invalid.',

            'lines.*.material_id.required'               => 'Please select a raw material for each line.',
            'lines.*.material_id.exists'                 => 'The selected material does not exist in the system.',

            'lines.*.quantity.required'                  => 'Please enter a quantity for each material line.',
            'lines.*.quantity.numeric'                   => 'Quantity must be a number.',
            'lines.*.quantity.min'                       => 'Quantity must be greater than zero.',

            'lines.*.unit_id.required'                   => 'Please select a unit of measure for each line.',
            'lines.*.unit_id.exists'                     => 'The selected unit of measure does not exist in the system.',

            'lines.*.required_delivery_date.required'    => 'Please specify a required delivery date for each line.',
            'lines.*.required_delivery_date.date'        => 'The required delivery date must be a valid date.',
            'lines.*.required_delivery_date.after_or_equal' => 'The required delivery date cannot be in the past.',
        ];
    }
}
