<?php
/*===============*/
/* Models Tests */ 
/*===============*/
if (empty($t)) die();

require_once('model.php');


function g_modelTest_serializedArr()
{
	static $arr = array('a','b','c');
	return $arr;
}

class TestModel extends DirBased_Model
{
	protected function serializeToArrayImpl()
	{
		return g_modelTest_serializedArr();
	}
}

function ModelTestConstructAndCallMethod($domain, $file, $url_arr, $method)
{
	$obj = new TestModel($domain, $file, $url_arr);
	return $obj->$method();
}

//negative testing
$name = 'DirBased_Model test (getCurrentDir, wrong domain)';
$params = array('sadfasfga','',array(),'getCurrentDir');
$t->catchException($name,null,'ModelTestConstructAndCallMethod',$params,'Error404');

$name = 'DirBased_Model test (getCurrentDir, empty domain, file = 123)';
$params = array('','123',array(),'getCurrentDir');
$t->catchException($name,null,'ModelTestConstructAndCallMethod',$params,'Error404');

$name = 'DirBased_Model test (getCurrentDir, crap in url_arr)';
$params = array('tests','',array('2314asgsdfgsd','d12341fgsdgs'),'getCurrentDir');
$t->catchException($name,null,'ModelTestConstructAndCallMethod',$params,'Error404');

/*$name = 'DirBased_Model test (getCurrentDir, slashes in url_arr)';
$params = array('tests','',array('model/','subdir/'),'getCurrentDir');
$t->catchException($name,null,'ModelTestConstructAndCallMethod',$params,'Error404');
*/

//getCurrentDir tests
$name = 'DirBased_Model test (getCurrentDir, normal dir, no file)';
$params = array('tests','',array(),'getCurrentDir');
$correct = 'tests/';
$t->compareFunc($name,null,'ModelTestConstructAndCallMethod',$params,$correct);

$name = 'DirBased_Model test (getCurrentDir, normal dir, with file)';
$params = array('tests','file.h',array(),'getCurrentDir');
$correct = 'tests/';
$t->compareFunc($name,null,'ModelTestConstructAndCallMethod',$params,$correct);

$name = 'DirBased_Model test (getCurrentDir, normal dir, with file=0)';
$params = array('tests','0',array(),'getCurrentDir');
$correct = 'tests/';
$t->compareFunc($name,null,'ModelTestConstructAndCallMethod',$params,$correct);

$name = 'DirBased_Model test (getCurrentDir, normal url_arr, model/)';
$params = array('tests','',array('model'),'getCurrentDir');
$correct = 'tests/model/';
$t->compareFunc($name,null,'ModelTestConstructAndCallMethod',$params,$correct);

$name = 'DirBased_Model test (getCurrentDir, normal url_arr, model/empty/)';
$params = array('tests','',array('model','empty'),'getCurrentDir');
$correct = 'tests/model/empty/';
$t->compareFunc($name,null,'ModelTestConstructAndCallMethod',$params,$correct);


//getCachePath tests
$name = 'DirBased_Model test (getCachePath, normal dir, no file)';
$params = array('tests','',array(),'getCachePath');
$correct = 'tests/.index'.CACHE_FILE;
$t->compareFunc($name,null,'ModelTestConstructAndCallMethod',$params,$correct);

$name = 'DirBased_Model test (getCachePath, normal dir, with file)';
$params = array('tests','file.h',array(),'getCachePath');
$correct = 'tests/.file.h'.CACHE_FILE;
$t->compareFunc($name,null,'ModelTestConstructAndCallMethod',$params,$correct);

$name = 'DirBased_Model test (getCachePath, normal dir, with file=0)';
$params = array('tests','0',array(),'getCachePath');
$correct = 'tests/.index'.CACHE_FILE;
$t->compareFunc($name,null,'ModelTestConstructAndCallMethod',$params,$correct);

$name = 'DirBased_Model test (getCachePath, normal dir, with file=2)';
$params = array('tests','2',array(),'getCachePath');
$correct = 'tests/.2'.CACHE_FILE;
$t->compareFunc($name,null,'ModelTestConstructAndCallMethod',$params,$correct);

$name = 'DirBased_Model test (getCachePath, normal url_arr, model/empty/)';
$params = array('tests','',array('model','empty'),'getCachePath');
$correct = 'tests/model/empty/.index'.CACHE_FILE;
$t->compareFunc($name,null,'ModelTestConstructAndCallMethod',$params,$correct);

