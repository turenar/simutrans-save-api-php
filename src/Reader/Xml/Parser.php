<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Reader\Xml;


use Turenar\Simutrans\Exception\InvalidSaveException;
use Turenar\Simutrans\Exception\MissingModuleException;
use Turenar\Simutrans\Stream\Input\InputStream;

class Parser
{
	public const DEFAULT_CHUNK_SIZE = 4096;

	/** @var InputStream */
	protected $stream;
	/** @var resource */
	protected $parser;
	/** @var Token[] */
	protected $queue;
	/** @var int */
	protected $chunk_size;

	public function __construct(InputStream $stream, int $chunk_size = self::DEFAULT_CHUNK_SIZE)
	{
		MissingModuleException::checkModuleFunction('xml', 'xml_parser_create');

		$this->parser = xml_parser_create('UTF-8');
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
		xml_set_element_handler($this->parser, [$this, 'onStartElement'], [$this, 'onEndElement']);
		xml_set_character_data_handler($this->parser, [$this, 'onCharacterData']);

		$this->stream = $stream;
		$this->queue = [];
		$this->chunk_size = $chunk_size;
	}

	public function next(): ?Token
	{
		$this->ensureNext();
		return array_shift($this->queue);
	}

	public function skip(int $tokenType, string $data = null): Parser
	{
		$token = $this->next();
		if ($token === null) {
			throw new InvalidSaveException("unexpected eof");
		}
		if ($token->getType() !== $tokenType) {
			throw new InvalidSaveException(
				"invalid token: expected type=$tokenType but got " . Token::represent($tokenType, $data));
		}
		if ($data !== null && $data !== $token->getStr()) {
			throw new InvalidSaveException(sprintf("invalid token: expected %s but actual %s", $token->asString(),
				Token::represent($tokenType, $data)));
		}
		return $this;
	}


	protected function ensureNext()
	{
		while (empty($this->queue) && $this->stream->hasNext()) {
			$data = $this->stream->read($this->chunk_size);
			if (!xml_parse($this->parser, $data, !$this->stream->hasNext())) {
				throw new InvalidSaveException(sprintf("xml parse failed: %s [%s,%s]",
					xml_error_string(xml_get_error_code($this->parser)), xml_get_current_line_number($this->parser),
					xml_get_current_column_number($this->parser)));
			}
		}
	}

	protected function onCharacterData($parser, string $text)
	{
		// TODO this is safe......?
		if (!preg_match('@^\n *$@', $text)) {
			$this->queue[] = new Token(Token::TYPE_STRING, $text);
		}
	}

	protected function onStartElement($parser, string $name, array $attribs)
	{
		$this->queue[] = new Token(Token::TYPE_OPEN_TAG, $name, $attribs);
	}

	protected function onEndElement($parser, string $name)
	{
		$this->queue[] = new Token(Token::TYPE_CLOSE_TAG, $name);
	}

}
