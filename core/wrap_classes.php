<?php
require_once ROOT_DIR . '/core/libs/rb.php';
require_once ROOT_DIR . '/core/libs/smarty/Smarty.class.php';

class DB extends R {

}

class View extends Smarty {
  public static $DEV_MODE = false;

  public function __construct() {
    parent::__construct();

    if (self::$DEV_MODE) {
      $smarty -> debugging = true;
      $smarty -> caching = false;
    } else {
      $smarty -> caching = true;
      $smarty -> cache_lifetime = 120;
    }

    $this -> use_include_path = true;
    if (ROOT_DIR != realpath(dirname(__FILE__))) {
      $this -> addTemplateDir(ROOT_DIR . DS . 'layouts');
      $this -> addConfigDir(ROOT_DIR . DS . 'configs');
    }
    $current_app = str_replace('/', DS, strrchr(dirname($_SERVER['PHP_SELF']), '/'));
    $this -> setCacheDir(ROOT_DIR . DS . 'cache' . DS . 'smarty' . $current_app);
    $this -> setCompileDir(ROOT_DIR . DS . 'cache' . DS . 'templates_c' . $current_app);

    $dir_level = substr_count(dirname($_SERVER['PHP_SELF']), '/');
    $public_dir = 'public';
    for ($i = 1; $i < $dir_level; $i++) {
      $public_dir = '../' . $public_dir;
    }
    $this -> assign("PUBLIC", $public_dir);
  }

  public static function registGlobal($key = NULL, $value = NULL, $nocache = false) {
    if ($key == NULL) {
      return false;
    } else {
      self::$global_tpl_vars[$key] = new Smarty_variable($value, $nocache);
      return true;
    }
  }

}
?>
