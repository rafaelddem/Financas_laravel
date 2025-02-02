<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionTypeRequest extends FormRequest
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
            'name' => 'required_without:_method|unique:transaction_types|between:3,30|regex:"^[A-Za-zÀ-ÖØ-öø-ÿ0-9 ]+$"',
            'relevance' => 'required|in:banal,relevant,indispensable',
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
            'relevance.required_without' => 'O campo \'Relevância\' é obrigatório',
            'relevance.in' => 'O \'Relevância\' deve ser um dos seguintes: ' . __('Banal') . ', ' . __('Relevant') . ' ou ' . __('Indispensable') . '.',
            'active.required' => 'O campo \'Ativo\' é obrigatório',
            'active.boolean' => 'O valor informado no campo \'Ativo\' não foi aceito',
        ];
    }
}
