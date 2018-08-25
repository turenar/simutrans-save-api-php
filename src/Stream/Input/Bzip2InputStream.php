<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Stream\Input;


use Turenar\Simutrans\Exception\MissingModuleException;

class Bzip2InputStream implements InputStream
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
		MissingModuleException::checkModuleFunction('bz2', 'bzopen');
		$this->fp = bzopen($filename, 'r');
	}


	public function read(int $len): string
	{
		return bzread($this->fp, $len);
	}


	public function hasNext(): bool
	{
		return !feof($this->fp);
	}
}
