<?php


// Import Currency Converter libraries
include "./libs/e4Currency.php";
include "./libs/e4QueryParser.php";
include "./libs/e4QuerySend.php";


// Input data
$inQuery = trim($argv[1]) ?: '1';
$inDefaultFrom = new e4Currency(trim($argv[2]));
$inDefaultFrom->parse();
$inDefaultFrom = $inDefaultFrom->getFirstOption('EUR');

$inDefaultTo = new e4Currency(trim($argv[3]));
$inDefaultTo->parse();
$inDefaultTo = $inDefaultTo->getFirstOption($inDefaultFrom == 'EUR' ? 'USD' : 'EUR');

$results = array();


// Parsing user request
$objQuery = new e4QueryParser($inQuery, $inDefaultFrom, $inDefaultTo);
if ($objQuery->parse())
{
	$tmpFrom = $objQuery->getFrom();
	$tmpTo = $objQuery->getTo();
	$tmpAmount = $objQuery->getAmount();

	if (count($tmpFrom) > 1)
	{
		foreach ($tmpFrom AS $currency)
		{
			$suggest = $objQuery->getSuggestion('from', $currency);
			$results[] = array(
				'uid' => $currency,
				'arg' => 'none',
				'title' => $suggest,
				'subtitle' => e4Currency::currencyName($currency).' ('.$currency.')',
				'autocomplete' => $suggest,
				'icon' => 'icon.png',
				'valid' => 'no');
		}
	} else if (count($tmpTo) > 1)
	{
		foreach ($tmpTo AS $currency)
		{
			$suggest = $objQuery->getSuggestion('to', $currency);
			$results[] = array(
				'uid' => $currency,
				'arg' => 'none',
				'title' => $suggest,
				'subtitle' => e4Currency::currencyName($currency).' ('.$currency.')',
				'autocomplete' => $suggest,
				'icon' => 'icon.png',
				'valid' => 'no');
		}
	} else
	{
		$tmpResponse = new e4QuerySend($tmpAmount, $tmpFrom[0], $tmpTo[0]);
		if ($tmpResponse->sendRequest())
		{
			$results[] = array(
				'uid' => 'none',
				'arg' => $tmpResponse->getToAmount(),
				'title' => implode(' ', array(
					$tmpResponse->getFromAmount(),
					$tmpResponse->getFromCurrency(),
					'=',
					$tmpResponse->getToAmount(),
					$tmpResponse->getToCurrency())),
				'subtitle' => 'Powered by '.$tmpResponse->getService(),
				'icon' => 'icon.png',
				'valid' => 'yes');
		}
	}
}


// Invalid response matched
if (!count($results))
	$results[] = array(
		'uid' => 'none',
		'arg' => 'none',
		'title' => 'Invalid query',
		'subtitle' => 'Try ‘12 €‘ or ‘12 € to $‘ or ‘€‘ and have fun!',
		'icon' => 'icon.png',
		'valid' => 'no');


// Preparing the XML output file
$xmlObject = new SimpleXMLElement("<items></items>");
$xmlAttributes = array('uid', 'arg', 'valid', 'autocomplete');
foreach($results AS $rows)
{
	$nodeObject = $xmlObject->addChild('item');
	$nodeKeys = array_keys($rows);
	foreach ($nodeKeys AS $key)
		$nodeObject->{ in_array($key, $xmlAttributes) ? 'addAttribute' : 'addChild' }($key, $rows[$key]);
}

// Print the XML output
echo $xmlObject->asXML();  

?>