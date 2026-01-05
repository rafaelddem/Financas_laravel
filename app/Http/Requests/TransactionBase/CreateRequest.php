<?php

namespace App\Http\Requests\TransactionBase;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'payment_type' => PaymentMethod::find($this->payment_method_id)->type->value,
        ]);
    }

    public function rules()
    {
        return [
            'title' => 'required|between:3,50|regex:"^[A-Za-zÀ-ÖØ-öø-ÿç0-9\-() ]+$',
            'category_id' => 'required|exists:categories,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'source_wallet_id' => 'required|exists:wallets,id',
            'destination_wallet_id' => 'required|exists:wallets,id',
            'card_id' => 'required_if:payment_type,credit|required_if:payment_type,debit|exists:cards,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => __('validation.required', ['attribute' => __('Title')]),
            'title.between' => __('validation.between', ['attribute' => __('Title')]),
            'title.regex' => __('The :attribute field must contain only letters, numbers and the characters: :characters.', ['attribute' => __('Title'), 'characters' => '"-", "(" e ")"']),
            'category_id.required' => __('validation.required', ['attribute' => __('Category')]),
            'category_id.exists' => __('validation.exists', ['attribute' => __('Category')]),
            'payment_method_id.required' => __('validation.required', ['attribute' => __('Payment Method')]),
            'payment_method_id.exists' => __('validation.exists', ['attribute' => __('Payment Method')]),
            'source_wallet_id.required' => __('validation.required', ['attribute' => __('Source Wallet')]),
            'source_wallet_id.exists' => __('validation.exists', ['attribute' => __('Source Wallet')]),
            'destination_wallet_id.required' => __('validation.required', ['attribute' => __('Destination Wallet')]),
            'destination_wallet_id.exists' => __('validation.exists', ['attribute' => __('Destination Wallet')]),
            'card_id.required_if' => __('validation.required_if', ['attribute' => __('Card'), 'other' => __('Payment Method'), 'value' => __('Card')]),
            'card_id.exists' => __('validation.exists', ['attribute' => __('Card')]),
        ];
    }
}
