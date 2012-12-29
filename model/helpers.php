<?php
/************************************
 *
 *	Comic Viewer, Model Helpers
 *
 *	Ilnar Taichinov, 2012
 *	mail@ilna.ru
 *
 ***********************************/ 

function getSubdirs($path, $onlyName = false)
{
	$dirs = array();
	if (!file_exists($path))
		return $dirs;	
	
	$files = scandir($path);
	foreach ($files as $file) {
		//skip parents
		if ( '.' == $file || '..' == $file ) 
			continue;
		//skip hidden
		if ( '.' == $file[0] || '_' == $file[0] )
			continue;
                //skip about dir
                if (isAboutDir($file))
                    continue;
		//skip files
		$full_path = $path . $file;
		if (!is_dir($full_path))
			continue;
		$dirs[] = ($onlyName) ? $file : $full_path . '/';
	}
	return $dirs;
}

function getCurrentDirName($current_dir)
{
	// $current_dir = abc/def/123
	$pos = strripos($current_dir, '/');
	if (false === $pos)
		return $current_dir;
	$current_dir = mb_substr($current_dir, 0 ,$pos);
	
	// $current_dir = abc/def----
	$pos = strripos($current_dir, '/');
	if (false === $pos)
		return $current_dir;
	
	// $current_dir = ----def----
	return mb_substr($current_dir, $pos+1);
}

//function getParentDir($str) {
//    return getParentDirName($str);
//}

function getParentDirName($str)
{
	if (!is_string($str) || '' === $str) 
		return $str;
	$str .= 'tweek'; //needed to make sure that file is always specified, so pathinfo would return correct dirname
	$path_arr = pathinfo($str);
	if (empty($path_arr['dirname']))
		return '';
	//var_dump($str,$path_arr['dirname'].'/');
	$res = dirname($path_arr['dirname'].'/');
	if ('.' == $res) 
		return '';
	$res .= '/';
	return $res;
}

function g_ImageFileExtensions()
{
	static $exts = '';
	if (!is_array($exts)) {
		$exts = array();
		$tmp = explode(',', IMAGE_FILE_TYPES);
		foreach($tmp as $ext) {
			$exts[]=trim($ext);
		}
		
	}
	return $exts;
}

function isAboutDir($str)
{
	return ABOUT_DIR_NAME == $str || ABOUT_DIR_NAME.'/' == $str;
}