<?php
require_once 'link.php';
require_once 'common.php';

class LinkTest extends PHPUnit_Framework_TestCase {
    
    protected $path1 = 'test/path/';
    protected $path2 = 'test/path/file';
    protected $path3 = 'test';
    protected $obj1;
    protected $obj2;
    protected $obj3;

    protected function setUp() {
        $this->obj1 = new Link($this->path1);
        $this->obj2 = new Link($this->path2);
        $this->obj3 = new Link($this->path3);
    }

    protected function tearDown() {
        
    }
    
    /**
     * @covers Link::__construct
     * 
     */
    public function testCtor() {
        $this->obj1 = new Link($this->path1);
    }

    /**
     * @covers Link::getParentDir
     * @covers Link::getParentDirInternal
     */
    public function testGetParentDir() {
        $this->assertEquals('test/',$this->obj1->getParentDir());
        $this->assertEquals('test/',$this->obj2->getParentDir());
        $this->assertEquals('',$this->obj3->getParentDir());
    }

    /**
     * @covers Link::getCurrentDir
     * 
     */
    public function testGetCurrentDir() {
        $this->assertEquals('test/path/',$this->obj1->getCurrentDir());
        $this->assertEquals('test/path/',$this->obj2->getCurrentDir());
        $this->assertEquals('',$this->obj3->getCurrentDir());
       
    }
    
    
    /**
     * @covers Link::href
     * @expectedException Error500
     * 
     */
    public function testHrefErr1() {
        $obj = '';
        $this->obj1->href($obj);
    }
    
    /**
     * @covers Link::href
     * @expectedException Error500
     * 
     */
    public function testHrefErr2() {
        $obj = array('id'=>123);
        $this->obj1->href($obj);
    }
    
    /**
     * @covers Link::href
     * @expectedException Error500
     * 
     */
    public function testHrefErr3() {
        $testItem = array(
            'id' => 123, 
            'realPath'=>'file.ext'
        );
        $this->obj1->href($testItem, false, false);
    }

    /**
     * @covers Link::href
     * 
     */
    public function testHref() {
        $testItem = array(
            'id' => 123, 
            'realPath'=>'example.com/com/ln/ch/file.ext'
        );
        $this->assertEquals( 'com/ln/ch/123/', 
                $this->obj1->href($testItem, false, false) );
        
        $this->assertEquals( 'com/ln/ch/file.ext', 
                $this->obj1->href($testItem, false, true) );
        
        $this->assertEquals( 'ln/ch/file.ext', 
                $this->obj1->href($testItem, true, true) );
        
        
        $testItem2 = array(
            'page_id' => 123
        );
        $this->assertEquals( 'test/path/123', 
                $this->obj1->href($testItem2, false, false) );
        
        $this->assertEquals( 'test/path/123', 
                $this->obj1->href($testItem2, false, true) );
        
        $this->assertEquals( 'test/path/123', 
                $this->obj1->href($testItem2, true, true) );
        
        $testItem3 = new Item(
            123, 
            'example.com/com/ln/ch/file.ext'
        );
        $this->assertEquals( 'com/ln/ch/123/', 
                $this->obj1->href($testItem3, false, false) );
        
        $this->assertEquals( 'com/ln/ch/file.ext', 
                $this->obj1->href($testItem3, false, true) );
        
        $this->assertEquals( 'ln/ch/file.ext', 
                $this->obj1->href($testItem3, true, true) );

    }

}
