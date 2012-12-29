<?
class SimpleTester
{
	const TraceAll = '3';
	const TraceNotifications = '2';
	const TraceErrors = '1';
	const TraceFaults = '0';
	
	private $traceLevel = self::TraceAll;
	private $totalTestsCount = 0;
	private $failedTestsCount = 0;
	
	public function __construct($trace_level = self::TraceAll)
	{
		switch ($trace_level)
		{
			case self::TraceAll:
			case self::TraceNotifications:
			case self::TraceErrors:
			case self::TraceFaults:
				$this->traceLevel = $trace_level;
				break;	
			default:
				$this->writeLog("Can't set trace level to '$trace_level', default value used ('$this->traceLevel')");
		}
	}
	
	public function writeLog($str)
	{
		echo $str . "\n";
	}
	
	public function compare($test_name, $val1, $val2)
	{
		$func1 = array(
			'object' => $this, 
			'method' => 'compare_internal', 
			'params' => array($val1, $val2)
		);
		return $this->try_catch($test_name, $func1, null);
	}
	
	public function compareFunc($test_name, $func_obj, $func_name, $params, $val)
	{
		$func1 = array(
			'object' => $func_obj, 
			'method' => $func_name, 
			'params' => $params
		);
		$func2 = array(
			'object' => $this, 
			'method' => 'compare_internal', 
			'params' => array($val)
		);
		return $this->try_catch($test_name, $func1, $func2);
	}
	
	public function catchException($test_name, $func_object, $func_name, $params, $exception) {
		$func1 = array(
			'object' => $func_object, 
			'method' => $func_name, 
			'params' => $params
		);
		return $this->try_catch($test_name, $func1, null,$exception);
	}
	
	public function getStat()
	{
		return array('total' => $this->totalTestsCount, 'failed' => $this->failedTestsCount);
	}
	
	///===================================================
	
	private function start_test($test_name)
	{
		$this->totalTestsCount ++;
		
		if ($this->traceLevel >= self::TraceNotifications) {
				$this->writeLog("Start $test_name ");
		}
	}
	
	private function test_failed($test_name, $e = null)
	{
		$this->failedTestsCount ++;
		if ($this->traceLevel > self::TraceFaults)
			$this->writeLog( '<span class="error"><b>!!!' . $test_name . ' failed:</b></span> ' . (($e)? (get_class($e) . '(' .$e->getMessage().')' ) : '') . "\n\n");
			
		if ($this->traceLevel >= self::TraceAll && $e)
			$this->writeLog( var_export($e, true) );
	}
	
	private function test_passed($test_name)
	{
		if ($this->traceLevel >= self::TraceNotifications) {
			$this->writeLog('<span class="ok">OK</span>');
		}
	}
	
	private function try_catch($test_name,$victim_func, $tester_func, $exception = '')
	{
		$this->start_test($test_name);
		//prepare function to be tested
		$call = $victim_func['method'];
		if (null !== $victim_func['object']) {
				$call = array($victim_func['object'],$victim_func['method']);
		}
		$params = (isset($victim_func['params'])) ? $victim_func['params'] : array();
		
		//prepare tester function if any
		$call2 = (count($tester_func) > 0) ? $tester_func['method'] : false;
		if ($call2 && null !== $tester_func['object']) {
				$call2 = array($tester_func['object'],$tester_func['method']);
				$params2 = $tester_func['params'];
		}
		
		$res = null;
		
		try
		{
			//run the function we want to test
			$res = call_user_func_array($call, $params);
			//if we have tester, test returned value
			if (false !== $call2) {
				$params2[]=$res;
				$res = call_user_func_array($call2, $params2);
			}
		}
		catch (Exception $e)
		{
			if ($this->traceLevel >= self::TraceAll)
				$this->writeLog( var_export($e, true) );
			
			//Did we suppose to get an exception?
			if ($exception !== get_class($e) && $exception != $e->getMessage()) {
				if (is_string($exception) && '' != $exception) {
					$e = new Exception("Correct exception message '$exception' not equal to '".$e->getMessage().'\'');
				}
				$this->test_failed($test_name,$e);
				return false;
			}
			
			//We actually are waiting for the exception.
			$this->test_passed($test_name);
			return true;
		}
		
		if ($exception) {
			//We aited for an exception, but none has raised, test failed
			$this->test_failed($test_name, new Exception('Exception is not thrown.'));
			return false;
		}
		
		$this->test_passed($test_name);
		return true;
	}
	
	
	private function compare_internal($val1, $val2)
	{	
		if ($val1 != $val2)
			throw new Exception("Compare fail \n" 
							. var_export($val1,true) 
							. "\n\n IS NOT EQUAL TO \n\n" 
							. var_export($val2,true)
							. " \n" 
			);
	}
}