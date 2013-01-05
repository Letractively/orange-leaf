<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author ilnar
 */
// TODO: check include path
//ini_set('include_path', ini_get('include_path'));

// put your code here
$path = 'C:\\Users\\ilnar\\Desktop\\home\\comic\\comv4\\';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

$path = 'C:\\Users\\ilnar\\Desktop\\home\\comic\\comv4\\model\\';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

$path = 'C:\\Users\\ilnar\\Desktop\\home\\comic\\comv4\\view\\';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

$path = 'C:\\Users\\ilnar\\Desktop\\home\\comic\\comv4\\controller\\';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'cfg.php';
?>
