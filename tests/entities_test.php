<?php
/*===============*/
/* Entities Tests */ 
/*===============*/
if (empty($t)) die();

require_once('model/entities.php');

$correct_result = array(
	'title'=>'', 
	'defaultLanguage' => DEFAULT_LANGUAGE,
	'isNew' => false,
	'author' => '',
	'booksCount' => 0,
	'chaptersCount' => 0,
	'authorLink' => '',
	'authorEmail' => '',
	'announce' => ''
);
$actual_result = get_object_vars(new Comic_XML);
$t->compare('Comic_XML test (default constructor)',$correct_result,$actual_result);

$actual_result = get_object_vars(new Comic_XML('tests/entities/notfound.xml'));
$t->compare('Comic_XML test (xml file absent)',$correct_result,$actual_result);

$actual_result = get_object_vars(new Comic_XML('tests/entities/corrupted.xml'));
$t->compare('Comic_XML test (xml corrupted)',$correct_result,$actual_result);


$correct_result = array(
	'title'=>'Orange Life', 
	'defaultLanguage' => 'en',
	'isNew' => true,
	'author' => '',
	'booksCount' => 0,
	'chaptersCount' => 0,
	'authorLink' => '',
	'authorEmail' => '',
	'announce' => ''
);
$actual_result = get_object_vars(new Comic_XML('tests/entities/incomplete.xml'));
$t->compare('Comic_XML test (xml incomplete)',$correct_result,$actual_result);


$correct_result = array(
	'title'=>'Orange Life', 
	'defaultLanguage' => 'en',
	'isNew' => true,
	'author' => 'Lemon5ky',
	'booksCount' => 1,
	'chaptersCount' => 1,
	'authorLink' => 'http://lemon5ky.net',
	'authorEmail' => 'dimathebest@mail.ru',
	'announce' => 'Suprb com'
);
$actual_result = get_object_vars(new Comic_XML('tests/entities/normal.xml'));
$t->compare('Comic_XML test (xml normal)',$correct_result,$actual_result);

$correct_result = array(
	'title'=>'Русский комикс', 
	'defaultLanguage' => 'ru',
	'isNew' => true,
	'author' => 'Lemon5ky',
	'booksCount' => 1,
	'chaptersCount' => 1,
	'authorLink' => 'http://lemon5ky.net',
	'authorEmail' => 'dimathebest@mail.ru',
	'announce' => 'Suprb com'
);
$actual_result = get_object_vars(new Comic_XML('tests/entities/russian.xml'));
$t->compare('Comic_XML test (russian xml)',$correct_result,$actual_result);







//======================Book description ==============================

$correct_result = array(
	'cover' => DEFAULT_COVER,
	'dir' => 'empty_dir/',
	'languages' => array(),
	'title'=>'Empty Dir', 
	'defaultLanguage' => DEFAULT_LANGUAGE,
	'isNew' => false,
	'author' => '',
	'booksCount' => 0,
	'chaptersCount' => 0,
	'authorLink' => '',
	'authorEmail' => '',
	'announce' => ''
);
$actual_result = get_object_vars(new Book_Description('tests/entities/empty_dir/'));
$t->compare('Book_Description test (empty_dir)',$correct_result,$actual_result);


$correct_result = array(
	'cover' => DEFAULT_COVER,
	'dir' => '',
	'languages' => array(),
	'title'=>'', 
	'defaultLanguage' => DEFAULT_LANGUAGE,
	'isNew' => false,
	'author' => '',
	'booksCount' => 0,
	'chaptersCount' => 0,
	'authorLink' => '',
	'authorEmail' => '',
	'announce' => ''
);
$actual_result = get_object_vars(new Book_Description('dfgbdhbj'));
$t->compare('Book_Description test (incorrect path)',$correct_result,$actual_result);


$correct_result = array(
	'cover' => DEFAULT_COVER,
	'dir' => 'empty_dir/',
	'languages' => array(),
	'title'=>'Empty Dir', 
	'defaultLanguage' => DEFAULT_LANGUAGE,
	'isNew' => false,
	'author' => '',
	'booksCount' => 0,
	'chaptersCount' => 0,
	'authorLink' => '',
	'authorEmail' => '',
	'announce' => ''
);
$actual_result = get_object_vars(new Book_Description('tests/entities/empty_dir/'));
$t->compare('Book_Description test (empty_dir)',$correct_result,$actual_result);

