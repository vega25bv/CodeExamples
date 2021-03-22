<?php
spl_autoload_register(function($className) {
	require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $className) . '.php';
});

function display($data)
{
	if (PHP_SAPI === 'cli')	{
		var_dump($data);
	} else {
		echo "<pre>".PHP_EOL;
		var_dump($data);
		echo "</pre>".PHP_EOL;
	}
}