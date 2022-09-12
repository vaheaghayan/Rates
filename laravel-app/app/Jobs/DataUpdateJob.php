<?php

namespace App\Jobs;

use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Services\OldRates;
use App\Services\Rates;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DataUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */


    public function __construct(private Rates $rates,private OldRates $oldRates,private ExchangeRateRepositoryInterface $exchangeRateRepository)
    {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $customLog = Log::channel('custom');
        $customLog->info('DataUpdateJob started');

        $ratesArray = $this->rates->toArray();

        if (!$this->exchangeRateRepository->getRatesCount())
        {
            $this->rates->insertToTable();
            $customLog->info('DataUpdatedJob completed');
            return;
        }

        if (count($ratesArray) != 6 or count($ratesArray[0]) != 14)
        {
            $customLog->error('Error: Data format is invalid');
            $customLog->info('DataUpdatedJob completed');
            return;
        }


        $oldRatesArray = $this->oldRates->toArray();

        if($oldRatesArray != $ratesArray)
        {
            $this->rates->insertToTable();
        }else{
            $customLog->info( 'The data does not need to be modified');
            $customLog->info('DataUpdatedJob completed');
            return;
        }

        $customLog->info( 'Data inserted into table');
        $customLog->info('DataUpdatedJob completed');

    }

}
