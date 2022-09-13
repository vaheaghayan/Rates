<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Interfaces\ExchangeRateRepositoryInterface;

class Rates
{

    private function responseLogging($response): void
    {
        $log = [];
        $log['status'] = $response->status();
        $log['body'] = json_encode($response->body());
        $log['total_time'] = $response->handlerStats()['total_time'];
        Log::channel('custom')->info($log);
    }

    private function getXML(): \SimpleXMLElement
    {
        $response = Http::get(env('EXCHANGE_RATES_URL'));
        $this->responseLogging($response);
        return simplexml_load_string($response->body());
    }

    public function __construct(private ExchangeRateRepositoryInterface $exchangeRateRepository)
    {}

    private function xmlToArray(): array
    {
        return  $this->dateFormatting(json_decode( json_encode($this->getXML()), true));
    }

    private function dateFormatting($ratesArray): array
    {
        $rates = [];
        foreach ($ratesArray['RATE'] as $rate){
            $rate['@attributes']['ratetime'] = date_create($rate['@attributes']['ratetime']);
            $rate['@attributes']['cbratetime'] = date_create($rate['@attributes']['cbratetime']);
            $rates[] = $rate['@attributes'];
        }
        return $rates;
    }

    private function unsetRedundantData(array $columns): array
    {
        array_shift($columns);
        array_pop($columns);
        array_pop($columns);
        return $columns;
    }

    public function insertToTable(): void
    {
        foreach ($this->xmlToArray() as $rate)
        {
            $columns = $this->unsetRedundantData($this->exchangeRateRepository->getColumnNames());

            if ($columns != array_keys($rate)){
                Log::channel('custom')->error('Invalid Data');
                return;
            }

            $currency = $rate['currency'];
            unset($rate['currency']);
            $this->exchangeRateRepository->updateOrCreateRateByCurrency(['currency' => $currency], $rate);
        }
        Log::channel('custom')->info('create or update data');
    }
}
