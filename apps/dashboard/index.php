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
    Session::write('test', 'Test from session!');
    $this->assign('shared_func', TestSharedDomain::getInstance()->func());
    $this->assign('func', TestDomain::getInstance()->func());
    $this->assign('test_session', Session::fetch('test'));
    $this->display();
  }
}

App::getInstance()->run();
?>