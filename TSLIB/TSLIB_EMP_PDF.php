<?php
require_once('../TSLIB/TSLIB_mpdf571/mpdf571/mpdf.php');
include "../TSLIB/TSLIB_CONNECTION.php";
include "../TSLIB/TSLIB_GET_USERSTAMP.php";
$USERSTAMP=$UserStamp;

if($_REQUEST['option']=='company_datatable')
{

    $EMP_loginid=$_REQUEST['login_id'];
    $cmp_details=mysqli_query($con,"SELECT CONCAT(EMP.EMP_FIRST_NAME,' ',EMP.EMP_LAST_NAME) AS EMPLOYEE_NAME,DATE_FORMAT(EMP.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,ED.ED_DESIGNATION,EMP.EMP_MOBILE_NUMBER,EMP.EMP_NEXT_KIN_NAME,URC.URC_DATA AS EMP_RELATIONHOOD,EMP.EMP_ALT_MOBILE_NO,EMP.EMP_HOUSE_NO,EMP.EMP_STREET_NAME,EMP.EMP_AREA,EMP.EMP_PIN_CODE,EMP_AADHAAR_NO,EMP.EMP_PASSPORT_NO,EMP.EMP_VOTER_ID,EMP.EMP_COMMENTS,ULD.ULD_USERSTAMP,EMP.EMP_ACCOUNT_NAME,EMP.EMP_ACCOUNT_NO,EMP.EMP_BANK_NAME,EMP.EMP_BRANCH_NAME,URC1.URC_DATA AS EMP_ACCOUNT_TYPE,EMP.EMP_IFSC_CODE,EMP.EMP_BRANCH_ADDRESS,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET,DATE_FORMAT(CONVERT_TZ(EMP.EMP_TIMESTAMP,'+00:00','+05:30'),'%d-%m-%Y %T') AS EMP_TIMESTAMP FROM EMPLOYEE_DETAILS EMP,EMPLOYEE_DESIGNATION ED,USER_RIGHTS_CONFIGURATION URC,USER_LOGIN_DETAILS ULD,USER_ACCESS UA,USER_RIGHTS_CONFIGURATION URC1,COMPANY_PROPERTIES CP,COMPANY_PROPERTIES_DETAILS CPD WHERE ED.ED_ID=EMP.EMP_DESIGNATION AND EMP.EMP_RELATIONHOOD=URC.URC_ID AND URC.CGN_ID=22 AND EMP.ULD_ID=UA.ULD_ID AND ULD.ULD_ID=UA.ULD_ID AND EMP.EMP_ACCOUNT_TYPE=URC1.URC_ID AND URC1.CGN_ID=21 AND CP.CP_ID=CPD.CP_ID AND EMP.EMP_ID=CPD.EMP_ID AND EMP.ULD_ID=$EMP_loginid AND UA.UA_TERMINATE IS NULL ORDER BY EMPLOYEE_NAME ASC");
    while($row=mysqli_fetch_array($cmp_details)){
        $cmpdetails[]=array($row['EMPLOYEE_NAME'],$row['CP_LAPTOP_NUMBER'],$row['CP_CHARGER_NUMBER'],$row['CP_BATTERY_SERIAL_NUMBER'],$row['CP_LAPTOP_BAG_NUMBER'],$row['CP_MOUSE_NUMBER'],$row['CPD_DOOR_ACCESS'],$row['CPD_ID_CARD'],$row['CPD_HEADSET'],$row['EMP_ACCOUNT_NAME'],$row['EMP_ACCOUNT_NO'],$row['EMP_BANK_NAME'],$row['EMP_BRANCH_NAME'],$row['EMP_ACCOUNT_TYPE'],$row['EMP_IFSC_CODE'],$row['EMP_BRANCH_ADDRESS'],$row['EMP_DOB'],$row['ED_DESIGNATION'],$row['EMP_MOBILE_NUMBER'],$row['EMP_NEXT_KIN_NAME'],$row['EMP_RELATIONHOOD'],$row['EMP_ALT_MOBILE_NO'],$row['EMP_HOUSE_NO'],$row['EMP_STREET_NAME'],$row['EMP_AREA'],$row['EMP_PIN_CODE'],$row['EMP_AADHAAR_NO'],$row['EMP_PASSPORT_NO'],$row['EMP_VOTER_NO'],$row['EMP_COMMENTS'],$row['ULD_USERSTAMP'],$row['EMP_TIMESTAMP']);

    }
    $cmp_properties='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;">
         <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>EMPLOYEE NAME</td><td style=width="250">'.$cmpdetails[0][0].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LAPTOP NO</td><td style=width="250">'.$cmpdetails[0][1].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>CHARGER NO</td><td style=width="250">'.$cmpdetails[0][2].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>BATTERY NO</td><td style=width="250">'.$cmpdetails[0][3].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LAPTOP BAG NO </td><td style=width="250">'.$cmpdetails[0][4].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>MOUSE NO</td><td style=width="250">'.$cmpdetails[0][5].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DOOR ACCESS</td><td style=width="250">'.$cmpdetails[0][6].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ID CARD</td><td style=width="250">'.$cmpdetails[0][7].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>HEAD SET</td><td style=width="250">'.$cmpdetails[0][8].'</td></tr>
         </table>';

    $bank_details='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;">
         <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>EMPLOYEE NAME</td><td style=width="250">'.$cmpdetails[0][0].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ACCOUNT NAME</td><td style=width="250">'.$cmpdetails[0][9].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ACCOUNT NUMBER</td><td style=width="250">'.$cmpdetails[0][10].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>BANK NAME</td><td style=width="250">'.$cmpdetails[0][11].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>BRANCH NAME</td><td style=width="250">'.$cmpdetails[0][12].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ACCOUNT TYPE</td><td style=width="250">'.$cmpdetails[0][13].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>IFDC CODE</td><td style=width="250">'.$cmpdetails[0][14].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>BRANCH ADDRESS</td><td style=width="250">'.$cmpdetails[0][15].'</td></tr>
         </table>';
    $personal_details='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;">
         <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>EMPLOYEE NAME</td><td style=width="250">'.$cmpdetails[0][0].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DOB</td><td style=width="250">'.$cmpdetails[0][16].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DESIGNATION</td><td style=width="250">'.$cmpdetails[0][17].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>MOBILE NO</td><td style=width="250">'.$cmpdetails[0][18].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>KIN NAME</td><td style=width="250">'.$cmpdetails[0][19].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>RELATIONHOOD</td><td style=width="250">'.$cmpdetails[0][20].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ALTER MOBILE NO</td><td style=width="250">'.$cmpdetails[0][21].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>HOUSE NO</td><td style=width="250">'.$cmpdetails[0][22].'</td></tr>
         <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>STREET NAME</td><td style=width="250">'.$cmpdetails[0][23].'</td></tr>
          <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>AREA</td><td style=width="250">'.$cmpdetails[0][24].'</td></tr>
           <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>PIN CODE</td><td style=width="250">'.$cmpdetails[0][25].'</td></tr>
            <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>AADHAAR NO</td><td style=width="250">'.$cmpdetails[0][26].'</td></tr>
             <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>PASSPORT NO</td><td style=width="250">'.$cmpdetails[0][27].'</td></tr>
              <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>VOTER ID</td><td style=width="250">'.$cmpdetails[0][28].'</td></tr>
              <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>COMMENTS</td><td style=width="250">'.$cmpdetails[0][29].'</td></tr>
              <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>USERSTAMP</td><td style=width="250">'.$cmpdetails[0][30].'</td></tr>
              <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>TIMESTAMP</td><td style=width="250">'.$cmpdetails[0][31].'</td></tr>

         </table>';

    $reportheadername='COMPANY PROPERTIES TABLE'.$cmp_properties[0][0];
    $finaltable= '<html><body><table><tr><td style="text-align: center;"><div></div></td></tr><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">' .$cmp_properties.'</div></h2></td></tr><br><br><br><tr><td>'.$bank_details.'</td></tr><br><br><br><tr><td>'.$personal_details.'</td></tr><br><br><br></table></body></html>';
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
    $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">COMPANY PROPERTIES DETAILS</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($finaltable);
    $reportpdf=$mpdf->Output(''.$reportheadername.'.pdf','d');
}
if($_REQUEST['option']=='company_allemp')
{
    $cmp_all=mysqli_query($con,"SELECT CONCAT(EMP.EMP_FIRST_NAME,' ',EMP.EMP_LAST_NAME) AS EMPLOYEE_NAME,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET FROM COMPANY_PROPERTIES CP,COMPANY_PROPERTIES_DETAILS CPD,EMPLOYEE_DETAILS EMP,USER_ACCESS UA WHERE CP.CP_ID=CPD.CP_ID  AND EMP.EMP_ID=CPD.EMP_ID AND UA.ULD_ID=EMP.ULD_ID AND UA.UA_TERMINATE IS NULL ORDER BY EMPLOYEE_NAME ASC");
    $cmpdetailall='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;"></caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>EMPLOYEE NAME</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LAPTOP NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>CHARGER NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>BATTERY NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LAPTOP BAG NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>MOUSE NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>DOOR ACCESS</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>ID CARD</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>HEAD SET</b></td></tr></th>';
    while($row=mysqli_fetch_array($cmp_all)){

        $cmpdetailall .="<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$row['EMPLOYEE_NAME']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_LAPTOP_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_CHARGER_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_BATTERY_SERIAL_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_LAPTOP_BAG_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_MOUSE_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CPD_DOOR_ACCESS']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CPD_ID_CARD']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CPD_HEADSET']."</td></tr>";

    }
    $cmpdetailall .="</table>";
    $reportheadername='COMPANY PROPERTIES TABLE'.$cmpdetailall;
    $finaltable='<html><body><table><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">'.$cmpdetailall.'</div></h2></td></tr></table></body></html>';
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
    $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">COMPANY PROPERTIES DETAILS OF ALL ACTIVE EMPLOYEE</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($finaltable);
    $reportpdf=$mpdf->Output('COMPANY PROPERTIES TABLE.pdf','d');
}
if($_REQUEST['option']=='cmp_unused')
{
    $cmp_unsed=mysqli_query($con,"SELECT CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER FROM COMPANY_PROPERTIES CP WHERE CP_FLAG IS NULL");
    $cmpun_used='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;"></caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LAPTOP NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>CHARGER NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>BATTERY NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LAPTOP BAG NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>MOUSE NO</b></td></tr></th>';
    while($row=mysqli_fetch_array($cmp_unsed)){
        $cmpun_used .="<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_LAPTOP_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_CHARGER_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_BATTERY_SERIAL_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_LAPTOP_BAG_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_MOUSE_NUMBER']."</td></tr>";
    }
    $cmpun_used .="</table>";
    $reportheadername='COMPANY PROPERTIES TABLE'.$cmpun_used;
    $finaltable='<html><body><table><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">'.$cmpun_used.'</div></h2></td></tr></table></body></html>';
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
    $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">UN/USED COMPANY PROPERTIES DETAILS</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($finaltable);
    $reportpdf=$mpdf->Output('UN/USED COMPANY PROPERTIES.pdf','d');
}
if($_REQUEST['option']=='cmp_non_active'){
    $cmp_non_active=mysqli_query($con,"SELECT V.EMPLOYEE_NAME,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET FROM VW_TS_ALL_NON_ACTIVE_EMPLOYEE_DETAILS V,COMPANY_PROPERTIES CP,COMPANY_PROPERTIES_DETAILS CPD,EMPLOYEE_DETAILS EMP WHERE CP.CP_ID=CPD.CP_ID AND EMP.EMP_ID=CPD.EMP_ID AND V.ULD_ID=EMP.ULD_ID ORDER BY V.EMPLOYEE_NAME ASC");
    $cmp_non='<table width=1000 colspan=3px cellpadding=3px><caption align="left" style="font-weight: bold;"></caption><sethtmlpageheader name="header" page="all" value="on" show-this-page="1"/><th nowrap><tr style="color:white;" bgcolor="#498af3" align="center" height=25px><td align="center" style="border: 1px solid black;color:white;" nowrap><b>EMPLOYEE NAME</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LAPTOP NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>CHARGER NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>BATTERY NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>LAPTOP BAG NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>MOUSE NO</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>DOOR ACCESS</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>ID CARD</b></td><td align="center" style="border: 1px solid black;color:white;" nowrap><b>HEAD SET</b></td></tr></th>';
    while($row=mysqli_fetch_array($cmp_non_active)){

        $cmp_non .="<tr style='border: 1px solid black;' height=20px ><td nowrap style='border: 1px solid black;'>".$row['EMPLOYEE_NAME']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_LAPTOP_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_CHARGER_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_BATTERY_SERIAL_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_LAPTOP_BAG_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CP_MOUSE_NUMBER']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CPD_DOOR_ACCESS']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CPD_ID_CARD']."</td><td nowrap style='border: 1px solid black;text-align:center;'>".$row['CPD_HEADSET']."</td></tr>";

    }
    $cmp_non .="</table>";
    $reportheadername='COMPANY PROPERTIES TABLE'.$cmp_non;
    $finaltable='<html><body><table><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">'.$cmp_non.'</div></h2></td></tr></table></body></html>';
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
    $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">COMPANY PROPERTIES DETAILS FOR NON-ACTIVE EMPLOYEE</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($finaltable);
    $reportpdf=$mpdf->Output('COMPANY PROPERTIES TABLE.pdf','d');
}
if($_REQUEST['option']=='non_active_details')
{
    $EMP_loginid=$_REQUEST['login_id'];
    $cmp_all_non_active=mysqli_query($con," SELECT V.EMPLOYEE_NAME,DATE_FORMAT(EMP.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,ED.ED_DESIGNATION AS EMP_DESIGNATION,EMP.EMP_MOBILE_NUMBER,EMP.EMP_NEXT_KIN_NAME,URC.URC_DATA AS EMP_RELATIONHOOD,
EMP.EMP_ALT_MOBILE_NO,EMP.EMP_HOUSE_NO,EMP.EMP_STREET_NAME,EMP.EMP_AREA,EMP.EMP_PIN_CODE,
EMP.EMP_AADHAAR_NO,EMP.EMP_PASSPORT_NO,EMP.EMP_VOTER_ID,EMP.EMP_COMMENTS,EMP.EMP_ACCOUNT_NAME,
EMP.EMP_ACCOUNT_NO,EMP.EMP_BANK_NAME,EMP.EMP_BRANCH_NAME,URC1.URC_DATA AS EMP_ACCOUNT_TYPE,EMP.EMP_IFSC_CODE,EMP.EMP_BRANCH_ADDRESS,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,
CP.CP_BATTERY_SERIAL_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CPD.CPD_DOOR_ACCESS,
CPD.CPD_ID_CARD,CPD.CPD_HEADSET,ULD.ULD_USERSTAMP,DATE_FORMAT(CONVERT_TZ(EMP.EMP_TIMESTAMP,'+00:00','+05:30'),'%d-%m-%Y %T') AS EMP_TIMESTAMP
FROM VW_TS_ALL_NON_ACTIVE_EMPLOYEE_DETAILS V,EMPLOYEE_DETAILS EMP LEFT JOIN COMPANY_PROPERTIES_DETAILS CPD ON EMP.EMP_ID=CPD.EMP_ID LEFT JOIN COMPANY_PROPERTIES CP ON
CPD.CP_ID=CP.CP_ID,USER_RIGHTS_CONFIGURATION URC,EMPLOYEE_DESIGNATION ED,USER_LOGIN_DETAILS ULD,USER_RIGHTS_CONFIGURATION URC1 WHERE V.ULD_ID=EMP.ULD_ID AND
EMP.EMP_RELATIONHOOD=URC.URC_ID AND URC.CGN_ID=22 AND ED.ED_ID=EMP.EMP_DESIGNATION AND
EMP.ULD_ID=ULD.ULD_ID AND EMP.EMP_ACCOUNT_TYPE=URC1.URC_ID AND URC1.CGN_ID=21 AND
V.ULD_ID=EMP.ULD_ID AND EMP.ULD_ID=$EMP_loginid ORDER BY V.EMPLOYEE_NAME ASC");
    while($row=mysqli_fetch_array($cmp_all_non_active)){
        $cmpdetails[]=array($row['EMPLOYEE_NAME'],$row['CP_LAPTOP_NUMBER'],$row['CP_CHARGER_NUMBER'],$row['CP_BATTERY_SERIAL_NUMBER'],$row['CP_LAPTOP_BAG_NUMBER'],$row['CP_MOUSE_NUMBER'],$row['CPD_DOOR_ACCESS'],$row['CPD_ID_CARD'],$row['CPD_HEADSET'],$row['EMP_ACCOUNT_NAME'],$row['EMP_ACCOUNT_NO'],$row['EMP_BANK_NAME'],$row['EMP_BRANCH_NAME'],$row['EMP_ACCOUNT_TYPE'],$row['EMP_IFSC_CODE'],$row['EMP_BRANCH_ADDRESS'],$row['EMP_DOB'],$row['ED_DESIGNATION'],$row['EMP_MOBILE_NUMBER'],$row['EMP_NEXT_KIN_NAME'],$row['EMP_RELATIONHOOD'],$row['EMP_ALT_MOBILE_NO'],$row['EMP_HOUSE_NO'],$row['EMP_STREET_NAME'],$row['EMP_AREA'],$row['EMP_PIN_CODE'],$row['EMP_AADHAAR_NO'],$row['EMP_PASSPORT_NO'],$row['EMP_VOTER_NO'],$row['EMP_COMMENTS'],$row['ULD_USERSTAMP'],$row['EMP_TIMESTAMP']);

    }


    $cmp_properties='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;">
         <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>EMPLOYEE NAME</td><td style=width="250">'.$cmpdetails[0][0].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LAPTOP NO</td><td style=width="250">'.$cmpdetails[0][1].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>CHARGER NO</td><td style=width="250">'.$cmpdetails[0][2].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>BATTERY NO</td><td style=width="250">'.$cmpdetails[0][3].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>LAPTOP BAG NO </td><td style=width="250">'.$cmpdetails[0][4].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>MOUSE NO</td><td style=width="250">'.$cmpdetails[0][5].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DOOR ACCESS</td><td style=width="250">'.$cmpdetails[0][6].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ID CARD</td><td style=width="250">'.$cmpdetails[0][7].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>HEAD SET</td><td style=width="250">'.$cmpdetails[0][8].'</td></tr>
         </table>';

    $bank_details='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;">
         <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>EMPLOYEE NAME</td><td style=width="250">'.$cmpdetails[0][0].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ACCOUNT NAME</td><td style=width="250">'.$cmpdetails[0][9].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ACCOUNT NUMBER</td><td style=width="250">'.$cmpdetails[0][10].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>BANK NAME</td><td style=width="250">'.$cmpdetails[0][11].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>BRANCH NAME</td><td style=width="250">'.$cmpdetails[0][12].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ACCOUNT TYPE</td><td style=width="250">'.$cmpdetails[0][13].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>IFDC CODE</td><td style=width="250">'.$cmpdetails[0][14].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>BRANCH ADDRESS</td><td style=width="250">'.$cmpdetails[0][15].'</td></tr>
         </table>';
    $personal_details='<table width=1000 border=1 cellpadding=0 cellspacing=0 colspan=3px  style="border-collapse:collapse;">
         <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>EMPLOYEE NAME</td><td style=width="250">'.$cmpdetails[0][0].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DOB</td><td style=width="250">'.$cmpdetails[0][16].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>DESIGNATION</td><td style=width="250">'.$cmpdetails[0][17].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>MOBILE NO</td><td style=width="250">'.$cmpdetails[0][18].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>KIN NAME</td><td style=width="250">'.$cmpdetails[0][19].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>RELATIONHOOD</td><td style=width="250">'.$cmpdetails[0][20].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>ALTER MOBILE NO</td><td style=width="250">'.$cmpdetails[0][21].'</td></tr>
        <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>HOUSE NO</td><td style=width="250">'.$cmpdetails[0][22].'</td></tr>
         <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>STREET NAME</td><td style=width="250">'.$cmpdetails[0][23].'</td></tr>
          <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>AREA</td><td style=width="250">'.$cmpdetails[0][24].'</td></tr>
           <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>PIN CODE</td><td style=width="250">'.$cmpdetails[0][25].'</td></tr>
            <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>AADHAAR NO</td><td style=width="250">'.$cmpdetails[0][26].'</td></tr>
             <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>PASSPORT NO</td><td style=width="250">'.$cmpdetails[0][27].'</td></tr>
              <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>VOTER ID</td><td style=width="250">'.$cmpdetails[0][28].'</td></tr>
              <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>COMMENTS</td><td style=width="250">'.$cmpdetails[0][29].'</td></tr>
              <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>USERSTAMP</td><td style=width="250">'.$cmpdetails[0][30].'</td></tr>
              <tr><td width="250" style="color:#fff; background-color:#498af3;font-weight: bold;" height=25px>TIMESTAMP</td><td style=width="250">'.$cmpdetails[0][31].'</td></tr>

         </table>';

    $reportheadername='COMPANY PROPERTIES TABLE'.$cmp_properties[0][0];
    $finaltable= '<html><body><table><tr><td style="text-align: center;"><div></div></td></tr><tr><td><h2><div style="font-weight: bold;margin-bottom: 5cm;">' .$cmp_properties.'</div></h2></td></tr><br><br><br><tr><td>'.$bank_details.'</td></tr><br><br><br><tr><td>'.$personal_details.'</td></tr><br><br><br></table></body></html>';
    $mpdf=new mPDF('utf-8','A4');
    $mpdf->debug=true;
    $mpdf->SetHTMLHeader('<h3><div style="text-align: center; font-weight: bold;margin-bottom: 2cm;">COMPANY PROPERTIES DETAILS</div></h3>', 'O', true);
    $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
    $mpdf->WriteHTML($finaltable);
    $reportpdf=$mpdf->Output(''.$reportheadername.'.pdf','d');
}
?>