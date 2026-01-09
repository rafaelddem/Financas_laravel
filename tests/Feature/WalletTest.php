<?php

namespace Tests\Feature;

use App\Models\Owner;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WalletTest extends TestCase
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
        $owner = Owner::factory()->create(['active' => true]);
        $response = $this->get(route('owner.wallet.list', ['owner_id' => $owner->id]));

        $response->assertStatus(200);
        $this->assertArrayHasKey('wallets', $response->original);
    }

    public function test_create_successfully(): void
    {
        $owner = Owner::factory()->create(['active' => true]);
        $dataWallet = Wallet::factory()->fromOwner($owner)->make([
            'main_wallet' => true,
            'active' => true,
        ]);

        $this->post(route('owner.wallet.store', ['owner_id' => $owner->id]), $dataWallet->toArray())
            ->assertRedirect(route('owner.wallet.list', ['owner_id' => $owner->id, 'message' => __('Data created successfully.')]));

        $this->assertDatabaseHas('wallets', $dataWallet->toArray());
        $this->assertEquals(1, $owner->wallets()->where('main_wallet', true)->count());
    }

    public function test_create_inactivate_successfully(): void
    {
        $owner = Owner::factory()->create(['active' => true]);
        $dataWallet = Wallet::factory()->fromOwner($owner)->make([
            'main_wallet' => false,
            'active' => false,
        ]);

        $this->post(route('owner.wallet.store', ['owner_id' => $owner->id]), $dataWallet->toArray())
            ->assertRedirect(route('owner.wallet.list', ['owner_id' => $owner->id, 'message' => __('Data created successfully.')]));

        $this->assertDatabaseHas('wallets', $dataWallet->toArray());
    }

    public function test_create_fail_without_name(): void
    {
        $owner = Owner::factory()->create(['active' => true]);
        $dataWallet = Wallet::factory()->make();
        unset($dataWallet['name']);

        $this->post(route('owner.wallet.store', ['owner_id' => $owner->id]), $dataWallet->toArray())
            ->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => __('Name')])]);
    }

    public function test_create_fail_duplicate_name(): void
    {
        $owner = Owner::factory()->create(['active' => true]);
        $dataWallet = Wallet::factory()->create();

        $this->post(route('owner.wallet.store', ['owner_id' => $owner->id]), $dataWallet->toArray())
            ->assertSessionHasErrors(['name' => __('validation.unique', ['attribute' => __('Name')])]);
    }

    public function test_create_fail_very_short_name(): void
    {
        $owner = Owner::factory()->create(['active' => true]);
        $dataWallet = Wallet::factory()->make([
            'name' => 'Na'
        ]);

        $this->post(route('owner.wallet.store', ['owner_id' => $owner->id]), $dataWallet->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => __('Name'), 'min' => 3, 'max' => 45])]);
    }

    public function test_create_fail_very_long_name(): void
    {
        $owner = Owner::factory()->create(['active' => true]);
        $dataWallet = Wallet::factory()->make([
            'name' => '1234567890123456789012345678901234567890123456'
        ]);

        $this->post(route('owner.wallet.store', ['owner_id' => $owner->id]), $dataWallet->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => __('Name'), 'min' => 3, 'max' => 45])]);
    }

    public function test_create_fail_inactivate_main_wallet(): void
    {
        $owner = Owner::factory()->create(['active' => true]);
        $dataWallet = Wallet::factory()->make([
            'main_wallet' => true,
            'active' => false,
        ]);

        $this->post(route('owner.wallet.store', ['owner_id' => $owner->id]), $dataWallet->toArray())
            ->assertSessionHasErrors(['active' => __('A wallet marked as main cannot be inactive.')]);
    }

    public function test_update_main_wallet_active_owner(): void
    {
        $owner = Owner::factory()->create(['active' => true]);
        $secondaryWallet = Wallet::factory()->fromOwner($owner)->create([
            'main_wallet' => false,
            'active' => false,
        ]);
        $secondaryWalletNewData = Wallet::factory()->make([
            'main_wallet' => true,
            'active' => true,
        ])->toArray();

        $this->put(route('owner.wallet.update', ['owner_id' => $owner->id, 'id' => $secondaryWallet->id]), $secondaryWalletNewData)
            ->assertRedirect(route('owner.wallet.list', ['owner_id' => $owner->id, 'message' => __('Data updated successfully.')]));

        $secondaryWalletOriginalData = $secondaryWallet->toArray();
        $secondaryWalletUpdatedData = $secondaryWallet->refresh();
        $this->assertEquals(1, $owner->wallets()->where('main_wallet', true)->count());
        $this->assertEquals($secondaryWalletUpdatedData['main_wallet'], $secondaryWalletNewData['main_wallet']);
        $this->assertEquals($secondaryWalletUpdatedData['active'], $secondaryWalletNewData['active']);
        $this->assertEquals($secondaryWalletUpdatedData['description'], $secondaryWalletNewData['description']);

        $this->assertEquals($secondaryWalletUpdatedData['name'], $secondaryWalletOriginalData['name']);
        $this->assertEquals($secondaryWalletUpdatedData['owner_id'], $secondaryWalletOriginalData['owner_id']);
    }

    public function test_update_fail_inactivate_main_wallet(): void
    {
        $owner = Owner::factory()->create(['active' => true]);
        $wallet = $owner->wallets()->where('main_wallet', true)->first();
        $newDataWallet = $wallet->toArray();
        $newDataWallet['active'] = false;

        $this->put(route('owner.wallet.update', ['owner_id' => $owner->id, 'id' => $wallet->id]), $newDataWallet)
            ->assertSessionHasErrors(['active' => __('A wallet marked as main cannot be inactive.')]);
    }

    public function test_update_fail_uncheck_main_wallet(): void
    {
        $owner = Owner::factory()->create(['active' => true]);
        $wallet = $owner->wallets()->where('main_wallet', true)->first();
        $newDataWallet = $wallet->toArray();
        $newDataWallet['main_wallet'] = false;

        $this->put(route('owner.wallet.update', ['owner_id' => $owner->id, 'id' => $wallet->id]), $newDataWallet)
            ->assertSessionHasErrors(['message' => __('The main wallet of an account cannot be unmarked as such.')]);
    }

    public function test_update_inactivate_secondary_wallet(): void
    {
        $owner = Owner::factory()->create(['active' => true]);
        $wallet = Wallet::factory()->fromOwner($owner)->create([
            'main_wallet' => false,
            'active' => true,
        ]);
        $newDataWallet = $wallet->toArray();
        $newDataWallet['active'] = false;

        $this->put(route('owner.wallet.update', ['owner_id' => $owner->id, 'id' => $wallet->id]), $newDataWallet)
            ->assertRedirect(route('owner.wallet.list', ['owner_id' => $owner->id, 'message' => __('Data updated successfully.')]));
    }

    public function test_update_activate_secondary_wallet(): void
    {
        $owner = Owner::factory()->create(['active' => true]);
        $wallet = Wallet::factory()->fromOwner($owner)->create([
            'main_wallet' => false,
            'active' => false,
        ]);
        $newDataWallet = $wallet->toArray();
        $newDataWallet['active'] = true;

        $this->put(route('owner.wallet.update', ['owner_id' => $owner->id, 'id' => $wallet->id]), $newDataWallet)
            ->assertRedirect(route('owner.wallet.list', ['owner_id' => $owner->id, 'message' => __('Data updated successfully.')]));

        unset($newDataWallet['created_at'], $newDataWallet['updated_at']);
        $this->assertDatabaseHas('wallets', $newDataWallet);
    }

    public function test_update_activate_wallet_by_inative_owner(): void
    {
        $owner = Owner::factory()->create(['active' => false]);
        $wallet = $owner->wallets()->where('main_wallet', true)->first();
        $newDataWallet = $wallet->toArray();
        $newDataWallet['active'] = true;

        $this->put(route('owner.wallet.update', ['owner_id' => $owner->id, 'id' => $wallet->id]), $newDataWallet)
        ->assertSessionHasErrors(['message' => __('It is not allowed to activate a Wallet whose Owner is inactive.')]);
    }

    public function test_remove_successfully(): void
    {
        $wallet = Wallet::factory()->fromOwner()->create();

        $this->delete(route('owner.wallet.destroy', ['owner_id' => $wallet->owner_id]), $wallet->toArray())
            ->assertRedirect(route('owner.wallet.list', ['owner_id' => $wallet->owner_id, 'message' => __('Data deleted successfully.')]));

        $this->assertDatabaseMissing('wallets', [
            'id' => $wallet->id,
        ]);
    }

    public function test_remove_fail(): void
    {
        $wallet = Wallet::factory()->fromOwner()->create();

        $this->delete(route('owner.wallet.destroy', ['owner_id' => $wallet->owner_id]), ['id' => 999999999999 ])
            ->assertRedirect(route('owner.wallet.list', ['owner_id' => $wallet->owner_id]))
            ->assertSessionHasErrors(['message' => __("The reported record was not found.")]);
    }

    public function test_remove_fail_main_wallet(): void
    {
        $wallet = Wallet::factory()->fromOwner()->create([
            'main_wallet' => true,
        ]);

        $this->delete(route('owner.wallet.destroy', ['owner_id' => $wallet->owner_id]), $wallet->toArray())
            ->assertRedirect(route('owner.wallet.list', ['owner_id' => $wallet->owner_id]))
            ->assertSessionHasErrors(['message' => __("It is not allowed to remove the main Wallet.")]);

        $this->assertDatabaseHas('wallets', ['id' => $wallet->id]);
    }

    public function test_remove_fail_wallet_with_values(): void
    {
        $wallet = Wallet::factory()->fromOwner()->create();
        Transaction::factory()->wallets($wallet, null)->create([
            'transaction_date' => Carbon::now()->addMonth(),
            'processing_date' => Carbon::now()->addMonth(),
        ]);

        $this->delete(route('owner.wallet.destroy', ['owner_id' => $wallet->owner_id]), $wallet->toArray())
            ->assertRedirect(route('owner.wallet.list', ['owner_id' => $wallet->owner_id]))
            ->assertSessionHasErrors(['message' => __("It is not allowed to remove a Wallet that has outstanding monetary balances.")]);

        $this->assertDatabaseHas('wallets', ['id' => $wallet->id]);
    }
}
