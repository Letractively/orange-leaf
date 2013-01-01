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

function loadErrorPage($code, $message, $template, $e = null)
{
    $proto = $_SERVER['SERVER_PROTOCOL'];
    if (!$proto) {
        $proto = 'HTTP';
    }
    
    header($proto . ' ' . $code . ' ' . $message, true, $code);
    
    if (IS_DEBUGGING && $e) {
        var_dump($e);
    } else {
        if (file_exists($template)) {
            echo file_get_contents($template);
        } else {
            echo "Error $code $message\n";
        }
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
	
} catch (Error404 $e) {
    loadErrorPage(404, 'Page not found', ERROR_404_PAGE, $e);
    
} catch (Error403 $e) {
    loadErrorPage(403, 'Access denied', ERROR_403_PAGE, $e);
    
} catch (Exception $e) {    
    loadErrorPage(500, 'Internal Server Error', ERROR_500_PAGE, $e);
    
}

?>