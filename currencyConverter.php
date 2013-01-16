<?php

// Symbols imported from http://www.xe.com/symbols.php
$knownSymbols = array(
	'£' => 'GBP',	'$' => 'USD',	'€' => 'EUR',	'₴' => 'UAH',	'$u' => 'UYU',
	'lek' => 'ALL',	'؋' => 'AFN',	'ƒ' => 'ANG',	'ман' => 'AZN',	'p.' => 'BYR',
	'bz$' => 'BZD',	'$b' => 'BOB',	'km' => 'BAM',	'P' => 'BWP',	'лв' => 'BGN',
	'r$' => 'BRL',	'៛' => 'KHR',	'¥' => 'JPY',	'₩' => 'KRW',	'₭' => 'LAK',
	'ls' => 'LVL',	'lt' => 'LTL',	'ден' => 'MKD',	'rm' => 'MYR',	'₨' => 'NPR',
	'₮' => 'MNT',	'mt' => 'MZN',	'c$' => 'NIO',	'₦' => 'NGN',	'kr' => 'SEK',
	'﷼' => 'SAR',	'b/.' => 'PAB',	'gs' => 'PYG',	's/.' => 'PEN',	'₱' => 'CUP',
	'zł' => 'PLN',	'lei' => 'RON',	'руб' => 'RUB',	'Дин' => 'RSD',	'Дин.' => 'RSD',
	's' => 'SOS',	'r' => 'ZAR',	'nt$' => 'TWD',	'฿' => 'THB',	'tt$' => 'TTD',
	'₤' => 'TRL',	'₴' => 'UAH',	'$u' => 'UYU',	'bs' => 'VEF',	'₫' => 'VND',
	'z$' => 'ZWD');
// Convert known symbols in a valid Currency code
function convertCurrencySymbol($symbol)
{
	global $knownSymbols;
	return mb_strtoupper($knownSymbols[mb_strtolower(trim($symbol), 'UTF-8')] ?: $symbol, 'UTF-8');
}


// Input data
$inQuery = $argv[1] ?: '1';
$inDefaultFrom = convertCurrencySymbol($argv[2]) ?: 'EUR';
$inDefaultTo = convertCurrencySymbol($argv[3]) ?: 'USD';

// Regular expression
$tNumber = '(\s*([from\s+]*)?(?<a>[\-|\+]?[\d\,\.]+))?';
$tFrom = '(\s*([from\s+]*)?(?<from>[^to|\s|\d]+))?';
$tTo = '(\s*([to\s+]*)?(?<to>[^\s]+)\s*)?';
$reTests = array(
	'to\s+'.$tTo.$tNumber.$tFrom,
	'to\s+'.$tNumber.$tTo.$tFrom,
	'to\s+'.$tTo.'(\s*from\s*)?'.$tNumber.$tFrom,
	$tNumber.$tFrom.$tTo,
	$tFrom.$tNumber.$tTo);
$results = array();


// Query parser
foreach ($reTests AS $re)
{
	if (preg_match("/^\s*{$re}/i", $inQuery, $match))
	{
		// Convert input symbols in a valid currency
		$match['from'] = convertCurrencySymbol($match['from']) ?: $inDefaultFrom;
		if (!($match['to'] = convertCurrencySymbol($match['to'])))
			$match['to'] = ($match['from'] == $inDefaultTo ? $inDefaultFrom : $inDefaultTo);

		if (strlen($match['from']) != 3 || strlen($match['to']) != 3)
			continue;

		$match['a'] = preg_replace('/(\d+)\.(?!\d+$)/', '$1', str_replace(',', '.', $match['a'] ?: '1'))*1;
		$match['a'] = $match['a'] ?: 1;

		// Loading remote data from Google Finance
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://www.google.com/finance/converter?'.http_build_query(array(
			'a' => $match['a'],
			'from' => $match['from'],
			'to' => $match['to'])));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		$out = curl_exec($ch);
		curl_close($ch);

		// Parsing Google output
		if (preg_match('/<div\s+id=["|\']?currency_converter_result["|\']?[^>]?>(.*)/', $out, $data))
		{
			$out = trim(strip_tags($data[1]));
			if (preg_match('/^\s*(?<fromFull>(?<from>[\d\.\,]+)\s*\w{3})\s*=\s*(?<toFull>(?<to>[\d\.\,]+)\s*\w{3})\s*$/', $out, $responseMatch))
				$results[] = array(
					'uid' => 'none',
					'arg' => $responseMatch['to'],
					'title' => $out,
					'subtitle' => 'powered by Google Finance',
					'icon' => 'icon.png',
					'valid' => false);
		}
		break;
	}
}


// No favorites matched
if (!count($results))
	$results[] = array(
		'uid' => 'none',
		'arg' => 'none',
		'title' => 'Invalid query',
		'subtitle' => 'Try ‘12 €‘ or ‘12 € to $‘ or ‘€‘ and have fun!',
		'icon' => 'icon.png',
		'valid' => false);


// Preparing the XML output file
$xmlObject = new SimpleXMLElement("<items></items>");
foreach($results AS $rows)
{
	$nodeObject = $xmlObject->addChild('item');
	$nodeKeys = array_keys($rows);
	foreach ($nodeKeys AS $key)
		$nodeObject->{ $key == 'uid' || $key == 'arg' ? 'addAttribute' : 'addChild' }($key, $rows[$key]);
}

// Print the XML output
echo $xmlObject->asXML();  

?>