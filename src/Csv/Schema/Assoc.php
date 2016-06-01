<?php

namespace Iv\Csv\Schema;

class Assoc extends Node
{
	private $start;
	private $end;

	public function __construct($key, $start, $end)
	{
		parent::__construct($key);
		$this->start = $start;
		$this->end = $end;
	}

	public function readLine($line, &$parent, $last = false)
	{
		$result = array();

		for($i = $this->start; $i <= $this->end; $i+=2 )
			if( !empty( $line[$i] ))
				$result[$line[$i]] = $line[$i+1];

		$this->addData($result, $parent);
	}
}
