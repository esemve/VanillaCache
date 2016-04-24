<?php

namespace Esemve\VanillaCache;

use \Config;
use \File;
use \Exception;
use Esemve\VanillaCache\Engines\FileEngine;

class Vanilla
{

	protected $engine;

	public function __construct()
	{
		$selectedEngine = Config::get('vanilla.engine');
		$engineName = Config::get('vanilla.engines.'.$selectedEngine.'.laravel');
		$this->engine = new $engineName( Config::get('vanilla') );
	}

	public function cache($content,$expireSec = 600)
	{
		if (is_object($content))
		{
			$content = $content->render();
		}

		$this->engine->makeCache($content,$expireSec);
		return $content;
	}

	public function storeHtml($name,$content)
	{
		$name = str_replace('/','',$name);

		$this->engine->html($name,$content);
	}

	public function getHtml($name)
	{
		$content = $this->engine->getHtml($name);
		if ($content)
		{
			return $content;
		}

		return '';
	}


}