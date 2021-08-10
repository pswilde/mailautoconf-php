<?php
$conf = Core::$Config["Services"];
$data = Core::get_get_data();
$email_provided = false;
if ($data["path"]) {
  $query = parse_url($data["path"]);
  $email = explode("=",$query["query"]);
  if ($email[0] == "emailaddress") {
    $email = $email[1];
    $email_provided = true;
    if (Core::$Config["LogonDomain"]) {
      $email = str_ireplace(Core::$Config["Domain"],Core::$Config["LogonDomain"],$email);
    }
  }
}
?>
<clientConfig version="1.1">
 <emailProvider id="<?php echo Core::$Config["Domain"]?>">
   <domain><?php echo Core::$Config["Domain"]?></domain>
   <displayName><?php echo $email_provided ? $email : "%EMAILADDRESS%" ;?></displayName>
   <?php if($conf["InMail"]){
     $in = $conf["InMail"]; ?>
     <incomingServer type="<?php echo strtolower($in["Type"]);?>">
       <hostname><?php echo $in["Server"];?></hostname>
       <port><?php echo $in["Port"];?></port>
       <socketType><?php echo $in["SocketType"];?></socketType>
       <username><?php echo $email_provided ? $email : "%EMAILADDRESS%";?></username>
       <authentication><?php echo $in["Authentication"];?></authentication>
     </incomingServer>
   <?php } ?>
   <?php if($conf["OutMail"]){
     $out = $conf["OutMail"]; ?>
   <outgoingServer type="<?php echo strtolower($out["Type"]);?>">
     <hostname><?php echo $out["Server"];?></hostname>
     <port><?php echo $out["Port"];?></port>
     <socketType><?php echo $out["SocketType"];?></socketType>
     <username><?php echo $email_provided ? $email : "%EMAILADDRESS%";?></username>
     <authentication><?php echo $out["Authentication"];?></authentication>
   </outgoingServer>
   <?php } ?>
 </emailProvider>
</clientConfig>
