<?php

class e4Currency
{
	protected $input = null;
	protected $options = array();

	public function __construct($input=null)
	{
		$this->setInput($input);
	}
	public function setInput($input=null)
	{
		$this->input = mb_strtoupper(trim($input) ?: '', 'UTF-8');
	}
	public function getInput()
	{
		return $this->input;
	}
	public function getOptions()
	{
		return array_keys($this->options);
	}
	public function getFirstOption($default=false)
	{
		$options = $this->getOptions();
		return $options[0] ?: $default;
	}
	public function parse()
	{
		$this->options = array();

		if (($tmp = self::isValidSymbol($this->input)) !== false)
			$this->options[$tmp] = true;
		if (($tmp = self::isValidCurrency($this->input)) !== false)
			$this->options[$tmp] = true;
		if (!count($this->options))
			$this->options = self::autoCompose($this->input);

		return count($this->options) ?: false;
	}
	public function isEqualOf(e4Currency $test)
	{
		return $this->options == $test->options;
	}


	static $validSymbols = array(
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
	static $validCurrency = array(
		'USD' => 'US Dollar',
		'EUR' => 'Euro',
		'JPY' => 'Japanese Yen',
		'GBP' => 'British Pound Sterling',
		'CHF' => 'Swiss Franc',
		'AUD' => 'Australian Dollar',
		'CAD' => 'Canadian Dollar',
		'SEK' => 'Swedish Krona',
		'HKD' => 'Hong Kong Dollar',
		'NOK' => 'Norwegian Krone',
		'BTC' => 'Bitcoin',
		'AED' => 'United Arab Emirates Dirham',
		'ANG' => 'Netherlands Antillean Guilder',
		'ARS' => 'Argentine Peso',
		'BDT' => 'Bangladeshi Taka',
		'BGN' => 'Bulgarian Lev',
		'BHD' => 'Bahraini Dinar',
		'BND' => 'Brunei Dollar',
		'BOB' => 'Bolivian Boliviano',
		'BRL' => 'Brazilian Real',
		'BWP' => 'Botswanan Pula',
		'CLP' => 'Chilean Peso',
		'CNY' => 'Chinese Yuan',
		'COP' => 'Colombian Peso',
		'CRC' => 'Costa Rican Colón',
		'CZK' => 'Czech Republic Koruna',
		'DKK' => 'Danish Krone',
		'DOP' => 'Dominican Peso',
		'DZD' => 'Algerian Dinar',
		'EEK' => 'Estonian Kroon',
		'EGP' => 'Egyptian Pound',
		'FJD' => 'Fijian Dollar',
		'HNL' => 'Honduran Lempira',
		'HRK' => 'Croatian Kuna',
		'HUF' => 'Hungarian Forint',
		'IDR' => 'Indonesian Rupiah',
		'ILS' => 'Israeli New Sheqel',
		'INR' => 'Indian Rupee',
		'JMD' => 'Jamaican Dollar',
		'JOD' => 'Jordanian Dinar',
		'KES' => 'Kenyan Shilling',
		'KRW' => 'South Korean Won',
		'KWD' => 'Kuwaiti Dinar',
		'KYD' => 'Cayman Islands Dollar',
		'KZT' => 'Kazakhstani Tenge',
		'LBP' => 'Lebanese Pound',
		'LKR' => 'Sri Lankan Rupee',
		'LTL' => 'Lithuanian Litas',
		'LVL' => 'Latvian Lats',
		'MAD' => 'Moroccan Dirham',
		'MDL' => 'Moldovan Leu',
		'MKD' => 'Macedonian Denar',
		'MUR' => 'Mauritian Rupee',
		'MVR' => 'Maldivian Rufiyaa',
		'MXN' => 'Mexican Peso',
		'MYR' => 'Malaysian Ringgit',
		'NAD' => 'Namibian Dollar',
		'NGN' => 'Nigerian Naira',
		'NIO' => 'Nicaraguan Córdoba',
		'NPR' => 'Nepalese Rupee',
		'NZD' => 'New Zealand Dollar',
		'OMR' => 'Omani Rial',
		'PEN' => 'Peruvian Nuevo Sol',
		'PGK' => 'Papua New Guinean Kina',
		'PHP' => 'Philippine Peso',
		'PKR' => 'Pakistani Rupee',
		'PLN' => 'Polish Zloty',
		'PYG' => 'Paraguayan Guarani',
		'QAR' => 'Qatari Rial',
		'RON' => 'Romanian Leu',
		'RSD' => 'Serbian Dinar',
		'RUB' => 'Russian Ruble',
		'SAR' => 'Saudi Riyal',
		'SCR' => 'Seychellois Rupee',
		'SGD' => 'Singapore Dollar',
		'SKK' => 'Slovak Koruna',
		'SLL' => 'Sierra Leonean Leone',
		'SVC' => 'Salvadoran Colón',
		'THB' => 'Thai Baht',
		'TND' => 'Tunisian Dinar',
		'TRY' => 'Turkish Lira',
		'TTD' => 'Trinidad and Tobago Dollar',
		'TWD' => 'New Taiwan Dollar',
		'TZS' => 'Tanzanian Shilling',
		'UAH' => 'Ukrainian Hryvnia',
		'UGX' => 'Ugandan Shilling',
		'UYU' => 'Uruguayan Peso',
		'UZS' => 'Uzbekistan Som',
		'VEF' => 'Venezuelan Bolívar',
		'VND' => 'Vietnamese Dong',
		'XOF' => 'CFA Franc BCEAO',
		'YER' => 'Yemeni Rial',
		'ZAR' => 'South African Rand',
		'ZMK' => 'Zambian Kwacha');
	static function isValidSymbol($symbol)
	{
		$symbol = mb_strtolower(trim($symbol), 'UTF-8');
		return self::$validSymbols[$symbol] ?: false;
	}
	static function isValidCurrency($currency)
	{
		$currency = mb_strtoupper(trim($currency), 'UTF-8');
		return self::$validCurrency[$currency] ? $currency : false;
	}
	static function autoCompose($currency, $all=false)
	{
		$out = array();
		$totChars = strlen($currency);
		if (($all && $totChars >= 0 && $totChars <= 3) || (!$all && $totChars > 0 && $totChars < 3))
			foreach (self::$validCurrency AS $key => $name)
				if (!$totChars || substr_compare($key, $currency, 0, $totChars) === 0)
					$out[$key] = true;

		return $out;
	}
	static function currencyName($currency)
	{
		return self::$validCurrency[$currency] ?: 'Unknown';
	}
}

?>