<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OwnerRequest extends FormRequest
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
            'name' => 'required|unique:owners|min:3|max:30|regex:/^[a-zA-Z 0-9]+$/',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo \'nome\' é obrigatório',
            'name.unique' => 'O \'nome\' informado já está sendo utilizado por outra pessoa',
            'name.min' => 'O campo \'nome\' deve contem pelo menos :min caracteres',
            'name.max' => 'O campo \'nome\' deve se limitar a :max caracteres',
            'name.regex' => 'O campo \'nome\' deve se limitar letras, números e espaços',
        ];
    }
}
