<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstallmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'movement' => 'required|numeric', 
            'installment_number' => 'required|numeric', 
            'duo_date' => 'required|date', 
            'payment_date' => 'date', 
            'gross_value' => 'required|numeric', 
            'descount_value' => 'numeric', 
            'interest_value' => 'numeric', 
            'rounding_value' => 'numeric', 
            'net_value' => 'required|numeric', 
            'source_wallet' => 'required|numeric', 
            'destination_wallet' => 'required|numeric', 
        ];
    }
}
