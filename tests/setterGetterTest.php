<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use treehousetim\document\Exception as documentException;

use PHPUnit\Framework\TestCase;

final class setterGetterTest extends TestCase
{
	public function testSetPublic()
	{
		$doc = new smallTestDocument();
		$doc
			->publicValue( 'some value' );

		$this->assertEquals(
			'some value',
			$doc->publicValue
		);
	}
	//------------------------------------------------------------------------
	public function testSetProtected()
	{
		$out = [];
		$out['publicValue'] = '';
		$out['protectedValue'] = 'some value';

		$doc = new smallTestDocument();
		$doc
			->protectedValue( 'some value' );

		$this->assertEquals(
			$out,
			$doc->jsonSerialize()
		);
	}
	//------------------------------------------------------------------------
	public function testGetProtected()
	{
		$doc = new smallTestDocument();
		$doc
			->protectedValue( 'some value' );

		$this->assertEquals(
			'some value',
			$doc->protectedValue
		);
	}
	//------------------------------------------------------------------------
	public function testGetNonExists()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::noSuchProperty );

		$doc = new smallTestDocument();
		$test = $doc->property_not_exists;
	}
}