<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperCurrencyPair
 */
class CurrencyPair extends Model
{
    use HasFactory;

    protected $with = [
        'baseCurrency',
        'targetCurrency',
    ];

    /**
     * @return HasOne
     */
    public function baseCurrency(): HasOne
    {
        return $this->hasOne(Currency::class, 'id', 'base_currency');
    }

    /**
     * @return HasOne
     */
    public function targetCurrency(): HasOne
    {
        return $this->hasOne(Currency::class, 'id', 'target_currency');
    }

    /**
     * @return HasMany
     */
    public function trades(): HasMany
    {
        return $this->hasMany(CurrencyPairTrade::class);
    }

    /**
     * @return HasMany
     */
    public function prices(): HasMany
    {
        return $this->hasMany(CurrencyPairPrice::class);
    }

    /**
     * @return HasOne
     */
    public function latestPrice(): HasOne
    {
        return $this->hasOne(CurrencyPairPrice::class)->ofMany()->latest();
    }
}
