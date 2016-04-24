<?php

namespace Esemve\VanillaCache\Engines;

use Esemve\VanillaCache\Interfaces\LaravelEngineInterface;

class EngineDummy extends Engine implements LaravelEngineInterface
{
	protected $htmls = [];

	public function __construct($config)
	{
	}

	/**
	 * Save the cache to file
	 * @param string $content
	 * @param int $expire Sec
	 */
	public function makeCache($content,$expire)
	{
	}

	/**
	 * Save content to file
	 * @param string $name
	 * @param string $content
	 */
	public function storeHtml($name,$content)
	{
		$this->htmls[$name] = $content;
	}

	/**
	 * Get a saved html
	 * @param string $name html file name (without .thml)
	 * @return string
	 */
	public function getHtml($name)
	{
		return $this->htmls[$name];
	}


}