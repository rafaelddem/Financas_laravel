<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentType;
use App\Enums\Relevance;
use App\Models\Card;
use App\Models\Installment;
use App\Models\Invoice;
use App\Models\Owner;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\Wallet;
use App\Services\CardService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Transaction Types */
        // case banal = "Banal";
        // case relevant = "Relevante";
        // case indispensable = "Indispensavel";
        $transactionTypeGenericIn = TransactionType::find(1);
        $transactionTypeGenericOut = TransactionType::find(2);
        $transactionTypeGenericTransf = TransactionType::find(3);
        $transactionTypeStudies = TransactionType::create([
            'name' => 'Estudos',
            'relevance' => Relevance::Relevant->value,
        ]);
        $transactionTypeInternet = TransactionType::create([
            'name' => 'Internet',
            'relevance' => Relevance::Indispensable->value,
        ]);
        $transactionTypeSalary = TransactionType::create([
            'name' => 'Salário',
            'relevance' => Relevance::Indispensable->value,
        ]);
        $transactionTypeCar = TransactionType::create([
            'name' => 'Carro',
            'relevance' => Relevance::Indispensable->value,
        ]);



        /* Payment Methods */
        // case notes = "Dinheiro físico";
        // case transfer = "Transação bancária";
        // case debit = "Débito (cartão)";
        // case credit = "Crédito (cartão)";
        $paymentMethodNotes = PaymentMethod::find(1);
        $paymentMethodTransfer = PaymentMethod::find(2);
        $paymentMethodDebit = PaymentMethod::find(3);
        $paymentMethodCredit = PaymentMethod::find(4);
        $paymentMethodPix = PaymentMethod::create([
            'name' => 'PIX',
            'type' => PaymentType::Transfer->value,
        ]);



        /* Owners */
        $ownerSystem = Owner::find(1);
        $ownerMe = Owner::find(2);

        /* Wallets | Cards */
        $walletSystem = Wallet::find(1);
        $walletMeDefault = Wallet::find(2);
        $walletNuBank = Wallet::create([
            'name' => 'NuBank',
            'owner_id' => $ownerMe->id,
            'main_wallet' => false,
            'description' => 'NuBank',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
            $cardDebitNubank = Card::create([
                'wallet_id' => $walletNuBank->id,
                'name' => 'NuBank Débito',
                'card_type' => 'debit',
                'first_day_month' => 1,
                'days_to_expiration' => 1,
                'active' => true,
            ]);

            $cardCreditNubank = Card::create([
                'wallet_id' => $walletNuBank->id,
                'name' => 'NuBank Crédito',
                'card_type' => 'credit',
                'first_day_month' => 1,
                'days_to_expiration' => 40,
                'active' => true,
            ]);
            // $startDate = CardService::calculateStartDate($cardCreditNubank->first_day_month);
            // Invoice::create([
            //     'card_id' => $cardCreditNubank->id,
            //     'start_date' => $startDate,
            //     'end_date' => $startDate->clone()->addMonth()->subDay(),
            //     'due_date' => $startDate->clone()->addMonth()->subDay()->addDays($cardCreditNubank->days_to_expiration),
            // ]);

            $cardCreditNubank_2 = Card::create([
                'wallet_id' => $walletNuBank->id,
                'name' => 'NuBank Black',
                'card_type' => 'credit',
                'first_day_month' => 1,
                'days_to_expiration' => 40,
                'active' => true,
            ]);
            $startDate = CardService::calculateStartDate($cardCreditNubank_2->first_day_month);
            $endDate = $startDate->clone()->addMonth()->subDay()->endOfDay();
            Invoice::create([
                'card_id' => $cardCreditNubank_2->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'due_date' => $endDate->clone()->addDays($cardCreditNubank_2->days_to_expiration),
                'payment_date' => $endDate->clone()->addDay(),
            ]);

        $walletSicoob = Wallet::create([
            'name' => 'Sicoob',
            'owner_id' => $ownerMe->id,
            'main_wallet' => false,
            'description' => 'Sicoob',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
            $cardDebitSicoob = Card::create([
                'wallet_id' => $walletSicoob->id,
                'name' => 'Sicoob Débito',
                'card_type' => 'debit',
                'first_day_month' => 1,
                'days_to_expiration' => 1,
                'active' => true,
            ]);

            $cardCreditSicoob = Card::create([
                'wallet_id' => $walletSicoob->id,
                'name' => 'Sicoob Crédito',
                'card_type' => 'credit',
                'first_day_month' => 1,
                'days_to_expiration' => 40,
                'active' => true,
            ]);
            $startDate = CardService::calculateStartDate($cardCreditSicoob->first_day_month);
            $endDate = $startDate->clone()->addMonth()->subDay()->endOfDay();
            Invoice::create([
                'card_id' => $cardCreditSicoob->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'due_date' => $endDate->clone()->addDays($cardCreditSicoob->days_to_expiration),
                'payment_date' => $endDate->clone()->addDay(),
            ]);

        $walletSantander = Wallet::create([
            'name' => 'Santander',
            'owner_id' => $ownerMe->id,
            'main_wallet' => false,
            'description' => 'Santander',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);



        /* Transactions */

        /* Data base das transações */
        $today = Carbon::now()->startOfDay();

        /* Dois meses atrás */

        $twoMonthsAgo = $today->clone()->startOfMonth()->subMonth(2);
        Transaction::create([
            'title' => 'Dinheiro inicial',
            'transaction_date' => $twoMonthsAgo,
            'processing_date' => $twoMonthsAgo,
            'transaction_type_id' => $transactionTypeGenericIn->id,
            'relevance' => Relevance::Indispensable->value,
            'payment_method_id' => $paymentMethodNotes->id,
            'source_wallet_id' => $walletSystem->id,
            'destination_wallet_id' => $walletMeDefault->id,
            'gross_value' => 500.00,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Valor inicial em dinheiro físico',
        ]);

        Transaction::create([
            'title' => 'Salário #1',
            'transaction_date' => $twoMonthsAgo,
            'processing_date' => $twoMonthsAgo,
            'transaction_type_id' => $transactionTypeSalary->id,
            'relevance' => Relevance::Indispensable->value,
            'payment_method_id' => $paymentMethodPix->id,
            'source_wallet_id' => $walletSystem->id,
            'destination_wallet_id' => $walletSantander->id,
            'gross_value' => 2000.00,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Salário (dois meses atrás)',
        ]);

        Transaction::create([
            'title' => 'Internet',
            'transaction_date' => $twoMonthsAgo->clone()->addDays(4),
            'processing_date' => $twoMonthsAgo->clone()->addDays(4),
            'transaction_type_id' => $transactionTypeInternet->id,
            'relevance' => Relevance::Indispensable->value,
            'payment_method_id' => $paymentMethodPix->id,
            'source_wallet_id' => $walletSantander->id,
            'destination_wallet_id' => $walletSystem->id,
            'gross_value' => 100.00,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Mensalidade internet (dois meses atrás)',
        ]);

        Transaction::create([
            'title' => 'Gasolina',
            'transaction_date' => $twoMonthsAgo->clone()->addDays(4),
            'processing_date' => $twoMonthsAgo->clone()->addDays(4),
            'transaction_type_id' => $transactionTypeCar->id,
            'relevance' => Relevance::Relevant->value,
            'payment_method_id' => $paymentMethodNotes->id,
            'source_wallet_id' => $walletMeDefault->id,
            'destination_wallet_id' => $walletSystem->id,
            'gross_value' => 100.00,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Gasolina #1 (dois meses atrás)',
        ]);

        Transaction::create([
            'title' => 'Gasolina',
            'transaction_date' => $twoMonthsAgo->clone()->addDays(23),
            'processing_date' => $twoMonthsAgo->clone()->addDays(23),
            'transaction_type_id' => $transactionTypeCar->id,
            'relevance' => Relevance::Relevant->value,
            'payment_method_id' => $paymentMethodNotes->id,
            'source_wallet_id' => $walletMeDefault->id,
            'destination_wallet_id' => $walletSystem->id,
            'gross_value' => 100.00,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Gasolina #2 (dois meses atrás)',
        ]);

        /* Crédits */

        $twoMonthsAgoCredits = new Collection();
        $twoMonthsAgoInvoiceValue = 0;

        $twoMonthsAgoCredits->push(
            Transaction::create([
                'title' => 'Netflix',
                'transaction_date' => $twoMonthsAgo->clone()->addDays(4),
                'processing_date' => $twoMonthsAgo->clone()->addDays(4),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Relevant->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 59.90,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Mensalidade Netflix (dois meses atrás)',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $twoMonthsAgoCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $twoMonthsAgoCredits->last()->transaction_date,
                'gross_value' => 59.90,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'payment_date' => $twoMonthsAgo->clone()->lastOfMonth()->addDay(),
            ]);
            $twoMonthsAgoInvoiceValue += $installment->net_value;

        $twoMonthsAgoCredits->push(
            Transaction::create([
                'title' => 'Mercado',
                'transaction_date' => $twoMonthsAgo->clone()->addDays(6),
                'processing_date' => $twoMonthsAgo->clone()->addDays(6),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 259.68,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Mercado #1 (dois meses atrás)',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $twoMonthsAgoCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $twoMonthsAgoCredits->last()->transaction_date,
                'gross_value' => 259.68,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'payment_date' => $twoMonthsAgo->clone()->lastOfMonth()->addDay(),
            ]);
            $twoMonthsAgoInvoiceValue += $installment->net_value;

        $twoMonthsAgoCredits->push(
            Transaction::create([
                'title' => 'Cafeteria',
                'transaction_date' => $twoMonthsAgo->clone()->addDays(8),
                'processing_date' => $twoMonthsAgo->clone()->addDays(8),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 69.98,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Cafeteria (dois meses atrás)',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $twoMonthsAgoCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $twoMonthsAgoCredits->last()->transaction_date,
                'gross_value' => 69.98,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'payment_date' => $twoMonthsAgo->clone()->lastOfMonth()->addDay(),
            ]);
            $twoMonthsAgoInvoiceValue += $installment->net_value;

        $twoMonthsAgoCredits->push(
            Transaction::create([
                'title' => 'Pizzaria',
                'transaction_date' => $twoMonthsAgo->clone()->addDays(11),
                'processing_date' => $twoMonthsAgo->clone()->addDays(12),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 159.80,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Pizzaria (dois meses atrás)',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $twoMonthsAgoCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $twoMonthsAgoCredits->last()->transaction_date,
                'gross_value' => 159.80,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'payment_date' => $twoMonthsAgo->clone()->lastOfMonth()->addDay(),
            ]);
            $twoMonthsAgoInvoiceValue += $installment->net_value;

        $twoMonthsAgoCredits->push(
            Transaction::create([
                'title' => 'Mercado',
                'transaction_date' => $twoMonthsAgo->clone()->addDays(18),
                'processing_date' => $twoMonthsAgo->clone()->addDays(18),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 59.99,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Mercado #2 (dois meses atrás)',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $twoMonthsAgoCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $twoMonthsAgoCredits->last()->transaction_date,
                'gross_value' => 30.00,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'payment_date' => $twoMonthsAgo->clone()->lastOfMonth()->addDay(),
            ]);
            $twoMonthsAgoInvoiceValue += $installment->net_value;
            Installment::create([
                'transaction_id' => $twoMonthsAgoCredits->last()->id,
                'installment_number' => 2,
                'installment_date' => $twoMonthsAgoCredits->last()->transaction_date->addMonth()->startOfMonth(),
                'gross_value' => 29.99,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);

        $twoMonthsAgoCredits->push(
            Transaction::create([
                'title' => 'Mercado',
                'transaction_date' => $twoMonthsAgo->clone()->addDays(20),
                'processing_date' => $twoMonthsAgo->clone()->addDays(20),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 59.99,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Mercado #3 (dois meses atrás)',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $twoMonthsAgoCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $twoMonthsAgoCredits->last()->transaction_date,
                'gross_value' => 59.99,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'payment_date' => $twoMonthsAgo->clone()->lastOfMonth()->addDay(),
            ]);
            $twoMonthsAgoInvoiceValue += $installment->net_value;

        $invoiceTwoMonthsAgo = Invoice::create([
            'card_id' => $cardCreditNubank->id,
            'start_date' => $twoMonthsAgo->clone(),
            'end_date' => $twoMonthsAgo->clone()->lastOfMonth()->endOfDay(),
            'due_date' => $twoMonthsAgo->clone()->lastOfMonth()->endOfDay()->addDays($cardCreditNubank->days_to_expiration),
            'payment_date' => $twoMonthsAgo->clone()->lastOfMonth()->endOfDay()->addDay(),
            'value' => $twoMonthsAgoInvoiceValue,
            'status' => InvoiceStatus::Paid->value,
        ]);

        Transaction::create([
            'title' => 'Boleto Crédito + Débito',
            'transaction_date' => $invoiceTwoMonthsAgo->end_date->clone()->addDay(),
            'processing_date' => $invoiceTwoMonthsAgo->end_date->clone()->addDay(),
            'transaction_type_id' => $transactionTypeGenericTransf->id,
            'relevance' => Relevance::Indispensable->value,
            'payment_method_id' => $paymentMethodPix->id,
            'source_wallet_id' => $walletSantander->id,
            'destination_wallet_id' => $walletNuBank->id,
            'gross_value' => $invoiceTwoMonthsAgo->value + 89.90,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Pix para pagamento do boleto e valor adicional para movimentação no débito (dois meses atrás)',
        ]);

        Transaction::create([
            'title' => 'Pagamento Boleto Crédito',
            'transaction_date' => $invoiceTwoMonthsAgo->end_date->clone()->addDay(),
            'processing_date' => $invoiceTwoMonthsAgo->end_date->clone()->addDay(),
            'transaction_type_id' => $transactionTypeGenericTransf->id,
            'relevance' => Relevance::Indispensable->value,
            'payment_method_id' => $paymentMethodPix->id,
            'source_wallet_id' => $walletNuBank->id,
            'destination_wallet_id' => $walletSystem->id,
            'gross_value' => $invoiceTwoMonthsAgo->value,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Pagamento do boleto (dois meses atrás)',
        ]);

        /* Débits */

        $twoMonthsAgoDebits = new Collection();

        $twoMonthsAgoDebits->push(
            Transaction::create([
                'title' => 'Ingresso show',
                'transaction_date' => $invoiceTwoMonthsAgo->end_date->clone()->addDay(),
                'processing_date' => $invoiceTwoMonthsAgo->end_date->clone()->addDay(),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodPix->id,
                'source_wallet_id' => $walletNuBank->id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 89.90,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Ingresso show (dois meses atrás)',
            ])
        );

        /* Um mês atrás */
        $oneMonthAgo = $today->clone()->startOfMonth()->subMonth(1);

        Transaction::create([
            'title' => 'Salário #2',
            'transaction_date' => $oneMonthAgo,
            'processing_date' => $oneMonthAgo,
            'transaction_type_id' => $transactionTypeSalary->id,
            'relevance' => Relevance::Indispensable->value,
            'payment_method_id' => $paymentMethodPix->id,
            'source_wallet_id' => $walletSystem->id,
            'destination_wallet_id' => $walletSantander->id,
            'gross_value' => 2000.00,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Salário (um mês atrás)',
        ]);

        Transaction::create([
            'title' => 'Internet',
            'transaction_date' => $oneMonthAgo->clone()->addDays(4),
            'processing_date' => $oneMonthAgo->clone()->addDays(4),
            'transaction_type_id' => $transactionTypeInternet->id,
            'relevance' => Relevance::Indispensable->value,
            'payment_method_id' => $paymentMethodPix->id,
            'source_wallet_id' => $walletSantander->id,
            'destination_wallet_id' => $walletSystem->id,
            'gross_value' => 100.00,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Mensalidade internet (um mês atrás)',
        ]);

        Transaction::create([
            'title' => 'Gasolina',
            'transaction_date' => $oneMonthAgo->clone()->addDays(4),
            'processing_date' => $oneMonthAgo->clone()->addDays(4),
            'transaction_type_id' => $transactionTypeCar->id,
            'relevance' => Relevance::Relevant->value,
            'payment_method_id' => $paymentMethodNotes->id,
            'source_wallet_id' => $walletMeDefault->id,
            'destination_wallet_id' => $walletSystem->id,
            'gross_value' => 75.00,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Gasolina #1 (um mês atrás)',
        ]);

        Transaction::create([
            'title' => 'Gasolina',
            'transaction_date' => $oneMonthAgo->clone()->addDays(23),
            'processing_date' => $oneMonthAgo->clone()->addDays(23),
            'transaction_type_id' => $transactionTypeCar->id,
            'relevance' => Relevance::Relevant->value,
            'payment_method_id' => $paymentMethodNotes->id,
            'source_wallet_id' => $walletMeDefault->id,
            'destination_wallet_id' => $walletSystem->id,
            'gross_value' => 150.00,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Gasolina #2 (um mês atrás)',
        ]);

        /* Créditos */

        $oneMonthsAgoCredits = new Collection();
        $oneMonthsAgoInvoiceValue = 0;

        $oneMonthsAgoCredits->push(
            Transaction::create([
                'title' => 'Netflix',
                'transaction_date' => $oneMonthAgo->clone()->addDays(4),
                'processing_date' => $oneMonthAgo->clone()->addDays(4),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Relevant->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 59.90,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Mensalidade Netflix (um mês atrás)',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $oneMonthsAgoCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $oneMonthsAgoCredits->last()->transaction_date,
                'gross_value' => 59.90,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);
            $oneMonthsAgoInvoiceValue += $installment->net_value;

        $oneMonthsAgoCredits->push(
            Transaction::create([
                'title' => 'Mercado',
                'transaction_date' => $oneMonthAgo->clone()->addDays(6),
                'processing_date' => $oneMonthAgo->clone()->addDays(6),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 239.25,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Mercado #1 (um mês atrás)',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $oneMonthsAgoCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $oneMonthsAgoCredits->last()->transaction_date,
                'gross_value' => 239.25,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);
            $oneMonthsAgoInvoiceValue += $installment->net_value;

        $oneMonthsAgoCredits->push(
            Transaction::create([
                'title' => 'Cafeteria',
                'transaction_date' => $oneMonthAgo->clone()->addDays(8),
                'processing_date' => $oneMonthAgo->clone()->addDays(8),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 69.98,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Cafeteria (um mês atrás)',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $oneMonthsAgoCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $oneMonthsAgoCredits->last()->transaction_date,
                'gross_value' => 69.98,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);
            $oneMonthsAgoInvoiceValue += $installment->net_value;

        $oneMonthsAgoCredits->push(
            Transaction::create([
                'title' => 'Tênis',
                'transaction_date' => $oneMonthAgo->clone()->addDays(8),
                'processing_date' => $oneMonthAgo->clone()->addDays(8),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 239.90,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Tênis (um mês atrás)',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $oneMonthsAgoCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $oneMonthsAgoCredits->last()->transaction_date,
                'gross_value' => 59.98,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);
            $oneMonthsAgoInvoiceValue += $installment->net_value;
            Installment::create([
                'transaction_id' => $oneMonthsAgoCredits->last()->id,
                'installment_number' => 2,
                'installment_date' => $oneMonthsAgoCredits->last()->transaction_date->addMonth()->startOfMonth(),
                'gross_value' => 59.98,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);
            Installment::create([
                'transaction_id' => $oneMonthsAgoCredits->last()->id,
                'installment_number' => 3,
                'installment_date' => $oneMonthsAgoCredits->last()->transaction_date->addMonths(2),
                'gross_value' => 59.97,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);
            Installment::create([
                'transaction_id' => $oneMonthsAgoCredits->last()->id,
                'installment_number' => 4,
                'installment_date' => $oneMonthsAgoCredits->last()->transaction_date->addMonths(3),
                'gross_value' => 59.97,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);

        $oneMonthsAgoCredits->push(
            Transaction::create([
                'title' => 'Mercado',
                'transaction_date' => $oneMonthAgo->clone()->addDays(15),
                'processing_date' => $oneMonthAgo->clone()->addDays(15),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 9.80,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Mercado #2 (um mês atrás)',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $oneMonthsAgoCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $oneMonthsAgoCredits->last()->transaction_date,
                'gross_value' => 9.80,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);
            $oneMonthsAgoInvoiceValue += $installment->net_value;

        $oneMonthsAgoCredits->push(
            Transaction::create([
                'title' => 'Mercado',
                'transaction_date' => $oneMonthAgo->clone()->addDays(20),
                'processing_date' => $oneMonthAgo->clone()->addDays(20),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 59.99,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Mercado #3 (um mês atrás)',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $oneMonthsAgoCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $oneMonthsAgoCredits->last()->transaction_date,
                'gross_value' => 59.99,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);
            $oneMonthsAgoInvoiceValue += $installment->net_value;

        $twoMonthsAgoCredits->each(function ($transaction) use (&$oneMonthsAgoInvoiceValue) {
            $oneMonthsAgoInvoiceValue += $transaction?->installments()->get()->get(1)?->net_value;
        });
        $invoiceOneMonthAgo = Invoice::create([
            'card_id' => $cardCreditNubank->id,
            'start_date' => $oneMonthAgo->clone(),
            'end_date' => $oneMonthAgo->clone()->lastOfMonth()->endOfDay(),
            'due_date' => $oneMonthAgo->clone()->lastOfMonth()->endOfDay()->addDays($cardCreditNubank->days_to_expiration),
            'value' => $oneMonthsAgoInvoiceValue,
            'status' => InvoiceStatus::Closed->value,
        ]);

        Transaction::create([
            'title' => 'Pix Boleto Crédito',
            'transaction_date' => $invoiceOneMonthAgo->end_date->clone()->addDay(),
            'processing_date' => $invoiceOneMonthAgo->end_date->clone()->addDay(),
            'transaction_type_id' => $transactionTypeGenericTransf->id,
            'relevance' => Relevance::Indispensable->value,
            'payment_method_id' => $paymentMethodPix->id,
            'source_wallet_id' => $walletSantander->id,
            'destination_wallet_id' => $walletNuBank->id,
            'gross_value' => $invoiceOneMonthAgo->value,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Pix para pagamento do boleto (um mês atrás)',
        ]);

        /* Débits */

        Transaction::create([
            'title' => 'Pix para eventuais débitos',
            'transaction_date' => $invoiceTwoMonthsAgo->end_date->clone()->addDay(),
            'processing_date' => $invoiceTwoMonthsAgo->end_date->clone()->addDay(),
            'transaction_type_id' => $transactionTypeGenericTransf->id,
            'relevance' => Relevance::Indispensable->value,
            'payment_method_id' => $paymentMethodPix->id,
            'source_wallet_id' => $walletSantander->id,
            'destination_wallet_id' => $walletNuBank->id,
            'gross_value' => 1000.00,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Pix para que a carteira tenha valores disponíveis para transações no no débito (um mês atrás)',
        ]);

        $oneMonthAgoDebits = new Collection();

        $oneMonthAgoDebits->push(
            Transaction::create([
                'title' => 'Cinema',
                'transaction_date' => $oneMonthAgo->clone()->addDays(10),
                'processing_date' => $oneMonthAgo->clone()->addDays(10),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodPix->id,
                'source_wallet_id' => $walletNuBank->id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 159.90,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Ingressos para o Cinema e pipoca (um mês atrás)',
            ])
        );

        $oneMonthAgoDebits->push(
            Transaction::create([
                'title' => 'Mercado',
                'transaction_date' => $oneMonthAgo->clone()->addDays(15),
                'processing_date' => $oneMonthAgo->clone()->addDays(15),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodPix->id,
                'source_wallet_id' => $walletNuBank->id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 29.90,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.10,
                'description' => 'Mercado (um mês atrás)',
            ])
        );

        /* Mês atual */

        $startOfMonth = $today->clone()->startOfMonth();

        Transaction::create([
            'title' => 'Salário #3',
            'transaction_date' => $startOfMonth,
            'processing_date' => $startOfMonth,
            'transaction_type_id' => $transactionTypeSalary->id,
            'relevance' => Relevance::Indispensable->value,
            'payment_method_id' => $paymentMethodPix->id,
            'source_wallet_id' => $walletSystem->id,
            'destination_wallet_id' => $walletSantander->id,
            'gross_value' => 2000.00,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Salário (um mês atrás)',
        ]);

        Transaction::create([
            'title' => 'Internet',
            'transaction_date' => $startOfMonth->clone()->addDays(4),
            'processing_date' => $startOfMonth->clone()->addDays(4),
            'transaction_type_id' => $transactionTypeInternet->id,
            'relevance' => Relevance::Indispensable->value,
            'payment_method_id' => $paymentMethodPix->id,
            'source_wallet_id' => $walletSantander->id,
            'destination_wallet_id' => $walletSystem->id,
            'gross_value' => 100.00,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Mensalidade internet',
        ]);

        Transaction::create([
            'title' => 'Gasolina',
            'transaction_date' => $startOfMonth->clone()->addDays(4),
            'processing_date' => $startOfMonth->clone()->addDays(4),
            'transaction_type_id' => $transactionTypeCar->id,
            'relevance' => Relevance::Relevant->value,
            'payment_method_id' => $paymentMethodNotes->id,
            'source_wallet_id' => $walletMeDefault->id,
            'destination_wallet_id' => $walletSystem->id,
            'gross_value' => 100.00,
            'discount_value' => 0.00,
            'interest_value' => 0.00,
            'rounding_value' => 0.00,
            'description' => 'Gasolina',
        ]);

        /* Crédito */

        $thisMonthCredits = new Collection();
        $thisMonthInvoiceValue = 0;

        $thisMonthCredits->push(
            Transaction::create([
                'title' => 'Mercado',
                'transaction_date' => $startOfMonth->clone()->addDays(4),
                'processing_date' => $startOfMonth->clone()->addDays(4),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 175.25,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Mercado',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $thisMonthCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $thisMonthCredits->last()->transaction_date,
                'gross_value' => 175.25,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);
            $thisMonthInvoiceValue += $installment->net_value;

        $thisMonthCredits->push(
            Transaction::create([
                'title' => 'Netflix',
                'transaction_date' => $startOfMonth->clone()->addDays(4),
                'processing_date' => $startOfMonth->clone()->addDays(4),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Relevant->value,
                'payment_method_id' => $paymentMethodCredit->id,
                'card_id' => $cardCreditNubank->id,
                'source_wallet_id' => $cardCreditNubank->wallet_id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 59.90,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Mensalidade Netflix',
            ])
        );
            $installment = Installment::create([
                'transaction_id' => $thisMonthCredits->last()->id,
                'installment_number' => 1,
                'installment_date' => $thisMonthCredits->last()->transaction_date,
                'gross_value' => 59.90,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);
            $thisMonthInvoiceValue += $installment->net_value;

        $twoMonthsAgoCredits->each(function ($transaction) use (&$thisMonthInvoiceValue) {
            $thisMonthInvoiceValue += $transaction?->installments()->get()->get(2)?->net_value;
        });
        $oneMonthsAgoCredits->each(function ($transaction) use (&$thisMonthInvoiceValue) {
            $thisMonthInvoiceValue += $transaction?->installments()->get()->get(1)?->net_value;
        });
        Invoice::create([
            'card_id' => $cardCreditNubank->id,
            'start_date' => $startOfMonth->clone(),
            'end_date' => $startOfMonth->clone()->lastOfMonth()->endOfDay(),
            'due_date' => $startOfMonth->clone()->lastOfMonth()->endOfDay()->addDays($cardCreditNubank->days_to_expiration),
            'value' => $thisMonthInvoiceValue,
            'status' => InvoiceStatus::Open->value,
        ]);

        /* Débito */

        $thisMonthAgoDebits = new Collection();

        $thisMonthAgoDebits->push(
            Transaction::create([
                'title' => 'Gasolina',
                'transaction_date' => $startOfMonth->clone()->addDays(7),
                'processing_date' => $startOfMonth->clone()->addDays(7),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodPix->id,
                'source_wallet_id' => $walletNuBank->id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 50.00,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Gasolina (este mês)',
            ])
        );

        /* Transações previstas */

        $thisMonthAgoDebits->push(
            Transaction::create([
                'title' => 'Gasolina',
                'transaction_date' => Carbon::now()->addMonth(),
                'processing_date' => Carbon::now()->addMonth(),
                'transaction_type_id' => $transactionTypeGenericOut->id,
                'relevance' => Relevance::Banal->value,
                'payment_method_id' => $paymentMethodPix->id,
                'source_wallet_id' => $walletNuBank->id,
                'destination_wallet_id' => $walletSystem->id,
                'gross_value' => 50.00,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => 'Gasolina (este mês)',
            ])
        );
    }

    // private function generateValues(float &$gross, float &$discount, float &$interest, float &$rounding)
    // {
    //     $rand = mt_rand(0, 10);

    //     $gross = mt_rand(100, 5000) / 100;

    //     if ($rand >= 6) 
    //         $discount = round(($gross / mt_rand(5, 10)), 2);

    //     if ($rand >= 9) 
    //         $interest = round(($gross * mt_rand(1, 10) / 100), 2);

    //     if ($rand >= 4) {
    //         $net = $gross - $discount + $interest;
    //         $rounding = ($net - round($net)) * -1;
    //     }
    // }

    // private function createTransactions_notes(int $daysAgo = 25)
    // {
    //     $today = new Carbon();
    //     $transactionTypeGenericOut = TransactionType::find(2);
    //     $paymentMethodNotes = PaymentMethod::find(1);
    //     $walletSystem = Wallet::find(1);
    //     $walletMeDefault = Wallet::find(2);

    //     for ($i=0; $i < $daysAgo; $i++) {
    //         $gross = $discount = $interest = $rounding = 0;
    //         $this->generateValues($gross, $discount, $interest, $rounding);

    //         Transaction::create([
    //             'title' => 'Transação simples #' . ($i + 1),
    //             'transaction_date' => $today->clone()->subDays($daysAgo - $i),
    //             'processing_date' => $today->clone()->subDays($daysAgo - $i),
    //             'transaction_type_id' => $transactionTypeGenericOut->id,
    //             'relevance' => Relevance::Banal->value,
    //             'payment_method_id' => $paymentMethodNotes->id,
    //             'source_wallet_id' => $walletMeDefault->id,
    //             'destination_wallet_id' => $walletSystem->id,
    //             'gross_value' => $gross,
    //             'discount_value' => $discount,
    //             'interest_value' => $interest,
    //             'rounding_value' => $rounding,
    //         ]);
    //     }
    // }

    // private function createTransactions_debit(int $daysAgo = 25)
    // {
    //     $today = new Carbon();
    //     $transactionTypeGenericOut = TransactionType::find(2);
    //     $paymentMethodDebit = PaymentMethod::where('type', PaymentType::Debit->value)->inRandomOrder()->first();
    //     $walletSystem = Wallet::find(1);
    //     // $baseDate = $today->setMonth($month)->startOfMonth()->startOfDay();
    //     $transactionTypeGenericIn = TransactionType::find(1);
    //     $walletMeDefault = Wallet::find(2);

    //     for ($i=0; $i < $daysAgo; $i++) {
    //         $gross = $discount = $interest = $rounding = 0;
    //         $this->generateValues($gross, $discount, $interest, $rounding);

    //         Transaction::create([
    //             'title' => 'Transação simples #' . ($i + 1),
    //             'transaction_date' => $today->clone()->subDays($daysAgo - $i),
    //             'processing_date' => $today->clone()->subDays($daysAgo - $i),
    //             'transaction_type_id' => $transactionTypeGenericOut->id,
    //             'relevance' => Relevance::Banal->value,
    //             'payment_method_id' => $paymentMethodDebit->id,
    //             'source_wallet_id' => $walletMeDefault->id,
    //             'destination_wallet_id' => $walletSystem->id,
    //             'gross_value' => $gross,
    //             'discount_value' => $discount,
    //             'interest_value' => $interest,
    //             'rounding_value' => $rounding,
    //         ]);
    //     }
    // }

    // private function createCreditTransactions(int $month, Card $card, int $count = 25)
    // {
    //     $today = new Carbon();
    //     $baseDate = $today->setMonth($month)->startOfMonth()->startOfDay();

    //     for ($i=0; $i < $count; $i++) { 
    //     //     Transaction::create([
    //     //         'title' => 'Netflix',
    //     //         'transaction_date' => $startOfMonth->clone()->addDays(4),
    //     //         'processing_date' => $startOfMonth->clone()->addDays(4),
    //     //         'transaction_type_id' => $transactionTypeGenericOut->id,
    //     //         'relevance' => Relevance::Relevant->value,
    //     //         'payment_method_id' => $paymentMethodCredit->id,
    //     //         'card_id' => $cardCreditNubank->id,
    //     //         'source_wallet_id' => $cardCreditNubank->wallet_id,
    //     //         'destination_wallet_id' => $walletSystem->id,
    //     //         'gross_value' => 59.90,
    //     //         'discount_value' => 0.00,
    //     //         'interest_value' => 0.00,
    //     //         'rounding_value' => 0.00,
    //     //         'description' => 'Mensalidade Netflix',
    //     //     ]);
    //     }
    // }
}
