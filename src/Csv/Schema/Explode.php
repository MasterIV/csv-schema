<?php

namespace Iv\Csv\Schema;

class Explode extends Node
{
	private $col;
	private $separator;

	public function __construct($col, $separator, $key = null)
	{
		parent::__construct($key);
		$this->col = $col;
		$this->separator = $separator;
	}

	public function readLine($line, &$parent, $last = false)
	{
		$this->addData( explode($this->separator, $line[$this->col]), $parent );
	}
}
