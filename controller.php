<?
/************************************
 *
 *	Comic Viewer, Controller Classes
 *
 *	Ilnar Taichinov, 2012
 *	mail@ilna.ru
 *
 ***********************************/ 
require_once(APPLICATION_ROOT . 'controller/link.php');

class Request
{
	public $domain = '';
	public $dir = '';
	public $file = '';
	public $query = '';
} 

class Comic_Controller
{
	private $req;		//Request - HTTP request data
	private $model = null;
	private $view = null;
	
	private function parseServerStrings()
	{
		$req = new Request;
		$req->domain = $_SERVER['HTTP_HOST'];
		$req->query = urldecode($_SERVER['QUERY_STRING']);
		
		$path = urldecode(strtok( $_SERVER['REQUEST_URI'], '?' ));
		$last_slash_pos = strripos($path, '/');
		if (FALSE === $last_slash_pos) {
			$req->file = $path;
		} else {	
			//$len = count($path);
			$req->file = mb_substr($path, $last_slash_pos + 1);
			$req->dir = mb_substr($path, 0, $last_slash_pos+1);
		}
		
		$this->req = $req;
	}
	
	private function sanitizeServerStrings()
	{
		$this->req->domain = htmlspecialchars($this->req->domain);
		$this->req->dir = htmlspecialchars($this->req->dir);
		$this->req->file = htmlspecialchars($this->req->file);
		$this->req->query = htmlspecialchars($this->req->query);
	}
	
	/* checks if URL is valid: if SUBDIR is presented */
	private function explodeDir()
	{
		$raw_url_parts = explode('/',$this->req->dir);
                //bugfix, error 404 in case of empty subdir
		$subdir_parts = ( '' === SUBDIR) ? array() : explode('/',SUBDIR);
		$url_parts = array();
		$subdir_index = 0;
		foreach ($raw_url_parts as $part) {
			if ( '' == $part ) 
				continue;
				
			if ( isset($subdir_parts[$subdir_index]) 
					&& $subdir_parts[$subdir_index] == $part )
			{
				$subdir_index++;
				continue;
			}
			$url_parts[]=$part;
		}
		if ($subdir_index < count($subdir_parts))
			throw new Error404(SUBDIR .' not found in the URL.');
		
		return $url_parts;
	}
        
        private function onlyNumericOffset() 
        {
            if ( 0 == intval($this->req->file) && !!$this->req->file)
                throw new Error404('Wrong offset');
        }
	
	private function chooseModelAndView() 
	{
		$url = $this->explodeDir();
                
                if (SINGLE_COMIC_MODE && SINGLE_COMIC_DIR) {
                    array_unshift($url,SINGLE_COMIC_DIR);
                }
                
		switch ( count($url) ) {
                    case 0:
                        //Index, outputs books 
                        $this->onlyNumericOffset();
			$this->model = new Index_Model($this->req->domain, $this->req->file);
                        $link = new Link($this->req->dir);
			$this->view = new Index_View($link);
                        break;
                        
                    case 1:
                        //Book, outputs languages
                        $this->onlyNumericOffset();
			$this->model = new Book_Model($this->req->domain, $url);
                        break;
                        
                    case 2:
                        //Language, outputs chapters
                        $this->onlyNumericOffset();
			$this->model = new Chapters_Model($this->req->domain, $this->req->file, $url);
                        $link = new Link($this->req->dir);
			$this->view = new Chapters_View($link);
                        break;
                        
                    case 3:
                        //Chapter, outputs ...
//                        if ($this->req->file === strval(intval($this->req->file)) //if number
//				|| !(0 == intval($this->req->file) && !!$this->req->file) ) // and not null
//			{
//                            //... a list of images in chapter
//                            $this->model = new Images_Model($this->req->domain, $this->req->file, $url);
//                            $link = new Link($this->req->dir);
//                            $this->view = new Chapters_View($link);
//			
//                        } else if (USE_ORIGINAL_NAMES) {
//                            //... an image
//                            $this->model = new The_Image_Model($this->req->domain, $this->req->file, $url);
//                            $link = new Link($this->req->dir);
//                            $this->view = new Image_View($link);
//                            
//			} else {       
//                            throw new Error404('Original names are turned off.');
//                        }
                        
                        if ( '' === $this->req->file ) {
                            //... a list of images
                            $page_id = '';
                            if ($this->req->query) {
                                $q = array();
                                parse_str($this->req->query, $q);
                                if ( isset($q['page']) && 0 !== intval($q['page']) ) {
                                    $page_id = intval($q['page']);
                                    if (0 === $page_id) $page_id = '';
                                }
                            }
                            $this->model = new Images_Model($this->req->domain, $page_id, $url);
                            $this->view = new Chapters_View( new Link($this->req->dir) );
                        } else {
                           //... an Image 
                           if (USE_ORIGINAL_NAMES) {
                               $this->model = new The_Image_Model($this->req->domain, $this->req->file, $url);
                           } else {
                               $this->onlyNumericOffset();
                               array_push($url, $this->req->file);
                               $this->model = new The_Num_Image_Model($this->req->domain, $url); 
                           }
                           $this->view = new Image_View( new Link($this->req->dir) );
                        }
                        
                        break;
                        
                    case 4:
                        //Image
                        if (USE_ORIGINAL_NAMES) {
                          throw new Error404('Original names are turned on. Path too long.');  
                        }
                        $this->model = new The_Num_Image_Model($this->req->domain, $url);
                        $link = new Link($this->req->dir);
                        $this->view = new Image_View($link);
                        break;
                    
                    default:
                        throw new Error404('Model not found.');
                }
	}
	
	public function __construct()
	{
		$this->parseServerStrings();
		$this->sanitizeServerStrings();
	}
	
	public function acquireModel()
	{
		if (null == $this->model)
			$this->chooseModelAndView();
		return $this->model;
	}
	
	public function acquireView()
	{
		if (null == $this->view)
			$this->chooseModelAndView();
		return $this->view;
	}
	
}