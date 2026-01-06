<?php

namespace Tests\Feature;

use App\Models\Owner;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OwnerTest extends TestCase
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
        $response = $this->get(route('owner.list'));

        $response->assertStatus(200);
        $this->assertArrayHasKey('owners', $response->original);
    }

    public function test_create_successfully(): void
    {
        $dataOwner = Owner::factory()->make();

        $this->post(route('owner.store'), $dataOwner->toArray())
            ->assertRedirect(route('owner.list', ['message' => __('Data created successfully.')]));

        $this->assertDatabaseHas('owners', $dataOwner->toArray());
    }

    public function test_create_successfully_with_wallet(): void
    {
        $dataOwner = Owner::factory()->make();

        $this->post(route('owner.store'), $dataOwner->toArray())
            ->assertRedirect(route('owner.list', ['message' => __('Data created successfully.')]));

        $owner = Owner::where('name', $dataOwner['name'])->with('wallets')->first();
        $this->assertCount(1, $owner->wallets);
        $this->assertEquals(__('Standard Owner\'s Wallet', ['owner' => $dataOwner->name]), $owner->wallets->first()->name);
        $this->assertEquals(1, $owner->wallets->first()->main_wallet);
    }

    public function test_create_successfully_inactivate_with_wallet(): void
    {
        $dataOwner = Owner::factory()->make([ 'active' => false ]);

        $this->post(route('owner.store'), $dataOwner->toArray())
            ->assertRedirect(route('owner.list', ['message' => __('Data created successfully.')]));

        $owner = Owner::where('name', $dataOwner['name'])->with('wallets')->first();
        $wallet = $owner->wallets->first();
        $this->assertEquals(__('Standard Owner\'s Wallet', ['owner' => $dataOwner->name]), $wallet->name);
        $this->assertEquals(0, $wallet->active);
    }

    // #[DataProvider('validCharacters')]
    // public function test_create_successfully_with_characters(string $char): void
    // {
    //     $dataOwner = Owner::factory()->make();
    //     $dataOwner['name'] .= $char;

    //     $this->post(route('owner.store'), $dataOwner->toArray())
    //         ->assertRedirect(route('owner.list', ['message' => __('Data created successfully.')]));

    //     $this->assertDatabaseHas('owners', $dataOwner->toArray());
    // }

    public function test_create_fail_without_name(): void
    {
        $dataOwner = Owner::factory()->make();
        unset($dataOwner['name']);

        $this->post(route('owner.store'), $dataOwner->toArray())
            ->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => 'Nome'])]);
    }

    public function test_create_fail_duplicate_name(): void
    {
        $dataOwner = Owner::factory()->create();

        $this->post(route('owner.store'), $dataOwner->toArray())
            ->assertSessionHasErrors(['name' => __('validation.unique', ['attribute' => 'Nome'])]);
    }

    public function test_create_fail_very_short_name(): void
    {
        $dataOwner = Owner::factory()->make([
            'name' => 'Na'
        ]);

        $this->post(route('owner.store'), $dataOwner->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => 'Nome', 'min' => 3, 'max' => 30])]);
    }

    public function test_create_fail_very_long_name(): void
    {
        $dataOwner = Owner::factory()->make([
            'name' => '1234567890123456789012345678901'
        ]);

        $this->post(route('owner.store'), $dataOwner->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => 'Nome', 'min' => 3, 'max' => 30])]);
    }

    // #[DataProvider('invalidCharacters')]
    // public function test_create_fail_unacept_characters(string $char): void
    // {
    //     $dataOwner = Owner::factory()->make();
    //     $dataOwner['name'] .= $char;

    //     $this->post(route('owner.store'), $dataOwner->toArray())
    //         ->assertSessionHasErrors(['name' => 'O campo Nome deve se limitar letras, números, pontos, traços e espaços']);
    // }

    public function test_activate_successfully(): void
    {
        $originOwner = Owner::factory()->withMoreWallets()->create([ 'active' => false ])->toArray();
        $updateOwner = Owner::factory()->make([ 'active' => true ])->toArray();
        $updateOwner['id'] = $originOwner['id'];

        $this->put(route('owner.update'), $updateOwner)
            ->assertRedirect(route('owner.list', ['message' => __('Data updated successfully.')]));

        $updatedOwner = Owner::find($originOwner['id']);
        $this->assertEquals($updatedOwner['name'], $originOwner['name']);       // name não deve ser alterado
        $this->assertEquals($updatedOwner['active'], $updateOwner['active']);   // active deve ser alterado
    }

    public function test_activate_successfully_with_main_wallet(): void
    {
        $originOwner = Owner::factory()->withMoreWallets()->create([ 'active' => false ])->toArray();
        $updateOwner = Owner::factory()->make([ 'active' => true ])->toArray();
        $updateOwner['id'] = $originOwner['id'];

        $this->put(route('owner.update'), $updateOwner)
            ->assertRedirect(route('owner.list', ['message' => __('Data updated successfully.')]));

        $wallets = Wallet::query()->where('owner_id', $originOwner['id'])->where('active', true)->get();
        $this->assertCount(1, $wallets);
        $this->assertEquals(1, $wallets->first()->main_wallet);
    }

    public function test_inactivate_successfully(): void
    {
        $originOwner = Owner::factory()->withMoreWallets()->create([ 'active' => true ])->toArray();
        $updateOwner = Owner::factory()->make([ 'active' => false ])->toArray();
        $updateOwner['id'] = $originOwner['id'];

        $this->put(route('owner.update'), $updateOwner)
            ->assertRedirect(route('owner.list', ['message' => __('Data updated successfully.')]));

        $updatedOwner = Owner::find($originOwner['id']);
        $this->assertEquals($updatedOwner['name'], $originOwner['name']);       // name não deve ser alterado
        $this->assertEquals($updatedOwner['active'], $updateOwner['active']);   // active deve ser alterado
    }

    public function test_inactivate_successfully_with_wallets(): void
    {
        $originOwner = Owner::factory()->withMoreWallets()->create([ 'active' => true ])->toArray();
        $updateOwner = Owner::factory()->make([ 'active' => false ])->toArray();
        $updateOwner['id'] = $originOwner['id'];

        $this->put(route('owner.update'), $updateOwner)
            ->assertRedirect(route('owner.list', ['message' => __('Data updated successfully.')]));

        $wallets = Wallet::query()->where('owner_id', $originOwner['id'])->get();
        $this->assertTrue($wallets->where('active', true)->isEmpty());
        $this->assertTrue($wallets->where('active', false)->isNotEmpty());
    }
}
