<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency',
        'buy',
        'sell',
        'buycardsrate',
        'sellcardsrate',
        'buycashrate',
        'sellcashrate',
        'ratetime',
        'cbrate',
        'cbratetime',
        'buyrateforcross',
        'sellrateforcross',
        'buyratefortransfer',
        'sellratefortransfer'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}
