<?
/************************************
 *
 *	Comic Viewer, Model Classes
 *
 *	Ilnar Taichinov, 2012
 *	mail@ilna.ru
 *
 ***********************************/ 
require_once('model/helpers.php');
require_once('model/entities.php');
require_once('model/dir_based.php');

class Index_Model extends Node_Model
{
    protected function getChildrenImpl($offset) {
        $dl = new DirList('Book_Description',$this->getCurrentDir(),$offset, BOOKS_ON_A_PAGE);
        $stats = $dl->getPageStats();
        $data = $dl->getPageData();
        //var_dump($data);
        if (1 == $stats['total_elements']) 
        {
            throw new Redirect($data[0]->dir . $data[0]->defaultLanguage . '/');
        }
        return $dl;
    }
    
    protected function getDescriptionImpl($offset) { return new Chapter_Description(''); }  
    protected function getCurrentLangImpl() { return DEFAULT_LANGUAGE; }
    protected function linksToOtherLangs($langs) {
        return array();
    }
    public function __construct($domain, $offset) { parent::__construct($domain, $offset, array()); }
}

// just a redirector class
class Book_Model extends DirBased_Model
{
    public function __construct($domain, $arr) {
        parent::__construct($domain, '', $arr);
        $this_book = new Book_Description($this->getCurrentDir());
        throw new Redirect( $this_book->defaultLanguage . '/');
    }

    protected function serializeToArrayImpl() { return array(); }
}



class Chapters_Model extends Node_Model
{
    protected function linksToOtherLangs($langs) {
        $comic_dir = getParentDirName($this->getCurrentDir());
        $res = array();
        foreach ($langs as $ln) {
            if ( file_exists($comic_dir . $ln . '/') )
                $res[]= $ln;
        }
        return $res;
    }
            
    protected function getChildrenImpl($offset) {
        $dl = new DirList('Chapter_Description',$this->getCurrentDir(),$offset, CHAPTERS_ON_A_PAGE);
        
        $stat = $dl->getPageStats();
        $data = $dl->getPageData();
        if (1 == $stat['total_elements']) 
                throw new Redirect($data[0]->dir );
        
        return $dl;
    }
    
    protected function getDescriptionImpl($offset) {
        $comic_dir = getParentDirName($this->getCurrentDir());
        return new Book_Description($comic_dir);
    }
    
    protected function getCurrentLangImpl() {
        $lang_dir = $this->getCurrentDir();
        return getCurrentDirName($lang_dir );
    }
}


class Images_Model extends Node_Model
{
    private $show_all;
    
    protected function linksToOtherLangs($langs) {
        $comic_dir = getParentDirName($this->getCurrentDir());
        $comic_dir = getParentDirName($comic_dir);
        $chapter_dir = getCurrentDirName($this->getCurrentDir()); 
        $res = array();
        foreach ($langs as $ln) {
            if (file_exists($comic_dir . $ln . '/' . $chapter_dir . '/') )
                $res[]=$ln;
        }
        return $res;
    }
    
    protected function getChildrenImpl($offset){
        $n_imgs = ($this->show_all) ? 0 : IMAGES_ON_A_PAGE;
        return new FileList('Image_Description',$this->getCurrentDir(),$offset, $n_imgs);
    }
    
    protected function getDescriptionImpl($offset){
        $comic_dir = getParentDirName( getParentDirName($this->getCurrentDir()) );
        return new Book_Description($comic_dir);
    }
    
    protected function getExtraImpl($offset){
        $arr['chapter'] = getCurrentDirName($this->getCurrentDir());
        return $arr;
    }
    
    protected function getCurrentLangImpl()
    {
        $lang_dir = getParentDirName($this->getCurrentDir());
        return getCurrentDirName($lang_dir );
    }
    public function __construct($domain, $offset, $arr, $show_all = false)
    {
            parent::__construct($domain, $offset, $arr);
            $this->show_all = !!$show_all;
    }
}



class The_Image_Model extends Images_Model
{

    private $filename = '';
    
    protected function linksToOtherLangs($langs) {
        $comic_dir = getParentDirName($this->getCurrentDir());
        $comic_dir = getParentDirName($comic_dir);
        $chapter_dir = getCurrentDirName($this->getCurrentDir()); 
        $res = array();
        foreach ($langs as $ln) {
            if (file_exists($comic_dir . $ln . '/' . $chapter_dir . '/' . $this->filename) )
                $res[]=$ln;
        }
        return $res;
    }

