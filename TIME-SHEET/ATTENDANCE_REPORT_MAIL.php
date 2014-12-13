<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************ATTENDANCE REPORT MAIL TRIGGER *************************************//
//VER 0.01-INITIAL VERSION, SD:28/11/2014 ED:28/11/2014,TRACKER NO:74
//*********************************************************************************************************//-->
<?php
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
include "COMMON_FUNCTIONS.php";
include "CONNECTION.php";
include "GET_USERSTAMP.php";
$month_end = date('Y-m-d',strtotime('last day of this month'));
$current_date=date('Y-m-d');

if($month_end==$current_date){
$select_admin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='ADMIN'";
$select_sadmin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='SUPER ADMIN'";
$admin_rs=mysqli_query($con,$select_admin);
$sadmin_rs=mysqli_query($con,$select_sadmin);
$select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=11";
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
$month_year=date('F-Y');
$admin_name = substr($admin, 0, strpos($admin, '.'));
$sadmin_name = substr($sadmin, 0, strpos($sadmin, '.'));
$spladminname=$admin_name.'/'.$sadmin_name;
$spladminname=strtoupper($spladminname);
// splitting the body msg
$email_body;
$body_msg =explode(",", $body);
$length=count($body_msg);
for($i=0;$i<$length;$i++){
    $email_body.=$body_msg[$i].'<br><br>';
}
$mail_subject=str_replace("[MONTH]",$month_year,$mail_subject);
$result = $con->query("CALL SP_EMPLOYEE_ATTENDANCE_CALCULATION ('$month_year','','$UserStamp',@TEMP_USER_ABSENT_COUNT,@TOTAL_DAYS,@WORKING_DAYS)");
if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
$select = $con->query('SELECT @TEMP_USER_ABSENT_COUNT,@TOTAL_DAYS');
$result = $select->fetch_assoc();
$temp_table_name= $result['@TEMP_USER_ABSENT_COUNT'];
$result1 = $con->query("CALL SP_TS_REPORT_COUNT_ABSENT_FLAG('$month_year','$UserStamp',@TEMP_USER_ABSENT_COUNT)");
if(!$result1) die("CALL failed: (" . $con->errno . ") " . $con->error);
$select = $con->query('SELECT @TEMP_USER_ABSENT_COUNT');
$result1 = $select->fetch_assoc();
$temp_table_name1= $result1['@TEMP_USER_ABSENT_COUNT'];
$total_days=$result['@TOTAL_DAYS'];
$select_data="SELECT * FROM $temp_table_name A LEFT JOIN $temp_table_name1 B on B.UNAME=A.LOGINID ORDER BY LOGINID ASC ";
$select_data_rs1=mysqli_query($con,$select_data);
$select_data_rs=mysqli_query($con,$select_data);
if($row=mysqli_fetch_array($select_data_rs1)){
    $total_working_days=$row['NO_OF_DAYS'];
}
// replace strings in body msg
$replace= array("[SADMIN]", "[MONTH]","[TDAY]", "[WDAY]");
$str_replaced  = array($spladminname,$month_year,$total_days,$total_working_days);
$newphrase = str_replace($replace, $str_replaced, $email_body);
$row=mysqli_num_rows($select_data_rs);
$x=$row;
$values_array=array();
$message='<html><body>'.'<br>'.'<h> '.$newphrase.'</h>'.'<br>'.'<br>'.'<table border=1px  width=500 colspan=3px cellpadding=3px  class="srcresult"><thead  bgcolor=#6495ed style=color:white><tr align="center"  height=2px ><td><b>LOGIN ID</b></td><td width=100 nowrap><b>NO OF PRESENT</b></td><td width=90 nowrap><b>NO OF ABSENT</b></td><td width=90 nowrap><b>NO OF ONDUTY</b></td><td  width=120 nowrap><b>PERMISSION HOUR(S)</b></td><td width=90 nowrap><b>REPORT ENTRY MISSED</b></td><td width=100 nowrap><b>WORKED IN HOLIDAYS</b></td></tr></thead>';
while($row=mysqli_fetch_array($select_data_rs)){
    $absent_count=$row['ABSENT_COUNT'];
    $no_of_present=$row['NO_OF_PRESENT'];
    $no_of_absent=$row['NO_OF_ABSENT'];
    $no_of_onduty=$row['NO_OF_ONDUTY'];
    $permission=$row['PERMISSION_HRS'];
    $login_id=$row['LOGINID'];
    $work_in_holiday=$row['WORKING_IN_HOLIDAYS'];
    if($permission<0.5){
        $permission=' ';
    }
    if($no_of_onduty<0.5){
        $no_of_onduty=' ';
    }
    if($no_of_absent<0.5){
        $no_of_absent=' ';
    }
    if($no_of_present<0.5){
        $no_of_present=' ';
    }
    if($work_in_holiday<0.5){
        $work_in_holiday= ' ';
    }

    $message=$message. "<tr height=25px ><td >".$login_id."</td><td align='center'>".$no_of_present."</td><td align='center'>".$no_of_absent."</td><td align='center'>".$no_of_onduty."</td><td align='center'>".$permission."</td><td align='center'>".$absent_count."</td><td align='center'>".$work_in_holiday."</td></tr>";
}
$drop_query="DROP TABLE $temp_table_name ";
$drop_query1="DROP TABLE $temp_table_name1 ";
mysqli_query($con,$drop_query);
mysqli_query($con,$drop_query1);

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


