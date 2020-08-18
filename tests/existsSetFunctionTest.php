<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use treehousetim\document\Exception as documentException;

use PHPUnit\Framework\TestCase;

final class existsSetFunction extends TestCase
{
	public function testExists()
	{
		$doc = new smallTestDocument();

		$this->assertTrue( $doc->fieldExists( 'publicValue' ) );
		$this->assertFalse( $doc->fieldExists( 'noGood' ) );
	}
	//------------------------------------------------------------------------
	public function testExistsSet()
	{
		$doc = new smallTestDocument();
		$doc->publicValue( 'set me' );

		$this->assertTrue( $doc->fieldExistsAndIsSet( 'publicValue' ) );
	}
	//------------------------------------------------------------------------
	public function testNotSet()
	{
		$doc = new smallTestDocument();
		$doc->publicValue( 'set me' );

		$this->assertFalse( $doc->fieldExistsAndIsSet( 'publicValuee' ) );
	}
	//------------------------------------------------------------------------
	public function testDocument()
	{
		$doc = new fieldOutIfSetDocument();
		$doc->value( 'test' );
		$this->assertEquals( ['value' => 'test'], $doc->jsonSerialize() );
	}
	//------------------------------------------------------------------------
	public function testDocumentEmpty()
	{
		$doc = new fieldOutIfSetDocument();
		$this->assertEquals( [], $doc->jsonSerialize() );
	}
}