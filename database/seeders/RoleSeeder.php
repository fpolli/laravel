<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    //
    DB::table('roles')->insert([
      [
        'name' => 'Guest',
        'level' => 0,
        'public' => true,
      ],
      [
        'name' => 'Sponsor',
        'level' => 0,
        'public' => false,
      ],
      [
        'name' => 'Friend',
        'level' => 1,
        'public' => true,
      ],
      [
        'name' => 'Patron',
        'level' => 2,
        'public' => true,
      ],
      [
        'name' => 'Member',
        'level' => 3,
        'public' => true,
      ],
      [
        'name' => 'Shopkeeper',
        'level' => 3,
        'public' => false,
      ],
      [
        'name' => 'Creator',
        'level' => 3,
        'public' => false,
      ],
      [
        'name' => 'Contributor',
        'level' => 4,
        'public' => false,
      ],
      [
        'name' => 'Author',
        'level' => 5,
        'public' => false,
      ],
      [
        'name' => 'Editor',
        'level' => 6,
        'public' => false,
      ],
      [
        'name' => 'Moderator',
        'level' => 6,
        'public' => false,
      ],
      [
        'name' => 'Administrator',
        'level' => 7,
        'public' => false,
      ],
      [
        'name' => 'Guiding Member',
        'level' => 8,
        'public' => false,
      ],
      [
        'name' => 'Fred',
        'level' => 10,
        'public' => false,
      ],

    ]);
  }
}
