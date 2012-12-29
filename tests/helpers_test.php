<?php
/*===============*/
/* Helpers Tests */ 
/*===============*/

if (empty($t)) die();

require_once('model/helpers.php');

//getSubdirs
$correct_result = array('regular_dir','regular_dir2');
$actual_result = getSubdirs('tests/dir_list/', true);
$t->compare('getSubdirs test (only labels)',$correct_result,$actual_result);

$correct_result = array('tests/dir_list/regular_dir/','tests/dir_list/regular_dir2/');
$actual_result = getSubdirs('tests/dir_list/', false);
$t->compare('getSubdirs test (path)',$correct_result,$actual_result);

$correct_result = array('tests/dir_list/regular_dir/','tests/dir_list/regular_dir2/');
$actual_result = getSubdirs('tests/dir_list/');
$t->compare('getSubdirs test (default)',$correct_result,$actual_result);

$correct_result = array();
$actual_result = getSubdirs('tests/dir_list');
$t->compare('getSubdirs test (default, without slash)',$correct_result,$actual_result);

$correct_result = array();
$actual_result = getSubdirs('tests/dir_list2');
$t->compare('getSubdirs test (wrong path)',$correct_result,$actual_result);

//getCurrentDirName
$correct_result = 'tests';
$actual_result = getCurrentDirName('tests');
$t->compare('getCurrentDirName test (just name)',$correct_result,$actual_result);

$correct_result = '123';
$actual_result = getCurrentDirName('/etc/abc/123/');
$t->compare('getCurrentDirName test (full path without file)',$correct_result,$actual_result);

$correct_result = '123';
$actual_result = getCurrentDirName('/etc/abc/123/abc.txt');
$t->compare('getCurrentDirName test (full path with file)',$correct_result,$actual_result);

$correct_result = '123';
$actual_result = getCurrentDirName('abc/123/abc.txt');
$t->compare('getCurrentDirName test (relative path with file)',$correct_result,$actual_result);

$correct_result = 'abc.txt';
$actual_result = getCurrentDirName('abc.txt');
$t->compare('getCurrentDirName test (filename)',$correct_result,$actual_result);

//getParentDirName
$correct_result = '';
$actual_result = getParentDirName('');
$t->compare('getCurrentDirName test (empty string)',$correct_result,$actual_result);

$correct_result = '';
$actual_result = getParentDirName('tests');
$t->compare('getCurrentDirName test (just name)',$correct_result,$actual_result);

$correct_result = '/etc/abc/';
$actual_result = getParentDirName('/etc/abc/123/');
$t->compare('getParentDirName test (full path without file)',$correct_result,$actual_result);

$correct_result = '/etc/abc/';
$actual_result = getParentDirName('/etc/abc/123/abc.txt');
$t->compare('getParentDirName test (full path with file)',$correct_result,$actual_result);

$correct_result = 'abc/';
$actual_result = getParentDirName('abc/123/abc.txt');
$t->compare('getParentDirName test (relative path with file)',$correct_result,$actual_result);

$correct_result = '';
$actual_result = getParentDirName('abc.txt');
$t->compare('getParentDirName test (filename)',$correct_result,$actual_result);


//g_ImageFileExtensions
$correct_result = array('jpg', 'png', 'gif');
$actual_result = g_ImageFileExtensions();
$t->compare('g_ImageFileExtensions test',$correct_result,$actual_result);

//isAboutDir
$correct_result = true;
$actual_result = isAboutDir('about');
$t->compare('isAboutDir test (w/o slash)',$correct_result,$actual_result);

$correct_result = true;
$actual_result = isAboutDir('about/');
$t->compare('isAboutDir test (with slash)',$correct_result,$actual_result);

$correct_result = false;
$actual_result = isAboutDir('notabout');
$t->compare('isAboutDir test (not about)',$correct_result,$actual_result);

$correct_result = false;
$actual_result = isAboutDir('');
$t->compare('isAboutDir test (empty string)',$correct_result,$actual_result);
