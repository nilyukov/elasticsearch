<?php

namespace App\Providers;


use App\Repositories\Articles\ArticlesRepository;
use App\Repositories\Articles\EloquentRepository;
use App\Repositories\ElasticSearch\ElasticsearchRepository;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
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
        $this->app->bind(ArticlesRepository::class, function(){
            // Это полезно, если мы хотим выключить наш кластер
            // или при развертывании поиска на продакшене
            if (! config('services.search.enabled')) {
                return new EloquentRepository();
            }
            return new ElasticsearchRepository(app(Client::class));
        });

        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))
                ->build();
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
