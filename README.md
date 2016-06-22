# CSV Schema
A lot of people love Excel. Accounting people, marketing guys or like in my case game designers.
They create spreadsheets in wild formats and it is now your task to get that data in your application.
Sadly the possibility to create complex nested data structures with Excel and import them in a proper format
are very limited. The simplest way is usually to tell people to save their stuff as CSV.
And this is the time where this library comes into play.

## Value

```csv
col1,col2
foo,bar
hello,workd
```

```php
$schema = new Value(0);
$reader = new Reader();
$data = $reader->readFile('data.csv', $schema);

/* This will create an array with 
$data = ['foo', 'hello']; */
```

The Value class will read a value for the given column for each line.
Alone, this is nothing you would need a lib for but its real use comes 
when combining it with other node type

## Record

```php
$schema = new Record([
	new Value(0, 'col1'),
	new Value(1, 'col2'),
]);

$reader = new Reader();
$data = $reader->readFile('data.csv', $schema);

/* This will create an array with 
$data = [
	['col1' => 'foo', 'col2' => 'bar'],
	['col1' => 'hello', 'col2' => 'world'],
]; */
```

Ok this is still no magic, but we are still not at the end.
The record does not expect a single column where to find data but
a list of any other node that provides the data.

What you can also see is that the Value got a second parameter.
If the parameter is present the value is stored in a key similar to the parameters value.
This second parameter can also be used for the Record class in the same way.

If you use multiple nested Records you can achieve structures like in the following example:
 
```csv
First,Last,Age,Street,Nr,Zip,City
Tim,Meier,22,Main Street,1,1337,Berlin
Max,Mustermann,69,Some Road,2,21854,London
```

```php
$schema = new Record([
	new Value(0, 'first_name'),
	new Value(1, 'last_name'),
	new Value(2, 'age'),
	new Record([
		new Value(3, 'street'),
		new Value(4, 'nr'),
		new Value(5, 'zip'),
		new Value(6, 'city'),
	], 'address'),
]);

/* Will output:
Array
(
    [0] => Array
        (
            [first_name] => Tim
            [last_name] => Meier
            [age] => 22
            [address] => Array
                (
                    [street] => Main Street
                    [nr] => 1
                    [zip] => 1337
                    [city] => Berlin
                )
        )

    [1] => Array
        (
            [first_name] => Max
            [last_name] => Mustermann
            [age] => 69
            [address] => Array
                (
                    [street] => Some Road
                    [nr] => 2
                    [zip] => 21854
                    [city] => London
                )
        )
)
*/
```

## Aggregator

Seeing the previous example you might ask yourself: but how do I handle it when a user has multiple addresses?
Basically there are two possibilities. One is using an Aggregator.
The Aggregator will turn multiple lines of your csv in one single element in your result array.
Therefor you have to provide two lists of nodes. The ones that are duplicated on each line and the ones that differ.
Additional you need to specify a key in which the differing elements should bes stored.
But let's see an example for better understanding:

```csv
First,Last,Age,Street,Nr,Zip,City
Tim,Meier,22,Main Street,1,1337,Berlin
Max,Mustermann,69,Some Road,2,21854,London
Max,Mustermann,69,Ligusterweg,3,54641,New York
```

```php
$schema = new Aggregator([
	new Value(0, 'first_name'),
	new Value(1, 'last_name'),
	new Value(2, 'age'),
], 'addresses', new Record([
	new Value(3, 'street'),
	new Value(4, 'nr'),
	new Value(5, 'zip'),
	new Value(6, 'city'),
]));

/* Will output:
Array
(
    [0] => Array
        (
            [first_name] => Tim
            [last_name] => Meier
            [age] => 22
            [addresses] => Array
                (
                    [0] => Array
                        (
                            [street] => Main Street
                            [nr] => 1
                            [zip] => 1337
                            [city] => Berlin
                        )
                )
        )

    [1] => Array
        (
            [first_name] => Max
            [last_name] => Mustermann
            [age] => 69
            [addresses] => Array
                (
                    [0] => Array
                        (
                            [street] => Some Road
                            [nr] => 2
                            [zip] => 21854
                            [city] => London
                        )

                    [1] => Array
                        (
                            [street] => Ligusterweg
                            [nr] => 3
                            [zip] => 54641
                            [city] => New York
                        )
                )
        )
)
*/
```

This works a bit like:
```sql
SELECT * FROM users GROUP BY first_name, last_name, age;
```
with the difference that the non grouped columns are listed in a sub-array that is stored in the given key.

## Collection

But as I already said in the previous chapter, there is more than one way to handle our "multiple address problem".
If you don't wand to repeat yourself each line you can store all the data in online instead of aggregating it.
This would look like in the following example:

```csv
First,Middle,Last,Age,Street,Nr,Zip,City,street,nr,zip,city
Tim,,Meier,22,Main Street,1,1337,Berlin,,,,
Max,Heinz,Mustermann,69,Some Road,2,21854,London,Ligusterweg,3,54641,New York
```

```php
$schema = new Record([
	new Collection(0, 1, 'first_names'),
	new Value(2, 'last_name'),
	new Value(3, 'age'),
	new Collection(4, 8, 'addresses', new Record([
		new Value(0, 'street'),
		new Value(1, 'nr'),
		new Value(2, 'zip'),
		new Value(3, 'city'),
	])),
]);

/* This will have nearly the same output as the previous example 
except that the first name is an array: */
```

The first to Parameters of the Collection are the start and the end column of the collection.
If your collection consists of a record, the end column is the first column of the last record instance.
Note that everything nested into a collection needs to start counting from column 0 again.

## And what about Objects?

The Record and the Aggregator also allow you to specify a classname.
If the classname is given thy weill create an object of this class instead of an array.

```php
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
```

So far properties must be public. But maybe I add some reflection magic in future 
to also enable reading into private properties.

## Quick and dirty XML dump

As the headline already says, this is quick and "dirty". There is a little XML dumper included in this project.
You can use it to output your data quickly in xml. Of course you can also use any other proper xml solution that
can turn an array into XML. The Writer will only work with arrays not with objects.

```php
$mapping = ['addresse' => 'address'];
$writer = new Writer(['users' => $reader->readFile('data.csv', $schema)], $mapping);
echo $writer->get();
```