//serializeToArray tests
$name = 'DirBased_Model test (serializeToArray)';
$params = array('tests','',array(),'serializeToArray');
$correct = g_modelTest_serializedArr();
$t->compareFunc($name,null,'ModelTestConstructAndCallMethod',$params,$correct);




/*============================= INDEX MODEL ======================= */
function IndexModelTestConstructAndCallMethod($domain, $offset, $method = '')
{
	$obj = new Index_Model($domain, $offset);
	if ($method) return $obj->$method();
}

$name = 'Index_Model test (wrong domain)';
$params = array('dgdsfgnjkdngjk/','','serializeToArray');
$correct = 'Error404';
$t->catchException($name,null,'IndexModelTestConstructAndCallMethod',$params,$correct);

$name = 'Index_Model test (redirect test)';
$params = array('tests/model/single_chapter/','','serializeToArray');
$correct = 'chapter/en/';
$t->catchException($name,null,'IndexModelTestConstructAndCallMethod',$params,$correct);

$name = 'Index_Model test (empty dir)';
$params = array('tests/model/empty/','abc','serializeToArray');
$correct = array(
	'elements' => array(),
	'stat' => array(
		'total_elements' => 0,
		'offset' => 0,
		'elements_on_page' => BOOKS_ON_A_PAGE
	)
);
$t->compareFunc($name,null,'IndexModelTestConstructAndCallMethod',$params,$correct);


$name = 'Index_Model test (offset not a digit)';
$params = array('tests/model/two_chapters/','abc','serializeToArray');
$correct = array(
	'elements' => array(
		0 => array(
			'title' => 'Ln1',
			'dir' => 'ln1/',
			'cover' => DEFAULT_COVER,
			'languages' => array(),
			'default_language' => DEFAULT_LANGUAGE,
			'is_new' => false,
			'announce' => ''
		),
		1 => array(
			'title' => 'Ln2',
			'dir' => 'ln2/',
			'cover' => DEFAULT_COVER,
			'languages' => array(),
			'default_language' => DEFAULT_LANGUAGE,
			'is_new' => false,
			'announce' => ''
		)
	),
	'stat' => array(
		'total_elements' => 2,
		'offset' => 0,
		'elements_on_page' => BOOKS_ON_A_PAGE
	)
);
$t->compareFunc($name,null,'IndexModelTestConstructAndCallMethod',$params,$correct);


$name = 'Index_Model test (normal)';
$params = array('tests/model/two_chapters/','','serializeToArray');
$t->compareFunc($name,null,'IndexModelTestConstructAndCallMethod',$params,$correct);


///===================== BOOK MODEL ===================================
function BookModelTestConstruct($domain,$arr)
{
	$bm = new Book_Model($domain,$arr);
}

$name = 'Book_Model test (normal dir)';
$params = array('tests/model/two_chapters/ln1/',array());
$correct = DEFAULT_LANGUAGE.'/';
$t->catchException($name,null,'BookModelTestConstruct',$params,$correct);


///===================== CHAPTERS MODEL ===================================
function ChaptersModelTestConstructAndCall($domain, $offset, $arr, $method)
{
	$obj = new Chapters_Model($domain, $offset, $arr);
	if ($method) return $obj->$method();
}

$name = 'Chapters_Model test (one subdir and about)';
$params = array('tests/model/one_and_about/',0,array(),'serializeToArray');
$correct = '00/';
$t->catchException($name,null,'ChaptersModelTestConstructAndCall',$params,$correct);


$name = 'Chapters_Model test (one dir + about)';
$params = array('tests/model/two_chapters/',0,array(),'serializeToArray');
$correct = array(
	'elements' => array(
		0 => array(
			'title' => 'Ln1',
			'dir' => 'ln1/',
			'cover' => DEFAULT_COVER,
			'is_new' => false,
			'announce' => ''
		),
		1 => array(
			'title' => 'Ln2',
			'dir' => 'ln2/',
			'cover' => DEFAULT_COVER,
			'is_new' => false,
			'announce' => ''
		)
	),
	'stat' => array(
		'total_elements' => 2,
		'offset' => 0,
		'elements_on_page' => CHAPTERS_ON_A_PAGE
	)
);
$t->compareFunc($name,null,'ChaptersModelTestConstructAndCall',$params,$correct);

