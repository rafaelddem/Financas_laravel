<?php

namespace App\Http\Requests\PaymentMethod;

use App\Enums\PaymentType;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'name' => 'required|unique:payment_methods|between:3,30|regex:"^[A-Za-zÀ-ÖØ-öø-ÿ0-9-. ]+$"',
            'type' => 'required|in:' . implode(',', PaymentType::values()),
            'active' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => 'Nome']),
            'name.unique' => __('validation.unique', ['attribute' => 'Nome']),
            'name.between' => __('validation.between', ['attribute' => 'Nome']),
            'name.regex' => __('The :attribute field must contain only letters, numbers, periods, dashes and spaces.', ['attribute' => 'Nome']),
            'type.required' => __('validation.required', ['attribute' => 'Tipo']),
            'type.in' => __('validation.in', ['attribute' => 'Tipo']),
            'active.required' => __('validation.required', ['attribute' => 'Ativo']),
            'active.boolean' => __('validation.boolean', ['attribute' => 'Ativo']),
        ];
    }
}
