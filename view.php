<?
/************************************
 *
 *	Comic Viewer, Model Classes
 *
 *	Ilnar Taichinov, 2012
 *	mail@ilna.ru
 *
 ***********************************/ 
 
//require_once('view/htmltools.php');
require_once('view/comic_view.php');
require_once('view/helpers.php');
 
class Image_View extends Comic_View
{
    protected function buildHTMLImpl($data, $link)
    {	
        $o = &$data;
        $l = &$link;
        $buttons = getImagePagingButtons($o);
        $page = array( 
            'number' => $buttons['current_num'],
            'total' => $o->stat('total_elements')
        ); 
        
        $o->addExtra('buttons', $buttons);
        $o->addExtra('page', $page);
        include IMAGE_PAGE_TEMPLATE;
    }
}
 
class Chapters_View extends Comic_View
{
    protected function buildHTMLImpl($data, $link)
    {	
        $o = &$data;
        $l = &$link;
        $o->addExtra('buttons', getPagingButtons($o->stat()));
        if (USE_AJAX_CHAPTER) $o->addExtra('ajax', true);
        
        include CHAPTERS_TEMPLATE;
    }
}
 
 
class Index_View extends Comic_View
{
    protected function buildHTMLImpl(/*HTMLProvider*/ $data, /* Link */ $link)
    {	
        $o = &$data;
        $l = &$link;
        $o->addExtra('buttons', getPagingButtons($o->stat()));
        
        include INDEX_TEMPLATE;
    }
}
 
