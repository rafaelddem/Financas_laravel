<?php

namespace App\Console\Commands;

use App\Services\NoticeService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateNotices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:notices {--daily} {--log}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new notices based on Transactions Planned';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->line(Carbon::now()->format('d/m/Y H:i:s') . ' - Preparing...');

            if ($this->option('daily')) {
                app(NoticeService::class)->generateNoticeByTransactionsPlannedForToday();
            }

            $this->info(Carbon::now()->format('d/m/Y H:i:s') . ' - Generated notices');
        } catch (\Throwable $th) {
            $returnMessage = Carbon::now()->format('d/m/Y H:i:s') . ' - Failed to generate notices.';

            if ($this->option('log')) 
                $returnMessage .= ' Description: ' . $th->getMessage() . '.';

            $this->error($returnMessage);
        }
    }
}
