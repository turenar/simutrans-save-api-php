<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Reader\Xml;


use Turenar\Simutrans\Context;
use Turenar\Simutrans\Exception\InvalidSaveException;
use Turenar\Simutrans\Exception\MissingModuleException;
use Turenar\Simutrans\Reader\Reader;
use Turenar\Simutrans\Stream\Input\InputStream;
use Turenar\Simutrans\Version;

class XmlReader implements Reader
{
	private $parser;
	private $context;

	public function __construct(InputStream $stream, $chunk_size = Parser::DEFAULT_CHUNK_SIZE)
	{
		MissingModuleException::checkModuleFunction('xml', 'xml_parser_create');

		$this->parser = new Parser($stream, $chunk_size);
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

	public function readUnsignedShort(): int
	{
		return $this->fixUnsigned($this->readShort(), 16);
	}

	public function readInt(): int
	{
		return $this->nextNumber('i32');
	}

	public function readUnsignedInt(): int
	{
		return $this->fixUnsigned($this->readInt(), 32);
	}

	public function readLongLong(): string
	{
		// 64bit width integer doesn't support on 32bit environment.
		// we should return it as string
		return $this->getTagContent('i64');
	}

	public function readId(): int
	{
		return $this->nextNumber('id');
	}

	public function readString(): string
	{
		$token = $this->parser->next();
		$token->assertType(Token::TYPE_STRING);
		return $token->getStr();
	}

	public function readDouble(): float
	{
		$d = $this->getTagContent('d1000');
		return (doubleval($d) + 0.000001) / 1000.0;
	}

	public function openTag(string $tag_name): Reader
	{
		$this->parser->skip(Token::TYPE_OPEN_TAG, $tag_name);
		return $this;
	}

	public function closeTag(string $tag_name): Reader
	{
		$this->parser->skip(Token::TYPE_CLOSE_TAG, $tag_name);
		return $this;
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
			try {
				$version = Version::parse($version_str);
			} catch (\InvalidArgumentException $e) {
				throw new InvalidSaveException($e->getMessage(), $e->getCode(), $e);
			}
			// extended save has <i32>revision</i32> immediately after <Simutrans>
			$extended_revision = $version->isExtendedVersion() ? $this->readInt() : null;
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
