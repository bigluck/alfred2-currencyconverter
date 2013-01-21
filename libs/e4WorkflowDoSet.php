<?php

class e4WorkflowDoSet extends e4WorkflowCommands
{
	public function run($inQuery, $args)
	{
		// Parsing user request
		$currentType = preg_match('/from/i', $this->inID) ? 'from' : 'to';
		$currentValue = $this->app->getDefault($currentType);
		$out = array();

		// Filter currency by user type
		$found = e4Currency::autoCompose(strtoupper($inQuery), true);
		foreach ($found AS $id => $null)
		{
			if ($id == $currentValue)
				array_unshift($out, $this->createRow($id, true));
			else
				array_push($out, $this->createRow($id));
		}

		if ($args[1] == 'save' && count($out) == 1)
			die($this->setDefault($currentType, $out[0]['arg']));

		return $out;
	}
	protected function createRow($id, $selected=false)
	{
		return array(
			'uid' => $id,
			'arg' => $id,
			'title' => 'Set '.$id.($selected ? ' (current setting)' : ''),
			'subtitle' => e4Currency::currencyName($id),
			'autocomplete' => $this->inID.$id,
			'icon' => 'icon.png',
			'valid' => 'true');
	}
	protected function setDefault($key, $value)
	{
		$currentFrom = $this->app->getDefault('from');
		$currentTo = $this->app->getDefault('to');

		$this->app->setDefault($key, $value);
		if ($key == 'from' && $value == $currentTo)
			$this->app->setDefault('to', $currentFrom);
		elseif ($key == 'to' && $value == $currentFrom)
			$this->app->setDefault('from', $currentTo);

		return 'New defaults: form '.$this->app->getDefault('from').' to '.$this->app->getDefault('to');
	}
}

?>