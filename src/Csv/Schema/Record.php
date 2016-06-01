<?php

namespace Iv\Csv\Schema;

class Record implements Node
{
	/** @var Node[] */
	private $fields;
	/** @var string */
	private $key;

	public function __construct($fields, $key = null)
	{
		$this->fields = $fields;
		$this->key = $key;
	}

	protected function readFields($line, $fields = null) {
		$result = array();
		$fields = $fields ?: $this->fields;

		foreach( $fields as $field ) {
			$field->readLine($line, $result);
		}

		return $result;
	}

	protected function addData($data, &$parent) {
		if(empty($this->key))
			$parent[] = $data;
		else
			$parent[$this->key] = $data;
	}

	public function readLine($line, &$parent, $last = false)
	{
		$this->addData($this->readFields($line), $parent);
	}

	public function getLength()
	{
		return count( $this->fields );
	}
}
