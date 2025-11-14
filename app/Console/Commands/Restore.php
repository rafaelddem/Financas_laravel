<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Restore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restore';

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
        $this->line('Removing tables...');

        Artisan::call('migrate:reset');

        $this->line('Running migrations...');

        Artisan::call('migrate', ['--seed' => true]);

        $this->line('Adding test data...');

        try {
            Artisan::call('db:seed', ['--class' => 'Restore']);

            $this->info('Existing data successfully added!');
        } catch (\Throwable $th) {
            $this->error('Failed to restore data');
        }
    }
}
