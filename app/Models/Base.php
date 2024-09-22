<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
  //not needed because already saving the stiClassField with
  //the distinguishing value
  //  public function __construct($attributes = array())
  //  {
  //    parent::__construct($attributes);
  //    if ($this->useSti()) {
  //      $this->setAttribute($this->stiClassField, get_class($this));
  //    }
  //  }

  private function useSti()
  {
    return ($this->stiClassField && $this->stiNamespace && $this->stiBaseClass);
  }

  public function newQuery()
  {
    $builder = parent::newQuery();
    // If I am using STI, and I am not the base class,
    // then filter on the class name.
    if ($this->useSti()) {
      $baseclass = "{$this->stiNamespace}\\{$this->stiBaseClass}";
      if (get_class(new $baseclass) !== get_class($this)) {
        $builder->where($this->stiClassField, "=", get_class($this));
      }
    }
    return $builder;
  }

  //if child class is in a subfolder, needs a childNamespace property
  public function newFromBuilder($attributes = [], $connection = null)
  {
    $attributes = (array) $attributes;
    if ($this->useSti() && $attributes[$this->stiClassField]) {
      $namespace = $this->childNamespace ?? $this->stiNamespace;
      $class = $namespace . '\\' . ucfirst($attributes[$this->stiClassField]);
      $instance = new $class;
      $instance->exists = true;
      $instance->setRawAttributes((array) $attributes, true);

      $instance->setConnection($connection ?: $this->getConnectionName());

      $instance->setTable($this->getTable());

      $instance->mergeCasts($this->casts);


      $instance->fireModelEvent('retrieved', false);

      return $instance;
    } else {
      return parent::newFromBuilder($attributes, $connection);
    }
  }
}
