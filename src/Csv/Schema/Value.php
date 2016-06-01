<?php

namespace Iv\Csv\Schema;

class Value implements Node
{
	private $key;
	private $col;

	public function __construct($key, $col)
	{
		$this->key = $key;
		$this->col = $col;
	}

	public function readLine($line, &$parent, $last = false)
	{
		$parent[$this->key] = trim( $line[$this->col] );
	}
}
