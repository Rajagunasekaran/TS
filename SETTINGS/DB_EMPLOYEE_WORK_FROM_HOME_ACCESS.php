<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE PROJECT ACCESS*********************************************//
//DONE BY:LALITHA
//VER 0.03 SD:09/01/2014 ED:09/01/2014,TRACKER NO:74,Changed preloader position
//VER 0.02 SD:06/01/2014 ED:08/01/2014,TRACKER NO:74,Updated preloader position nd message box position,Changed loginid to emp name
//VER 0.01-INITIAL VERSION, SD:24/09/2014 ED:29/09/2014,TRACKER NO:79
//*********************************************************************************************************//
error_reporting(0);
if(isset($_REQUEST)){
    include "../TSLIB/TSLIB_CONNECTION.php";
    include "../TSLIB/TSLIB_COMMON.php";
    include "../TSLIB/TSLIB_GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
    $project_result=mysqli_query($con,"SELECT AC_ID,AC_DATA FROM ATTENDANCE_CONFIGURATION WHERE AC_ID=15");
    $get_project_array=array();
    while($row=mysqli_fetch_array($project_result)){
        $get_project_array[]=array($row["AC_DATA"],$row["AC_ID"]);
    }
//    }
//FETCHING DATAS LOADED FRM DB FOR LOGIN ID ND ERR MSGS
    if($_REQUEST['option']=="common")
    {
        $REV_active_empname=mysqli_query($con,"SELECT * FROM VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS WHERE URC_DATA!='SUPER ADMIN'  ORDER BY EMPLOYEE_NAME");
        $REV_active_emp=array();
        while($row=mysqli_fetch_array($REV_active_empname)){
            $REV_active_emp[]=array($row["EMPLOYEE_NAME"],$row["ULD_ID"]);
        }
        $EMP_ENTRY_errmsg=get_error_msg('71,83,139,140');
        $final_values=array($REV_active_emp,$get_project_array,$EMP_ENTRY_errmsg);
        echo json_encode($final_values);
    }
//FETCHING SELECT DATA VALUE
    if($_REQUEST['option']=="check_flag")
    {
        $loginid=$_REQUEST['loginid'];
        $check_flag=mysqli_query($con,"SELECT WFHA_FLAG FROM WORK_FROM_HOME_ACCESS WHERE WFHA_FLAG='X' AND ULD_ID=$loginid");
        $flag='';
        while($row=mysqli_fetch_array($check_flag))
        {
            $flag=$row['WFHA_FLAG'];
        }
        echo json_encode($flag);
    }
//FUNCTION TO SAVE AND UPDATE THE EMPLOYEE PROJECT DETAILS
    $EMP_ENTRY_uld_id=$_POST['EMP_ENTRY_lb_loginid'];
    $uld_id=mysqli_query($con,"SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$EMP_ENTRY_loginid'");
    while($row=mysqli_fetch_array($uld_id)){
        $EMP_ENTRY_uld_id=$row["ULD_ID"];
    }

    $user_stamp=mysqli_query($con,"SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP'");
    while($row=mysqli_fetch_array($user_stamp)){
        $EMP_ENTRY_user_id=$row["ULD_ID"];
    }

    $projectid=$_POST['checkbox'];

    if($projectid != "")
    {
        $projectid='X';
    }
    else
    {
        $projectid='';
    }
    if($_REQUEST['option']=="PROJECT_PROPETIES_SAVE"){

        $query=mysqli_query($con,"SELECT * FROM WORK_FROM_HOME_ACCESS WHERE ULD_ID=$EMP_ENTRY_uld_id");
        $tot_record=mysqli_num_rows($query);
        if($tot_record>0)
        {
            $result = $con->query("UPDATE WORK_FROM_HOME_ACCESS SET WFHA_FLAG='$projectid' WHERE ULD_ID=$EMP_ENTRY_uld_id");
            if($result)
            {
                $return_flag=1;
            }
            else{
                $return_flag=0;
            }
            $msg='UPDATE';
            $final_array=array($return_flag,$msg);
            echo json_encode($final_array);
        }
        else
        {
            $result = $con->query("INSERT INTO WORK_FROM_HOME_ACCESS(ULD_ID,WFHA_FLAG,WFHA_USERSTAMP_ID) VALUES('$EMP_ENTRY_uld_id','$projectid','$EMP_ENTRY_user_id')");
            if($result)
            {
                $return_flag=1;
            }
            else{
                $return_flag=0;
            }
            $msg='SAVE';
            $final_array=array($return_flag,$msg);
            echo json_encode($final_array);
        }
    }
}
?>