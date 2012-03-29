<?php

abstract class AbstractApp extends Singleton {
  protected function __construct() {
    include_once ROOT_DIR . '/apps/shared/domain_definitions.php';
    $app = $this -> get_app_config();
    if (isset($app['role'])) {
      $this -> permission = $app['role'];
    }
  }

  protected $filter_faild_handler = NULL;
  protected $authorize_faild_handler = NULL;
  protected $permission = array();

  public function get_app_config() {
    $cates = &Configure::fetch('apps');

    foreach ($cates as $cate) {
      foreach ($cate['apps'] as $app) {
        if ($app['path'] == Configure::fetch('cur_app')) {
          return $app;
        }
      }
    }
    return array();
  }

  public function test_can_run($role) {
    if (empty($this -> permission) || in_array(DEFAULT_ROLE, $this -> permission)) {
      return true;
    } else {
      return in_array($role, $this -> permission);
    }
  }

  protected function handle_faild($handler) {
    if (is_string($handler)) {
      $url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $handler;
      header('Location: ' . $url);
    } elseif (is_a($handler, 'AbstractApp')) {
      $handler -> run();
    } elseif (is_callable($handler)) {
      handler();
    } else {
      AppHelper::redirect_to_root();
    }
    return;
  }

  public function setFilterHandler($filter_handler) {
    $this -> filter_handler = $filter_handler;
  }

  protected function before_filter() {
    return true;
  }

  public abstract function run();
}

abstract class BaseApp extends AbstractApp {
  protected function __construct() {
    parent::__construct();
    try {
      include_once 'domain_definitions.php';
      $this -> view = new View;
    } catch (exception $e) {
      $redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . strrchr(dirname($_SERVER['PHP_SELF']), '/') . '/public/400.html';
      AppHelper::redirect($redirect_url);
    }
  }

  protected $view = NULL;

  protected function do_post() {
    $this -> do_get();
  }

  protected abstract function do_get();

  protected function display($view_file = NULL) {
    if ($view_file != NULL) {
      $this -> view -> display($view_file);
    } else {
      $this -> view -> display('index.tpl');
    }
  }

  protected function assign($tpl_var, $value = null, $nocache = false) {
    return $this -> view -> assign($tpl_var, $value, $nocache);
  }

  protected function getView() {
    return $this -> view;
  }

  public function run() {
    if (!$this -> test_can_run(AuthorizeHelper::get_cur_role())) {
      $this -> handle_faild($this -> authorize_faild_handler);
    }
    if (!$this -> before_filter()) {
      $this -> handle_faild($this -> filter_faild_handler);
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && is_callable($this -> do_post)) {
      $this -> do_post();
    } else {
      $this -> do_get();
    }
  }

}
?>
