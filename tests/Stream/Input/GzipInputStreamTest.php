<?php
declare(strict_types=1);

namespace Turenar\Simutrans\Stream\Input;


use PHPUnit\Framework\TestCase;
use Turenar\Simutrans\TestUtils;

class GzipInputStreamTest extends TestCase
{
	public function testRead()
	{
		$gz_stream = new GzipInputStream(TestUtils::getSaveDir() . '/extended/xml_gz.sve');
		$gz_hash = hash_init('sha256');
		while ($gz_stream->hasNext()) {
			hash_update($gz_hash, $gz_stream->read(65536));
		}

		$raw_stream = new FileInputStream(TestUtils::getSaveDir() . '/extended/xml.sve');
		$raw_hash = hash_init('sha256');
		while ($raw_stream->hasNext()) {
			hash_update($raw_hash, $raw_stream->read(65536));
		}

		// compare hash rather than raw body to avoid large diff
		self::assertEquals(hash_final($raw_hash), hash_final($gz_hash), 'gzipped content mismatch');
	}
}
