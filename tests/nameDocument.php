<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use treehousetim\document\Exception;

class nameDocument extends document
{
	public $full_name;
	public $first_name;
	public $last_name;
	public $suffix;

	public function jsonSerialize ()
	{
		$this->validate();
		$out = [];

		$out['full_name'] = $this->full_name;
		$out['first_name'] = $this->first_name;
		$out['last_name'] = $this->last_name;

		$this->optionalFieldOut( 'suffix', $out );

		return $out;
	}
	//------------------------------------------------------------------------
	protected function validate()
	{
		$this->validateRequired( 'first_name' );
		$this->validateRequired( 'last_name' );
		$this->validateRequired( 'full_name' );
	}
}