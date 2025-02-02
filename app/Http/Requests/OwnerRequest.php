<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OwnerRequest extends FormRequest
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
            'name' => 'required_without:_method|unique:owners|between:3,30|regex:"^[A-Za-zÀ-ÖØ-öø-ÿ0-9 ]+$"',
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
        ];
    }
}
