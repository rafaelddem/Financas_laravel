<?php

namespace App\Http\Requests\Wallet;

class UpdateRequest extends CreateRequest
{
    public function prepareForValidation()
    {
        if ($this->has('main_wallet')) {
            $this->merge([ 'main_wallet' => filter_var($this->main_wallet, FILTER_VALIDATE_BOOLEAN) ]);
        }

        if ($this->has('active')) {
            $this->merge([ 'active' => filter_var($this->active, FILTER_VALIDATE_BOOLEAN) ]);
        }
    }

    public function rules()
    {
        $rules = parent::rules();

        unset($rules['name'], $rules['owner_id']);

        return $rules;
    }
}
