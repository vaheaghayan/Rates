<?php

namespace App\Repositories;

use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\ExchangeRate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ExchangeRateRepository implements ExchangeRateRepositoryInterface
{
    public function getAllRates(): Collection
    {
        return ExchangeRate::all();
    }

    public function getRateById($rateId): Collection
    {
        return ExchangeRate::query()->findOrFail($rateId);
    }

    public function deleteRateById($rateId): int
    {
        return ExchangeRate::destroy($rateId);
    }

    public function createRate(array $rateDetails): Model
    {
        return ExchangeRate::query()->create($rateDetails);
    }

    public function updateRate($rateId, array $newDetails): int
    {
        return ExchangeRate::whereId($rateId)->update($newDetails);
    }

    public function getRatesCount(): int
    {
        return ExchangeRate::query()->count();
    }

    public function getLastSixRates(): Collection
    {
        return ExchangeRate::query()->orderByDesc('id')->limit(6)->get();
    }
}
