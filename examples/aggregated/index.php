<pre><?php

require '../../vendor/autoload.php';

use Iv\Csv\Schema\Aggregator;
use Iv\Csv\Schema\Value;
use Iv\Csv\Reader;

$schema = new Aggregator([
	new Value('first_name', 0),
	new Value('last_name', 1),
	new Value('age', 2),
], 'addresses', [
	new Value('street', 3),
	new Value('nr', 4),
	new Value('zip', 5),
	new Value('city', 6),
]);

$reader = new Reader();
print_r($reader->readFile('data.csv', $schema));
