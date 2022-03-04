<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use PHPUnit\Framework\TestCase;

final class asClassTest extends TestCase
{
	public function testProperties()
	{
		$nameDoc = (new nameDocument())
			->first_name( 'Robby' )
			->last_name( 'Robot' )
			->full_name( 'Robby the Robot' );

		$nameObject = $nameDoc->asClassWithProps( nameObject::class );
		
		$this->assertEquals( $nameObject->first_name, 'Robby' );
		$this->assertEquals( $nameObject->last_name, 'Robot' );
		$this->assertEquals( $nameObject->full_name, 'Robby the Robot' );
		$this->assertInstanceOf( 'treehousetim\document\test\nameObject', $nameObject );
		$this->assertEquals( serialize( $nameObject ), 'O:37:"treehousetim\document\test\nameObject":4:{s:9:"full_name";s:15:"Robby the Robot";s:10:"first_name";s:5:"Robby";s:9:"last_name";s:5:"Robot";s:6:"suffix";N;}' );
	}
	//------------------------------------------------------------------------
	public function testPropertiesWithMap()
	{
		$nameDoc = (new nameDocument())
			->first_name( 'Robby' )
			->last_name( 'Robot' )
			->full_name( 'Robby the Robot' );

		$nameObject = $nameDoc->asClassWithProps( nameObject::class, ['first_name' => 'first_name'] );
		
		$this->assertEquals( $nameObject->first_name, 'Robby' );
		$this->assertEquals( $nameObject->last_name, '' );
		$this->assertEquals( $nameObject->full_name, '' );
	}
	//------------------------------------------------------------------------
	public function testFuncs()
	{
		$nameDoc = (new nameDocument())
			->first_name( 'Robby' )
			->last_name( 'Robot' )
			->full_name( 'Robby the Robot' );

		$nameObject = $nameDoc->asClassWithFuncs( nameObjectFuncs::class );
		
		$this->assertEquals( $nameObject->first_name, 'Robby' );
		$this->assertEquals( $nameObject->last_name, 'Robot' );
		$this->assertEquals( $nameObject->full_name, 'Robby the Robot' );
		$this->assertInstanceOf( 'treehousetim\document\test\nameObjectFuncs', $nameObject );
	}
	//------------------------------------------------------------------------
	public function testFuncsWithMap()
	{
		$nameDoc = (new nameDocument())
			->first_name( 'Robby' )
			->last_name( 'Robot' )
			->full_name( 'Robby the Robot' );

		$nameObject = $nameDoc->asClassWithFuncs( nameObjectFuncs::class, ['first_name' => 'first_name'] );
		
		$this->assertEquals( $nameObject->first_name, 'Robby' );
		$this->assertEquals( $nameObject->last_name, '' );
		$this->assertEquals( $nameObject->full_name, '' );
	}
}