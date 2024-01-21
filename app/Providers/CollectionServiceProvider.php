<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class CollectionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Collection::macro('toCsv', function () {
            $path = storage_path('app/public/temp');
            if (!File::exists($path)) {
                mkdir($path);
            }
            dd($this);
            
            // return $this->map(function (string $value) use ($locale) {
                
            // });
        });
    }
}
