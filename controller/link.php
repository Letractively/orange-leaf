<?php
abstract class Link
{
    private $cur_dir = null;    // path/to/dir/
    private $parent_dir = null; // path/to/
 
    protected abstract function originalNameLinkImpl($obj);
    protected abstract function alteredNameLinkImpl($obj);
            
    function __construct($dir) {
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
    
    public function href(/* Item */ $obj, $use_orig = USE_ORIGINAL_NAMES)
    {
        if (!isset($obj['id'])) {
            throw new Error500('Link::href error building link, corrupted object provided');
        }
        
        if ($use_orig) {
            $res =  $this->originalNameLinkImpl($obj);
            if (null !== $res) {
                return $res;
            }
        }  
        return $this->alteredNameLinkImpl($obj);
    }
}
