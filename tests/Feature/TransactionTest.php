<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ 'role' => 'admin' ]);
        $this->actingAs($user);
    }

    public function test_index(): void
    {
        $response = $this->get(route('transaction.list'));

        $response->assertStatus(200);
    }

    public function test_create(): void
    {
        $response = $this->get(route('transaction.create'));

        $response->assertStatus(200);
    }

    public function test_store_successfully(): void
    {
        $transaction = Transaction::factory()->simple()->make()->toArray();
        $transaction['transaction_date'] = str_split($transaction['transaction_date'], 10)[0];
        $transaction['processing_date'] = str_split($transaction['processing_date'], 10)[0];

        $this->post(route('transaction.store'), $transaction)
            ->assertRedirect(route('transaction.list', ['message' => __('Data created successfully.')]));

        $this->assertDatabaseHas('transactions', $transaction);
    }

    public function test_store_fail_without_required_data(): void
    {
        // $transaction = Transaction::factory()->simple()->make()->toArray();
        // unset($transaction['title']);
        // unset($transaction['transaction_date']);
        // unset($transaction['processing_date']);
        // unset($transaction['category_id']);
        // unset($transaction['relevance']);
        // unset($transaction['payment_method_id']);
        // unset($transaction['source_wallet_id']);
        // unset($transaction['destination_wallet_id']);
        // unset($transaction['gross_value']);
        // unset($transaction['discount_value']);
        // unset($transaction['interest_value']);
        // unset($transaction['rounding_value']);

        $this->post(route('transaction.store'), [])
            ->assertSessionHasErrors(['title' => __('validation.required', ['attribute' => __('Title')])])
            ->assertSessionHasErrors(['transaction_date' => __('validation.required', ['attribute' => __('Transaction Date')])])
            ->assertSessionHasErrors(['processing_date' => __('validation.required', ['attribute' => __('Processing Date')])])
            ->assertSessionHasErrors(['category_id' => __('validation.required', ['attribute' => __('Category')])])
            ->assertSessionHasErrors(['relevance' => __('validation.required', ['attribute' => __('Relevance')])])
            ->assertSessionHasErrors(['payment_method_id' => __('validation.required', ['attribute' => __('Payment Method')])])
            ->assertSessionHasErrors(['source_wallet_id' => __('validation.required', ['attribute' => __('Source Wallet')])])
            ->assertSessionHasErrors(['destination_wallet_id' => __('validation.required', ['attribute' => __('Destination Wallet')])])
            ->assertSessionHasErrors(['gross_value' => __('validation.required', ['attribute' => __('Gross Value')])])
            ;
    }

    public function test_create_fail_very_short_title(): void
    {
        $transaction = Transaction::factory()->make([
            'title' => 'Na'
        ]);

        $this->post(route('transaction.store'), $transaction->toArray())
            ->assertSessionHasErrors(['title' => __('validation.between.string', ['attribute' => __('Title'), 'min' => 3, 'max' => 50])]);
    }

    public function test_create_fail_very_long_title(): void
    {
        $transaction = Transaction::factory()->make([
            'title' => '123456789012345678901234567890123456789012345678901'
        ]);

        $this->post(route('transaction.store'), $transaction->toArray())
            ->assertSessionHasErrors(['title' => __('validation.between.string', ['attribute' => __('Title'), 'min' => 3, 'max' => 50])]);
    }

    public function test_create_fail_invalid_dates(): void
    {
        $transaction = Transaction::factory()->make([
            'transaction_date' => '12345678', 
            'processing_date' => '12345678', 
        ]);

        $this->post(route('transaction.store'), $transaction->toArray())
            ->assertSessionHasErrors(['transaction_date' => __('validation.date_format', ['attribute' => __('Transaction Date'), 'format' => 'Y-m-d'])])
            ->assertSessionHasErrors(['processing_date' => __('validation.date_format', ['attribute' => __('Processing Date'), 'format' => 'Y-m-d'])]);
    }

    public function test_create_fail_invalid_processing_date(): void
    {
        $transaction = Transaction::factory()->make([
            'transaction_date' => Carbon::now(), 
            'processing_date' => Carbon::now()->subDay(), 
        ]);

        $this->post(route('transaction.store'), $transaction->toArray())
            ->assertSessionHasErrors(['processing_date' => __('validation.after_or_equal', ['attribute' => __('Processing Date'), 'date' => __('Transaction Date')])]);
    }

    public function test_store_fail_invalid_category(): void
    {
        $transaction = Transaction::factory()->make()->toArray();
        $transaction['category_id'] = 999999999999;

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['category_id' => __('validation.exists', ['attribute' => __('Category')])]);
    }

    public function test_store_fail_invalid_relevance(): void
    {
        $transaction = Transaction::factory()->make()->toArray();
        $transaction['relevance'] = '99999999';

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['relevance' => __('validation.exists', ['attribute' => __('Relevance')])]);
    }

    public function test_store_fail_invalid_payment_method(): void
    {
        $transaction = Transaction::factory()->make()->toArray();
        $transaction['payment_method_id'] = 999999999999;

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['payment_method_id' => __('validation.exists', ['attribute' => __('Payment Method')])]);
    }

    public function test_store_fail_invalid_source_wallet(): void
    {
        $transaction = Transaction::factory()->make()->toArray();
        $transaction['source_wallet_id'] = 999999999999;

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['source_wallet_id' => __('validation.exists', ['attribute' => __('Source Wallet')])]);
    }

    public function test_store_fail_invalid_destination_wallet(): void
    {
        $transaction = Transaction::factory()->make()->toArray();
        $transaction['destination_wallet_id'] = 999999999999;

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['destination_wallet_id' => __('validation.exists', ['attribute' => __('Destination Wallet')])]);
    }
    
    public function test_store_fail_invalid_card(): void
    {
        $transaction = Transaction::factory()->credit()->make()->toArray();
        $transaction['card_id'] = 999999999999;

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['card_id' => __('validation.exists', ['attribute' => __('Card')])]);
    }

    public function test_store_fail_card_from_another_wallet(): void
    {
        $transaction = Transaction::factory()->credit()->make()->toArray();
        $transaction['card_id'] = Card::factory()->fromWallet()->create()->id;
        $transaction['transaction_date'] = str_split($transaction['transaction_date'], 10)[0];
        $transaction['processing_date'] = str_split($transaction['processing_date'], 10)[0];

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['message' => __('The selected Card must belong to the Source Wallet.')]);
    }

    public function test_store_fail_value_invalid_min(): void
    {
        $transaction = Transaction::factory()->simple()->make([
            'gross_value' => '0.00',
            'discount_value' => '-0.01',
            'interest_value' => '-100000.00',
            'rounding_value' => '-100000.00',
        ])->toArray();
        $transaction['transaction_date'] = str_split($transaction['transaction_date'], 10)[0];
        $transaction['processing_date'] = str_split($transaction['processing_date'], 10)[0];

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['gross_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Gross Value'), 'min' => '0,01', 'max' => '99999,99'])])
            ->assertSessionHasErrors(['discount_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Discount Value'), 'min' => '0,00', 'max' => '99999,99'])])
            ->assertSessionHasErrors(['interest_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Interest Value'), 'min' => '-99999,99', 'max' => '99999,99'])])
            ->assertSessionHasErrors(['rounding_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Rounding Value'), 'min' => '-99999,99', 'max' => '99999,99'])]);
    }

    public function test_store_fail_value_invalid_max(): void
    {
        $transaction = Transaction::factory()->simple()->make([
            'gross_value' => '100000.00',
            'discount_value' => '100000.00',
            'interest_value' => '100000.00',
            'rounding_value' => '100000.00',
        ])->toArray();
        $transaction['transaction_date'] = str_split($transaction['transaction_date'], 10)[0];
        $transaction['processing_date'] = str_split($transaction['processing_date'], 10)[0];

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['gross_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Gross Value'), 'min' => '0,01', 'max' => '99999,99'])])
            ->assertSessionHasErrors(['discount_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Discount Value'), 'min' => '0,00', 'max' => '99999,99'])])
            ->assertSessionHasErrors(['interest_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Interest Value'), 'min' => '-99999,99', 'max' => '99999,99'])])
            ->assertSessionHasErrors(['rounding_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Rounding Value'), 'min' => '-99999,99', 'max' => '99999,99'])]);
    }

    public function test_store_fail_value_invalid_sum(): void
    {
        $transaction = Transaction::factory()->simple()->make([
            'gross_value' => '10.00',
            'discount_value' => '10.00',
        ])->toArray();
        $transaction['transaction_date'] = str_split($transaction['transaction_date'], 10)[0];
        $transaction['processing_date'] = str_split($transaction['processing_date'], 10)[0];

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['message' => __('The sum of values of the Transaction cannot be zero.')]);
    }

    public function test_store_fail_installment_value_invalid_min(): void
    {
        $transaction = Transaction::factory()->credit()->make()->toArray();
        $transaction['transaction_date'] = str_split($transaction['transaction_date'], 10)[0];
        $transaction['processing_date'] = str_split($transaction['processing_date'], 10)[0];
        $transaction['installments'][0]['gross_value'] = 0.00;
        $transaction['installments'][0]['discount_value'] = -0.01;
        $transaction['installments'][0]['interest_value'] = -100000.00;
        $transaction['installments'][0]['rounding_value'] = -100000.00;

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['installments.0.gross_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Gross Value (Installment)'), 'min' => '0,01', 'max' => '99999,99'])])
            ->assertSessionHasErrors(['installments.0.discount_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Discount Value (Installment)'), 'min' => '0,00', 'max' => '99999,99'])])
            ->assertSessionHasErrors(['installments.0.interest_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Interest Value (Installment)'), 'min' => '-99999,99', 'max' => '99999,99'])])
            ->assertSessionHasErrors(['installments.0.rounding_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Rounding Value (Installment)'), 'min' => '-99999,99', 'max' => '99999,99'])]);
    }

    public function test_store_fail_installment_value_invalid_max(): void
    {
        $transaction = Transaction::factory()->credit()->make()->toArray();
        $transaction['transaction_date'] = str_split($transaction['transaction_date'], 10)[0];
        $transaction['processing_date'] = str_split($transaction['processing_date'], 10)[0];
        $transaction['installments'][0]['gross_value'] = 100000.00;
        $transaction['installments'][0]['discount_value'] = 100000.00;
        $transaction['installments'][0]['interest_value'] = 100000.00;
        $transaction['installments'][0]['rounding_value'] = 100000.00;

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['installments.0.gross_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Gross Value (Installment)'), 'min' => '0,01', 'max' => '99999,99'])])
            ->assertSessionHasErrors(['installments.0.discount_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Discount Value (Installment)'), 'min' => '0,00', 'max' => '99999,99'])])
            ->assertSessionHasErrors(['installments.0.interest_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Interest Value (Installment)'), 'min' => '-99999,99', 'max' => '99999,99'])])
            ->assertSessionHasErrors(['installments.0.rounding_value' => __('The value must have two decimal places and be between min and max.', ['value' => __('Rounding Value (Installment)'), 'min' => '-99999,99', 'max' => '99999,99'])]);
    }

    public function test_store_fail_installment_invalid_discount_value(): void
    {
        $transaction = Transaction::factory()->credit()->make()->toArray();
        $transaction['transaction_date'] = str_split($transaction['transaction_date'], 10)[0];
        $transaction['processing_date'] = str_split($transaction['processing_date'], 10)[0];
        $transaction['installments'][0]['gross_value'] = 10.00;
        $transaction['installments'][0]['discount_value'] = 10.00;

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['message' => __('Invalid value for field of installment installmentNumber.', ['field' => __('Discount Value'), 'installmentNumber' => 1])]);
    }

    public function test_store_fail_installment_invalid_interest_value(): void
    {
        $transaction = Transaction::factory()->credit()->make()->toArray();
        $transaction['transaction_date'] = str_split($transaction['transaction_date'], 10)[0];
        $transaction['processing_date'] = str_split($transaction['processing_date'], 10)[0];
        $transaction['installments'][0]['gross_value'] = 10.00;
        $transaction['installments'][0]['interest_value'] = 10.00;

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['message' => __('Invalid value for field of installment installmentNumber.', ['field' => __('Interest Value'), 'installmentNumber' => 1])]);
    }

    public function test_store_fail_installment_invalid_rounding_value(): void
    {
        $transaction = Transaction::factory()->credit()->make()->toArray();
        $transaction['transaction_date'] = str_split($transaction['transaction_date'], 10)[0];
        $transaction['processing_date'] = str_split($transaction['processing_date'], 10)[0];
        $transaction['installments'][0]['gross_value'] = 10.00;
        $transaction['installments'][0]['rounding_value'] = 10.00;

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['message' => __('Invalid value for field of installment installmentNumber.', ['field' => __('Rounding Value'), 'installmentNumber' => 1])]);
    }

    public function test_store_fail_installment_value_invalid_sum(): void
    {
        $transaction = Transaction::factory()->credit()->make()->toArray();
        $transaction['transaction_date'] = str_split($transaction['transaction_date'], 10)[0];
        $transaction['processing_date'] = str_split($transaction['processing_date'], 10)[0];
        $transaction['gross_value'] = 10.00;
        $transaction['installments'][0]['gross_value'] = 10.00;
        $transaction['installments'][0]['discount_value'] = 9.00;
        $transaction['installments'][0]['rounding_value'] = -2.00;

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['message' => __('Sum of installment installmentNumber cannot be negative.', ['installmentNumber' => 1])]);
    }

    public function test_store_fail_installments_sum(): void
    {
        $transaction = Transaction::factory()->credit()->make()->toArray();
        $transaction['gross_value'] = 10.00;
        $transaction['installments'][0]['gross_value'] = 11.00;
        $transaction['transaction_date'] = str_split($transaction['transaction_date'], 10)[0];
        $transaction['processing_date'] = str_split($transaction['processing_date'], 10)[0];

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['message' => __('The sum of the Gross Values of the Installments cannot be greater than the Net Value of the Transaction.')]);
    }

    public function test_store_fail_installment_date(): void
    {
        $transaction = Transaction::factory()->credit()->make()->toArray();
        $transaction['transaction_date'] = str_split($transaction['transaction_date'], 10)[0];
        $transaction['processing_date'] = str_split($transaction['processing_date'], 10)[0];
        $transaction['installments'][0]['installment_date'] = Carbon::now()->subMonth()->format('Y-m-d');

        $this->post(route('transaction.store'), $transaction)
            ->assertSessionHasErrors(['installments.0.installment_date' => __('validation.after_or_equal', ['attribute' => __('Installment Date'), 'date' => __('Transaction Date')])]);
    }
}
