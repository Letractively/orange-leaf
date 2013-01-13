<?php
require_once 'entities.php';

class ItemListTest extends PHPUnit_Framework_TestCase {
    
    protected $obj;
    protected $list;
    
    private function makeDirs() {
        mkdir('itemList_dir');
//        touch('itemList_dir/');
    }

    private function removeDirs() {
        @rmdir('itemList_dir');
    }
    
    
    protected function setUp() {
        $this->removeDirs();
        $this->makeDirs();
        
        $params = array('Item','itemList_dir/');
        $this->list = array(new Item(0), new Item(1), new Item(2));
        $list = array(0,1,2);
        $this->obj = $this->getMockForAbstractClass('ItemList',$params,'ItemsListMock',false);
        $this->obj->expects($this->once())
            ->method('getItems')
            ->with($this->equalTo('itemList_dir/'))
            ->will($this->returnValue( $this->list ));
    }

    protected function tearDown() {
        $this->removeDirs();
    }

    /**
     * @covers ItemList::browseDir
     * @covers ItemList::__construct
     * 
     */
    public function testCtor() {
        $this->obj->__construct('Item','itemList_dir/');
    }
    
    /**
     * @covers ItemList::getPageStats
     * @covers ItemList::browseDir
     * 
     */
    public function testGetPageStats() {
        $this->obj->__construct('Item','itemList_dir/');
        $res = $this->obj->getPageStats();
        $this->assertEquals(count($this->list), $res['total_elements']);
        $this->assertEquals(0, $res['offset']);
        $this->assertEquals(0, $res['elements_on_page']);
    }

    /**
     * @covers ItemList::getPageData
     * 
     * 
     */
    public function testGetPageData() {
        $this->obj->__construct('Item','itemList_dir/');
        $res = $this->obj->getPageData();
        $this->assertEquals($this->list, $res);
    }

}
