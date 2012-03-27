<?php
require_once ROOT_DIR . '/apps/shared/func_definitions.php';

abstract class AbstractApp extends Singleton {
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
    try {
      Session::init();
      $config = &Configure::init();
      DB::setup($config['db']['conn_str'], $config['db']['user'], $config['db']['passwd']);
    } catch (exception $e) {
      $redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . strrchr(dirname($_SERVER['PHP_SELF']), '/') . '/public/400.html';
      header("Location: " . $redirect_url);
      exit ;
    }
  }

  protected abstract function forward();

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
        header('Location: ' . $url);
      }
      return;
    }
    $this -> forward();
  }

}

abstract class BaseApp extends AbstractApp {
  protected function __construct() {
    try {
      require 'func_definitions.php';
      Session::init();
      $config = &Configure::init();
      DB::setup($config['db']['conn_str'], $config['db']['user'], $config['db']['passwd']);
      View::$DEV_MODE = $config['dev_mode'];
      View::assignGlobal('CATES', Configure::fetch('apps'));
      View::assignGlobal('DIR_NAME', Configure::fetch('dir_name'));
      Configure::write('index_url', 'http://' . $_SERVER['HTTP_HOST'] . '/' . Configure::fetch('dir_name'));
      
      $cur_app = preg_replace("/^(\S+)\/(\w+)\//i", "$2", $_SERVER['REQUEST_URI']);
      Configure::write('app', $cur_app);
      View::assignGlobal('CUR_APP', $cur_app);
      
      $this -> view = new View;
    } catch (exception $e) {
      $redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . strrchr(dirname($_SERVER['PHP_SELF']), '/') . '/public/400.html';
      header("Location: " . $redirect_url);
      exit ;
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
        header('Location: ' . $url);
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
