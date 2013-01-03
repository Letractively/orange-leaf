<?php
/************************************
 *
 *	Comic Viewer, Entites processed 
 *  by model 
 *
 *	Ilnar Taichinov, 2012
 *	mail@ilna.ru
 *
 ***********************************/ 
require_once('common.php');
require_once('model/helpers.php');

class Item
{
    public $id = null;
}

class Comic_XML extends Item
{
	private $xml = false;
	
	public $title = '';
	public $defaultLanguage = DEFAULT_LANGUAGE;
	public $isNew = false;
	public $author = '';
	public $booksCount = 0;
	public $chaptersCount = 0;
	public $authorLink = '';
	public $authorEmail = '';
	public $announce = '';
	
	private function getTagContent($tag)
	{
		if (false !== $this->xml) {
			$records = $this->xml->getElementsByTagName($tag);
			if ( $records->length > 0 ) {
				return $records->item(0)->nodeValue ;
			}
		}
		return false;
	}
	
	public function __construct($path = '')
	{
		if ('' == $path) 
			return;
		$file = realpath($path);
		if (false === $file || !file_exists($file))
			return;
			//throw new Error404("Description file $path is not found.");
			
		$this->xml = new DOMDocument();
		$res = $this->xml->load($path);
		if (false === $res)
			return;
		
		if ( false !== ($tmp=$this->getTagContent('title')) ) 
			$this->title = $tmp; 
			
		if ( false !== ($tmp=$this->getTagContent('isNew')) ) 
			$this->isNew = !!$tmp; //to bool
		
		if ( false !== ($tmp=$this->getTagContent('defaultLanguage')) ) 
			$this->defaultLanguage = $tmp; 
		
		if ( false !== ($tmp=$this->getTagContent('booksCount')) ) 
			$this->booksCount = $tmp; 
		
		if ( false !== ($tmp=$this->getTagContent('chaptersCount')) ) 
			$this->chaptersCount = $tmp; 
		
		if ( false !== ($tmp=$this->getTagContent('author')) ) 
			$this->author = $tmp;
		
		if ( false !== ($tmp=$this->getTagContent('authorLink')) ) 
			$this->authorLink = $tmp;

		if ( false !== ($tmp=$this->getTagContent('authorEmail')) ) 
			$this->authorEmail = $tmp; 		

		if ( false !== ($tmp=$this->getTagContent('announce')) ) 
			$this->announce = $tmp; 				
		
	}
}
 
 /*************************************
  *
  *  Class representing a Book dir 
  *  in which there are languages and  
  *  chapters. 
  *
  *************************************/
class Book_Description extends Comic_XML
{
	public $cover = DEFAULT_COVER;
	//public $path = '';
	public $dir = '';
	public $languages = array();
        
        public $customCss = '';
        public $customBg = '';
        public $customFavIcon = '';
	
	private $extensions = 0;
	
	private function searchCover($cover_dir, $filename = COVER_FILE_NAME)
	{
//		if (!is_dir($cover_dir)) return false;
//		
//		foreach(g_ImageFileExtensions() as $extension) {
//			$cover = $cover_dir . $filename . '.' . $extension;
//			if ( file_exists($cover) ) {
//				$this->cover = $cover;
//				return true;
//			}
//		}
//                return false;
            $cover = $this->imageExists($cover_dir, $filename);
            if ( null === $cover) 
		return false;
            $this->cover = $cover;
            return true;
	}
        
        private function imageExists($dir, $filename)
        {
            if (!$filename || !is_dir($dir)) 
               return null;
		
            foreach(g_ImageFileExtensions() as $extension) {
                $cover = $dir . $filename . '.' . $extension;
                if ( file_exists($cover) ) 
                    return $cover;
            } 
            return null;
        }
	
	function __construct($current_dir)
	{
		if (!file_exists($current_dir)) 
			return;
		
                parent::__construct($current_dir . DESCRIPTION_FILE);
		
                //get dir name
		$current_dir_name = getCurrentDirName($current_dir);
		$this->dir = $current_dir_name . '/';
		
                //change title if needed
		if ('' === $this->title) {
			$current_dir_name = str_replace('_', ' ', $current_dir_name);
			$this->title = ucwords($current_dir_name);
		}
                
                //check if custom CSS, favicon or BG are available
                if (file_exists($current_dir . STYLE_FILE_NAME))
                        $this->customCss = $current_dir . STYLE_FILE_NAME;
                
                if (file_exists($current_dir . FAVICON_FILE_NAME))
                        $this->customFavIcon = $current_dir . FAVICON_FILE_NAME;
		
                $bg_image = $this->imageExists($current_dir, BACKGROUND_FILE_NAME);
                if ($bg_image)
                        $this->customBg = $bg_image;
		
                //populate languages
		$dirs = getSubdirs($current_dir, true);
		$this->languages = $dirs;
		
		//look for the cover in the current directory
		$cover_dir = $current_dir;
		$this->searchCover($cover_dir);
			
		if (count($this->languages) <= 0) 
			return;
		
		if ( false === array_search($this->defaultLanguage, $this->languages) )
			$this->defaultLanguage = $this->languages[0];
		
		//desperately try to find the cover in default language dir
		if ($this->cover != DEFAULT_COVER) 
			return;
			
		$cover_dir =$current_dir . $this->defaultLanguage . '/';
		if ($this->searchCover($cover_dir))
			return;
		
	}
}
 
 /*************************************
  *
  *  Class representing a Chapter dir 
  *  in which there are chapters. 
  *
  *************************************/
