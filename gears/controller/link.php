<?php
require_once APPLICATION_ROOT . 'model/entities.php';
class Link
{
    private $cur_dir = null;    // path/to/dir/
    private $parent_dir = null; // path/to/
            
    function __construct($dir) {
        if ('' == $dir || '/' == $dir) {
            $this->cur_dir = '';
            $this->parent_dir = '';
            return;
        }
        $this->cur_dir = dirname($dir.'hook').'/';
        if ('./' == $this->cur_dir) 
            $this->cur_dir = '';
        $this->parent_dir = $this->getParentDirInternal($dir);
    }
    
    private function getParentDirInternal($path)
    {
        //$path = $this->getCurrentDir(); // a/b/c/
        if (!$path) return null;
        $parts = explode('/',$path);    // [ a, b, c, _ ]
        if (count($parts) <= 2) {
            return '';
        }
        array_pop($parts);              // [ a, b, c ]
        array_pop($parts);              // [ a, b ]
        $path = implode('/',$parts);    // a/b
        $path .= '/';                   // a/b/
        return $path;
    }
    
    public function getParentDir()
    {
        return $this->parent_dir;
    }
    
    public function getCurrentDir()
    {
        return $this->cur_dir;
    }
    
    public function href($obj, $single_comic = SINGLE_COMIC_MODE, $use_orig = USE_ORIGINAL_NAMES)
    {   
        //it is a paging object
        if (is_array($obj) && isset($obj['page_id']) ) {
            return $this->getCurrentDir() . '?page=' . $obj['page_id'];
        }
        
        //it is an Item
        $id = $realPath = null;
        
        if (is_array($obj) && isset($obj['id']) && isset($obj['realPath'])) {
            $id = $obj['id'];
            $realPath = $obj['realPath'];
            
        } else if ( $obj instanceof Item ) {
            $id = $obj->id;
            $realPath = $obj->realPath;
            
        } else {
            throw new Error500('Link::href error building link, wrong object provided');
        }
        
        $res = '';
        $dirs = explode('/', $realPath);    // [domain, comic, language, chapter, filename]
        if ( empty($dirs[1]) ) {
            throw new Error500('Link::href error building link, corrupted Item::realPath');
        } 
        if (!$single_comic) { 
            $res = $dirs[1] . '/';          // comic/
        }
        
        if ( !empty($dirs[2]) ) { 
            $res .= $dirs[2] . '/';         // [comic/]language/
        }
        
        if ( !empty($dirs[3]) ) { 
            $res .= $dirs[3] . '/';         // [comic/]language/chapter/
        }
        
        if ( !empty($dirs[4]) && '' !== $dirs[4]) { 
            if ($use_orig) {
                $res .= $dirs[4] ;         // [comic/]language/chapter/filename
            } else {
                $res .= $id /*. '/'*/;              // [comic/]language/chapter/id
            }
        }
        
        return $res;
    }
}
