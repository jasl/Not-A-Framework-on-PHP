<?php
require_once 'bootstrap.php';

/**
 *
 */
class DispatchApp extends BaseDispatchApp {

  public function __construct() {
    parent::__construct();
  }

  protected function forward() {
    $apps = Configure::fetch('apps');
    $redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . strrchr(dirname($_SERVER['PHP_SELF']), '/') . '/apps/' . $apps['default']['apps']['default']['path'];
    header("Location: " . $redirect_url);
  }

}

$dispatch_app = new DispatchApp;
$dispatch_app->run();
?>
