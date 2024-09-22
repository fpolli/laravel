<?php

namespace App\Models\Content;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use App\FredOS\Services\Content\AudioService;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Audio extends Work
{
  //use HasFactory;


  //attributes in parent
  /**
   * The model's default values for attributes.
   *
   * @var array
   */
  protected $attributes = [
    'mime' => 'audio',
    //    'body' => []
  ];

  //relationships in parent


  //Accessors/Mutators?

  //casts
  //body
  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'body' => 'array',
      'nonfiction' => 'boolean',
      'adult' => 'boolean',
      'published_at' => 'datetime',
      'updated_at' => 'datetime',
      'credits' => 'array',
      'dna' => 'array',
      'triggers' => 'array'
    ];
  }

  //Special methods
  public function play()
  {
    $player = new AudioService;
    return $player->playAudio($this->body, $this->mime);
  }

  public function getTrailer()
  {
    //check if file exists
    if (isset($this->body['trailer']) && $this->body['trailer']) {
      $path = audio_path($this->body['trailer']);
      if (file_exists($path)) {
        $url = audio_url($this->body['trailer']);
        return $url;
      }
    }

    return '';
  }

  public function getPoster()
  {
    //check if file exists
    if (isset($this->body['poster']) && $this->body['poster']) {
      $path = image_path($this->body['poster']);
      if (file_exists($path)) {
        $url = image_url($this->body['poster']);
        return $url;
      }
    }

    return '';
  }
}
