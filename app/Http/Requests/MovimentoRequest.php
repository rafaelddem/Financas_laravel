<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovimentoRequest extends FormRequest
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
            'dataMovimento' => 'required|date', 
            'tipoMovimento' => 'required|numeric', 
            'valorInicial' => 'required', 
            'valorDesconto' => 'required', 
            'valorArredondamento' => 'required', 
            'valorFinal' => 'required', 
            'numeroParcelas' => 'required', 
            'relevancia' => 'required', 
            'descricao' => 'max:255', 
            'valorInicialParcela.*' => 'required', 
            'dataVencimentoParcela.*' => 'required', 
        ];
    }
}