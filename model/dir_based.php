<?php
abstract class DirBased_Model
{
    private $current_dir = '';
    private $file = '';

    abstract protected function serializeToArrayImpl();

    private function buildCachePath()
    {
            return $this->getCurrentDir() . '.' 
                    . (($this->file) ? $this->file : 'index') 
                    . CACHE_FILE;
    }

    public function __construct($domain, $file, $url_arr)
    {
            $path = ($domain) ? $domain . '/' : '';
            if (count($url_arr))
                    $path .= implode($url_arr, '/') . '/';

            if (!is_dir($path))
                    throw new Error404('Path not found.');

            $this->current_dir = $path;
            $this->file = $file;
    }

    public function getCurrentDir() { return $this->current_dir; }

    public function getCachePath() { return $this->buildCachePath(); }

    public function serializeToArray() { return $this->serializeToArrayImpl(); }
}


abstract class MultiLang_Model extends DirBased_Model
{
    abstract protected function getCurrentLangImpl();
    abstract protected function linksToOtherLangs($langs);
    
    public function getCurrentLanguage()
    {
        return $this->getCurrentLangImpl();
    }
}

abstract class Node_Model extends MultiLang_Model
{
    private $offset         = 0;
    private $list           = null;
    private $description    = null;
    private $about          = null;
    private $extra          = null;
    
    abstract protected function getChildrenImpl($offset);
    abstract protected function getDescriptionImpl($offset);
    
    /* virtual */ protected function getAboutImpl($offset) {
        $dir = $this->getCurrentDir() . ABOUT_DIR_NAME;
        return new Book_Description($dir);
    }
    
    /* virtual */ protected function serializeDecorator($arr) {
        return $arr;
    }
    
    /* virtual */ protected function getExtraImpl($offset) {
        return array();
    }

    protected function serializeToArrayImpl()
    {
        if (null === $this->list)
                $this->list = $this->getChildrenImpl($this->offset);
        
        if (null === $this->description)
                $this->description = $this->getDescriptionImpl($this->offset);
        
        if (null === $this->about)
                $this->about = $this->getAboutImpl($this->offset);
        
        if (null === $this->extra)
                $this->extra = $this->getExtraImpl($this->offset);
        
        $elements = array();
        foreach($this->list->getPageData() as $el) 
                $elements[] = get_object_vars($el);
        
        $arr = array();
        $arr['elements'] = $elements;
        $arr['stat'] = $this->list->getPageStats();
        $arr['descr'] = get_object_vars($this->description);
        $arr['about'] = get_object_vars($this->about);
        $arr['lang'] = $this->getCurrentLanguage();
        $arr['extra'] = $this->extra;
        //TODO: throw exception if we already have such field
        $arr['extra']['otherLangs'] = $this->linksToOtherLangs($this->description->languages);
        return $this->serializeDecorator($arr);
    }
    
    function __construct($domain, $file, $url_arr) {
        parent::__construct($domain, $file, $url_arr);
        $this->offset = $file;
    }
}