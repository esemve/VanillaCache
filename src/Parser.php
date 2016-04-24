<?php
/**
 * Regexp: Thx Sofi!
 */
namespace Esemve\VanillaCache;

use Esemve\VanillaCache\Interfaces\EngineInterface;


class Parser
{
	protected $engine;

	public function __construct(EngineInterface $engine)
	{
		$this->engine = $engine;
	}

	public function parse($content)
	{
		preg_match_all("/(<!--[ \\r\\n]*)*<## *(\\w+) *: *([\\?=&\\.\\/a-zA-Z0-9]+) *##>([ \\r\\n]*-->)*/", trim($content), $matches,PREG_SET_ORDER);
		if (!empty($matches[0])) {
			if (is_array($matches[0])) {
				foreach ($matches AS $key => $match) {
					if ($match[2] == 'html') {

						$insert = $this->engine->getHtml( $match[3] );

						$content = str_replace($match[0], $insert, $content);
					}
				}
			}
			return $content;
		}
		return $content;
	}
}