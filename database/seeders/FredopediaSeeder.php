<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FredopediaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    //
    DB::table('fredopedia')->insert([
      [
        'term' => 'why-join',
        'category' => 'membership',
        'definition' => 'Because it rocks',
      ],
      [
        'term' => 'levels',
        'category' => 'membership',
        'definition' =>
        'We have three levels of membership that you can subscribe to, but once you have joined at the Member level, you have just peeled the paper off the onion.',
      ],
      [
        'term' => 'community',
        'category' => 'membership',
        'definition' =>
        'When you subscribe as a Member, you are doing more than subscribing to an online publishing service, you are joining a community of people with a mission statement: to help each other thrive in this crazy, scary world.',
      ],
      [
        'term' => 'seeds-marketplace',
        'category' => 'membership',
        'definition' =>
        'At the core of our community is a Marketplace where Members can run businesses with the support of the whole community.',
      ],
      [
        'term' => 'terms-of-service',
        'category' => 'policies',
        'definition' => "don't be a dick",
      ],
      [
        'term' => 'privacy-policy',
        'category' => 'policies',
        'definition' =>
        'We respect your privacy and do not sell or share in any way any of your data outside of the Seeds Community.',
      ],
      [
        'term' => 'cookie-policy',
        'category' => 'policies',
        'definition' => 'We use a few cookies',
      ]
    ]);
  }
}
