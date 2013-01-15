<?php
require_once 'entities.php';

class DirListTest extends PHPUnit_Framework_TestCase {

    private function makeDirs() {
        mkdir('dirList_dir');
        mkdir('dirList_dir/p1');
        mkdir('dirList_dir/p2');
        mkdir('dirList_dir/p3');
        mkdir('dirList_dir/.p4');
        mkdir('dirList_dir/_p5');

    }

    private function removeDirs() {
        @rmdir('dirList_dir/_p5');
        @rmdir('dirList_dir/.p4');
        @rmdir('dirList_dir/p3');
        @rmdir('dirList_dir/p2');
        @rmdir('dirList_dir/p1');
        @rmdir('dirList_dir');
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
        $correct = array(
            new Item(0,'dirList_dir/p1/'),
            new Item(1,'dirList_dir/p2/'),
            new Item(2,'dirList_dir/p3/')
        );
        $obj = new DirList('Item','dirList_dir/');
        $this->assertEquals($correct, $obj->getPageData());
    }
}
