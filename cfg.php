<?php
//All pathes are relative to the index.php file

/* Common settings. */
define('IS_DEBUGGING',          true);                  //Display errors
define('USE_CACHE',		false);                 //Are we caching output?
define('ADMIN_EMAIL',		'mail@ilna.ru');        //Admin's e-mail

/* Filenames. */                
define('SUBDIR',                'com');                 //Subdir, where the reader resides
define('CACHE_FILE',		'_cache.phtml');        //Cache file ending.	
define('DESCRIPTION_FILE',	'descr.xml');           //Name of xml file with comic description
define('ABOUT_DIR_NAME',	'about');               //Where to look for info about author/comic
define('COVER_FILE_NAME',	'cover');               //Cover file name without extension
define('IMAGE_FILE_TYPES',	'jpg, png, gif');       //Available image formats (extensions)
define('STYLE_FILE_NAME',       'styles.css');
define('BACKGROUND_FILE_NAME',  'bg');                  //Extension will be taken from IMAGE_FILE_TYPES
define('FAVICON_FILE_NAME',     'favicon.ico');         

/* Defaults. */
define('DEFAULT_LANGUAGE', 	'en');                  //Label of default language
define('DEFAULT_COVER',		'404.jpg');             //Path to the default cover file
define('THUMBNAIL_WIDTH',	'230');                 //The width of thumbnail in pixels
define('DEFAULT_TITLE',         'Comic Viewer');        //The width of thumbnail in pixels
define('WORD_NOT_FOUND',        '(?)');                 //This token will be appended to word if it's not found in the dictionary.
define('VALUE_NOT_FOUND',       '{NotFound}');
define('PAGE_MAX_WIDTH',        900);

/* Paging settings. */
define('BOOKS_ON_A_PAGE',	6);                     //How many books to display on the main page.
define('CHAPTERS_ON_A_PAGE',    6);                     //How many chapters to display on the comic page.
define('IMAGES_ON_A_PAGE',	6);                     //How many images to display on the chapter page.
define('USE_AJAX_CHAPTER',      true);                  //If images to be loaded asynchronously

/* Templates. */
define('INDEX_TEMPLATE',	'tpls/index.phtml');	//Path to the index template file
define('CHAPTERS_TEMPLATE',	'tpls/chapters.phtml');	//Path to the chapters template file
define('IMAGE_PAGE_TEMPLATE',   'tpls/the_image.phtml');//Path to the images template file
define('TUMBNAIL_SCRIPT',	'thumb/phpThumb.php');	//Path to thumbnailzator
define('ERROR_404_PAGE',	'tpls/404.html');	//Path to error 404 page
define('ERROR_403_PAGE',	'tpls/403.html');	//Path to error 403 page
define('ERROR_500_PAGE',	'tpls/500.html');	//Path to error 500 page

/* Localization */
function g_dictionary()
{
	static $g_loc = array(
		'en' => array(
			'chapter' => 'Chapter',
			'prev' => 'Prev',
			'next' => 'Next',
                        'home' => 'Home'
		),
		'ru' => array(
			'chapter' => 'Глава',
			'prev' => 'Взад',
			'next' => 'Вперед',
                        'home' => 'Домой'
		)

	);
	return $g_loc;
}

/* Pretend that you didn't see it */
function g_translate($ln,$str)
{
	$lns = g_dictionary();
	if ( isset($lns[$ln][$str]) ) return $lns[$ln][$str];
        if (IS_DEBUGGING) $str .= WORD_NOT_FOUND; 
	return $str;	
}