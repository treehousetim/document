<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use treehousetim\document\Exception;

class nameDocument extends document
{
	protected $full_name;
	protected $first_name;
	protected $last_name;
	protected $suffix;

	const sufPHD = 'PHD';
	const sufMD = 'MD';

	protected $allowedSuffixes = [
		self::sufPHD,
		self::sufMD
	];

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
	public function suffix( $value ) : self
	{
		$this->validateValueInList( 'suffix', $value, $this->allowedSuffixes );
		$this->suffix = $value;

		return $this;
	}
	//------------------------------------------------------------------------
	protected function validate()
	{
		$this->validateRequired( 'first_name' );
		$this->validateRequired( 'last_name' );
		$this->validateRequired( 'full_name' );
	}
	//------------------------------------------------------------------------
	public function doValidateNotNull()
	{
		$this->validateNotNull( 'first_name', 'last_name' );
	}
	//------------------------------------------------------------------------
	public function doValidateNotNullNonField()
	{
		$this->validateNotNull( 'fizbuz' );
	}
}