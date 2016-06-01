<?php

namespace Iv\Csv;
use Iv\Csv\Schema\Node;

class Reader
{
	public static function readFile( $filename ) {
		/** @var Node $schema */
		$schema = include "schema/$filename.php";
		$fp = fopen("input/$filename.csv", "r");

		$lines = array();
		$result = array();

		// skip headings
		$headings = fgetcsv($fp, 4096, "\t");

		while($line = fgetcsv($fp, 4096, "\t"))
			$lines[] = array_map('trim', $line );

		fclose( $fp );
		$last = count($lines)-1;

		foreach($lines as $i => $line)
			if(!empty( $line[0] ))
				$schema->readLine($line, $result, $i == $last );

		return $result;
	}
}
