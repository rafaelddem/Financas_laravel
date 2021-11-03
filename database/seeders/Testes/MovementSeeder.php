<?php

namespace Database\Seeders\Testes;

use App\Models\Installment;
use App\Models\Movement;
use Illuminate\Database\Seeder;

class MovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $movement = Movement::create([
            'title'                => 'Entrada inicial',
            'installments'        => 1, 
            'movement_date'         => '2021-01-01', 
            'movement_type'         => 1,                   // 1 entrada | 2 saida
            'gross_value'          => 2000.0, 
            'descount_value'         => 0.0, 
            'rounding_value'   => 0.0, 
            'net_value'            => 2000.0, 
            'relevance'            => 2,                   // 0 baixa | 1 media | 3 alta
            'description'             => "Minha carteira - Valor inicial de 2.000,00", 
            'created_at'            => now(), 
            'updated_at'            => now(), 
        ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 1, 
                'duo_date'        => '2021-01-01', 
                'payment_date'         => '2021-01-01', 
                'gross_value'          => 2000.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 2000.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 1, 
                'destination_wallet'       => 3, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
/** */
        $movement = Movement::create([
            'title'                => 'Entrada inicial',
            'installments'        => 1, 
            'movement_date'         => '2021-01-01', 
            'movement_type'         => 1,                   // 1 entrada | 2 saida
            'gross_value'          => 2000.0, 
            'descount_value'         => 0.0, 
            'rounding_value'   => 0.0, 
            'net_value'            => 2000.0, 
            'relevance'            => 2,                   // 0 baixa | 1 media | 3 alta
            'description'             => "Terezinha - Valor inicial de 2.000,00", 
            'created_at'            => now(), 
            'updated_at'            => now(), 
        ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 1, 
                'duo_date'        => '2021-01-01', 
                'payment_date'         => '2021-01-01', 
                'gross_value'          => 2000.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 2000.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 1, 
                'destination_wallet'       => 6, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
/** */
        $movement = Movement::create([
            'title'                => 'Entrada inicial',
            'installments'        => 1, 
            'movement_date'         => '2021-01-01', 
            'movement_type'         => 1,                   // 1 entrada | 2 saida
            'gross_value'          => 2000.0, 
            'descount_value'         => 0.0, 
            'rounding_value'   => 0.0, 
            'net_value'            => 2000.0, 
            'relevance'            => 2,                   // 0 baixa | 1 media | 3 alta
            'description'             => "Márcio - Valor inicial de 2.000,00", 
            'created_at'            => now(), 
            'updated_at'            => now(), 
        ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 1, 
                'duo_date'        => '2021-01-01', 
                'payment_date'         => '2021-01-01', 
                'gross_value'          => 2000.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 2000.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 1, 
                'destination_wallet'       => 7, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
/** */
        $movement = Movement::create([
            'title'                => 'Saída de 15 reais',
            'installments'        => 1, 
            'movement_date'         => '2021-01-01', 
            'movement_type'         => 2,                   // 1 entrada | 2 saida
            'gross_value'          => 15.0, 
            'descount_value'         => 0.0, 
            'rounding_value'   => 0.0, 
            'net_value'            => 15.0, 
            'relevance'            => 2,                   // 0 baixa | 1 media | 3 alta
            'description'             => "Minha carteira - Gasto simples de 15 reais", 
            'created_at'            => now(), 
            'updated_at'            => now(), 
        ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 1, 
                'duo_date'        => '2021-01-01', 
                'payment_date'         => '2021-01-01', 
                'gross_value'          => 15.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 15.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
/** */
        $movement = Movement::create([
            'title'                => 'Saída de 35 reais',
            'installments'        => 1, 
            'movement_date'         => '2021-01-01', 
            'movement_type'         => 2,                   // 1 entrada | 2 saida
            'gross_value'          => 35.0, 
            'descount_value'         => 0.0, 
            'rounding_value'   => 0.0, 
            'net_value'            => 35.0, 
            'relevance'            => 2,                   // 0 baixa | 1 media | 3 alta
            'description'             => "Minha carteira - Gasto simples de 35 reais", 
            'created_at'            => now(), 
            'updated_at'            => now(), 
        ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 1, 
                'duo_date'        => '2021-01-01', 
                'payment_date'         => '2021-01-01', 
                'gross_value'          => 35.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 35.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
/** */
        $movement = Movement::create([
            'title'                => 'Saída de 50 reais em 5 parcelas',
            'installments'        => 5, 
            'movement_date'         => '2021-01-01', 
            'movement_type'         => 2,                   // 1 entrada | 2 saida
            'gross_value'          => 50.0, 
            'descount_value'         => 0.0, 
            'rounding_value'   => 0.0, 
            'net_value'            => 50.0, 
            'relevance'            => 2,                   // 0 baixa | 1 media | 3 alta
            'description'             => "Minha carteira - Gasto parcelado de 50 reais", 
            'created_at'            => now(), 
            'updated_at'            => now(), 
        ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 1, 
                'duo_date'        => '2021-02-01', 
                'payment_date'         => '2021-02-01', 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 2, 
                'duo_date'        => '2021-03-01', 
                'payment_date'         => '2021-03-01', 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 3, 
                'duo_date'        => '2021-04-01', 
                'payment_date'         => '2021-04-01', 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 4, 
                'duo_date'        => '2021-05-01', 
                'payment_date'         => null, 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 5, 
                'duo_date'        => '2021-06-01', 
                'payment_date'         => null, 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
/** */
        $movement = Movement::create([
            'title'                => 'Saída de 50 reais em 5 parcelas',
            'installments'        => 5, 
            'movement_date'         => '2021-03-01', 
            'movement_type'         => 2,                   // 1 entrada | 2 saida
            'gross_value'          => 50.0, 
            'descount_value'         => 0.0, 
            'rounding_value'   => 0.0, 
            'net_value'            => 50.0, 
            'relevance'            => 2,                   // 0 baixa | 1 media | 3 alta
            'description'             => "Minha carteira - Gasto parcelado de 50 reais", 
            'created_at'            => now(), 
            'updated_at'            => now(), 
        ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 1, 
                'duo_date'        => '2021-04-01', 
                'payment_date'         => '2021-04-01', 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 2, 
                'duo_date'        => '2021-05-01', 
                'payment_date'         => '2021-05-01', 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 3, 
                'duo_date'        => '2021-06-01', 
                'payment_date'         => '2021-06-01', 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 4, 
                'duo_date'        => '2021-07-01', 
                'payment_date'         => null, 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 5, 
                'duo_date'        => '2021-08-01', 
                'payment_date'         => null, 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
/** */
        $movement = Movement::create([
            'title'                => 'Saída de 50 reais em 5 parcelas',
            'installments'        => 5, 
            'movement_date'         => '2021-02-01', 
            'movement_type'         => 2,                   // 1 entrada | 2 saida
            'gross_value'          => 50.0, 
            'descount_value'         => 0.0, 
            'rounding_value'   => 0.0, 
            'net_value'            => 50.0, 
            'relevance'            => 2,                   // 0 baixa | 1 media | 3 alta
            'description'             => "Minha carteira - Gasto parcelado de 50 reais", 
            'created_at'            => now(), 
            'updated_at'            => now(), 
        ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 1, 
                'duo_date'        => '2021-03-01', 
                'payment_date'         => '2021-03-01', 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 2, 
                'duo_date'        => '2021-04-01', 
                'payment_date'         => '2021-04-01', 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 3, 
                'duo_date'        => '2021-05-01', 
                'payment_date'         => '2021-05-01', 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 4, 
                'duo_date'        => '2021-06-01', 
                'payment_date'         => null, 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 5, 
                'duo_date'        => '2021-07-01', 
                'payment_date'         => null, 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
/** */
        $movement = Movement::create([
            'title'                => 'Saída de 50 reais em 5 parcelas',
            'installments'        => 5, 
            'movement_date'         => '2021-01-01', 
            'movement_type'         => 2,                   // 1 entrada | 2 saida
            'gross_value'          => 50.0, 
            'descount_value'         => 0.0, 
            'rounding_value'   => 0.0, 
            'net_value'            => 50.0, 
            'relevance'            => 2,                   // 0 baixa | 1 media | 3 alta
            'description'             => "Minha carteira - Gasto parcelado de 50 reais", 
            'created_at'            => now(), 
            'updated_at'            => now(), 
        ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 1, 
                'duo_date'        => '2021-02-01', 
                'payment_date'         => '2021-02-01', 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 2, 
                'duo_date'        => '2021-03-01', 
                'payment_date'         => '2021-03-01', 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 3, 
                'duo_date'        => '2021-04-01', 
                'payment_date'         => '2021-04-01', 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 4, 
                'duo_date'        => '2021-05-01', 
                'payment_date'         => null, 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
            Installment::create([
                'movement'             => $movement->id, 
                'installment_number'               => 5, 
                'duo_date'        => '2021-06-01', 
                'payment_date'         => null, 
                'gross_value'          => 10.0, 
                'descount_value'         => 0.0, 
                'interest_value'            => 0.0, 
                'rounding_value'   => 0.0, 
                'net_value'            => 10.0, 
                'payment_method'        => 1,                   // 1 Dinheiro | 2 Crédito | 3 Débito
                'source_wallet'        => 3, 
                'destination_wallet'       => 2, 
                'created_at'            => now(), 
                'updated_at'            => now(), 
            ]);
    }
}