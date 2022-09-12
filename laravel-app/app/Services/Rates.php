<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Interfaces\ExchangeRateRepositoryInterface;

class Rates
{

    private function getXML(): \SimpleXMLElement
    {
        $response = Http::get(env('EXCHANGE_RATES_URL'));
        if ($response->status() == 404){
            Log::channel('custom')->error('error while getting response');
        }else{
            Log::channel('custom')->info('response successfully received');
        }
        return simplexml_load_string($response->body());
    }

    public function __construct(private ExchangeRateRepositoryInterface $exchangeRateRepository)
    {}



    private function xmlToArray(\SimpleXMLElement $xmlObj): array
    {
        return  json_decode( json_encode($xmlObj), true);
    }

    private function dateFormatting($ratesArray):array
    {
        foreach ($ratesArray['RATE'] as $rate){
            $rate['@attributes']['ratetime'] = date_create($rate['@attributes']['ratetime']);
            $rate['@attributes']['cbratetime'] = date_create($rate['@attributes']['cbratetime']);
            $rates[] = $rate['@attributes'];
        }
        return $rates;
    }

    public function toArray(): array
    {
        $ratesArray = $this->xmlToArray($this->getXML());
        return $this->dateFormatting($ratesArray);
    }

    public function insertToTable(): void
    {
        foreach ($this->toArray() as $rate)
        {
            $this->exchangeRateRepository->createRate($rate);
        }
        Log::channel('custom')->info('Data inserted into table');
    }

}
