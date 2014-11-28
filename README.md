# pdo-debug

Shows the SQL query constructed by PDO. Seriously. The magic behind: A simple function that combines your parameters 
and the raw query. Please note that this is just an emulation, not the "real" result of PDO, but it does the job for 
nearly all common daily tasks. Way better than NOTHING!

## Features

- one simple global function `debugPDO($sql, $parameters)`
- works with named parameters (like ':param_1') and question-mark-parameters (like '?')
- this repo also contains a demo .sql file for easily testing the example below

## Big thanks to

Taken from here: http://stackoverflow.com/questions/210564/getting-raw-sql-query-string-from-pdo-prepared-statements,
big big big thanks to bigwebguy (http://stackoverflow.com/users/168256/bigwebguy)
and Mike (http://stackoverflow.com/users/1083889/mike) for creating the debugQuery() function!

## How to add to a project

As usual, require this via Composer (require-dev might be more useful as you definitely don't need this in production):

```json
"require-dev": {
    "panique/pdo-debug": "0.2"
}
```

## How to use

Your PDO block probably looks like this:
```php
$sql = "INSERT INTO test (col1, col2, col3) VALUES (:col1, :col2, :col3)";
```
and this, right ?
```php
$query->execute(array(':col1' => $param_1, ':col2' => $param_2, ':col3' => $param_3));
```

To use this PDO logger, you'll have to rebuild the param array a little bit:
Create an array that has the identifier as the key and the parameter as the value, like below:
**WARNING: write this WITHOUT the colon! The keys need to be 'xxx', not ':xxx'!**

```php
$parameters = array(
    'param1' => 'hello',
    'param2' => 123,
    'param3' => null
);
```

Your full PDO block would then look like:

```php
$sql = "INSERT INTO test (col1, col2, col3) VALUES (:param1, :param2, :param3)";
$query = $database_connection->prepare($sql);
$query->execute($parameters);
```

Now you can debug / log the full SQL statement by using the static method `show()` of the PdoDebugger class. Make sure 
to pass the raw SQL statement and the parameters array that contains proper keys and values. Future releases might have
a more professional way of handling this.

```php
echo PdoDebugger::show($sql, $parameters);
```

The result of this example will be:

```sql
INSERT INTO test (col1, col2, col3) VALUES ('hello', 123, NULL)
```

Yeah!

## Support the project (and others)

Support this - and other - projects by donating a coffee via
[PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P5YLUK4MW3LDG)
or
[Flattr](https://flattr.com/submit/auto?user_id=panique&url=https%3A%2F%2Fgithub.com%2Fpanique%2Fpdo-debug)