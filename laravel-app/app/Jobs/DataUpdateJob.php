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


    public function __construct(private Rates $rates,private ExchangeRateRepositoryInterface $exchangeRateRepository)
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

        $this->rates->insertToTable();


//        if (count($ratesArray[0]) != 14)
//        {
//            $customLog->error('Error: Data format is invalid');
//            $customLog->info('DataUpdatedJob completed');
//            return;
//        }


        $customLog->info('DataUpdatedJob completed');

    }

}
