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
    // parse the default config file for default values
    $default_config = parse_ini_file(Core::root_dir()."/default-config/config.default.ini", true);
    // define the custom config file location
    $config_file = Core::root_dir()."/config/config.ini";
    $config = array();
    if (file_exists($config_file)) {
      // if a custom config file exists then parse that file too
      $config = parse_ini_file($config_file, true);
    }
    // merge the default config with the custom config
    Core::$Config = array_merge($default_config,$config);
    Core::$Config["PrimaryDomain"] = Core::$Config["Domain"][0];
    
    // parse the default services file for default values
    $default_services = parse_ini_file(Core::root_dir()."/default-config/services.default.ini", true);

    // define the custom services file location
    $services_file = Core::root_dir()."/config/services.ini";
    $services = array();
    if (file_exists($services_file)) {
      // if a custom config file exists then parse that file too
      $services = parse_ini_file($services_file, true);
    }

    // merge the default config with the custom config
    Core::$Config["Services"] = array_merge($default_services,$services);

    // get the current git commit, if it exists. For testing
    Core::$Config["CommitID"] = Core::get_current_git_commit();
  }
}
