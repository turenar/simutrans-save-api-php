<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Reader;


use Turenar\Simutrans\Context;

interface Reader
{
	public function getContext(): Context;

	public function readByte(): int;

	public function readShort(): int;

	public function readInt(): int;

	public function readLongLong(): string;

	public function readId(): int;

	public function readString(): string;
}
