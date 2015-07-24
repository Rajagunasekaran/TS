<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************ACCESS_RIGHTS_TERMINATE_SEARCH_UPDATE*********************************************//
//DONE BY:LALITHA
//VER 0.07-SD:25/02/2015 ED:25/02/2015, TRACKER NO:74,DESC:updated display name
//ver 0.06 SD:08/01/2015 ED:10/01/2015 tracker no :74,Merged Employee details in design part nd mail part also,Added Attach File Html Code nd file Upload Script,Changed validation,Replaced Login id as Employee name,Updated auto focus
//ver 0.05 SD:30/12/2014 ED:31/12/2014 tracker no :74,Updated preloader nd message box position
//done by :safi
//ver 0.04 SD:29/12/2014 ED:29/12/2014 tracker no :74,desc:updated to terminate login if selected day report entered also
//VER 0.03 SD:28/11/14 ED:1/12/2014 TRACKER NO:74 DESC:MERGED LOGIN CREATION/UPDATION AND EMPLOYEE CREATION FORM
//DONE BY:shalini
//VER 0.01-INITIAL VERSION, SD:20/08/2014 ED:11/09/2014,TRACKER NO:81
//*********************************************************************************************************//-->

set_include_path( get_include_path() . PATH_SEPARATOR . 'google-api-php-client-master/src' );
require_once 'google/appengine/api/mail/Message.php';
require_once 'google-api-php-client-master/src/Google/Client.php';
require_once 'google-api-php-client-master/src/Google/Service/Drive.php';
include 'google-api-php-client-master/src/Google/Service/Calendar.php';
use google\appengine\api\mail\Message;
include "../TSLIB/TSLIB_CONNECTION.php";
include "../TSLIB/TSLIB_COMMON.php";
include "../TSLIB/TSLIB_GET_USERSTAMP.php";
function getULD_ID_from_ULD_LOGINID1($ULD_LOGINID){
    global $con;
    $query="select ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID ='".$ULD_LOGINID."'";
    $result=mysqli_query($con,$query);
    while($row=mysqli_fetch_array($result)){
        $ULD_ID=$row["ULD_ID"];
    }
    return $ULD_ID;
}

