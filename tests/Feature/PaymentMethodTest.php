<?php

namespace Tests\Feature;

use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
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
        $response = $this->get(route('payment-method.list'));

        $response->assertStatus(200);
        $this->assertArrayHasKey('paymentMethods', $response->original);
    }

    public function test_create_successfully(): void
    {
        $paymentMethod = PaymentMethod::factory()->make();

        $this->post(route('payment-method.store'), $paymentMethod->toArray())
            ->assertRedirect(route('payment-method.list', ['message' => __('Data created successfully.')]));

        $this->assertDatabaseHas('payment_methods', $paymentMethod->toArray());
    }

    public function test_create_fail_without_name(): void
    {
        $paymentMethodData = PaymentMethod::factory()->make();
        unset($paymentMethodData['name']);

        $this->post(route('payment-method.store'), $paymentMethodData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => __('Name')])]);
    }

    public function test_create_fail_duplicate_name(): void
    {
        $paymentMethodData = PaymentMethod::factory()->create();

        $this->post(route('payment-method.store'), $paymentMethodData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.unique', ['attribute' => __('Name')])]);
    }

    public function test_create_fail_very_short_name(): void
    {
        $paymentMethodData = PaymentMethod::factory()->make([
            'name' => 'Na'
        ]);

        $this->post(route('payment-method.store'), $paymentMethodData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => __('Name'), 'min' => 3, 'max' => 30])]);
    }

    public function test_create_fail_very_long_name(): void
    {
        $paymentMethodData = PaymentMethod::factory()->make([
            'name' => '1234567890123456789012345678901'
        ]);

        $this->post(route('payment-method.store'), $paymentMethodData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => __('Name'), 'min' => 3, 'max' => 30])]);
    }

    public function test_create_fail_without_type(): void
    {
        $paymentMethodData = PaymentMethod::factory()->make();
        unset($paymentMethodData['type']);

        $this->post(route('payment-method.store'), $paymentMethodData->toArray())
            ->assertSessionHasErrors(['type' => __('validation.required', ['attribute' => __('Type')])]);
    }

    public function test_create_fail_invalid_type(): void
    {
        $paymentMethodData = PaymentMethod::factory()->make()->toArray();
        $paymentMethodData['type'] = '12345';

        $this->post(route('payment-method.store'), $paymentMethodData)
            ->assertSessionHasErrors(['type' => __('validation.in', ['attribute' => __('Type')])]);
    }

    public function test_update_inactivate_successfully(): void
    {
        $paymentMethodData = PaymentMethod::factory()->create([ 'active' => true ]);
        $paymentMethodUpdateData = PaymentMethod::factory()->make([ 'active' => false ]);

        $this->put(route('payment-method.update', ['id' => $paymentMethodData->id]), $paymentMethodUpdateData->toArray())
            ->assertRedirect(route('payment-method.list', ['message' => __('Data updated successfully.')]));

        $paymentMethodDataOriginalData = $paymentMethodData->toArray();
        $paymentMethodUpdatedData = $paymentMethodData->refresh();
        $this->assertEquals($paymentMethodUpdatedData['name'], $paymentMethodDataOriginalData['name']);         // Atributo não pode ser modificado
        $this->assertEquals($paymentMethodUpdatedData->type->value, $paymentMethodDataOriginalData['type']);         // Atributo não pode ser modificado

        $this->assertEquals($paymentMethodUpdatedData['active'], $paymentMethodUpdateData['active']);
    }

    public function test_update_activate_successfully(): void
    {
        $paymentMethodData = PaymentMethod::factory()->create([ 'active' => false ]);
        $paymentMethodUpdateData = PaymentMethod::factory()->make([ 'active' => true ]);

        $this->put(route('payment-method.update', ['id' => $paymentMethodData->id]), $paymentMethodUpdateData->toArray())
            ->assertRedirect(route('payment-method.list', ['message' => __('Data updated successfully.')]));

        $paymentMethodDataOriginalData = $paymentMethodData->toArray();
        $paymentMethodUpdatedData = $paymentMethodData->refresh();
        $this->assertEquals($paymentMethodUpdatedData['name'], $paymentMethodDataOriginalData['name']);         // Atributo não pode ser modificado
        $this->assertEquals($paymentMethodUpdatedData->type->value, $paymentMethodDataOriginalData['type']);         // Atributo não pode ser modificado

        $this->assertEquals($paymentMethodUpdatedData['active'], $paymentMethodUpdateData['active']);
    }

    public function test_remove_successfully(): void
    {
        $paymentMethod = PaymentMethod::factory()->create();

        $this->delete(route('payment-method.destroy'), $paymentMethod->toArray())
            ->assertRedirect(route('payment-method.list', ['message' => __('Data deleted successfully.')]));

        $this->assertDatabaseMissing('payment_methods', [
            'id' => $paymentMethod->id,
        ]);
    }

    public function test_remove_fail(): void
    {
        $this->delete(route('payment-method.destroy'), ['id' => 999999999999 ])
            ->assertRedirect(route('payment-method.list'))
            ->assertSessionHasErrors(['message' => __("The reported record was not found.")]);
    }

    public function test_remove_fail_used_PaymentMethod(): void
    {
        $paymentMethod = PaymentMethod::factory()->create();
        Transaction::factory()->create([
            'payment_method_id' => $paymentMethod->id,
        ]);

        $this->delete(route('payment-method.destroy'), $paymentMethod->toArray())
            ->assertRedirect(route('payment-method.list'))
            ->assertSessionHasErrors(['message' => __("It is not allowed to remove a Payment Method that is linked to a transaction.")]);

        $this->assertDatabaseHas('payment_methods', $paymentMethod->toArray());
    }
}
