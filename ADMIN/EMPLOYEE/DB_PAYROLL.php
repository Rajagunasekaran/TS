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

    $Earning_labels=$_POST['earninglbl'];
    $Earning_amt=$_POST['earningamt'];

    $Dedution_labels=$_POST['deductionlbl'];
    $Dedution_amt=$_POST['deductionamt'];

    $TH_query=mysqli_query($con,"SELECT * FROM VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS WHERE EMPLOYEE_NAME='$employeename'");
    while($row=mysqli_fetch_array($TH_query))
    {
        $TH_active_emp_id=$row["EMP_ID"];
    }

//FOR EARNING LABEL ARRAY
    $Earning_labels_length=count($Earning_labels);
    $Earning_lbl_id;
    for($i=0;$i<$Earning_labels_length;$i++)
    {
        if($i==0)
        {
            $Earning_lbl_id=$Earning_labels[$i];
        }
        else
        {
            $Earning_lbl_id=$Earning_lbl_id ."^".$Earning_labels[$i];
        }
    }
    $Earning_lbl_id;


//FOR EARNING AMT ARRAY
    $Earning_amt_length=count($Earning_amt);
    $Earning_amt_id;
    for($i=0;$i<$Earning_labels_length;$i++)
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

//FOR dedution LABEL ARRAY
    $Dedution_labels_length=count($Dedution_labels);
    $Dedution_lbl_id;
    for($i=0;$i<$Dedution_labels_length;$i++)
    {
        if($i==0)
        {
            $Dedution_lbl_id=$Dedution_labels[$i];
        }
        else
        {
            $Dedution_lbl_id=$Dedution_lbl_id ."^".$Dedution_labels[$i];
        }
    }
    $Dedution_lbl_id;


    //FOR dedution amt ARRAY

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
//APPLWY SP FOR INSERT PAYROLL
    $QUERY= "call SP_EMPLOYEE_PAYMENT_DETAILS_INSERT('$TH_active_emp_id','$fromdate','$todate','$paymentdate','$paymentmode','$comments','$Earning_lbl_id','$Earning_amt_id','$Dedution_lbl_id','$Dedution_amt_id','dhandapani.sattanathan@ssomens.com',@SUCCESS_FLAG)";

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

    $TH_query=mysqli_query($con,"SELECT * from VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS where URC_DATA!='SUPER ADMIN' ORDER BY EMPLOYEE_NAME");
    while($row=mysqli_fetch_array($TH_query))
    {
        $TH_active_empname[]=$row["EMPLOYEE_NAME"];
    }

    $error='1,2,3,4,17,7';
    $error_array=get_error_msg($error);

//    $errormessage=array();
//    $errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (1,2,3,4,17,7)");
//    while($row=mysqli_fetch_array($errormsg))
//    {
//        $errormessage[]=$row["EMC_DATA"];
//    }

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

    $finalvalues=array($TH_active_empname,$error_array,$paymentmode,$Earning_labels,$Dedution);
    echo JSON_ENCODE($finalvalues);

}
if($_REQUEST['option']=="pdf")
{

    $TH_query=mysqli_query($con,"SELECT EPD_FROM_PERIOD,EPD_TO_PERIOD,EPD_PAYMENT_DATE,EPD_TOTAL_AMOUNT,EPD_COMMENTS FROM EMPLOYEE_PAYMENT_DETAILS");
    while($row=mysqli_fetch_array($TH_query))
    {
        $employeepayment[]=array($row['EPD_FROM_PERIOD'],$row["EPD_TO_PERIOD"],$row['EPD_PAYMENT_DATE'],$row['EPD_TOTAL_AMOUNT'],$row['EPD_COMMENTS']);

    }
//        echo($employeepayment[0][1]);
//        exit;

    $employeepaymentdetails='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;">
    <caption style="caption-side: left;font-weight: bold;">PARTICULARS OF EMPLOYEE PAYROLL</caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/>
    <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>EMPLOYEE NAME</td><td style=width="250">'.$employeepayment[0][1].'</td>
    <td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>FORM DATE</td><td style=width="250">'.$employeepayment[0][2].' </td>
    </tr><tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>TO DATE</td><td width="250">'.$employeepayment[0][3].'</td>
    <td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>PAYMENT DATE</td><td width="250">'.$employeepayment[0][4].'</td>
    </tr><tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>COMMTENS</td><td style=width="250" colspan=3>'.$employeepayment[0][5].'</td></tr>
    </tr></table>';
//        echo($employeepaymentdetails);
//        exit;
    $reportheadername='COMPANY PROPERTIES TABLE'.$employeepaymentdetails[0][0];

    $finaltable= '<html><body><table><tr><td style="text-align: center;"><div></div></td></tr><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">'.$employeepaymentdetails.'</div></table></body></html>';
//        echo($finaltable);
//        exit;
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
//    $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">LIH MING CONSTRUCTION PTE LTD</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($finaltable);
    $reportpdf=$mpdf->Output(''.$reportheadername.'.pdf','d');
}



?>