    protected function serializeToArrayImpl() {
        $arr = parent::serializeToArrayImpl();
        $size = getimagesize($this->getCurrentDir().$this->filename);
        $arr['extra']['image'] = array(
                'path' => $this->getCurrentDir().$this->filename,
                'filename' => $this->filename,
                'size' =>  array(
                    'width' => (isset($size[0]) ? $size[0] : 0),
                    'height' => (isset($size[1]) ? $size[1] : 0),
                    'raw' => $size
                )
        );

        return $arr;
    }

    public function __construct($domain, $filename, $arr) {
        parent::__construct($domain, $filename, $arr, true);
        $this->filename = $filename;
    }
}

class The_Num_Image_Model extends The_Image_Model 
{
    private $id = null;
    public function __construct($domain, $arr) {
        $this->id = intval(array_pop($arr));
        $this->resolver = new The_Image_Model_Resolver($domain, $arr);
        $filename = $this->resolver->indexToName($this->id);
        if (null === $filename) 
            throw new Error404('Image num not found.');
        
        parent::__construct($domain, $filename, $arr);
    }
    
     protected function serializeToArrayImpl() {
        $arr = parent::serializeToArrayImpl();
        $arr['extra']['image']['num'] = $this->id;
        return $arr;
     }
}

class The_Image_Model_Resolver extends DirBased_Model  // OOP deadlock: in The_Num_Image_Model I need to call getCurrentDir before parent constructor is called
{
    private $pics = null; 
    protected function serializeToArrayImpl() { throw Error500('The_Image_Model_Resolver is an utility Model'); }
    
    private function populateList() 
    {
        if (null === $this->pics) {
            $this->pics = new FileList('Image_Description',  $this->getCurrentDir() );
        }
    }
    
    public function indexToName($index) {
        $this->populateList();
        $objs = $this->pics->getPageData();
        if (!isset($objs[$index])) //element not found
            return null;
        $obj = $objs[$index];
        return $obj->filename;
    }
    
    public function nameToIndex($name) {
        $this->populateList();
        $index = null;
        foreach($this->pics->getPageData() as $obj) {
            if ($obj->filename == $name) 
                break;
            $index++;
        }  
        return $index;
    }
    
    public function __construct($domain, $arr) {
        parent::__construct($domain, '', $arr);
    }
}

class About_Model extends Node_Model 
{
    protected function linksToOtherLangs($langs) {
        $comic_dir = getParentDirName($this->getCurrentDir());
        $comic_dir = getParentDirName($comic_dir);
        $res = array();
        foreach ($langs as $ln) {
            if ( file_exists($comic_dir . $ln . '/') )
                $res[]= $ln;
        }
        return $res;
    }
            
    protected function getChildrenImpl($offset) { return array(); }
    
    protected function getDescriptionImpl($offset) {
        $comic_dir = getParentDirName( getParentDirName($this->getCurrentDir()) );
        return new Book_Description($comic_dir);
    }
    
    protected function getCurrentLangImpl() {
        $lang_dir = getParentDirName( $this->getCurrentDir() );
        return getCurrentDirName($lang_dir );
    }
}

//class Index_Model extends DirBased_Model
//{
//    private $dirList = null;
//    private $offset = 0;
//
//    private function buildComicList()
//    {
//        $this->dirList = new DirList('Book_Description',$this->getCurrentDir(),$this->offset, BOOKS_ON_A_PAGE);
//        $stats = $this->dirList->getPageStats();
//        $data = $this->dirList->getPageData();
//        //var_dump($data);
//        if (1 == $stats['total_elements']) 
//        {
//            throw new Redirect($data[0]->dir . $data[0]->defaultLanguage . '/');
//        }
//    }
//
//
//    protected function serializeToArrayImpl()
//    {
//        if (null === $this->dirList) {
//                $this->buildComicList();
//        }
//        $arr = array();
//        $arr['elements'] = array();
//        foreach($this->dirList->getPageData() as $el) {
//            $sub_arr = array(
//                'title' => $el->title,
//                'dir' => $el->dir,
//                'cover' => $el->cover,
//                'languages' => $el->languages,
//                'default_language' => $el->defaultLanguage,
//                'is_new' => $el->isNew,
//                'announce' => $el->announce
//            );
//
//            $arr['elements'][] = $sub_arr; 
//        }
//        $arr['stat'] = $this->dirList->getPageStats();
//        $arr['descr'] = array();
//        $arr['about'] = array();
//        
//        return $arr;
//    }
//
//    public function __construct($domain, $offset)
//    {
//            parent::__construct($domain, $offset, array());
//            $this->offset = intval($offset);
//    }
//}


