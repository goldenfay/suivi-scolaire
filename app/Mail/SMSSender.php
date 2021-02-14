<?php 

namespace App\Mail;
class SMSSender{
var $host;
var $port;
/*
* Username that is to be used for submission
*/
var $username;
/*
* password that is to be used along with username
*/
var $password;
/*
* Sender Id to be used for submitting the message
*/
var $senderId;
/*
* Message content that is to be transmitted
*/
var $message;
/*
* Mobile No is to be transmitted.
*/
var $strMobile;
/*
* What type of the message that is to be sent
* <ul>
* <li>0:means plain text</li>
* <li>1:means flash</li>
* <li>2:means Unicode (Message content should be in Hex)</li>
* <li>6:means Unicode Flash (Message content should be in Hex)</li>
* </ul>
*/
var $messageType;
/*
* Require DLR or not
* <ul>
* <li>0:means DLR is not Required</li>
* <li>1:means DLR is Required</li>
* </ul>
*/
var $Dlr;

private function sms__unicode($message){
    $hex1='';
    if (function_exists('iconv')) {
    $latin = @iconv('UTF-8', 'ISO-8859-1', $message);
    if (strcmp($latin, $message)) {
    $arr = unpack('H*hex', @iconv('UTF-8', 'UCS-2BE', $message));
    $hex1 = strtoupper($arr['hex']);


    }
    if($hex1 ==''){
    $hex2='';
    $hex='';
    for ($i=0; $i < strlen($message); $i++){
    $hex = dechex(ord($message[$i]));
    $len =strlen($hex);
    $add = 4 - $len;
    if($len < 4){
    for($j=0;$j<$add;$j++){
    $hex="0".$hex;
    }
    }
    $hex2.=$hex;
    }
    return $hex2;
    }
    else{
    return $hex1;
    }
    }
    else{
    print 'iconv Function does not Exists !';
    }
}
//Constructor..
public function __construct($host,$port,$username,$password,$sender){
    $this->host=$host;
    $this->port=$port;
    $this->username = $username;
    $this->password = $password;
    $this->senderId= $sender;

}
public function Submit($message,$mobile,$msgtype,$dlr=0){
    if($msgtype=="2" ||$msgtype=="6") {
    //Call The Function Of String To HEX.
    $message = $this->sms__unicode($message);
    try{
        //Smpp http Url to send sms.
        $live_url="http://".$this->host.":".$this->port."/bulksms/bulksms?username=".$this->username."&password=".$this->password."&type=".$msgtype."&dlr=".$dlr."&destination=".$mobile."&source=".$this->senderId."&message=".$message."";
        // dd($live_url);
        $parse_url=file($live_url);
        return $parse_url[0];
    }catch(\Throwable $e){
        throw new \Exception($e->getMessage());
    }
    }
    else
        $message=urlencode($message);
    try{
        //Smpp http Url to send sms.
        $live_url="http://".$this->host.":".$this->port."/bulksms/bulksms?username=".$this->username."&password=".$this->password."&type=".$msgtype."&dlr=".$dlr."&destination=".$mobile."&source=".$this->senderId."&message=".$message."";
        $parse_url=file($live_url);
        return $parse_url[0];
    }
    catch(\Throwable $e){
        throw new \Exception($e->getMessage());
    }
}
}
// //Call The Constructor.
// $obj = new Sender("IP","Port","","","Tester"," "ال عرب ية "," 919990001245
// ,"2","1");
// $obj->Submit ();
?>
