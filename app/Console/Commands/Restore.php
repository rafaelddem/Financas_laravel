<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Restore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restore {--log}';

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
        $this->line(Carbon::now()->format('d/m/Y H:i:s') . ': Removing tables...');

        Artisan::call('migrate:reset');

        $this->line(Carbon::now()->format('d/m/Y H:i:s') . ': Running migrations...');

        Artisan::call('migrate', ['--seed' => true]);

        $this->line(Carbon::now()->format('d/m/Y H:i:s') . ': Adding test data...');

        try {
            Artisan::call('db:seed', ['--class' => 'Restore']);

            $this->info(Carbon::now()->format('d/m/Y H:i:s') . ': Existing data successfully added!');
        } catch (\Throwable $th) {
            if ($this->option('log')) {
                $this->error(Carbon::now()->format('d/m/Y H:i:s') . ': Failed to restore data: ' . $th->getMessage());
            } else {
                $this->error(Carbon::now()->format('d/m/Y H:i:s') . ': Failed to restore data');
            }
        }
    }
}
