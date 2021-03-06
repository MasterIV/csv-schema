<?php

namespace Iv\Csv\Schema;

class Collection extends Node
{
	private $start;
	private $end;

	/** @var Record */
	private $record;

	public function __construct($start, $end, $key = null, Record $record = null)
	{
		parent::__construct($key);
		$this->start = $start;
		$this->end = $end;
		$this->record = $record;
	}

	public function readLine($line, &$parent, $last = false)
	{
		$result = array();
		$length = empty($this->record) ? 1 : $this->record->getLength();

		for($i = $this->start; $i <= $this->end; $i+=$length )
			if( !empty( $line[$i] ))
				if( empty($this->record))
					$result[] = $line[$i];
				else
					$this->record->readLine(array_slice($line, $i), $result);

		$this->addData($result, $parent);
	}
}
