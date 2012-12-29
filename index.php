<?php
/************************************
 *
 *	Comic Viewer, Entry point
 *
 *	Ilnar Taichinov, 2012
 *	mail@ilna.ru
 *
 ***********************************/ 

require_once('cfg.php');

/************ INCLUDES *************/
require_once('common.php');
require_once('model.php');
require_once('view.php');
require_once('controller.php');


/************ HELPER FUNCTIONS *****/
function suppressErrors($yes)
{
	if ($yes)
		error_reporting(0);
	else
	{
		error_reporting(-1);
		ini_set('display_errors','1');
		ini_set('error_log','my_file.log');
	}
}


/*********** ENTRY POINT ***********/
try {
	suppressErrors(!IS_DEBUGGING);
	
	$controller = new Comic_Controller();

	$model = $controller->acquireModel();
	$view = $controller->acquireView();
	
	if ($model && $view) 
	{
		if ( '' != CACHE_FILE && USE_CACHE ) {
			$cache_path = $model->getCachePath();
			if (!$view->hasCache($cache_path)) {		
				$view->cache($cache_path, $model->serializeToArray()); 
			}
			$view->displayFromCache($cache_path);
		} else {	
			$view->display($model->serializeToArray());
		}
	}

}catch (Redirect $r) {
	header('Location: ' . $r->getMessage());
	
} catch (Exception $e) {
	var_dump($e);
} 
?>