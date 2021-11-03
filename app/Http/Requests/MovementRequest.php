<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovementRequest extends FormRequest
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
            'movement_date' => 'required|date', 
            'movement_type' => 'required|numeric', 
            'title' => 'required', 
            'gross_value' => 'required', 
            'descount_value' => 'required', 
            'rounding_value' => 'required', 
            'net_value' => 'required', 
            'installments' => 'required|numeric', 
            'relevance' => 'required', 
            'description' => 'max:255', 
            'installment_gross_value.*' => 'required', 
            'installment_duo_date.*' => 'required', 
        ];
    }
}