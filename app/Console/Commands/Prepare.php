<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Prepare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:prepare {--restore=} {--log}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates/Recreates the database structure.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->line(Carbon::now()->format('d/m/Y H:i:s') . ' - Removing tables...');

            Artisan::call('migrate:reset');

            $this->line(Carbon::now()->format('d/m/Y H:i:s') . ' - Creating tables...');

            Artisan::call('migrate');

            $this->line(Carbon::now()->format('d/m/Y H:i:s') . ' - Creating default data...');

            Artisan::call('db:seed');

            if ($this->option('restore')) {
                $this->line(Carbon::now()->format('d/m/Y H:i:s') . ' - Adding data...');

                Artisan::call('db:seed', ['--class' => $this->option('restore')]);

                $this->info(Carbon::now()->format('d/m/Y H:i:s') . ' - Existing data successfully added.');
            }

            $this->info(Carbon::now()->format('d/m/Y H:i:s') . ' - Database create successfully!');
        } catch (\Throwable $th) {
            $returnMessage = Carbon::now()->format('d/m/Y H:i:s') . ' - Failed to restore data.';

            if ($this->option('log')) 
                $returnMessage .= ' Description: ' . $th->getMessage() . '.';

            $this->error($returnMessage);
        }
    }
}
