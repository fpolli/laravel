<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    //
    DB::table('categories')->insert([
      [
        'name' => 'Love and Sex',
        'slug' => Str::slug('Love and Sex'),
        'blurb' => '',
        'genres' => '',
        'site' => 'moms'
      ],
      [
        'name' => 'Knowledge and Ignorance',
        'slug' => Str::slug('Knowledge and Ignorance'),
        'blurb' => '',
        'genres' => '',
        'site' => 'moms'
      ],
      [
        'name' => 'Class and Race',
        'slug' => Str::slug('Class and Race'),
        'blurb' => '',
        'genres' => '',
        'site' => 'moms'
      ],
      [
        'name' => 'Emperors and Armies',
        'slug' => Str::slug('Emperors and Armies'),
        'blurb' => '',
        'genres' => '',
        'site' => 'moms'
      ],
      [
        'name' => 'Earth and Sky',
        'slug' => Str::slug('Earth and Sky'),
        'blurb' => '',
        'genres' => '',
        'site' => 'moms'
      ],
      [
        'name' => 'God and Man',
        'slug' => Str::slug('God and Man'),
        'blurb' => '',
        'genres' => '',
        'site' => 'moms'
      ],
      [
        'name' => 'Culture and Meaning',
        'slug' => Str::slug('Culture and Meaning'),
        'blurb' => '',
        'genres' => '',
        'site' => 'moms'
      ],
      [
        'name' => 'Ideas and Actions',
        'slug' => Str::slug('Ideas and Actions'),
        'blurb' => '',
        'genres' => '',
        'site' => 'moms'
      ],
      [
        'name' => 'Love and Sex',
        'slug' => Str::slug('Love and Sex'),
        'blurb' => '',
        'genres' => '',
        'site' => 'cowp'
      ],
      [
        'name' => 'Knowledge and Ignorance',
        'slug' => Str::slug('Knowledge and Ignorance'),
        'blurb' => '',
        'genres' => '',
        'site' => 'cowp'
      ],
      [
        'name' => 'Class and Race',
        'slug' => Str::slug('Class and Race'),
        'blurb' => '',
        'genres' => '',
        'site' => 'cowp'
      ],
      [
        'name' => 'Emperors and Armies',
        'slug' => Str::slug('Emperors and Armies'),
        'blurb' => '',
        'genres' => '',
        'site' => 'cowp'
      ],
      [
        'name' => 'Earth and Sky',
        'slug' => Str::slug('Earth and Sky'),
        'blurb' => '',
        'genres' => '',
        'site' => 'cowp'
      ],
      [
        'name' => 'God and Man',
        'slug' => Str::slug('God and Man'),
        'blurb' => '',
        'genres' => '',
        'site' => 'cowp'
      ],
      [
        'name' => 'Culture and Meaning',
        'slug' => Str::slug('Culture and Meaning'),
        'blurb' => '',
        'genres' => '',
        'site' => 'cowp'
      ],
      [
        'name' => 'Ideas and Actions',
        'slug' => Str::slug('Ideas and Actions'),
        'blurb' => '',
        'genres' => '',
        'site' => 'cowp'
      ],
    ]);
  }
}
