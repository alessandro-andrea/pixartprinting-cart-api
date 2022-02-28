<?php

namespace App\Casts;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

class Price implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $money = new Money($value, new Currency(env('CURRENCY', 'EUR')));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter('it_IT', \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param array $value
     * @param array $attributes
     * @return string
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return (int)$value;
    }

}
