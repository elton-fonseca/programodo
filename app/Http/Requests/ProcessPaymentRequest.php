<?php

namespace App\Http\Requests;

use App\Enums\PaymentType;
use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
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
        $rules = [
            'payment_type' => payment_type_rules(),
            'instruction_id' => ['required', 'string'],
        ];

        if ($this->input('payment_type') === PaymentType::MBWAY->value) {
            $rules['prefix'] = ['required', 'max:3'];
            $rules['phone'] = ['required', 'min:6'];
        }

        return $rules;
    }
}
