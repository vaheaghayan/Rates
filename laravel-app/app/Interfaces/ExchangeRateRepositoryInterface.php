<?php

namespace App\Interfaces;

interface ExchangeRateRepositoryInterface
{
    public function updateOrCreateRateByCurrency(array $currency, array $rateDetails);

    public function getColumnNames();
}
