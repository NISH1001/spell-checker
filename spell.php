<?php
/**
 * @package testplugin
 */
/*
Plugin Name: paradox
Plugin URI: localhost
Description: A test plugin 
Version: 3.1.11
Author: paradox
Author URI: localhost
*/
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<script type="text/javascript">

function createCookie(name,value,days) 
{
    if (days) 
    {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    //document.cookie = name+"="+value+expires+"; path=/";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) 
{
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) 
    {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) 
{
    createCookie(name,"",-1);
}
</script>

<?php

$GLOBALS['test'] = '';
$GLOBALS['nepali'] = '';

function replace_unicode_escape_sequence($match) 
{
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}

function unicode_decode($str) 
{
    return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $str);
}

function tokenize($content)
{
    $exploded = preg_split('/\s+/', $content);
?>
    <script type="text/javascript">
        var splitted = [<?php echo '"'.implode('","', $exploded).'"' ?>];
        var regex = RegExp("[A-Za-z0-9!@#$%^&*()<>/?]+");
        var nepali = [];
        for(var i =0; i<splitted.length; ++i)
        {
            var test = regex.test(splitted[i]);
            if(test == false)
            {
                nepali.push(splitted[i]);
            }
        }
        var nepalitext = unescape(encodeURIComponent(nepali.join(" ")));
        //var nepalitext = encodeURI(nepali.join(" "));
        //document.cookie = "nepali_text=" + nepalitext;
        eraseCookie("nepali_text");
        createCookie("nepali_text", nepalitext, 30);
    </script>

<?php
}

function input($post_id)
{
    global $post;
    tokenize($post->post_content);
    $GLOBALS['nepali'] = $_COOKIE['nepali_text'];

    file_put_contents("/home/paradox/test.txt", utf8_decode($GLOBALS['nepali']));

    //$command = "python /home/paradox/Nish/Programming/Python/projects/nspell/";
    $args = $_COOKIE['nepali_text'];
    $dir = "/home/paradox/public_html/wordpress/wp-content/plugins/testplugin/nspell";
    chdir($dir);
    file_put_contents("./data/test/input.txt", utf8_decode($args))
    $text = shell_exec("./nspellwrapper.py " );
    //$text = substr($text, 1);
    $GLOBALS['test'] = "hello";
?>
    <script type="text/javascript">
    window.onload = function test()
    {
        var text = "<?php echo $GLOBALS['test']; ?>";
        text = decodeURIComponent(escape(text));
        //alert(decodeURIComponent(escape(readCookie("nepali_text"))));
        alert(text);
        //var spelling = document.getElementById("spell");
        //spelling.innerHTML = text;
        //alert(decodeURIComponent(escape(text)));
    }
    </script>

<?php
}


add_action('wp_head', 'input');
?>
