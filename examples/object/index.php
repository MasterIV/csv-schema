<pre><?php

require '../../vendor/autoload.php';

use Iv\Csv\Schema\Record;
use Iv\Csv\Schema\Value;
use Iv\Csv\Reader;

class User {
	public $firstName;
	public $lastName;
	public $age;
	public $address;
}

class Address {
	public $street;
	public $nr;
	public $zip;
	public $city;
}

$schema = new Record([
	new Value(0, 'firstName'),
	new Value(1, 'lastName'),
	new Value(2, 'age'),
	new Record([
		new Value(3, 'street'),
		new Value(4, 'nr'),
		new Value(5, 'zip'),
		new Value(6, 'city'),
	], 'address', 'Address'),
], null, 'User');

$reader = new Reader();
print_r($reader->readFile('data.csv', $schema));
