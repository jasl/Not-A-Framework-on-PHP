<?php
abstract class Singleton {
  private static $_instance = array();
  protected function __construct() {
  }

  final private function __clone() {
  }

  final public static function getInstance() {
    $class_name = get_called_class();//php>=5.3
    if (!isset(self::$_instance[$class_name])) {
      self::$_instance[$class_name] = new $class_name();
    }
    return self::$_instance[$class_name];
  }

}
?>