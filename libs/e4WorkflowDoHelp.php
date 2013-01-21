<?php

class e4WorkflowDoHelp extends e4WorkflowCommands
{
	public function run($inQuery, $args)
	{
		return array(
			array(
				'uid' => 'none',
				'arg' => 'http://www.alfredforum.com/index.php?/topic/182-currency-converter-workflow-with-natural-language-support/',
				'title' => 'Alfred 2 Forum Topic',
				'subtitle' => 'http://www.alfredforum.com/index.php?/topic/182-currency-converter-workflow-with-natural-language-support/',
				'icon' => 'iconHTTP.png',
				'valid' => 'yes'),
			array(
				'uid' => 'none',
				'arg' => 'https://github.com/BigLuck/alfred2-currencyconverter/blob/master/README.md',
				'title' => 'Documentation',
				'subtitle' => 'https://github.com/BigLuck/alfred2-currencyconverter/blob/master/README.md',
				'icon' => 'iconHTTP.png',
				'valid' => 'yes'),
			array(
				'uid' => 'none',
				'arg' => 'https://github.com/BigLuck/alfred2-currencyconverter',
				'title' => 'Source Code',
				'subtitle' => 'https://github.com/BigLuck/alfred2-currencyconverter',
				'icon' => 'iconHTTP.png',
				'valid' => 'yes'));
	}
}

?>