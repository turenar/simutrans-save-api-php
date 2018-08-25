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
		$bz_hash = hash_init('sha256');
		while ($bz_stream->hasNext()) {
			hash_update($bz_hash, $bz_stream->read(65536));
		}

		$raw_stream = new FileInputStream(TestUtils::getSaveDir() . '/xml.sve');
		$raw_hash = hash_init('sha256');
		while ($raw_stream->hasNext()) {
			hash_update($raw_hash, $raw_stream->read(65536));
		}

		// compare hash rather than raw body to avoid large diff
		self::assertEquals(hash_final($raw_hash), hash_final($bz_hash), 'bzipped content mismatch');
	}
}
