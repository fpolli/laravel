<?php

namespace App\FredOS\Traits;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Brick\Money\Money as Brick;
use NumberFormatter;

trait Money
{

  public function addMoneyColumnsToMigration(Blueprint $table)
  {
    $table->integer('amount');
    $table->string('currency', 3)->default('USD');
  }


  //'major', 'minor', 'currency'
  //uses Brick to convert to int 
  public function setAmountFromInput(array $options)
  {
    $options['minor'] = $options['minor'] ?? '00';
    $moneystr = "{$options['major']}.{$options['minor']}";
    $money = Brick::of($moneystr, $options['currency']);
    return $money->getMinorAmount()->toInt();
  }

  //mutator for models
  protected function amount(): Attribute
  {
    return Attribute::make(
      set: function ($value) {
        if (is_array($value)) {
          return $this->setAmountFromInput($value);
        }
        if (is_int($value)) {
          return $value;
        }
        return intval($value, 10);
      }
    );
  }


  //if  not using on a model, must supply amount and currency
  public function getAmountForInput(int $amount = 0, string $currency = '')
  {
    $amount = $amount ?: $this->amount;
    $currency = $currency ?: $this->currency;
    $str = $this->getAmountString(long: false, amount: $amount, currency: $currency);
    $divorced = explode('.', $str);
    $subatomic = [];
    if (count($divorced) == 2) {
      $subatomic['major'] = $divorced[0];
      $subatomic['minor'] = $divorced[1];
    } else {
      $subatomic['major'] = 0;
      $subatomic['minor'] = $divorced[0];
    }
    $subatomic['currency'] = $currency;
    return $subatomic;
  }

  public function getAmountString(int $amount = 0, string $currency = '', bool $long = true)
  {
    $amount = $amount ?: $this->amount;
    $currency = $currency ?: $this->currency;
    $locale = (auth()->user() && auth()->user()->locale) ? auth()->user()->locale : 'en_US';
    if ($long) {
      return Brick::ofMinor($amount, $currency)
        ->formatTo($locale);
    } else {
      $shrinker = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
      $shrinker->setSymbol(NumberFormatter::CURRENCY_SYMBOL, '');
      $shrinker->setSymbol(NumberFormatter::MONETARY_GROUPING_SEPARATOR_SYMBOL, '');
      $shrinker->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 2);
      return Brick::ofMinor($amount, $currency)
        ->formatWith($shrinker);
    }
  }
}
