<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use treehousetim\document\Exception;

class testDocument extends document
{
	public $name;
	public $address_line_1;
	public $city;
	public $state_province;
	public $postal_code;
	public $email;

	public function jsonSerialize ()
	{
		$this->validate();
		$out = [];

		$out['name'] = $this->name->jsonSerialize();
		$out['city'] = $this->city;
		$out['state_province'] = $this->state_province;
		$out['postal_code'] = $this->postal_code;
		$out['email'] = $this->email;
		$out['address_line_1'] = $this->address_line_1;

		return $out;
	}
	//------------------------------------------------------------------------
	public function name( nameDocument $nameDoc ) : self
	{
		$this->name = $nameDoc;
		return $this;
	}
	//------------------------------------------------------------------------
	protected function validate()
	{
		$this->validateRequired( 'state_province' );
		$this->validateRequired( 'postal_code' );
		$this->validateRequired( 'address_line_1' );
		$this->validateSubDocument( 'name' );
	}
}