<?php

namespace Tests\Feature;

use App\Models\TransactionType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TransactionTypeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_index(): void
    {
        $response = $this->get(route('transaction-type.list'));

        $response->assertStatus(200);
        $this->assertArrayHasKey('transactionTypes', $response->original);
    }

    public function test_create_successfully(): void
    {
        $transactionType = TransactionType::factory()->make();

        $this->post(route('transaction-type.store'), $transactionType->toArray())
            ->assertRedirect(route('transaction-type.list', ['message' => __('Data created successfully.')]));

        $this->assertDatabaseHas('transaction_types', $transactionType->toArray());
    }

    public function test_create_fail_without_name(): void
    {
        $transactionTypeData = TransactionType::factory()->make();
        unset($transactionTypeData['name']);

        $this->post(route('transaction-type.store'), $transactionTypeData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => 'Nome'])]);
    }

    public function test_create_fail_duplicate_name(): void
    {
        $transactionTypeData = TransactionType::factory()->create();

        $this->post(route('transaction-type.store'), $transactionTypeData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.unique', ['attribute' => 'Nome'])]);
    }

    public function test_create_fail_very_short_name(): void
    {
        $transactionTypeData = TransactionType::factory()->make([
            'name' => 'Na'
        ]);

        $this->post(route('transaction-type.store'), $transactionTypeData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => 'Nome', 'min' => 3, 'max' => 30])]);
    }

    public function test_create_fail_very_long_name(): void
    {
        $transactionTypeData = TransactionType::factory()->make([
            'name' => '1234567890123456789012345678901'
        ]);

        $this->post(route('transaction-type.store'), $transactionTypeData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => 'Nome', 'min' => 3, 'max' => 30])]);
    }

    public function test_create_fail_without_relevance(): void
    {
        $transactionTypeData = TransactionType::factory()->make();
        unset($transactionTypeData['relevance']);

        $this->post(route('transaction-type.store'), $transactionTypeData->toArray())
            ->assertSessionHasErrors(['relevance' => __('validation.required', ['attribute' => 'Relevância'])]);
    }

    public function test_create_fail_invalid_relevance(): void
    {
        $transactionTypeData = TransactionType::factory()->make()->toArray();
        $transactionTypeData['relevance'] = '12345';

        $this->post(route('transaction-type.store'), $transactionTypeData)
            ->assertSessionHasErrors(['relevance' => __('validation.in', ['attribute' => 'Relevância'])]);
    }

    public function test_update_successfully(): void
    {
        $transactionTypeData = TransactionType::factory()->create([ 'active' => true ]);
        $transactionTypeUpdateData = TransactionType::factory()->make([ 'active' => false ]);

        $this->put(route('transaction-type.update', ['id' => $transactionTypeData->id]), $transactionTypeUpdateData->toArray())
            ->assertRedirect(route('transaction-type.list', ['message' => __('Data updated successfully.')]));

        $transactionTypeDataOriginalData = $transactionTypeData->toArray();
        $transactionTypeUpdatedData = $transactionTypeData->refresh();
        $this->assertEquals($transactionTypeUpdatedData['name'], $transactionTypeDataOriginalData['name']);         // Atributo não pode ser modificado
        
        $this->assertEquals($transactionTypeUpdatedData['relevance'], $transactionTypeUpdateData['relevance']);
        $this->assertEquals($transactionTypeUpdatedData['active'], $transactionTypeUpdateData['active']);
    }
}
