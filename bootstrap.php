<?php
define('ROOT_DIR', realpath(dirname(__FILE__)));

require_once 'core/core_classes.php';

Session::init();
$config = &Configure::init();
DB::setup($config['db']['conn_str'], $config['db']['user'], $config['db']['passwd']);
View::$DEV_MODE = $config['dev_mode'];
View::registGlobal('CATES', Configure::fetch('apps'));
View::registGlobal('DIR_NAME', Configure::fetch('dir_name'));

Configure::write('index_url', 'http://' . $_SERVER['HTTP_HOST'] . '/' . Configure::fetch('dir_name'));
$cur_app = preg_replace("/^(\S+)\/(\w+)\//i", "$2", $_SERVER['REQUEST_URI']);
Configure::write('app', $cur_app);
View::registGlobal('CUR_APP', $cur_app);
?>
