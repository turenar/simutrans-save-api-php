<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Reader\Xml;


use Turenar\Simutrans\Exception\InvalidSaveException;

class Token
{
	public const TYPE_OPEN_TAG = 0;
	public const TYPE_CLOSE_TAG = 1;
	public const TYPE_STRING = 2;

	private static function represent(int $type, string $str, array $attributes = null): string
	{
		switch ($type) {
			case self::TYPE_STRING:
				return $str;
			case self::TYPE_OPEN_TAG:
			case self::TYPE_CLOSE_TAG:
				$close_slash = $type === self::TYPE_OPEN_TAG ? '' : '/';
				return sprintf('<%s%s%s>', $close_slash, $str, self::representAttributes($attributes));
			default:
				return "[$type]$str";
		}
	}

	private static function representAttributes(?array $attrs): string
	{
		if (!$attrs) {
			return '';
		}

		$str = '';
		foreach ($attrs as $key => $val) {
			$str .= " $key=\"$val\"";
		}
		return $str;
	}

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
			throw new InvalidSaveException("invalid token: expected type=$type but got " . $this->asString());
		} elseif ($str !== null && $str !== $this->str) {
			throw new InvalidSaveException(
				sprintf("invalid token: expected %s but got %s", self::represent($type, $str), self::asString()));
		}
	}

	public function asString(): string
	{
		self::represent($this->type, $this->str, $this->attributes);
	}
}
