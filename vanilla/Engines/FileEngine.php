<?php

use Esemve\VanillaCache\Interfaces\EngineInterface;

class Engine implements EngineInterface
{
	protected $config;
	protected $storageFolder;
	protected $cacheFolder;
	protected $htmlFolder;

	public function __construct($config)
	{
		$this->config = $config;
		$this->storageFolder = rtrim($this->config['storage_folder'],'/');
		$this->cacheFolder = $this->storageFolder.'/cache';
		$this->htmlFolder = $this->storageFolder.'/html';
	}

	/**
	 * Get the generated html file
	 * @param string $fileName Without .html
	 * @return string
	 */
	function getHtml($fileName)
	{
		$fileName = $fileName.'.html';
		if (file_exists($this->htmlFolder. '/' . $fileName)) {
			$insert = file_get_contents($this->htmlFolder. '/' . $fileName);
		} else {
			$insert = '';
		}

		return $insert;
	}

	/**
	 * Get cached value
	 * @param array $uriParts
	 * @return string|null
	 */
	public function getVanillaCacheContent($uriParts)
	{
		return $this->getRecursiveCacheContent($this->cacheFolder,$this->cacheFolder,$uriParts,0);
	}

	/**
	 * Get recursive content
	 * @param string $vanillaStorageFolder
	 * @param string $path
	 * @param array $uriParts
	 * @param int $element
	 * @return null|string
	 */
	protected function getRecursiveCacheContent($vanillaStorageFolder,$path,$uriParts,$element)
	{
		if (file_exists($path.'/'.$uriParts[$element]))
		{
			$path = realpath($path.'/'.$uriParts[$element]);

			if (count($uriParts)-1>$element)
			{
				return $this->getVanillaCacheContent($vanillaStorageFolder,$path,$uriParts,$element+1);
			}
			if (file_exists($path.'/content'))
			{
				if (file_exists($path.'/expire'))
				{
					$expire = (int)file_get_contents($path.'/expire');
					if ($expire>time())
					{
						return file_get_contents($path.'/content');
					}
					else
					{
						unlink($path.'/content');
						unlink($path.'/expire');
						$this->removeVanillaCacheContent($vanillaStorageFolder,$uriParts,$element);
						return null;
					}
				}
			}
			return null;
		}
		else
		{
			return null;
		}
	}

	/**
	 * If the content is expired remove the folder
	 * @param string $vanillaStorageFolder
	 * @param array $uriParts
	 */
	protected function removeVanillaCacheContent($vanillaStorageFolder,$uriParts)
	{
		$testParts = implode('/',$uriParts);
		if ($this->isVanillaCacheFolderEmpty($vanillaStorageFolder.'/'.$testParts))
		{
			rmdir($vanillaStorageFolder.'/'.$testParts);
			unset($uriParts[count($uriParts)-1]);
			if (count($uriParts)>0)
			{
				$this->removeVanillaCacheContent($vanillaStorageFolder, $uriParts);
			}
		}
	}

	/**
	 * Empty folder?
	 * @param string $dir
	 * @return bool|null
	 */
	protected function isVanillaCacheFolderEmpty($dir) {
		if (!is_readable($dir)) return NULL;
		$handle = opendir($dir);
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") {
				return FALSE;
			}
		}
		return TRUE;
	}
}