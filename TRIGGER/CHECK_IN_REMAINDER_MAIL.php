<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************CHECK IN REMAINDER MAIL TRIGGER *************************************//

//DONE BY:LALITHA
//VER 0.03-SD:25/02/2015 ED:25/02/2015, TRACKER NO:74,DESC:updated display name
//DONE BY:RAJA
//VER 0.02-SD:08/01/2015 ED:08/01/2015, TRACKER NO:175,DESC:CHANGED LOGIN ID AS EMPLOYEE NAME
//DONE BY:RAJA
//VER 0.01-INITIAL VERSION, SD:29/12/2014 ED:29/12/2014,TRACKER NO:74
//*********************************************************************************************************//-->
<?php
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
include "../TSLIB/TSLIB_COMMON_FUNCTIONS.php";
include "../TSLIB/TSLIB_CONNECTION.php";
date_default_timezone_set('Asia/Kolkata');
$get_active_user=array();
$get_active_user=get_active_login_id();//GET ALL ACTIVE LOGIN ID
$currentdate=date("Y-m-d");//CURRENT DATE
$Current_day=date('l');//CURRENT DAY
$check_ph=Check_public_holiday($currentdate);//CHECK CURRENT DATE IS IN PUBLIC HOLIDAY
$check_onduty=check_onduty($currentdate);//CHECK CURRENT DATE IS IN ONDUTY
$get_login_id=array();
$get_login_id=get_chekin_login_id($currentdate);//GET WHO ARE ALL DID CHECKIN FOR CURRENT DATE
$ph_array=get_public_holiday();// GET ALL PUBLIC HOLIDAY
$select_admin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='ADMIN'";
$select_sadmin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='SUPER ADMIN'";
$admin_rs=mysqli_query($con,$select_admin);
$sadmin_rs=mysqli_query($con,$select_sadmin);
if($row=mysqli_fetch_array($admin_rs)){
    $admin=$row["ULD_LOGINID"];//get admin
}
if($row=mysqli_fetch_array($sadmin_rs)){
    $sadmin=$row["ULD_LOGINID"];//get super admin
}
$select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=12";
$select_template_rs=mysqli_query($con,$select_template);
if($row=mysqli_fetch_array($select_template_rs)){
    $mail_subject=$row["ETD_EMAIL_SUBJECT"];
    $body=$row["ETD_EMAIL_BODY"];
}

$get_displayname=get_displayname();

if($Current_day!='Sunday'){
    if($check_ph==0 && $check_onduty==0 ){
        $remainder_array=array_diff($get_active_user,$get_login_id);
        $remainder_array=array_values($remainder_array);
        $array_length=count($remainder_array);
        for($i=0;$i<$array_length;$i++){
            $names=$remainder_array[$i];
            $check_emp="SELECT ULD_ID from USER_ADMIN_REPORT_DETAILS where UARD_DATE='$currentdate' and ULD_ID=(SELECT ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$names')";
            $sql=mysqli_query($con,$check_emp);
            $row=mysqli_num_rows($sql);
            if($row==0){
            $select_empname="SELECT EMPLOYEE_NAME from VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS where ULD_LOGINID='$names' ";
            $select_emp_name=mysqli_query($con,$select_empname);
            if($row=mysqli_fetch_array($select_emp_name)){
                $empname=$row["EMPLOYEE_NAME"];
            }
            $bodyscript=str_replace("[MAILID_USERNAME]","$empname",$body);
            $message_body=str_replace("[DATE]",date('l jS F Y '),$bodyscript);
                //SENDING MAIL OPTIONS
                $name = $get_displayname;
                $from = $admin;
                $message1 = new Message();
                $message1->setSender($name.'<'.$from.'>');
                $message1->addTo($names);
                $message1->setSubject($mail_subject);
                $message1->setHtmlBody($message_body);

                try {
                    $message1->send();
                } catch (\InvalidArgumentException $e) {
                    echo $e;
                }


        }
        }
    }
}