$correct_result = array(
	'cover' => 'tests/entities/book/en/cover.jpg',
	'dir' => 'book/',
	'languages' => array('de','en','ru'),
	'title'=>'Book', 
	'defaultLanguage' => DEFAULT_LANGUAGE,
	'isNew' => false,
	'author' => '',
	'booksCount' => 0,
	'chaptersCount' => 0,
	'authorLink' => '',
	'authorEmail' => '',
	'announce' => ''
);
$actual_result = get_object_vars(new Book_Description('tests/entities/book/'));
$t->compare('Book_Description test (3 langs)',$correct_result,$actual_result);

$correct_result = array(
	'cover' => DEFAULT_COVER,
	'dir' => 'entities/',
	'languages' => array(),
	'title'=>'Entities', 
	'defaultLanguage' => DEFAULT_LANGUAGE,
	'isNew' => false,
	'author' => '',
	'booksCount' => 0,
	'chaptersCount' => 0,
	'authorLink' => '',
	'authorEmail' => '',
	'announce' => ''
);
$actual_result = get_object_vars(new Book_Description('tests/entities/book'));
$t->compare('Book_Description test (3 langs, no slash)',$correct_result,$actual_result);


$correct_result = array(
	'cover' => DEFAULT_COVER,
	'dir' => 'book2/',
	'languages' => array('fr','jp'),
	'title'=>'Book2', 
	'defaultLanguage' => 'fr',
	'isNew' => false,
	'author' => '',
	'booksCount' => 0,
	'chaptersCount' => 0,
	'authorLink' => '',
	'authorEmail' => '',
	'announce' => ''
);
$actual_result = get_object_vars(new Book_Description('tests/entities/book2/'));
$t->compare('Book_Description test (2 langs, no default, no covers)',$correct_result,$actual_result);

$correct_result = array(
	'cover' => 'tests/entities/book3/cover.gif',
	'dir' => 'book3/',
	'languages' => array('aa','zz'),
	'title'=>'Book3', 
	'defaultLanguage' => 'aa',
	'isNew' => false,
	'author' => '',
	'booksCount' => 0,
	'chaptersCount' => 0,
	'authorLink' => '',
	'authorEmail' => '',
	'announce' => ''
);
$actual_result = get_object_vars(new Book_Description('tests/entities/book3/'));
$t->compare('Book_Description test (2 langs, no default, cover in current dir)',$correct_result,$actual_result);

$correct_result = array(
	'cover' => DEFAULT_COVER,
	'dir' => 'book4/',
	'languages' => array('en','ru'),
	'title'=>'Orange Life', 
	'defaultLanguage' => 'en',
	'isNew' => true,
	'author' => 'Lemon5ky',
	'booksCount' => 1,
	'chaptersCount' => 1,
	'authorLink' => 'http://lemon5ky.net',
	'authorEmail' => 'dimathebest@mail.ru',
	'announce' => 'Suprb com'
);
$actual_result = get_object_vars(new Book_Description('tests/entities/book4/'));
$t->compare('Book_Description test (2 langs, with xml, no cover)',$correct_result,$actual_result);




//======================Chapter description ==============================
$correct_result = array(
	'cover' => DEFAULT_COVER,
	'dir' => 'empty_dir/',
	'languages' => array(),
	'title'=>'Empty Dir', 
	'defaultLanguage' => DEFAULT_LANGUAGE,
	'isNew' => false,
	'author' => '',
	'booksCount' => 0,
	'chaptersCount' => 0,
	'authorLink' => '',
	'authorEmail' => '',
	'announce' => ''
);
$actual_result = get_object_vars(new Chapter_Description('tests/entities/empty_dir/'));
$t->compare('Chapter_Description test (empty_dir)',$correct_result,$actual_result);


