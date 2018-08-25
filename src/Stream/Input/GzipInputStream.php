<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Stream\Input;


use Turenar\Simutrans\Exception\MissingModuleException;

class GzipInputStream implements InputStream
{
	/**
	 * @var resource
	 */
	private $fp;

	/**
	 * GzipStream constructor.
	 * @param string $filename
	 */
	public function __construct(string $filename)
	{
		MissingModuleException::checkModuleFunction('zlib', 'gzopen');
		$this->fp = gzopen($filename, 'r');
	}


	public function read(int $len): string
	{
		return gzread($this->fp, $len);
	}


	public function hasNext(): bool
	{
		return !gzeof($this->fp);
	}
}
