<pre><?php

require '../../vendor/autoload.php';

use Iv\Csv\Schema\Record;
use Iv\Csv\Schema\Value;
use Iv\Csv\Reader;

$schema = new Record([
	new Value('first_name', 0),
	new Value('last_name', 1),
	new Value('age', 2),
	new Record([
		new Value('street', 3),
		new Value('nr', 4),
		new Value('zip', 5),
		new Value('city', 6),
	], 'address'),
]);

$reader = new Reader();
print_r($reader->readFile('data.csv', $schema));
