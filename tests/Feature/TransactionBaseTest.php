<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\TransactionBase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TransactionBaseTest extends TestCase
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
        $response = $this->get(route('transaction-base.list'));

        $response->assertStatus(200);
    }

    public function test_create(): void
    {
        $response = $this->get(route('transaction-base.create'));

        $response->assertStatus(200);
    }

    public function test_store_successfully(): void
    {
        $transactionBase = TransactionBase::factory()->make();

        $this->post(route('transaction-base.store'), $transactionBase->toArray())
            ->assertRedirect(route('transaction-base.list', ['message' => __('Data created successfully.')]));

        $this->assertDatabaseHas('transaction_bases', $transactionBase->toArray());
    }

    public function test_store_fail_without_required_data(): void
    {
        $this->post(route('transaction-base.store'), [])
            ->assertSessionHasErrors(['title' => __('validation.required', ['attribute' => __('Title')])])
            ->assertSessionHasErrors(['category_id' => __('validation.required', ['attribute' => __('Category')])])
            ->assertSessionHasErrors(['payment_method_id' => __('validation.required', ['attribute' => __('Payment Method')])])
            ->assertSessionHasErrors(['source_wallet_id' => __('validation.required', ['attribute' => __('Source Wallet')])])
            ->assertSessionHasErrors(['destination_wallet_id' => __('validation.required', ['attribute' => __('Destination Wallet')])]);
    }

    public function test_store_fail_without_card(): void
    {
        $transactionBase = TransactionBase::factory()->credit()->make()->toArray();
        unset($transactionBase['card_id']);

        $this->post(route('transaction-base.store'), $transactionBase)
            ->assertSessionHasErrors(['card_id' => __('validation.required_if', ['attribute' => __('Card'), 'other' => __('Payment Method'), 'value' => __('Card')])]);
    }

    public function test_create_fail_duplicate_title(): void
    {
        $transactionBase = TransactionBase::factory()->create();

        $this->post(route('transaction-base.store'), $transactionBase->toArray())
            ->assertSessionHasErrors(['title' => __('validation.unique', ['attribute' => __('Title')])]);
    }

    public function test_create_fail_very_short_title(): void
    {
        $transactionBase = TransactionBase::factory()->make([
            'title' => 'Na'
        ]);

        $this->post(route('transaction-base.store'), $transactionBase->toArray())
            ->assertSessionHasErrors(['title' => __('validation.between.string', ['attribute' => __('Title'), 'min' => 3, 'max' => 50])]);
    }

    public function test_create_fail_very_long_title(): void
    {
        $transactionBase = TransactionBase::factory()->make([
            'title' => '123456789012345678901234567890123456789012345678901'
        ]);

        $this->post(route('transaction-base.store'), $transactionBase->toArray())
            ->assertSessionHasErrors(['title' => __('validation.between.string', ['attribute' => __('Title'), 'min' => 3, 'max' => 50])]);
    }

    public function test_store_fail_invalid_category(): void
    {
        $transactionBase = TransactionBase::factory()->make()->toArray();
        $transactionBase['category_id'] = 999999999999;

        $this->post(route('transaction-base.store'), $transactionBase)
            ->assertSessionHasErrors(['category_id' => __('validation.exists', ['attribute' => __('Category')])]);
    }

    public function test_store_fail_invalid_payment_method(): void
    {
        $transactionBase = TransactionBase::factory()->make()->toArray();
        $transactionBase['payment_method_id'] = 999999999999;

        $this->post(route('transaction-base.store'), $transactionBase)
            ->assertSessionHasErrors(['payment_method_id' => __('validation.exists', ['attribute' => __('Payment Method')])]);
    }

    public function test_store_fail_invalid_source_wallet(): void
    {
        $transactionBase = TransactionBase::factory()->make()->toArray();
        $transactionBase['source_wallet_id'] = 999999999999;

        $this->post(route('transaction-base.store'), $transactionBase)
            ->assertSessionHasErrors(['source_wallet_id' => __('validation.exists', ['attribute' => __('Source Wallet')])]);
    }

    public function test_store_fail_invalid_destination_wallet(): void
    {
        $transactionBase = TransactionBase::factory()->make()->toArray();
        $transactionBase['destination_wallet_id'] = 999999999999;

        $this->post(route('transaction-base.store'), $transactionBase)
            ->assertSessionHasErrors(['destination_wallet_id' => __('validation.exists', ['attribute' => __('Destination Wallet')])]);
    }

    public function test_store_fail_invalid_card(): void
    {
        $transactionBase = TransactionBase::factory()->credit()->make()->toArray();
        $transactionBase['card_id'] = 999999999999;

        $this->post(route('transaction-base.store'), $transactionBase)
            ->assertSessionHasErrors(['card_id' => __('validation.exists', ['attribute' => __('Card')])]);
    }

    public function test_store_fail_card_from_another_wallet(): void
    {
        $transactionBase = TransactionBase::factory()->credit()->make()->toArray();
        $transactionBase['card_id'] = Card::factory()->create()->id;

        $this->post(route('transaction-base.store'), $transactionBase)
            ->assertSessionHasErrors(['message' => __('The selected Card must belong to the Source Wallet.')]);
    }

    public function test_remove_successfully(): void
    {
        $transactionBase = TransactionBase::factory()->create();

        $this->delete(route('transaction-base.destroy'), $transactionBase->toArray())
            ->assertRedirect(route('transaction-base.list', ['message' => __('Data deleted successfully.')]));

        $this->assertDatabaseMissing('transaction_bases', [
            'id' => $transactionBase->id,
        ]);
    }

    public function test_remove_fail(): void
    {
        $this->delete(route('transaction-base.destroy'), ['id' => 999999999999 ])
            ->assertRedirect(route('transaction-base.list'))
            ->assertSessionHasErrors(['message' => __("The reported record was not found.")]);
    }
}
