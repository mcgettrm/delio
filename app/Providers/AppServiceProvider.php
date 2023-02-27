<?php

namespace App\Providers;

use App\ProfitAndLoss\FinnhubAdapter;
use App\ProfitAndLoss\ProfitAndLossFacade;
use App\ProfitAndLoss\GrossProfitAndLossStrategy;
use App\ProfitAndLoss\ProfitAndLossFactory;
use App\ProfitAndLoss\StockDataReadingRepository;
use Illuminate\Support\Facades\Schema;
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
        $this->app->bind(ProfitAndLossFacade::class,function($app){
            return new ProfitAndLossFacade(
                $app->make(FinnhubAdapter::class),
                $app->make(StockDataReadingRepository::class),
                $app->make(GrossProfitAndLossStrategy::class),
                $app->make(ProfitAndLossFactory::class)
            );
        });

        $this->app->bind(FinnhubAdapter::class,function($app){
            $finnhubToken = env('FINNHUB_API_KEY');
            return new FinnhubAdapter(
                $finnhubToken,
                $app->make(ProfitAndLossFactory::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
