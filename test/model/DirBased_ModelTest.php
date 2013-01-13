<?php
require_once 'dir_based.php';

class DirBased_ModelTest extends PHPUnit_Framework_TestCase {

    protected $object;
    protected $params;
    
    
    private function makeDirs() {
        mkdir('example.com');
        mkdir('example.com/test');
        mkdir('example.com/test/dir');


    }

    private function removeDirs() {
        @rmdir('example.com/test/dir');
        @rmdir('example.com/test');
        @rmdir('example.com');
    }

    protected function setUp() {
        $this->removeDirs();
        $this->makeDirs();
        $this->params = array('example.com','file.php',array('test','dir'));
        $this->object = $this->getMockForAbstractClass('DirBased_Model',$this->params);
    }

    protected function tearDown() {
        $this->removeDirs();
    }

    /**
     * @covers DirBased_Model::getCurrentDir
     * @covers DirBased_Model::__construct
     * 
     */
    public function testGetCurrentDir() {
        $this->assertEquals('example.com/test/dir/',$this->object->getCurrentDir());
    }

    /**
     * @covers DirBased_Model::getCachePath
     * @covers DirBased_Model::buildCachePath
     */
    public function testGetCachePath() {
       $this->assertEquals('example.com/test/dir/.file.php'.CACHE_FILE,$this->object->getCachePath());
    }

    /**
     * @covers DirBased_Model::serializeToArray
     * @covers DirBased_Model::serializeToArrayImpl
     * 
     */
    public function testSerializeToArray() {
        $this->object->expects($this->once())
                     ->method('serializeToArrayImpl');
        $this->object->serializeToArray();
    }

}
