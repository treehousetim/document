<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use treehousetim\document\Exception as documentException;

use PHPUnit\Framework\TestCase;

final class outDataTest extends TestCase
{
	public function testDataArray()
	{
		$expectedValue = ['publicValue' => null, 'protectedValue' => null];

		$doc = new smallTestDocument();
		
		$this->assertEquals( $expectedValue, $doc->dataArray() );
	}
	//------------------------------------------------------------------------
	public function testDataObject()
	{
		$expectedValue = new \stdClass();
		$expectedValue->publicValue = null;
		$expectedValue->protectedValue = null;

		$doc = new smallTestDocument();
		
		$this->assertEquals( $expectedValue, $doc->dataObject() );
	}
}