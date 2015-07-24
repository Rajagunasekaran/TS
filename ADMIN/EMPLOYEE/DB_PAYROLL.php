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

    $TH_query=mysqli_query($con,"SELECT * FROM VW_TS_PAYSLIP_ALL_ACTIVE_EMPLOYEE_DETAILS WHERE EMPLOYEE_NAME='$employeename'");
    while($row=mysqli_fetch_array($TH_query))
    {
        $TH_active_emp_id=$row["EMP_ID"];
    }

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
    ECHO("call SP_EMPLOYEE_PAYMENT_DETAILS_INSERT('$TH_active_emp_id','$fromdate','$todate','$paymentdate','$paymentmode','$comments','$Earning_lbl_id','$Earning_amt_id','$Dedution_lbl_id','$Dedution_amt_id','dhandapani.sattanathan@ssomens.com',@SUCCESS_FLAG)");

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

    $TH_query=mysqli_query($con,"SELECT * from VW_TS_PAYSLIP_ALL_ACTIVE_EMPLOYEE_DETAILS where URC_DATA!='SUPER ADMIN' ORDER BY EMPLOYEE_NAME");
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
//echo($_REQUEST['option']=="pdf");

if($_REQUEST['option']=="pdf")
{
//        $emp_id=37;
//        $employeename=$_REQUEST['EMPNAME'];
//        $startdate=$_REQUEST['fromdate'];
//        $enddate=$_REQUEST['todate'];
//
//        $TH_query=mysqli_query($con,"SELECT * FROM VW_TS_PAYSLIP_ALL_ACTIVE_EMPLOYEE_DETAILS WHERE EMPLOYEE_NAME='$employeename'");
//        while($row=mysqli_fetch_array($TH_query))
//        {
//            $TH_active_emp_id=$row["EMP_ID"];
//        }

    $result = $con->query("CALL SP_TS_PAYSLIP_PDF(1,'2015-07-01','2015-07-31','rajalakshmi.r@ssomens.com',@TEMP_EMPLOYEE_PAYSLIP)");
    if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query('SELECT @TEMP_EMPLOYEE_PAYSLIP');
    $result = $select->fetch_assoc();
    $temp_table_name= $result['@TEMP_EMPLOYEE_PAYSLIP'];
    $select_data="SELECT * FROM $temp_table_name";
//        echo($select_data);
//        exit;

    $select_data_rs=mysqli_query($con,$select_data);
    $sitevisit_details=mysqli_num_rows($select_data_rs);

//echo($sitevisit_details);
//        exit;
    while($row=mysqli_fetch_array($select_data_rs))
    {
        $emppaymentdetails[]=array($row['EMP_ID'],$row['EMPLOYEE_NAME'],$row['EMP_DESIGNATION'],$row['EMP_EARNING'],$row['EMP_EARNING_AMOUNT'],$row['EMP_DEDUCTION'], $empdeduction_amt=$row['EMP_DEDUCTION_AMOUNT'],$emp_payment_date=$row['EMP_PAYMENT_DATE'],$emp_payment_mode=$row['EMP_PAYMENT_MODE'],$emp_payment_netpay=$row['EMP_NET_PAY'],$emp_from_date=$row['EMP_FROM_PERIOD'],$emp_to_date=$row['EMP_TO_PERIOD']);

    }
    if(count($emppaymentdetails)!=0)
    {
        $sitevisittable='<table width=1000 border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><caption align="left" style="font-weight: bold;"></caption></caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><tr style="color:white;" bgcolor="#498af3" align="center"><td height=20px align="center" style="color:white;" nowrap><b>EARNING</b></td><td height=20px align="center" style="color:white;" nowrap><b>AMOUNT</b></td><td height=20px align="center" style="color:white;" nowrap><b>DEDUCTION</b></td><td height=20px align="center" style="color:white;" nowrap><b>AMOUNT</b></td><td height=20px align="center" style="color:white;" width="250" nowrap><b>NET PAY</b></td></tr>';
        for($i=0;$i<count($emppaymentdetails);$i++)
        {
            $sitevisittable=$sitevisittable."<tr style='padding-left: 10px;'><td height=20px nowrap style='padding-left: 10px;'>".$emppaymentdetails[$i][3]."</td><td height=20px nowrap style='padding-left: 10px;'>".$emppaymentdetails[$i][4]."</td><td height=20px nowrap style='text-align:center;'>".$emppaymentdetails[$i][5]."</td><td height=20px nowrap style='text-align:center;'>".$emppaymentdetails[$i][6]."</td><td height=20px nowrap style='padding-left: 10px;'>".$emppaymentdetails[$i][9]."</td></tr>";
        }
        $sitevisittable=$sitevisittable.'</table>';
//           $finaltable=$finaltable.'<tr><td>'.$employeeinfo.'</td></tr><tr><td></td></tr><tr></tr><tr><td>'.$sitevisittable.'</td></tr>';
    }

    $finaltable='<html><body><p align=CENTER><br><b>SSOMENS</b><br>SOFTWARE DEVELOPMENT<br>MR.SOMAYA SARATHBABOU <br>DIRECTOR</center></p>NANDANAM APARTMENT<br> 66,A1 first floor,Pappammal Koil Street <br>Vaithikuppam
                     &nbsp;<br>Puducherry 605012<br>Landline No:+91 413 2333230/231<br>Mobile No:+91 9943303230<br><br><hr>
                     <p align=CENTER><H3><U>PAY SILP FOR THIS MONTH</U></H3></p></CENTER>
                     <p align=right><B>WORKING PERIOD:</B>'.$emppaymentdetails[0][10].'<B>&nbsp;&nbsp;TO&nbsp;&nbsp;</B>'.$emppaymentdetails[0][11].'<br><B>PAID DATE:</B>'.$emppaymentdetails[0][7].'<br><B>PAYMENT MODE:</B>'.$emppaymentdetails[0][8].'<BR></p>
                     <b>EMPLOYEE NAME:</b>'.$emppaymentdetails[0][1].'<BR><br><b>EMPLOYEE ID:</b>'.$emppaymentdetails[0][0].'<BR><br><b>EMPLOYEE DESIGNTION:</b>'.$emppaymentdetails[0][2].'<br><br><br><br><center>'.$sitevisittable.'</center>
                     <br><br><br><br><br><p align=center>******************COMPUTER GENERATED SLIP******************</p></body></html>';
//        echo($finaltable);

    $drop_query ="DROP TABLE $temp_table_name ";
    mysqli_query($con,$drop_query);
//        exit;
    $reportheadername='PAY SLIP'.$emppaymentdetails[0][0];
//        $finaltable='<html><body><center>SSOMENS<br>SOFTWARE DEVELOPMENT<br>MR.somaya sarathbabou</center>NANDANAM APT<br> 66 Pappammal Koil Street <br>Vaithikuppam <br> Puducherry 605012<hr><table><tr><td style="text-align: center;"><div></div></td></tr><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">' .$employeeinfo.'<tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">' .$sitevisittable.'</div></table></body></html>';
//        $finaltable=''.$empl.'<table><tr><td style="text-align: center;"><div></div></td></tr><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">' .$employeeinfo.'<tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">' .$sitevisittable.'</div></table></body></html>';
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
//      $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">LIH MING CONSTRUCTION PTE LTD</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($finaltable);
    $reportpdf=$mpdf->Output(''.$reportheadername.'.pdf','d');
}
?>