<?php
require_once('../../TSLIB/TSLIB_mpdf571/mpdf571/mpdf.php');
include "../../TSLIB/TSLIB_CONNECTION.php";
include "../../TSLIB/TSLIB_COMMON.php";

//GETTING ERR MSG
$errormessages=array();
$errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (83,99)");
while($row=mysqli_fetch_array($errormsg)){
    $errormessages[]=$row["EMC_DATA"];
}
if($_REQUEST['option']=="ALL ACTIVE EMPLOYEE")
{
    $date= mysqli_query($con,"SELECT CONCAT(EMP.EMP_FIRST_NAME,' ',EMP.EMP_LAST_NAME) AS EMPLOYEE_NAME,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET FROM COMPANY_PROPERTIES CP,COMPANY_PROPERTIES_DETAILS CPD,EMPLOYEE_DETAILS EMP,USER_ACCESS UA WHERE CP.CP_ID=CPD.CP_ID  AND EMP.EMP_ID=CPD.EMP_ID AND UA.ULD_ID=EMP.ULD_ID AND UA.UA_TERMINATE IS NULL ORDER BY EMPLOYEE_NAME ASC");
    $ure_values=array();
    $final_values=array();
    while($row=mysqli_fetch_array($date)){
        $AE_empname=$row['EMPLOYEE_NAME'];
        $CPD_laptopno=$row['CP_LAPTOP_NUMBER'];
        $CPD_chargerno=$row['CP_CHARGER_NUMBER'];
        $CPD_battery=$row['CP_BATTERY_SERIAL_NUMBER'];
        $CPD_laptopbag=$row['CP_LAPTOP_BAG_NUMBER'];
        $CPD_mouse=$row['CP_MOUSE_NUMBER'];
        $CPD_dooraccess=$row['CPD_DOOR_ACCESS'];
        $CPD_idcard=$row['CPD_ID_CARD'];
        $CPD_headset=$row['CPD_HEADSET'];
        $final_values=array('empname'=>$AE_empname,'laptopno'=>$CPD_laptopno,'chargerno'=>$CPD_chargerno,'battery'=>$CPD_battery,'laptopbag'=> $CPD_laptopbag,'mouse'=>$CPD_mouse,'dooraccess'=>$CPD_dooraccess,'idcard'=>$CPD_idcard,'headset'=>$CPD_headset);
        $ure_values[]=$final_values;
    }
    $finalvalue=array($ure_values,$errormessages);
    echo JSON_ENCODE($finalvalue);
}
if($_REQUEST['option']=="ALL NONACTIVE EMPLOYEE")
{
    $date= mysqli_query($con,"SELECT V.EMPLOYEE_NAME,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET FROM VW_TS_ALL_NON_ACTIVE_EMPLOYEE_DETAILS V,COMPANY_PROPERTIES CP,COMPANY_PROPERTIES_DETAILS CPD,EMPLOYEE_DETAILS EMP WHERE CP.CP_ID=CPD.CP_ID AND EMP.EMP_ID=CPD.EMP_ID AND V.ULD_ID=EMP.ULD_ID ORDER BY V.EMPLOYEE_NAME ASC");
    $ure_values=array();
    $final_values=array();
    while($row=mysqli_fetch_array($date)){
        $AE_empname=$row['EMPLOYEE_NAME'];
        $CPD_laptopno=$row['CP_LAPTOP_NUMBER'];
        $CPD_chargerno=$row['CP_CHARGER_NUMBER'];
        $CPD_battery=$row['CP_BATTERY_SERIAL_NUMBER'];
        $CPD_laptopbag=$row['CP_LAPTOP_BAG_NUMBER'];
        $CPD_mouse=$row['CP_MOUSE_NUMBER'];
        $CPD_dooraccess=$row['CPD_DOOR_ACCESS'];
        $CPD_idcard=$row['CPD_ID_CARD'];
        $CPD_headset=$row['CPD_HEADSET'];
        $final_values=array('empname'=>$AE_empname,'laptopno'=>$CPD_laptopno,'chargerno'=>$CPD_chargerno,'battery'=>$CPD_battery,'laptopbag'=> $CPD_laptopbag,'mouse'=>$CPD_mouse,'dooraccess'=>$CPD_dooraccess,'idcard'=>$CPD_idcard,'headset'=>$CPD_headset);
        $ure_values[]=$final_values;
    }
    $finalvalue=array($ure_values,$errormessages);
    echo JSON_ENCODE($finalvalue);
}
if($_REQUEST['option']=="ACTIVE_EMPLOYEE")
{
    $empdet_active_emp=get_active_emp_id();
    $empdet_active_nonemp=get_nonactive_emp_id();
    $REV_errmsg=get_error_msg('148,149,150,151,152,153');
    $final_values=array($empdet_active_emp,$empdet_active_nonemp,$REV_errmsg);
    echo JSON_ENCODE($final_values);
}

