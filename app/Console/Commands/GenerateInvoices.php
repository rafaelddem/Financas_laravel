<?php

namespace App\Console\Commands;

use App\Exceptions\BaseException;
use App\Services\InvoiceService;
use Illuminate\Console\Command;

class GenerateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            app(InvoiceService::class)->generateInvoices();

            $resultMessage = __('Invoices created successfully.');
        } catch (BaseException $exception) {
            $resultMessage = __($exception->getMessage());
        } catch (\Throwable $th) {
            $resultMessage = __('An error occurred while performing the action. Please try again or contact support.');
        }

        print($resultMessage . PHP_EOL);
    }
}
