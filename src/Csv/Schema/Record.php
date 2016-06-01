<?php

namespace Iv\Csv\Schema;

class Record extends Node
{
	/** @var Node[] */
	private $fields;
	/** @var \ReflectionClass */
	private $class;

	public function __construct($fields, $key = null, $class = null)
	{
		parent::__construct($key);
		$this->fields = $fields;

		if(!empty( $class ))
			$this->class = new \ReflectionClass($class);
	}

	protected function readFields($line, $fields = null)
	{
		$result = empty($this->class) ? array() : $this->class->newInstanceWithoutConstructor();
		$fields = $fields ?: $this->fields;

		foreach ($fields as $field) {
			$field->readLine($line, $result);
		}

		return $result;
	}

	public function readLine($line, &$parent, $last = false)
	{
		$this->addData($this->readFields($line), $parent);
	}

	public function getLength()
	{
		return count($this->fields);
	}
}
