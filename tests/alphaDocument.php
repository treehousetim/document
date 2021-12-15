<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;
use treehousetim\document\Exception;

class alphaDocument extends document
{
	protected $alpha;
	protected $alphaNum;

	public function jsonSerialize ()
	{
		$this->validate();
		$out = [];

		$out['alpha'] = $this->alpha;
		$out['alphaNum'] = $this->alphaNum;

		return $out;
	}
	//------------------------------------------------------------------------
	protected function validate()
	{
		$this->validateAlphaNumeric( 'alphaNum' );
		$this->validateAlpha( 'alpha' );
	}
}