<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use treehousetim\document\Exception;

class intDocument extends document
{
	protected $a_number;

	public function jsonSerialize ()
	{
		$this->validate();
		$out = [];

		$out['a_number'] = $this->a_number;

		return $out;
	}
	//------------------------------------------------------------------------
	protected function validate()
	{
		$this->validateInteger( 'a_number' );
	}
}