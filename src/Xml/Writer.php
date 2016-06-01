<?php

namespace Iv\Xml;

class Writer
{
	private $keys;
	private $out = '<?xml version="1.0" encoding="UTF-8"?>';

	public function __construct($data, $keys)
	{
		$this->keys = $keys;
		$this->convertAssoc($data);
	}

	public function get()
	{
		return $this->out;
	}

	private function isAssoc($arr)
	{
		return array_keys($arr) !== range(0, count($arr) - 1);
	}

	private function convertAssoc($data) {
		foreach($data as $k => $v)
			$this->convertTag($v, $k);
	}

	private function convertNumeric($k, $data)
	{
		$name = substr($k, 0, -1);
		if(isset($this->keys[$name]))
			$name = $this->keys[$name];
		else
			$name = ucfirst($name);

		foreach($data as $v)
			if(!empty($v))
				$this->convertTag($v, $name);
	}

	private function convertTag($v, $k)
	{
		if ($v === null || $v === '' || $v === []) {
			$this->out .= "\n<$k/>";
		} else {
			$this->out .= "\n<$k>";

			if (is_array($v) && $this->isAssoc($v))
				$this->convertAssoc($v);
			elseif (is_array($v))
				$this->convertNumeric($k, $v);
			else
				$this->out .= $v;

			$this->out .= "</$k>";
		}
	}
}
