<?php

namespace App\Providers;

use App\Services\VkUsersData;
use Illuminate\Support\ServiceProvider;
use VK\Client\Enums\VKLanguage;
use VK\Client\VKApiClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(VKApiClient::class, function ($app) {
            return new VKApiClient('5.101', VKLanguage::RUSSIAN);
        });
        $this->app->bind(VkUsersData::class, function ($app) {
            return new VkUsersData($app->make(VKApiClient::class), config('vkminiapps.app.token'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
