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
        $this->obj1 = $this->getMockForAbstractClass('Link',array($this->path1));
        $this->obj2 = $this->getMockForAbstractClass('Link',array($this->path2));
        $this->obj3 = $this->getMockForAbstractClass('Link',array($this->path3));
    }

    protected function tearDown() {
        
    }
    
    /**
     * @covers Link::__construct
     * 
     */
    public function testCtor() {
        $obj = $this->getMockForAbstractClass('Link',array($this->path1));
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
    public function testHref3() {
        $obj = '';
        $this->obj1->href($obj);
    }
    
     /**
     * @covers Link::href
     * 
     */
    public function testHref2() {
        $obj = array('id' => 0);
        $this->obj1->expects($this->any())
                   ->method('originalNameLinkImpl')
                   ->with($this->equalTo($obj));
        $this->obj1->href($obj, true);
    }

    /**
     * @covers Link::href
     * 
     */
    public function testHref() {
        $obj = array('id' => 0);
        $this->obj1->expects($this->any())
                   ->method('originalNameLinkImpl')
                   ->with($this->equalTo($obj))
                   ->will($this->returnValue('Win'));
        
        $this->obj1->expects($this->once())
                   ->method('alteredNameLinkImpl')
                   ->with($this->equalTo($obj));
        
        $this->obj1->href($obj, true);
        $this->obj1->href($obj, false);
        
        
//        $this->obj1->expects($this->any())
//                   ->method('originalNameLinkImpl')
//                   ->with($this->equalTo($obj))
//                   ->will($this->returnValue(null));
//        $this->obj1->href($obj, true);
    }

}
