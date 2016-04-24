<?php
/*
  |--------------------------------------------------------------------------
  | Vanilla cache config
  |--------------------------------------------------------------------------
  |
  | Please do not use any Laravel specified functions in this config file, because
  | it will loading before Laravel loaded!
  */

$GLOBALS['vanillaCacheConfig'] = [

	/*
	  |--------------------------------------------------------------------------
	  | Enable vanilla cache
	  |--------------------------------------------------------------------------
	  |
	  | If this is false the vanilla cache is inactive.
	  | Be careful, because when it's false the vanilla tags will be sent to output!
	  */

	'enabled' => true,

	/*
	  |--------------------------------------------------------------------------
	  | Storage type
	  |--------------------------------------------------------------------------
	  |
	  | file: Store cache in storage/
	  | mysql: Store to mysql
	  */
	'engine' => 'file',

	/*
	 |--------------------------------------------------------------------------
	 | Clear expired cache lottery
	 |--------------------------------------------------------------------------
	 |
	 | 0: disabled
	 | If it's more then 0 the Vanilla cache for all request will generate a random number
	 | from 0 to $lottery. If this is number = $lottery, the cache vill remove all expired
	 | element for cache
	 */

	'lottery' => 100,

	/*
	 |--------------------------------------------------------------------------
	 | Storage engines
	 |--------------------------------------------------------------------------
	 |
	 |  'type' => [
	 |		'laravel' => 'Class\For\Laravel',
	 |		'vanilla' => '/srv/www/before_laravel_loaded_engine_parser.php',
	 |		'config => [ ... ]
	 | ]
	 */

	'engines' => [
		'file' => [
			'laravel' => 'Esemve\\VanillaCache\\Engines\\FileEngine',
			'vanilla' => __DIR__.'/../packages/esemve/vanillacache/vanilla/Engines/FileEngine.php',
			'config' => [
				'storage_folder' => realpath(__DIR__.'/../storage/').'/vanilla/'
			]
		]
	]
];
return $GLOBALS['vanillaCacheConfig'];