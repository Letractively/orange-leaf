<?php
require_once 'model/dir_based.php';
class MultiLang_ModelTest extends PHPUnit_Framework_TestCase {

     private function makeDirs() {
        mkdir('example2.com');
    }

    private function removeDirs() {
        @rmdir('example2.com');
    }

    protected function setUp() {
        $this->removeDirs();
        $this->makeDirs();
    }

    protected function tearDown() {
        $this->removeDirs();
    }

    /*
     * @covers MultiLang_Model::getCurrentLanguage
     */
    public function testGetCurrentLanguage() {
        $params = array(
            'example2.com',
            '',
            array()
        );
        $mock = $this->getMockForAbstractClass('MultiLang_Model',$params);
        $mock->expects($this->once())
             ->method('getCurrentLangImpl');
        $mock->GetCurrentLanguage();
    }

}
