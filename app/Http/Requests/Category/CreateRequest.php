<?php

namespace App\Http\Requests\Category;

use App\Enums\Relevance;
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
            'name' => 'required|unique:categories|between:3,30|regex:"^[A-Za-zÀ-ÖØ-öø-ÿç0-9\-.() ]+$"',
            'relevance' => 'required|in:' . implode(',', Relevance::values()),
            'active' => 'required|boolean',
            'description' => 'max:255|regex:"^[A-Za-zÀ-ÖØ-öø-ÿç0-9\-.,_*(): ]+$"',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('Name')]),
            'name.unique' => __('validation.unique', ['attribute' => __('Name')]),
            'name.between' => __('validation.between', ['attribute' => __('Name')]),
            'name.regex' => __('The :attribute field must contain only letters, numbers and the characters: :characters.', ['attribute' => __('Name'), 'characters' => '"-", ".", "(" e ")"']),
            'relevance.required' => __('validation.required', ['attribute' => __('Relevance')]),
            'relevance.in' => __('validation.in', ['attribute' => __('Relevance')]),
            'active.required' => __('validation.required', ['attribute' => __('Active')]),
            'active.boolean' => __('validation.boolean', ['attribute' => __('Active')]),
            'description.max' => __('validation.max', ['attribute' => __('Description')]),
            'description.regex' => __('The :attribute field must contain only letters, numbers and the characters: :characters.', ['attribute' => __('Description'), 'characters' => '"-", ".", ",", "_", "*", ":", "(" e ")"']),
        ];
    }
}
