<?php

namespace Tests\Feature;

use App\Models\Owner;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use DatabaseTransactions;

    public function test_index(): void
    {
        $response = $this->get(route('wallet.list'));

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

        $this->post(route('wallet.store'), $dataWallet->toArray())
            ->assertRedirect(route('wallet.list', ['message' => __('Data created successfully.')]));

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

        $this->post(route('wallet.store'), $dataWallet->toArray())
            ->assertRedirect(route('wallet.list', ['message' => __('Data created successfully.')]));

        $this->assertDatabaseHas('wallets', $dataWallet->toArray());
    }

    public function test_create_fail_without_name(): void
    {
        $dataWallet = Wallet::factory()->make();
        unset($dataWallet['name']);

        $this->post(route('wallet.store'), $dataWallet->toArray())
            ->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => 'Nome'])]);
    }

    public function test_create_fail_duplicate_name(): void
    {
        $dataWallet = Wallet::factory()->create();

        $this->post(route('wallet.store'), $dataWallet->toArray())
            ->assertSessionHasErrors(['name' => __('validation.unique', ['attribute' => 'Nome'])]);
    }

    public function test_create_fail_very_short_name(): void
    {
        $dataWallet = Wallet::factory()->make([
            'name' => 'Na'
        ]);

        $this->post(route('wallet.store'), $dataWallet->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => 'Nome', 'min' => 3, 'max' => 45])]);
    }

    public function test_create_fail_very_long_name(): void
    {
        $dataWallet = Wallet::factory()->make([
            'name' => '1234567890123456789012345678901234567890123456'
        ]);

        $this->post(route('wallet.store'), $dataWallet->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => 'Nome', 'min' => 3, 'max' => 45])]);
    }

    public function test_create_fail_without_owner(): void
    {
        $dataWallet = Wallet::factory()->make();
        unset($dataWallet['owner_id']);

        $this->post(route('wallet.store'), $dataWallet->toArray())
            ->assertSessionHasErrors(['owner_id' => __('It is necessary to inform who this wallet belongs to.')]);
    }

    public function test_create_fail_inactivate_main_wallet(): void
    {
        $dataWallet = Wallet::factory()->make([
            'main_wallet' => true,
            'active' => false,
        ]);

        $this->post(route('wallet.store'), $dataWallet->toArray())
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

        $this->put(route('wallet.update', ['id' => $secondaryWallet->id]), $secondaryWalletNewData)
            ->assertRedirect(route('wallet.list', ['message' => __('Data updated successfully.')]));

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

        $this->put(route('wallet.update', ['id' => $wallet->id]), $newDataWallet)
            ->assertSessionHasErrors(['active' => __('A wallet marked as main cannot be inactive.')]);
    }

    public function test_update_fail_uncheck_main_wallet(): void
    {
        $owner = Owner::factory()->create(['active' => true]);
        $wallet = $owner->wallets()->where('main_wallet', true)->first();
        $newDataWallet = $wallet->toArray();
        $newDataWallet['main_wallet'] = false;

        $this->put(route('wallet.update', ['id' => $wallet->id]), $newDataWallet)
            ->assertRedirect(route('wallet.list', ['message' => __('The main wallet of an account cannot be unmarked as such.')]));
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

        $this->put(route('wallet.update', ['id' => $wallet->id]), $newDataWallet)
            ->assertRedirect(route('wallet.list', ['message' => __('Data updated successfully.')]));
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

        $this->put(route('wallet.update', ['id' => $wallet->id]), $newDataWallet)
            ->assertRedirect(route('wallet.list', ['message' => __('Data updated successfully.')]));

        unset($newDataWallet['created_at'], $newDataWallet['updated_at']);
        $this->assertDatabaseHas('wallets', $newDataWallet);
    }

    public function test_update_activate_wallet_by_inative_owner(): void
    {
        $owner = Owner::factory()->create(['active' => false]);
        $wallet = $owner->wallets()->where('main_wallet', true)->first();
        $newDataWallet = $wallet->toArray();
        $newDataWallet['active'] = true;

        $this->put(route('wallet.update', ['id' => $wallet->id]), $newDataWallet)
            ->assertRedirect(route('wallet.list', ['message' => __('It is not allowed to activate a Wallet whose Owner is inactive.')]));
    }

    /**
     * O método para verifição de pendências de uma Carteira ainda não foi implementado
     * Por esse motivo, os testes reverentes a remoção de registros (softDelete) não foram implementados
     * Assim como os testes referêntes a inativação de uma Carteira quando a mesma possuir pendências
     */
}
