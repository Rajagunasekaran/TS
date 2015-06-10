<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************PUBLIC HOLIDAY SEARCH/UPDATE*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:17/12/2014 ED:17/12/2014,TRACKER NO:74
//*********************************************************************************************************//
error_reporting(0);
if(isset($_REQUEST)){
    include "../TSLIB/TSLIB_CONNECTION.php";
    include "../TSLIB/TSLIB_COMMON.php";
    include "../TSLIB/TSLIB_GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
//FETCHING DATAS LOADED FRM DB FOR LISTBX
    if($_REQUEST['option']=="common")
    {
// GET ERR MSG
        $PH_SRC_UPD_nodate=get_error_msg('4,18,56,83,89');
        // YEAR FOR PUBLIC HOLIDAY
        $PH_SRC_UPD_yr_list = mysqli_query($con,"SELECT DISTINCT DATE_FORMAT(PH_DATE, '%Y')AS YEAR FROM PUBLIC_HOLIDAY;");
        $PH_SRC_UPD_yrlist=array();
        while($row=mysqli_fetch_array($PH_SRC_UPD_yr_list)){
            $PH_SRC_UPD_yrlist[]=array($row["YEAR"]);
        }
        $final_values=array($PH_SRC_UPD_yrlist,$PH_SRC_UPD_nodate);
        echo JSON_ENCODE($final_values);
    }
    if($_REQUEST['option']=="PUBLIC_HOLIDAY_DETAILS")
    {
        //FETCHING USER LOGIN DETAILS RECORDS
        $PH_SRC_UPD_year=$_REQUEST["PH_SRC_UPD_lb_yr"];
        $date= mysqli_query($con,"SELECT PH.PH_ID,DATE_FORMAT(PH.PH_DATE,'%d-%m-%Y') AS PH_DATE,PH.PH_DESCRIPTION,ULD.ULD_LOGINID,DATE_FORMAT(CONVERT_TZ(PH.PH_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS PH_TIMESTAMP FROM PUBLIC_HOLIDAY PH ,USER_LOGIN_DETAILS ULD WHERE PH.ULD_ID=ULD.ULD_ID AND YEAR(PH.PH_DATE)='$PH_SRC_UPD_year'");
        $ure_values=array();
        while($row=mysqli_fetch_array($date)){
            $PH_SRC_UPD_date=$row["PH_DATE"];
            $PH_SRC_UPD_descr=$row["PH_DESCRIPTION"];
            $PH_SRC_UPD_id=$row['PH_ID'];
            $PH_SRC_UPD_userstamp=$row['ULD_LOGINID'];
            $PH_SRC_UPD_timestamp=$row['PH_TIMESTAMP'];
            $final_values=(object) ['id'=>$PH_SRC_UPD_id,'PH_SRC_UPD_date' =>$PH_SRC_UPD_date,'PH_SRC_UPD_descr' =>$PH_SRC_UPD_descr,'PH_SRC_UPD_userstamp' =>$PH_SRC_UPD_userstamp,'PH_SRC_UPD_timestamp' =>$PH_SRC_UPD_timestamp];
            $ure_values[]=$final_values;
        }
        $finalvalue=array($ure_values);
        echo JSON_ENCODE($finalvalue);
    }
    //FUNCTION FOR TO UPDATE THE EMPLOYEE DETAILS ND COMPANY DETAILS
    if($_REQUEST['option']=="PROJECT_DETAILS_UPDATE"){
        $EMPSRC_UPD_DEL_rd_flxtbl=$_POST['EMPSRC_UPD_DEL_rd_flxtbl'];
        $PH_SRC_UPD_des=$_POST['PH_SRC_UPD_tb_des'];
        $PH_SRC_UPD_des=$con->real_escape_string($PH_SRC_UPD_des);
        $PH_SRC_UPD_date=$_POST['PH_SRC_UPD_tb_date'];
        $PH_SRC_UPD_date = date('Y-m-d',strtotime($PH_SRC_UPD_date));
        $sql="UPDATE PUBLIC_HOLIDAY SET PH_DATE='$PH_SRC_UPD_date',PH_DESCRIPTION='$PH_SRC_UPD_des',ULD_ID=(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP') WHERE PH_ID='$EMPSRC_UPD_DEL_rd_flxtbl' ";
        if (!mysqli_query($con,$sql)) {
            die('Error: ' . mysqli_error($con));
            $flag=0;
        }
        else{
            $flag=1;
        }
        echo $flag;
    }
}