<?php
declare(strict_types=1);

namespace Turenar\Simutrans;


class TestUtils
{
	public static function getSaveDir()
	{
		return defined('SAVE_DIR') ? SAVE_DIR : __DIR__ . '/save';
	}
}
