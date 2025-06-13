<?php

namespace App\Http\Requests\Card;

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
            'name' => 'required|unique:cards,name,NULL,NULL,wallet_id,' . $this->route()->parameter('wallet_id') . '|between:3,20|regex:"^[A-Za-zÀ-ÖØ-öø-ÿ0-9-. ]+$"',
            'card_type' => 'required|in:debit,credit',
            'first_day_month' => 'required_if:card_type,credit|integer|between:1,28',
            'days_to_expiration' => 'required_if:card_type,credit|integer|between:1,20',
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
            'card_type.required' => __('validation.required', ['attribute' => __('Card type')]),
            'card_type.in' => __('validation.in', ['attribute' => 'Card type']),
            'first_day_month.required' => __('validation.required', ['attribute' => __('First day of month')]),
            'first_day_month.integer' => __('validation.integer', ['attribute' => 'First day of month']),
            'first_day_month.between' => __('validation.between', ['attribute' => 'First day of month']),
            'days_to_expiration.required' => __('validation.required', ['attribute' => __('Days to expiration')]),
            'days_to_expiration.integer' => __('validation.integer', ['attribute' => 'Days to expiration']),
            'days_to_expiration.between' => __('validation.between', ['attribute' => 'Days to expiration']),
            'active.required' => __('validation.required', ['attribute' => 'Ativo']),
            'active.boolean' => __('validation.boolean', ['attribute' => 'Ativo']),
        ];
    }
}
