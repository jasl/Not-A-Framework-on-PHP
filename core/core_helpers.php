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
class ValidationHelper extends Singleton {
  
}

/**
 * 
 */
class ViewHelper extends Singleton {
	
}

/**
 * 
 */
class AppHelper extends Singleton {
	
}


?>