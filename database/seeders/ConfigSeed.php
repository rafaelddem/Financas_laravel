<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Config::create([
            'key' => 'SYSTEM_ID',
            'env_key' => 'SYSTEM_ID',
            'name' => 'ID do usuário "Sistema"',
            'description' => 'ID do usuário "Sistema"',
            'value' => 1,
        ]);
        Config::create([
            'key' => 'MY_OWNER_ID',
            'env_key' => 'MY_OWNER_ID',
            'name' => 'ID do usuário principal',
            'description' => 'ID do usuário principal',
            'value' => 2,
        ]);
        Config::create([
            'key' => 'INVOICE_PAYMENT_CATEGORY',
            'env_key' => 'INVOICE_PAYMENT_CATEGORY',
            'name' => 'Categoria padrão no pagamento de Fatura',
            'description' => 'Categoria que será usada como padrão nas Transações de pagamento de Fatura',
            'value' => 4,
        ]);
        Config::create([
            'key' => 'INVOICE_PAYMENT_METHOD',
            'env_key' => 'INVOICE_PAYMENT_METHOD',
            'name' => 'Método de Pagamento padrão no pagamento de Fatura',
            'description' => 'Método de Pagamento que será usada como padrão nas Transações de pagamento de Fatura',
            'value' => 2,
        ]);
        Config::create([
            'key' => 'INVOICE_PARTIAL_PAYMENT_CATEGORY',
            'env_key' => 'INVOICE_PARTIAL_PAYMENT_CATEGORY',
            'name' => 'Categoria padrão no pagamento parcial de Fatura',
            'description' => 'Categoria que será usada como padrão nas Transações de pagamento parcial de Fatura',
            'value' => 5,
        ]);
        Config::create([
            'key' => 'INVOICE_TRANSACTION_BASE',
            'env_key' => 'INVOICE_TRANSACTION_BASE',
            'name' => 'Transação Padrão para o pagamento de Fatura',
            'description' => 'Transação que será usada por padrão no pagamento de Fatura',
            'value' => 1,
        ]);
        Config::create([
            'key' => 'TRANSACTION_LIMIT_DAYS_TO_UPDATE',
            'env_key' => 'TRANSACTION_LIMIT_DAYS_TO_UPDATE',
            'name' => 'Limite de dias para atualização da Transação',
            'description' => 'Quantidade de dias limite (a partir do dia atual) que limitará a atualização da Transação',
            'value' => 30,
        ]);
        Config::create([
            'key' => 'TRANSACTION_LIMIT_DAYS_TO_REMOVE',
            'env_key' => 'TRANSACTION_LIMIT_DAYS_TO_REMOVE',
            'name' => 'Limite de dias para remoção da Transação',
            'description' => 'Quantidade de dias limite (a partir do dia atual) que limitará a remoção da Transação',
            'value' => 30,
        ]);
    }
}
