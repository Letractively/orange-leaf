<?php
define('INSTALL_CFG_PATH',      '../cfg.php');
define('INSTALL_DEF_CFG_PATH',  '../cfg.default.php');
define('INSTALL_FILES_DIR',     '../');
define('INSTALL_SAMPLE_DIR',    'sample');

if (file_exists(INSTALL_CFG_PATH)) die('The script is already installed. Edit '.INSTALL_CFG_PATH.' manally.');
session_start();

function getDomain()
{
    return $_SERVER['SERVER_NAME'];
}

function getSubdir()
{
    $path = explode('/', $_SERVER['REQUEST_URI']);
    $chunks = array();
    
    //remove empty chunks
    foreach ($path as $chunk)
        if ($chunk)
            $chunks[]=$chunk;
    
    if (count($chunks)) {
        //remove last dir
        array_pop($chunks);
    }
    
    //glue
    return implode('/', $chunks);
}

function parseCfg($path)
{
    $res = array();
    
    $str = file_get_contents($path);
    if (false === $str)
        die("Can't open config at $path");
    
    $p_define   =   "define\(";
    $p_name     =   "'([A-Z_0-9]+)',"; 
    $p_value    =   "[\t ]*[']*([^']*)[']*\);";
    $p_comment  =   "[\t ]*\/\/(.*)";
    
    $pattern    =   '|'.$p_define.$p_name.$p_value.$p_comment.'|u';
    $matches = array();
    preg_match_all($pattern,$str,$matches,PREG_SET_ORDER);
    
    foreach ($matches as $m) {
        $key = $m[1];
        $val = trim($m[2]);
        if ('true' === $val)
            $val = true;
        else if ('false' === $val)
            $val = false;
        else if (is_numeric($val))
            $val = intval($val);
        
        $res[ $key ]=array(
                'val'=>$val,
                'comment'=>trim($m[3])
        );
        
        if ('SUBDIR' == $key) {
            $res[$key]['val'] = getSubdir();
        }
    }
    return $res;
}

function saveCfg($path,$arr)
{  
    $out="<?php\r\n";
    
    foreach($arr as $key=>$el) {
        $val = $el['val'];
        $comment = $el['comment'];
        if (is_string($val))
            $val = "'$val'";
        if (false === $val)
            $val = 'false';
        if (true === $val)
            $val = 'true';
        $out .= "define('$key',\t$val);\t\t//$comment\r\n";
    }
    return file_put_contents($path, $out);
}

function processForm($req)
{
    $domain = getDomain();
    if ($domain) {
        $path = INSTALL_FILES_DIR.$domain; 
        if ($req['sample_data'] 
                && file_exists(INSTALL_SAMPLE_DIR)
                && !file_exists($path)
                && is_dir(INSTALL_SAMPLE_DIR)
                && rename(INSTALL_SAMPLE_DIR, $path) ) {
                    echo "<p>Sample data has been copied to $path.</p>";
                
        } else if (mkdir($path)) {
            echo "<p>Dir $path has been created.</p>";
        }
    }
    
    $cfg = parseCfg(INSTALL_DEF_CFG_PATH);
    echo '<p>Got ' . count($cfg) . ' config records.</p>';
    foreach($cfg as $key=>$el) {
        $var = strtolower($key);
        $val =& $cfg[$key]['val'];
        if (is_bool($val))
            $val = false;
        if ( isset($req[$var]) ) {
            if (is_int($val)) 
                $val = intval($req[$var]);
            else if (is_bool($val))
                $val = !!$req[$var];
            else 
                $val = addslashes($req[$var]);
        }
    }
    echo '<p>Saving</p>';
    $success = saveCfg(INSTALL_CFG_PATH,$cfg);
    if (!$success) 
        echo '<p>Error saving config.</p>';
    else
        echo '<p>Done.</p>';
    return $success;
}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
    .descr{display:none; font-size: 80%; color:gray;}
    .key, .value {vertical-align: top; padding:10px 5px;}
    .key{font-size:120%;width:250px;padding-right: 1px}
    .value{padding-left:1px;
</style>
    
<script type="text/javascript">
   function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
   }
</script>
</head>
<body>
    <div class ="com_wrapper">
        <div class="com_body">
            <?php if (isset($_REQUEST['salt'])):?>
                <?php 
                    if ($_REQUEST['salt'] == $_SESSION['salt'])
                    {
                        processForm($_REQUEST);
                        unset($_SESSION['salt']);
                        echo '<a href="..">Install finished.</a>';
                    }
                    else
                    {
                        echo "<p>Error submitting form, turn on cookies.</p>";
                    }
                    
                ?>
            <?php else: ?>
                <?php 
                    $g_default_cfg = parseCfg(INSTALL_DEF_CFG_PATH); 
                    $g_salt = md5( rand(0,1024) . 'ololo' );
                    $_SESSION['salt'] = $g_salt;
                ?>
                <form>
                    <input type="hidden" name="salt" value="<?php echo $g_salt; ?>" />
                    <input type="checkbox" name="sample_data" /><label for="smaple_data">Create sample dir</label>
                    <p>Please verify and change config settings:</p>
                    <table>
                        <?php foreach ($g_default_cfg as $key=>$el):?>
                        <tr>
                            <?php $id = 'id_'.$key; ?>
                            <td class="key">
                                <a href="javascript:void(0)" onclick="toggle_visibility('<?php echo $id?>');" ><?php echo $key?></a>
                                <div class="descr" id="<?php echo $id?>">
                                    <?php echo $el['comment'];?>
                                </div>
                            </td>
                            <td clas="value">
                                <?php if (is_bool($el['val'])):?>
                                    <input type="checkbox" name="<?php echo strtolower($key)?>" <?php if ($el['val']) echo 'checked="checked"'?> /> 
                                <?php else: ?>
                                    <input type="text" name="<?php echo strtolower($key)?>" value="<?php echo $el['val']?>" /> 
                                <?php endif?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </table>
                    <input type="submit" name="send" />
                </form>
            <?php endif; ?>
	</div>
    </div>
</body>
</html>