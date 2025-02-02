<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletRequest extends FormRequest
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
        /**
         * Não pode existir uma main_wallet inativa, porém, a regra 'accepted_if' não está limitando esse cenário
         * Removi então esta validação, e apliquei um tratamento no controller
         * 
         * Corrigir esse problema mais tarde, e remover o código adicionar no controller
         */
        return [
            'name' => 'required_without:_method|unique:wallets,name,NULL,NULL,owner_id,' . $this->get('owner_id') . '|between:3,30|regex:/^[a-zA-Z0-9 ,]+$/',
            'owner_id' => 'required_without:_method|integer',
            'description' => 'max:255|regex:/^[a-zA-Z0-9 ,]+$/',
            // 'active' => 'required_if:main_wallet,true|accepted_if:main_wallet,true',
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
            'owner_id.required_without' => 'É necessário informa a quem pertence esta carteira',
            'description.max' => 'O campo \'Descrição\' deve se limitar a :max caracteres',
            'description.regex' => 'O campo \'Descrição\' deve se limitar letras, números e espaços',
            // 'active.required_if' => 'Não é possível inativar uma carteira que esteja marcada como sendo a principal',
        ];
    }
}
