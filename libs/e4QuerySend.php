<?php

class e4QuerySend
{
	protected $app = null;
	protected $amount = 1;
	protected $from = null;
	protected $to = null;
	protected $requestService = null;
	protected $responseFromAmount = null;
	protected $responseFromCurrency = null;
	protected $responseToAmount = null;
	protected $responseToCurrency = null;
	protected $valid = false;

	public function __construct($app, $amount, $from, $to)
	{
		$this->app = $app;
		$this->amount = $this->responseFromAmount = $amount;
		$this->from = $this->responseFromCurrency = $from;
		$this->to = $this->responseToCurrency = $to;
	}
	public function getFromAmount()
	{
		return $this->responseFromAmount;
	}
	public function getFromCurrency()
	{
		return $this->responseFromCurrency;
	}
	public function getToAmount()
	{
		return $this->responseToAmount;
	}
	public function getToCurrency()
	{
		return $this->responseToCurrency;
	}
	public function getService()
	{
		return $this->requestService;
	}
	public function isValid()
	{
		return $this->valid;
	}
	public function sendRequest()
	{
		if ($this->from == 'BTC' || $this->to == 'BTC')
			return $this->queryBitcoin();
		return $this->queryGoogle();
	}
	protected function queryGoogle()
	{
		$this->requestService = 'Google Finance';
		$response = $this->app->sendHTTPRequest('http://www.google.com/finance/converter?'.http_build_query(array(
			'a' => $this->amount,
			'from' => $this->from,
			'to' => $this->to)), null, 300);

		if ($response &&
			preg_match('/<div\s+id=["|\']?currency_converter_result["|\']?[^>]?>(.*)/', $response, $data) &&
			preg_match('/^\s*(?<from>(?<fromAmount>[\d\.\,]+)\s*(?<fromCurrency>\w{3}))\s*=\s*(?<to>(?<toAmount>[\d\.\,]+)\s*(?<toCurrency>\w{3}))\s*$/', strip_tags($data[1]), $data))
		{
			$this->responseFromAmount = $data['fromAmount']*1;
			$this->responseFromCurrency = $data['fromCurrency'];
			$this->responseToAmount = $data['toAmount']*1;
			$this->responseToCurrency = $data['toCurrency'];
			return $this->valid = true;
		}

		return $this->valid = false;
	}
	protected function queryBitcoin()
	{
		$this->requestService = 'BTCrate.com';
		$response = $this->app->sendHTTPRequest('http://btcrate.com/convert?'.http_build_query(array(
			'amount' => $this->amount,
			'from' => $this->from,
			'to' => $this->to)), null, 300);

		if ($response && $response = @json_decode($response, true))
		{
			$this->responseToAmount = $response['converted']*1;
			return $this->valid = true;
		}

		return $this->valid = false;
	}
}

?>