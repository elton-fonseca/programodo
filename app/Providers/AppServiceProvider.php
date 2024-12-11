<?php

namespace App\Providers;

use App\Services\Payshop\PayshopClientFactory;
use App\Services\Payshop\PayshopClientFactoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PayshopClientFactoryInterface::class, PayshopClientFactory::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Http::macro('paybylink', function () {
            $url = config('services.paybylink.url_base');

            return Http::withHeaders([
                'Authorization' => 'AdminTokenTest',
                'X-IBM-Client-Id' => config('services.paybylink.client_id'),
                'X-IBM-Client-Secret' => config('services.paybylink.client_secret'),
                'ApiPublisher-client-id' => 'admin',
            ])->baseUrl($url);
        });
    }
}
