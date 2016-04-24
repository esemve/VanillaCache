<?php

use Esemve\VanillaCache\Parser;

class ParserTest extends TestCase
{
	public function setUp()
	{
		require_once __DIR__.'/Dummy/EngineDummy.php';
	}

	public function tearDown()
	{
	}

	protected function getEngine()
	{
		return new \Esemve\VanillaCache\Engines\EngineDummy(['config'=>'xxx']);;
	}

	public function testParserDontModifiedTheAnswer()
	{
		$parser = new Parser($this->getEngine());
		$testTexts = [
			'árvíztűrő tükörfúrógép',
			'',
			'info',
			'dsaéld,aspőkjő324j9öő3 mjrfpröőjefőúü34júüö1ki3ú4ürfökw',
			'<br>info</b>dasd</hello>',
			'It\'s important <b>info</b>',
			'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vel ligula sit amet arcu feugiat sollicitudin et sed ipsum. Ut a lobortis purus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Donec faucibus metus nec est efficitur, a vehicula sem lacinia. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In vulputate rutrum sem, non tincidunt nibh hendrerit id. Curabitur sit amet turpis nec orci lobortis commodo at vel mauris. Donec condimentum, nisi et tincidunt semper, ligula ligula congue elit, non ornare tortor odio ut leo. Aenean volutpat eu lorem vel porta. Vivamus tempor urna non vehicula venenatis. Vestibulum turpis nibh, vulputate ut tellus non, consectetur consectetur quam. Sed lacinia felis eget metus vulputate tincidunt. In convallis neque risus, a fermentum sem vehicula eget. Vivamus dapibus enim dignissim nibh lobortis tincidunt.',
			'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'."\n".' Cras vel ligula sit amet arcu feugiat sollicitudin et sed ipsum. Ut a lobortis purus. Interdum et malesuada fames ac ante ipsum primis in faucibus.'."\n".'Donec faucibus metus nec est efficitur, a vehicula sem lacinia. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In vulputate rutrum sem, non tincidunt nibh hendrerit id. Curabitur sit amet turpis nec orci lobortis commodo at vel mauris. Donec condimentum, nisi et tincidunt semper, ligula ligula congue elit, non ornare tortor odio ut leo. Aenean volutpat eu lorem vel porta. Vivamus tempor urna non vehicula venenatis. Vestibulum turpis nibh, vulputate ut tellus non, consectetur consectetur quam. Sed lacinia felis eget metus vulputate tincidunt. In convallis neque risus, a fermentum sem vehicula eget. Vivamus dapibus enim dignissim nibh lobortis tincidunt.',
		];

		foreach ($testTexts AS $value)
		{
			$this->assertEquals($value,$parser->parse($value));
		}
	}

	public function testHtmlReplaced()
	{
		$engine = $this->getEngine();
		$engine->storeHtml('info','INFO');
		$engine->storeHtml('XXX','XXX');
		$engine->storeHtml('hello','HELLO');

		$parser = new Parser($engine);
		$testTexts = [
			'<##html:info##><b>InfoHello</b>TestText' => 'INFO<b>InfoHello</b>TestText',
			'<##html:info##><b><##html:XXX##></b>TestText' => 'INFO<b>XXX</b>TestText',
			'<##html:info##><b><##html:XXX##></b><##html:hello##>' => 'INFO<b>XXX</b>HELLO',
			'<## html :info##><b><##   html   :   XXX   ##></b><##	html:	hello##>' => 'INFO<b>XXX</b>HELLO',
		];

		foreach ($testTexts AS $value)
		{
			$this->assertEquals($value,$parser->parse($value));
		}
	}

	public function testHtmlCommentRemoved()
	{
		$engine = $this->getEngine();
		$engine->storeHtml('info','INFO');
		$engine->storeHtml('XXX','XXX');
		$engine->storeHtml('hello','HELLO');

		$parser = new Parser($engine);
		$testTexts = [
			'<!--<##html:info##>--><b>InfoHello</b>TestText' => 'INFO<b>InfoHello</b>TestText',
			'<!--<##html:info##>--><b><##html:XXX##></b>TestText' => 'INFO<b>XXX</b>TestText',
			'<!--                  <##html:info##>           --><b><##html:XXX##></b><##html:hello##>' => 'INFO<b>XXX</b>HELLO',
			'<## html :info##><b><!--<##   html   :   XXX   ##>    --></b><##	html:	hello##>' => 'INFO<b>XXX</b>HELLO',
		];

		foreach ($testTexts AS $value)
		{
			$this->assertEquals($value,$parser->parse($value));
		}
	}

}