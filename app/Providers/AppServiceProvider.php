<?php

namespace App\Providers;

use App\ExchangeRate\Repository\CacheRepository;
use App\ExchangeRate\Repository\DBRepository;
use App\ExchangeRate\Repository\HttpRepository;
use App\ExchangeRate\Storage;
use App\ExchangeRate\StorageInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(StorageInterface::class, function () {
            return new Storage(
                new CacheRepository(),
                new DBRepository(),
                new HttpRepository()
            );
        });
    }

}
