<?php
error_reporting(0);
include "../../TSLIB/TSLIB_CONNECTION.php";
include "../../TSLIB/TSLIB_GET_USERSTAMP.php";
include "../../TSLIB/TSLIB_COMMON.php";

$USERSTAMP=$UserStamp;
global $con;

if($_REQUEST['Option']=="initial_data")
{
    $TH_active_emp=get_active_emp_id();
    $TH_active_empname=array();
    $TH_query=mysqli_query($con,"SELECT EMPLOYEE_NAME FROM VW_TS_ALL_EMPLOYEE_DETAILS ORDER BY EMPLOYEE_NAME ASC");
    while($row=mysqli_fetch_array($TH_query))
    {
        $TH_active_empname[]=$row["EMPLOYEE_NAME"];
    }
    $errormessage=array();
    $errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (73,74,75)");
    while($row=mysqli_fetch_array($errormsg))
    {
        $errormessage[]=$row["EMC_DATA"];
    }

    $paymentmode=array();
    $TH_query=mysqli_query($con,"SELECT EPM_PAYMENT_MODE FROM EMPLOYEE_PAYMENT_MODE");
    while($row=mysqli_fetch_array($TH_query))
    {
        $paymentmode[]=$row["EPM_PAYMENT_MODE"];
    }
    $Earning_labels=array();

    $TH_query=mysqli_query($con,"SELECT EEL_EARNING_LABELS FROM EMPLOYEE_PAYMENT_EARNING_LABELS ORDER BY EEL_EARNING_LABELS ASC");
    while($row=mysqli_fetch_array($TH_query))
    {
        $Earning_labels[]=$row["EEL_EARNING_LABELS"];
    }
    $Dedution=array();
    $TH_query=mysqli_query($con,"SELECT EPDL_DEDUCTION_LABELS FROM EMPLOYEE_PAYMENT_DEDUCTION_LABELS");
    while($row=mysqli_fetch_array($TH_query))
    {
        $Dedution[]=$row["EPDL_DEDUCTION_LABELS"];
    }

    $finalvalues=array($TH_active_empname,$errormessage,$paymentmode,$Earning_labels,$Dedution);
    echo JSON_ENCODE($finalvalues);
}

?>