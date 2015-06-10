<?php
//error_reporting(0);
include "TSLIB/TSLIB_GET_USERSTAMP.php";
include "TSLIB/TSLIB_CONNECTION.php";
include "TSLIB/TSLIB_COMMON.php";
$USERSTAMP=$UserStamp;
if($_REQUEST['option']=="MENU")
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    $user_ipaddress = $ip = getenv('HTTP_CLIENT_IP')?:
        $user_ipaddress =     getenv('HTTP_X_FORWARDED_FOR')?:
            $user_ipaddress =    getenv('HTTP_X_FORWARDED')?:
                $user_ipaddress =    getenv('HTTP_FORWARDED_FOR')?:
                    $user_ipaddress =   getenv('HTTP_FORWARDED')?:
                        $user_ipaddress =    getenv('REMOTE_ADDR');
    $ip_check_flag=0;
    $flag_wfh=null;
    try{

        $err_msg=get_error_msg('61,119,124');

        $select_loginid_role=mysqli_query($con,"SELECT URC_DATA from VW_ACCESS_RIGHTS_TERMINATE_LOGINID where ULD_LOGINID='$USERSTAMP' ");
        $login_id_role;
        while($row=mysqli_fetch_array($select_loginid_role)){
            $login_id_role=$row["URC_DATA"];
        }

        $userstamp_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
        while($row=mysqli_fetch_array($userstamp_id)){
            $ADM_userstamp_id=$row["ULD_ID"];
        }

        $wfh_flag=mysqli_query($con,"select WFHA_FLAG from WORK_FROM_HOME_ACCESS where ULD_ID=$ADM_userstamp_id");
        $wfh_row=mysqli_num_rows($wfh_flag);
        if($wfh_row>0)
        {
            if($row=mysqli_fetch_array($wfh_flag))
            {
                $flag_wfh=$row['WFHA_FLAG'];
            }
            if($flag_wfh=='X')
            {
                $ip_check_flag=1;
            }
        }

        if($flag_wfh==null)
        {
            $id_add=mysqli_query($con,"select URC_DATA from USER_RIGHTS_CONFIGURATION where URC_ID=15");
            while($row=mysqli_fetch_array($id_add)){
                if(preg_match("/$user_ipaddress/",$row[0])){
                    $ip_check_flag=1;
                }
            }
        }


//        $id_add=mysqli_query($con,"select URC_DATA from USER_RIGHTS_CONFIGURATION where URC_ID=15");
//        while($row=mysqli_fetch_array($id_add)){
//            if(preg_match("/$user_ipaddress/",$row[0])){
//                $ip_check_flag=1;
//            }
//        }
        $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM USER_LOGIN_DETAILS ULD,USER_ACCESS UA,USER_MENU_DETAILS UMP,MENU_PROFILE MP where ULD_LOGINID='$UserStamp' and UA.ULD_ID=ULD.ULD_ID and UA.RC_ID=UMP.RC_ID and MP.MP_ID=UMP.MP_ID AND UA.UA_TERMINATE IS NULL ORDER BY MP_MNAME ASC");

        $ure_values=array();
        $URSC_Main_menu_array=array();
        $i=0;
        while($row=mysqli_fetch_array($main_menu_data)){
            $URSC_Main_menu_array[]=$row["MP_MNAME"];

            $sub_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MSUB from USER_LOGIN_DETAILS ULD,USER_ACCESS UA,USER_MENU_DETAILS UMP,MENU_PROFILE MP where ULD_LOGINID='$UserStamp' and UA.ULD_ID=ULD.ULD_ID and UA.RC_ID=UMP.RC_ID and MP.MP_ID=UMP.MP_ID and MP.MP_MNAME='$URSC_Main_menu_array[$i]' ORDER BY MP_MSUB ASC");

            $URSC_sub_menu_row=array();
            $URSC_sub_sub_menu_row_col=array();
            $URSC_sub_sub_menu_row_col_data=array();
            $j=0;
            while($row=mysqli_fetch_array($sub_menu_data))  {
                $URSC_sub_menu_row[]=$row["MP_MSUB"];
                $sub_sub_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MSUBMENU,MP_MFILENAME,MP_SCRIPT_FLAG FROM USER_LOGIN_DETAILS ULD,USER_ACCESS UA,USER_MENU_DETAILS UMP,MENU_PROFILE MP where ULD_LOGINID='$UserStamp' and UA.ULD_ID=ULD.ULD_ID and UA.RC_ID=UMP.RC_ID and MP.MP_ID=UMP.MP_ID and MP.MP_MNAME='$URSC_Main_menu_array[$i]' AND MP_MSUB='$URSC_sub_menu_row[$j]'  ORDER BY MP_MSUBMENU ASC");
                $URSC_sub_sub_menu_row_data=array();
                $script_flag=array();
                $file_name=array();
                while($row=mysqli_fetch_array($sub_sub_menu_data)){

                    $script_flag[]=$row["MP_SCRIPT_FLAG"];
                    $file_name[]=$row["MP_MFILENAME"];
                    if($row["MP_MSUBMENU"]==null||$row["MP_MSUBMENU"]=="")continue;
                    $URSC_sub_sub_menu_row_data[]=$row["MP_MSUBMENU"];

                }
                $URSC_script_flag[]=$script_flag;
                $URSRC_filename[]=$file_name;
                $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
                $j++;
            }

            $URSC_sub_menu_array[]=$URSC_sub_menu_row;
            $i++;
        }

        if(count($URSC_Main_menu_array)!=0){
            $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array,$URSC_script_flag,$URSRC_filename);    // $final = array($URSC_sub_menu_array,$URSC_sub_sub_menu_array,$URSC_sub_sub_menu_data_array);
        }
        else{

            $final_values=array($URSC_Main_menu_array,$err_msg);
        }
        $_SESSION['menus']=$final_values;

        $date=date('Y-m-d');

        $checkintime=mysqli_query($con,"select ECIOD_CHECK_IN_TIME from EMPLOYEE_CHECK_IN_OUT_DETAILS where ECIOD_DATE='$date' and ULD_ID='$ADM_userstamp_id'");
        $row=mysqli_num_rows($checkintime);
        if($row>0)
        {
            while($row=mysqli_fetch_array($checkintime)){
                $checkintime=$row["ECIOD_CHECK_IN_TIME"];
            }
        }
        else
        {
            $checkintime=null;
        }
        $checkouttime=mysqli_query($con,"select ECIOD_CHECK_OUT_TIME from EMPLOYEE_CHECK_IN_OUT_DETAILS where ECIOD_DATE='$date' and ULD_ID='$ADM_userstamp_id'");
        $checkoutrow=mysqli_num_rows($checkouttime);
        if($checkoutrow>0){

            if($checkoutrow=mysqli_fetch_array($checkouttime)){
                $check_out_time=$checkoutrow["ECIOD_CHECK_OUT_TIME"];
            }
        }
        else{

            $check_out_time=null;

        }
        $allvalues=array($final_values,$checkintime,$err_msg,$date,$check_out_time,$login_id_role,$ip_check_flag);
