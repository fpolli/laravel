<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      SiteSeeder::class,
      RoleSeeder::class,
      PriceSeeder::class,
      ProductSeeder::class,
      CategorySeeder::class,
      FredopediaSeeder::class
    ]);
  }
}
