<?php

namespace Iv\Csv\Schema;

class Assoc implements Node
{
	private $key;
	private $start;
	private $end;

	public function __construct($key, $start, $end)
	{
		$this->key = $key;
		$this->start = $start;
		$this->end = $end;
	}

	public function readLine($line, &$parent, $last = false)
	{
		$result = array();

		for($i = $this->start; $i <= $this->end; $i+=2 )
			if( !empty( $line[$i] ))
				$result[$line[$i]] = $line[$i+1];

		$parent[$this->key] = $result;
	}
}
