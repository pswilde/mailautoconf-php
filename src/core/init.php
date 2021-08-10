<?php
// this class starts the whole thing going.
class Init {
  public function start (){
    // define a constant for checking later on
    define("LETSGO",true);

    // require the Load class which also imports the Core class
    require("init/loader.php");

    // Not sure if we're using a database yet, but leaving this here.
    //require("db/db.php");

    // Get the config
    self::get_config();

    // A Session may not be necessary as this is fundamentally an API
    //Core::StartSession();

    // Continue and load the requested page
    $loader = new Loader();
    $loader->request_page();
  }
  private function get_config(){
    // define the config file location
    $config = Core::root_dir()."/config/config.ini";

    // if it doesn't exist, use the sample file
    // YOU REALLY SHOULD HAVE YOUR OWN CONFIG FILE!!!

    if (!file_exists($config)) {
      $config = Core::root_dir()."/sample-config/config.sample.ini";
    }

    // define services file location
    $services = Core::root_dir()."/config/services.ini";

    // if it doesn't exist, use the sample file
    // YOU REALLY SHOULD HAVE YOUR OWN SERVICES FILE!!!

    if (!file_exists($services)) {
      $services = Core::root_dir()."/sample-config/services.sample.ini";
    }

    // Store the config settings in the Core::Config variable
    // the "true" means it's going to parse the headers as well.
    Core::$Config = parse_ini_file($config, true);
    Core::$Config["Services"] = parse_ini_file($services, true);
    Core::$Config["CommitID"] = Core::get_current_git_commit();
  }
}
