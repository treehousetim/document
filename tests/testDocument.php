<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use treehousetim\document\Exception;

class testDocument extends document
{
	protected $name;
	protected $address_line_1;
	protected $city;
	protected $state_province;
	protected $postal_code;
	protected $email;

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
		$this->markValueSet( 'name' );
		return $this;
	}
	//------------------------------------------------------------------------
	public function setNameString( $name ) : self
	{
		$this->name = $name;
		$this->markValueSet( 'name' );
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
	//------------------------------------------------------------------------
	public function validateValidateHasValue()
	{
		$this->validateHasValue( 'postal_code' );
	}
}