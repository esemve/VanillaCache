<?php

namespace Esemve\VanillaCache\Engines;

use Esemve\VanillaCache\Interfaces\LaravelEngineInterface;
use \File;


class FileEngine extends Engine implements LaravelEngineInterface
{

	protected $storageFolder = '';
	protected $htmlFolder = '';
	protected $cacheFolder = '';

	public function __construct($config)
	{
		$this->storageFolder = rtrim(rtrim($config['engines']['file']['config']['storage_folder'],'/'),'\\');
		$this->htmlFolder = $this->storageFolder.'/html';
		$this->cacheFolder = $this->storageFolder.'/cache';

		if (!file_exists($this->storageFolder)) {
			File::makeDirectory($this->storageFolder);
		}
		if (!file_exists($this->htmlFolder)) {
			File::makeDirectory($this->htmlFolder);
			file_put_contents($this->htmlFolder('.gitignore'),'*'."\r\n"."!.gitignore");
		}
		if (!file_exists($this->cacheFolder)) {
			File::makeDirectory($this->cacheFolder);
			file_put_contents($this->cacheFolder('.gitignore'),'*'."\r\n"."!.gitignore");
		}
	}

	/**
	 * Save the cache to file
	 * @param string $content
	 * @param int $expire Sec
	 */
	public function makeCache($content,$expire)
	{
		$sums = $this->getSums();
		$sumList = '';

		foreach ($sums AS $sum)
		{
			$sumList .= '/'.$sum;
			$path = $this->cacheFolder($sumList);

			if (!is_dir($path))
			{
				@mkdir($path);
			}
		}

		@file_put_contents($path.'/content',$content);
		@file_put_contents($path.'/expire',time()+$expire);
	}

	/**
	 * Save content to file
	 * @param string $name
	 * @param string $content
	 */
	public function storeHtml($name,$content)
	{
		$randomFileName = $this->htmlFolder(str_random(50));
		file_put_contents($randomFileName,$content);
		$finishedName = $this->htmlFolder($name.'.html');
		if (file_exists($finishedName))
		{
			unlink($finishedName);
		}
		rename($randomFileName,$finishedName);
	}

	/**
	 * Get a saved html
	 * @param string $name html file name (without .thml)
	 * @return string
	 */
	public function getHtml($name)
	{
		$storagedHtmlFile = $this->htmlFolder($name.'.html');
		if (file_exists($storagedHtmlFile)) {
			return file_get_contents($storagedHtmlFile);
		}
	}

	/**
	 * Get the cache folder path
	 * @param string $params path
	 * @return string
	 */
	protected function cacheFolder($params)
	{
		return $this->cacheFolder.'/'.ltrim($params,'/');
	}

	/**
	 * Get the cached html -s path
	 * @param string $params
	 * @return string
	 */
	protected function htmlFolder($params)
	{
		return $this->htmlFolder.'/'.ltrim($params,'/');
	}

	/**
	 * Get storage folder path
	 * @param string $params
	 * @return string
	 */
	protected function storageFolder($params)
	{
		return $this->storageFolder.'/'.ltrim($params,'/');
	}

}