<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************CHECK IN/OUT DETAILS MAIL TRIGGER *************************************//
//DONE BY:LALITHA
//VER 0.03-SD:25/02/2015 ED:25/02/2015, TRACKER NO:74,DESC:updated display name
//DONE BY:RAJA
//VER 0.02-SD:08/01/2015 ED:08/01/2015, TRACKER NO:175,DESC:CHANGED LOGIN ID AS EMPLOYEE NAME, DISPLAYED REPORT LOCATION INSTEAD OF LOCATION ID
//DONE BY:RAJA
//VER 0.01-INITIAL VERSION, SD:30/12/2014 ED:30/12/2014,TRACKER NO:74
//*********************************************************************************************************//-->
<?php
//set_include_path( get_include_path() . PATH_SEPARATOR . 'google-api-php-client-master/src' );
//require_once 'google-api-php-client-master/src/Google/Client.php';
//require_once 'google-api-php-client-master/src/Google/Service/Drive.php';
require_once('../TSLIB/TSLIB_mpdf571/mpdf571/mpdf.php');
require_once 'google/appengine/api/mail/Message.php';
use google\appengine\api\mail\Message;
include "../TSLIB/TSLIB_COMMON_FUNCTIONS.php";
include "../TSLIB/TSLIB_CONNECTION.php";
//include "CONFIG.php";
$month_sdate = date('Y-m-d',strtotime('first day of this month'));
$current_date=date('Y-m-d');
if($month_sdate==$current_date){
    $select_admin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='ADMIN'";
    $select_sadmin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='SUPER ADMIN'";
    $admin_rs=mysqli_query($con,$select_admin);
    $sadmin_rs=mysqli_query($con,$select_sadmin);
    $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=13";
    $select_template_rs=mysqli_query($con,$select_template);
    if($row=mysqli_fetch_array($select_template_rs)){
        $mail_subject=$row["ETD_EMAIL_SUBJECT"];
        $body=$row["ETD_EMAIL_BODY"];
    }

    $get_displayname=get_displayname();

    if($row=mysqli_fetch_array($admin_rs)){
        $admin=$row["ULD_LOGINID"];//get admin
    }
    if($row=mysqli_fetch_array($sadmin_rs)){
        $sadmin=$row["ULD_LOGINID"];//get super admin
    }
    $month_year=date('F-Y',strtotime("-1 month"));
    $sdate=date('Y-m-d',strtotime("first day of last month"));
    $edate=date('Y-m-d',strtotime("last day of last month"));
    $admin_name = substr($admin, 0, strpos($admin, '.'));
    $sadmin_name = substr($sadmin, 0, strpos($sadmin, '.'));
    $spladminname=$admin_name.'/'.$sadmin_name;
    $spladminname=strtoupper($spladminname);
    $sub=str_replace("[SADMIN]","$spladminname",$body);
    $sub=str_replace("[MONTH]",$month_year,$sub);
    $mail_subject=str_replace("[MONTH]",$month_year,$mail_subject);
    $query="SELECT D.EMPLOYEE_NAME, DATE_FORMAT(A.ECIOD_DATE,'%d-%m-%Y,%a') AS ECIOD_DATE,A.ECIOD_CHECK_IN_TIME,C.CIORL_LOCATION,A.ECIOD_CHECK_OUT_TIME,E.CIORL_LOCATION
            FROM EMPLOYEE_CHECK_IN_OUT_DETAILS A LEFT JOIN CLOCK_IN_OUT_REPORT_LOCATION C ON A.ECIOD_CHECK_IN_LOCATION=C.CIORL_ID LEFT JOIN CLOCK_IN_OUT_REPORT_LOCATION E ON A.ECIOD_CHECK_OUT_LOCATION=E.CIORL_ID
            INNER JOIN USER_LOGIN_DETAILS B ON A.ULD_ID = B.ULD_ID INNER JOIN VW_TS_ALL_EMPLOYEE_DETAILS D ON B.ULD_ID = D.ULD_ID WHERE A.ECIOD_DATE BETWEEN '$sdate' AND '$edate' ORDER BY D.EMPLOYEE_NAME";
    $sql=mysqli_query($con,$query);
    $row=mysqli_num_rows($sql);
    $x=$row;
    $message='<html><body>'.'<br>'.'<b>'.$mail_subject.'</b></h><br>'.'<br><br>'.'<table width=1300 colspan="5px" cellpadding="2px" ><tr style="color:white;" bgcolor="#6495ed" align="center" height=2px >'
        . '<td align="center" width=250 nowrap style="border: 1px solid black;color:white;"><b>EMPLOYEE NAME</b></td>'
        . '<td align="center" width=110 nowrap style="border: 1px solid black;color:white;"><b>DATE</b></td>'
        . '<td align="center" width=100 nowrap style="border: 1px solid black;color:white;"><b>CLOCK IN</b></td>'
        . '<td align="center" width=120 nowrap style="border: 1px solid black;color:white;"><b>CLOCK IN LOCATION</b></td>'
        . '<td align="center" width=100 nowrap style="border: 1px solid black;color:white;"><b>CLOCK OUT</b></td>'
        . '<td align="center" width=120 nowrap style="border: 1px solid black;color:white;"><b>CLOCK OUT LOCATION</b></td></tr>';
    while($row=mysqli_fetch_array($sql)){
        $username=$row['EMPLOYEE_NAME'];
        $checkindate=$row['ECIOD_DATE'];
        $checkintime=$row['ECIOD_CHECK_IN_TIME'];
        $checkinlocation=$row['CIORL_LOCATION'];
        $checkouttime=$row['ECIOD_CHECK_OUT_TIME'];
        $checkoutlocation=$row['CIORL_LOCATION'];

        $message=$message. "<tr style='border: 1px solid black;'><td align='center' nowrap style='border: 1px solid black;'>".$username."</td><td align='center' style='border: 1px solid black;'>".$checkindate."</td><td align='center' style='border: 1px solid black;'>".$checkintime."</td><td style='border: 1px solid black;'>".$checkinlocation."</td><td align='center' style='border: 1px solid black;'>".$checkouttime."</td><td style='border: 1px solid black;'>".$checkoutlocation."</td></tr>";
    }
    $message=$message."</table></body></html>";
    ob_clean();
    $mpdf = new mPDF('utf-8', 'Folio-L');
    $mpdf->WriteHTML($message);
    $outputpdf=$mpdf->Output('CLOCK IN/OUT DETAILS ' .$month_year. '.pdf','S');
    ob_end_clean();
    $FILENAME='CLOCK IN/OUT DETAILS ' .$month_year. '.pdf';
    //SENDING MAIL OPTIONS
    $name = $get_displayname;
    $from = $admin;
    $message1 = new Message();
    $message1->setSender($name.'<'.$from.'>');
    $message1->addTo($admin);
    $message1->addCc($sadmin);
    $message1->setSubject($mail_subject);
    $message1->setHtmlBody($sub);
    $message1->addAttachment($FILENAME,$outputpdf);
    $message1->send();

}