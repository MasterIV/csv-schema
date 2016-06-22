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
