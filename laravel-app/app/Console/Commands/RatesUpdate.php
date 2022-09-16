<?php

namespace App\Console\Commands;


use App\Services\Rates;
use Illuminate\Console\Command;


class RatesUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rates:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'updating data in exchange_table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function __construct(private Rates $rates)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info("Update Rates");
        $bar = $this->output->createProgressBar(10);
        $bar->start();
        $this->rates->insertToTable($bar);
        $bar->finish();
    }

}
