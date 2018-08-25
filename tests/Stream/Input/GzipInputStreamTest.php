<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Stream\Input;


use PHPUnit\Framework\TestCase;
use Turenar\Simutrans\TestUtils;

class GzipInputStreamTest extends TestCase
{
	public function testRead()
	{
		$gz_stream = new GzipInputStream(TestUtils::getSaveDir() . '/xml_gz.sve');
		$gz_data = [];
		while ($gz_stream->hasNext()) {
			$gz_data[] = $gz_stream->read(65536);
		}

		$raw_stream = new FileInputStream(TestUtils::getSaveDir() . '/xml.sve');
		$raw_data = [];
		while ($raw_stream->hasNext()) {
			$raw_data[] = $raw_stream->read(65536);
		}

		$gz_content = implode('', $gz_data);
		$raw_content = implode('', $raw_data);
		self::assertEquals($raw_content, $gz_content, 'gzipped content mismatch');
	}
}
