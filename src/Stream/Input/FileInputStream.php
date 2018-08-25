<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Stream\Input;


class FileInputStream implements InputStream
{
	/**
	 * @var resource
	 */
	private $fp;

	/**
	 * FileInputStream constructor.
	 * @param string $filename
	 */
	public function __construct(string $filename)
	{
		$this->fp = fopen($filename, 'rb');
	}

	public function read(int $len): string
	{
		return fread($this->fp, $len);
	}

	public function hasNext(): bool
	{
		return !feof($this->fp);
	}
}
