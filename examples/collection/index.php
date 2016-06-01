<pre><?php

require '../../vendor/autoload.php';

use Iv\Csv\Schema\Record;
use Iv\Csv\Schema\Value;
use Iv\Csv\Schema\Collection;
use Iv\Csv\Reader;

$schema = new Record([
	new Collection('first_names', 0, 1),
	new Value('last_name', 2),
	new Value('age', 3),
	new Collection('addresses', 4, 8, new Record([
		new Value('street', 0),
		new Value('nr', 1),
		new Value('zip', 2),
		new Value('city', 3),
	])),
]);

$reader = new Reader();
print_r($reader->readFile('data.csv', $schema));
