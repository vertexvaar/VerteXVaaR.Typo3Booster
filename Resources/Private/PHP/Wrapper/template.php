<?php
define('PATH_thisScript', '{script_path}');
$classLoader = require('{autoload_file}');
$classPreLoader = new \ClassPreloader\ClassLoader();
$classPreLoader->register();
// {template_code}
$classPreLoader->unregister();
file_put_contents('{target_file}', '<?php return ' . var_export($classPreLoader->getFilenames(), true) . ';');
