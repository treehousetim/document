<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use treehousetim\document\Exception;

class arrayDocument extends document
{
	protected $name = array();

	public function jsonSerialize ()
	{
		$this->validate();
		return $this->getFieldArray( 'name' );
	}
	//------------------------------------------------------------------------
	public function name( nameDocument $nameDoc ) : self
	{
		$this->name[] = $nameDoc;
		$this->markValueSet( 'name' );
		return $this;
	}
	//------------------------------------------------------------------------
	protected function validate()
	{
		$this->validateSubDocument( 'name' );
	}
}