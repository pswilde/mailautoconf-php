<?php class Core {
  public static $Config;
  public const VERSION = "0.0.7";
  public static $CurrentPage;
  public static function root_dir(){
    return $_SERVER['DOCUMENT_ROOT'];
  }

  public static function get_post_data(){
    // Gets the POST request into an array
    $data = [];
    foreach($_POST as $key=>$value){
      $data[$key] = $value;
    }
    return $data;
  }
  public static function get_get_data(){
    // Gets the GET request into an array
    $data = [];
    foreach($_GET as $key=>$value){
      $data[$key] = $value;
    }
    return $data;
  }
  public static function random_string($length = 10) {
    // Generates a random string - unused currently
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
  public static function start_session(){
    // Starts a session, unused currently
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
    // Gets the current git commit ID - for testing
    $gitref = sprintf( self::root_dir().'/../.git/refs/heads/%s', $branch );
    if ( file_exists($gitref) && $hash = file_get_contents( $gitref ) ) {
      return trim($hash);
    } else {
      return false;
    }
  }
  public static function full_url(){
    // Gets the full requested URL
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      $addr = "https";
    } else {
      $addr = "http";
    }
    // Here append the common URL characters.
    $addr .= "://";

    // Append the host(domain name, ip) to the URL.
    $addr .= $_SERVER['HTTP_HOST'];

    // Append the requested resource location to the URL
    $addr .= $_SERVER['REQUEST_URI'];

    // Return the address
    return $addr;
  }
}
