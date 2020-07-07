<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;

class smallTestDocument extends document
{
	public $publicValue;
	protected $protectedValue;

	public function jsonSerialize()
	{
		$out = [];
		$out['publicValue'] = $this->publicValue;
		$out['protectedValue'] = $this->protectedValue;

		return $out;
	}
	//------------------------------------------------------------------------
	public function validate()
	{

	}
}