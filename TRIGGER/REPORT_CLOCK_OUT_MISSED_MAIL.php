<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************REPORT CLOCK OUT MISSED MAIL TRIGGER *************************************//
//DONE BY:LALITHA
//VER 0.02-SD:25/02/2015 ED:25/02/2015, TRACKER NO:74,DESC:updated display name
//VER 0.01-INITIAL VERSION, SD:18/02/2015 ED:18/02/2015,TRACKER NO:74
//*********************************************************************************************************//-->
<?php
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
include "../TSLIB/TSLIB_COMMON_FUNCTIONS.php";
include "../TSLIB/TSLIB_CONNECTION.php";
$currentdate=date("Y-m-d");//CURRENT DATE
//$currentdate=date("Y-m-d",strtotime("-1 days"));//yesterday DATE
$select_admin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='ADMIN'";
$select_sadmin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='SUPER ADMIN'";
$admin_rs=mysqli_query($con,$select_admin);
$sadmin_rs=mysqli_query($con,$select_sadmin);
$select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=15";
$select_template_rs=mysqli_query($con,$select_template);
if($row=mysqli_fetch_array($select_template_rs)){
    $mail_subject=$row["ETD_EMAIL_SUBJECT"];
    $body=$row["ETD_EMAIL_BODY"];
}
$mail_subject=get_displayname();
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
$mail_subject=str_replace("[DATE]",date("d/m/Y"),$mail_subject);
$message='<html><body>'.'<br>'.'<h> '.$sub.'</h>'.'<br>'.'<br>'.'<table border=1  width=470 ><thead  bgcolor=#6495ed style=color:white><tr  align="center"  height=2px ><td width=260><b>EMPLOYEE NAME</b></td><td width=200><b>REPORT DATE</b></td></tr></thead>';
//$query="SELECT EMPLOYEE_NAME, DATE_FORMAT(CURDATE(),'%W-%d-%M-%Y')AS REPORT_DATE FROM VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS VW,EMPLOYEE_CHECK_IN_OUT_DETAILS ECIOD WHERE ECIOD_DATE='$currentdate' AND ECIOD_CHECK_OUT_TIME IS NULL AND ECIOD.ULD_ID=VW.ULD_ID ORDER BY EMPLOYEE_NAME";
$result = $con->query("CALL SP_TS_CLOCK_OUT_MISSED_DETAILS('$admin',@TEMP_CLOCK_OUT_MISSED_DETAILS)");
if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
$select = $con->query('SELECT @TEMP_CLOCK_OUT_MISSED_DETAILS');
$result = $select->fetch_assoc();
$temp_table_name= $result['@TEMP_CLOCK_OUT_MISSED_DETAILS'];
$select_data="select * from $temp_table_name ORDER BY EMPLOYEE_NAME ";
$select_data_rs=mysqli_query($con,$select_data);
$row=mysqli_num_rows($select_data_rs);
$x=$row;
if($x>0){
    while($row=mysqli_fetch_array($select_data_rs)){
        $adm_employeename=$row["EMPLOYEE_NAME"];
        $adm_date=$row["REPORT_DATE"];

        $message=$message. "<tr><td width=220>".$adm_employeename."</td><td width=150>".$adm_date."</td></tr>";
    }
    $message=$message."</table></body></html>";
    $REP_subject_date=$mail_subject;

//SENDING MAIL OPTIONS
    $name = $mail_subject;
    $from = $admin;
    $message1 = new Message();
    $message1->setSender($name.'<'.$from.'>');
    $message1->addTo($admin);
    $message1->addCc($sadmin);
    $message1->setSubject($REP_subject_date);
    $message1->setHtmlBody($message);

    try {
        $message1->send();
    } catch (\InvalidArgumentException $e) {
        echo $e;
    }
}

$drop_query="DROP TABLE $temp_table_name ";
mysqli_query($con,$drop_query);

