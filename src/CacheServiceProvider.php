<?php

namespace Esemve\VanillaCache;

use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->publishes([
			__DIR__.'/../config/vanilla.php' => config_path('vanilla.php')
		], 'config');
	}

	public function register()
	{
		$this->app->singleton('Vanilla', function(){
			return new Vanilla;
		});
	}
}