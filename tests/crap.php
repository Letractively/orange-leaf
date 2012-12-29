<?php

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

class A
{
	private $val = '';
	
	function __construct($val)
	{
		$this->val = $val;
		echo "A($val)";
	}
	
	public function getVal()
	{
		return $this->val;
	}
}

$a = new A('boo');

$call = array('a','getVal');

echo $$call[0]->$call[1]();

class Exp extends Exception
{}

try {
	throw new Exp();
	
} catch (Exception $e) {
	echo get_class($e);
}

echo ' bool =' . (new Exp('boo') == new Exp('boo'));