<?php
class Responder {
  private $Response;
  public function show_response(){
    // get the response detailed by the url requested
    $response = $this->get_response();
    if ($response){
      // send response
      $this->send_response($response);
    } else {
      // Send error instead
      $e = new Errors();
      $e->throw_error("NotFound");
    }
  }
  private function send_response($response){
    switch ($response->content_type){
      case "json":
        header('Content-Type: application/json');
        // Send json encoded response
        echo json_encode($response);
        break;
      case "ms-json":
        header('Content-Type: application/json');
        // Send json encoded response
        // This is currently an undocumented file from Microsoft so it's not ready yet
        echo json_encode($response->content, JSON_UNESCAPED_UNICODE);
        break;
      case "xml":
        header('Content-Type: application/xml');
        include ($response->content);
        break;
    }


  }
  private function get_response(){
    $resp = false;
    // Handle the requested URL, using as many known autoconfiguration urls as possible
    switch (strtolower(Core::$CurrentPage)){
      case "get/test":
        $resp = $this->dummy_response();
        break;
      case "get/all":
        $resp = $this->all_urls();
        break;
      case "mail/autoconfig.xml":
      case "mail/config-v1.1.xml":
        $resp = $this->moz_auto_config();
        break;
      case "autodiscover/autodiscover.xml":
        $resp = $this->ms_autodiscover();
        break;
      case "autodiscover/autodiscover.json":
        $resp = $this->ms_autodiscover_json();
        break;
      case "none":
      case "test":
      case "home":
      case "root":
        $resp = $this->get_test_working();
        break;
      default:
        break;
    }
    return $resp;
  }
  private function get_username($service,$email_address) {
    $username = "%EMAILADDRESS%";
    if(!$service["UsernameIsFQDN"]) {

      preg_match("/^[^@]+/",$email_address,$matches);
      if (count($matches) > 0){
        $username = $matches[0];
      }
    } else if ($service["RequireLogonDomain"]) {
      $username = preg_replace("/[^@]+$/",Core::$Config["LogonDomain"],$email_address,1);
    }
    return $username;
  }
  private function all_urls(){
    $response = new Response();
    // Not really useful, unless some lovely app developers want to parse it for their app :)
    // TODO:: Will work out a better message later
    $response->message = "All URLs Requested";

    // Cycle through each service and add to payload
    foreach (Core::$Config["Services"] as $key => $service){
      if (!$service["Enabled"]) {
        continue;
      }
      $response->content[$key] = $service;
    }

    return $response;
  }
  private function moz_auto_config(){
    // The default Thunderbird or Evolution autoconfig.xml file
    $response = new Response();
    $response->content_type = "xml";
    $response->content = "public/autoconfig.php";
    return $response;
  }
  private function ms_autodiscover(){
    // The default Microsoft Autodiscover.xml file
    $response = new Response();
    $response->content_type = "xml";
    $response->content = "public/autodiscover.php";
    return $response;
  }
  private function ms_autodiscover_json(){
    // The new Microsoft Autodiscover.json file - undocumented
    $response = new Response();
    $response->content_type = "ms-json";
    if (strtolower($_GET['Protocol']) == 'autodiscoverv1') {
      $response->content = new MSAutodiscoverJSONResponse();
      $response->content->Protocol = "AutodiscoverV1";
      $response->content->Url = Core::$Config["BaseURL"] . "/Autodiscover/Autodiscover.xml";
    } else {
      $response->content = new MSAutodiscoverJSONError();
      http_response_code(400);
      $response->headers_set = true;
      $response->content->ErrorCode = "InvalidProtocol";
      $response->content->ErrorMessage = "The given protocol value '" . $_GET['Protocol'] . "' is invalid. Supported values are 'AutodiscoverV1'";
    }

    return $response;
  }
  private function dummy_response(){
    // Generate a dummy response for testing
    $response = new Response();
    $response->message = "OK, here's some scrumptious data! Enjoy!";
    $response->content = array("data" => array("some_data" => "Ohhhhhmmmm nom nom nom nom nom nom",
                                               "extra_data" => array("garnish" => "buuuuuuuuuuurp")),
                                "more_data" => "yuuuuuum yum yum yum");
    return $response;
  }
  private function get_test_working(){
    // Generate a dummy response for testing
    $response = new Response();
    $response->message = "Success! Things are working! Please request a valid URL i.e. /mail/config-v1.1.xml";
    return $response;
  }
}
class Response {
  public $url;
  public $content_type = "json";
  public $message;
  // public $headers_set = false;
  public $content = array();
  public function __construct(){
    // add requested page to response. I don't know why, but it could helpful for diagnostics at some point
    // Does not happen on autoconfig.xml/autodisocver.xml/autodiscover.json files
    $this->url = Core::$CurrentPage;
    if (!Core::$Config["CommitID"]){
      $this->version = Core::VERSION;
    } else {
      $this->version = "git commit ".Core::$Config["CommitID"];
    }

  }
}
class AutoConfig {
  public function prepare_autoconfig_info() {
    // TODO: Move all the code in autoconfig.php into here
    return false;
  }
}
class MSAutodiscoverJSONResponse {
  // More work to do - handling of MS Autodiscover.json requests
  public $Protocol;
  public $Url;
}
class MSAutodiscoverJSONError {
  public $ErrorCode;
  public $ErrorMessage;
}
