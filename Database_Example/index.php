<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "autoload.php";
use Db\Connect;

$dns = "mysql:host=127.0.0.1;dbname=testDb;charset=utf8";
$userName = "yourUser";
$password = "yourUserPassword";

// create connection
$connection = new Connect($dns, $userName, $password, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));


// clear table
$deletedRows = $connection->query("DELETE FROM user")->getAffectedRows();
echo "Deleted Rows: ". $deletedRows.PHP_EOL;


// for insert we can use
$lastInsertId = $connection->query("INSERT INTO user SET name = :name", [
	['key' => ':name', 'value' => 'Bogdan']
])->getInsertId();
echo "Last Insert id: ". $lastInsertId.PHP_EOL;


$lastInsertId = $connection->query("INSERT INTO user SET name = :name", [
	['key' => ':name', 'value' => 'Mr,']
])->getInsertId();
echo "Last Insert id: ". $lastInsertId.PHP_EOL;


// for update
$affectedRows = $connection->query("UPDATE user SET name = :name WHERE id = :id", array(
	['key' => ':id', 'value' => $lastInsertId, 'value_type' => PDO::PARAM_INT],
	['key' => ':name', 'value' => 'Mr. Mr. Mr.']
))->getAffectedRows();
echo "Affected Rows: ". $affectedRows.PHP_EOL;


// now for select make and execute query
$result = $connection->query("SELECT * FROM user WHERE id = :id OR name = :name;", array(
	['key' => ':id', 'value' => $lastInsertId, 'value_type' => PDO::PARAM_INT],
	['key' => ':name', 'value' => 'Bogdan']
));
echo "Count of selected Rows: ".$result->getNumberRows().PHP_EOL;


echo PHP_EOL.PHP_EOL.PHP_EOL."Show current first row".PHP_EOL;
display($result->current()); // will show firs row
/**  will show firs row
array(2) {
	["id"]=> string(1) "1"
	["name"]=> string(6) "Bogdan"
}
 */


echo PHP_EOL.PHP_EOL.PHP_EOL."Go to next value".PHP_EOL;
$result->next(); // will switch to second row


echo PHP_EOL.PHP_EOL.PHP_EOL."Show second row".PHP_EOL;
display($result->current()); // will show second row
/**  will show second row
array(2) {
	["id"]=> string(1) "2"
	["name"]=> string(26) "Mr. Mr. Mr."
}
 */

// also we can use loops to move per list
echo PHP_EOL.PHP_EOL.PHP_EOL."Walk per list with FOR".PHP_EOL;
for ($result->rewind(); $result->valid(); $result->next()) {
	display($result->current());
}
/**
array(2) {
	["id"]=> string(1) "1"
	["name"]=> string(6) "Bogdan"
}
array(2) {
	["id"]=> string(1) "2"
	["name"]=> string(26) "Mr. Mr. Mr."
}
 */

$result->rewind();


echo PHP_EOL.PHP_EOL.PHP_EOL."Walk per list with FOREACH".PHP_EOL;
foreach ($result as $r) {
	display($r);
}
/**
array(2) {
	["id"]=> string(1) "1"
	["name"]=> string(6) "Bogdan"
}
array(2) {
	["id"]=> string(1) "2"
	["name"]=> string(26) "Mr. Mr. Mr."
}
 */


echo PHP_EOL.PHP_EOL.PHP_EOL."Walk per generator with FOREACH".PHP_EOL;
$generator = $result->getGenerator();
foreach ($generator as $g) {
	display($g);
}