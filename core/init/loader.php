<?php
require ("core.php");
require ("user.php");
require ("responder.php");
require ("errors.php");
class Loader {
  public function request_page(){
    // Check if user is authenticated and go to the relevant section
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
        if (substr($p,0,6) != "/admin") {
          header('Content-Type: application/json');  // <-- header declaration
        }
        // if($p != "none") {
          Core::$CurrentPage = substr($p,1);
          require_once(Core::root_dir()."/public/respond.php");
        // } else {
        //   Core::$CurrentPage = "Error";
        //   require_once(Core::RootDir()."/public/error.php");
        // }
        break;
    }
  }
  public function get_page_name(){
    $uri = Core::get_get_data();
    $page = "/none";
    if(isset($uri["page"])){
      $page = parse_url($uri["page"]);
      $page = $page["path"];
    }
    return $page;
  }
}
