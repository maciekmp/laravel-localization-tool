<?php
/**
 * Name: Laravel Localization Tool
 * Version: 0.3
 */
//Set for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

function cutTo($string, $symbol) {
    if (strpos($string, $symbol) == false)
        return $string;
    else
        return substr($string, 0, strpos($string, $symbol));
}

if (isset($_POST['code'])) {

    $code = $_POST['code'];

    $code_return = $code;
    $code = explode('@lang(\'', $code);
    array_shift($code);
    
    $elements = array();
    $strings = array();
    $keys = array();
    
    foreach ($code as $c) {
        //get string in @lang methos
        $ce = explode(')', $c);
        $c = array_shift($ce);
        $c = str_replace('\'', '', $c);

        //join rest of the string
        $after = implode(')', $ce);
        
        //get string after first dot sign
        $c = explode('.', $c);
        array_shift($c);
        $c = implode('.', $c);

        //remove rest of the origin string
        $after = cutTo($after, '"');
        $after = cutTo($after, "\n");
        $after = cutTo($after, "{{");
        $after = cutTo($after, '<');
        
        //replace string in blade template
        $code_return = str_replace($after, '', $code_return);
        
        //if string already exist ignore it
        //todo: overwrite it
        if (in_array($c, $elements)){
            continue;
        }
        //if key exist in exlclude_keys do not add it
        $exclude = explode(',', $_POST['exclude_keys']);
        if (in_array($c, $exclude))
            continue;
        //add key to $elements and origin string to $strings
        array_push($elements, $c);
        array_push($strings, $after);
    }
    $code_return = htmlspecialchars($code_return, ENT_IGNORE);
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Laravel Localization Tool</title>
        <meta name="description" content="Simple tool to help with localization process on laravel template files.">
        <meta name="author" content="SitePoint">
        <style>
            body{
                padding: 20px;
                font-family: arial;
            }
            textarea{
                display: block;
                height: 250px;
                width: 70%;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <h1>Laravel Localization Tool</h1>
        <small>
            Description: paste your blade code with @lang definitions before actual strings. Examples in <a href="README.md">README.md</a><br/>
            Original strings are cut to: new line mark, opening html tag, blade variable mark '{{' and quote sign.
        </small>
        <?php
        if (isset($elements)) {
            echo "<h2>Localization file output</h2>";
            echo "<textarea>\n";
            echo "<?php\n\nreturn array(\n";
            foreach ($elements as $key => $e) {
                echo "'" . $e . "' => '" . trim($strings[$key]) . "',\n";
            }
            echo ");";
            echo "\n</textarea>";
        }
        ?>
        <form method='post'>
            <h2>Blade code input/output</h2>
            <textarea name='code'><?php if (isset($code_return)) echo $code_return; ?></textarea>
            Exclude keys:
            <input name='exclude_keys' value='close_button,save_button, exit_button'/>
            <input type='submit'>
        </form>
        <footer>
        <a href="https://github.com/maciekmp/laravel-localization-tool/">GitHub</a>
        </footer>
    </body>
</html>
