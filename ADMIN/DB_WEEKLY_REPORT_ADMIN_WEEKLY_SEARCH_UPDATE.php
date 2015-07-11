<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*********************************ADMIN WEEKLY SEARCH/UPDATE******************************************//
//DONE BY:LALITHA
//0.05-SD:29/12/2014 ED:30/12/2014,TRACKER NO:74,Changed date picker nd validation
//0.04-SD:19/12/2014 ED:19/12/2014,TRACKER NO:74,Updated sorting function for date nd timestamp
//DONE BY:SASIKALA
//0.03-SD:03/12/2014 ED:04/12/2014,TRACKER NO:74,DONE REPORT SHOWING POINT BY POINT,DATATABLE HEADER FIXED AND PDF EXPORT FILENAME FIXED.
//DONE BY:LALITHA
//0.02-SD:02/12/2014 ED:02/12/2014,TRACKER NO:74,Fixed max date nd min dte,Changed Preloder funct,Removed confirmation err msg,Fixed flex tble width
//DONE BY:SHALINI
//VER 0.01-INITIAL VERSION, SD:20/10/2014 ED:28/10/2014,TRACKER NO:86
//*********************************************************************************************************//-->
error_reporting(0);
include "../TSLIB/TSLIB_CONNECTION.php";
include "../TSLIB/TSLIB_GET_USERSTAMP.php";
$USERSTAMP=$UserStamp;
if(isset($_REQUEST['option']) && $_REQUEST['option']!=''){
    $actionfunction = $_REQUEST['option'];
    call_user_func($actionfunction,$_REQUEST,$con);
}
//FUNCTION TO SHOW DATATABLE
function showData($data,$con){
    $date = $con->real_escape_string($data['startdate']);
    $startdate=$_REQUEST['startdate'];
    $startdate = date("Y-m-d",strtotime($startdate));
    $enddate=$_REQUEST['enddate'];
//    $date = $con->real_escape_string($data['enddate']);
    $enddate = date("Y-m-d",strtotime($enddate));
    $AWSU_values=array();
    $sql="SELECT AW.AWRD_ID,AW.AWRD_REPORT,AW.AWRD_DATE,DATE_FORMAT(CONVERT_TZ(AW.AWRD_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS AWRD_TIMESTAMP,ULD.ULD_LOGINID as ULD_USERSTAMP FROM ADMIN_WEEKLY_REPORT_DETAILS AW JOIN USER_LOGIN_DETAILS ULD on AW.ULD_ID=ULD.ULD_ID  where AW.AWRD_DATE BETWEEN '$startdate' AND '$enddate' ORDER BY AWRD_DATE DESC";
    $projectfetch= mysqli_query($con, $sql);
    $AWSU_values=false;
    while($row=mysqli_fetch_array($projectfetch)){
        $awsu_report=$row['AWRD_REPORT'];
        if($awsu_report!=null){
            $awsu_rprt='';
            $body_msg =explode("\n", $awsu_report);
            $length=count($body_msg);
            for($i=0;$i<$length;$i++){
                $awsu_rprt.=$body_msg[$i].'<br>';
            }
        }
        else{
            $awsu_rprt=null;
        }
        $awsu_id=$row['AWRD_ID'];
        $awsu_date=$row['AWRD_DATE'];
        $awsu_userstamp=$row["ULD_USERSTAMP"];
        $awsu_timestamp=$row["AWRD_TIMESTAMP"];
        $AWSU_report_values=array('report'=>$awsu_rprt,'report1'=>$awsu_report,'id'=>$awsu_id,'date'=>$awsu_date,'userstamp'=>$awsu_userstamp,'timestamp'=>$awsu_timestamp);
        $AWSU_values[]=$AWSU_report_values;
    }
    echo JSON_ENCODE($AWSU_values);
}
//FUNCTION TO UPDATE VALUES
function updateData($data,$con){
    global $USERSTAMP;
    $report = $con->real_escape_string($data['report']);
    $editid = $con->real_escape_string($data['editid']);
    $sql = "UPDATE ADMIN_WEEKLY_REPORT_DETAILS SET AWRD_REPORT='$report',ULD_ID=(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')  WHERE AWRD_ID=$editid";
    if (!mysqli_query($con,$sql)) {
        die('Error: ' . mysqli_error($con));
        $flag=0;
    }
    else{
        $flag=1;
    }
    echo $flag;
}
//INLINE EDIT.
if($_REQUEST['option']=='update')
{
    $awsu_id=$_REQUEST['id'];
    $report=$_REQUEST['reportvalue'];
    $report1= $con->real_escape_string($report);
//    echo("UPDATE  ADMIN_WEEKLY_REPORT_DETAILS  SET ETD_EMAIL_SUBJECT='$ET_SRC_UPD_DEL_subject' WHERE ETD_ID=$ET_SRC_UPD_DEL_el_id");
    $update="UPDATE ADMIN_WEEKLY_REPORT_DETAILS SET AWRD_REPORT='$report1' WHERE AWRD_ID= $awsu_id";
    if ($con->query($update) === TRUE) {
        $flag= 1;
    }
    else
    {
        echo "Error: " . $update . "<br>" . $con->error;
        $flag=0;
    }
    echo $flag;
}
?>