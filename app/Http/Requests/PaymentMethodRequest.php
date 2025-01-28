<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'active' => filter_var($this->active, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function rules()
    {
        return [
            'name' => 'required_without:_method|between:3,30|regex:"^[A-Za-zÀ-ÖØ-öø-ÿ0-9 ]+$"|unique:payment_methods',
            'type' => 'required_without:_method|in:notes,transfer,debit,credit',
            'active' => 'required|boolean',
        ];
    }
}
