<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*********************************ADMIN WEEKLY REPORT ENTRY******************************************//
//DONE BY:LALITHA
//VER 0.04-SD:29/12/2014 ED:30/12/2014,TRACKER NO:74,Changed date picker function nd validation,Updated err msg(rep nt saved)
//VER 0.03-SD:02/12/2014 ED:02/12/2014,TRACKER NO:74,Changed Preloder funct,Removed confirmation err msg,Removed hardcode fr mindate
//VER 0.02,SD:14/11/2014 ED:14/11/2014,TRACKER NO:74,Fixed max date nd min dte
//DONE BY:SHALINI
//VER 0.01-INITIAL VERSION, SD:16/10/2014 ED:19/10/2014,TRACKER NO:86
//*********************************************************************************************************//-->
error_reporting(0);
if(isset($_REQUEST))
{
    include "../TSLIB/TSLIB_CONNECTION.php";
    include "../TSLIB/TSLIB_GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    if($_REQUEST['option']=='SUBMIT')
    {
        $rep_entryreport = $_REQUEST['AWRE_SRC_ta_enterreport'];
        $date=$_REQUEST['AWRE_SRC_tb_date'];
        $rep_entrydate = date("Y-m-d",strtotime($date));
        $rep_entryreport=$con->real_escape_string($rep_entryreport);
        $query="select ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID ='$USERSTAMP'";
        $result=mysqli_query($con,$query);
        while($row=mysqli_fetch_array($result)){
            $rep_getuld_id=$row["ULD_ID"];
        }
        $sql="INSERT INTO ADMIN_WEEKLY_REPORT_DETAILS (AWRD_REPORT,AWRD_DATE,ULD_ID)VALUES('$rep_entryreport','$rep_entrydate','$rep_getuld_id')";
        if (!mysqli_query($con,$sql)) {
            die('Error: ' . mysqli_error($con));
            $flag=0;
        }
        else{
            $flag=1;
        }
        echo $flag;
    }
    else if($_REQUEST['option']=='CHECK')
    {
        $date_array=array();
        $sql="SELECT * FROM ADMIN_WEEKLY_REPORT_DETAILS ";
        $sql_result= mysqli_query($con,$sql);
        while($row=mysqli_fetch_array($sql_result)){
            $date_array[]=$row["AWRD_DATE"];
        }
        echo JSON_ENCODE($date_array);
    }
}
?>