<?php
/**
 * Created by 迹忆.
 * User: onmpw
 * HomePage: https://www.onmpw.com
 * Date: 2018/12/7
 * Time: 14:10
 */

namespace Onmpw\JiyiRequest;

use Illuminate\Support\ServiceProvider;
use Onmpw\JiyiRequest\Lib\RequestContract;
use Onmpw\JiyiRequest\Lib\CurlRequest;

class JiyiRequestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RequestContract::class,function ($app){
            return $app->make(CurlRequest::class);
        });
    }
}