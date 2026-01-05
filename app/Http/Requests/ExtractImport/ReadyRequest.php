<?php

namespace App\Http\Requests\ExtractImport;

use App\Enums\Relevance;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class ReadyRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'gross_value' => $this->convertToFloat($this->gross_value ?? null),
            'payment_type' => PaymentMethod::find($this->payment_method_id)->type->value,
        ]);
    }

    private function convertToFloat($value = null)
    {
        if (is_null($value) || $value == '0,00') {
            return 0;
        }
        $value = str_replace(['.', ','], ['', '.'], $value);
        return is_numeric($value) ? (float) $value : 'invalid value';
    }

    public function rules()
    {
        return [
            'title' => 'required|between:3,50|regex:"^[A-Za-zÀ-ÖØ-öø-ÿç0-9\-\/.*() ]+$"',
            'transaction_date' => 'required|date_format:Y-m-d',
            'category_id' => 'required|exists:categories,id',
            'relevance' => 'required|in:' . implode(',', Relevance::values()),
            'payment_method_id' => 'required|exists:payment_methods,id',
            'source_wallet_id' => 'required|exists:wallets,id',
            'destination_wallet_id' => 'required|exists:wallets,id',
            'card_id' => 'required_if:payment_type,credit|required_if:payment_type,debit|exists:cards,id',
            'description' => 'nullable|max:255|regex:"^[A-Za-zÀ-ÖØ-öø-ÿç0-9\-.,_*(): ]+$"',
            'installment_number' => 'sometimes|numeric|max:1',

            'gross_value' => 'sometimes|numeric|between:0.01,99999.99',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => __('validation.required', ['attribute' => __('Title')]),
            'title.between' => __('validation.between', ['attribute' => __('Title')]),
            'title.regex' => __('The :attribute field must contain only letters, numbers and the characters: :characters.', ['attribute' => __('Title'), 'characters' => '"-", "/", ".", "*", "(" e ")"']),
            'transaction_date.required' => __('validation.required', ['attribute' => __('Transaction Date')]),
            'transaction_date.date_format' => __('validation.date_format', ['attribute' => __('Transaction Date')]),
            'category_id.required' => __('validation.required', ['attribute' => __('Category')]),
            'category_id.exists' => __('validation.exists', ['attribute' => __('Category')]),
            'relevance.required' => __('validation.required', ['attribute' => __('Relevance')]),
            'relevance.in' => __('validation.in', ['attribute' => __('Relevance')]),
            'payment_method_id.required' => __('validation.required', ['attribute' => __('Payment Method')]),
            'payment_method_id.exists' => __('validation.exists', ['attribute' => __('Payment Method')]),
            'source_wallet_id.required' => __('validation.required', ['attribute' => __('Source Wallet')]),
            'source_wallet_id.exists' => __('validation.exists', ['attribute' => __('Source Wallet')]),
            'destination_wallet_id.required' => __('validation.required', ['attribute' => __('Destination Wallet')]),
            'destination_wallet_id.exists' => __('validation.exists', ['attribute' => __('Destination Wallet')]),
            'card_id.required_if' => __('validation.required_if', ['attribute' => __('Card'), 'other' => __('Payment Method'), 'value' => __('Card')]),
            'card_id.exists' => __('validation.exists', ['attribute' => __('Card')]),
            'description.max' => __('validation.max', ['attribute' => __('Description')]),
            'description.regex' => __('The :attribute field must contain only letters, numbers and the characters: :characters.', ['attribute' => __('Description'), 'characters' => '"-", ".", ",", "_", "*", ":", "(" e ")"']),
            'installment_number.max' => __('Credit Transactions (and their Installments) must have their Transaction Date within the period of the open (or soon-to-be-opened) invoice.'),

            'gross_value.required' => __('validation.required', ['attribute' => __('Gross Value')]),
            'gross_value.numeric' => __('The value must have two decimal places and be between min and max.', ['value' => __('Gross Value'), 'min' => '0,01', 'max' => '99999,99']),
            'gross_value.between' => __('The value must have two decimal places and be between min and max.', ['value' => __('Gross Value'), 'min' => '0,01', 'max' => '99999,99']),
        ];
    }
}
