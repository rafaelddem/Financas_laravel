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
            'name' => 'required_without:_method|unique:payment_methods|between:3,30|regex:"^[A-Za-zÀ-ÖØ-öø-ÿ0-9 ]+$"',
            'type' => 'required_without:_method|in:notes,transfer,debit,credit',
            'active' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required_without' => 'O campo \'Nome\' é obrigatório',
            'name.unique' => 'O \'Nome\' informado já está sendo utilizado por outra pessoa',
            'name.min' => 'O campo \'Nome\' deve contem pelo menos :min caracteres',
            'name.max' => 'O campo \'Nome\' deve se limitar a :max caracteres',
            'name.regex' => 'O campo \'Nome\' deve se limitar letras, números e espaços',
            'type.required_without' => 'O campo \'Tipo\' é obrigatório',
            'type.in' => 'O \'Tipo\' deve ser um dos seguintes: ' . __('Notes') . ', ' . __('Transfer') . ', ' . __('Debit') . ' ou ' . __('Credit') . '.',
            'active.required' => 'O campo \'Ativo\' é obrigatório',
            'active.boolean' => 'O valor informado no campo \'Ativo\' não foi aceito',
        ];
    }
}
