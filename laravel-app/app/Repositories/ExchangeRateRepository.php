<?php

namespace App\Repositories;

use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\ExchangeRate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ExchangeRateRepository implements ExchangeRateRepositoryInterface
{
    public function updateOrCreateRateByCurrency(array $currency, array $rateDetails): Model
    {
        return ExchangeRate::updateOrCreate($currency,$rateDetails);
    }
    public function getColumnNames(): array
    {
        return Schema::getColumnListing('exchange_rates');
    }

}
