<?php
require_once 'cfg.php';
require_once 'model/helpers.php';

class FunctionsTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp() {
        @rmdir('dirs/.hidden');
        @rmdir('dirs/_system');
        @rmdir('dirs/regular_dir');
        @rmdir('dirs/regular_dir2');
        @rmdir('dirs');
        
        mkdir('dirs');
        mkdir('dirs/regular_dir');
        mkdir('dirs/regular_dir2');
        mkdir('dirs/.hidden');
        mkdir('dirs/_system');
    }
    
    protected function tearDown() {
        @rmdir('dirs/.hidden');
        @rmdir('dirs/_system');
        @rmdir('dirs/regular_dir');
        @rmdir('dirs/regular_dir2');
        @rmdir('dirs');
    }
    
    /*
     * @covers ::g_dictionary
     */
    public function test_g_dictionary() {
        $res = g_dictionary();
        $this->assertTrue(is_array($res));
        $this->assertTrue(count($res) > 0);
    }
    
    /*
     * @covers ::g_translate
     */
    public function test_g_translate() {
        $this->assertEquals('Chapter',g_translate('en','chapter'));
        $word = 'djgsdgsdbgdsj';
        $ans = $word;
        if (IS_DEBUGGING)
            $ans = $ans . WORD_NOT_FOUND;
        $this->assertEquals($ans,g_translate('en',$word));
    }
    
    /*
     * @covers ::getSubdirs
     */
    public function test_getSubdirs() {
        $this->assertEquals(array('regular_dir','regular_dir2'), 
                            getSubdirs('dirs/', true) );
        
        $this->assertEquals(array('dirs/regular_dir/','dirs/regular_dir2/'), 
                            getSubdirs('dirs/', false) );
        
        $this->assertEquals(array('dirs/regular_dir/','dirs/regular_dir2/'), 
                            getSubdirs('dirs/') );
        
        $this->assertEquals(array(), 
                            getSubdirs('dirs') );
        
        $this->assertEquals(array(), 
                            getSubdirs('dirs2') );
    }
    
     /*
     * @covers ::getCurrentDirName
     */
    public function test_getCurrentDirName() {
        $this->assertEquals('tests', getCurrentDirName('tests') );
        $this->assertEquals('123', getCurrentDirName('/etc/abc/123/') );
        $this->assertEquals('123', getCurrentDirName('/etc/abc/123/abc.txt') );
        $this->assertEquals('123', getCurrentDirName('abc/123/abc.txt') );
        $this->assertEquals('abc.txt', getCurrentDirName('abc.txt') );

    }
    
    /*
     * @covers ::getParentDirName
     */
    public function test_getParentDirName() {
        $this->assertEquals('', getParentDirName('') );
        $this->assertEquals('', getParentDirName('tests') );
        $this->assertEquals('/etc/abc/', getParentDirName('/etc/abc/123/') );
        $this->assertEquals('/etc/abc/', getParentDirName('/etc/abc/123/abc.txt') );
        $this->assertEquals('abc/', getParentDirName('abc/123/abc.txt') );
        $this->assertEquals('', getParentDirName('abc.txt') );        
    }
    
    /*
     * @covers ::g_ImageFileExtensions
     */
    public function test_g_ImageFileExtensions() {
        $this->assertEquals(array('jpg', 'png', 'gif'), g_ImageFileExtensions() );
               
    }
    
    /*
     * @covers ::isAboutDir
     */
    public function test_isAboutDir() {
        $this->assertTrue(isAboutDir('about'));
        $this->assertTrue(isAboutDir('about/'));
        $this->assertFalse(isAboutDir('notabout'));
        $this->assertFalse(isAboutDir(''));
               
    }
    
}