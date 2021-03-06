<?php
// Get some core information to help with generation.
$services = Core::$Config["Services"];
$data = Core::get_get_data();
if (isset($data["emailaddress"])) {
  $email_address = $data["emailaddress"];
}
// The below link has config-v1.1.xml information
// https://wiki.mozilla.org/Thunderbird:Autoconfiguration:ConfigFileFormat
?>
<clientConfig version="1.1">
 <emailProvider id="<?php echo Core::$Config["PrimaryDomain"]?>">
   <?php foreach (Core::$Config["Domain"] as $domain){ ?>
   <domain><?php echo $domain; ?></domain>
   <?php } ?>
   <displayName>%EMAILADDRESS%</displayName>
   <?php if($services["InMail"]&& $services["InMail"]["Enabled"]){
     $service = $services["InMail"]; ?>
     <incomingServer type="<?php echo strtolower($service["Type"]);?>">
       <hostname><?php echo $service["Server"];?></hostname>
       <port><?php echo $service["Port"];?></port>
       <socketType><?php echo $service["SocketType"];?></socketType>
       <username><?php echo $this->get_username($service,$email_address); ?></username>
       <authentication><?php echo $service["Authentication"];?></authentication>
     </incomingServer>
   <?php }
   if($services["OutMail"]&& $services["OutMail"]["Enabled"]){
     $service = $services["OutMail"]; ?>
   <outgoingServer type="<?php echo strtolower($service["Type"]);?>">
     <hostname><?php echo $service["Server"];?></hostname>
     <port><?php echo $service["Port"];?></port>
     <socketType><?php echo $service["SocketType"];?></socketType>
     <username><?php echo $this->get_username($service,$email_address);?></username>
     <authentication><?php echo $service["Authentication"];?></authentication>
   </outgoingServer>
   <?php }
   if ($services["AddressBook"] && $services["AddressBook"]["Enabled"]) {
     $service = $services["AddressBook"]; ?>
    <addressBook type="<?php echo strtolower($service["Type"]); ?>">
      <username><?php echo $this->get_username($service,$email_address);?></username>
      <authentication><?php echo $service["Authentication"];?></authentication>
      <serverURL><?php echo $service["Server"];?></serverURL>
    </addressBook>
    <?php }
    if ($services["Calendar"] && $services["Calendar"]["Enabled"]){
      $service = $services["Calendar"] ;?>
    <calendar type="<?php echo strtolower($service["Type"]);?>">
      <username><?php echo $this->get_username($service,$email_address);?></username>
      <authentication><?php echo $service["Authentication"];?></authentication>
      <serverURL><?php echo $service["Server"];?></serverURL>
    </calendar>
    <?php }
    if ($services["WebMail"] && $services["WebMail"]["Enabled"]) {
      $service = $services["WebMail"]; ?>
    <webMail>
      <loginPage url="<?php echo $service["Server"];?>" />
      <loginPageInfo url="<?php echo $service["Server"];?>">
        <username><?php echo $this->get_username($service,$email_address);?></username>
        <usernameField id="<?php echo $service["UsernameDivID"];?>" name="<?php echo $service["UsernameDivName"];?>" />
        <passwordField name="<?php echo $service["PasswordDivName"];?>" />
        <loginButton id="<?php echo $service["SubmitButtonID"];?>" name="<?php echo $service["SubmitButtonName"];?>"/>
      </loginPageInfo>
    </webMail>
    <?php } ?>
 </emailProvider>
</clientConfig>
