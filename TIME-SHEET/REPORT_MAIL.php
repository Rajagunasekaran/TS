<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************REPORT MAIL TRIGGER *************************************//
//DONE BY:SAFIYULLAH
//VER 0.03,SD:31/10/2014 ED:31/10/2014,TRACKER NO:74,DESC:UPDATED TABLE WIDTH
//VER 0.02,SD:24/10/2014 ED:24/10/2014,TRACKER NO:82,DESC:update subject and body to get from email template
//VER 0.01-INITIAL VERSION, SD:16/09/2014 ED:08/10/2014,TRACKER NO:82
//*********************************************************************************************************//-->

<?php
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
include "COMMON_FUNCTIONS.php";
include "CONNECTION.php";
$currentdate=date("Y-m-d");//CURRENT DATE
$select_admin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='ADMIN'";
$select_sadmin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='SUPER ADMIN'";
$admin_rs=mysqli_query($con,$select_admin);
$sadmin_rs=mysqli_query($con,$select_sadmin);
$select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=4";
$select_template_rs=mysqli_query($con,$select_template);
if($row=mysqli_fetch_array($select_template_rs)){
    $mail_subject=$row["ETD_EMAIL_SUBJECT"];
    $body=$row["ETD_EMAIL_BODY"];
}
if($row=mysqli_fetch_array($admin_rs)){
    $admin=$row["ULD_LOGINID"];//get admin
}
if($row=mysqli_fetch_array($sadmin_rs)){
    $sadmin=$row["ULD_LOGINID"];//get super admin
}
$admin_name = substr($admin, 0, strpos($admin, '.'));
$sadmin_name = substr($sadmin, 0, strpos($sadmin, '.'));
$spladminname=$admin_name.'/'.$sadmin_name;
$spladminname=strtoupper($spladminname);
$sub=str_replace("[SADMIN]","$spladminname",$body);
$sub=str_replace("[DATE]",date("d-m-Y"),$sub);
$message='<html><body>'.'<br>'.'<h> '.$sub.'</h>'.'<br>'.'<br>'.'<table border=1  width=1300 ><thead  bgcolor=#6495ed style=color:white><tr  align="center"  height=2px ><td><b>LOGIN ID</b></td><td ><b>REPORT</b></td><td><b>USER STAMP</b></td><td><b>TIMESTAMP</b></td></tr></thead>';
$query="SELECT DISTINCT A.UARD_REPORT,A.UARD_REASON,B.ULD_LOGINID,C.ULD_LOGINID as USERSTAMP,DATE_FORMAT(CONVERT_TZ(A.UARD_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') as UARD_TIMESTAMP FROM USER_ADMIN_REPORT_DETAILS A
            INNER JOIN USER_LOGIN_DETAILS B on A.ULD_ID=B.ULD_ID INNER JOIN USER_LOGIN_DETAILS C on A.UARD_USERSTAMP_ID=C.ULD_ID
              INNER JOIN USER_ACCESS D where A.UARD_DATE='$currentdate' and D.UA_TERMINATE IS null order by ULD_LOGINID";
$sql=mysqli_query($con,$query);
$row=mysqli_num_rows($sql);
$x=$row;
if($x>0){
while($row=mysqli_fetch_array($sql)){
    $adm_report=$row["UARD_REPORT"];
    $adm_userstamp=$row["USERSTAMP"];
    $adm_timestamp=$row["UARD_TIMESTAMP"];
    $adm_loginid=$row["ULD_LOGINID"];
    $adm_reason=$row["UARD_REASON"];
    if($adm_report==null){
        $final_report='REASON:'.$adm_reason;
    }
    else if($adm_reason==null){

        $final_report='REPORT:'.$adm_report;
    }
    else{

        $final_report='REPORT:'.$adm_report.'<br><br>'.'REASON:'.$adm_reason;

    }

    $message=$message. "<tr><td >".$adm_loginid."</td><td >".$final_report."</td><td >".$adm_userstamp."</td><td >".$adm_timestamp."</td></tr>";
}
$message=$message."</table></body></html>";
$mail_options = [
    "sender" => $admin,
    "to" => $admin,
    "cc"=>$sadmin,
    "subject" => $mail_subject,
    "htmlBody" => $message
];
try {
    $message = new Message($mail_options);
    $message->send();
} catch (\InvalidArgumentException $e) {
    echo $e;
}
}
