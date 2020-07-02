<?php

define( 'BASEPATH', realpath( dirname( __FILE__ ) . '/..' ) . '/' );
include BASEPATH . 'vendor/autoload.php';

function getNameDocument() : \treehousetim\document\test\nameDocument
{
	$doc = new \treehousetim\document\test\nameDocument();
	$doc
		->full_name( 'My Name Is Unimportant' )
		->first_name( 'Robot' )
		->last_name( 'Rooty' );

	return $doc;
}
//------------------------------------------------------------------------
function getTestDocument() : \treehousetim\document\test\testDocument
{
	$doc = new  \treehousetim\document\test\testDocument();
	$doc
		->name( getNameDocument() )
		->address_line_1( '123 Somewhere Lane' )
		->city( 'Springfield' )
		->state_province( 'ZZ' )
		->postal_code( '12345' )
		->email( 'example@example.com' );

	return $doc;
}