$correct_result = array(
	'cover' => DEFAULT_COVER,
	'dir' => '',
	'languages' => array(),
	'title'=>'', 
	'defaultLanguage' => DEFAULT_LANGUAGE,
	'isNew' => false,
	'author' => '',
	'booksCount' => 0,
	'chaptersCount' => 0,
	'authorLink' => '',
	'authorEmail' => '',
	'announce' => ''
);
$actual_result = get_object_vars(new Chapter_Description('dfgbdhbj'));
$t->compare('Chapter_Description test (incorrect path)',$correct_result,$actual_result);

$correct_result = array(
	'cover' => 'tests/entities/chapter/1.jpg',
	'dir' => 'chapter/',
	'languages' => array(),
	'title'=>'Chapter', 
	'defaultLanguage' => DEFAULT_LANGUAGE,
	'isNew' => false,
	'author' => '',
	'booksCount' => 0,
	'chaptersCount' => 0,
	'authorLink' => '',
	'authorEmail' => '',
	'announce' => ''
);
$actual_result = get_object_vars(new Chapter_Description('tests/entities/chapter/'));
$t->compare('Chapter_Description test (normal dir)',$correct_result,$actual_result);

$correct_result = array(
	'cover' => DEFAULT_COVER,
	'dir' => 'chapter_no_images/',
	'languages' => array(),
	'title'=>'Chapter No Images', 
	'defaultLanguage' => DEFAULT_LANGUAGE,
	'isNew' => false,
	'author' => '',
	'booksCount' => 0,
	'chaptersCount' => 0,
	'authorLink' => '',
	'authorEmail' => '',
	'announce' => ''
);
$actual_result = get_object_vars(new Chapter_Description('tests/entities/chapter_no_images/'));
$t->compare('Chapter_Description test (no appropriate file)',$correct_result,$actual_result);

//======================Image description ==============================
$correct_result = array('path'=>'','filename'=>'');
$actual_result = get_object_vars(new Image_Description);
$t->compare('Image_Description test (default constructor)',$correct_result,$actual_result);


$correct_result =  array('path'=>'abc/filename.ext','filename'=>'filename.ext');
$actual_result = get_object_vars(new Image_Description('abc/filename.ext'));
$t->compare('Image_Description test (normal path)',$correct_result,$actual_result);

//======================Item List description ==============================

function g_entity_testList()
{
	static $test_list = array();
	if (count($test_list) <= 0) {
		$test_list = array();
		$test_list['normal'] = array('item1','item2','item3');
		$test_list['empty'] = array();
		$test_list['long'] = array('item1','item2','item3', 'item4', 'item5', 'item6', 'item7', 'item8', 'item9', 'item10');
		$test_list['short'] = array('item1');		
	}
	return $test_list;
}

class EntityTestList extends ItemList
{
	protected function getItems($dir)
	{
		$testlist = g_entity_testList();
		return $testlist[$dir];
	}
}

class EntityTestItem
{
	public $val = '';
	
	function __construct($str)
	{
		$this->val = $str;
	}
}

/*function EntityTestListFunc($class, $dir, $offset = 0, $elements_on_page = 0)
{
	$tl = new EntityTestList($class, $dir, $offset, $elements_on_page);
}*/

$tl = new EntityTestList('EntityTestItem','empty');

$correct_result = array('total_elements'=>'0', 'offset'=>0, 'elements_on_page'=>0);
$actual_result = $tl->getPageStats();
$t->compare('ItemList test (getPageStats, empty dir)',$correct_result,$actual_result);

$correct_result = array();
$actual_result = $tl->getPageData();
$t->compare('ItemList test (getPageData, empty dir)',$correct_result,$actual_result);


$tl = new EntityTestList('EntityTestItem','normal', 0, 3);

$correct_result = array('total_elements'=>'3', 'offset'=>0, 'elements_on_page'=>3);
$actual_result = $tl->getPageStats();
$t->compare('ItemList test (getPageStats, normal dir)',$correct_result,$actual_result);

$correct_result = array(new EntityTestItem('item1'),new EntityTestItem('item2'),new EntityTestItem('item3'));
$actual_result = $tl->getPageData();
$t->compare('ItemList test (getPageData, normal dir)',$correct_result,$actual_result);


$tl = new EntityTestList('EntityTestItem','short', 0, 3);