$name = 'Chapters_Model test (2 dirs + about)';
$params = array('tests/model/two_and_about/',0,array(),'serializeToArray');
$correct = array(
	'elements' => array(
		0 => array(
			'title' => 'One',
			'dir' => 'one/',
			'cover' => DEFAULT_COVER,
			'is_new' => false,
			'announce' => ''
		),
		1 => array(
			'title' => 'Two',
			'dir' => 'two/',
			'cover' => DEFAULT_COVER,
			'is_new' => false,
			'announce' => ''
		)
	),
	'about' => array(
		'title' => 'About',
		'dir' => 'about/',
		'cover' => DEFAULT_COVER,
		'is_new' => false,
		'announce' => ''
	),
	'stat' => array(
		'total_elements' => 3,
		'offset' => 0,
		'elements_on_page' => CHAPTERS_ON_A_PAGE
	)
);
$t->compareFunc($name,null,'ChaptersModelTestConstructAndCall',$params,$correct);


$name = 'Chapters_Model test (empty dir)';
$params = array('tests',0, array('model','empty'),'serializeToArray');
$correct = array(
	'elements' => array(),
	'stat' => array(
		'total_elements' => 0,
		'offset' => 0,
		'elements_on_page' => CHAPTERS_ON_A_PAGE
	)
);
$t->compareFunc($name,null,'ChaptersModelTestConstructAndCall',$params,$correct);

///===================== IMAGES MODEL ===================================
function ImagesModelTestConstructAndCall($domain, $offset, $arr, $method)
{
	$obj = new Images_Model($domain, $offset, $arr);
	if ($method) return $obj->$method();
}

$name = 'Images_Model test (normal, 3 items)';
$correct = array(
	'elements' => array(
		0 => array(
			'path' => 'tests/model/three_images/image1.jpg',
			'filename' => 'image1.jpg'
		),
		1 => array(
			'path' => 'tests/model/three_images/image2.png',
			'filename' => 'image2.png'
		),
		2 => array(
			'path' => 'tests/model/three_images/image3.gif',
			'filename' => 'image3.gif'
		)
	),
	'stat' => array(
		'total_elements' => 3,
		'offset' => 0,
		'elements_on_page' => IMAGES_ON_A_PAGE
	),
	'comic_description' => array (
		'cover' => DEFAULT_COVER,
		'dir' => 'tests/',
		'languages' => array (
			0 => 'dir_list',
			1 => 'entities',
			2 => 'model',
		),
		'title' => 'Tests',
		'defaultLanguage' => 'dir_list',
		'isNew' => false,
		'author' => '',
		'booksCount' => 0,
		'chaptersCount' => 0,
		'authorLink' => '',
		'authorEmail' => '',
		'announce' => ''
	)
);
$params = array('tests',0,array('model','three_images'),'serializeToArray');
$t->compareFunc($name,null,'ImagesModelTestConstructAndCall',$params,$correct);


///===================== AN IMAGE MODEL ===================================
function TheImageModelTestConstructAndCall($domain, $offset, $arr, $method)
{
	$obj = new The_Image_Model($domain, $offset, $arr);
	if ($method) return $obj->$method();
}

$name = 'The_Image_Model test (normal, have image)';
$correct = array(
	'elements' => array(
		0 => array(
			'path' => 'tests/model/three_images/image1.jpg',
			'filename' => 'image1.jpg'
		),
		1 => array(
			'path' => 'tests/model/three_images/image2.png',
			'filename' => 'image2.png'
		),
		2 => array(
			'path' => 'tests/model/three_images/image3.gif',
			'filename' => 'image3.gif'
		)
	),
	'stat' => array(
		'total_elements' => 3,
		'offset' => 0,
		'elements_on_page' => 0
	),
	'comic_description' => array (
		'cover' => DEFAULT_COVER,
		'dir' => 'tests/',
		'languages' => array (
			0 => 'dir_list',
			1 => 'entities',
			2 => 'model',
		),
		'title' => 'Tests',
		'defaultLanguage' => 'dir_list',
		'isNew' => false,
		'author' => '',
		'booksCount' => 0,
		'chaptersCount' => 0,
		'authorLink' => '',
		'authorEmail' => '',
		'announce' => ''
	),
	'image' => array (
		'path' => 'tests/model/three_images/image1.jpg',
		'filename' => 'image1.jpg'
	)
);

$params = array('tests','image1.jpg',array('model','three_images'),'serializeToArray');
$t->compareFunc($name,null,'TheImageModelTestConstructAndCall',$params,$correct);
