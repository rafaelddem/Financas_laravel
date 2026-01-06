<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'active' => filter_var($this->active, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function rules()
    {
        return [
            'name' => 'required|unique:owners,name|between:3,30|regex:"^[A-Za-zÀ-ÖØ-öø-ÿç0-9\-.() ]+$"',
            'active' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('Name')]),
            'name.unique' => __('validation.unique', ['attribute' => __('Name')]),
            'name.between' => __('validation.between', ['attribute' => __('Name')]),
            'name.regex' => __('The :attribute field must contain only letters, numbers and the characters: :characters.', ['attribute' => __('Name'), 'characters' => '"-", ".", "(" e ")"']),
            'active.boolean' => __('validation.boolean', ['attribute' => __('Active')]),
        ];
    }
}
