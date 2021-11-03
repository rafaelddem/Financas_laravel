<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MovementTypeRequest extends FormRequest
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
            'relevance' => ['required', Rule::in([0, 1, 2])], 
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo \'nome\' é obrigatório', 
            'name.max' => 'O campo \'nome\' deve se limitar a :max caracteres', 
            'relevance.required' => 'O campo \'relevância\' é obrigatório', 
            'relevance.in' => 'O campo \'relevância\' não possui valor permitido', 
        ];
    }
}
