<?php

namespace App\Models\Content;


class Circle
{
  public int $id;
  public string $name;
  public int $level;
  public string $role;

  public function __construct(array $props)
  {
    $this->id = $props['id'];
    $this->name = $props['name'];
    $this->level = $props['level'];
    $this->role = $props['role'];
  }
}
