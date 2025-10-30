<?php

namespace App\Http\Requests\ExtractImport;

class UpdateRequest extends CreateRequest
{
    public function rules()
    {
        $rules = parent::rules();

        // unset($rules['name']);

        return $rules;
    }
}
