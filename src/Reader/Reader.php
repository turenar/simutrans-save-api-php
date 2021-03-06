<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Reader;


use Turenar\Simutrans\Context;

interface Reader
{
	public function getContext(): Context;

	public function readByte(): int;

	public function readUnsignedByte(): int;

	public function readShort(): int;

	public function readUnsignedShort(): int;

	public function readInt(): int;

	public function readUnsignedInt(): int;

	public function readLongLong(): string;

	public function readDouble(): float;

	public function readId(): int;

	public function readString(): string;

	public function openTag(string $tag_name): Reader;

	public function closeTag(string $tag_name): Reader;
}
