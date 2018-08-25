<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Reader\Xml;


use PHPUnit\Framework\TestCase;
use Turenar\Simutrans\Stream\Input\FileInputStream;
use Turenar\Simutrans\TestUtils;

class XmlReaderTest extends TestCase
{
	public function testGetStandardContext()
	{
		$stream = new FileInputStream(TestUtils::getSaveDir() . '/standard/xml.sve');
		$reader = new XmlReader($stream);

		$context = $reader->getContext();
		self::assertEquals("0.120.6", $context->getVersion()->asString());
		self::assertEquals("pak64", $context->getPakName());
		self::assertEquals(null, $context->getExtendedRevision());
	}

	public function testGetExtendedContext()
	{
		$stream = new FileInputStream(TestUtils::getSaveDir() . '/extended/xml.sve');
		$reader = new XmlReader($stream);

		$context = $reader->getContext();
		self::assertEquals("0.120.4.14", $context->getVersion()->asString());
		self::assertEquals("pak128.britain-ex-nightly", $context->getPakName());
		self::assertEquals(0, $context->getExtendedRevision());
	}
}
