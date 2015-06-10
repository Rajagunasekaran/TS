<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************CLOCK IN/OUT DETAILS*********************************************//
//DONE BY:LALITHA
//VER 0.02-SD:09/01/2015 ED:10/01/2015,TRACKER NO:74,Updated Sorting function
//VER 0.01-INITIAL VERSION, SD:03/01/2015 ED:05/01/2015,TRACKER NO:74
//*********************************************************************************************************//
error_reporting(0);
if(isset($_REQUEST)){
    include "../../TSLIB/TSLIB_CONNECTION.php";
    include "../../TSLIB/TSLIB_COMMON.php";
    include "../../TSLIB/TSLIB_GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
//FETCHING DATAS LOADED FRM DB FOR INITIAL LISTBX
    if($_REQUEST['option']=="common")
    {
// GET ERR MSG
        $REP_chk_errmsg=get_error_msg("15,16,18,83,121,122");
        //GET ACTIVE LOGIN ID
        $REP_chk_active_emp=get_active_emp_id();
        // NONACTIVE EMPLOYEE LIST
        $REP_chk_active_nonemp=get_nonactive_emp_id();
// REPORT CONFIGURATION LIST
        $REP_report_config = mysqli_query($con,"SELECT * FROM REPORT_CONFIGURATION WHERE CGN_ID=16");
        $REP_rprtconfiglist=array();
        while($row=mysqli_fetch_array($REP_report_config)){
            $REP_rprtconfiglist[]=array($row["RC_DATA"],$row["RC_ID"]);
        }
        $REP_CHK_final_values=array($REP_chk_active_emp,$REP_chk_active_nonemp,$REP_rprtconfiglist,$REP_chk_errmsg);
        echo JSON_ENCODE($REP_CHK_final_values);
    }
    //SETTING MIN ND MAX DATE FOR DATE PICKER WITH LOGIN ID OPTION
    if($_REQUEST["option"]=="datemin_max"){
        $login_id=$_REQUEST['REP_chk_loginid'];
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$login_id'");
        while($row=mysqli_fetch_array($uld_id)){
            $REP_uld_id=$row["ULD_ID"];
        }
        $admin_searchmin_date=mysqli_query($con,"SELECT MIN(ECIOD_DATE) as ECIOD_DATE FROM EMPLOYEE_CHECK_IN_OUT_DETAILS ");
        while($row=mysqli_fetch_array($admin_searchmin_date)){
            $admin_searchmin_date_value=$row["ECIOD_DATE"];
            $admin_min_date = $admin_searchmin_date_value;
        }
        $admin_searchmax_date=mysqli_query($con,"SELECT MAX(ECIOD_DATE) as ECIOD_DATE FROM EMPLOYEE_CHECK_IN_OUT_DETAILS");
        while($row=mysqli_fetch_array($admin_searchmax_date)){
            $admin_searchmax_date_value=$row["ECIOD_DATE"];
            $admin_max_date= $admin_searchmax_date_value;
        }
        $finalvalue=array($admin_min_date,$admin_max_date);
        echo JSON_ENCODE($finalvalue);
    }
    //FUNCTION FOR SHOW THE DATA FOR ALL ACTIVE EMP WITH DATE
    if($_REQUEST['option']=="ALL_ACTIVE_RANGE"){
        $alldate = $_REQUEST['date'];
        $empdate = date('Y-m-d',strtotime($alldate));
        $REP_flextbl= mysqli_query($con,"SELECT CONCAT(EMP.EMP_FIRST_NAME,' ',EMP.EMP_LAST_NAME) AS EMPLOYEE_NAME,ECIOD.ECIOD_CHECK_IN_TIME,CIORL_IN.CIORL_LOCATION AS ECIOD_CHECK_IN_LOCATION,ECIOD.ECIOD_CHECK_OUT_TIME,CIORL_OUT.CIORL_LOCATION AS ECIOD_CHECK_OUT_LOCATION FROM EMPLOYEE_CHECK_IN_OUT_DETAILS ECIOD LEFT JOIN EMPLOYEE_DETAILS EMP on EMP.ULD_ID=ECIOD.ULD_ID LEFT JOIN CLOCK_IN_OUT_REPORT_LOCATION CIORL_IN ON  ECIOD.ECIOD_CHECK_IN_LOCATION=CIORL_IN.CIORL_ID LEFT JOIN CLOCK_IN_OUT_REPORT_LOCATION CIORL_OUT ON ECIOD.ECIOD_CHECK_OUT_LOCATION=CIORL_OUT.CIORL_ID  WHERE ECIOD.ECIOD_DATE='$empdate' ORDER BY EMP_FIRST_NAME");
        $REP_values=array();
        while($row=mysqli_fetch_array($REP_flextbl)){
            $check_in_time=$row["ECIOD_CHECK_IN_TIME"];
            $check_in_location=$row["ECIOD_CHECK_IN_LOCATION"];
            $check_out_time=$row["ECIOD_CHECK_OUT_TIME"];
            $check_out_location=$row["ECIOD_CHECK_OUT_LOCATION"];
            $check_in_empname=$row["EMPLOYEE_NAME"];
            $final_values=array('check_in_empname'=>$check_in_empname,'check_in_time'=>$check_in_time,'check_in_location' =>$check_in_location,'check_out_time' =>$check_out_time,'check_out_location'=>$check_out_location);
            $REP_values[]=$final_values;
        }
        echo JSON_ENCODE($REP_values);
    }
    //FUNCTION FOR SHOW THE DATA FOR BETWEEN RANGE
    if($_REQUEST['option']=="BETWEEN_RANGE"){
        $login_id=$_REQUEST['loginid'];
        $REP_start_datevalue=$_REQUEST['startdate'];
        $REP_start_finaldatevalue = date('Y-m-d',strtotime($REP_start_datevalue));
        $REP_end_datevalue=$_REQUEST['enddate'];
        $REP_end_finaldatevalue = date('Y-m-d',strtotime($REP_end_datevalue));
        $REP_flextbl= mysqli_query($con,"SELECT DATE_FORMAT(ECIOD.ECIOD_DATE,'%d-%m-%Y') AS E_ECIOD_DATE,ECIOD.ECIOD_CHECK_IN_TIME,CIORL_IN.CIORL_LOCATION AS ECIOD_CHECK_IN_LOCATION ,ECIOD.ECIOD_CHECK_OUT_TIME,CIORL_OUT.CIORL_LOCATION AS ECIOD_CHECK_OUT_LOCATION FROM EMPLOYEE_CHECK_IN_OUT_DETAILS ECIOD LEFT JOIN CLOCK_IN_OUT_REPORT_LOCATION CIORL_IN ON  ECIOD.ECIOD_CHECK_IN_LOCATION=CIORL_IN.CIORL_ID LEFT JOIN CLOCK_IN_OUT_REPORT_LOCATION CIORL_OUT ON ECIOD.ECIOD_CHECK_OUT_LOCATION=CIORL_OUT.CIORL_ID  WHERE ECIOD.ECIOD_DATE BETWEEN '$REP_start_finaldatevalue' AND '$REP_end_finaldatevalue' AND ECIOD.ULD_ID='$login_id' ORDER BY ECIOD_DATE");
        $REP_values=array();
        while($row=mysqli_fetch_array($REP_flextbl)){
            $check_in_time=$row["ECIOD_CHECK_IN_TIME"];
            $check_in_location=$row["ECIOD_CHECK_IN_LOCATION"];
            $check_out_time=$row["ECIOD_CHECK_OUT_TIME"];
            $check_out_location=$row["ECIOD_CHECK_OUT_LOCATION"];
            $check_in_date=$row["E_ECIOD_DATE"];
            $final_values=array('check_in_date'=>$check_in_date,'check_in_time'=>$check_in_time,'check_in_location' =>$check_in_location,'check_out_time' =>$check_out_time,'check_out_location'=>$check_out_location);
            $REP_values[]=$final_values;
        }
        echo JSON_ENCODE($REP_values);
    }
    //SET  MIN ND MAX DATE FUNCTION FOR PROJECT NAME BY DATE RANGE
    if($_REQUEST['option']=="set_datemin_max")
    {
        $REP_loginid=$_REQUEST['REP_chk_loginid'];
        //SET MIN DATE
        $min_date=mysqli_query($con,"select  MIN(ECIOD_DATE) AS ECIOD_DATE from EMPLOYEE_CHECK_IN_OUT_DETAILS WHERE ULD_ID='$REP_loginid'");
        while($row=mysqli_fetch_array($min_date)){
            $mindate_array=$row["ECIOD_DATE"];
            $min_date = $mindate_array;
        }
        //SET MAX DATE
        $REV_searchmax_date=mysqli_query($con,"select  MAX(ECIOD_DATE) AS ECIOD_DATE from EMPLOYEE_CHECK_IN_OUT_DETAILS WHERE ULD_ID='$REP_loginid'");
        while($row=mysqli_fetch_array($REV_searchmax_date)){
            $REV_searchmax_date_value=$row["ECIOD_DATE"];
            $max_date= $REV_searchmax_date_value;
        }
        $minmax_date_values=array($min_date,$max_date);
        echo JSON_ENCODE($minmax_date_values);
    }
}
?>