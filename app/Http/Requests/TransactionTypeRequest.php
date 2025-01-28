<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionTypeRequest extends FormRequest
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
            'name' => 'required_without:_method|between:3,30|regex:"^[A-Za-zÀ-ÖØ-öø-ÿ0-9 ]+$"|unique:transaction_types',
            'relevance' => 'required|in:banal,relevant,indispensable',
            'active' => 'required|boolean',
        ];
    }
}
