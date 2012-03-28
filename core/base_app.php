<?php

abstract class AbstractApp extends Singleton {
  protected function __construct() {
    include_once ROOT_DIR . '/apps/shared/func_definitions.php';
  }

  protected $filter_handler = NULL;

  public function setFilterHandler($filter_handler) {
    $this -> filter_handler = $filter_handler;
  }

  protected function before_filter() {
    return true;
  }

  public abstract function run();
}

abstract class BaseDispatchApp extends AbstractApp {
  protected function __construct() {
    parent::__construct();
  }

  protected abstract function forward();

  public function run() {
    if (!$this -> before_filter()) {
      if (is_string($this -> filter_handler)) {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $this -> filter_handler;
        AppHelper::redirect($url);
      } elseif (is_a($this -> filter_handler, 'BaseApp')) {
        $this -> filter_handler -> run();
      } elseif (is_callable($this -> filter_handler)) {
        $this -> filter_handler();
      } else {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . Configure::fetch('dir_name');
        AppHelper::redirect($url);
      }
      return;
    }
    $this -> forward();
  }

}

abstract class BaseApp extends AbstractApp {
  protected function __construct() {
    parent::__construct();
    try {
      include_once 'func_definitions.php';
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
    if (!$this -> before_filter()) {
      if (is_string($this -> filter_handler)) {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $this -> filter_handler;
        header('Location: ' . $url);
      } elseif (is_a($this -> filter_handler, 'BaseApp')) {
        $this -> filter_handler -> run();
      } elseif (is_callable($this -> filter_handler)) {
        $this -> filter_handler();
      } else {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . Configure::fetch('dir_name');
        AppHelper::redirect($url);
      }
      return;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && is_callable($this -> do_post)) {
      $this -> do_post();
    } else {
      $this -> do_get();
    }
  }

}
?>
