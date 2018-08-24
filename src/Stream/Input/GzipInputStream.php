<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Stream\Input;


use Turenar\Simutrans\Exception\MissingModuleException;

class GzipInputStream implements InputStream
{
	private $inflater;
	/**
	 * @var resource
	 */
	private $fp;

	/**
	 * GzipStream constructor.
	 * @param resource $fp
	 */
	public function __construct($fp)
	{
		MissingModuleException::checkModuleFunction('zlib', 'deflate_init');
		$this->inflater = inflate_init(ZLIB_ENCODING_GZIP);
		$this->fp = $fp;
	}


	public function read(int $len): string
	{
		$data = fread($this->fp, $len);
		return inflate_add($this->inflater, $data);
	}


	public function hasNext(): bool
	{
		return !feof($this->fp);
	}
}
