<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use treehousetim\document\Exception as documentException;

use PHPUnit\Framework\TestCase;

final class mapTest extends TestCase
{
	public function testMap()
	{
		$map = ['fullName' => 'full_name', 'firstName' => 'first_name', 'lastName' => 'last_name'];
		$data = ['fullName' => 'Robot Droid', 'firstName' => 'Robot', 'lastName' => 'Droid'];
		$doc = new \treehousetim\document\test\nameDocument();
		$doc->setFromMappedArray( $map, $data );

		$this->assertEquals( 'Robot Droid', $doc->full_name );
		$this->assertEquals( 'Robot', $doc->first_name );
		$this->assertEquals( 'Droid', $doc->last_name );
	}
}