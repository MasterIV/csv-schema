<?php

namespace Iv\Csv\Schema;

class Aggregator extends Record
{
	/** @var Node */
	private $child;
	/** @var string */
	private $childKey;
	/** @var array */
	private $current = array();

	public function __construct($fields, $childKey, Node $child, $key = null, $class = null)
	{
		parent::__construct($fields, $key, $class);
		$this->child = $child;
		$this->childKey = $childKey;
	}

	public function readLine($line, &$parent, $last = false)
	{
		$data = $this->readFields($line);

		if (is_object($data)) {
			$diff = empty($this->current) || array_diff_assoc(get_object_vars($data), get_object_vars($this->current));
		} else {
			$diff = array_diff_assoc($data, $this->current);
		}

		if ($diff) {
			if (!empty($this->current))
				$this->addData($this->current, $parent);

			$this->current = $data;
		}

		if (is_object($this->current)) {
			if($diff) $this->current->{$this->childKey} = array();
			$this->child->readLine($line, $this->current->{$this->childKey}, $last);
		} else {
			if($diff) $this->current[$this->childKey] = array();
			$this->child->readLine($line, $this->current[$this->childKey], $last);
		}

		if ($last)
			$this->addData($this->current, $parent);
	}
}
