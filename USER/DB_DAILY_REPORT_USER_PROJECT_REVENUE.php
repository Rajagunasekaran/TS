<?php
error_reporting(0);
include "../TSLIB/TSLIB_CONNECTION.php";
include "../TSLIB/TSLIB_GET_USERSTAMP.php";
include "../TSLIB/TSLIB_COMMON.php";
$USERSTAMP=$UserStamp;

global $USERSTAMP;
global $con;
//$uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
//while($row=mysqli_fetch_array($uld_id)){
//    $ure_uld_id=$row["ULD_ID"];
//}
$user_uld_id=mysqli_query($con,"select ULD.ULD_ID,CONCAT(ED.EMP_FIRST_NAME,' ',ED.EMP_LAST_NAME)AS USER_NAME from USER_LOGIN_DETAILS ULD, EMPLOYEE_DETAILS ED where ULD.ULD_ID=ED.ULD_ID AND ULD.ULD_LOGINID='$USERSTAMP'");
while($row=mysqli_fetch_array($user_uld_id)){
    $ure_uld_id=$row["ULD_ID"];
    $ure_uld_name=$row["USER_NAME"];
}
if($_REQUEST['option']=="common")
{
// GET ERR MSG
    $REV_errmsg=get_error_msg('15,16,75,82,83,106,107,108,111,112');
    $final_array=array($REV_errmsg,$ure_uld_id,$ure_uld_name);
    echo JSON_ENCODE($final_array);
}

if($_REQUEST['option']=="SPECICIFIED_PROJECT_NAME")
{
//    $project_result=mysqli_query($con,"select * from EMPLOYEE_PROJECT_DETAILS EPD,PROJECT_DETAILS PD,PROJECT_STATUS PS where EPD.PS_ID=PS.PS_ID AND PS.PD_ID=PD.PD_ID  AND EPD.ULD_ID='$ure_uld_id' order by PD.PD_PROJECT_NAME asc");
    $project_result=mysqli_query($con,"select * from EMPLOYEE_PROJECT_DETAILS EPD,PROJECT_DETAILS PD,PROJECT_STATUS PS where EPD.PS_ID=PS.PS_ID AND PS.PD_ID=PD.PD_ID  AND EPD.ULD_ID='$ure_uld_id' order by PD.PD_PROJECT_NAME asc");
    $get_project_array=array();
    while($row=mysqli_fetch_array($project_result)){
        $get_project_array[]=array($row["PD_PROJECT_NAME"]);
    }
    echo JSON_ENCODE($get_project_array);
}
//EMPLOYEE PERIOD
if($_REQUEST['option']=="EMPLOYEEPERIOD")
{
    $REV_project_date = mysqli_query($con,"SELECT MIN(UARD_DATE)AS MINDATE, MAX(UARD_DATE) AS MAXDATE FROM USER_ADMIN_REPORT_DETAILS WHERE  ULD_ID='$ure_uld_id'");
    while($row=mysqli_fetch_array($REV_project_date)){
        $min_date=$row["MINDATE"];
        $max_date =$row["MAXDATE"];
        $date_values=array($min_date,$max_date);
    }
    echo JSON_ENCODE($date_values);
}

//SET  MIN ND MAX DATE FUNCTION FOR PROJECT NAME BY DATE RANGE
if($_REQUEST['option']=="set_datemin_max")
{
    $REV_prjctnamelbx=$_REQUEST['REV_project_name'];
    $REV_project_recver=$_REQUEST['project_recver'];
    $uld_id=mysqli_query($con,"select PS_ID from PROJECT_DETAILS PD ,PROJECT_STATUS PS where PD_PROJECT_NAME='$REV_prjctnamelbx'  AND PD.PD_ID=PS.PD_ID AND PS.PS_REC_VER='$REV_project_recver'");
    while($row=mysqli_fetch_array($uld_id)){
        $REV_pd_id=$row["PS_ID"];
    }
    //SET MIN DATE
    $min_date=mysqli_query($con,"select PS_START_DATE from PROJECT_STATUS where PS_ID='$REV_pd_id'");
    while($row=mysqli_fetch_array($min_date)){
        $mindate_array=$row["PS_START_DATE"];
        $min_date = $mindate_array;
    }
    //SET MAX DATE
    $REV_searchmax_date=mysqli_query($con,"select  MAX(UARD_DATE) AS UARD_DATE from USER_ADMIN_REPORT_DETAILS where UARD_PSID='$REV_pd_id' ");
    while($row=mysqli_fetch_array($REV_searchmax_date)){
        $REV_searchmax_date_value=$row["UARD_DATE"];
        $max_date= $REV_searchmax_date_value;
    }
    $minmax_date_values=array($min_date,$max_date);
    echo JSON_ENCODE($minmax_date_values);
}

