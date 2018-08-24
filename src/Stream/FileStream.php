<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Stream;


class FileStream implements Stream
{
	/**
	 * @var resource
	 */
	private $fp;

	/**
	 * FileStream constructor.
	 * @param resource $fp
	 */
	public function __construct($fp)
	{
		$this->fp = $fp;
	}

	public function read(int $len): string
	{
		return fread($this->fp, $len);
	}

	public function write(string $data): void
	{
		fwrite($this->fp, $data);
	}

	public function hasNext(): bool
	{
		return !feof($this->fp);
	}
}
