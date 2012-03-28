<?php
/**
 *
 */
class AuthorizeHelper extends Singleton {
  protected function __construct() {
    $this -> role_auth_list = array();

    $apps = Configure::fetch('apps');
    foreach ($apps as $cate_key => $cate_apps) {
      foreach ($cate_apps['apps'] as $key => $value) {
        $app_lowest_role_level = $value['lowest_role_level'];
        if (!isset($this -> role_auth_list[$app_lowest_role_level])) {
          $this -> role_auth_list[$app_lowest_role_level] = array();
        }
        array_push($this -> role_auth_list[$app_lowest_role_level], $value['path']);
      }
    }
    ksort($this -> role_auth_list);
  }

  public function authorize($role_level = 10, $app_name = '') {
    if (isset($this -> role_auth_list[$role_level])) {
      foreach ($this->role_auth_list as $lowest_role_level => $apps) {
        if ($lowest_role_level < $role_level) {
          continue;
        } else {
          if (in_array($app_name, $this -> role_auth_list[$lowest_role_level])) {
            return true;
          }
        }
      }
    }
    return false;
  }

  private $role_auth_list = NULL;
}

/**
 *
 */
class ValidationHelper {

}

/**
 *
 */
class AppHelper {
  public static function debug($var = false, $showHtml = false, $showFrom = true) {
    if (Configure::read() > 0) {
      if ($showFrom) {
        $calledFrom = debug_backtrace();
        echo '<strong>' . substr(str_replace(ROOT, '', $calledFrom[0]['file']), 1) . '</strong>';
        echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
      }
      echo "\n<pre class=\"cake-debug\">\n";

      $var = print_r($var, true);
      if ($showHtml) {
        $var = str_replace('<', '&lt;', str_replace('>', '&gt;', $var));
      }
      echo $var . "\n</pre>\n";
    }
  }

  public static function redirect($url) {
    header("Location: " . $url);
    exit ;
  }

}
?>