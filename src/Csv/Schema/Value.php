<?php

namespace Iv\Csv\Schema;

class Value extends Node
{
	private $col;

	public function __construct($col, $key = null)
	{
		parent::__construct($key);
		$this->col = $col;
	}

	public function readLine($line, &$parent, $last = false)
	{
		$this->addData( $line[$this->col], $parent );
	}
}
