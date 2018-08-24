<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Stream;


interface Stream
{
	public function read(int $len): string;

	public function write(string $data): void;

	public function hasNext(): bool;
}
