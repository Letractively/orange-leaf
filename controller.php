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
	
	private function chooseModelAndView() 
	{
		$url = $this->explodeDir();
		
		
		if (0 == count($url) /*&& '' == $this->req->file*/) {
			if ( 0 == intval($this->req->file) && !!$this->req->file)
				throw new Error404('Wrong offset');
			$this->model = new Index_Model($this->req->domain, $this->req->file);
			$this->view = new Index_View($this->req->dir);
			return;
		}
		
		if (1 == count($url)) {
			if ( 0 == intval($this->req->file) && !!$this->req->file)
				throw new Error404('Wrong offset');
			$this->model = new Book_Model($this->req->domain, $url);
			$this->view = null;
			return;
		}
		
		if (2 == count($url)) {
			if ( 0 == intval($this->req->file) && !!$this->req->file)
				throw new Error404('Wrong offset');
			$this->model = new Chapters_Model($this->req->domain, $this->req->file, $url);
			$this->view = new Chapters_View($this->req->dir);
			return;
		}
		
		if (3 == count($url)) {
			if ($this->req->file === strval(intval($this->req->file)) 
				|| !(0 == intval($this->req->file) && !!$this->req->file) ) 
			{
				$this->model = new Images_Model($this->req->domain, $this->req->file, $url);
				$this->view = new Chapters_View($this->req->dir);
			} else {
				$this->model = new The_Image_Model($this->req->domain, $this->req->file, $url);
				$this->view = new Image_View($this->req->dir);
			}
			return;
		}
		
		//return new Story_Model($this->req, $url);
		
		throw new Error404('Model not found.');
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