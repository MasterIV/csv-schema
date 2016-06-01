<?php

namespace Iv\Csv\Schema;

abstract class Node
{
	private $key;

	public function __construct($key)
	{
		$this->key = $key;
	}

	public abstract function readLine( $line, &$parent, $last = false );

	protected function addData($data, &$parent)
	{
		if (empty($this->key))
			$parent[] = $data;
		elseif (is_object($parent))
			$parent->{$this->key} = $data;
		else
			$parent[$this->key] = $data;
	}
}
