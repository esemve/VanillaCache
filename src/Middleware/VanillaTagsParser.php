<?php

namespace Esemve\VanillaCache\Middleware;

use Esemve\VanillaCache\Parser;
use Closure;
use Config;

class VanillaTagsParser
{

	protected $engine;

	/**
	 * Replace vanilla tags
	 * @param Request $request
	 * @param Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$response = $next($request);

		if (empty($this->engine))
		{
			$selectedEngine = Config::get('vanilla.engine');
			$engineName = Config::get('vanilla.engines.'.$selectedEngine.'.laravel');
			$this->engine = new $engineName( Config::get('vanilla') );
		}
		
		$parser = new Parser( $this->engine );

		$response->setContent(
			$parser->parse(
				$response->getContent()
				,storage_path('vanilla')
			)
		);

		return $response;
	}
}