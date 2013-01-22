<?php

class e4QueryParser
{
	static $reNumber = '(\s*(from|to)?\s*(?<amount>[\-|\+]?\s*[\d\,\.]+)?)';
	static $reFrom = '\s*(from\s*)?(?<from>[^\d|\s]{1,3})?';
	static $reTo = '\s*(to\s*)?(?<to>[^\d|\s]{1,3})?';
	static $reRules = null;

	protected $app = null;
	protected $defaultFrom = null;
	protected $defaultTo = null;
	protected $query = '';
	protected $parsed = null;
	protected $amount = 1;
	protected $from = null;
	protected $to = null;
	protected $valid = false;

	public function __construct($app, $query=null, $defaultFrom='$', $defaultTo='€')
	{
		if (!self::$reRules)
			self::$reRules = array(
				self::$reNumber.self::$reFrom.self::$reTo => 'from %n %f to %t',
				self::$reFrom.self::$reNumber.self::$reTo => 'from %f %n to %t',
				self::$reNumber.self::$reTo.self::$reFrom => 'to %n %f from %t',
				self::$reTo.self::$reNumber.self::$reFrom => 'to %t %n from %f',
				self::$reTo.self::$reNumber.self::$reFrom => 'to %t from %n %f',
				self::$reTo.self::$reFrom.self::$reNumber => 'to %t from %f %n');
		
		$this->app = $app;

		// Set default values
		$this->defaultFrom = new e4Currency($defaultFrom);
		$this->defaultFrom->parse();
		$this->defaultTo = new e4Currency($defaultTo);
		$this->defaultTo->parse();

		// Initialize object data
		$this->setQuery($query);
		$this->from = new e4Currency();
		$this->to = new e4Currency();
	}
	public function setQuery($query=null)
	{
		$this->query = $query ?: 1;
	}
	public function getFrom()
	{
		return $this->from->getOptions() ?: $this->defaultFrom->getOptions();
	}
	public function getFromInput()
	{
		return $this->from->getInput();
	}
	public function getTo()
	{
		return $this->to->getOptions() ?: $this->defaultTo->getOptions();
	}
	public function getToInput()
	{
		return $this->to->getInput();
	}
	public function getAmount()
	{
		return $this->amount;
	}
	public function getParsed()
	{
		return $this->parsed;
	}
	public function getSuggestion($type='from', $suggest='EUR')
	{
		if (!($string = $this->parsed['reSuggest']))
			return '';

		$words = explode(' ', $string);
		$out = array();

		foreach ($words AS $rule)
		{
			if ($rule == 'from' && preg_match('/(from)/i', $this->query, $match))
			{
				$out[] = $match[1];
			} elseif ($rule == 'to' && preg_match('/(to)/i', $this->query, $match))
			{
				$out[] = $match[1];
			} elseif ($rule == '%n' && $this->parsed['amount'])
			{
				$out[] = $this->amount;
			} elseif ($rule == '%f' && $type == 'from')
			{
				$out[] = $suggest;
				break;
			} elseif ($rule == '%f' && $this->from->getOptions())
			{
				$first = $this->from->getFirstOption();
				$out[] = strcasecmp($this->parsed['from'], $suggest) < 0 ? $suggest : $this->parsed['from'];
			} elseif ($rule == '%t' && $type == 'to')
			{
				$out[] = $suggest;
				break;
			} elseif ($rule == '%t' && $this->to)
			{
				$first = $this->to->getFirstOption();
				$out[] = strcasecmp($this->parsed['to'], $suggest) < 0 ? $suggest : $this->parsed['to'];
			}
		}
		return implode(' ', $out).' ';
	}
	public function parse()
	{
		// Try to export informations from the user query
		foreach (self::$reRules AS $re => $suggestString)
		{
			if (preg_match("/^\s*{$re}\s*$/i", $this->query, $this->parsed))
			{
				$this->parsed['re'] = $re;
				$this->parsed['reSuggest'] = $suggestString;

				// Parse from currency
				$this->from->setInput($this->parsed['from']);
				if (!$this->from->parse())
					$this->from = $this->defaultFrom;

				// Parse to currency
				$this->to->setInput($this->parsed['to']);
				if (!$this->to->parse())
					$this->to = $this->from->isEqualOf($this->defaultFrom) ? $this->defaultTo : $this->defaultFrom;

				// Parse amount
				$this->amount = preg_replace('/(\d+)\.(?!\d+$)/', '$1', str_replace(',', '.', $this->parsed['amount'] ?: '1'))*1;
				$this->amount = $this->amount ?: 1;

				return $this->valid = true;
			}
		}
		return $this->valid = false;
	}
	public function isValid()
	{
		return $this->valid;
	}
	public function startingFromOrTo()
	{
		return preg_match('/^to/', $this->parsed['reSuggest']) ? 'to' : 'from';
	}
}

?>