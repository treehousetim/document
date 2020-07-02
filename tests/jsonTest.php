<?php declare(strict_types=1);

use treehousetim\document\document;
use treehousetim\document\Exception as documentException;

use PHPUnit\Framework\TestCase;

final class jsonTest extends TestCase
{
	public function testSampleImplementation()
	{
		$doc = new treehousetim\document\test\nameDocument();
		$doc
			->full_name( 'Robot Droid' )
			->first_name( 'Robot' )
			->last_name( 'Droid' );

		$this->assertEquals(
			'{"full_name":"Robot Droid","first_name":"Robot","last_name":"Droid"}',
			json_encode( $doc )
		);
	}
}