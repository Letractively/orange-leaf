<?php
require_once 'cfg.php';
require_once 'comic_view.php';
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-05 at 17:12:19.
 */
class HTMLProviderTest extends PHPUnit_Framework_TestCase {

    protected $normalArr;
    protected $normalObj;
    protected $emptyArr;
    protected $emptyObj;
    protected $currentDir = 'test/dir/';

    protected function setUp() {
        $this->normalArr = array(
            'descr' => array(
                'title' => 'ACorrectTitle'
            ),
            'elements' => array(1,2,3,4),
            'stat' => array('a'=>1),
            'about' => array(),
            'lang' => 'en',
            'extra' => array('boo'=>'baz')
        );
        
        $this->emptyArr = array(
            'descr' => array(),
            'elements' => array(),
            'stat' => array(),
            'about' => array(),
            'lang' => '',
            'extra' => array()
        );
        
        $this->normalObj = new HTMLProvider($this->currentDir, $this->normalArr);
        $this->emptyObj = new HTMLProvider($this->currentDir, $this->emptyArr);
    }

    protected function tearDown() {
        $normalObj = new HTMLProvider($this->currentDir, $this->normalArr);
        $emptyObj = new HTMLProvider($this->currentDir, $this->emptyArr);
    }
    
    /**
     * @covers HTMLProvider::__construct
     * 
     */
     public function testCtor() {
         
     }
    /**
     * @covers HTMLProvider::title
     * 
     */
    public function testTitle() {
        $this->assertEquals($this->normalArr['descr']['title'],$this->normalObj->title());
        //cached
        $this->assertEquals($this->normalArr['descr']['title'],$this->normalObj->title());
        $this->assertEquals(DEFAULT_TITLE,$this->emptyObj->title());
    }

    /**
     * @covers HTMLProvider::homeUrl
     * 
     */
    public function testHomeUrl() {
        $tmp = $_SERVER['SCRIPT_NAME'];
        $_SERVER['SCRIPT_NAME'] = '/test/path/script.php';
        $this->assertEquals('/test/path/',$this->normalObj->homeUrl());
        $_SERVER['SCRIPT_NAME'] = $tmp;
    }

    /**
     * @covers HTMLProvider::domain
     * 
     */
    public function testDomain() {
        $tmp_https = $_SERVER['HTTPS'];
        $tmp_host = $_SERVER['HTTP_HOST'];
        $_SERVER['HTTPS'] = '1';
        $_SERVER['HTTP_HOST'] = 'example.com';
        $this->assertEquals('https://example.com',$this->normalObj->domain());
        $this->assertEquals('example.com',$this->normalObj->domain(false));
        unset($_SERVER['HTTPS']);
        $this->assertEquals('http://example.com',$this->normalObj->domain());
        $this->assertEquals('example.com',$this->normalObj->domain(false));
        $_SERVER['HTTP_HOST'] = $tmp_host;
        $_SERVER['HTTPS'] = $tmp_https;
    }

    /**
     * @covers HTMLProvider::translate
     * 
     */
    public function testTranslate() {
        $this->assertEquals('Chapter',$this->normalObj->translate('chapter'));
        $word = 'djgsdgsdbgdsj';
        $ans = $word;
        if (IS_DEBUGGING)
            $ans = $ans . WORD_NOT_FOUND;
        $this->assertEquals($ans,$this->normalObj->translate($word));
    }

    /**
     * @covers HTMLProvider::thumb_abs
     * 
     */
    public function testThumb_abs() {
        $result1 = TUMBNAIL_SCRIPT . '?src=test.jpg';
        $result2 = TUMBNAIL_SCRIPT . '?src=test.jpg&w=100';
        $result3 = TUMBNAIL_SCRIPT . '?src=test.jpg&w=100&h=100';
        $this->assertEquals($result1,$this->normalObj->thumb_abs('test.jpg'));
        $this->assertEquals($result2,$this->normalObj->thumb_abs('test.jpg', 100));
        $this->assertEquals($result3,$this->normalObj->thumb_abs('test.jpg', 100, 100));
    }

    /**
     * @covers HTMLProvider::thumb
     * @depends testHomeUrl
     * 
     */
    public function testThumb() {
        $result1 = TUMBNAIL_SCRIPT . '?src='.$this->normalObj->homeUrl().'test.jpg&w='.THUMBNAIL_WIDTH;
        $result2 = TUMBNAIL_SCRIPT . '?src='.$this->normalObj->homeUrl().'test.jpg&w=100';
        $result3 = TUMBNAIL_SCRIPT . '?src='.$this->normalObj->homeUrl().'test.jpg&w=100&h=100';
        $this->assertEquals($result1,$this->normalObj->thumb('test.jpg'));
        $this->assertEquals($result2,$this->normalObj->thumb('test.jpg', 100));
        $this->assertEquals($result3,$this->normalObj->thumb('test.jpg', 100, 100));
    }

    /**
     * @covers HTMLProvider::currentDir
     * 
     */
    public function testCurrentDir() {
        $this->assertEquals($this->currentDir,$this->normalObj->currentDir());
    }

}
