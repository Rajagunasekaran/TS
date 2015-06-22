<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE PROJECT ACCESS SEARCH/UPDATE*********************************************//
//DONE BY:LALITHA
//VER 0.03 SD:09/01/2014 ED:10/01/2014,TRACKER NO:74,Changed preloader position
//VER 0.02 SD:06/12/2014 ED:08/12/2014,TRACKER NO:74,Updated preloader position nd message box position,Changed loginid to emp name
//VER 0.01-INITIAL VERSION, SD:24/09/2014 ED:29/09/2014,TRACKER NO:79
//*********************************************************************************************************//
error_reporting(0);
if(isset($_REQUEST)){
    include "../TSLIB/TSLIB_CONNECTION.php";
    include "../TSLIB/TSLIB_COMMON.php";
    include "../TSLIB/TSLIB_GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
//FETCHING DATAS LOADED FRM DB FOR LOGIN ID ND ERR MSGS
    if($_REQUEST['option']=="common")
    {
        $EMPSRC_UPD_errmsg=get_error_msg('56,83,118');
        $EMPSRC_UPD_active_empname=mysqli_query($con,"SELECT * FROM VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS WHERE URC_DATA!='SUPER ADMIN' AND ULD_ID IN(SELECT ULD_ID FROM EMPLOYEE_PROJECT_DETAILS) ORDER BY EMPLOYEE_NAME");
        $EMPSRC_UPD_active_emp=array();
        while($row=mysqli_fetch_array($EMPSRC_UPD_active_empname)){
            $EMPSRC_UPD_active_emp[]=array($row["EMPLOYEE_NAME"],$row["ULD_ID"]);
        }
        $final_values=array($EMPSRC_UPD_active_emp,$EMPSRC_UPD_errmsg);
        echo JSON_ENCODE($final_values);
    }
    //FETCHING DATAS LOADED FRM DB FOR PROJECT NAME
    if($_REQUEST['option']=="PROJECT_NAME")
    {
        $loginid=$_POST['EMPSRC_UPD_lb_loginid'];
        $get_project_array=get_emp_projectentry();
//        echo "SELECT DISTINCT PS.PS_ID,PD.PD_ID,PD.PD_PROJECT_NAME,PS.PS_REC_VER from EMPLOYEE_PROJECT_DETAILS EPD,PROJECT_DETAILS PD,PROJECT_STATUS PS where EPD.PS_ID=PS.PS_ID AND PD.PD_ID=PS.PD_ID  AND EPD.EPD_FLAG IS NULL AND EPD.ULD_ID='$loginid'";
        $query= "SELECT DISTINCT PS.PS_ID,PD.PD_ID,PD.PD_PROJECT_NAME,PS.PS_REC_VER from EMPLOYEE_PROJECT_DETAILS EPD,PROJECT_DETAILS PD,PROJECT_STATUS PS where EPD.PS_ID=PS.PS_ID AND PD.PD_ID=PS.PD_ID  AND EPD.EPD_FLAG IS NULL AND EPD.ULD_ID='$loginid'";
        $EMPSRC_UPD_prj_result=mysqli_query($con,$query);
        while($row=mysqli_fetch_array($EMPSRC_UPD_prj_result)){
            $get_project[]=array($row["PD_PROJECT_NAME"],$row["PS_ID"],$row["PS_REC_VER"]);
        }
        $final_values=array($get_project_array,$get_project);
        echo JSON_ENCODE($final_values);
    }
    //FUNCTION FOR TO UPDATE THE EMPLOYEE PROJECT DETAILS
    if($_REQUEST['option']=="PROJECT_PROPERTIES_UPDATE"){
        $EMP_ENTRY_uld_id=$_POST['EMPSRC_UPD_lb_loginid'];
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$EMPSRC_UPD_loginid'");
        while($row=mysqli_fetch_array($uld_id)){
            $EMP_ENTRY_uld_id=$row["ULD_ID"];
        }
        $project=$_POST['checkbox'];
        $length=count($project);
        $projectid;
        for($i=0;$i<$length;$i++){
            if($i==0){
                $projectid=$project[$i];
            }
            else{
                $projectid=$projectid .",".$project[$i];
            }
        }
        $projectid;
        $result = $con->query("CALL SP_EMPLOYEE_PROJECT_ENTRY_INSERT('2','$EMP_ENTRY_uld_id','$projectid','$USERSTAMP',@PROJECTUPDATE_FLAG)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @PROJECTUPDATE_FLAG');
        $result = $select->fetch_assoc();
        $return_flag= $result['@PROJECTUPDATE_FLAG'];
        echo $return_flag;
    }
}
?>