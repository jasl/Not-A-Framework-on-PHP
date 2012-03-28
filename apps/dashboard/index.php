<?php
require_once '../../bootstrap.php';

class App extends BaseApp {
  public function __construct() {
    parent::__construct();
  }
  
  protected function before_filter() {
    return true;
  }
  public function do_post() {
    $this->display();
  }
  public function do_get() {
    $this->assign('shared_func', TestSharedDomin::getInstance()->func());
    $this->assign('func', TestDomin::getInstance()->func());
    $this->display();
  }
}

App::getInstance()->run();
?>