<?php

namespace Iv\Csv\Schema;

class Aggregator extends Record implements Node
{
	/** @var string */
	private $addKey;
	/** @var Node[] */
	private $addFields;
	/** @var array */
	private $current = array();

	public function __construct($fields, $addKey, $addFields, $key = null)
	{
		parent::__construct($fields, $key);
		$this->addFields = $addFields;
		$this->addKey = $addKey;
	}

	public function readLine( $line, &$parent, $last = false ) {
		$data = $this->readFields($line);

		if(array_diff_assoc($data, $this->current)) {
			if(!empty($this->current))
				$this->addData($this->current, $parent);

			$this->current = $data;
			$this->current[$this->addKey] = array($this->readFields($line, $this->addFields));
		} else {
			$this->current[$this->addKey][] = $this->readFields($line, $this->addFields);
		}

		if($last)
			$this->addData($this->current, $parent);
	}
}
