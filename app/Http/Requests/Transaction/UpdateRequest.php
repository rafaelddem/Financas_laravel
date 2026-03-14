<?php

namespace App\Http\Requests\Transaction;

use App\Models\PaymentMethod;

class UpdateRequest extends CreateRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'gross_value' => parent::convertToFloat($this->gross_value ?? null),
            'discount_value' => parent::convertToFloat($this->discount_value ?? null),
            'interest_value' => parent::convertToFloat($this->interest_value ?? null),
            'rounding_value' => parent::convertToFloat($this->rounding_value ?? null),
            'payment_type' => PaymentMethod::find($this->payment_method_id)?->type->value,
        ]);

        $installments = [];
        if ($this->installments) {
            foreach ($this->installments as $key => $installment) {
                $installments[$key] = [
                    'installment_number' => (int) $installment['installment_number'] ?? null,
                    'gross_value' => parent::convertToFloat($installment['gross_value'] ?? null),
                    'discount_value' => parent::convertToFloat($installment['discount_value'] ?? null),
                    'interest_value' => parent::convertToFloat($installment['interest_value'] ?? null),
                    'rounding_value' => parent::convertToFloat($installment['rounding_value'] ?? null),
                    'installment_date' => $installment['installment_date'],
                ];
            }

            $this->merge([
                'installments' => $installments
            ]);
        }
    }

    public function rules()
    {
        $rules = parent::rules();

        $rules += [
            'installments.*.installment_number' => 'required_if:payment_type,credit|numeric|between:1,99',
        ];

        return $rules;
    }
}
