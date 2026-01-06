<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CardTest extends TestCase
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
        $wallet = Wallet::factory()->fromOwner()->create(['active' => true]);
        $response = $this->get(route('owner.wallet.card.list', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id]));

        $response->assertStatus(200);
        $this->assertArrayHasKey('cards', $response->original);
    }

    public function test_create_successfully(): void
    {
        $wallet = Wallet::factory()->fromOwner()->create();
        $cardData = Card::factory()->fromWallet($wallet)->make();

        $this->post(route('owner.wallet.card.store', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id]), $cardData->toArray())
            ->assertRedirect(route('owner.wallet.card.list', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id, 'message' => __('Data created successfully.')]));

        $this->assertDatabaseHas('cards', $cardData->toArray());
    }

    public function test_create_inactivate_successfully(): void
    {
        $wallet = Wallet::factory()->fromOwner()->create();
        $cardData = Card::factory()->fromWallet($wallet)->make(['active' => false]);

        $this->post(route('owner.wallet.card.store', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id]), $cardData->toArray())
            ->assertRedirect(route('owner.wallet.card.list', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id, 'message' => __('Data created successfully.')]));

        $this->assertDatabaseHas('cards', $cardData->toArray());
    }

    public function test_create_fail_without_name(): void
    {
        $wallet = Wallet::factory()->fromOwner()->create();
        $cardData = Card::factory()->fromWallet($wallet)->make();
        unset($cardData['name']);

        $this->post(route('owner.wallet.card.store', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id]), $cardData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => 'Nome'])]);
    }

    public function test_create_fail_duplicate_name(): void
    {
        $wallet = Wallet::factory()->fromOwner()->create();
        $cardData = Card::factory()->fromWallet($wallet)->create();

        $this->post(route('owner.wallet.card.store', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id]), $cardData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.unique', ['attribute' => 'Nome'])]);
    }

    public function test_create_fail_very_short_name(): void
    {
        $wallet = Wallet::factory()->fromOwner()->create();
        $cardData = Card::factory()->fromWallet($wallet)->make([
            'name' => 'Na'
        ]);

        $this->post(route('owner.wallet.card.store', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id]), $cardData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => 'Nome', 'min' => 3, 'max' => 20])]);
    }

    public function test_create_fail_very_long_name(): void
    {
        $wallet = Wallet::factory()->fromOwner()->create();
        $cardData = Card::factory()->fromWallet($wallet)->make([
            'name' => '123456789012345678901'
        ]);

        $this->post(route('owner.wallet.card.store', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id]), $cardData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => 'Nome', 'min' => 3, 'max' => 20])]);
    }

    public function test_update_main_wallet_active_owner(): void
    {
        $wallet = Wallet::factory()->fromOwner()->create();
        $cardOriginal = Card::factory()->fromWallet($wallet)->create([ 'card_type' => 'debit' ]);
        $cardUpdate = Card::factory()->make([
            'id' => $cardOriginal->id,
            'wallet_id' => 1,
            "card_type" => "credit",
            'first_day_month' => $cardOriginal->first_day_month + 5,
            'days_to_expiration' => $cardOriginal->days_to_expiration + 5,
            'active' => !$cardOriginal->active,
        ])->toArray();

        $this->put(route('owner.wallet.card.store', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id]), $cardUpdate)
            ->assertRedirect(route('owner.wallet.card.list', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id, 'message' => __('Data updated successfully.')]));

        $cardOriginalData = $cardOriginal->toArray();
        $cardUpdatedData = $cardOriginal->refresh();

        $this->assertEquals($cardUpdatedData['name'], $cardOriginalData['name']);
        $this->assertEquals($cardUpdatedData['wallet_id'], $cardOriginalData['wallet_id']);
        $this->assertEquals($cardUpdatedData['card_type'], $cardOriginalData['card_type']);

        $this->assertEquals($cardUpdatedData['first_day_month'], $cardUpdate['first_day_month']);
        $this->assertEquals($cardUpdatedData['days_to_expiration'], $cardUpdate['days_to_expiration']);
        $this->assertEquals($cardUpdatedData['active'], $cardUpdate['active']);
    }
}
