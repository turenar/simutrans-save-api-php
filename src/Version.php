<?php
declare(strict_types=1);

namespace Turenar\Simutrans;


class Version
{
	/**
	 * @param string $version
	 * @throws \InvalidArgumentException
	 * @return Version
	 */
	public static function parse(string $version): Version
	{
		$version_strs = explode('.', $version);
		$versions = [];
		foreach ($version_strs as $str) {
			if (!is_numeric($str)) {
				throw new \InvalidArgumentException('version specifier is not numeric');
			}
			$versions[] = intval($str);
		}
		return new Version($versions);
	}

	/**
	 * @var int[]
	 */
	private $versions;

	/**
	 * Version constructor.
	 * @param int[] $versions
	 */
	public function __construct(array $versions)
	{
		if (count($versions) < 3) {
			throw new \InvalidArgumentException('version specifier must have 3 elements');
		}
		$this->versions = $versions;
	}

	public function isExtendedVersion(): bool
	{
		return count($this->versions) >= 4;
	}

	public function getStandardMajor(): ?int
	{
		return $this->versions[1] ?? null;
	}

	public function getStandardMinor(): ?int
	{
		return $this->versions[2] ?? null;
	}

	public function getExtendedSaveMinor(): ?int
	{
		return $this->versions[2] ?? null;
	}

	public function getExtendedMajor(): ?int
	{
		return $this->versions[3] ?? null;
	}

	public function asString(): string
	{
		return implode('.', $this->versions);
	}
}
