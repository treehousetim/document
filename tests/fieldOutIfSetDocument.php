<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;

class fieldOutIfSetDocument extends document
{
	protected $value;

	public function jsonSerialize()
	{
		$out = [];
		$this->fieldOutIfSet( 'value', $out );
		return $out;
	}
	//------------------------------------------------------------------------
	public function validate()
	{

	}
}