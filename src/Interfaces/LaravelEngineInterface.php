<?php

namespace Esemve\VanillaCache\Interfaces;

interface LaravelEngineinterface extends EngineInterface
{
	public function storeHtml($name,$content);
	public function makeCache($content,$expire);

}