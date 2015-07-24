
<?php

include "../../TSLIB/TSLIB_CONNECTION.php";
include "../../TSLIB/TSLIB_GET_USERSTAMP.php";
include "../../TSLIB/TSLIB_COMMON.php";
require_once('../../TSLIB/TSLIB_mpdf571/mpdf571/mpdf.php');
global $USERSTAMP;

if($_REQUEST['option']=="pdf")
{

    $result = $con->query("CALL SP_TS_PAYSLIP_PDF(1,'2015-07-01','2015-07-31','rajalakshmi.r@ssomens.com',@TEMP_EMPLOYEE_PAYSLIP)");
    if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query('SELECT @TEMP_EMPLOYEE_PAYSLIP');
    $result = $select->fetch_assoc();
    $temp_table_name= $result['@TEMP_EMPLOYEE_PAYSLIP'];

    $select_data="SELECT * FROM $temp_table_name";
    $select_data_rs=mysqli_query($con,$select_data);
    $sitevisit_details=mysqli_num_rows($select_data_rs);

    while($row=mysqli_fetch_array($select_data_rs))
    {
        $emppaymentdetails[]=array($row['EMP_ID'],$row['EMPLOYEE_NAME'],$row['EMP_DESIGNATION'],$row['EMP_EARNING'],$row['EMP_EARNING_AMOUNT'],$row['EMP_DEDUCTION'], $empdeduction_amt=$row['EMP_DEDUCTION_AMOUNT'],$emp_payment_date=$row['EMP_PAYMENT_DATE'],$emp_payment_mode=$row['EMP_PAYMENT_MODE'],$emp_payment_netpay=$row['EMP_NET_PAY']);

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

    $finaltable='<html><body><p align=CENTER><br>SSOMENS<br>SOFTWARE DEVELOPMENT<br>MR.SOMAYA SARATHBABOU <br>DIRECTOR</center></p>NANDANAM APARTMENT<br> 66,A1 first floor,Pappammal Koil Street <br>Vaithikuppam
                     &nbsp;<br>Puducherry 605012<br>Landline No:+91 413 2333230/231<br>Mobile No:+91 9943303230<br><br><hr>
                     <center><H3><U>PAY SILP FOR THIS MONTH</U></H3</center>
                     <p align=right><B>WORKING PERIOD:</B><br><B>PAID DATE:</B>'.$emppaymentdetails[0][7].'<br><B>PAYMENT MODE:</B>'.$emppaymentdetails[0][8].'<BR></p>
                     <b>EMPLOYEE NAME:</b>'.$emppaymentdetails[0][1].'<BR><br><b>EMPLOYEE ID:</b>'.$emppaymentdetails[0][0].'<BR><br><b>EMPLOYEE DESIGNTION:</b>'.$emppaymentdetails[0][2].'<br><br><br><br><center>'.$sitevisittable.'</center>
                     <br><br><br><br><br><p align=center>******************COMPUTER GENERATED SLIP******************</p></body></html>';
//    echo($finaltable);
    $drop_query ="DROP TABLE $temp_table_name ";
    mysqli_query($con,$drop_query);
//    exit;
    $reportheadername='PAYSLIP'.$emppaymentdetails[0][0];
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
//  $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">LIH MING CONSTRUCTION PTE LTD</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($finaltable);
    $reportpdf=$mpdf->Output(''.$reportheadername.'.pdf','d');
}
?>