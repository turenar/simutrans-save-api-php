<?php
declare(strict_types=1);

namespace Turenar\Simutrans;


class Context
{
	private $version;
	private $pak_name;
	private $extended_revision;

	public function __construct(Version $version, string $pak_name, ?int $extended_revision = null)
	{
		$this->version = $version;
		$this->pak_name = $pak_name;
		$this->extended_revision = $extended_revision;
	}

	/**
	 * @return Version
	 */
	public function getVersion(): Version
	{
		return $this->version;
	}

	/**
	 * @return string
	 */
	public function getPakName(): string
	{
		return $this->pak_name;
	}

	/**
	 * @return string
	 */
	public function getExtendedRevision(): ?int
	{
		return $this->extended_revision;
	}
}
