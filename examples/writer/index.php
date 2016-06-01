<?php

require '../../vendor/autoload.php';
header("Content-type: text/xml");

use Iv\Csv\Schema\Record;
use Iv\Csv\Schema\Value;
use Iv\Csv\Schema\Collection;
use Iv\Csv\Reader;
use Iv\Xml\Writer;

$schema = new Record([
	new Collection('names', 0, 2),
	new Value(3, 'age'),
	new Collection('addresses', 4, 8, new Record([
		new Value(0, 'street'),
		new Value(1, 'nr'),
		new Value(2, 'zip'),
		new Value(3, 'city'),
	])),
]);

$reader = new Reader();
$mapping = ['addresse' => 'address'];
$writer = new Writer(['users' => $reader->readFile('data.csv', $schema)], $mapping);
echo $writer->get();
