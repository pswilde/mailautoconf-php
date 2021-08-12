<?php
// require all necessary files
require ("core.php");
require ("user.php");
require ("responder.php");
require ("errors.php");

class Loader {
  public function request_page(){
    // Check if user is authenticated and go to the relevant section
    // Currently no authentication is in place so should always be true
    if (User::is_authenticated()){
      $this->go_to_page(true);
    } else {
      $this->go_to_page(false);
    }
  }
  public function go_to_page($authenticated) {
    switch ($authenticated) {
      case false:
        require(Core::root_dir()."/public/unauthorized.php");
        break;
      default:
        $p = $this->get_page_name();
        if(substr($p,0,1) == "/") {
          // Remove first slash if exists
          Core::$CurrentPage = substr($p,1);
        } else {
          Core::$CurrentPage = $p;
        }
        require_once(Core::root_dir()."/public/respond.php");
        break;
    }
  }
  public function get_page_name(){
    $uri = Core::get_get_data();
    $page = "/none";
    if(isset($uri["path"])){
      $page = parse_url($uri["path"]);
      $page = $page["path"];
    }
    return $page;
  }
}
