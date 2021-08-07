<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarteiraRequest extends FormRequest
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
            'nome' => 'required|max:50', 
            'pessoa' => 'required|integer', 
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo \'nome\' é obrigatório', 
            'pessoa.required' => 'É necessário informa a quem pertence esta carteira', 
            'nome.max' => 'O campo \'nome\' deve se limitar a :max caracteres', 
        ];
    }
}
