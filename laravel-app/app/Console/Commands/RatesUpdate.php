<?php

namespace App\Console\Commands;

use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Jobs\DataUpdateJob;
use App\Services\OldRates;
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
    public function __construct(private Rates $rates, private OldRates $oldRates, private ExchangeRateRepositoryInterface $exchangeRateRepository)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        DataUpdateJob::dispatch($this->rates, $this->oldRates, $this->exchangeRateRepository);
    }

}
