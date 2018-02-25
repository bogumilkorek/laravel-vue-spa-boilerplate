<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
      protected $fillable = [
        'title', 'content', 'order'
      ];
    
      public function getRouteKeyName()
      {
        return 'slug';
      }

      public function images()
      {
        return $this->morphMany('App\Image', 'imageable');
      }
}
