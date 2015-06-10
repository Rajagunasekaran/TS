<?php
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
        $CLK_errmsg=get_error_msg("15,18,83,100,101");
// REPORT CONFIGURATION LIST
        $CLK_report_config = mysqli_query($con,"SELECT * FROM REPORT_CONFIGURATION WHERE CGN_ID=19");
        $CLK_rprtconfiglist=array();
        while($row=mysqli_fetch_array($CLK_report_config)){
            $CLK_rprtconfiglist[]=array($row["RC_DATA"],$row["RC_ID"]);
        }
// ACTIVE EMPLOYEE LIST
        $CLK_active_emp=get_active_emp_id();
// NONACTIVE EMPLOYEE LIST
        $CLK_active_nonemp=get_nonactive_emp_id();
        $CLK_final_values=array($CLK_rprtconfiglist,$CLK_active_emp,$CLK_active_nonemp,$CLK_errmsg);
        echo JSON_ENCODE($CLK_final_values);
    }
//SETTING MIN ND MAX DATE FOR DATE PICKER WITH LOGIN ID OPTION
    if($_REQUEST["option"]=="minmax_dtewth_loginid"){
        $login_id=$_REQUEST['CLK_loginid'];
        $admin_searchmin_date=mysqli_query($con,"SELECT MIN(ECIOD_DATE) as ECIOD_DATE FROM EMPLOYEE_CHECK_IN_OUT_DETAILS where ULD_ID='$login_id' ");
        while($row=mysqli_fetch_array($admin_searchmin_date)){
            $admin_searchmin_date_value=$row["ECIOD_DATE"];
            $admin_min_date = $admin_searchmin_date_value;
        }
        $admin_searchmax_date=mysqli_query($con,"SELECT MAX(ECIOD_DATE) as ECIOD_DATE FROM EMPLOYEE_CHECK_IN_OUT_DETAILS where ULD_ID='$login_id' ");
        while($row=mysqli_fetch_array($admin_searchmax_date)){
            $admin_searchmax_date_value=$row["ECIOD_DATE"];
            $admin_max_date= $admin_searchmax_date_value;
        }

        $finalvalue=array($admin_min_date,$admin_max_date);
        echo JSON_ENCODE($finalvalue);
    }
    //SETTING MIN ND MAX DATE FOR DATE PICKER WITH BANDWIDTH BY MONTH OPTION
    if($_REQUEST["option"]=="minmax_dtewth_monthyr"){
        $admin_searchmin_date=mysqli_query($con,"SELECT MIN(ECIOD_DATE) as ECIOD_DATE FROM EMPLOYEE_CHECK_IN_OUT_DETAILS ORDER BY ECIOD_DATE ");
        while($row=mysqli_fetch_array($admin_searchmin_date)){
            $admin_searchmin_date_value=$row["ECIOD_DATE"];
            $admin_min_date = $admin_searchmin_date_value;
        }
        $admin_searchmax_date=mysqli_query($con,"SELECT MAX(ECIOD_DATE) as ECIOD_DATE FROM EMPLOYEE_CHECK_IN_OUT_DETAILS ORDER BY ECIOD_DATE ");
        while($row=mysqli_fetch_array($admin_searchmax_date)){
            $admin_searchmax_date_value=$row["ECIOD_DATE"];
            $admin_max_date= $admin_searchmax_date_value;
        }
        $finalvalue=array($admin_min_date,$admin_max_date);
        echo JSON_ENCODE($finalvalue);
    }
    //FETCHING DATA TABLE FRM DB FOR ACTIVE ND NON ACTIVE EMP BANDWIDTH DETAILS
    if($_REQUEST['option']=="CLK_loginid_searchoption")
    {
        $CLK_loginid=$_REQUEST['CLK_loginid'];
        $CLK_mnthyrval=$_REQUEST['CLK_monthyear'];
//        echo $CLK_mnthyrval;
//        $CLK_start_value = date('Y-m-d',strtotime($CLK_mnthyrval));
        $final_start=date('Y-m-01', strtotime($CLK_mnthyrval));
//        echo $final_start;
        $final_end=date('Y-m-t', strtotime($CLK_mnthyrval));
//        echo $final_end;
//        echo "SELECT COUNT(*) AS CLOCK_OUTMISSED  FROM EMPLOYEE_CHECK_IN_OUT_DETAILS WHERE  ECIOD_DATE BETWEEN '$final_start' AND '$final_end' AND ECIOD_CHECK_OUT_LOCATION IS NULL AND ULD_ID='$CLK_loginid'";
        $CLK_flextbl= mysqli_query($con,"SELECT DATE_FORMAT(ECIOD_DATE,'%d-%m-%Y') AS ECIOD_DATE FROM EMPLOYEE_CHECK_IN_OUT_DETAILS WHERE  ECIOD_DATE BETWEEN '$final_start' AND '$final_end' AND ECIOD_FLAG='X' AND ULD_ID='$CLK_loginid'");
        $CLK_COUNT= mysqli_query($con,"SELECT COUNT(*) AS CLOCK_OUTMISSED FROM EMPLOYEE_CHECK_IN_OUT_DETAILS WHERE  ECIOD_DATE BETWEEN '$final_start' AND '$final_end' AND ECIOD_FLAG='X' AND ULD_ID='$CLK_loginid'");

        $CLK_values=array();
        $ET_count_values=array();
        while($row=mysqli_fetch_array($CLK_flextbl)){
            $CLK_count=$row["ECIOD_DATE"];
            $final_values=(object) ['date'=>$CLK_count];
            $CLK_values[]=$final_values;
        }
        while($row=mysqli_fetch_array($CLK_COUNT)){
            $CLK_missedcount=$row["CLOCK_OUTMISSED"];
            $final_values_count=(object) ['count'=>$CLK_missedcount];
            $ET_count_values[]=$final_values_count;
        }
        $variable=array($CLK_values,$ET_count_values);
        echo JSON_ENCODE($variable);
    }
    //FETCHING DATA TABLE FRM DB FOR MONTH ND YEAR BW RECORDS
    if($_REQUEST['option']=="CLK_monthyear_searchoption")
    {
        $date=$_REQUEST["CLK_db_selectmnth"];
        $result = $con->query("CALL SP_TS_REPORT_COUNT_CLOCKOUT_MISSED('$date','$UserStamp',@TEMP_USER_ABSENT_COUNT)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @TEMP_USER_ABSENT_COUNT');
        $result = $select->fetch_assoc();
        $temp_table_name= $result['@TEMP_USER_ABSENT_COUNT'];
        $select_data="select * from $temp_table_name order by UNAME ";
        $select_data_rs=mysqli_query($con,$select_data);
        $row=mysqli_num_rows($select_data_rs);
        $x=$row;
        $values_array=array();
        while($row=mysqli_fetch_array($select_data_rs)){
            $name=$row['UNAME'];
            $count_value=$row['CLOCKOUT_MISSED_COUNT'];
            $final_values=array('name'=>$name,'absent_count' => $count_value);
            $values_array[]=$final_values;
        }
        $drop_query="DROP TABLE $temp_table_name ";
        mysqli_query($con,$drop_query);
        echo   JSON_ENCODE($values_array);
    }
}
?>