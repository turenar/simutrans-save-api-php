<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Reader\Xml;


class Token
{
	public const TYPE_OPEN_TAG = 0;
	public const TYPE_CLOSE_TAG = 1;
	public const TYPE_STRING = 2;

	protected $type;
	protected $str;
	protected $attributes;

	public function __construct(int $type, string $str, array $attributes = null)
	{
		$this->type = $type;
		$this->str = $str;
		$this->attributes = $attributes;
	}

	public function getType(): int
	{
		return $this->type;
	}

	public function getStr(): string
	{
		return $this->str;
	}

	/**
	 * @return string[]|null
	 */
	public function getAttributes(): ?array
	{
		return $this->attributes;
	}

	public function assertType(int $type, ?string $str = null)
	{
		if ($type !== $this->type) {
			throw new \LogicException("invalid token: expected type=$type but actual type={$this->getType()}");
		}
	}
}
