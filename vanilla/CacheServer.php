<?php

$server = &$_SERVER;

if (!empty($server['REQUEST_METHOD'])) {
	if (($server['REQUEST_METHOD'] == 'GET') && (empty($_COOKIE['nocache']))) {

		$engine = $GLOBALS['vanillaCacheConfig']['engine'];
		$vanillaEngineFile = $GLOBALS['vanillaCacheConfig']['engines'][$engine]['vanilla'];
		$config = [];
		if (!empty($GLOBALS['vanillaCacheConfig']['engines'][$engine]['config'])) {
			$config = $GLOBALS['vanillaCacheConfig']['engines'][$engine]['config'];
		}

		$uri = trim($server['REQUEST_URI']);
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

		require_once __DIR__ . '/../src/Interfaces/EngineInterface.php';
		require_once $vanillaEngineFile;


		try {
			$engine = new Engine($config);

			if ($engine instanceof \Esemve\VanillaCache\Interfaces\EngineInterface) {
				$content = $engine->getVanillaCacheContent($uri);
				if ($content) {
					require_once __DIR__ . '/../src/Parser.php';
					$parser = new \Esemve\VanillaCache\Parser($engine);
					echo $parser->parse($content);
					die();
				}
			}
		} catch (Exception $e) {
		}

		unset($content);
		unset($uri);
		unset($params);
		unset($server);
		unset($vanillaConfig);
		unset($vanillaStorageFolder);
	}
}
else
{
	unset($server);
}
?>

