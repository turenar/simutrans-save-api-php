<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Stream\Input;


use PHPUnit\Framework\TestCase;
use Turenar\Simutrans\TestUtils;

class Bzip2InputStreamTest extends TestCase
{
	public function testRead()
	{
		$bz_stream = new Bzip2InputStream(TestUtils::getSaveDir() . '/xml_bz2.sve');
		$bz_data = [];
		while ($bz_stream->hasNext()) {
			$bz_data[] = $bz_stream->read(65536);
		}

		$raw_stream = new FileInputStream(TestUtils::getSaveDir() . '/xml.sve');
		$raw_data = [];
		while ($raw_stream->hasNext()) {
			$raw_data[] = $raw_stream->read(65536);
		}

		$gz_content = implode('', $bz_data);
		$raw_content = implode('', $raw_data);
		self::assertEquals($raw_content, $gz_content, 'gzipped content mismatch');
	}
}
