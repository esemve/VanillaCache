# Vanilla Cache (v0.0.1)

Vanilla Cache is a Cache System for Laravel 5.X. It's a response cache system, so you can cache the full html response, and **serve this next time without booting Laravel**. 

You can use this in shared webserver. The request time is max 10-20ms with this cache.

### It's a dev version! Please don't use this in live project!

## Installation

```bash
composer require esemve/vanillacache –dev
```

Add to providers:
```php
Esemve\VanillaCache\CacheServiceProvider::class
```

Add to Facades:
```php
'Vanilla' => Esemve\VanillaCache\Facades\Vanilla::class
```

After:
```bash
composer dump-autoload
php artisan vendor:publish
```

You can create a cache from Laravel now! 

Next step is to set up the CacheServer. It will serve cache BEFORE laravel has loaded. Add to your composer.json "autoload" section beginning:

```json
"files": ["config/vanilla.php","vendor/esemve/vanillacache/vanilla/CacheServer.php"],
```

## Why is the config/vanilla.php is so weird?

Because it is loading BEFORE Laravel, so you can't use any Laravel specified function in this file!


## How to use?

```php
use \Vanilla;
…

  public function index(RoomRepository $roomRepository)
    {
        $rooms = $roomRepository->getAll();

        return Vanilla::Cache(View::make('cinema.index',[
            'rooms' => $rooms
        ]),10);
    }
```

**Vanilla::Cache($view,$sec)**

Saves the view content for the actual url (include GET parameters) for 10 sec. If less than in 10 sec any user open this url, the CacheServer will serve the cache without booting Laravel.



## Engines
VanillaCache contains a file engine default. You can create an engine (for example: mysql, redis etc). 


## How can you create an engine? 
config/vanilla.php

```php
'file' => [
	    'laravel' => 'Esemve\\VanillaCache\\Engines\\FileEngine',
	    'vanilla' => __DIR__.'/../vendor/esemve/vanillacache/vanilla/Engines/FileEngine.php',
	    'config' => [
		'storage_folder' => realpath(__DIR__.'/../storage/').'/vanilla/'
	    ]
	]
```

**„file”:** name of engine

**In „laravel” => 'xxx'** section fills a namespace for your cache engine. The Esemve\VanillaCache\Engines\Engine interface must be implemented. This engine is live IN Laravel

**In „vanilla” => 'xxx'** section can set up the engine file what loading BEFORE laravel. It must implement Esemve\VanillaCache\Interfaces\EngineInterface. It will serve the cache content to CacheServer.

**The „config” section** will send to your engine.


## Dinamic content / blocks (Similar SSI)
All page contains dynamic or repeted elements, for example right side, menus etc. From this elements you can generate a HTML, and you can include it to your cache.

Generate a HTML for cache:

**Vanilla::storeHtml(„rightSide”,view(„_partials.rightSide”))**

After this you can use a **<##html:##>** tag in your page.
```html
<html>
...
<body>
… <!--
<##html:rightSide##>
-->
...
</body>
</html>
```
The CacheServer AND the Laravel will automatice replace this tag (with comment tags if that exists) to the stored html content.
