<?php

namespace Database\Seeders;

use App\Enums\PaymentType;
use App\Enums\Relevance;
use App\Models\Card;
use App\Models\Invoice;
use App\Models\Owner;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\Wallet;
use App\Services\CardService;
use Carbon\Carbon;
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
                'days_to_expiration' => 10,
                'active' => true,
            ]);
            $endDate = CardService::calculateEndDate($cardCreditNubank->first_day_month);
            Invoice::create([
                'card_id' => $cardCreditNubank->id,
                'start_date' => $endDate->clone()->subMonth()->startOfDay(),
                'end_date' => $endDate,
                'due_date' => $endDate->clone()->addDays($cardCreditNubank->days_to_expiration),
            ]);

            $cardCreditNubank_2 = Card::create([
                'wallet_id' => $walletNuBank->id,
                'name' => 'NuBank Black',
                'card_type' => 'credit',
                'first_day_month' => 1,
                'days_to_expiration' => 10,
                'active' => true,
            ]);
            $endDate = CardService::calculateEndDate($cardCreditNubank_2->first_day_month);
            Invoice::create([
                'card_id' => $cardCreditNubank_2->id,
                'start_date' => $endDate->clone()->subMonth()->startOfDay(),
                'end_date' => $endDate,
                'due_date' => $endDate->clone()->addDays($cardCreditNubank_2->days_to_expiration),
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
                'days_to_expiration' => 10,
                'active' => true,
            ]);
            $endDate = CardService::calculateEndDate($cardCreditSicoob->first_day_month);
            Invoice::create([
                'card_id' => $cardCreditSicoob->id,
                'start_date' => $endDate->clone()->subMonth()->startOfDay(),
                'end_date' => $endDate,
                'due_date' => $endDate->clone()->addDays($cardCreditSicoob->days_to_expiration),
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
            'gross_value' => 1000.00,
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
        ]);

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
        ]);

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
        ]);

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
            'description' => 'Mercado #2 (dois meses atrás)',
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

        /**
         * Lançar dados cartão
         */

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
        ]);

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
        ]);

        $transactionCreditOneMonthAgoTenis = Transaction::create([
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
        ]);
            DB::table('installments')->insert([
                'transaction_id' => $transactionCreditOneMonthAgoTenis->id,
                'installment_number' => 1,
                'installment_date' => $transactionCreditOneMonthAgoTenis->transaction_date,
                'gross_value' => 59.98,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);
            DB::table('installments')->insert([
                'transaction_id' => $transactionCreditOneMonthAgoTenis->id,
                'installment_number' => 2,
                'installment_date' => (new Carbon($transactionCreditOneMonthAgoTenis->transaction_date))->addMonth(),
                'gross_value' => 59.98,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);
            DB::table('installments')->insert([
                'transaction_id' => $transactionCreditOneMonthAgoTenis->id,
                'installment_number' => 3,
                'installment_date' => (new Carbon($transactionCreditOneMonthAgoTenis->transaction_date))->addMonths(2),
                'gross_value' => 59.97,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);
            DB::table('installments')->insert([
                'transaction_id' => $transactionCreditOneMonthAgoTenis->id,
                'installment_number' => 4,
                'installment_date' => (new Carbon($transactionCreditOneMonthAgoTenis->transaction_date))->addMonths(3),
                'gross_value' => 59.97,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
            ]);

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
        ]);

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

        /**
         * Lançar dados cartão
         */

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
    }
}
