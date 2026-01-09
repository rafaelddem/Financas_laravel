<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class InvoiceTest extends TestCase
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
        $this->get(route('invoice.list'))
            ->assertStatus(200);
    }

    public function test_details(): void
    {
        $invoice = Invoice::factory()->fromCard()->create();

        $this->get(route('invoice.details', ['invoice_id' => $invoice->id]))
            ->assertStatus(200);
    }

    public function test_pay(): void
    {
        $invoice = Invoice::factory()->fromCard()->create();
        Transaction::factory()->wallets(null, $invoice->card->wallet)->create([
            'gross_value' => $invoice->value,
        ]);

        $this->post(route('invoice.pay', ['id' => $invoice->id]));

        $invoice->refresh();
        $this->assertEquals($invoice->status, InvoiceStatus::Paid->value);
        $this->assertTrue($invoice->payment_date->diffInSeconds(Carbon::now()) <= 2);
    }

    public function test_if_alter_next_installments(): void
    {
        $end_date = Carbon::now()->subDay();
        $invoice = Invoice::factory()->fromCard()->create([
            'start_date' => $end_date->clone()->subMonth()->addDay(),
            'end_date' => $end_date,
        ]);
        Transaction::factory()->wallets(null, $invoice->card->wallet)->create([
            'gross_value' => $invoice->value,
        ]);

        $transaction = Transaction::factory()->credit($invoice->card, false)->withInstallments(3)->create([
            'transaction_date' => $invoice->start_date->addDay(),
            'processing_date' => $invoice->start_date->addDay(),
        ]);

        $this->post(route('invoice.pay', ['id' => $invoice->id]));

        $this->assertNotNull($transaction->installments()->get()->get(0)->payment_date);
        $this->assertNull($transaction->installments()->get()->get(1)->payment_date);
    }

    public function test_pay_without_transactions(): void
    {
        $invoice = Invoice::factory()->fromCard()->create([
            'value' => 0,
        ]);

        $this->post(route('invoice.pay', ['id' => $invoice->id]))
            ->assertRedirect(route('invoice.list', ['message' => __('Action executed successfully.')]));
    }

    public function test_fail_pay_without_amount(): void
    {
        $invoice = Invoice::factory()->fromCard()->create();

        $this->post(route('invoice.pay', ['id' => $invoice->id]))
            ->assertSessionHasErrors(['message' => __('The wallet does not have enough value for payment.')]);
    }
}
