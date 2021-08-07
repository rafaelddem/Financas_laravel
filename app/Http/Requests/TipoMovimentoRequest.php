<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TipoMovimentoRequest extends FormRequest
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
            'relevancia' => ['required', Rule::in([0, 1, 2])], 
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo \'nome\' é obrigatório', 
            'nome.max' => 'O campo \'nome\' deve se limitar a :max caracteres', 
            'relevancia.required' => 'O campo \'relevancia\' é obrigatório', 
            'relevancia.in' => 'O campo \'relevancia\' não possui valor permitido', 
        ];
    }
}
