<?php


// Library autoloader
define('ROOT', dirname($_SERVER['SCRIPT_NAME']).'/');
function __autoload($className)
{
	include ROOT.'libs/'.$className.'.php';
}


// Loading application config
$app = new e4WorkflowApp(ROOT);
echo $app->run($argv);


// e4Workflow library
class e4WorkflowApp
{
	public $root = false;
	public $appDefaultCommand = false;
	public $appCommands = array();

	public $name = 'global';
	public $version = 1.0;
	public $namespace = 'global';
	public $defaults = array();
	public $configPath = '~/Library/Preferences/net.exit4web.Alfred2Workflows.plist';

	public function __construct($root=false, $path='appConfig.json')
	{
		$this->root = $root ?: dirname($_SERVER['SCRIPT_NAME']).'/';

		if (($e4Config = file_get_contents($root.$path)) === false)
			throw new Exception('Config file "'.$path.'" not found', 1);
		if (($e4Config = json_decode($e4Config, true)) === false)
			throw new Exception('Invalid config file syntax', 1);

		// Loading app informations
		$this->setName($e4Config['app']['name'], $e4Config['app']['pListFileName']);
		$this->setVersion($e4Config['app']['version']);
		$this->setNamespace($e4Config['app']['namespace']);

		// Loading default configuration
		if (count($e4Config['defaults']) > 0)
			foreach ($e4Config['defaults'] AS $key => $value)
				$this->addDefault($key, $value);

		// Loading app commands
		if (count($e4Config['commands']) > 0)
			foreach ($e4Config['commands'] AS $info)
				$this->addCommand($info['id'], $info);
	}
	public function setName($name, $pListName=false)
	{
		$this->name = trim($name);
		if (trim($pListName) != '')
	 		$this->configPath = '~/Library/Preferences/'.trim($pListName).'.plist';
	}
	public function setVersion($version=1)
	{
		$this->version = trim($version);
	}
	public function setNamespace($namespace=false)
	{
		$this->namespace = ($namespace ?: strtolower(str_replace(' ', '', $this->name))).'.';
	}
	public function addCommand($key, $configs)
	{
		$configs['icon'] = $configs['icon'] ?: 'icon.png';
		$configs['valid'] = $configs['valid'] ? 'yes' : 'no';
		if ($configs['default'] === true)
			$this->appDefaultCommand = $configs['id'];
		$this->appCommands[$key] = $configs;
	}

	public function run($argv)
	{
		array_shift($argv);
		$query = trim($argv[0]) ?: '';

		$objects = array();
		$out = array();

		// Reading and executing input query
		if ($argv[1] != 'default' && count($this->appCommands) > 0)
			foreach ($this->appCommands AS $key => $config)
				if (!$query || preg_match('/^'.preg_quote(substr($query, 0, strlen($key))).'/i', $key))
					$objects[] = $this->loadCommander($key, $query);


		// Filter results and running requests
		if (!count($objects) && $this->appDefaultCommand !== false)
			$out = $this->loadCommander($this->appDefaultCommand, $query)->run($query, $argv);
		elseif (count($objects) == 1 && ($data = $objects[0]->getQueryMatch()) !== false)
			$out = $objects[0]->run($data[1], $argv);
		elseif (count($objects) > 0)
			foreach($objects AS $object)
				$out[] = $object->getCommandSuggest();


		// Transform output array to XML
		$xmlObject = new SimpleXMLElement("<items></items>");
		$tmpTypes = array(
			'uid' => 'addAttribute',
			'arg' => 'addAttribute',
			'valid' => 'addAttribute',
			'autocomplete' => 'addAttribute');
		foreach($out AS $rows)
		{
			$objItem = $xmlObject->addChild('item');
			foreach ($rows AS $key => $value)
				$objItem->{ $tmpTypes[$key] ?: 'addChild' }($key, $value);
		}
		return $xmlObject->asXML();
	}

	public function loadCommander($id, $query)
	{
		$config = $this->appCommands[$id];
		$className = 'e4WorkflowDo'.$config['cmd'];
		return new $className($this, $query, $config);
	}

	public function addDefault($key, $value)
	{
		if (!$this->getDefault($key))
			$this->setDefault($key, $value);
	}
	public function setDefault($key, $value)
	{
		if (shell_exec("defaults write {$this->configPath} \"{$this->namespace}{$key}\" \"{$value}\""))
			$this->defaults[$key] = trim($value);
		return $this->defaults[$key];
	}
	public function getDefault($key)
	{
		if ($out = shell_exec("defaults read {$this->configPath} \"{$this->namespace}{$key}\""))
			$this->defaults[$key] = trim($out);
		return $this->defaults[$key];
	}
}

abstract class e4WorkflowCommands
{
	protected $inQuery = '';
	protected $inID = '';
	protected $inConfig = array();

	public function __construct(e4WorkflowApp $app, $query, $config)
	{
		$this->app = $app;
		$this->inQuery = $query;
		$this->inConfig = $config;
		$this->inID = $this->inConfig['id'];
	}
	public function getConfig($key=false)
	{
		return $key ? $this->inConfig[$key] : $this->inConfig;
	}
	public function getQueryMatch()
	{
		if (preg_match('/^'.preg_quote($this->inID).'\s*(.*)\s*$/i', $this->inQuery, $out))
			return $out;
		return false;
	}
	public function getCommandSuggest()
	{
		return array(
			'uid' => $this->inID,
			'arg' => 'none',
			'title' => $this->getCommandSuggestValue('title'),
			'subtitle' => $this->getCommandSuggestValue('subtitle'),
			'autocomplete' => $this->inID.' ',
			'icon' => $this->getCommandSuggestValue('icon'),
			'valid' => $this->getCommandSuggestValue('valid'));
	}
	public function  getCommandSuggestValue($row)
	{
		return $this->inConfig[$row] ?: null;
	}

	public function run($inQuery, $args)
	{
		return array(array(
			'uid' => 'none',
			'arg' => 'none',
			'title' => 'Internal error',
			'subtitle' => 'Uncompleted "'.$this->inID.'" definition!',
			'icon' => 'icon.png',
			'valid' => 'no'));
	}
}

?>