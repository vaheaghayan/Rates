<?php

namespace App\Services;

use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\ExchangeRate;

class OldRates
{
    private array $oldRatesArray;

    public function __construct(private ExchangeRateRepositoryInterface $exchangeRateRepository)
    {
        $this->oldRatesArray = array_reverse($this->exchangeRateRepository->getLastSixRates()->toArray());
    }


    private function dateFormatting($oldRatesArray): array
    {
        $oldRates = [];
        foreach ($oldRatesArray as $oldRateArray){
            $oldRateArray['ratetime'] = date_create($oldRateArray['ratetime']);
            $oldRateArray['cbratetime'] = date_create($oldRateArray['cbratetime']);
            $oldRates[] = $oldRateArray;
        }
        return $oldRates;
    }

    public function toArray(): array
    {
        return $this->dateFormatting($this->oldRatesArray);
    }

}
