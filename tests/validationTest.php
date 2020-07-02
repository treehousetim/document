<?php declare(strict_types=1);

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
		$this->expectExceptionMessage( 'name is not a sub document' );

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


}