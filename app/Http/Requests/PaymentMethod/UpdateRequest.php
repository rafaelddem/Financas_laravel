<?php

namespace App\Http\Requests\PaymentMethod;

class UpdateRequest extends CreateRequest
{
    public function rules()
    {
        return [
            'active' => parent::rules()['active']
        ];
    }
}