//class Chapters_Model extends MultiLang_Model
//{
//    private $dirList = null;
//    private $offset = 0;
//    private $currentDescription = null;
//
//    protected function getCurrentLangImpl()
//    {
//        //$lang_dir = getParentDirName($this->getCurrentDir());
//        $lang_dir = $this->getCurrentDir();
//        return getCurrentDirName($lang_dir );
//    }
//
//
//    private function buildComicList()
//    {
//        $comic_dir = getParentDirName($this->getCurrentDir());
//        $this->currentDescription = new Book_Description($comic_dir);
//        //$this->currentDescription = new Book_Description($this->getCurrentDir());
//
//        $this->dirList = new DirList('Chapter_Description',$this->getCurrentDir(),$this->offset, CHAPTERS_ON_A_PAGE);
//        $stats = $this->dirList->getPageStats();
//
//        //check if we have about page
//        $haveAboutPage = false;
//        $data = $this->dirList->getPageData();
//        foreach($data as $record) {
//                if (isAboutDir($record->dir))	{
//                        $haveAboutPage = true;
//                }
//        }
//        //var_dump($this->currentDescription);
//        //var_dump($haveAboutPage);
//        //get subdirs num excluding "about"
//        $totalChapters = $stats['total_elements'] - (($haveAboutPage) ? 1 : 0);
//
//        if (1 == $totalChapters) 
//        {
//                throw new Redirect($data[0]->dir );
//        }
//    }
//
//
//    protected function serializeToArrayImpl()
//    {
//        if (null === $this->dirList) {
//                $this->buildComicList();
//        }
//        $arr = array();
//        $arr['elements'] = array();
//        //$arr['about'] = array();
//        foreach($this->dirList->getPageData() as $el) {
//                $sub_arr = array(
//                        'title' => $el->title,
//                        'dir' => $el->dir,
//                        'cover' => $el->cover,
//                        'is_new' => $el->isNew,
//                        'announce' => $el->announce
//                );
//                if (isAboutDir($el->dir)) {
//                        $arr['about'] = $sub_arr;
//                        continue;
//                }
//                $arr['elements'][] = $sub_arr; 
//        }
//        $arr['stat'] = $this->dirList->getPageStats();
//
//        return $arr;
//    }
//
//    public function __construct($domain, $offset, $arr)
//    {
//            parent::__construct($domain, $offset, $arr);
//            $this->offset = intval($offset);
//    }
//}

//class Images_Model extends MultiLang_Model
//{
//
//    private $fileList = null;
//    private $offset = 0;
//    private $currentDescription = null;
//    private $show_all = false;
//
//    protected function getCurrentLangImpl()
//    {
//            $lang_dir = getParentDirName($this->getCurrentDir());
//            return getCurrentDirName($lang_dir );
//    }
//
//    private function getCurrentChapter()
//    {
//            return getCurrentDirName($this->getCurrentDir());
//    }
//
//    private function buildComicList()
//    {
//            $comic_dir = getParentDirName( getParentDirName($this->getCurrentDir()) );
//            $this->currentDescription = new Book_Description($comic_dir);
//            $this->fileList = new FileList('Image_Description',$this->getCurrentDir(),$this->offset, ($this->show_all) ? 0 : IMAGES_ON_A_PAGE);
//    }
//
//
//    protected function serializeToArrayImpl()
//    {
//            if (null === $this->fileList) {
//                    $this->buildComicList();
//            }
//            //var_dump($this->currentDescription);
//            $arr['elements'] = array();
//            foreach($this->fileList->getPageData() as $el) {
//                    $sub_arr = array(
//                            'path' => $el->path,
//                            'filename' => $el->filename,
//                    );
//                    $arr['elements'][] = $sub_arr; 
//            }
//            $arr['stat'] = $this->fileList->getPageStats();
//            $arr['comic_description'] = array(
//                    'cover' => $this->currentDescription->cover,
//                    'dir' => $this->currentDescription->dir,
//                    'languages' => $this->currentDescription->languages,
//                    'title'=>$this->currentDescription->title, 
//                    'defaultLanguage' => $this->currentDescription->defaultLanguage,
//                    'isNew' => $this->currentDescription->isNew,
//                    'author' => $this->currentDescription->author,
//                    'booksCount' => $this->currentDescription->booksCount,
//                    'chaptersCount' => $this->currentDescription->chaptersCount,
//                    'authorLink' => $this->currentDescription->authorLink,
//                    'authorEmail' => $this->currentDescription->authorEmail,
//                    'announce' => $this->currentDescription->announce
//            );
//            $arr['lang'] = $this->getCurrentLanguage();
//            $arr['chapter'] = $this->getCurrentChapter();
//            return $arr;
//    }
//
//    public function __construct($domain, $offset, $arr, $show_all = false)
//    {
//            parent::__construct($domain, $offset, $arr);
//            $this->offset = intval($offset);
//            $this->show_all = !!$show_all;
//    }
//}


 
  
