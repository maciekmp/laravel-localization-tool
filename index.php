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
    foreach ($code as $c) {
        $ce = explode('\')', $c);
        $c = $ce[0];
        $after = $ce[1];
        $c = explode('.', $c);
        array_shift($c);
        $c = implode('.', $c);
        array_push($elements, $c);

        $after = cutTo($after, '<');
        $after = cutTo($after, "\n");
        array_push($strings, $after);
        $code_return = str_replace($after, '', $code_return);
    }
    $code_return = htmlspecialchars($code_return, ENT_IGNORE);
}
?>
<!doctype>
<html>
    <head>
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
            Description: paste your blade code with @lang definitions before actual strings, ex:@lang('project.exit_button')Exit
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
            <input type='submit'>
        </form>
    </body>
</html>