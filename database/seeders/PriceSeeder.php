<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    //
    DB::table('prices')->insert([
      [ //friend
        'name' => 'Annual',
        'stripe_price_id' => 'price_1NCOyPGXVdLqaGKl3IjdBqdK',
        'renewal_interval' => 'year',
        'interval_quantity' => 1,
        'amount' => 3000,
        'currency' => 'USD'
      ],
      [ //friend
        'name' => 'Monthly',
        'stripe_price_id' => 'price_1NCOyPGXVdLqaGKlca638n3f',
        'renewal_interval' => 'month',
        'interval_quantity' => 1,
        'amount' => 300,
        'currency' => 'USD'
      ], [ //patron
        'name' => 'Annual',
        'stripe_price_id' => 'price_1Ot9zRGXVdLqaGKlTMeCA7YK',
        'renewal_interval' => 'year',
        'interval_quantity' => 1,
        'amount' => 10000,
        'currency' => 'USD'
      ],
      [ //patron
        'name' => 'Monthly',
        'stripe_price_id' => 'price_1Ot9zRGXVdLqaGKlICZ9sbM0',
        'renewal_interval' => 'month',
        'interval_quantity' => 1,
        'amount' => 1000,
        'currency' => 'USD'
      ],
      [ //member
        'name' => 'Annual',
        'stripe_price_id' => 'price_1OtA1QGXVdLqaGKlsLMBESlJ',
        'renewal_interval' => 'year',
        'interval_quantity' => 1,
        'amount' => 25000,
        'currency' => 'USD'
      ],
      [ //member
        'name' => 'Monthly',
        'stripe_price_id' => 'price_1OtA1QGXVdLqaGKlBnT16y9d',
        'renewal_interval' => 'month',
        'interval_quantity' => 1,
        'amount' => 2500,
        'currency' => 'USD'
      ],
    ]);
  }
}
