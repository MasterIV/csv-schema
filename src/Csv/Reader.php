<?php

namespace Iv\Csv;
use Iv\Csv\Schema\Node;

class Reader
{
	private $separator;
	private $skipHeadline;

	/**
	 * Reader constructor.
	 * @param string $separator
	 * @param bool $skipHeadline
	 */
	public function __construct($separator = ',', $skipHeadline = true)
	{
		$this->separator = $separator;
		$this->skipHeadline = $skipHeadline;
	}

	public function readFile( $filename, Node $schema ) {
		$fp = fopen($filename, 'r');

		$lines = array();
		$result = array();

		if($this->skipHeadline)
			$headings = fgetcsv($fp, 4096, $this->separator);

		while($line = fgetcsv($fp, 4096, $this->separator))
			$lines[] = array_map('trim', $line );

		fclose( $fp );
		$last = count($lines)-1;

		foreach($lines as $i => $line)
			if(!empty( $line[0] ))
				$schema->readLine($line, $result, $i == $last );

		return $result;
	}
}
