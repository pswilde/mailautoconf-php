<?php
$conf = Core::$Config["Services"];
//get raw POST data so we can extract the email address
$data = file_get_contents("php://input");
file_put_contents(Core::root_dir()."/xmltest");
preg_match("/\<EMailAddress\>(.*?)\<\/EMailAddress\>/", $data, $matches);
echo '<?xml version="1.0" encoding="utf-8" ?>'; ?>
<Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006">
   <Response xmlns="http://schemas.microsoft.com/exchange/autodiscover/outlook/responseschema/2006a">
       <Account>
           <AccountType>email</AccountType>
           <Action>settings</Action>
           <?php if ($conf["InMail"]){
             $in = $conf["InMail"];?>
            <Protocol>
                <Type><?php echo $in["Type"];?></Type>
                <Server><?php echo $in["Server"];?></Server>
                <Port><?php echo $in["Port"];?></Port>
                <DomainRequired><?php echo Core::$Config["RequireAuthDomain"] ? "on" : "off";?></DomainRequired>
                <LoginName><?php echo $matches[1]; ?></LoginName>
                <SPA><?php echo $in["SPA"] ? "on" : "off";?></SPA>
                <SSL><?php echo $in["SocketType"] == "SSL" ? "on" : "off";?></SSL>
                <AuthRequired><?php echo $in["NoAuthRequired"] ? "off" : "on";?></AuthRequired>
            </Protocol>
            <?php }
            if ($conf["OutMail"]) {
              $out = $conf["OutMail"];?>
            <Protocol>
                <Type><?php echo $out["Type"];?></Type>
                <Server><?php echo $out["Server"];?></Server>
                <Port><?php echo $out["Port"];?></Port>
                <DomainRequired><?php echo Core::$Config["RequireAuthDomain"] ? "on" : "off";?></DomainRequired>
                <LoginName><?php echo $matches[1]; ?></LoginName>
                <SPA><?php echo $in["SPA"] ? "on" : "off";?></SPA>
                <Encryption><?php echo $in["SocketType"];?></Encryption>
                <AuthRequired><?php echo $in["NoAuthRequired"] ? "off" : "on";?></AuthRequired>
                <UsePOPAuth><?php echo $in["POPAuth"] ? "on" : "off";?></UsePOPAuth>
                <SMTPLast><?php echo $in["SMTPLast"] ? "on" : "off";?></SMTPLast>
            </Protocol>
            <?php } ?>
       </Account>
   </Response>
</Autodiscover>
