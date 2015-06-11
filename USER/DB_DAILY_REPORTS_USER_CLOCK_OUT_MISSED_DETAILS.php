<?php
error_reporting(0);
if(isset($_REQUEST)){
    include "../TSLIB/TSLIB_CONNECTION.php";
    include "../TSLIB/TSLIB_COMMON.php";
    include "../TSLIB/TSLIB_GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;

    $USERSTAMP=$UserStamp;

//    $user_uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
    $user_uld_id=mysqli_query($con,"select ULD.ULD_ID,CONCAT(ED.EMP_FIRST_NAME,' ',ED.EMP_LAST_NAME)AS USER_NAME from USER_LOGIN_DETAILS ULD, EMPLOYEE_DETAILS ED where ULD.ULD_ID=ED.ULD_ID AND ULD.ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($user_uld_id)){
        $uld_id=$row["ULD_ID"];
        $uld_name=$row["USER_NAME"];
    }
    if($_REQUEST['option']=="common")
    {
// GET ERR MSG
        $CLK_errmsg=get_error_msg("15,18,83,100,101");
        echo JSON_ENCODE($CLK_errmsg);
    }
//SETTING MIN ND MAX DATE FOR DATE PICKER WITH LOGIN ID OPTION
if($_REQUEST["option"]=="minmax_dtewth_loginid"){
//$login_id=$_REQUEST['CLK_loginid'];
$admin_searchmin_date=mysqli_query($con,"SELECT MIN(ECIOD_DATE) as ECIOD_DATE FROM EMPLOYEE_CHECK_IN_OUT_DETAILS where ULD_ID='$uld_id' ");
while($row=mysqli_fetch_array($admin_searchmin_date)){
$admin_searchmin_date_value=$row["ECIOD_DATE"];
$admin_min_date = $admin_searchmin_date_value;
}
$admin_searchmax_date=mysqli_query($con,"SELECT MAX(ECIOD_DATE) as ECIOD_DATE FROM EMPLOYEE_CHECK_IN_OUT_DETAILS where ULD_ID='$uld_id' ");
while($row=mysqli_fetch_array($admin_searchmax_date)){
$admin_searchmax_date_value=$row["ECIOD_DATE"];
$admin_max_date= $admin_searchmax_date_value;
}

$finalvalue=array($admin_min_date,$admin_max_date,$uld_id,$uld_name);
//   echo($finalvalue);
echo JSON_ENCODE($finalvalue);
}
    if($_REQUEST['option']=="CLK_loginid_searchoption")
    {
//        $CLK_loginid=$_REQUEST['CLK_loginid'];
        $CLK_mnthyrval=$_REQUEST['CLK_monthyear'];
//        echo $CLK_mnthyrval;
//        $CLK_start_value = date('Y-m-d',strtotime($CLK_mnthyrval));
        $final_start=date('Y-m-01', strtotime($CLK_mnthyrval));
//        echo $final_start;
        $final_end=date('Y-m-t', strtotime($CLK_mnthyrval));
//        echo $final_end;
//        echo "SELECT COUNT(*) AS CLOCK_OUTMISSED  FROM EMPLOYEE_CHECK_IN_OUT_DETAILS WHERE  ECIOD_DATE BETWEEN '$final_start' AND '$final_end' AND ECIOD_CHECK_OUT_LOCATION IS NULL AND ULD_ID='$CLK_loginid'";
        $CLK_flextbl= mysqli_query($con,"SELECT DATE_FORMAT(ECIOD_DATE,'%d-%m-%Y') AS ECIOD_DATE FROM EMPLOYEE_CHECK_IN_OUT_DETAILS WHERE  ECIOD_DATE BETWEEN '$final_start' AND '$final_end' AND ECIOD_FLAG='X' AND ULD_ID='$uld_id'");
        $CLK_COUNT= mysqli_query($con,"SELECT COUNT(*) AS CLOCK_OUTMISSED FROM EMPLOYEE_CHECK_IN_OUT_DETAILS WHERE  ECIOD_DATE BETWEEN '$final_start' AND '$final_end' AND ECIOD_FLAG='X' AND ULD_ID='$uld_id'");

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

}