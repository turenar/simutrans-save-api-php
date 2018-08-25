<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Reader\Xml;


use Turenar\Simutrans\Context;
use Turenar\Simutrans\Exception\InvalidSaveException;
use Turenar\Simutrans\Exception\MissingModuleException;
use Turenar\Simutrans\Exception\NotImplementedException;
use Turenar\Simutrans\Reader\Reader;
use Turenar\Simutrans\Stream\Input\InputStream;
use Turenar\Simutrans\Version;

class XmlReader implements Reader
{
	private $parser;
	private $context;

	public function __construct(InputStream $stream)
	{
		MissingModuleException::checkModuleFunction('xml', 'xml_parser_create');

		$this->parser = new Parser($stream);
		$this->context = $this->parseContext();
	}

	public function getContext(): Context
	{
		return $this->context;
	}

	public function readByte(): int
	{
		return $this->nextNumber('i8');
	}

	public function readUnsignedByte(): int
	{
		return $this->fixUnsigned($this->readByte(), 8);
	}

	public function readShort(): int
	{
		return $this->nextNumber('i16');
	}

	public function readInt(): int
	{
		return $this->nextNumber('i32');
	}

	public function readLongLong(): string
	{
		throw new NotImplementedException();
		// TODO: Implement readLongLong() method.
	}

	public function readId(): int
	{
		throw new NotImplementedException();
		// TODO: Implement readInt() method.
	}

	public function readString(): string
	{
		throw new NotImplementedException();
		// TODO: Implement readString() method.
	}

	private function parseContext(): Context
	{
		$simutrans_tag = $this->parser->next();
		$simutrans_tag->assertType(Token::TYPE_OPEN_TAG);
		if ($simutrans_tag->getStr() !== 'Simutrans') {
			throw new InvalidSaveException("missing Simutrans tag");
		}
		$attrs = $simutrans_tag->getAttributes();
		$version_str = $attrs['version'] ?? null;
		$pak = $attrs['pak'] ?? null;
		if ($version_str !== null && $pak !== null) {
			$version = Version::parse($version_str);
			$extended_revision = null;
			if ($version->isExtendedVersion()) {
				$extended_revision = $this->readInt();
			}
			return new Context($version, $pak, $extended_revision);
		} else {
			throw new InvalidSaveException("invalid version/pak signature");
		}
	}


	private function nextNumber(string $tag_name): int
	{
		return intval($this->getTagContent($tag_name));
	}

	private function getTagContent(string $tag_name): string
	{
		$this->parser->skip(Token::TYPE_OPEN_TAG, $tag_name);
		$content_token = $this->parser->next();
		$content_token->assertType(Token::TYPE_STRING);
		$this->parser->skip(Token::TYPE_CLOSE_TAG, $tag_name);
		return $content_token->getStr();
	}

	private function fixUnsigned(int $orig, int $bit_width): int
	{
		if ($orig < 0) {
			return $orig + (1 << $bit_width);
		} else {
			return $orig;
		}
	}
}
