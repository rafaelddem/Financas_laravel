<?php

namespace App\Http\Requests\ExtractModule;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|unique:extract_modules,name|between:3,50|regex:"^[A-Za-zÀ-ÖØ-öø-ÿ0-9-. ]+$"',
            'transaction_base_in_id' => 'required',
            'transaction_base_out_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('Name')]),
            'name.unique' => __('validation.unique', ['attribute' => __('Name')]),
            'name.between' => __('validation.between', ['attribute' => __('Name')]),
            'name.regex' => __('The :attribute field must contain only letters, numbers, periods, dashes and spaces.', ['attribute' => __('Name')]),
            'transaction_base_in_id.required' => __('validation.required', ['attribute' => __('Transaction Base In')]),
            'transaction_base_out_id.required' => __('validation.required', ['attribute' => __('Transaction Base Out')]),
        ];
    }
}
