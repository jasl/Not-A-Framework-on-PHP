<?php
require_once ROOT_DIR . '/core/libs/singleton.php';
require_once ROOT_DIR . '/core/libs/spyc.php';
require_once ROOT_DIR . '/core/wrap_classes.php';
require_once ROOT_DIR . '/core/core_helpers.php';
require_once ROOT_DIR . '/core/base_app.php';
require_once ROOT_DIR . '/core/base_domin.php';

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

?>
