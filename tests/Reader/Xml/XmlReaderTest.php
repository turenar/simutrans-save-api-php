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

	public function testReadByte()
	{
		$stream = new FileInputStream(TestUtils::getSaveDir() . '/dummy/i8.sve');
		$reader = new XmlReader($stream);

		self::assertEquals(0, $reader->readByte());
		self::assertEquals(42, $reader->readByte());
		self::assertEquals(127, $reader->readByte());
		self::assertEquals(-1, $reader->readByte());
		self::assertEquals(-128, $reader->readByte());
	}

	public function testReadUnsignedByte()
	{
		$stream = new FileInputStream(TestUtils::getSaveDir() . '/dummy/i8.sve');
		$reader = new XmlReader($stream);

		self::assertEquals(0, $reader->readUnsignedByte());
		self::assertEquals(42, $reader->readUnsignedByte());
		self::assertEquals(127, $reader->readUnsignedByte());
		self::assertEquals(255, $reader->readUnsignedByte());
		self::assertEquals(128, $reader->readUnsignedByte());
	}
}
