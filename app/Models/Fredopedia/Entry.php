<?php

namespace App\Models\Fredopedia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
  use HasFactory;

  protected $table = 'fredopedia';

  //Attributes
  //id
  //topic
  //name
  //definition
  protected $guarded = ['id'];

  //Relationships are on the inheriting models
}
