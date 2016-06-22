<?php

namespace Iv\Csv\Schema;

class Fixed extends Node
{
	private $val;

	public function __construct($val, $key = null)
	{
		parent::__construct($key);
		$this->val = $val;
	}

	public function readLine($line, &$parent, $last = false)
	{
		$this->addData( $this->val, $parent );
	}
}