if($_REQUEST['option']=="ACTIVE_EMPLOYEE_companydet")
{
    $EMP_loginid=$_REQUEST['login_id'];
    // echo $EMP_loginid;
    $date=mysqli_query($con,"SELECT CONCAT(EMP.EMP_FIRST_NAME,'',EMP.EMP_LAST_NAME) AS EMPLOYEE_NAME,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET FROM COMPANY_PROPERTIES CP,EMPLOYEE_DETAILS EMP,COMPANY_PROPERTIES_DETAILS CPD,USER_ACCESS UA,USER_LOGIN_DETAILS ULD WHERE CP.CP_ID = CPD.CP_ID AND EMP.EMP_ID=CPD.EMP_ID AND UA.ULD_ID=EMP.ULD_ID AND ULD.ULD_ID=UA.ULD_ID AND ULD.ULD_ID=EMP.ULD_ID AND UA.UA_TERMINATE IS NULL AND ULD.ULD_ID=$EMP_loginid ORDER BY EMPLOYEE_NAME ASC");
//    $ure_values=array();
    $final_values=array();
    while($row=mysqli_fetch_array($date)){
        $AE_empname=$row['EMPLOYEE_NAME'];
        $CPD_laptopno=$row['CP_LAPTOP_NUMBER'];
        $CPD_chargerno=$row['CP_CHARGER_NUMBER'];
        $CPD_battery=$row['CP_BATTERY_SERIAL_NUMBER'];
        $CPD_bag=$row['CP_LAPTOP_BAG_NUMBER'];
        $CPD_mouse=$row['CP_MOUSE_NUMBER'];
        $CPD_dooraccess=$row['CPD_DOOR_ACCESS'];
        $CPD_idcard=$row['CPD_ID_CARD'];
        $CPD_headset=$row['CPD_HEADSET'];
        $final_values[]=array('empname'=>$AE_empname,'laptopno'=>$CPD_laptopno,'chargerno'=>$CPD_chargerno,'battery'=>$CPD_battery,'laptopbag'=> $CPD_bag,'mouse'=>$CPD_mouse,'dooraccess'=>$CPD_dooraccess,'idcard'=>$CPD_idcard,'headset'=>$CPD_headset);
//        $ure_values=$final_values;
    }
    $EMP_loginid=$_REQUEST['login_id'];
    $bank=mysqli_query($con,"SELECT CONCAT(EMP.EMP_FIRST_NAME,' ',EMP.EMP_LAST_NAME) AS EMPLOYEE_NAME,EMP.EMP_ACCOUNT_NAME,EMP.EMP_ACCOUNT_NO,EMP.EMP_BANK_NAME,EMP.EMP_BRANCH_NAME,URC.URC_DATA,EMP.EMP_IFSC_CODE,EMP.EMP_BRANCH_ADDRESS FROM EMPLOYEE_DETAILS EMP,USER_RIGHTS_CONFIGURATION URC,USER_ACCESS UA WHERE UA.ULD_ID=EMP.ULD_ID AND EMP.EMP_ACCOUNT_TYPE=URC.URC_ID AND URC.CGN_ID=21 AND UA.UA_TERMINATE IS NULL AND EMP.ULD_ID=$EMP_loginid  ORDER BY EMPLOYEE_NAME ASC");
//    $ures_values=array();
    $finals_values=array();
    while($row=mysqli_fetch_array($bank)){
        $BD_empname=$row['EMPLOYEE_NAME'];
        $BD_acctname=$row['EMP_ACCOUNT_NAME'];
        $BD_acctno=$row['EMP_ACCOUNT_NO'];
        $BD_bankname=$row['EMP_BANK_NAME'];
        $BD_branchname=$row['EMP_BRANCH_NAME'];
        $BD_accttype=$row['URC_DATA'];
        $BD_IFSC=$row['EMP_IFSC_CODE'];
        $BD_branchaddr=$row['EMP_BRANCH_ADDRESS'];
        $finals_values[]=array('empname'=> $BD_empname,'accountname'=>$BD_acctname,'accountno'=> $BD_acctno,'bankname'=>$BD_bankname,'branchname'=> $BD_branchname,'accttype'=>$BD_accttype,'ifsc'=>$BD_IFSC,'branchaddr'=>$BD_branchaddr);
//        $ures_values=$finals_values;

    }
    $EMP_loginid=$_REQUEST['login_id'];
    $personal=mysqli_query($con,"SELECT CONCAT(EMP.EMP_FIRST_NAME,' ',EMP.EMP_LAST_NAME) AS EMPLOYEE_NAME,DATE_FORMAT(EMP.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,ED.ED_DESIGNATION,EMP.EMP_MOBILE_NUMBER,EMP.EMP_NEXT_KIN_NAME,URC.URC_DATA,EMP.EMP_ALT_MOBILE_NO,EMP.EMP_HOUSE_NO,
EMP.EMP_STREET_NAME,EMP.EMP_AREA,EMP.EMP_PIN_CODE,EMP_AADHAAR_NO,EMP.EMP_PASSPORT_NO,EMP.EMP_VOTER_ID,EMP.EMP_COMMENTS,ULD.ULD_USERSTAMP,DATE_FORMAT(CONVERT_TZ(EMP.EMP_TIMESTAMP,'+00:00','+05:30'),'%d-%m-%Y %T') AS
EMP_TIMESTAMP FROM EMPLOYEE_DETAILS EMP,EMPLOYEE_DESIGNATION ED,USER_RIGHTS_CONFIGURATION URC,USER_LOGIN_DETAILS ULD,USER_ACCESS UA
WHERE ED.ED_ID=EMP.EMP_DESIGNATION AND EMP.EMP_RELATIONHOOD=URC.URC_ID AND URC.CGN_ID=22
AND EMP.ULD_ID=UA.ULD_ID AND ULD.ULD_ID=UA.ULD_ID AND UA.UA_TERMINATE IS NULL AND EMP.ULD_ID=$EMP_loginid ORDER BY EMPLOYEE_NAME ASC;");
    $finalss_values=array();
    while($row=mysqli_fetch_array($personal)){
        $PD_empname=$row['EMPLOYEE_NAME'];
        $PD_dob=$row['EMP_DOB'];
        $PD_desgn=$row['ED_DESIGNATION'];
        $PD_mblnumber=$row['EMP_MOBILE_NUMBER'];
        $PD_kinname=$row['EMP_NEXT_KIN_NAME'];
        $PD_relationhood=$row['URC_DATA'];
        $PD_altmblno=$row['EMP_ALT_MOBILE_NO'];
        $PD_houseno=$row['EMP_HOUSE_NO'];
        $PD_streetname=$row['EMP_STREET_NAME'];
        $PD_area=$row['EMP_AREA'];
        $PD_pincode=$row['EMP_PIN_CODE'];
        $PD_addhar=$row['EMP_AADHAAR_NO'];
        $PD_passport=$row['EMP_PASSPORT_NO'];
        $PD_voter_id=$row['EMP_VOTER_NO'];
        $PD_comments=$row['EMP_COMMENTS'];
        $userstamp=$row['ULD_USERSTAMP'];
        $timestamp=$row['EMP_TIMESTAMP'];
        $finalss_values[]=array('empname'=>$PD_empname,'date_of_birth'=>$PD_dob,'designation'=>$PD_desgn,'mobilenumber'=>$PD_mblnumber,'kiname'=>$PD_kinname,'relation'=>$PD_relationhood,'altmblnumber'=>$PD_altmblno,'houseno'=>$PD_houseno,'streetname'=>$PD_streetname,'area'=>$PD_area,'pincode'=>$PD_pincode,'addhar'=>$PD_addhar,'passport'=>$PD_passport,'voter'=>$PD_voter_id,'comments'=>$PD_comments,'userstamp'=>$userstamp,'timestamp'=>$timestamp);
    }
    $finalvalue=array($final_values,$finals_values,$finalss_values,$errormessages);
    echo JSON_ENCODE($finalvalue);

}

if($_REQUEST['option']=="NONACTIVE_EMPLOYEE_companydet")
{
    $EMP_loginid=$_REQUEST['login_id'];
    $date=mysqli_query($con,"SELECT V.EMPLOYEE_NAME,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET FROM VW_TS_ALL_NON_ACTIVE_EMPLOYEE_DETAILS V,COMPANY_PROPERTIES CP,COMPANY_PROPERTIES_DETAILS CPD,EMPLOYEE_DETAILS EMP WHERE CP.CP_ID=CPD.CP_ID AND EMP.EMP_ID=CPD.EMP_ID AND V.ULD_ID=EMP.ULD_ID AND EMP.ULD_ID=$EMP_loginid ORDER BY V.EMPLOYEE_NAME ASC");
//    $ure_values=array();
    $final_values=array();
    while($row=mysqli_fetch_array($date)){
        $AE_empname=$row['EMPLOYEE_NAME'];
        $CPD_laptopno=$row['CP_LAPTOP_NUMBER'];
        $CPD_chargerno=$row['CP_CHARGER_NUMBER'];
        $CPD_battery=$row['CP_BATTERY_SERIAL_NUMBER'];
        $CPD_laptopbag=$row['CP_LAPTOP_BAG_NUMBER'];
        $CPD_mouse=$row['CP_MOUSE_NUMBER'];
        $CPD_dooraccess=$row['CPD_DOOR_ACCESS'];
        $CPD_idcard=$row['CPD_ID_CARD'];
        $CPD_headset=$row['CPD_HEADSET'];
        $final_values[]=array('empname'=>$AE_empname,'laptopno'=>$CPD_laptopno,'chargerno'=>$CPD_chargerno,'battery'=>$CPD_battery,'laptopbag'=> $CPD_laptopbag,'mouse'=>$CPD_mouse,'dooraccess'=>$CPD_dooraccess,'idcard'=>$CPD_idcard,'headset'=>$CPD_headset);
//        $ure_values[]=$final_values;$CPD_battery
    }
    $EMP_loginid=$_REQUEST['login_id'];
//    echo("SELECT CONCAT(EMP.EMP_FIRST_NAME,' ',EMP.EMP_LAST_NAME) AS EMPLOYEE_NAME,EMP.EMP_ACCOUNT_NAME,EMP.EMP_ACCOUNT_NO,EMP.EMP_BANK_NAME,EMP.EMP_BRANCH_NAME,URC.URC_DATA,EMP.EMP_IFSC_CODE,EMP.EMP_BRANCH_ADDRESS FROM EMPLOYEE_DETAILS EMP,USER_RIGHTS_CONFIGURATION URC,USER_ACCESS UA WHERE UA.ULD_ID=EMP.ULD_ID AND EMP.EMP_ACCOUNT_TYPE=URC.URC_ID AND URC.CGN_ID=21 AND UA.UA_TERMINATE IS NULL AND EMP.ULD_ID=$EMP_loginid  ORDER BY EMPLOYEE_NAME ASC");
    $bank=mysqli_query($con,"SELECT V.EMPLOYEE_NAME,EMP.EMP_ACCOUNT_NAME,EMP.EMP_ACCOUNT_NO,EMP.EMP_BANK_NAME,EMP.EMP_BRANCH_NAME,URC.URC_DATA AS EMP_ACCOUNT_TYPE,EMP.EMP_IFSC_CODE,EMP.EMP_BRANCH_ADDRESS FROM VW_TS_ALL_NON_ACTIVE_EMPLOYEE_DETAILS V,EMPLOYEE_DETAILS EMP,USER_RIGHTS_CONFIGURATION URC WHERE V.ULD_ID=EMP.ULD_ID AND EMP.EMP_ACCOUNT_TYPE=URC.URC_ID AND URC.CGN_ID=21 AND EMP.ULD_ID=$EMP_loginid ORDER BY V.EMPLOYEE_NAME ASC");
//    $ures_values=array();
    $finals_values=array();
    while($row=mysqli_fetch_array($bank)){
        $BD_empname=$row['EMPLOYEE_NAME'];
        $BD_acctname=$row['EMP_ACCOUNT_NAME'];
        $BD_acctno=$row['EMP_ACCOUNT_NO'];
        $BD_bankname=$row['EMP_BANK_NAME'];
        $BD_branchname=$row['EMP_BRANCH_NAME'];
        $BD_accttype=$row['EMP_ACCOUNT_TYPE'];
        $BD_IFSC=$row['EMP_IFSC_CODE'];
        $BD_branchaddr=$row['EMP_BRANCH_ADDRESS'];
        $finals_values[]=array('empname'=> $BD_empname,'accountname'=>$BD_acctname,'accountno'=> $BD_acctno,'bankname'=>$BD_bankname,'branchname'=> $BD_branchname,'accttype'=>$BD_accttype,'ifsc'=>$BD_IFSC,'branchaddr'=>$BD_branchaddr);
//        $ures_values=$finals_values;

    }
    $EMP_loginid=$_REQUEST['login_id'];
    $personal=mysqli_query($con,"SELECT V.EMPLOYEE_NAME,DATE_FORMAT(EMP.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,ED.ED_DESIGNATION,EMP.EMP_MOBILE_NUMBER,EMP.EMP_NEXT_KIN_NAME,URC.URC_DATA AS EMP_RELATIONHOOD,EMP.EMP_ALT_MOBILE_NO,EMP.EMP_HOUSE_NO,
EMP.EMP_STREET_NAME,EMP.EMP_AREA,EMP.EMP_PIN_CODE,EMP_AADHAAR_NO,EMP.EMP_PASSPORT_NO,EMP.EMP_VOTER_ID,EMP.EMP_COMMENTS,ULD.ULD_USERSTAMP,DATE_FORMAT(CONVERT_TZ(EMP.EMP_TIMESTAMP,'+00:00','+05:30'),'%d-%m-%Y %T') AS
EMP_TIMESTAMP FROM VW_TS_ALL_NON_ACTIVE_EMPLOYEE_DETAILS V,EMPLOYEE_DETAILS EMP,
EMPLOYEE_DESIGNATION ED,USER_RIGHTS_CONFIGURATION URC,USER_LOGIN_DETAILS ULD WHERE ED.ED_ID=EMP.EMP_DESIGNATION AND V.ULD_ID=EMP.ULD_ID AND EMP.EMP_RELATIONHOOD=URC.URC_ID AND URC.CGN_ID=22
AND EMP.ULD_ID=ULD.ULD_ID AND EMP.ULD_ID=$EMP_loginid ORDER BY V.EMPLOYEE_NAME ASC");
    $finalss_values=array();
    while($row=mysqli_fetch_array($personal)){
        $PD_empname=$row['EMPLOYEE_NAME'];
        $PD_dob=$row['EMP_DOB'];
        $PD_desgn=$row['ED_DESIGNATION'];
        $PD_mblnumber=$row['EMP_MOBILE_NUMBER'];
        $PD_kinname=$row['EMP_NEXT_KIN_NAME'];
        $PD_relationhood=$row['EMP_RELATIONHOOD'];
        $PD_altmblno=$row['EMP_ALT_MOBILE_NO'];
        $PD_houseno=$row['EMP_HOUSE_NO'];
        $PD_streetname=$row['EMP_STREET_NAME'];
        $PD_area=$row['EMP_AREA'];
        $PD_pincode=$row['EMP_PIN_CODE'];
        $PD_addhar=$row['EMP_AADHAAR_NO'];
        $PD_passport=$row['EMP_PASSPORT_NO'];
        $PD_voter_id=$row['EMP_VOTER_NO'];
        $PD_comments=$row['EMP_COMMENTS'];
        $userstamp=$row['ULD_USERSTAMP'];
        $timestamp=$row['EMP_TIMESTAMP'];
        $finalss_values[]=array('empname'=>$PD_empname,'date_of_birth'=>$PD_dob,'designation'=>$PD_desgn,'mobilenumber'=>$PD_mblnumber,'kiname'=>$PD_kinname,'relation'=>$PD_relationhood,'altmblnumber'=>$PD_altmblno,'houseno'=>$PD_houseno,'streetname'=>$PD_streetname,'area'=>$PD_area,'pincode'=>$PD_pincode,'addhar'=>$PD_addhar,'passport'=>$PD_passport,'voter'=>$PD_voter_id,'comments'=>$PD_comments,'userstamp'=>$userstamp,'timestamp'=>$timestamp);
    }
    $finalvalue=array($final_values,$finals_values,$finalss_values,$errormessages);
    echo JSON_ENCODE($finalvalue);
}
if($_REQUEST['option']=='company_details')
{
    $cmpy=mysqli_query($con,"SELECT CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER FROM COMPANY_PROPERTIES CP WHERE CP_FLAG IS NULL");
    $final_array=array();
    while($row=mysqli_fetch_array($cmpy)){
        $CMP_laptop_no=$row['CP_LAPTOP_NUMBER'];
        $CMP_charger_no=$row['CP_CHARGER_NUMBER'];
        $CMP_battery=$row['CP_BATTERY_SERIAL_NUMBER'];
        $CMP_lapbag=$row['CP_LAPTOP_BAG_NUMBER'];
        $CMP_mouse=$row['CP_MOUSE_NUMBER'];
        $final_array[]=array('laptop'=>$CMP_laptop_no,'charger'=>$CMP_charger_no,'battery'=>$CMP_battery,'bag'=>$CMP_lapbag,'mouse'=>$CMP_mouse);
        $finalvalue=array($final_array,$errormessage);
        echo JSON_ENCODE($finalvalue);
    }
}
if($_REQUEST['option']=='company_datatable')
{

    $EMP_loginid=$_REQUEST['login_id'];
    $cmp_details=mysqli_query($con,"SELECT CONCAT(EMP.EMP_FIRST_NAME,' ',EMP.EMP_LAST_NAME) AS EMPLOYEE_NAME,DATE_FORMAT(EMP.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,ED.ED_DESIGNATION,EMP.EMP_MOBILE_NUMBER,EMP.EMP_NEXT_KIN_NAME,URC.URC_DATA AS EMP_RELATIONHOOD,EMP.EMP_ALT_MOBILE_NO,EMP.EMP_HOUSE_NO,EMP.EMP_STREET_NAME,EMP.EMP_AREA,EMP.EMP_PIN_CODE,EMP_AADHAAR_NO,EMP.EMP_PASSPORT_NO,EMP.EMP_VOTER_ID,EMP.EMP_COMMENTS,ULD.ULD_USERSTAMP,EMP.EMP_ACCOUNT_NAME,EMP.EMP_ACCOUNT_NO,EMP.EMP_BANK_NAME,EMP.EMP_BRANCH_NAME,URC1.URC_DATA AS EMP_ACCOUNT_TYPE,EMP.EMP_IFSC_CODE,EMP.EMP_BRANCH_ADDRESS,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET,DATE_FORMAT(CONVERT_TZ(EMP.EMP_TIMESTAMP,'+00:00','+05:30'),'%d-%m-%Y %T') AS EMP_TIMESTAMP FROM EMPLOYEE_DETAILS EMP,EMPLOYEE_DESIGNATION ED,USER_RIGHTS_CONFIGURATION URC,USER_LOGIN_DETAILS ULD,USER_ACCESS UA,USER_RIGHTS_CONFIGURATION URC1,COMPANY_PROPERTIES CP,COMPANY_PROPERTIES_DETAILS CPD WHERE ED.ED_ID=EMP.EMP_DESIGNATION AND EMP.EMP_RELATIONHOOD=URC.URC_ID AND URC.CGN_ID=22 AND EMP.ULD_ID=UA.ULD_ID AND ULD.ULD_ID=UA.ULD_ID AND EMP.EMP_ACCOUNT_TYPE=URC1.URC_ID AND URC1.CGN_ID=21 AND CP.CP_ID=CPD.CP_ID AND EMP.EMP_ID=CPD.EMP_ID AND EMP.ULD_ID=$EMP_loginid AND UA.UA_TERMINATE IS NULL ORDER BY EMPLOYEE_NAME ASC");
    while($row=mysqli_fetch_array($cmp_details)){
        $cmpdetails[]=array($row['EMPLOYEE_NAME'],$row['CP_LAPTOP_NUMBER'],$row['CP_CHARGER_NUMBER'],$row['CP_BATTERY_SERIAL_NUMBER'],$row['CP_LAPTOP_BAG_NUMBER'],$row['CP_MOUSE_NUMBER'],$row['CPD_DOOR_ACCESS'],$row['CPD_ID_CARD'],$row['CPD_HEADSET'],$row['EMP_ACCOUNT_NAME'],$row['EMP_ACCOUNT_NO'],$row['EMP_BANK_NAME'],$row['EMP_BRANCH_NAME'],$row['EMP_ACCOUNT_TYPE'],$row['EMP_IFSC_CODE'],$row['EMP_BRANCH_ADDRESS'],$row['EMP_DOB'],$row['ED_DESIGNATION'],$row['EMP_MOBILE_NUMBER'],$row['EMP_NEXT_KIN_NAME'],$row['EMP_RELATIONHOOD'],$row['EMP_ALT_MOBILE_NO'],$row['EMP_HOUSE_NO'],$row['EMP_STREET_NAME'],$row['EMP_AREA'],$row['EMP_PIN_CODE'],$row['EMP_AADHAAR_NO'],$row['EMP_PASSPORT_NO'],$row['EMP_VOTER_NO'],$row['EMP_COMMENTS'],$row['ULD_USERSTAMP'],$row['EMP_TIMESTAMP']);

    }
    $cmp_properties='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;">
         <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>EMPLOYEE NAME</td><td style=width="250">'.$cmpdetails[0][0].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LAPTOP NO</td><td style=width="250">'.$cmpdetails[0][1].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>CHARGER NO</td><td style=width="250">'.$cmpdetails[0][2].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>BATTERY NO</td><td style=width="250">'.$cmpdetails[0][3].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LAPTOP BAG NO </td><td style=width="250">'.$cmpdetails[0][4].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>MOUSE NO</td><td style=width="250">'.$cmpdetails[0][5].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DOOR ACCESS</td><td style=width="250">'.$cmpdetails[0][6].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ID CARD</td><td style=width="250">'.$cmpdetails[0][7].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>HEAD SET</td><td style=width="250">'.$cmpdetails[0][8].'</td></tr>
         </table>';

    $bank_details='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;">
         <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>EMPLOYEE NAME</td><td style=width="250">'.$cmpdetails[0][0].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ACCOUNT NAME</td><td style=width="250">'.$cmpdetails[0][9].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ACCOUNT NUMBER</td><td style=width="250">'.$cmpdetails[0][10].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>BANK NAME</td><td style=width="250">'.$cmpdetails[0][11].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>BRANCH NAME</td><td style=width="250">'.$cmpdetails[0][12].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ACCOUNT TYPE</td><td style=width="250">'.$cmpdetails[0][13].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>IFDC CODE</td><td style=width="250">'.$cmpdetails[0][14].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>BRANCH ADDRESS</td><td style=width="250">'.$cmpdetails[0][15].'</td></tr>
         </table>';
    $personal_details='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;">
         <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>EMPLOYEE NAME</td><td style=width="250">'.$cmpdetails[0][0].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DOB</td><td style=width="250">'.$cmpdetails[0][16].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DESIGNATION</td><td style=width="250">'.$cmpdetails[0][17].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>MOBILE NO</td><td style=width="250">'.$cmpdetails[0][18].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>KIN NAME</td><td style=width="250">'.$cmpdetails[0][19].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>RELATIONHOOD</td><td style=width="250">'.$cmpdetails[0][20].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ALTER MOBILE NO</td><td style=width="250">'.$cmpdetails[0][21].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>HOUSE NO</td><td style=width="250">'.$cmpdetails[0][22].'</td></tr>
         <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>STREET NAME</td><td style=width="250">'.$cmpdetails[0][23].'</td></tr>
          <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>AREA</td><td style=width="250">'.$cmpdetails[0][24].'</td></tr>
           <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>PIN CODE</td><td style=width="250">'.$cmpdetails[0][25].'</td></tr>
            <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>AADHAAR NO</td><td style=width="250">'.$cmpdetails[0][26].'</td></tr>
             <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>PASSPORT NO</td><td style=width="250">'.$cmpdetails[0][27].'</td></tr>
              <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>VOTER ID</td><td style=width="250">'.$cmpdetails[0][28].'</td></tr>
              <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>COMMENTS</td><td style=width="250">'.$cmpdetails[0][29].'</td></tr>
              <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>USERSTAMP</td><td style=width="250">'.$cmpdetails[0][30].'</td></tr>
              <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>TIMESTAMP</td><td style=width="250">'.$cmpdetails[0][31].'</td></tr>

         </table>';

    $reportheadername='COMPANY PROPERTIES TABLE'.$cmp_properties[0][0];
    $finaltable= '<html><body><table><tr><td style="text-align: center;"><div></div></td></tr><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">' .$cmp_properties.'</div></h2></td></tr><br><br><br><tr><td>'.$bank_details.'</td></tr><br><br><br><tr><td>'.$personal_details.'</td></tr><br><br><br></table></body></html>';

    ob_clean();
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
    $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">COMPANY PROPERTIES DETAILS</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($finaltable);

    $mpdf->Output(''.$reportheadername.'.pdf','d');

}
if($_REQUEST['option']=='company_allemp')
{
    $cmp_all=mysqli_query($con,"SELECT CONCAT(EMP.EMP_FIRST_NAME,' ',EMP.EMP_LAST_NAME) AS EMPLOYEE_NAME,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET FROM COMPANY_PROPERTIES CP,COMPANY_PROPERTIES_DETAILS CPD,EMPLOYEE_DETAILS EMP,USER_ACCESS UA WHERE CP.CP_ID=CPD.CP_ID  AND EMP.EMP_ID=CPD.EMP_ID AND UA.ULD_ID=EMP.ULD_ID AND UA.UA_TERMINATE IS NULL ORDER BY EMPLOYEE_NAME ASC");
    $cmpdetailall='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;"></caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>EMPLOYEE NAME</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LAPTOP NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>CHARGER NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>BATTERY NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LAPTOP BAG NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>MOUSE NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>DOOR ACCESS</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>ID CARD</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>HEAD SET</b></td></tr></th>';
    while($row=mysqli_fetch_array($cmp_all)){

        $cmpdetailall .="<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$row['EMPLOYEE_NAME']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_LAPTOP_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_CHARGER_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_BATTERY_SERIAL_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_LAPTOP_BAG_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_MOUSE_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CPD_DOOR_ACCESS']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CPD_ID_CARD']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CPD_HEADSET']."</td></tr>";

    }
    $cmpdetailall .="</table>";
    $reportheadername='COMPANY PROPERTIES TABLE'.$cmpdetailall;
    $finaltable='<html><body><table><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">'.$cmpdetailall.'</div></h2></td></tr></table></body></html>';
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
    $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">COMPANY PROPERTIES DETAILS OF ALL ACTIVE EMPLOYEE</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($finaltable);
    $reportpdf=$mpdf->Output('COMPANY PROPERTIES TABLE.pdf','d');
}
if($_REQUEST['option']=='cmp_unused')
{
    $cmp_unsed=mysqli_query($con,"SELECT CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER FROM COMPANY_PROPERTIES CP WHERE CP_FLAG IS NULL");
    $cmpun_used='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;"></caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LAPTOP NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>CHARGER NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>BATTERY NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LAPTOP BAG NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>MOUSE NO</b></td></tr></th>';
    while($row=mysqli_fetch_array($cmp_unsed)){
        $cmpun_used .="<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_LAPTOP_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_CHARGER_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_BATTERY_SERIAL_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_LAPTOP_BAG_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_MOUSE_NUMBER']."</td></tr>";
    }
    $cmpun_used .="</table>";
    $reportheadername='COMPANY PROPERTIES TABLE'.$cmpun_used;
    $finaltable='<html><body><table><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">'.$cmpun_used.'</div></h2></td></tr></table></body></html>';
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
    $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">UN/USED COMPANY PROPERTIES DETAILS</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($finaltable);
    $reportpdf=$mpdf->Output('UN/USED COMPANY PROPERTIES.pdf','d');
}
if($_REQUEST['option']=='cmp_non_active'){
    $cmp_non_active=mysqli_query($con,"SELECT V.EMPLOYEE_NAME,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET FROM VW_TS_ALL_NON_ACTIVE_EMPLOYEE_DETAILS V,COMPANY_PROPERTIES CP,COMPANY_PROPERTIES_DETAILS CPD,EMPLOYEE_DETAILS EMP WHERE CP.CP_ID=CPD.CP_ID AND EMP.EMP_ID=CPD.EMP_ID AND V.ULD_ID=EMP.ULD_ID ORDER BY V.EMPLOYEE_NAME ASC");
    $cmp_non='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;"></caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>EMPLOYEE NAME</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LAPTOP NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>CHARGER NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>BATTERY NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LAPTOP BAG NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>MOUSE NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>DOOR ACCESS</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>ID CARD</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>HEAD SET</b></td></tr></th>';
    while($row=mysqli_fetch_array($cmp_non_active)){

        $cmp_non .="<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$row['EMPLOYEE_NAME']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_LAPTOP_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_CHARGER_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_BATTERY_SERIAL_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_LAPTOP_BAG_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_MOUSE_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CPD_DOOR_ACCESS']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CPD_ID_CARD']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CPD_HEADSET']."</td></tr>";

    }
    $cmp_non .="</table>";
    $reportheadername='COMPANY PROPERTIES TABLE'.$cmp_non;
    $finaltable='<html><body><table><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">'.$cmp_non.'</div></h2></td></tr></table></body></html>';
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
    $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">COMPANY PROPERTIES DETAILS FOR NON-ACTIVE EMPLOYEE</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($finaltable);
    $reportpdf=$mpdf->Output('COMPANY PROPERTIES TABLE.pdf','d');
}

?>