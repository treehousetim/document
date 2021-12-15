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

		//$doc = getTestDocument();
		$doc = new  \treehousetim\document\test\testDocument();
		//unset( $doc->address_line_1 );

		$doc->doValidate();
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
		// poor code in test document to test validation
		$doc->setNameString( 'string value instead of document object' );

		$doc->dovalidate();
	}
	//------------------------------------------------------------------------
	public function testCallOneVar()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::callOneVar );

		$doc = getTestDocument();
		$doc->city( 'Springfield', 'IL' );

		$doc->dovalidate();
	}
	//------------------------------------------------------------------------
	public function testDoValidate()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::callOneVar );

		$doc = getTestDocument();
		$doc->city( 'Springfield', 'IL' );

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
	//------------------------------------------------------------------------
	public function testHasValue()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::noValue );

		$doc = getTestDocument();
		$doc->postal_code( '' );
		$doc->validateValidateHasValue();
	}
	//------------------------------------------------------------------------
	public function testUnset()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::missingData );

		$doc = getTestDocument();
		unset( $doc->address_line_1 );

		$doc->doValidate();
	}
	//------------------------------------------------------------------------
	public function testIntValidation()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::disallowedValue );

		$doc = new intDocument();
		$doc->a_number( '1.1' );
		$doc->doValidate();
	}
	//------------------------------------------------------------------------
	public function testIntValidationSpace()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::disallowedValue );

		$doc = new intDocument();
		$doc->a_number( '' );
		$doc->doValidate();
	}
	//------------------------------------------------------------------------
	public function testIntValidationFalse()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::disallowedValue );

		$doc = new intDocument();
		$doc->a_number( false );
		$doc->doValidate();
	}
	//------------------------------------------------------------------------
	public function testIntValidationNoException()
	{
		$doc = new intDocument();
		$doc->a_number( '1' );
		$doc->doValidate();
		$this->assertEquals( 1, $doc->a_number );

		$doc->a_number( 1 );
		$doc->doValidate();
		$this->assertEquals( 1, $doc->a_number );
	}
	//------------------------------------------------------------------------
	public function testAlphaException()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::disallowedValue );

		$doc = new alphaDocument();
		$doc->alpha( 'abc123' );
		$doc->alphaNum( 'abc123' );
		$doc->doValidate();
	}
	//------------------------------------------------------------------------
	public function testAlphaNumbersPresentException()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::disallowedValue );

		$doc = new alphaDocument();
		$doc->alpha( '123' );
		$doc->alphaNum( 'abc123' );
		$doc->doValidate();
	}
	//------------------------------------------------------------------------
	public function testAlphaNumUnderscoreException()
	{
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::disallowedValue );

		$doc = new alphaDocument();
		$doc->alpha( 'abc' );
		$doc->alphaNum( '__abc123' );
		$doc->doValidate();
	}
	//------------------------------------------------------------------------
	public function testAlphaNumEmptyNoException()
	{
		$doc = new alphaDocument();
		$doc->alpha( '' );
		$doc->alphaNum( '' );
		$doc->doValidate();
		$this->assertEquals( '', $doc->alpha );
	}
}