class Chapter_Description extends Book_Description
{
	function __construct($path)
	{
		if (!is_dir($path)) return;
		parent::__construct($path);
		
		//we don't need languages here, reset
		$this->languages = array();
		$this->defaultLanguage = DEFAULT_LANGUAGE;
		
		//we still don't know what to do with the cover.
		if (DEFAULT_COVER === $this->cover)
		{
			$all_files = scandir($path);
			foreach ($all_files as $file) {
				$full_path = $path . $file;
				
				//skip_dirs
				if (is_dir($full_path))
					continue;
					
				//skip hidden
				if ( '.' == $file[0] || '_' == $file[0] )
					continue;
				
				//check if file is image
				$path_parts = pathinfo($file);
				if (empty($path_parts['extension']))
					continue;
					
				foreach (g_ImageFileExtensions() as $ext) {
					if ($path_parts['extension'] == $ext) {
						$this->cover = $full_path;
						break;
					}						
				}
				
				//if we've already found a cover
				if (DEFAULT_COVER !== $this->cover) 
					break;
			}
		}
		
	}
}


class Image_Description
{
	public $path = '';
	public $filename = '';
	function __construct($path='')
	{
		$this->path = $path;
		$path_parts = pathinfo($path);
		if (!empty($path_parts['basename'])) {
			$this->filename = $path_parts['basename'];
		}
	}
}
 
 
 /*************************************
 *
 *  Class which builds a list of 
 *  entities of particular type.
 * 	See DirList::element_class member. 
 *
 *************************************/
abstract class ItemList
{
	private $offset = 0;
	private $elements_on_page = 0;
	private $total_elements = 0;
	private $dir = '';
	private $data = null;
	private $element_class = '';
	
	abstract protected function getItems($dir) ;
	
	private function browseDir()
	{
		$data = array();
		$dir_num = 0;
		$subdirs = $this->getItems($this->dir);
		$last_el = $this->offset+$this->elements_on_page;
		foreach ( $subdirs as $comic_dir ) {
			if ($dir_num >= $this->offset 
				&& (0==$this->elements_on_page || $dir_num < $last_el)  ) 
			{
                                $obj = new $this->element_class($comic_dir);
                                $obj->id = $dir_num;
				$data[] = $obj;
			}
			$dir_num++;
		}
		/*if ($this->offset >= $dir_num)
			throw new Error404('Too big offset.');*/
		$this->total_elements = $dir_num;
		$this->data = $data;
	}
	
	
	function __construct($class, $dir, $offset = 0, $elements_on_page = 0)
	{
		$this->offset = $offset;
		$this->dir = $dir;
		$this->elements_on_page = $elements_on_page;
		$this->element_class = $class;
		$this->browseDir();
	}
	
	public function getPageStats()
	{
		return array(
			'total_elements' => $this->total_elements,
			'offset' => $this->offset,
			'elements_on_page' => $this->elements_on_page
		);
	}
	
	public function getPageData()
	{
		return $this->data;
	}
 }
 
class DirList extends ItemList
{
	protected function getItems($dir)
	{
		return getSubdirs($dir);
	}
 }
 
class FileList extends ItemList
{
	protected function getItems($path)
	{
		$res = array();
		if (!file_exists($path))
			return $res;
			
		$all_files = scandir($path);
		foreach ($all_files as $file) {
			$full_path = $path . $file;
			
			//skip_dirs
			if (is_dir($full_path))
				continue;
				
			//skip hidden
			if ( '.' == $file[0] || '_' == $file[0] )
				continue;
				
			//check if file is image
			$path_parts = pathinfo($file);
			if (empty($path_parts['extension']))
				continue;
				
			foreach (g_ImageFileExtensions() as $ext) {
				if ($path_parts['extension'] == $ext) {
					$res[] = $full_path;
				}						
			}
		}
		return $res;
	}
}