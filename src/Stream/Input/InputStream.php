<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Stream\Input;


interface InputStream
{
	public function read(int $len): string;

	public function hasNext(): bool;
}
