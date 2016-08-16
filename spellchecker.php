<?php
/**
 * @package testplugin
 */
/*
Plugin Name: spell checker
Plugin URI: localhost
Description: A nepali spellchecker plugin 
Version: 3.1.11
Author: paradox
Author URI: localhost
*/
ini_set( 'default_charset', 'UTF-8' );

$GLOBALS['to_display'] = "";

function tokenize($content)
{
    $exploded = preg_split('/\s+/', $content);
    $nepali = array();
    $regex = "/[A-Za-z0-9!@#$%^&*()]+/";
    foreach($exploded as &$word)
    {
        $match = preg_match($regex, $word);
        if($match == 0 && !in_array($word, $nepali) && $word!="।")
        {
            $nepali[] = $word;
        }
    }
    return $nepali;
    //return implode(' ', $nepali);
}

function getRequest($content)
{
    $text = implode(' ', $content);
    $url = "http://localhost:8000/nspell/api/?text=" . urlencode($text);
    $response = file_get_contents($url);
    return $response;
}

function render($value)
{
?>
    <script type="text/javascript">
        window.onload = function test()
        {
            var to_display = "<?php echo $value; ?>";
            //to_display = decodeURIComponent(escape(to_display));
            var splitted = to_display.split(";");
            var spell_div = document.getElementById("spell");
            spell_div.innerHTML = "";
            if(splitted.length == 1)
            {
                spell_div.innerHTML = "No mistakes.. cheers.. be awesome..";
                return;
            }

            var color = "<span style='color:red;font-size:16px'>";
            for(var i=0; i<splitted.length-1; ++i)
            {
                var words = splitted[i].split(":");
                //spell_div.innerHTML += "<br/>" + splitted[i];
                spell_div.innerHTML += color + words[0] +  " : " + "</span>" + words[1];
                spell_div.innerHTML += "<br/>";
            }
        }
    </script>
<?php
}

function spellCheker($post_id)
{
    // global post variable consisting of contextual data
    global $post;
    $content = $post->post_content;
    $id = $post->ID;

    // get nepali words' array
    $nepali = tokenize($content);

    // now get the JSON response 
    $response = getRequest($nepali);

    // convert JSON string to php arrays
    $response = json_decode($response, true);

    $output_array = array();

    //$val = $response['म']['minedit'];
    foreach($response as $key => $value)
    {
        $minedit = $value['minedit'];
        if($minedit > 0)
        {
            $likely = implode(', ', $value['likely']);
            $GLOBALS['to_display'] .= $key . " : " .  $likely . ";" ;
            $output_array[$key] = $likely;
            /*
            echo "<script type='text/javascript'>";
            echo "alert('{$key} : {$likely}');";
            echo "</script>";
             */
        }
    }
    render($GLOBALS['to_display']);
}

add_action('wp_head', 'spellCheker');
?>

