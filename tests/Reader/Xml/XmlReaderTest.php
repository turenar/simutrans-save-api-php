<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Reader\Xml;


use PHPUnit\Framework\TestCase;
use Turenar\Simutrans\Stream\Input\FileInputStream;
use Turenar\Simutrans\TestUtils;

class XmlReaderTest extends TestCase
{
	public function testGetContext()
	{
		$fp = fopen(TestUtils::getSaveDir() . '/xml.sve', 'r');
		$stream = new FileInputStream($fp);
		$reader = new XmlReader($stream);

		$context = $reader->getContext();
		self::assertEquals("0.120.4.14", $context->getVersion()->asString());
		self::assertEquals("pak128.britain-ex-nightly", $context->getPakName());
		self::assertEquals(0, $context->getExtendedRevision());
	}
}
