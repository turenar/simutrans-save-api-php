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

	public function testReadShort()
	{
		$stream = new FileInputStream(TestUtils::getSaveDir() . '/dummy/i16.sve');
		$reader = new XmlReader($stream);

		self::assertEquals(0, $reader->readShort());
		self::assertEquals(256, $reader->readShort());
		self::assertEquals(32767, $reader->readShort());
		self::assertEquals(-1, $reader->readShort());
		self::assertEquals(-256, $reader->readShort());
		self::assertEquals(-32768, $reader->readShort());
	}

	public function testReadUnsignedShort()
	{
		$stream = new FileInputStream(TestUtils::getSaveDir() . '/dummy/i16.sve');
		$reader = new XmlReader($stream);

		self::assertEquals(0, $reader->readUnsignedShort());
		self::assertEquals(256, $reader->readUnsignedShort());
		self::assertEquals(32767, $reader->readUnsignedShort());
		self::assertEquals(65535, $reader->readUnsignedShort());
		self::assertEquals(65280, $reader->readUnsignedShort());
		self::assertEquals(32768, $reader->readUnsignedShort());
	}

	public function testReadInt()
	{
		$stream = new FileInputStream(TestUtils::getSaveDir() . '/dummy/i32.sve');
		$reader = new XmlReader($stream);

		self::assertEquals(0, $reader->readInt());
		self::assertEquals(123456789, $reader->readInt());
		self::assertEquals(2147483647, $reader->readInt());
		self::assertEquals(-1, $reader->readInt());
		self::assertEquals(-123456789, $reader->readInt());
		self::assertEquals(-2147483648, $reader->readInt());
	}

	public function testReadUnsignedInt()
	{
		$stream = new FileInputStream(TestUtils::getSaveDir() . '/dummy/i32.sve');
		$reader = new XmlReader($stream);

		self::assertEquals(0, $reader->readUnsignedInt());
		self::assertEquals(123456789, $reader->readUnsignedInt());
		self::assertEquals(2147483647, $reader->readUnsignedInt());
		self::assertEquals(4294967295, $reader->readUnsignedInt());
		self::assertEquals(4171510507, $reader->readUnsignedInt());
		self::assertEquals(2147483648, $reader->readUnsignedInt());
	}
}
