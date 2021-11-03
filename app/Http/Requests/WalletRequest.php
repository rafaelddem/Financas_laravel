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
            'name' => 'required|max:50', 
            'owner' => 'required|integer', 
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo \'nome\' é obrigatório', 
            'owner.required' => 'É necessário informa a quem pertence esta carteira', 
            'name.max' => 'O campo \'nome\' deve se limitar a :max caracteres', 
        ];
    }
}
