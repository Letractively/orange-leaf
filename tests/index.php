<html>
<head>
	<title>
		Com Viewer Tests
	</title>
	<style type="text/css">
		.ok{ font-weight:bold; color:#00AA00; }
		.error{ /*font-weight:bold;*/ color:#ff0000; }
	</style>
</head>
<body>
<h1>Com Viewer Tester</h1>
<pre>
<?php

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

function getmicrotime() { 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec);
}

$start_time = getmicrotime();

require_once('tester.php');
$t = new SimpleTester(SimpleTester::TraceErrors);

chdir('..');
require_once('cfg.php');
require_once('tests/helpers_test.php');
require_once('tests/entities_test.php');
require_once('tests/models_test.php');


$totals = $t->getStat();
echo '<hr \>Passed tests: ' . ($totals['total']-$totals['failed']) . '/' . $totals['total'] . "\n";
echo 'Finished in ' . (getmicrotime() - $start_time) . " sec.\n" 
?>

</pre>
</body>
</html>