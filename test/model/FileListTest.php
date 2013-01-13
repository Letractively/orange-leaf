<?php
require_once 'entities.php';


class ItemMock2 extends Item 
{
    public $val = null;
    
    function __construct($str, $id = null) {
        $this->val = $str;
        parent::__construct($id);
    }
}

class FileListTest extends PHPUnit_Framework_TestCase {
    private function makeDirs() {
        mkdir('fileList_dir');
        mkdir('fileList_dir/d1');
        touch('fileList_dir/f2.gif');
        touch('fileList_dir/f3.png');
        touch('fileList_dir/.f4');
        touch('fileList_dir/_f5');
        touch('fileList_dir/f6.jpg');
        touch('fileList_dir/f7.txt');

    }

    private function removeDirs() {
        @unlink('fileList_dir/f7.txt');
        @unlink('fileList_dir/f6.jpg');
        @unlink('fileList_dir/_f5');
        @unlink('fileList_dir/.f4');
        @unlink('fileList_dir/f3.png');
        @unlink('fileList_dir/f2.gif');
        @rmdir('fileList_dir/d1');
        @rmdir('fileList_dir');
    }
    
    protected function setUp() {
        $this->removeDirs();
        $this->makeDirs();
    }

    protected function tearDown() {
       $this->removeDirs(); 
    }
    
    /*
     * @covers FileList::getItems
     */
    public function testGetItems() {
        $correct = array(new ItemMock2('fileList_dir/f2.gif',0),new ItemMock2('fileList_dir/f3.png',1),new ItemMock2('fileList_dir/f6.jpg',2));
        $obj = new FileList('ItemMock2','fileList_dir/');
        $this->assertEquals($correct, $obj->getPageData());
    }
}
