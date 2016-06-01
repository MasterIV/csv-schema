<pre><?php

require '../../vendor/autoload.php';

use Iv\Csv\Schema\Record;
use Iv\Csv\Schema\Value;
use Iv\Csv\Reader;

$schema = new Record([
	new Value(0, 'first_name'),
	new Value(1, 'last_name'),
	new Value(2, 'age'),
	new Value(3, 'street'),
	new Value(4, 'nr'),
	new Value(5, 'zip'),
	new Value(6, 'city'),
]);

$reader = new Reader();
print_r($reader->readFile('data.csv', $schema));
