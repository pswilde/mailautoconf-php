<?php
$conf = Core::$Config["Services"];
$data = Core::get_get_data();
$email_provided = false;
$display_name = false;
$emailaddress = false;
if ($data["emailaddress"]) {
  $email_address = $data["emailaddress"];
  $display_name = $email_address;
  $email_provided = true;
} else if ($data["path"]) {
  $query = parse_url($data["path"]);
  $email_address = explode("=",$query["query"]);
  if ($email_address[0] == "emailaddress") {
    $email_address = $email[1];
    $email_provided = true;
    $display_name = $email_address;
  }
}
if ($email_provided) {
  if(!Core::$Config["RequireAuthDomain"]) {
    $email_address = str_ireplace("@".Core::$Config["Domain"],"",$email_address);
  } else if (Core::$Config["LogonDomain"]) {
    $email_address = str_ireplace(Core::$Config["Domain"],Core::$Config["LogonDomain"],$email_address);
  }
}
?>
<clientConfig version="1.1">
 <emailProvider id="<?php echo Core::$Config["Domain"]?>">
   <domain><?php echo Core::$Config["Domain"]?></domain>
   <displayName><?php echo $email_provided ? $display_name : "%EMAILADDRESS%" ;?></displayName>
   <?php if($conf["InMail"]){
     $in = $conf["InMail"]; ?>
     <incomingServer type="<?php echo strtolower($in["Type"]);?>">
       <hostname><?php echo $in["Server"];?></hostname>
       <port><?php echo $in["Port"];?></port>
       <socketType><?php echo $in["SocketType"];?></socketType>
       <username><?php echo $email_provided ? $email_address : "%EMAILADDRESS%";?></username>
       <authentication><?php echo $in["Authentication"];?></authentication>
     </incomingServer>
   <?php } ?>
   <?php if($conf["OutMail"]){
     $out = $conf["OutMail"]; ?>
   <outgoingServer type="<?php echo strtolower($out["Type"]);?>">
     <hostname><?php echo $out["Server"];?></hostname>
     <port><?php echo $out["Port"];?></port>
     <socketType><?php echo $out["SocketType"];?></socketType>
     <username><?php echo $email_provided ? $email_address : "%EMAILADDRESS%";?></username>
     <authentication><?php echo $out["Authentication"];?></authentication>
   </outgoingServer>
   <?php } ?>
 </emailProvider>
</clientConfig>
