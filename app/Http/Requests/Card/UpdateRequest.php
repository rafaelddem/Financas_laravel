<?php

namespace App\Http\Requests\Card;

class UpdateRequest extends CreateRequest
{
    public function prepareForValidation()
    {
        if ($this->has('active')) {
            $this->merge([ 'active' => filter_var($this->active, FILTER_VALIDATE_BOOLEAN) ]);
        }
    }

    public function rules()
    {
        $rules = parent::rules();

        unset($rules['name'], $rules['card_type'], $rules['active']);

        return $rules;
    }
}
