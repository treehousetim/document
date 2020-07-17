<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use treehousetim\document\Exception as documentException;

use PHPUnit\Framework\TestCase;

final class arrayDocumentTest extends TestCase
{
	public function testJSONOutput()
	{
		$doc = new arrayDocument();

		$doc
			->name( (new nameDocument())
				->first_name( 'Robby' )
				->last_name( 'Robot' )
				->full_name( 'Robby the Robot' )
			)
			->name( (new nameDocument())
				->first_name( 'Shelia' )
				->last_name( 'Supreme' )
				->full_name( 'Shelia Supreme' )
			)
			->name( (new nameDocument())
				->first_name( 'Duran' )
				->last_name( 'Duran' )
				->full_name( 'Duran Duran' )
			)
			->name( (new nameDocument())
				->first_name( 'Jenny' )
				->last_name( 'Jumper' )
				->full_name( 'Jenny Jumper' )
			);

		$this->assertEquals(
			'{"name":[{"full_name":"Robby the Robot","first_name":"Robby","last_name":"Robot"},{"full_name":"Shelia Supreme","first_name":"Shelia","last_name":"Supreme"},{"full_name":"Duran Duran","first_name":"Duran","last_name":"Duran"},{"full_name":"Jenny Jumper","first_name":"Jenny","last_name":"Jumper"}]}',
			json_encode( $doc )
		);

		// test the exception also
		$this->expectException( documentException::class );
		$this->expectExceptionCode( documentException::missingData );
		$this->expectExceptionMessage( 'name:: has not been set' );

		unset( $doc->name );
		$doc->doValidate();
	}
}