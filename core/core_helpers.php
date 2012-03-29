<?php
/**
 *
 */
class AuthorizeHelper {
  public static function set_role($role = NULL) {
    if (is_null($role)) {
      Session::write('cur_role', DEFAULT_ROLE);
    } else {
      Session::write('cur_role', $role);
    }
  }

  public static function get_cur_role() {
    $role = Session::fetch('cur_role');
    return is_null($role) ? DEFAULT_ROLE : $role;
  }

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
    if (Configure::fetch() > 0) {
      if ($showFrom) {
        $calledFrom = debug_backtrace();
        echo '<strong>' . substr(str_replace(ROOT_DIR, '', $calledFrom[0]['file']), 1) . '</strong>';
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

  public static function redirect_to_root() {
    $url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . Configure::fetch('dir_name');
    AppHelper::redirect($url);
  }

}
?>