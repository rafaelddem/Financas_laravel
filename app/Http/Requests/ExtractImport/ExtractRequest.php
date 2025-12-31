<?php

namespace App\Http\Requests\ExtractImport;

use Illuminate\Foundation\Http\FormRequest;

class ExtractRequest extends FormRequest
{
    public function rules()
    {
        return [
            'module_id' => 'required',
            'extract_file' => 'required',
            'transaction_base_id_in' => 'required|exists:transaction_bases,id',
            'transaction_base_id_out' => 'required|exists:transaction_bases,id',
        ];
    }

    public function messages()
    {
        return [
            'module_id.required' => __('validation.required', ['attribute' => __('Extract Module')]),
            'extract_file.required' => __('validation.required', ['attribute' => __('Extract File')]),
            'transaction_base_id_in.required' => __('validation.required', ['attribute' => __('Transaction Base In')]),
            'transaction_base_id_in.exists' => __('validation.exists', ['attribute' => __('Transaction Base In')]),
            'transaction_base_id_out.required' => __('validation.required', ['attribute' => __('Transaction Base Out')]),
            'transaction_base_id_out.exists' => __('validation.exists', ['attribute' => __('Transaction Base Out')]),
        ];
    }
}
