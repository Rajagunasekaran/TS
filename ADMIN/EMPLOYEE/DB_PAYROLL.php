<?php
error_reporting(0);
include "../../TSLIB/TSLIB_CONNECTION.php";
include "../../TSLIB/TSLIB_GET_USERSTAMP.php";
include "../../TSLIB/TSLIB_COMMON.php";
require_once('../../TSLIB/TSLIB_mpdf571/mpdf571/mpdf.php');
global $USERSTAMP;
global $con;
//FUNTION FOR SUBMIT BUTTON CLICK
if ($_REQUEST['Option']=="earningdedution")
{
    $employeename=$_POST['ps_emp_name'];
    $fromdate=date("Y-m-d",strtotime($_POST['PS_tb_FROM_DATE']));
    $todate=date("Y-m-d",strtotime($_POST['PS_tb_TO_DATE']));
    $paymentdate=date("Y-m-d",strtotime($_POST['PS_tb_PAYMENT_DATE']));
    $paymentmode=$_POST['payment_mode_lb'];
    $comments=$_POST['PS_tb_COMMENTS'];

    $Earning_label=$_REQUEST['Earrleng'];
    $Dedution_label=$_REQUEST['Darrleng'];
    $other_recove_lbl=$_REQUEST['otharrleng'];

//    echo($Earning_label);
//    echo($Dedution_label);
//    echo($other_recove_lbl);
//    exit;

    $Earning_amt=$_POST['earningamt'];
    $Dedution_amt=$_POST['deductionamt'];
    $otherrecover_amt=$_POST['other_recoveramt'];

//    print_r($Earning_amt);
////    print_r($Dedution_amt);
////    print_r($otherrecover_amt);
//    exit;

    $TH_query=mysqli_query($con,"SELECT * FROM VW_TS_PAYSLIP_ALL_ACTIVE_EMPLOYEE_DETAILS WHERE EMPLOYEE_NAME='$employeename'");
    while($row=mysqli_fetch_array($TH_query))
    {
        $TH_active_emp_id=$row["EMP_ID"];
    }


//FOR EARNING AMT ARRAY
    $Earning_amt_length=count($Earning_amt);
    $Earning_amt_id;
    for($i=0;$i<$Earning_amt_length;$i++)
    {
        if($i==0)
        {
            $Earning_amt_id=$Earning_amt[$i];
        }
        else
        {
            $Earning_amt_id=$Earning_amt_id ."^".$Earning_amt[$i];
        }
    }
    $Earning_amt_id;

//    echo("array".$Earning_amt_id);

    $Dedution_amt_length=count($Dedution_amt);
    $Dedution_amt_id;
    for($i=0;$i<$Dedution_amt_length;$i++)
    {
        if($i==0)
        {
            $Dedution_amt_id=$Dedution_amt[$i];
        }
        else
        {
            $Dedution_amt_id=$Dedution_amt_id ."^".$Dedution_amt[$i];
        }
    }
    $Dedution_amt_id;

    $other_recovery_amt_length=count($otherrecover_amt);
    $other_recovery_amt_id;
    for($i=0;$i<$other_recovery_amt_length;$i++)
    {
        if($i==0)
        {
            $other_recovery_amt_id=$otherrecover_amt[$i];
        }
        else
        {
            $other_recovery_amt_id=$other_recovery_amt_id ."^".$otherrecover_amt[$i];
        }
    }
    $other_recovery_amt_id;

//    echo("sp id".$TH_active_emp_id);
//        exit;
//APPLWY SP FOR INSERT PAYROLL
//    echo("call SP_EMPLOYEE_PAYMENT_DETAILS_INSERT('$TH_active_emp_id','$fromdate','$todate','$paymentdate','$paymentmode','$comments','$Earning_label','$Earning_amt_id','$Dedution_label','$Dedution_amt_id','$other_recove_lbl','$other_recovery_amt_id','dhandapani.sattanathan@ssomens.com',@SUCCESS_FLAG)");
    $QUERY= "call SP_EMPLOYEE_PAYMENT_DETAILS_INSERT('$TH_active_emp_id','$fromdate','$todate','$paymentdate','$paymentmode','$comments','$Earning_lbl_id','$Earning_amt_id','$Dedution_lbl_id','$Dedution_amt_id','$other_recover_lbl','$otherrecover_amt','dhandapani.sattanathan@ssomens.com',@SUCCESS_FLAG)";
    $result = $con->query($QUERY);
    if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query('SELECT @SUCCESS_FLAG');
    $result = $select->fetch_assoc();
    $flag= $result['@SUCCESS_FLAG'];
    echo json_encode($flag);
}
//FUNTION FOR INITIAL LOAD
if($_REQUEST['Option']=="initial_data")
{
    $TH_active_emp=get_active_emp_id();
    $TH_active_empname=array();

    $TH_query=mysqli_query($con,"SELECT * from VW_TS_PAYSLIP_ALL_ACTIVE_EMPLOYEE_DETAILS where URC_DATA!='SUPER ADMIN' ORDER BY EMPLOYEE_NAME");
    while($row=mysqli_fetch_array($TH_query))
    {
        $TH_active_empname[]=$row["EMPLOYEE_NAME"];
    }
    $error='1,2,3,4,17,7';
    $error_array=get_error_msg($error);

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

    $otherrecover=array();
    $TH_query=mysqli_query($con,"SELECT EORL_RECOVERY_LABELS FROM EMPLOYEE_OTHER_RECOVERIES_LABELS");
    while($row=mysqli_fetch_array($TH_query))
    {
        $otherrecover[]=$row["EORL_RECOVERY_LABELS"];
    }
    $finalvalues=array($TH_active_empname,$error_array,$paymentmode,$Earning_labels,$Dedution,$otherrecover);
    echo JSON_ENCODE($finalvalues);

}
?>