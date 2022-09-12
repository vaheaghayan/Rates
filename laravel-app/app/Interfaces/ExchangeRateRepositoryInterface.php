<?php

namespace App\Interfaces;

interface ExchangeRateRepositoryInterface
{
    public function getAllRates();
    public function getRateById($rateId);
    public function deleteRateById($rateId);
    public function createRate(array $rateDetails);
    public function updateRate($rateId, array $newDetails);
    public function getRatesCount();
    public function getLastSixRates();
}