if(isset($_REQUEST))
{
    $userstamp=$UserStamp;

    if($_REQUEST['option']=='TERMINATIONLB')
    {
        $active_emp=get_active_emp_id();

        echo json_encode( $active_emp);
    }
    else if($_REQUEST['option']=='REJOINLB')
    {
        $active_nonemp=get_nonactive_emp_id();
        echo json_encode($active_nonemp);
    }
    else if($_REQUEST['option']=='SEARCHLB')
    {
        $active_nonemp=get_nonactive_emp_id();
        echo json_encode($active_nonemp);
    }
    else if($_REQUEST['option']=='LISTBOX')
    {
        //RELATIONHOOD
        $rname_result=mysqli_query($con,"SELECT URC_DATA FROM USER_RIGHTS_CONFIGURATION WHERE CGN_ID=22 ORDER BY URC_DATA");
        $get_rname_array=array();
        while($row=mysqli_fetch_array($rname_result)){
            $get_rname_array[]=$row["URC_DATA"];
        }
        //LAPTOP NUMBER
        $lname_result=mysqli_query($con,"SELECT CP_LAPTOP_NUMBER FROM COMPANY_PROPERTIES WHERE CP_FLAG IS NULL ORDER BY CP_LAPTOP_NUMBER");
        $get_lname_array=array();
        while($row=mysqli_fetch_array($lname_result)){
            $get_lname_array[]=$row["CP_LAPTOP_NUMBER"];
        }
        //DESIGNATION
        $rdesgn_result=mysqli_query($con,"SELECT * FROM EMPLOYEE_DESIGNATION ORDER BY ED_DESIGNATION");
        $get_rdesgn_array=array();
        while($row=mysqli_fetch_array($rdesgn_result)){
            $get_rdesgn_array[]=$row["ED_DESIGNATION"];
        }
        //ACCOUNT TYPE
        $acctype_result=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE CGN_ID=21 ORDER BY URC_DATA");
        $get_acctype_array=array();
        while($row=mysqli_fetch_array($acctype_result)){
            $get_acctype_array[]=$row["URC_DATA"];
        }
        $final_array=array($get_rdesgn_array,$get_rname_array,$get_lname_array,$get_acctype_array);
        echo json_encode($final_array);
    }
    else if($_REQUEST['option']=='FETCH')
    {
        $URT_SRC_uld_id = $_REQUEST['URT_SRC_loggin'];
        $select_recver="SELECT UA_REC_VER FROM USER_ACCESS WHERE ULD_ID =(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_ID='$URT_SRC_uld_id') AND UA_TERMINATE='X'";
        $selectrecver_rs=mysqli_query($con,$select_recver);
        $recver_array=array();
        while($row=mysqli_fetch_array($selectrecver_rs))
        {
            $recver_array[]=$row['UA_REC_VER'];
        }
        if(count($recver_array)==1){
            $query= "SELECT UA_REASON,UA_END_DATE ,UA_REC_VER FROM USER_ACCESS where ULD_ID =(select ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_ID ='".$URT_SRC_uld_id."')";
            $loginsearch_fetchingdata= mysqli_query($con, $query);
            $URT_SRC_values=array();
            if($row=mysqli_fetch_array($loginsearch_fetchingdata)){
                $URT_SRC_enddate=$row["UA_END_DATE"];
                $URT_SRC_reason=$row["UA_REASON"];
                $URT_SRC_recver=$row["UA_REC_VER"];
                $final_date = date('d-m-Y',strtotime( $URT_SRC_enddate));
                $URT_SRC_values=array('enddate'=>$final_date,'reasonn' => $URT_SRC_reason,'recver'=>$URT_SRC_recver);
            }
            echo json_encode($URT_SRC_values);
        }
        else{
            $URT_SRC_values=array('recver'=>$recver_array);
            echo json_encode($URT_SRC_values);
        }
    }
    else if($_REQUEST['option']=='FETCH DATA'){
        $loginid = $_REQUEST['URT_SRC_loggin'];
        $recver=$_REQUEST['recver'];
        $query= "SELECT UA_REASON,UA_END_DATE  FROM USER_ACCESS where ULD_ID =$loginid and UA_REC_VER='$recver'";
        $loginsearch_fetchingdata= mysqli_query($con, $query);
        $URT_SRC_values=array();
        if($row=mysqli_fetch_array($loginsearch_fetchingdata)){
            $URT_SRC_enddate=$row["UA_END_DATE"];
            $URT_SRC_reason=$row["UA_REASON"];

            $final_date = date('d-m-Y',strtotime( $URT_SRC_enddate));
            $URT_SRC_values=array('enddate'=>$final_date,'reasonn' => $URT_SRC_reason);
        }
        echo json_encode($URT_SRC_values);
    }
    else if($_REQUEST['option']=='GETDATE')
    {
        $loginid_result = $_REQUEST['URT_SRC_loggin'];
        $query= "SELECT  DATE_FORMAT(UA_JOIN_DATE,'%d-%m-%Y') as UA_JOIN_DATE  FROM USER_ACCESS where UA_REC_VER=(select MAX(UA_REC_VER) as UA_REC_VER_MAX from USER_ACCESS where ULD_ID=$loginid_result AND UA_TERMINATE IS NULL)AND ULD_ID=$loginid_result";
        $joindate_data= mysqli_query($con, $query);
        $URT_SRC_values=array();
        while($row=mysqli_fetch_array($joindate_data)){
            $mindate=$row["UA_JOIN_DATE"];
        }
        echo $mindate;
    }
    else if($_REQUEST['option']=='GETENDDATE')
    {
        $loginid_result = $_REQUEST['URT_SRC_loggin'];
        $query= "SELECT  DATE_FORMAT(UA_END_DATE,'%d-%m-%Y') as UA_END_DATE  FROM USER_ACCESS where UA_REC_VER=(select MAX(UA_REC_VER) as UA_REC_VER_MAX from USER_ACCESS where ULD_ID=$loginid_result AND UA_TERMINATE IS NOT NULL)AND ULD_ID=$loginid_result";
        $enddate_data= mysqli_query($con, $query);
        $URT_SRC_values=array();
        while($row=mysqli_fetch_array($enddate_data)){
            $mindate=$row["UA_END_DATE"];
        }
        $lname_result=mysqli_query($con,"SELECT CP_LAPTOP_NUMBER FROM COMPANY_PROPERTIES WHERE CP_FLAG IS NULL");
        $get_lname_array=array();
        while($row=mysqli_fetch_array($lname_result)){
            $get_lname_array[]=$row["CP_LAPTOP_NUMBER"];
        }
        //EMPLOYEE DETAILS
        $login_id_result = $_REQUEST['URT_SRC_loggin'];
//        $loginsearch_fetchingdata= mysqli_query($con,"SELECT DISTINCT RC.RC_NAME,UA.UA_JOIN_DATE,URC1.URC_DATA,EMP.EMP_ID,EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME,DATE_FORMAT(EMP.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,EMP.EMP_DESIGNATION,EMP.EMP_MOBILE_NUMBER,EMP.EMP_NEXT_KIN_NAME,EMP.EMP_RELATIONHOOD,EMP.EMP_ALT_MOBILE_NO,EMP.EMP_BANK_NAME,EMP.EMP_BRANCH_NAME,EMP.EMP_ACCOUNT_NAME,EMP.EMP_ACCOUNT_NO,EMP.EMP_IFSC_CODE,EMP.EMP_ACCOUNT_TYPE,EMP.EMP_BRANCH_ADDRESS,CPD.CPD_LAPTOP_NUMBER,CPD.CPD_CHARGER_NUMBER,CPD.CPD_LAPTOP_BAG,CPD.CPD_MOUSE,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET,EMP.EMP_AADHAAR_NO,EMP.EMP_PASSPORT_NO,EMP.EMP_VOTER_ID,EMP.EMP_COMMENTS,ULD.ULD_LOGINID,DATE_FORMAT(CONVERT_TZ(EMP.EMP_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS EMP_TIMESTAMP
//           FROM EMPLOYEE_DETAILS EMP left join COMPANY_PROPERTIES_DETAILS CPD on EMP.EMP_ID=CPD.EMP_ID,USER_LOGIN_DETAILS ULD,USER_ACCESS UA ,USER_RIGHTS_CONFIGURATION URC,USER_RIGHTS_CONFIGURATION URC1,ROLE_CREATION RC  WHERE EMP.ULD_ID=ULD.ULD_ID AND UA.UA_EMP_TYPE=URC1.URC_ID and ULD.ULD_ID=UA.ULD_ID and URC.URC_ID=RC.URC_ID and RC.RC_ID=UA.RC_ID and ULD.ULD_ID='$login_id_result' and UA.UA_REC_VER=(select max(UA_REC_VER) from USER_ACCESS UA,USER_LOGIN_DETAILS ULD where ULD.ULD_ID=UA.ULD_ID and ULD.ULD_ID='$login_id_result' ) ORDER BY EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME");
        $loginsearch_fetchingdata= mysqli_query($con,"SELECT DISTINCT RC.RC_NAME,UA.UA_JOIN_DATE,URC1.URC_DATA,EMP.EMP_ID,EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME,DATE_FORMAT(EMP.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,ED.ED_DESIGNATION AS EMP_DESIGNATION,EMP.EMP_MOBILE_NUMBER,EMP.EMP_NEXT_KIN_NAME,URC2.URC_DATA AS EMP_RELATIONHOOD,EMP.EMP_ALT_MOBILE_NO,EMP.EMP_HOUSE_NO,EMP.EMP_STREET_NAME,EMP.EMP_AREA,EMP.EMP_PIN_CODE,EMP.EMP_BANK_NAME,EMP.EMP_BRANCH_NAME,EMP.EMP_ACCOUNT_NAME,EMP.EMP_ACCOUNT_NO,
EMP.EMP_IFSC_CODE,URC3.URC_DATA AS EMP_ACCOUNT_TYPE,EMP.EMP_BRANCH_ADDRESS,CP.CP_LAPTOP_NUMBER,
CP.CP_CHARGER_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CPD.CPD_DOOR_ACCESS,
CPD.CPD_ID_CARD,CPD.CPD_HEADSET,EMP.EMP_AADHAAR_NO,EMP.EMP_PASSPORT_NO,
EMP.EMP_VOTER_ID,EMP.EMP_COMMENTS,ULD.ULD_LOGINID,
DATE_FORMAT(CONVERT_TZ(EMP.EMP_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS EMP_TIMESTAMP FROM
EMPLOYEE_DETAILS EMP left join COMPANY_PROPERTIES_DETAILS CPD on EMP.EMP_ID=CPD.EMP_ID left join
COMPANY_PROPERTIES CP ON CPD.CP_ID = CP.CP_ID,
USER_LOGIN_DETAILS ULD,USER_ACCESS UA ,USER_RIGHTS_CONFIGURATION URC,USER_RIGHTS_CONFIGURATION
URC1,EMPLOYEE_DESIGNATION ED,USER_RIGHTS_CONFIGURATION URC2,USER_RIGHTS_CONFIGURATION URC3,
ROLE_CREATION RC WHERE EMP.ULD_ID=ULD.ULD_ID AND
UA.UA_EMP_TYPE=URC1.URC_ID and ULD.ULD_ID=UA.ULD_ID and URC.URC_ID=RC.URC_ID and
RC.RC_ID=UA.RC_ID and ED.ED_ID=EMP.EMP_DESIGNATION AND URC2.URC_ID=EMP.EMP_RELATIONHOOD AND
URC3.URC_ID=EMP.EMP_ACCOUNT_TYPE AND ULD.ULD_ID='$login_id_result' and
UA.UA_REC_VER=(select max(UA_REC_VER) from USER_ACCESS UA,USER_LOGIN_DETAILS ULD where
ULD.ULD_ID=UA.ULD_ID and ULD.ULD_ID='$login_id_result') ORDER BY EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME");
        while($row=mysqli_fetch_array($loginsearch_fetchingdata)){
            $URSRC_role=$row['URC_DATA'];
            $URSRC_firstname=$row['EMP_FIRST_NAME'];
            $URSRC_lastname=$row['EMP_LAST_NAME'];
            $URSRC_dob=$row['EMP_DOB'];
            $URSRC_designation=$row['EMP_DESIGNATION'];
            $URSRC_mobile=$row['EMP_MOBILE_NUMBER'];
            $URSRC_kinname=$row['EMP_NEXT_KIN_NAME'];
            $URSRC_relationhd=$row['EMP_RELATIONHOOD'];
            $URSRC_Mobileno=$row['EMP_ALT_MOBILE_NO'];
            $URSRC_Houseno=$row['EMP_HOUSE_NO'];
            $URSRC_Streetname=$row['EMP_STREET_NAME'];
            $URSRC_Area=$row['EMP_AREA'];
            $URSRC_Postalcode=$row['EMP_PIN_CODE'];
            $URSRC_laptopno=$row['CP_LAPTOP_NUMBER'];
            $URSRC_chrgrno=$row['CP_CHARGER_NUMBER'];
            $URSRC_bag=$row['CP_LAPTOP_BAG_NUMBER'];
            $URSRC_mouse=$row['CP_MOUSE_NUMBER'];
            $URSRC_dooracess=$row['CPD_DOOR_ACCESS'];
            $URSRC_idcard=$row['CPD_ID_CARD'];
            $URSRC_headset=$row['CPD_HEADSET'];
            $URSRC_bankname=$row['EMP_BANK_NAME'];
            $URSRC_brancname=$row['EMP_BRANCH_NAME'];
            $URSRC_acctname=$row['EMP_ACCOUNT_NAME'];
            $URSRC_acctno=$row['EMP_ACCOUNT_NO'];
            $URSRC_acctype=$row['EMP_ACCOUNT_TYPE'];
            $URSRC_ifsccode=$row['EMP_IFSC_CODE'];
            $URSRC_branchaddr=$row['EMP_BRANCH_ADDRESS'];
            $URSRC_aadhar=$row['EMP_AADHAAR_NO'];
            $URSRC_passport=$row['EMP_PASSPORT_NO'];
            $URSRC_voterid=$row['EMP_VOTER_ID'];
            $URSRC_comments=$row['EMP_COMMENTS'];
            $URSRC_btryslno=$row['CP_BATTERY_SERIAL_NUMBER'];
            $final_values=(object)['firstname'=>$URSRC_firstname,'lastname'=>$URSRC_lastname,'dob'=>$URSRC_dob,'designation'=>$URSRC_designation,'mobile'=>$URSRC_mobile,'kinname'=>$URSRC_kinname,'relationhood'=>$URSRC_relationhd,'altmobile'=>$URSRC_Mobileno,'laptop'=>$URSRC_laptopno,'chargerno'=>$URSRC_chrgrno,'bag'=>$URSRC_bag,'mouse'=>$URSRC_mouse,'dooraccess'=>$URSRC_dooracess,'idcard'=>$URSRC_idcard,'headset'=>$URSRC_headset,'bankname'=>$URSRC_bankname,'branchname'=>$URSRC_brancname,'accountname'=>$URSRC_acctname,'accountno'=>$URSRC_acctno,'ifsccode'=>$URSRC_ifsccode,'accountype'=>$URSRC_acctype,'branchaddress'=>$URSRC_branchaddr,'URSRC_aadhar'=>$URSRC_aadhar,'URSRC_passport'=>$URSRC_passport,'URSRC_voterid'=>$URSRC_voterid,'URSRC_comments'=>$URSRC_comments,'URSRC_role'=>$URSRC_role,'URSRC_house'=>$URSRC_Houseno,'URSRC_street'=>$URSRC_Streetname,'URSRC_area'=>$URSRC_Area,'URSRC_postal'=>$URSRC_Postalcode,'URSRC_btryslno'=>$URSRC_btryslno];
//        echo $final_values;
        }
        $URSRC_values[]=array($final_values,$mindate,$get_lname_array);
        echo json_encode($URSRC_values);
    }
    else if($_REQUEST['option']=="COMPANY_PROPERTY")
    {
        $URSRC_lb_laptopno=$_REQUEST['URSRC_lb_laptopno'];
        $URSRC_cmpny_prop=mysqli_query($con,"SELECT CP_CHARGER_NUMBER,CP_LAPTOP_BAG_NUMBER,CP_MOUSE_NUMBER,CP_BATTERY_SERIAL_NUMBER FROM COMPANY_PROPERTIES WHERE CP_LAPTOP_NUMBER = '$URSRC_lb_laptopno'");
        while($row=mysqli_fetch_array($URSRC_cmpny_prop)){
            $URSRC_charger_no=$row["CP_CHARGER_NUMBER"];
            $URSRC_laptop_no=$row["CP_LAPTOP_BAG_NUMBER"];
            $URSRC_mouse_no=$row["CP_MOUSE_NUMBER"];
            $URSRC_btrysrl_no=$row["CP_BATTERY_SERIAL_NUMBER"];
            $URSRC_report_values=array('chargerno'=>$URSRC_charger_no,'laptopno'=>$URSRC_laptop_no,'mouse'=>$URSRC_mouse_no,'btry'=>$URSRC_btrysrl_no);
            $URSRC_values[]=$URSRC_report_values;
        }
        echo json_encode($URSRC_values);
    }
    else if($_REQUEST['option']=='GET_VALUE'){

        $loginid = $_REQUEST['URT_SRC_loggin'];
        $date_value = $_REQUEST['date_value'];
        $final_date = date('Y-m-d',strtotime( $date_value));
//        echo "SELECT  MAX(UARD_DATE) AS UARD_DATE from USER_ADMIN_REPORT_DETAILS WHERE UARD_DATE>'$final_date' AND ULD_ID='$loginid'";
        $select_data="SELECT  MAX(UARD_DATE) AS UARD_DATE from USER_ADMIN_REPORT_DETAILS WHERE UARD_DATE>'$final_date' AND ULD_ID='$loginid'";
        $select_data_rs=mysqli_query($con,$select_data);
        if($row=mysqli_fetch_array($select_data_rs)){
            $finaldate=$row['UARD_DATE'];
            if($finaldate!=null){
                $finaldate = date('d-m-Y',strtotime($finaldate));
            }
            else
                $finaldate='';
        }
        $flag_array=[$finaldate];
        echo json_encode($flag_array);
//        echo $finaldate;
    }
    else if($_REQUEST['option']=='UPDATE')
    {
        $reason_update=$_REQUEST['URT_SRC_ta_nreasonupdate'];
        $reason_update=$con->real_escape_string($reason_update);
        $loggin=$_REQUEST['URT_SRC_loggin'];
        $date=$_REQUEST['URT_SRC_tb_ndatepickerupdate'];
        $recver=$_REQUEST['URT_SRC_lb_recordversion'];
        $enddate = date("Y-m-d",strtotime($date));
        $sql="UPDATE USER_ACCESS SET UA_END_DATE='$enddate',UA_REASON='$reason_update',UA_USERSTAMP='$userstamp' where ULD_ID='$loggin'  AND UA_REC_VER='$recver'";
        if (!mysqli_query($con,$sql)) {
            die('Error: ' . mysqli_error($con));
            $flag=0;
        }
        else{
            $flag=1;
        }
        echo $flag;
    }
//    else if($_REQUEST['option']=='REJOIN')
    else if($_REQUEST['URT_SRC_btn_rejoin']=='REJOIN')
    {
        $loggin=$_REQUEST['URT_SRC_lb_nloginrejoin'];
//        echo $loggin;
        $loggin_empty='';
//        $login_id=$_REQUEST['login_id'];
        $login_id=$_REQUEST['URT_SRC_lb_loginrejoin'];
//        echo $login_id;
        $role_access = $_REQUEST['URT_SRC_radio_nrole'];
        $final_radioval=str_replace("_"," ",$role_access);
        $date=$_REQUEST['URT_SRC_tb_ndatepickerrejoin'];
        $emp_type=$_REQUEST['URSRC_lb_selectemptype'];
//        echo $emp_type;
        $joindate = date("Y-m-d",strtotime($date));
        $URSRC_firstname=$_REQUEST['URSRC_tb_firstname'];
        $URSRC_lastname=$_REQUEST['URSRC_tb_lastname'];
        $URSRC_dob=$_REQUEST['URSRC_tb_dob'];
        $URSRC_finaldob = date('Y-m-d',strtotime($URSRC_dob));
        $URSRC_designation=$_REQUEST['URSRC_tb_designation'];
        $URSRC_Mobileno=$_REQUEST['URSRC_tb_permobile'];
        $URSRC_kinname=$_REQUEST['URSRC_tb_kinname'];
        $URSRC_relationhd=$_REQUEST['URSRC_tb_relationhd'];
        $URSRC_mobile=$_REQUEST['URSRC_tb_mobile'];
        $URSRC_house=$_REQUEST['URSRC_tb_houseno'];
        $URSRC_street=$_REQUEST['URSRC_tb_strtname'];
        $URSRC_area=$_REQUEST['URSRC_tb_area'];
        $URSRC_postal=$_REQUEST['URSRC_tb_pstlcode'];
        $URSRC_bankname=$_REQUEST['URSRC_tb_bnkname'];
        $URSRC_brancname=$_REQUEST['URSRC_tb_brnchname'];
        $URSRC_acctname=$_REQUEST['URSRC_tb_accntname'];
        $URSRC_acctno=$_REQUEST['URSRC_tb_accntno'];
        $URSRC_ifsccode=$_REQUEST['URSRC_tb_ifsccode'];
        $URSRC_acctype=$_REQUEST['URSRC_tb_accntyp'];
        $URSRC_branchaddr1=$_REQUEST['URSRC_ta_brnchaddr'];
        $URSRC_branchaddr=$con->real_escape_string($URSRC_branchaddr1);
        $URSRC_laptopno=$_REQUEST['URSRC_tb_laptopno'];
        $URSRC_chrgrno=$_REQUEST['URSRC_tb_chargerno'];
        $URSRC_bag=$_REQUEST['URSRC_tb_laptopbagno'];
        $URSRC_aadharno=$_REQUEST['URSRC_tb_aadharno'];
        $URSRC_voterid=$_REQUEST['URSRC_tb_votersid'];
        $URSRC_passportno=$_REQUEST['URSRC_tb_passportno'];
        $URSRC_comments=$_REQUEST['URSRC_ta_comments'];
        $URSRC_btryslno=$_REQUEST['URSRC_tb_btry'];
        $URSRC_comments=$con->real_escape_string($URSRC_comments);

        //File upload function
        $filesarray=$_REQUEST['filearray'];

        //End of File Uploads
//        if($URSRC_bag=='on')
//        {
//            $URSRC_bag= 'X';
//            $bag='YES';
//        }
//        else
//        {
//            $URSRC_bag='';
//            $bag='NO';
//        }
        $URSRC_chklapbag=$_REQUEST['URSRC_chk_bag'];
        $URSRC_bag=$_REQUEST['URSRC_tb_laptopbagno'];
//        if($URSRC_chklapbag=='on')
//        {
//            $URSRC_bag;
//        }
//        else
//        {
//            $URSRC_bag='';
//        }
//        echo $URSRC_bag;
        $URSRC_chkmouse=$_REQUEST['URSRC_chk_mouse'];
        $URSRC_mouse=$_REQUEST['URSRC_tb_mouse'];
        if($URSRC_chkmouse=='on')
        {
            $URSRC_mouse;
        }
        else
        {
            $URSRC_mouse='';
        }
//        if($URSRC_mouse=='on')
//        {
//            $URSRC_mouse= 'X';
//            $mouse='YES';
//        }
//        else
//        {
//            $URSRC_mouse='';
//            $mouse='NO';
//        }
        $URSRC_dooracess=$_REQUEST['URSRC_chk_dracess'];
        if($URSRC_dooracess=='on')
        {
            $URSRC_dooracess= 'X';
            $dooraccess='YES';
        }
        else
        {
            $URSRC_dooracess='';
            $dooraccess='NO';
        }
        $URSRC_idcard=$_REQUEST['URSRC_chk_idcrd'];
        if($URSRC_idcard=='on')
        {
            $URSRC_idcard= 'X';
            $idcard='YES';
        }
        else
        {
            $URSRC_idcard='';
            $idcard='NO';
        }
        $URSRC_headset=$_REQUEST['URSRC_chk_headset'];
        if($URSRC_headset=='on')
        {
            $URSRC_headset= 'X';
            $headset='YES';
        }
        else
        {
            $URSRC_headset='';
            $headset='NO';
        }
        $URSRC_chk_aadharno=$_REQUEST['URSRC_chk_aadharno'];
        if($URSRC_chk_aadharno=='on')
        {
            $URSRC_aadharno;
        }
        else
        {
            $URSRC_aadharno='';
        }
        $URSRC_chk_passportno=$_REQUEST['URSRC_chk_passportno'];
        if($URSRC_chk_passportno=='on')
        {
            $URSRC_passportno;
        }
        else
        {
            $URSRC_passportno='';
        }
        $URSRC_chk_votersid=$_REQUEST['URSRC_chk_votersid'];
        if($URSRC_chk_votersid=='on')
        {
            $URSRC_voterid;
        }
        else
        {
            $URSRC_voterid='';
        }
        $con->autocommit(false);
//        echo "CALL  SP_TS_LOGIN_CREATION_INSERT('2','$loggin_empty','$loggin','$final_radioval','$joindate','$emp_type','$URSRC_firstname','$URSRC_lastname','$URSRC_finaldob','$URSRC_designation','$URSRC_Mobileno','$URSRC_kinname','$URSRC_relationhd','$URSRC_mobile','$URSRC_bankname','$URSRC_brancname','$URSRC_acctname','$URSRC_acctno','$URSRC_ifsccode','$URSRC_acctype','$URSRC_branchaddr','$URSRC_aadharno','$URSRC_passportno','$URSRC_voterid','$URSRC_comments','$URSRC_laptopno','$URSRC_chrgrno','$URSRC_bag','$URSRC_mouse','$URSRC_dooracess','$URSRC_idcard','$URSRC_headset','$userstamp','$URSRC_house','$URSRC_street','$URSRC_area','$URSRC_postal','$URSRC_btryslno',@success_flag)";
//   exit;
        $result = $con->query("CALL  SP_TS_LOGIN_CREATION_INSERT('2','$loggin_empty','$loggin','$final_radioval','$joindate','$emp_type','$URSRC_firstname','$URSRC_lastname','$URSRC_finaldob','$URSRC_designation','$URSRC_Mobileno','$URSRC_kinname','$URSRC_relationhd','$URSRC_mobile','$URSRC_bankname','$URSRC_brancname','$URSRC_acctname','$URSRC_acctno','$URSRC_ifsccode','$URSRC_acctype','$URSRC_branchaddr','$URSRC_aadharno','$URSRC_passportno','$URSRC_voterid','$URSRC_comments','$URSRC_laptopno','$URSRC_chrgrno','$URSRC_bag','$URSRC_mouse','$URSRC_dooracess','$URSRC_idcard','$URSRC_headset','$userstamp','$URSRC_house','$URSRC_street','$URSRC_area','$URSRC_postal','$URSRC_btryslno',@success_flag)");
//        $result = $con->query("CALL  SP_TS_LOGIN_CREATION_INSERT('2','$loggin_empty','$login_id','$final_radioval','$joindate','$emp_type','$URSRC_firstname','$URSRC_lastname','$URSRC_finaldob','$URSRC_designation','$URSRC_Mobileno','$URSRC_kinname','$URSRC_relationhd','$URSRC_mobile','$URSRC_bankname','$URSRC_brancname','$URSRC_acctname','$URSRC_acctno','$URSRC_ifsccode','$URSRC_acctype','$URSRC_branchaddr','$URSRC_aadharno','$URSRC_passportno','$URSRC_voterid','$URSRC_comments','$URSRC_laptopno','$URSRC_chrgrno','$URSRC_bag','$URSRC_mouse','$URSRC_dooracess','$URSRC_idcard','$URSRC_headset','$userstamp','$URSRC_house','$URSRC_street','$URSRC_area','$URSRC_postal',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
        if($flag==1){
            $select_loggin=mysqli_query($con,"SELECT * from VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS where ULD_ID= $loggin");
            if($row=mysqli_fetch_array($select_loggin)){
                $loggin=$row["ULD_LOGINID"];

            }
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
            $select_link=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=4");
            if($row=mysqli_fetch_array($select_link)){
                $site_link=$row["URC_DATA"];
            }
            $select_ss_link=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=5");
            if($row=mysqli_fetch_array($select_ss_link)){
                $ss_link=$row["URC_DATA"];
            }

            $select_fileid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=9");
            if($row=mysqli_fetch_array($select_fileid)){
                $ss_fileid=$row["URC_DATA"];
            }
            $select_fileid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=35");
            if($row=mysqli_fetch_array($select_fileid)){
                $ss_codefileid=$row["URC_DATA"];
            }
            $loginid_name = strtoupper(substr($loggin, 0, strpos($loggin, '@')));
            if(substr($loginid_name, 0, strpos($loginid_name, '.'))){

                $loginid_name = strtoupper(substr($loginid_name, 0, strpos($loginid_name, '.')));

            }
            else{
                $loginid_name=$loginid_name;
            }
            $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$loggin'");
            while($row=mysqli_fetch_array($uld_id)){
                $URSC_uld_id=$row["ULD_ID"];
            }
            $select_des=mysqli_query($con,"SELECT EMP_DESIGNATION FROM EMPLOYEE_DETAILS WHERE ULD_ID='$URSC_uld_id'");
            while($row=mysqli_fetch_array($select_des)){
                $URSC_des=$row["EMP_DESIGNATION"];
            }

            $select_calenderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=10");
            if($row=mysqli_fetch_array($select_calenderid)){
                $calenderid=$row["URC_DATA"];
            }

            $select_youtubelink=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=12");
            if($row=mysqli_fetch_array($select_youtubelink)){
                $youtubelink=$row["URC_DATA"];
            }
            //CODE  OPTIMIZATION
            $select_code=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=34");
            if($row=mysqli_fetch_array($select_code)){
                $codeoptimi=$row["URC_DATA"];
            }
            $select_folderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=13");
            if($row=mysqli_fetch_array($select_folderid)){
                $folderid=$row["URC_DATA"];
            }

            $fileId=$ss_fileid;
            $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=1";
            $select_template_rs=mysqli_query($con,$select_template);
            if($row=mysqli_fetch_array($select_template_rs)){
                $mail_subject=$row["ETD_EMAIL_SUBJECT"];
                $body=$row["ETD_EMAIL_BODY"];
            }
            $select_displayname="SELECT URC_DATA FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=26";
            $select_displayname_rs=mysqli_query($con,$select_displayname);
            if($row=mysqli_fetch_array($select_displayname_rs)){
                $mail_displayname=$row["URC_DATA"];
            }
            $drive = new Google_Client();
            $Client=get_servicedata();
            $ClientId=$Client[0];
            $ClientSecret=$Client[1];
            $RedirectUri=$Client[2];
            $DriveScopes=$Client[3];
            $CalenderScopes=$Client[4];
            $Refresh_Token=$Client[5];
            $drive->setClientId($ClientId);
            $drive->setClientSecret($ClientSecret);
            $drive->setRedirectUri($RedirectUri);
            $drive->setScopes(array($DriveScopes,$CalenderScopes));
            $drive->setAccessType('online');
            $authUrl = $drive->createAuthUrl();
            $refresh_token= $Refresh_Token;
            $drive->refreshToken($refresh_token);
            $service = new Google_Service_Drive($drive);
//            echo "URSRC_calendar_create($loggin,$fileId,$URSRC_firstname,$URSC_uld_id,$joindate,$calenderid,'REJOIN DATE','REJOIN',$filesarray,$URSRC_firstname,$URSRC_lastname,$folderid)";

            $return_array=URSRC_calendar_create($loggin,$fileId,$URSRC_firstname,$URSC_uld_id,$joindate,$calenderid,'REJOIN DATE','REJOIN',$filesarray,$URSRC_firstname,$URSRC_lastname,$folderid);
//           print_r($return_array);

            $ss_flag=$return_array[0];
            $cal_flag=$return_array[1];

            $file_array=$return_array[3];
//            echo $cal_flag.$ss_flag;
            if($filesarray!=''){

                if(count($file_array)==0){
                    $file_flag=0;
                    $cal_flag=0;
                    URSRC_unshare_document($loggin,$fileId);
                    $con->rollback();

                }
            }

            if($ss_flag==0){

                $con->rollback();

            }
            if($cal_flag==0){
                URSRC_unshare_document($loggin,$fileId);
                for($i=0;$i<count($file_array);$i++){
                    delete_file($service,$file_array[$i]);
                }
                $con->rollback();
            }
//            echo $flag.$ss_flag.$cal_flag.$fileId.$file_flag.$folderid;
            if(($ss_flag==1)&&($cal_flag==1)){
                $email_body;
                $body_msg =explode("^", $body);
                $length=count($body_msg);
                for($i=0;$i<$length;$i++){
                    $email_body.=$body_msg[$i].'<br><br>';
                }
                $replace= array("[LOGINID]", "[LINK]","[SSLINK]", "[VLINK]","[DES]","[CLINK]");
                $str_replaced  = array($URSRC_firstname,$site_link, $ss_link, $youtubelink,'<b>'.$URSRC_designation.'</b>',$codeoptimi);
                $final_message = str_replace($replace, $str_replaced, $email_body);

                $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=10";
                $select_template_rs=mysqli_query($con,$select_template);
                if($row=mysqli_fetch_array($select_template_rs)){
                    $mail_subject1=$row["ETD_EMAIL_SUBJECT"];
                    $body=$row["ETD_EMAIL_BODY"];
                }
                //not aplicable
                if($URSRC_laptopno=='' || $URSRC_laptopno=='SELECT')
                {
                    $URSRC_laptopno="N/A";
                }
                else{
                    $URSRC_laptopno=$_POST['URSRC_tb_laptopno'];
                }
                if($URSRC_chrgrno=='')
                {
                    $URSRC_chrgrno="N/A";
                }
                else{
                    $URSRC_chrgrno=$_POST['URSRC_tb_chargerno'];
                }
                if($URSRC_btryslno=='')
                {
                    $URSRC_btryslno="N/A";
                }
                else{
                    $URSRC_btryslno=$_POST['URSRC_tb_btry'];
                }
                $URSRC_bag=$_POST['URSRC_tb_laptopbagno'];
                if($URSRC_bag!='')
                {
                    $URSRC_bag;
                }
                else{
                    $URSRC_bag="N/A";
                }
                if($URSRC_chk_aadharno=='on')
                {
                    $URSRC_aadharno;
                }
                else
                {
                    $URSRC_aadharno="N/A";
                }
                if($URSRC_chk_passportno=='on')
                {
                    $URSRC_passportno;
                }
                else
                {
                    $URSRC_passportno="N/A";
                }
                if($URSRC_chk_votersid=='on')
                {
                    $URSRC_voterid;
                }
                else
                {
                    $URSRC_voterid="N/A";
                }
                if($URSRC_chkmouse=='on')
                {
                    $URSRC_mouse;
                }
                else
                {
                    $URSRC_mouse="N/A";
                }
                //not applicable
//STRING REPLACE FUNCTION
                $emp_email_body;
                $body_msg =explode("^", $body);
                $length=count($body_msg);
                for($i=0;$i<$length;$i++){
                    $emp_email_body.=$body_msg[$i].'<br><br>';
                }
                $comment_per =explode("\n", $URSRC_comments);
                $commnet_length=count($comment_per);
                for($i=0;$i<$commnet_length;$i++){
                    $comment_msgper.=$comment_per[$i].'<br>';
                }
                $comment =explode("\n", $URSRC_branchaddr1);
                $commnet_length=count($comment);
                for($i=0;$i<$commnet_length;$i++){
                    $comment_msg.=$comment[$i].'<br>';
                }

                $replace= array( "[FNAME]","[LNAME]", "[DOB]","[DESG]","[MOBNO]","[KINNAME]","[REL]","[ALTMOBNO]","[AADHAAR NO]","[PASSPORT NO]","[VOTERS ID NO]","[LAPNO]","[CHRNO]","[LAPBAG]","[MOUSE]","[DACC]","[IDCARD]","[HEADSET]","[BANKNAME]","[BRANCHNAME]","[ACCNAME]","[ACCNO]","[IFSCCODE]","[ACCTYPE]","[BANKADDRESS]","PERSONAL DETAILS:","COMPANY PROPERTIES DETAILS:","BANK ACCOUNT DETAILS:","[AADHAAR NO]","[PASSPORT NO]","[VOTERS ID NO]","[HOUSE NO]","[STREET NAME]","[PINCODE]","[AREA]","[COMMENTS]","[BSNO]");
                $str_replaced  = array($URSRC_firstname, $URSRC_lastname, $URSRC_dob,$URSRC_designation,$URSRC_Mobileno,$URSRC_kinname,$URSRC_relationhd,$URSRC_mobile,$URSRC_aadharno,$URSRC_passportno,$URSRC_voterid,$URSRC_laptopno,$URSRC_chrgrno,$URSRC_bag,$URSRC_mouse,$dooraccess,$idcard,$headset,$URSRC_bankname,$URSRC_brancname,$URSRC_acctname,$URSRC_acctno,$URSRC_ifsccode,$URSRC_acctype,$comment_msg,'<b>'."PERSONAL DETAILS:".'</b>','<b>'."COMPANY PROPERTIES DETAILS:".'</b>','<b>'."BANK ACCOUNT DETAILS:".'</b>',$URSRC_aadharno,$URSRC_passportno,$URSRC_voterid,$URSRC_house,$URSRC_street,$URSRC_postal,$URSRC_area,$comment_msgper,$URSRC_btryslno);

                $newphrase = str_replace($replace, $str_replaced, $emp_email_body);
                $final_message=$final_message.'<br>'.$newphrase;

                //SENDING MAIL OPTIONS
                $name = $mail_displayname;
                $from = 'lalitha.rajendiran@ssomens.com';//$admin;
                $message1 = new Message();
                $message1->setSender($name.'<'.$from.'>');
//                $message1->addTo($loggin);
                $message1->addTo('lalitha.rajendiran@ssomens.com');
//                $message1->addCc($admin);
                $message1->setSubject($mail_subject);
                $message1->setHtmlBody($final_message);

                try {
                    $message1->send();
                } catch (\InvalidArgumentException $e) {
                    echo $e;
                }

                $select_intro_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=14";
                $select_introtemplate_rs=mysqli_query($con,$select_intro_template);
                if($row=mysqli_fetch_array($select_introtemplate_rs)){
                    $intro_mail_subject=$row["ETD_EMAIL_SUBJECT"];
                    $intro_body=$row["ETD_EMAIL_BODY"];
                }
                $select_intro_displayname="SELECT URC_DATA FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=26";
                $select_displayname_rs=mysqli_query($con,$select_intro_displayname);
                if($row=mysqli_fetch_array($select_displayname_rs)){
                    $intro_mail_displayname=$row["URC_DATA"];
                }
                $intro_email_body;
                $intro_body_msg =explode("^", $intro_body);
                $intro_length=count($intro_body_msg);
                for($i=0;$i<$intro_length;$i++){
                    $intro_email_body.=$intro_body_msg[$i].'<br><br>';
                }
                $replace= array("[DATE]", "[employee name]","[emailid]","[designation]");
                $str_replaced  = array(date("d-m-Y"),'<b>'.$URSRC_firstname.'</b>', $loggin,'<b>'.$URSRC_designation.'</b>');
                $intro_message = str_replace($replace, $str_replaced, $intro_email_body);
                $cc_array=get_active_login_id();
//                $cc_array=['safiyullah.mohideen@ssomens.com'];
                //SENDING MAIL OPTIONS
                $name = $intro_mail_displayname;
                $from = 'lalitha.rajendiran@ssomens.com';//$admin;
                $message1 = new Message();
                $message1->setSender($name.'<'.$from.'>');
//                $message1->addTo($cc_array);
                $message1->addTo('lalitha.rajendiran@ssomens.com');
                $message1->setSubject($intro_mail_subject);
                $message1->setHtmlBody($intro_message);

                try {
                    $message1->send();
                } catch (\InvalidArgumentException $e) {
                    echo $e;
                }
            }
            $flag_array=[$flag,$ss_flag,$cal_flag,$fileId,$file_flag,$folderid];
//            $flag_array=[$flag,'1','1','1','1','1'];
//
        }
        else{

            $flag_array=[$flag];

        }
        $con->commit();
        echo json_encode($flag_array);
    }
    else if($_REQUEST['option']=='TERMINATE')
    {
        set_time_limit(0);
        $reason_termin=$_POST['URT_SRC_ta_nreasontermination'];
        $reason_termin=$con->real_escape_string($reason_termin);
        $loggin=$_REQUEST['loggin'];
        $date=$_POST['URT_SRC_tb_ndatepickertermination'];
        $enddate = date("Y-m-d",strtotime($date));
        $con->autocommit(false);
        $select_loggin=mysqli_query($con,"SELECT * from VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS where ULD_ID= $loggin");
        if($row=mysqli_fetch_array($select_loggin)){
            $loginid=$row["ULD_LOGINID"];
            $emp_name=$row["EMPLOYEE_NAME"];

        }
//        echo "CALL SP_TS_LOGIN_TERMINATE_SAVE($loggin,'$enddate','$reason_termin','$userstamp',@success_flag)";
        $result = $con->query("CALL SP_TS_LOGIN_TERMINATE_SAVE($loggin,'$enddate','$reason_termin','$userstamp',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag=$result['@success_flag'];
        if($flag==1){

            $select_fileid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=9");
            if($row=mysqli_fetch_array($select_fileid)){
                $ss_fileid=$row["URC_DATA"];
            }

            $loginid_name = strtoupper(substr($loginid, 0, strpos($loginid, '@')));
            if(substr($loginid_name, 0, strpos($loginid_name, '.'))){

                $loginid_name = strtoupper(substr($loginid_name, 0, strpos($loginid_name, '.')));
            }
            else{
                $loginid_name=$loginid_name;
            }
            $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$loginid'");
            while($row=mysqli_fetch_array($uld_id)){
                $URSC_uld_id=$row["ULD_ID"];
            }
            $select_calenderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=10");
            if($row=mysqli_fetch_array($select_calenderid)){
                $calenderid=$row["URC_DATA"];
            }
            $fileId=$ss_fileid;
//
            $return_array=URSRC_calendar_create($loginid,$fileId,$emp_name,$URSC_uld_id,$enddate,$calenderid,'TERMINATE DATE','TERMINATE','','','','');
            $ss_flag=$return_array[0];
            $cal_flag=$return_array[1];
            if($ss_flag==0){
                $con->rollback();
            }
            if($cal_flag==0){
                share_document($loginid,$fileId);
                $con->rollback();
            }
            $flag_array=[$flag,$ss_flag,$cal_flag,$fileId];
        }
        else{
            $flag_array=[$flag];
        }
        $con->commit();
        echo json_encode($flag_array);
    }
}
//FUNCTION FOR CALENDAR SHARING DOCUMENT
function share_document($loggin,$fileId){
    global $con,$ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token;
    $drive = new Google_Client();
    $Client=get_servicedata();
    $ClientId=$Client[0];
    $ClientSecret=$Client[1];
    $RedirectUri=$Client[2];
    $DriveScopes=$Client[3];
    $CalenderScopes=$Client[4];
    $Refresh_Token=$Client[5];
    $drive->setClientId($ClientId);
    $drive->setClientSecret($ClientSecret);
    $drive->setRedirectUri($RedirectUri);
    $drive->setScopes(array($DriveScopes,$CalenderScopes));
    $drive->setAccessType('online');
    $authUrl = $drive->createAuthUrl();
    $refresh_token= $Refresh_Token;
    $drive->refreshToken($refresh_token);
    $service = new Google_Service_Drive($drive);
    $value=$loggin;
    $type='user';
    $role='reader';
    $email=$loggin;
    $newPermission = new Google_Service_Drive_Permission();
    $newPermission->setValue($value);
    $newPermission->setType($type);
    $newPermission->setRole($role);
    $newPermission->setEmailAddress($email);
    try {
        $service->permissions->insert($fileId, $newPermission);
        $ss_flag=1;
    } catch (Exception $e) {
        $ss_flag=0;
    }
}
//FUNCTION FOR CALENDAR CREATION
function URSRC_calendar_create($loggin,$fileId,$loginid_name,$URSC_uld_id,$finaldate,$calenderid,$status,$form,$filesarray,$URSRC_firstname,$URSRC_lastname,$folderid){
    global $con,$ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token;
    $drive = new Google_Client();
    $Client=get_servicedata();
    $ClientId="651424197810-72ki9kr1k58v3qec0034q1bfj2og6ss8.apps.googleusercontent.com";//$Client[0];
    $ClientSecret="NRY1PYuUK-NeJAZVJxUvXBeb";//$Client[1];
    $RedirectUri="https://developers.google.com/oauthplayground";//$Client[2];
    $DriveScopes="https://www.googleapis.com/auth/drive";//$Client[3];
    $CalenderScopes="https://www.googleapis.com/auth/calendar";//$Client[4];
    $Refresh_Token="1/YkIO518mEEVO9z5bdieivdpf9XVBnZN4PWZPLStyGzk";//$Client[5];
    $drive->setClientId($ClientId);
    $drive->setClientSecret($ClientSecret);
    $drive->setRedirectUri($RedirectUri);
    $drive->setScopes(array($DriveScopes,$CalenderScopes));
    $drive->setAccessType('online');
    $authUrl = $drive->createAuthUrl();
    $refresh_token= $Refresh_Token;
    $drive->refreshToken($refresh_token);
    $service = new Google_Service_Drive($drive);
//    print_r($service);

    if($form=='TERMINATE'){
        $file_arraycount=1;
        try {
            $permissions = $service->permissions->listPermissions($fileId);
            $return_value= $permissions->getItems();
        } catch (Exception $e) {
            $ss_flag=0;
        }
        foreach ($return_value as $key => $value) {
            if ($value->emailAddress==$loggin) {
                $permission_id=$value->id;
            }
        }
        if($permission_id!=''){
            try {
                $service->permissions->delete($fileId, $permission_id);
                $ss_flag=1;
            } catch (Exception $e) {
                $ss_flag=0;
            }
        }
        else{

            $ss_flag=1;
        }
    }
    else{
        $value=$loggin;
        $type='user';
        $role='reader';
        $email=$loggin;
        $newPermission = new Google_Service_Drive_Permission();
        $newPermission->setValue($value);
        $newPermission->setType($type);
        $newPermission->setRole($role);
        $newPermission->setEmailAddress($email);
        try {
            $service->permissions->insert($fileId, $newPermission);
            $ss_flag=1;
        } catch (Exception $e) {
            $ss_flag=0;
        }

        if($ss_flag==1){
            if($filesarray!='')
            {
                $file_array=array();
                $allfilearray=(explode(",",$filesarray));
                foreach ($allfilearray as $value)
                {
                    $uploadfilename=$value;
                    $drivefilename=$URSRC_firstname.' '.$URSRC_lastname.'-'.$uploadfilename;
                    $extension =(explode(".",$uploadfilename));
                    if($extension[1]=='pdf'){$mimeType='application/pdf';}
                    if($extension[1]=='jpg'){$mimeType='image/jpeg';}
                    if($extension[1]=='png'){$mimeType='image/png';}
                    $file_id_value =insertFile($service,$drivefilename,'PersonalDetails',$folderid,$mimeType,$uploadfilename);
                    if($file_id_value!=''){
                        array_push($file_array,$file_id_value);
                    }
                }
                $file_arraycount=count($file_array);
            }
            else{
                $file_arraycount=1;
            }
        }


    }
    if($ss_flag==1 && $file_arraycount>0){
        $cal = new Google_Service_Calendar($drive);
        $event = new Google_Service_Calendar_Event();
        $event->setsummary($loginid_name.'  '.$status);
        $event->setDescription($URSC_uld_id);
        $start = new Google_Service_Calendar_EventDateTime();
        $start->setDate($finaldate);//setDate('2014-11-18');
        $event->setStart($start);
        $event->setEnd($start);
        try{
            $createdEvent = $cal->events->insert($calenderid, $event);
            $cal_flag=1;
        }
        catch(Exception $e){
            $cal_flag=0;
        }
    }
    $flag_array=[$ss_flag,$cal_flag,$file_id_value,$file_array];
    return $flag_array;
}
//File Upload Function Script
function insertFile($service, $title, $description, $parentId,$mimeType,$uploadfilename)
{
    $file = new Google_Service_Drive_DriveFile();
    $file->setTitle($title);
    $file->setDescription($description);
    $file->setMimeType($mimeType);
    if ($parentId != null) {
        $parent = new Google_Service_Drive_ParentReference();
        $parent->setId($parentId);
        $file->setParents(array($parent));
    }
    try
    {
        $data =file_get_contents($uploadfilename);
        $createdFile = $service->files->insert($file, array(
            'data' => $data,
            'mimeType' => $mimeType,
            'uploadType' => 'media',
        ));

        $fileid = $createdFile->getId();

    }
    catch (Exception $e)
    {
        $file_flag=0;

    }
    return $fileid;
//    return $file_flag;

}
function URSRC_unshare_document($loggin,$fileId){

    global $con,$ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token;
    $drive = new Google_Client();
    $drive->setClientId($ClientId);
    $drive->setClientSecret($ClientSecret);
    $drive->setRedirectUri($RedirectUri);
    $drive->setScopes(array($DriveScopes,$CalenderScopes));
    $drive->setAccessType('online');
    $authUrl = $drive->createAuthUrl();
    $refresh_token= $Refresh_Token;
    $drive->refreshToken($refresh_token);
    $service = new Google_Service_Drive($drive);


    try {
        $permissions = $service->permissions->listPermissions($fileId);
        $return_value= $permissions->getItems();
    } catch (Exception $e) {
//        print "An error occurred: " . $e->getMessage();
        $ss_flag=0;
    }
    foreach ($return_value as $key => $value) {
        if ($value->emailAddress==$loggin) {
            $permission_id=$value->id;
        }
    }
    if($permission_id!=''){
        try {
            $service->permissions->delete($fileId, $permission_id);
//        $ss_flag=1;
        } catch (Exception $e) {
//        print "An error occurred: " . $e->getMessage();
//        $ss_flag=0;
        }
    }

}
function delete_file($service,$fileid){

    try {
        $f=$service->files->delete($fileid);
    } catch (Exception $e) {
        $f= "An error occurred: " . $e->getMessage();
    }
    return $f;

}