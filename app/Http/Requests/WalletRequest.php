<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:30|regex:/^[a-zA-Z0-9 ,]+$/',
            'owner_id' => 'required|integer',
            'description' => 'max:255|regex:/^[a-zA-Z0-9 ,]+$/',
            'active' => 'required_if:main_wallet,1|integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo \'nome\' é obrigatório',
            'name.min' => 'O campo \'nome\' deve contem pelo menos :min caracteres',
            'name.max' => 'O campo \'nome\' deve se limitar a :max caracteres',
            'owner_id.required' => 'É necessário informa a quem pertence esta carteira',
            'name.regex' => 'O campo \'nome\' deve se limitar letras, números e espaços',
            'active.required_if' => 'Não é possível inativar uma carteira que esteja marcada como sendo a principal',
        ];
    }
}
