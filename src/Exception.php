<?php namespace treehousetim\document;

class Exception extends \LogicException
{
	const undefined = -1;
	const missingData = 1;
	const wrongType = 2;
	const callOneVar = 3;
	const noSuchProperty = 4;
}
