<?php

class SafeGetter
{
    public function safeGet($data)
    {
        $numargs = func_num_args();
//        if ($numargs <= 0) {
//            return null;
//        }
        $arg_list = func_get_args();
        $arr = $arg_list[0];
        if (!is_array($arr)) {
            return $data;
        }
        for ($i = 1; $i < $numargs; $i++) {
            $current_key = $arg_list[$i]; 
            
            //fail!
            if ( !isset($arr[$current_key]) ) {
//                if (IS_DEBUGGING) 
//                    return VALUE_NOT_FOUND;
                return null;
            }
            $arr = $arr[$current_key];
        }
        return $arr;
    }
    
    public function safeEcho($data)
    {
        $func = array($this,'safeGet');
        $params = func_get_args();
        echo call_user_func_array($func,$params);
    }
    
}

class DataProvider extends SafeGetter
{
    private $descr = array();
    private $elements = array();
    private $element_id = 0;
    private $stat = array();
    private $about = array();
    private $lang = DEFAULT_LANGUAGE;
    private $extra = array();

    public function addExtra($var,$val) {
        $this->extra[$var]=$val;
        return true;
    }

    public function lang() {
        return $this->lang;
    }
    
    public function descr($str = null) {
        if (null === $str) 
            return $this->descr;
     
        return $this->safeGet($this->descr,$str);
    }
    
    public function extra($str = null) {
        if (null === $str) 
            return $this->extra;
        
        return $this->safeGet($this->extra,$str);
    }
    
    public function hasElements(){
        return count($this->elements) > 0;
    }
    
    public function nextElement($reset = false) {
        if ($reset) $this->element_id = 0;
        $res = null;
        if (isset($this->elements[$this->element_id])) {
            $res = $this->elements[$this->element_id]; 
            $this->element_id++;
        } else {
            $this->element_id = 0;
        }
        
        return $res;
    }
    
    public function stat($str = null) {
        if (null === $str) 
            return $this->stat;
        return $this->safeGet($this->stat,$str);
    }
    
    public function elements($id = null) {
        if (null === $id) 
            return $this->elements;
        return $this->safeGet($this->elements,$id);
    }
    
    public function about($str = null) {
        if (null === $str) 
            return $this->about;
        
        return $this->safeGet($this->about,$str);
    }
    
    public function __construct($arr) {
        if (!isset($arr['descr'])) 
            throw new Error500('Wrong input, "descr" missing');
        $this->descr = $arr['descr'];
        
        if (!isset($arr['elements'])) 
            throw new Error500('Wrong input, "elements" missing');
        $this->elements = $arr['elements'];
        
        if (!isset($arr['stat'])) 
            throw new Error500('Wrong input, "stat" missing');
        $this->stat = $arr['stat'];
        
        if (!isset($arr['about'])) 
            throw new Error500('Wrong input, "about" missing');
        $this->about = $arr['about'];
        
        if (!isset($arr['lang'])) 
            throw new Error500('Wrong input, "lang" missing');
        $this->lang = $arr['lang'];
        
        if (isset($arr['extra']) && is_array($arr['extra'])) 
            $this->extra = $arr['extra'];
    }
    
}

class HTMLProvider extends DataProvider
{
    //for mini-caching
    private $cache = array();
    private $current_dir = '';
    
    public function title() {
        if ( !isset($this->cache['title']) ) {
            $this->cache['title'] = DEFAULT_TITLE;
            if ( $this->descr('title') ) 
                $this->cache['title'] = $this->descr('title');
        }
        return $this->cache['title'];
    }
    
    public function homeUrl() {
        if ( !isset($this->cache['home_url']) ) {
            $this->cache['home_url'] = ( dirname($_SERVER['SCRIPT_NAME']) ) ? dirname($_SERVER['SCRIPT_NAME']) . '/' : '';
        }
        return $this->cache['home_url'];
    }
    
    public function domain($with_proto = true) {
        $res = $with_proto ? 
            (!empty($_SERVER['HTTPS']) ?
                 'https://' : 'http://' ) 
            : '';
        if (empty($_SERVER['HTTP_HOST']))
            throw new Error500('HTTP_HOST is empty!!!');
        $res .= $_SERVER['HTTP_HOST'];
        return $res; 
    }
    
    public function translate($str) {
        return g_translate($this->lang(), $str);
    }
    
    public function thumb_abs($path, $width = 0, $height = 0)
    {
        $src = TUMBNAIL_SCRIPT . '?src=' . $path;
        if ( ($width = intval($width)) ) 
            $src .= '&w=' . $width;
        
        if ( ($height = intval($height)) ) 
            $src .= '&h=' . $height;
        
        return $src;
    }

    public function thumb($path, $width = 0, $height = 0)
    {
        $width = intval($width);
        if ($width <= 0)  $width = THUMBNAIL_WIDTH;
        
        $new_path = $this->homeUrl() . $path;
        return $this->thumb_abs($new_path, $width, $height);
    }
    
    public function currentDir() {
        return $this->current_dir;
    }
    
    private function generateId($type, $showBook = false, $showPage = false) 
    {
        $type = strtolower($type);
        $res = '';
        switch ($type) {
            case 'vk':
                $res = 'cv_' . rtrim($this->descr('dir'),'/') ;
                if (!$showBook) {
                    break;
                }
                $res .=  '_' . $this->lang() . 
                         '_' . $this->extra('chapter') . '_';
                
                if ($showPage) {
                    if (USE_ORIGINAL_NAMES) 
                        $res .= $this->safeGet($this->extra('image'),'filename') ;
                    else 
                        $res .= $this->safeGet($this->extra('image'),'num') ;
                }
                    
                break;
            
            case 'url':
            case 'fb':
                $res = $this->domain(); 
                
                if ($showBook) {
                    $res .= $this->currentDir();
                } else {
                    $res .= '/';
                    if (SUBDIR) {
                        $res .= SUBDIR . '/';
                    }
                    if (!SINGLE_COMIC_MODE) {
                          $res .= $this->descr('dir');
                    }
                    break;
                }
                       
                       
                if ($showPage) {
                    if (USE_ORIGINAL_NAMES)
                        $res .= $this->safeGet($this->extra('image'),'filename') ;
                    else 
                        $res .= $this->safeGet($this->extra('image'),'num') ;
                }
                break;
        }
        return $res;
    }
    
    public function generatePageID($type = null) {
        return $this->generateId($type,true,true);
    }

    public function generateChapterID($type = null) {
        return $this->generateId($type,true);
    }
    
    public function generateBookID($type = null) {
        return $this->generateId($type);
    }

    function __construct($str,$arr) {
        parent::__construct($arr);
        $this->current_dir = $str;
    }
}

abstract class Comic_View
{
    private $html = null;
    private $link = null;
    private $current_url = '';

    abstract protected function buildHTMLImpl($data, $link);

    private function buildHTML($site_tree = array())
    {
        $data = new HTMLProvider($this->current_url, $site_tree); 
        ob_start();
        $this->buildHTMLImpl($data, $this->link);
        $this->html = ob_get_clean();

    }

    function __construct($link)
    {
        $this->link = $link;
        $this->current_url = $link->getCurrentDir();
    }

    public function cache($path, $site_arr)
    {	
        $this->buildHTML($site_arr);
        file_put_contents($path, $this->html);
    }

    public function hasCache($path)
    {
        return (true == file_exists($path));
    }

    public function displayFromCache($path)
    {
        print(file_get_contents($path));
    }


    public function display($site_tree)
    {
        $this->buildHTML($site_tree);
        echo $this->html;
    }
}
