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
    include "../TSLIB_TSLIB_GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
//FETCHING DATAS LOADED FRM DB FOR LOGIN ID ND ERR MSGS
    if($_REQUEST['option']=="common")
    {
        $REV_active_empname=mysqli_query($con,"SELECT * FROM VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS WHERE URC_DATA!='SUPER ADMIN' AND ULD_ID NOT IN(SELECT ULD_ID FROM EMPLOYEE_PROJECT_DETAILS) ORDER BY EMPLOYEE_NAME");
        $REV_active_emp=array();
        while($row=mysqli_fetch_array($REV_active_empname)){
            $REV_active_emp[]=array($row["EMPLOYEE_NAME"],$row["ULD_ID"]);
        }
        $get_project_array=get_emp_projectentry();
        $EMP_ENTRY_errmsg=get_error_msg('71,83,117');
        $final_values=array($REV_active_emp,$get_project_array,$EMP_ENTRY_errmsg);
        echo JSON_ENCODE($final_values);
    }
    //FUNCTION FOR TO SAVE THE EMPLOYEE PROJECT DETAILS
    if($_REQUEST['option']=="PROJECT_PROPETIES_SAVE"){
        $EMP_ENTRY_uld_id=$_POST['EMP_ENTRY_lb_loginid'];
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$EMP_ENTRY_loginid'");
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
        $result = $con->query("CALL SP_EMPLOYEE_PROJECT_ENTRY_INSERT('1','$EMP_ENTRY_uld_id','$projectid','$USERSTAMP',@PROJECTINSERT_FLAG)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @PROJECTINSERT_FLAG');
        $result = $select->fetch_assoc();
        $return_flag= $result['@PROJECTINSERT_FLAG'];
        echo $return_flag;
    }
}
?>