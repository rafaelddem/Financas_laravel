<?php

namespace App\Http\Requests\Transaction;

use App\Enums\PaymentType;
use App\Enums\Relevance;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'gross_value' => $this->convertToFloat($this->gross_value ?? null),
            'discount_value' => $this->convertToFloat($this->discount_value ?? null),
            'interest_value' => $this->convertToFloat($this->interest_value ?? null),
            'rounding_value' => $this->convertToFloat($this->rounding_value ?? null),
            'payment_type' => PaymentMethod::find($this->payment_method_id)->type->value,
        ]);

        $installments = [];
        if ($this->installments) {
            foreach ($this->installments as $key => $installment) {
                $installments[$key] = [
                    'gross_value' => $this->convertToFloat($installment['gross_value'] ?? null),
                    'discount_value' => $this->convertToFloat($installment['discount_value'] ?? null),
                    'interest_value' => $this->convertToFloat($installment['interest_value'] ?? null),
                    'rounding_value' => $this->convertToFloat($installment['rounding_value'] ?? null),
                    'installment_date' => $installment['installment_date'],
                ];
            }

            $this->merge([
                'installments' => $installments
            ]);
        }
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
            'title' => 'required|between:3,50|regex:"^[A-Za-zÀ-ÖØ-öø-ÿ0-9-., ]+$"',
            'transaction_date' => 'required|date_format:Y-m-d',
            'processing_date' => 'required|date_format:Y-m-d|after_or_equal:transaction_date',
            'category_id' => 'required|exists:categories,id',
            'relevance' => 'required|in:' . implode(',', Relevance::values()),
            'payment_method_id' => 'required|exists:payment_methods,id',
            'source_wallet_id' => 'required|exists:wallets,id',
            'destination_wallet_id' => 'required|exists:wallets,id',
            'card_id' => 'required_if:payment_type,credit|required_if:payment_type,debit|exists:cards,id',
            'description' => 'nullable|max:255|regex:"^[A-Za-zÀ-ÖØ-öø-ÿ0-9-., ]+$"',

            'gross_value' => 'required|numeric|between:0.01,99999.99',
            'discount_value' => 'nullable|numeric|between:0.00,99999.99',
            'interest_value' => 'nullable|numeric|between:-99999.99,99999.99',
            'rounding_value' => 'nullable|numeric|between:-99999.99,99999.99',

            'installments' => 'required_if:payment_type,credit|array',
            'installments.*.gross_value' => 'required_if:payment_type,credit|numeric|between:0.01,99999.99',
            'installments.*.discount_value' => 'nullable|numeric|between:0.00,99999.99',
            'installments.*.interest_value' => 'nullable|numeric|between:-99999.99,99999.99',
            'installments.*.rounding_value' => 'nullable|numeric|between:-99999.99,99999.99',
            'installments.*.installment_date' => 'required_if:payment_type,credit|date|after_or_equal:transaction_date',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => __('validation.required', ['attribute' => __('Title')]),
            'title.between' => __('validation.between', ['attribute' => __('Title')]),
            'title.regex' => __('The :attribute field must contain only letters, numbers, periods, dashes and spaces.', ['attribute' => __('Title')]),
            'transaction_date.required' => __('validation.required', ['attribute' => __('Transaction Date')]),
            'transaction_date.date_format' => __('validation.date_format', ['attribute' => __('Transaction Date')]),
            'processing_date.required' => __('validation.required', ['attribute' => __('Processing Date')]),
            'processing_date.date_format' => __('validation.date_format', ['attribute' => __('Processing Date')]),
            'processing_date.after_or_equal' => __('validation.after_or_equal', ['attribute' => __('Processing Date'), 'date' => __('Transaction Date')]),
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
            'description.regex' => __('The :attribute field must contain only letters, numbers, periods, dashes and spaces.', ['attribute' => __('Description')]),

            'gross_value.required' => __('validation.required', ['attribute' => __('Gross Value')]),
            'gross_value.numeric' => __('The value must have two decimal places and be between min and max.', ['value' => __('Gross Value'), 'min' => '0,01', 'max' => '99999,99']),
            'gross_value.between' => __('The value must have two decimal places and be between min and max.', ['value' => __('Gross Value'), 'min' => '0,01', 'max' => '99999,99']),
            'discount_value.required' => __('validation.required', ['attribute' => __('Gross Value')]),
            'discount_value.numeric' => __('The value must have two decimal places and be between min and max.', ['value' => __('Discount Value'), 'min' => '0,01', 'max' => '99999,99']),
            'discount_value.between' => __('The value must have two decimal places and be between min and max.', ['value' => __('Discount Value'), 'min' => '0,01', 'max' => '99999,99']),
            'interest_value.required' => __('validation.required', ['attribute' => __('Gross Value')]),
            'interest_value.numeric' => __('The value must have two decimal places and be between min and max.', ['value' => __('Interest Value'), 'min' => '-99999,99', 'max' => '99999,99']),
            'interest_value.between' => __('The value must have two decimal places and be between min and max.', ['value' => __('Interest Value'), 'min' => '-99999,99', 'max' => '99999,99']),
            'rounding_value.required' => __('validation.required', ['attribute' => __('Gross Value')]),
            'rounding_value.numeric' => __('The value must have two decimal places and be between min and max.', ['value' => __('Rounding Value'), 'min' => '-99999,99', 'max' => '99999,99']),
            'rounding_value.between' => __('The value must have two decimal places and be between min and max.', ['value' => __('Rounding Value'), 'min' => '-99999,99', 'max' => '99999,99']),

            'installments.required_if' => __('validation.required_if', ['attribute' => __('Gross Value (Installment)'), 'other' => __('Payment Method'), 'value' => __(PaymentType::Credit->name)]),
            'installments.*.gross_value.required_if' => __('validation.required_if', ['attribute' => __('Gross Value (Installment)'), 'other' => __('Payment Method'), 'value' => __(PaymentType::Credit->name)]),
            'installments.*.gross_value.numeric' => __('The value must have two decimal places and be between min and max.', ['value' => __('Gross Value (Installment)'), 'min' => '0,01', 'max' => '99999,99']),
            'installments.*.gross_value.between' => __('The value must have two decimal places and be between min and max.', ['value' => __('Gross Value (Installment)'), 'min' => '0,01', 'max' => '99999,99']),
            'installments.*.discount_value.required_if' => __('validation.required_if', ['attribute' => __('Gross Value (Installment)'), 'other' => __('Payment Method'), 'value' => __(PaymentType::Credit->name)]),
            'installments.*.discount_value.numeric' => __('The value must have two decimal places and be between min and max.', ['value' => __('Discount Value (Installment)'), 'min' => '0,01', 'max' => '99999,99']),
            'installments.*.discount_value.between' => __('The value must have two decimal places and be between min and max.', ['value' => __('Discount Value (Installment)'), 'min' => '0,01', 'max' => '99999,99']),
            'installments.*.interest_value.required_if' => __('validation.required_if', ['attribute' => __('Gross Value (Installment)'), 'other' => __('Payment Method'), 'value' => __(PaymentType::Credit->name)]),
            'installments.*.interest_value.numeric' => __('The value must have two decimal places and be between min and max.', ['value' => __('Interest Value (Installment)'), 'min' => '-99999,99', 'max' => '99999,99']),
            'installments.*.interest_value.between' => __('The value must have two decimal places and be between min and max.', ['value' => __('Interest Value (Installment)'), 'min' => '-99999,99', 'max' => '99999,99']),
            'installments.*.rounding_value.required_if' => __('validation.required_if', ['attribute' => __('Gross Value (Installment)'), 'other' => __('Payment Method'), 'value' => __(PaymentType::Credit->name)]),
            'installments.*.rounding_value.numeric' => __('The value must have two decimal places and be between min and max.', ['value' => __('Rounding Value (Installment)'), 'min' => '-99999,99', 'max' => '99999,99']),
            'installments.*.rounding_value.between' => __('The value must have two decimal places and be between min and max.', ['value' => __('Rounding Value (Installment)'), 'min' => '-99999,99', 'max' => '99999,99']),
            'installments.*.installment_date.after_or_equal' => __('validation.after_or_equal', ['attribute' => __('Installment Date'), 'date' => __('Transaction Date')]),
        ];
    }
}
