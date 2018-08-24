<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Stream\Input;


use PHPUnit\Framework\TestCase;
use Turenar\Simutrans\TestUtils;

class GzipInputStreamTest extends TestCase
{
	public function testRead()
	{
		$gz_fp = fopen(TestUtils::getSaveDir() . '/xml_gz.sve', 'r');
		$gz_stream = new GzipInputStream($gz_fp);
		$gz_data = [];
		while ($gz_stream->hasNext()) {
			$gz_data[] = $gz_stream->read(65536);
		}

		$raw_fp = fopen(TestUtils::getSaveDir() . '/xml.sve', 'r');
		$raw_stream = new FileInputStream($raw_fp);
		$raw_data = [];
		while ($raw_stream->hasNext()) {
			$raw_data[] = $raw_stream->read(65536);
		}

		$gz_content = implode('', $gz_data);
		$raw_content = implode('', $raw_data);
		self::assertEquals($raw_content, $gz_content, 'gzipped content mismatch');
	}
}
