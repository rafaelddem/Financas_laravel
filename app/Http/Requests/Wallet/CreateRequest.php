<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'main_wallet' => filter_var($this->main_wallet, FILTER_VALIDATE_BOOLEAN),
            'active' => filter_var($this->active, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function rules()
    {
        return [
            'name' => 'required|unique:wallets,name,NULL,NULL,owner_id,' . $this->get('owner_id') . '|between:3,45|regex:"^[A-Za-zÀ-ÖØ-öø-ÿ0-9-. ]+$"',
            'description' => 'max:255|regex:"^[A-Za-zÀ-ÖØ-öø-ÿ0-9-., ]+$"',
            'active' => 'required_if:main_wallet,true|accepted_if:main_wallet,true',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => 'Nome']),
            'name.unique' => __('validation.unique', ['attribute' => 'Nome']),
            'name.between' => __('validation.between', ['attribute' => 'Nome']),
            'name.regex' => __('The :attribute field must contain only letters, numbers, periods, dashes and spaces.', ['attribute' => 'Nome']),
            'description.max' => __('validation.max', ['attribute' => 'Descrição']),
            'description.regex' => __('The :attribute field must contain only letters, numbers, periods, dashes and spaces.', ['attribute' => 'Descrição']),
            'active.required_if' => __('A wallet marked as main cannot be inactive.'),
            'active.accepted_if' => __('A wallet marked as main cannot be inactive.'),
        ];
    }
}
