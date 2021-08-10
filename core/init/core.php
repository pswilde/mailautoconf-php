<?php class Core {
  public static $Config;
  public static $CurrentPage;
  public static function root_dir(){
    return $_SERVER['DOCUMENT_ROOT'];
  }
  public static function get_current_page_name(){
    if (!isset(self::$CurrentPage->Name)){
      return "Login";
    } else {
      return self::$CurrentPage->Name;
    }
  }
  public static function get_post_data(){
    $data = [];
    foreach($_POST as $key=>$value){
      $data[$key] = $value;
    }
    return $data;
  }
  public static function get_get_data(){
    $data = [];
    foreach($_GET as $key=>$value){
      $data[$key] = $value;
    }
    return $data;
  }
  public static function random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
  public static function start_session(){
    session_start();
  }
  public static function end_session($redirect = true){
    // Unset all of the session variables
    $_SESSION = array();
    setcookie("user_session", "", time() - 3600);
    // Destroy the session.
    session_destroy();
    if($redirect){
      header("location: /?e=LogoutSuccess");
    }
  }
  public static function get_current_git_commit( $branch='master' ) {
    if ( $hash = file_get_contents( sprintf( '.git/refs/heads/%s', $branch ) ) ) {
      return trim($hash);
    } else {
      return false;
    }
  }
}
