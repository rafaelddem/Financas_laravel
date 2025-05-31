<?php

namespace App\Http\Requests\TransactionType;

class UpdateRequest extends CreateRequest
{
    public function rules()
    {
        $rules = parent::rules();

        unset($rules['name']);

        return $rules;
    }
}
