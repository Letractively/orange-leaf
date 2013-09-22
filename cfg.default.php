<?php
//All pathes are relative to the index.php file

/* Common settings. */
define('IS_DEBUGGING',          true);                  //Display errors.
define('USE_CACHE',		false);                 //Are we caching output?
define('ADMIN_EMAIL',		'mail@ilna.ru');        //Admin's e-mail.

/* URL handling */
define('SUBDIR',                '');                    //Subdir, where the reader resides.
define('USE_ORIGINAL_NAMES',    false);                 //Use original folder and file names in url, e.g. http://ilna.ru/com/orange_life/en/00/ol_00_06.jpg -> http://ilna.ru/com/orange_life/en/00/06/
define('SINGLE_COMIC_MODE',     false);                 //Setting to true will avoid inserting comic name after domain name and subdir http://ilna.ru/com/orange_life/en/00/ -> http://ilna.ru/com/en/00/
define('SINGLE_COMIC_DIR',      '');                    //If single mode turned on, this comic will be the only one.

/* Filenames. */                
define('CACHE_FILE',		'_cache.phtml');        //Cache file ending.	
define('DESCRIPTION_FILE',	'descr.xml');           //Name of xml file with comic description.
define('ABOUT_DIR_NAME',	'about');               //Where to look for info about author/comic.
define('COVER_FILE_NAME',	'cover');               //Cover file name without extension.
define('IMAGE_FILE_TYPES',	'jpg, png, gif');       //Available image formats (extensions).
define('STYLE_FILE_NAME',       'styles.css');          //File for stylesheet.
define('BACKGROUND_FILE_NAME',  'bg');                  //Extension will be taken from IMAGE_FILE_TYPES.
define('FAVICON_FILE_NAME',     'favicon.ico');         //Favicon path.

/* Defaults. */
define('DEFAULT_LANGUAGE', 	'en');                  //Label of default language.
define('DEFAULT_COVER',		'404.jpg');             //Path to the default cover file.
define('THUMBNAIL_WIDTH',	'230');                 //The width of thumbnail in pixels.
define('DEFAULT_TITLE',         'Comic Viewer');        //The width of thumbnail in pixels.
define('WORD_NOT_FOUND',        '(?)');                 //This token will be appended to word if it's not found in the dictionary.
define('VALUE_NOT_FOUND',       '{NotFound}');          //Value to be displayed, when value not found.
define('PAGE_MAX_WIDTH',        900);                   //Max width of page.

/* Paging settings. */
define('BOOKS_ON_A_PAGE',	6);                     //How many books to display on the main page.
define('CHAPTERS_ON_A_PAGE',    6);                     //How many chapters to display on the comic page.
define('IMAGES_ON_A_PAGE',	6);                     //How many images to display on the chapter page.
define('USE_AJAX_CHAPTER',      true);                  //If images are to be loaded asynchronously.

/* Templates. */
define('INDEX_TEMPLATE',	'tpls/index.phtml');	//Path to the index template file.
define('CHAPTERS_TEMPLATE',	'tpls/chapters.phtml');	//Path to the chapters template file.
define('IMAGE_PAGE_TEMPLATE',   'tpls/the_image.phtml');//Path to the images template file.
define('TUMBNAIL_SCRIPT',	'slir/');               //Path to thumbnailzator.
define('ERROR_404_PAGE',	'tpls/404.html');	//Path to error 404 page.
define('ERROR_403_PAGE',	'tpls/403.html');	//Path to error 403 page.
define('ERROR_500_PAGE',	'tpls/500.html');	//Path to error 500 page.
define('NAV_PAGE_SYMBOL',       '&#9679;');             //Symbol used for page.

/* Libs dir */
define('APPLICATION_ROOT',      'gears/');              //Relative path to classes.