//PROJECT RECORD VERSION
if($_REQUEST['option']=="PROJECTRECVERSION")
{
    $select_option=$_REQUEST["selectoption"];
    // PROJECT NAME REV VERSION LIST
    if($select_option==7 ||$select_option==10 ){
        $REV_projectname=$_REQUEST['REV_lb_projectname'];
    }else{
        $REV_projectname=$_REQUEST['REV_lb_empproject'];
    }
    $project_result=mysqli_query($con,"SELECT PS.PS_REC_VER FROM PROJECT_DETAILS PD JOIN PROJECT_STATUS PS WHERE PD.PD_ID=PS.PD_ID and PD.PD_PROJECT_NAME='$REV_projectname'");
    $REV_project_recver=array();
    while($row=mysqli_fetch_array($project_result)){
        $REV_project_recver[]=$row["PS_REC_VER"];
    }
    echo JSON_ENCODE($REV_project_recver);
}

//FETCHING DATA TABLE FRM DB FOR PROJECT REVENUE BY ACTIVE NONACTIVE EMPLOYEE
if($_REQUEST['option']=="nonactiveempdatatble")
{
    $loginid=$ure_uld_id;
    $projectname=$_REQUEST['REV_prjctname'];
    $with_projectname=$_REQUEST['REV_withproject'];
    $project_rec_ver=$_REQUEST['project_recver'];
    $REV_startdate="null";
    $REV_enddate="null";
    $projectnames="null";
    if($with_projectname=='project')//WITH PROJECT NAME
    {
//        echo "CALL SP_PROJECT_REVENUE_BY_EMPLOYEE('1','$loginid','$projectname','$project_rec_ver',$REV_startdate,$REV_enddate,'$USERSTAMP',@TEMP_PROJECT_EMPLOYEE_REVENUE,@NO_OF_DAYS_WORKED,@T_DAYS,@T_HRS,@T_MIN)";
        $result = $con->query("CALL SP_PROJECT_REVENUE_BY_EMPLOYEE('1','$loginid','$projectname','$project_rec_ver',$REV_startdate,$REV_enddate,'$USERSTAMP',@TEMP_PROJECT_EMPLOYEE_REVENUE,@NO_OF_DAYS_WORKED,@T_DAYS,@T_HRS,@T_MIN)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @TEMP_PROJECT_EMPLOYEE_REVENUE,@NO_OF_DAYS_WORKED,@T_DAYS,@T_HRS,@T_MIN');
        $result = $select->fetch_assoc();
        $temp_table_name= $result['@TEMP_PROJECT_EMPLOYEE_REVENUE'];
        $working_day=$result['@NO_OF_DAYS_WORKED'];
        $total_hrs=$result['@T_HRS'];
        $total_mints=$result['@T_MIN'];
        $select_data="select DATE_FORMAT(PROJECT_DATE,'%d-%m-%Y') AS PROJECT_DATE,DAYS,HRS,MINUTES from $temp_table_name ";
        $select_data_rs=mysqli_query($con,$select_data);
        $final_values=array();
        $values_array=false;
        while($row=mysqli_fetch_array($select_data_rs)){
            $projectdate=$row['PROJECT_DATE'];
            $project_days=$row['DAYS'];
            $project_hrs=$row['HRS'];
            $project_mints=$row['MINUTES'];
            $final_values=array('projectdate'=>$projectdate,'project_days'=>$project_days,'project_hrs'=>$project_hrs,'project_mints'=>$project_mints,'working_day' => $working_day);
            $values_array[]=$final_values;
        }
    }
    else{
        $result = $con->query("CALL SP_ALL_PROJECT_REVENUE_BY_EMPLOYEE('1','$loginid',$REV_startdate,$REV_enddate,'$USERSTAMP',@TEMP_PROJECT_EMPLOYEE_REVENUE)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @TEMP_PROJECT_EMPLOYEE_REVENUE');
        $result = $select->fetch_assoc();
        $temp_table_name= $result['@TEMP_PROJECT_EMPLOYEE_REVENUE'];
        $select_data="select PROJECT_NAME,DAYS,HRS,MINUTES from $temp_table_name ";
        $select_data_rs=mysqli_query($con,$select_data);
        $final_values=array();
        $values_array=false;
        while($row=mysqli_fetch_array($select_data_rs)){
            $projectname=$row['PROJECT_NAME'];
            $project_days=$row['DAYS'];
            $project_hrs=$row['HRS'];
            $project_mints=$row['MINUTES'];
            $final_values=array('projectname'=>$projectname,'project_days'=>$project_days,'project_hrs'=>$project_hrs,'project_mints'=>$project_mints);
            $values_array[]=$final_values;
        }
    }
    $drop_query="DROP TABLE $temp_table_name ";
    mysqli_query($con,$drop_query);
    echo JSON_ENCODE($values_array);
//    echo ($values_array);
}
?>