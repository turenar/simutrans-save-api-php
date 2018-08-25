<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Exception;

use PHPUnit\Framework\TestCase;

class MissingModuleExceptionTest extends TestCase
{
	/**
	 * @expectedException \Turenar\Simutrans\Exception\MissingModuleException
	 */
	public function testCheckModuleFunctionWithoutModule()
	{
		MissingModuleException::checkModuleFunction('not_found_module_name', 'not_found_func_name');
	}

	public function testCheckModuleFunctionWithModule()
	{
		self::assertNull(MissingModuleException::checkModuleFunction('json', 'json_encode'));
	}

}
