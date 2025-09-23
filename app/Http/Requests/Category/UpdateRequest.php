<?php

namespace App\Http\Requests\Category;

class UpdateRequest extends CreateRequest
{
    public function rules()
    {
        $rules = parent::rules();

        unset($rules['name']);

        return $rules;
    }
}
