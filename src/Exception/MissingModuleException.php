<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Exception;


class MissingModuleException extends \RuntimeException
{
	public static function checkModuleFunction($module_name, $function_name)
	{
		if (!function_exists($function_name)) {
			throw new MissingModuleException("missing php module: $module_name");
		}
	}
}
