<?php

use Cake\Core\Plugin;

$pluginName = 'Webapps';
if (Plugin::isLoaded($pluginName)) {
    $path = Plugin::path($pluginName) . 'webroot';
    if (is_dir($path)) {
        $file = $path . DS . 'index.html';
        echo file_get_contents($file);
        return;
    }
}

echo '<h1>run npm build:prod</h1>'


?>
