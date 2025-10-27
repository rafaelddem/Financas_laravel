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
        $this->info('Removing tables...');

        Artisan::call('migrate:reset');

        $this->info('Running migrations...');

        Artisan::call('migrate', ['--seed' => true]);

        $this->info('Adding test data...');

        try {
            Artisan::call('db:seed', ['--class' => 'Restore']);
        } catch (\Throwable $th) {}

        $this->info('Exist data added successfully!');
    }
}
