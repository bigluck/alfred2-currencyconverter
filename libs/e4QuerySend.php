<?php

class e4QuerySend
{
	protected $amount = 1;
	protected $from = null;
	protected $to = null;
	protected $requestService = null;
	protected $responseFromAmount = null;
	protected $responseFromCurrency = null;
	protected $responseToAmount = null;
	protected $responseToCurrency = null;
	protected $valid = false;

	public function __construct($amount, $from, $to)
	{
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
		$response = $this->sendHTTPRequest('http://www.google.com/finance/converter', array(
			'a' => $this->amount,
			'from' => $this->from,
			'to' => $this->to));

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
		$response = $this->sendHTTPRequest('http://btcrate.com/convert', array(
			'amount' => $this->amount,
			'from' => $this->from,
			'to' => $this->to));

		if ($response && $response = @json_decode($response, true))
		{
			$this->responseToAmount = $response['converted']*1;
			return $this->valid = true;
		}

		return $this->valid = false;
	}
	protected function sendHTTPRequest($url, $query=array())
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($query));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		$response = curl_exec($ch);

		if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200 || !$response)
			$response = false;

		curl_close($ch);
		return $response;
	}
}

?>