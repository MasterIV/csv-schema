<pre><?php

require '../../vendor/autoload.php';

use Iv\Csv\Schema\Record;
use Iv\Csv\Schema\Value;
use Iv\Csv\Schema\Collection;
use Iv\Csv\Reader;

$schema = new Record([
	new Collection('first_names', 0, 1),
	new Value(2, 'last_name'),
	new Value(3, 'age'),
	new Collection('addresses', 4, 8, new Record([
		new Value(0, 'street'),
		new Value(1, 'nr'),
		new Value(2, 'zip'),
		new Value(3, 'city'),
	])),
]);

$reader = new Reader();
print_r($reader->readFile('data.csv', $schema));
