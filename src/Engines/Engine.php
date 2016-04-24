<?php

namespace Esemve\VanillaCache\Engines;

class Engine
{

	/**
	 * Get url sum
	 * @return array|string
	 */
	public function getSums()
	{
		$uri = trim($_SERVER['REQUEST_URI']);
		$uri = trim($uri, '\/\&');

		$uri = explode('?', $uri, 2);
		if (!empty($uri[1])) {
			$params = md5($uri[1]);
		} else {
			$params = md5('');
		}
		$uri = array_filter(explode('/', $uri[0]));

		foreach ($uri AS $key => $value) {
			$uri[$key] = md5($value);
		}

		$uri[] = $params;
		unset($params);

		return $uri;
	}

}