<?php

use Cake\Core\Plugin;

$pluginName = 'Webapps';
if (Plugin::isLoaded($pluginName)) {
    $path = Plugin::path($pluginName) . 'webroot';
    if (is_dir($path)) {
        $file = $path . DS . 'apps/admin/index.html';
        echo file_get_contents($file);
        return;
    }
}

echo '<h2>Execute command In app/plugins/Webapps/frontend:  </h2><br/><h1>npm run build:prod</h1>';


?>
