<?php
class Responder {
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
    // Send json encoded response
    echo json_encode($response, true);
  }
  private function get_response(){
    $resp = false;
    switch (Core::$CurrentPage){
      case "get/test":
        $resp = $this->dummy_response();
        break;
      case "get/all":
        $resp = $this->all_urls();
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
  private function all_urls(){

    // This would be the default request from, say, an app.
    $response = new Response();

    // TODO:: Will work out a better message later
    $response->message = "All URLs Requested";

    // Cycle through each service and add to payload
    foreach (Core::$Config["Services"] as $key => $service){
      $response->payload[$key] = $service;
    }

    return $response;
  }
  private function dummy_response(){
    // Generate a dummy response for testing
    $response = new Response();
    $response->message = "OK, here's some scrumptious data! Enjoy!";
    $response->payload = array("data" => array("some_data" => "Ohhhhhmmmm nom nom nom nom nom nom",
                                               "extra_data" => array("garnish" => "buuuuuuuuuuurp")),
                                "more_data" => "yuuuuuum yum yum yum");
    return $response;
  }
  private function get_test_working(){
    // Generate a dummy response for testing
    $response = new Response();
    $response->message = "Success! Things are working! Please request a valid URL i.e. get/all";
    return $response;
  }
}
class Response {
  public $url;
  public $message;
  public $payload = array();
  public function __construct(){
    // add requested page to response. I don't know why, but it could helpful for diagnostics at some point
    $this->url = Core::$CurrentPage;
    $this->commit = Core::$Config["CommitID"];
  }
}
