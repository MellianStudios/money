<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCurrencyPairPrice
 */
class CurrencyPairPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_pair_id',
        'last',
        'high',
        'low',
        'volume',
        'bid',
        'ask',
        'created_at',
        'updated_at',
    ];
}
