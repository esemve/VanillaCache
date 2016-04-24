<?php

use Esemve\VanillaCache\Engines\Engine;

class EngineTest extends TestCase
{

	protected $class;
	protected $sums = [
		'/' => ["d41d8cd98f00b204e9800998ecf8427e"],
		'/dasd' => ["196b0f14eba66e10fba74dbf9e99c22f","d41d8cd98f00b204e9800998ecf8427e"],
		'/dasd/index.php' => ["196b0f14eba66e10fba74dbf9e99c22f","828e0013b8f3bc1bb22b4f57172b019d","d41d8cd98f00b204e9800998ecf8427e"],
		'/tükörfúrógép/index.php?test=1' => ["d16cce1f2015c8a78e2591c5443be344","828e0013b8f3bc1bb22b4f57172b019d","ab43fa841d53350d40435881ce57e880"],
		'/core/login?test=1&page=55' => ["a74ad8dfacd4f985eb3977517615ce25","d56b699830e77ba53855679cb1d252da","f16bda3185e25d2f95e45ce49e7e820a"],
		'/admin/login/?test=1&page=55&nocache=true' => ["21232f297a57a5a743894a0e4a801fc3","d56b699830e77ba53855679cb1d252da","8ce7609cbbf47e6bb22976e989e127ce"],
	];

	public function setUp()
	{
		$this->class = new Engine();
	}

	public function tearDown()
	{
		unset($_SERVER['REQUEST_URI']);
	}

	public function testEngineClassHasGetSumMethod()
	{
		$this->assertTrue(method_exists($this->class,'getSums'));
	}

	public function testEngineClassGetSumHasCorrectAnswer()
	{
		foreach ($this->sums AS $key => $value)
		{
			$_SERVER['REQUEST_URI'] = $key;
			$this->assertEquals($value,$this->class->getSums());
		}
	}
}