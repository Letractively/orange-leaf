<?
/************************************
 *
 *	Comic Viewer, Controller Classes
 *
 *	Ilnar Taichinov, 2012
 *	mail@ilna.ru
 *
 ***********************************/ 

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
			$len = count($path);
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
		$subdir_parts = explode('/',SUBDIR);
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
		
		switch ( count($url) ) {
                    case 0:
                        $this->onlyNumericOffset();
			$this->model = new Index_Model($this->req->domain, $this->req->file);
			$this->view = new Index_View($this->req->dir);
                        break;
                        
                    case 1:
                        $this->onlyNumericOffset();
			$this->model = new Book_Model($this->req->domain, $url);
                        break;
                        
                    case 2:
                        $this->onlyNumericOffset();
			$this->model = new Chapters_Model($this->req->domain, $this->req->file, $url);
			$this->view = new Chapters_View($this->req->dir);
                        break;
                        
                    case 3:
                        if ($this->req->file === strval(intval($this->req->file)) //if number
				|| !(0 == intval($this->req->file) && !!$this->req->file) ) 
			{
                            $this->model = new Images_Model($this->req->domain, $this->req->file, $url);
                            $this->view = new Chapters_View($this->req->dir);
			} else if (USE_ORIGINAL_NAMES) {
                            $this->model = new The_Image_Model($this->req->domain, $this->req->file, $url);
                            $this->view = new Image_View($this->req->dir);
			} else {       
                            throw new Error404('Original names are turned off.');
                        }
                        break;
                        
                    case 4:
                        if (USE_ORIGINAL_NAMES) {
                          throw new Error404('Original names are turned on. Path too long.');  
                        }
                        $this->model = new The_Num_Image_Model($this->req->domain, $url);
                        $this->view = new Image_View($this->req->dir);
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