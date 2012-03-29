<?php
require_once 'bootstrap.php';

/**
 *
 */
class DispatchApp extends BaseApp {

  public function __construct() {
    parent::__construct();
  }

  protected function do_get() {
    $apps = Configure::fetch('apps');
    $redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . strrchr(dirname($_SERVER['PHP_SELF']), '/') . '/apps/' . $apps['default']['apps']['default']['path'];
    AppHelper::redirect($redirect_url);
  }

}

DispatchApp::getInstance() -> run();
?>
