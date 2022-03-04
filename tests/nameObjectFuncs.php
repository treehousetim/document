<?php declare(strict_types=1); namespace treehousetim\document\test;

use treehousetim\document\document;

class nameObjectFuncs
{
	public $full_name;
	public $first_name;
	public $last_name;
	public $suffix;

	public function first_name( $value )
	{
		$this->first_name = $value;
	}
	//------------------------------------------------------------------------
	public function full_name( $value )
	{
		$this->full_name = $value;
	}
	//------------------------------------------------------------------------
	public function last_name( $value )
	{
		$this->last_name = $value;
	}
}