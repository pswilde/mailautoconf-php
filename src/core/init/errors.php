<?php
class Errors {
  public function throw_error($err){
    header('Content-Type: application/json');  // <-- header declaration
    $error = false;
    // Some Saved error messages - more to be added
    switch ($err) {
      case "NotFound":
        $error = new ErrorMessage("Error","404","Not Found");
        break;
      case "Unauthorized":
        $error = new ErrorMessage("Error","402","Unauthorized");
        break;
      default:
        $error = new ErrorMessage("Error","500","Internal Error");
        break;
    }
    // Output error in JSON format
    echo json_encode($error, true);    // <--- encode
  }
}
class ErrorMessage {
  public $error;
  public $code;
  public $message;
  public $commit;
  public function __construct($err,$code,$msg){
    $this->error = $err;
    $this->code = $code;
    $this->message = $msg;
    $this->url = Core::$CurrentPage;
    $this->commit = Core::$Config["CommitID"];
  }
}