//    mysqli_close($con);
        echo JSON_ENCODE($allvalues);
    }
    catch (mysqli_sql_exception $e) {


        echo $e->getMessage();

    }
}
else if($_REQUEST['option']=='CLOCK')
{
    global $con;
    $check_in_out_location=$_REQUEST['location'];
    $btn_value=$_REQUEST['btn_value'];
    $date=date('Y-m-d');
    $checkintime=date("G:i:s", time());
    $checkouttime=date("G:i:s", time());
    $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($uld_id)){
        $ure_uld_id=$row["ULD_ID"];
    }
    if($btn_value=='CLOCK IN'){
        $result = $con->query("CALL SP_TS_EMPLOYEE_CHECK_IN_OUT_DETAILS_INSERT_UPDATE('1','$ure_uld_id','$date','$checkintime','$check_in_out_location','$ure_uld_id',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
    }
    else{
        $result = $con->query("CALL SP_TS_EMPLOYEE_CHECK_IN_OUT_DETAILS_INSERT_UPDATE('2','$ure_uld_id','$date','$checkouttime','$check_in_out_location','$ure_uld_id',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];

    }
//    $sql="INSERT INTO EMPLOYEE_CHECK_IN_OUT_DETAILS(ULD_ID,ECIOD_DATE,ECIOD_CHECK_IN_TIME,ECIOD_CHECK_IN_LOCATION,ECIOD_CHECK_OUT_TIME,ECIOD_CHECK_OUT_LOCATION,ECIOD_USERSTAMP_ID) VALUES('$ure_uld_id','$date','$checkintime','$checkinlocation',$checkouttime,$checkoutlocation,'$ure_uld_id')";
//    if (!mysqli_query($con,$sql)) {
//        die('Error: ' . mysqli_error($con));
//
//        $flag=0;
//    }
//    else{
//        $flag=1;
//    }
    $values=array($flag,$checkintime);
    echo JSON_encode($values);
}

?>