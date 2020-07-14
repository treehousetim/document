<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use treehousetim\document\Exception as documentException;

use PHPUnit\Framework\TestCase;

final class validationTest extends TestCase
{
	public function testMissingData()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::missingData );

		$doc = getTestDocument();
		$doc->address_line_1( '' );

		// required to trigger validation
		$doc->jsonSerialize();
	}
	//------------------------------------------------------------------------
	public function testSettingNonExistantProperty()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::noSuchProperty );

		$doc = new \treehousetim\document\test\nameDocument();
		$doc->property_not_exists( 'document' );
	}
	//------------------------------------------------------------------------
	public function testSubDocument()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::wrongType );
		$this->expectExceptionMessage( 'name:: is not a sub document' );

		$doc = getTestDocument();
		$doc->name = 'string value instead of document object';

		// required to trigger validation
		$doc->jsonSerialize();
	}
	//------------------------------------------------------------------------
	public function testCallOneVar()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::callOneVar );

		$doc = getTestDocument();
		$doc->city( 'Springfield', 'IL' );

		// required to trigger validation
		$doc->jsonSerialize();
	}
	//------------------------------------------------------------------------
	public function testDoValidate()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::callOneVar );

		$doc = getTestDocument();
		$doc->city( 'Springfield', 'IL' );

		// should call validate and trigger the exception above
		$doc->doValidate();
	}
	//------------------------------------------------------------------------
	public function testValidateValueInList()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::disallowedValue );

		$doc = getNameDocument();
		$doc->suffix( 'bad value' );
	}
	//------------------------------------------------------------------------
	public function testValidateValueInListSuccess()
	{
		$doc = getNameDocument();
		$doc->suffix( nameDocument::sufMD );
		$this->assertEquals( $doc->suffix, nameDocument::sufMD );
	}
	//------------------------------------------------------------------------
	public function testNotNull()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::missingData );

		$doc = new \treehousetim\document\test\nameDocument();
		$doc->doValidateNotNull();
	}
	//------------------------------------------------------------------------
	public function testNotNullEquals()
	{
		$doc = new \treehousetim\document\test\nameDocument();
		$doc
			->first_name( '' )
			->last_name( '' );
		$doc->doValidateNotNull();

		$this->assertEquals( $doc->first_name, '' );
		$this->assertEquals( $doc->last_name, '' );
	}
	//------------------------------------------------------------------------
	public function testNotNullNonField()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::noSuchProperty );

		$doc = new \treehousetim\document\test\nameDocument();
		$doc->doValidateNotNullNonField();
	}
}