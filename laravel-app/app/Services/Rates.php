<?php
namespace App\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;
use App\Interfaces\ExchangeRateRepositoryInterface;
class Rates
{

    private function getResponse(): Response
    {
        $client = new Client();
//        $response = $client->get(env('EXю.CHANGE_RATES_URL'));
        $response = $client->get('https://httpstat.us/500');
        return $response;
    }

    private function getXML(): \SimpleXMLElement
    {
        return simplexml_load_string($this->getResponse()->getBody()->getContents());
    }

    public function __construct(private ExchangeRateRepositoryInterface $exchangeRateRepository)
    {}

    private function xmlToArray(): array
    {
        $convertedData = array();
        $xmlArray  = (array) $this->getXML();
        foreach ($xmlArray['RATE'] as $rate)
        {
            $attributes = ((array) $rate)['@attributes'];
            $convertedData[] = $attributes;
        }
        return $this->dateFormatting($convertedData);
    }

    private function dateFormatting($ratesArray): array
    {
        $rates = [];
        foreach ($ratesArray as $rate)
        {
            $rate['ratetime'] = date_create($rate['ratetime']);
            $rate['cbratetime'] = date_create($rate['cbratetime']);
            $rates[] = $rate;
        }
        return $rates;
    }

    private function unsetRedundantColumns(array $columns): array
    {
        return array_diff($columns, ['id', 'created_at', 'updated_at']);
    }

    public function insertToTable($bar): void
    {
        try {
            $bar->advance();
            $rates = $this->xmlToArray();
        }catch (\Exception $exception){
            Log::channel('custom')->error($exception->getMessage());
            return;
        }
        foreach ($rates as $rate) {
            $bar->advance();

            $columns = $this->unsetRedundantColumns($this->exchangeRateRepository->getColumnNames());

            if (array_diff(array_keys($rate),$columns))
            {
                Log::channel('custom')->error('Invalid Data');
                return;
            }
            $currency = $rate['currency'];
            unset($rate['currency']);
            $this->exchangeRateRepository->updateOrCreateRateByCurrency(['currency' => $currency], $rate);
        }
        $bar->advance();
    }
}
