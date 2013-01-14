<?php
require_once 'common.php';
require_once 'comic_view.php';
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-05 at 17:12:19.
 */
class DataProviderTest extends PHPUnit_Framework_TestCase {

    /**
     * @var DataProvider
     */
    protected $object;
    protected $normalArray;
    protected $incompleteArray;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->normalArray = array(
            'descr' => array('foo'=>'bar'),
            'elements' => array(1,2,3,4),
            'stat' => array('a'=>1),
            'about' => array(),
            'lang' => 'en',
            'extra' => array('boo'=>'baz')
        );
        $this->incompleteArray = array(
            'descr' => array(),
            
            'stat' => array(),
            'about' => array(),
            'lang' => array(),
            'extra' => array()
        );
        
        $this->object = new DataProvider($this->normalArray);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    /**
     *
     * @expectedException   Error500
     * ;expectedExceptionMessage    Wrong input, "descr" missing   
     */
    public function testExceptionBadCtor() {
       $obj = new DataProvider($this->incompleteArray); 
    }

    /**
     * @covers DataProvider::addExtra
     * 
     */
    public function testAddExtra() {
        $rnd = rand(0, 1000000);
        $var = "var$rnd";
        $this->object->addExtra($var, true);
        $this->assertTrue($this->object->extra($var));
    }

    /**
     * @covers DataProvider::lang
     * 
     */
    public function testLang() {
        $this->assertEquals($this->object->lang(),$this->normalArray['lang']);
    }

    /**
     * @covers DataProvider::descr
     * 
     */
    public function testDescr() {
        $this->assertEquals($this->object->descr('foo'),$this->normalArray['descr']['foo']);
        $this->assertEmpty($this->object->descr('foo1'));
    }

    /**
     * @covers DataProvider::extra
     * 
     */
    public function testExtra() {
        $this->assertEquals($this->object->extra('boo'),$this->normalArray['extra']['boo']);
        $this->assertEmpty($this->object->extra('foo1'));
    }

    /**
     * @covers DataProvider::hasElements
     * 
     */
    public function testHasElements() {
        $this->assertTrue($this->object->hasElements());
    }

    /**
     * @covers DataProvider::nextElement
     * 
     */
    public function testNextElement() {
        $this->assertEquals($this->object->nextElement(),1);
        $this->assertEquals($this->object->nextElement(),2);
        $this->assertEquals($this->object->nextElement(),3);
        $this->assertEquals($this->object->nextElement(),4);
        $this->assertNull($this->object->nextElement());
        //resest
        $this->assertEquals($this->object->nextElement(true),1);
    }

    /**
     * @covers DataProvider::stat
     * 
     */
    public function testStat() {
        $this->assertEquals($this->object->stat('a'),$this->normalArray['stat']['a']);
        $this->assertEmpty($this->object->stat('a1'));
    }

    /**
     * @covers DataProvider::elements
     * 
     */
    public function testElements() {
        $this->assertEquals($this->object->elements(), $this->normalArray['elements']);
        $this->assertEquals($this->object->elements(0), $this->normalArray['elements'][0]);
        $this->assertEquals($this->object->elements(2), $this->normalArray['elements'][2]);
        $this->assertNull($this->object->elements(123));
    }

    /**
     * @covers DataProvider::about
     * @todo   Implement testAbout().
     */
    public function testAbout() {
        $this->assertEquals($this->object->about(),$this->normalArray['about']);
        $this->assertEmpty($this->object->about('a1'));
    }

}