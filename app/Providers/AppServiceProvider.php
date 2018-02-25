<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Page;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         // Set morph map for polymorphic relation
         Relation::morphMap([
          'page' => 'App\Page'
         ]);

         // Automatically create slug before add and update specified entities,
         // and auto increment order
         $this->createSlugs(['Page']);

         // Disable additional data[] on resources
         Resource::withoutWrapping();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function createSlugs($modelNames) {
        foreach($modelNames as $modelName)
        {
          $model = app("App\\$modelName");
    
          $model::creating(function($instance) use ($model) {
            $instance->slug = str_slug($instance->title, '-');
            $instance->order = $model::max('order') + 1;
            return true;
          });
    
          $model::updating(function($instance) {
            $instance->slug = str_slug($instance->title, '-');
            return true;
          });
        }
      }
}