$correct_result = array('total_elements'=>'1', 'offset'=>0, 'elements_on_page'=>3);
$actual_result = $tl->getPageStats();
$t->compare('ItemList test (getPageStats, short dir)',$correct_result,$actual_result);

$correct_result = array(new EntityTestItem('item1'));
$actual_result = $tl->getPageData();
$t->compare('ItemList test (getPageData, short dir)',$correct_result,$actual_result);


$tl = new EntityTestList('EntityTestItem','long', 0, 3);

$correct_result = array('total_elements'=>'10', 'offset'=>0, 'elements_on_page'=>3);
$actual_result = $tl->getPageStats();
$t->compare('ItemList test (getPageStats, long dir)',$correct_result,$actual_result);

$correct_result = array(new EntityTestItem('item1'),new EntityTestItem('item2'),new EntityTestItem('item3'));
$actual_result = $tl->getPageData();
$t->compare('ItemList test (getPageData, long dir)',$correct_result,$actual_result);


$tl = new EntityTestList('EntityTestItem','long', 3, 3);

$correct_result = array('total_elements'=>'10', 'offset'=>3, 'elements_on_page'=>3);
$actual_result = $tl->getPageStats();
$t->compare('ItemList test (getPageStats, long dir, offset 3)',$correct_result,$actual_result);

$correct_result = array(new EntityTestItem('item4'),new EntityTestItem('item5'),new EntityTestItem('item6'));
$actual_result = $tl->getPageData();
$t->compare('ItemList test (getPageData, long dir, offset 3)',$correct_result,$actual_result);


$tl = new EntityTestList('EntityTestItem','long', 10, 3);

$correct_result = array('total_elements'=>'10', 'offset'=>10, 'elements_on_page'=>3);
$actual_result = $tl->getPageStats();
$t->compare('ItemList test (getPageStats, long dir, offset 10)',$correct_result,$actual_result);

$correct_result = array();
$actual_result = $tl->getPageData();
$t->compare('ItemList test (getPageData, long dir, offset 10)',$correct_result,$actual_result);



//==================================FileList tests================
$fl = new FileList('EntityTestItem','empty');

$correct_result = array('total_elements'=>'0', 'offset'=>0, 'elements_on_page'=>0);
$actual_result = $fl->getPageStats();
$t->compare('FileList test (getPageStats, empty dir)',$correct_result,$actual_result);

$correct_result = array();
$actual_result = $fl->getPageData();
$t->compare('FileList test (getPageData, empty dir)',$correct_result,$actual_result);


$fl = new FileList('EntityTestItem','jeghjkrgn', 0, 3);

$correct_result = array('total_elements'=>'0', 'offset'=>0, 'elements_on_page'=>3);
$actual_result = $fl->getPageStats();
$t->compare('FileList test (getPageStats, wrong path)',$correct_result,$actual_result);

$correct_result = array();
$actual_result = $fl->getPageData();
$t->compare('FileList test (getPageData, wrong path)',$correct_result,$actual_result);


$fl = new FileList('EntityTestItem','tests/entities/chapter_no_images/', 0, 3);

$correct_result = array('total_elements'=>'0', 'offset'=>0, 'elements_on_page'=>3);
$actual_result = $fl->getPageStats();
$t->compare('FileList test (getPageStats, no appropriate images in folder)',$correct_result,$actual_result);

$correct_result = array();
$actual_result = $fl->getPageData();
$t->compare('FileList test (getPageData, no appropriate images in folder)',$correct_result,$actual_result);


$fl = new FileList('EntityTestItem','tests/entities/chapter/', 0, 3);

$correct_result = array('total_elements'=>'2', 'offset'=>0, 'elements_on_page'=>3);
$actual_result = $fl->getPageStats();
$t->compare('FileList test (getPageStats, normal dir)',$correct_result,$actual_result);

$correct_result = array(new EntityTestItem('tests/entities/chapter/1.jpg'),new EntityTestItem('tests/entities/chapter/2.jpg'));
$actual_result = $fl->getPageData();
$t->compare('FileList test (getPageData, normal dir)',$correct_result,$actual_result);

