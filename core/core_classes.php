<?php
require_once ROOT_DIR . '/core/libs/singleton.php';
require_once ROOT_DIR . '/core/libs/spyc.php';
require_once ROOT_DIR . '/core/wrap_classes.php';
require_once ROOT_DIR . '/core/core_helpers.php';
require_once ROOT_DIR . '/core/base_app.php';

/**
 *
 */
final class Configure {

  public static function & init() {
    self::$config = Spyc::YAMLLoad(ROOT_DIR . DS . 'config' . DS . 'config.yml');
    return self::$config;
  }

  private static $config = array();

  public static function write($key = NULL, $value = NULL) {
    if ($key == NULL) {
      return false;
    } else {
      self::$config[$key] = $value;
      return true;
    }
  }

  public static function & fetch($key = NULL) {
    if ($key == NULL) {
      return self::$config;
    } else {
      if (isset(self::$config)) {
        return self::$config[$key];
      } else {
        return NULL;
      }
    }
  }

}

final class Session {
  public static function init() {
    return session_start();
  }

  public static function destroy() {
    return session_destroy();
  }

  public static function write($key = NULL, $value = NULL) {
    if ($key == NULL) {
      return false;
    } else {
      $_SESSION[$key] = $value;
      return true;
    }
  }

  public static function remove($key) {
    if ($key == NULL || !isset($_SESSION[$key])) {
      return false;
    } else {
      unset($_SESSION[$key]);
      return true;
    }
  }

  public static function read($key = NULL) {
    if ($key == NULL || !isset($_SESSION[$key])) {
      return NULL;
    } else {
      return $_SESSION[$key];
    }
  }

}

final class Kernel extends Singleton {
  private $initialized = false;

  public function init() {
    try {
      Session::init();
      $config = Configure::init();
      DB::setup($config['db']['conn_str'], $config['db']['user'], $config['db']['passwd']);
      View::$DEV_MODE = $config['dev_mode'];
      View::assignGlobal('CATES', Configure::fetch('apps'));
      View::assignGlobal('DIR_NAME', Configure::fetch('dir_name'));
      Configure::write('index_url', 'http://' . $_SERVER['HTTP_HOST'] . '/' . Configure::fetch('dir_name'));
      
      $this->initialized = true;
    } catch (exception $e) {
      $this->initialized = false;
      
      $redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . strrchr(dirname($_SERVER['PHP_SELF']), '/') . '/public/400.html';
      header("Location: " . $redirect_url);
      
      exit;
    }
    return true;
  }

  public function boot() {
    if (dirname($_SERVER['PHP_SELF']) == str_replace(DS, DS, strrchr(dirname($_SERVER['PHP_SELF']), '/'))) {
      $apps = Configure::fetch('apps');
      $redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . strrchr(dirname($_SERVER['PHP_SELF']), '/') . '/apps/' . $apps['default']['apps']['default']['path'];
      header("Location: " . $redirect_url);
    } else {
      $cur_app = preg_replace("/^(\S+)\/(\w+)\//i", "$2", $_SERVER['REQUEST_URI']);
      Configure::write('app', $cur_app);
      View::assignGlobal('CUR_APP', $cur_app);

      $app = new App;
      $app -> run();
    }
  }

}
?>
