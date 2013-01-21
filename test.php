<?php


// Library autoloader
define('ROOT', dirname($_SERVER['SCRIPT_NAME']).'/');
function __autoload($className)
{
	include ROOT.'libs/'.$className.'.php';
}


$config = json_decode(file_get_contents(ROOT.'appTest.json'), true);
$testData = array(
	'from' => 'currency',
	'to' => 'currency',
	'amount' => 'float');

echo str_repeat("\n", 15);


foreach ($config['testList'] AS $i => $test)
{
	$errors = false;

	$objQuery = new e4QueryParser($test['query'], $config['defaultFrom'], $config['defaultTo']);
	$objQuery->parse();

	if ($objQuery->isValid() != $test['valid'])
	{
		// Unexpected different result
		$errors .= '{ e4QueryParser: '.($objQuery->isValid() ? 'parsed' : 'invalid').' } ';
		$errors .= '!= ';
		$errors .= '{ expected: '.($test['valid'] ? 'parsed' : 'invalid').' } ';
	} elseif ($objQuery->isValid())
	{
		// Ok, both are valid; check every property
		foreach ($testData AS $key => $type)
		{
			$newKey = $key.'.in';

			switch ($type)
			{
				case 'currency':
					$getType = 'get'.ucfirst($key);
					$test[$newKey] = $objQuery->$getType();
					break;

				case 'float':
					$test[$newKey] = $objQuery->getAmount();
					break;
			}

			if ($test[$key] != $test[$newKey])
			{
				$errors .= '{ '.$key.': ';
				$errors .= '{ e4QueryParser: '.json_encode($test[$newKey]).' } ';
				$errors .= '!= ';
				$errors .= '{ expected: '.json_encode($test[$key]).' } ';
				$errors .= ' } ';
			}
		}
	}

	echo $i.': '.($errors ? $test['query'].' => '.$errors : 'Passed!')."\n";
	if ($errors)
		die(print_r($objQuery, true));
}

?>