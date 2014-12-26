<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*************************************REVENUE************************************************************//
//DONE BY:LALITHA
//VER 0.04-SD:13/11/2014 ED:13/11/2014,TRACKER NO:97,Tested Newly added outflg for project by emp,Updated showned err msg nd hide,Renamed column name of prjct by revenue
//VER 0.03-SD:29/10/2014 ED:31/10/2014,TRACKER NO:97,Fixed the column nd div width,Changed the data table header is meaningful nd put space also,Updated Sorting fr dte,Hide the section id nd old dt datas while listbx changing fn,Added Preloader in list bx fn ,Column values alignd in centre
//VER 0.02-SD:14/10/2014 ED:21/10/2014,TRACKER NO:97,Did others two parts of projects,Changed data tble for prjct rvn by actv nonactv emp option,Removed hard code of list bx option(tkn data nd id also frm db),Updated data tble,validation,Loaded all err msg frm db,hiding err msg,lbls nd others fields in unwanted places,Changed queries,update dte frmt,Update comments,Set min nd max dte
//DONE BY:SASIKALA
//VER 0.01-INITIAL VERSION, SD:08/10/2014 ED:15/10/2014,TRACKER NO:97
//*********************************************************************************************************//
error_reporting(0);
if(isset($_REQUEST)){
    include "CONNECTION.php";
    include "COMMON.php";
    include "GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
//FETCHING DATAS LOADED FRM DB FOR LISTBX
    if($_REQUEST['option']=="common")
    {
// GET ERR MSG
        $REV_errmsg=get_error_msg('15,16,75,82,106,107,108,111,112');
// REPORT CONFIGURATION LIST
        $REV_project_list = mysqli_query($con,"SELECT * FROM REPORT_CONFIGURATION WHERE CGN_ID=2");
        $REV_projectlist=array();
        while($row=mysqli_fetch_array($REV_project_list)){
            $REV_projectlist[]=array($row["RC_DATA"],$row["RC_ID"]);
        }
// PROJECT NAME LIST
        $project_result=mysqli_query($con,"SELECT PD.PD_PROJECT_NAME FROM PROJECT_DETAILS PD JOIN PROJECT_STATUS PS WHERE PD.PD_ID=PS.PD_ID ORDER BY PD_PROJECT_NAME ASC ");
        $REV_project_array=array();
        while($row=mysqli_fetch_array($project_result)){
            $REV_project_array[]=$row["PD_PROJECT_NAME"];
        }
// ACTIVE EMPLOYEE LIST
        $REV_active_emp=get_active_login_id();
// NON ACTIVE EMPLOYEE LIST
        $REV_active_nonemp=get_nonactive_login_id();
        $final_values=array($REV_projectlist,$REV_project_array,$REV_active_emp,$REV_active_nonemp,$REV_errmsg);
        echo JSON_ENCODE($final_values);
    }
//SETTING MIN ND MAX DATE FUNCTION FOR NON ACTIVE EMPLOYEE BY DATE RANGE
    if($_REQUEST['option']=="login_id")
    {
        $REV_loginid=$_REQUEST['REV_loginids'];
        $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$REV_loginid'");
        while($row=mysqli_fetch_array($uld_id)){
            $REV_uld_id=$row["ULD_ID"];
        }
        //SET MIN DATE
        $min_date=mysqli_query($con,"SELECT UA_JOIN_DATE FROM USER_ACCESS where ULD_ID='$REV_uld_id'");
        while($row=mysqli_fetch_array($min_date)){
            $mindate_array=$row["UA_JOIN_DATE"];
            $min_date = $mindate_array;
        }
        //SET MAC DATE
        $REV_searchmax_date=mysqli_query($con,"SELECT MAX(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS WHERE ULD_ID='$REV_uld_id'");
        while($row=mysqli_fetch_array($REV_searchmax_date)){
            $REV_searchmax_date_value=$row["UARD_DATE"];
            $max_date= $REV_searchmax_date_value;
        }
        $min_date_values=array($min_date,$max_date);
        echo JSON_ENCODE($min_date_values);
    }
//SET  MIN ND MAX DATE FUNCTION FOR PROJECT NAME BY DATE RANGE
    if($_REQUEST['option']=="set_datemin_max")
    {
        $REV_prjctnamelbx=$_REQUEST['REV_project_name'];
        $uld_id=mysqli_query($con,"select PD_ID from PROJECT_DETAILS where PD_PROJECT_NAME='$REV_prjctnamelbx'");
        while($row=mysqli_fetch_array($uld_id)){
            $REV_pd_id=$row["PD_ID"];
        }
        //SET MIN DATE
        $min_date=mysqli_query($con,"select PS_START_DATE from PROJECT_STATUS where PD_ID='$REV_pd_id'");
        while($row=mysqli_fetch_array($min_date)){
            $mindate_array=$row["PS_START_DATE"];
            $min_date = $mindate_array;
        }
        //SET MAX DATE
        $REV_searchmax_date=mysqli_query($con,"select PS_END_DATE from PROJECT_STATUS where PD_ID='$REV_pd_id'");
        while($row=mysqli_fetch_array($REV_searchmax_date)){
            $REV_searchmax_date_value=$row["PS_END_DATE"];
            $max_date= $REV_searchmax_date_value;
        }
        $minmax_date_values=array($min_date,$max_date);
        echo JSON_ENCODE($minmax_date_values);
    }
//FETCHING DATA TABLE FRM DB FOR PROJECT REVENUE
    if($_REQUEST['option']=="projectname")
    {
        $projectname=$_REQUEST['REV_projectname'];
        $REV_startdate="null";
        $REV_enddate="null";
        $result = $con->query("CALL SP_PROJECT_REVENUE_BY_PROJECT_NAME('1','$projectname',$REV_startdate,$REV_enddate,'$USERSTAMP',@TEMP_PROJECT_REVENUE)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @TEMP_PROJECT_REVENUE');
        $result = $select->fetch_assoc();
        $temp_table_name= $result['@TEMP_PROJECT_REVENUE'];
        $REV_values=array();
        $sqlquery=mysqli_query($con,"SELECT USER_NAME,TOTAL_DAYS,TOTAL_HRS,TOTAL_MINUTES FROM $temp_table_name");
        $values_array=false;
        while($row=mysqli_fetch_array($sqlquery)){
            $username=$row['USER_NAME'];
            $noofdays=$row['TOTAL_DAYS'];
            $total_hrs=$row['TOTAL_HRS'];
            $total_minutes=$row['TOTAL_MINUTES'];
            $REV_values=array('username'=>$username,'noofdays'=>$noofdays,'total_hrs'=>$total_hrs,'total_minutes'=>$total_minutes);
            $values_array[]=$REV_values;
        }
        $drop_query="DROP TABLE $temp_table_name ";
        mysqli_query($con,$drop_query);
        echo JSON_ENCODE($values_array);
    }
//FETCHING DATA TABLE FRM DB FOR PROJECT REVENUE BY ACTIVE NONACTIVE EMPLOYEE
    if($_REQUEST['option']=="nonactiveempdatatble")
    {
        $loginid=$_REQUEST['REV_loginid'];
        $projectname=$_REQUEST['REV_prjctname'];
        $with_projectname=$_REQUEST['REV_withproject'];
        $REV_startdate="null";
        $REV_enddate="null";
        $projectnames="null";
        if($with_projectname=='project')//WITH PROJECT NAME
        {
            $result = $con->query("CALL SP_PROJECT_REVENUE_BY_EMPLOYEE('1','$loginid','$projectname',$REV_startdate,$REV_enddate,'$USERSTAMP',@TEMP_PROJECT_EMPLOYEE_REVENUE,@NO_OF_DAYS_WORKED,@T_DAYS,@T_HRS,@T_MIN)");
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
    }
//FETCHING DATA TABLE FRM DB FOR PROJECT REVENUE BY ACTIVE NONACTIVE EMPLOYEE BY DATERANGE
    if($_REQUEST['option']=="non_activeemp_dterange")
    {
        $loginid=$_REQUEST['REV_loginid'];
        $projectname=$_REQUEST['REV_prjctname'];
        $REV_start_datevalue=$_REQUEST['REV_start_datevalue'];
        $REV_start_finaldatevalue = date('Y-m-d',strtotime($REV_start_datevalue));
        $REV_end_datevalue=$_REQUEST['REV_end_datevalue'];
        $REV_end_finaldatevalue = date('Y-m-d',strtotime($REV_end_datevalue));
        $with_projectname=$_REQUEST['REV_withproject'];
        if($with_projectname=='project')//WITH PROJECT NAME
        {
            $result = $con->query("CALL SP_PROJECT_REVENUE_BY_EMPLOYEE('1','$loginid','$projectname','$REV_start_finaldatevalue','$REV_end_finaldatevalue','$USERSTAMP',@TEMP_PROJECT_EMPLOYEE_REVENUE,@NO_OF_DAYS_WORKED,@T_DAYS,@T_HRS,@T_MIN)");
            if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
            $select = $con->query('SELECT @TEMP_PROJECT_EMPLOYEE_REVENUE,@NO_OF_DAYS_WORKED,@T_DAYS,@T_HRS,@T_MIN');
            $result = $select->fetch_assoc();
            $temp_table_name= $result['@TEMP_PROJECT_EMPLOYEE_REVENUE'];
            $total_no_project=$result['@NO_OF_DAYS_WORKED'];
            $total_days=$result['@T_DAYS'];
            $total_hrs=$result['@T_HRS'];
            $total_mints=$result['@T_MIN'];
            $select_data="select  DATE_FORMAT(PROJECT_DATE,'%d-%m-%Y') AS PROJECT_DATE,DAYS,HRS,MINUTES from $temp_table_name WHERE PROJECT_DATE BETWEEN '$REV_start_finaldatevalue' AND '$REV_end_finaldatevalue'";
            $select_data_rs=mysqli_query($con,$select_data);
            $final_values=array();
            $values_array=false;
            while($row=mysqli_fetch_array($select_data_rs)){
                $projectdate=$row['PROJECT_DATE'];
                $project_days=$row['DAYS'];
                $project_hrs=$row['HRS'];
                $project_mints=$row['MINUTES'];
                $final_values=array('projectdate'=>$projectdate,'project_days'=>$project_days,'project_hrs'=>$project_hrs,'project_mints'=>$project_mints,'total_no_project' => $total_no_project);
                $values_array[]=$final_values;
            }
        }
        else
        {
            $result = $con->query("CALL SP_ALL_PROJECT_REVENUE_BY_EMPLOYEE('1','$loginid','$REV_start_finaldatevalue','$REV_end_finaldatevalue','$USERSTAMP',@TEMP_PROJECT_EMPLOYEE_REVENUE)");
            if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
            $select = $con->query('SELECT @TEMP_PROJECT_EMPLOYEE_REVENUE');
            $result = $select->fetch_assoc();
            $temp_table_name= $result['@TEMP_PROJECT_EMPLOYEE_REVENUE'];
            $select_data="select PROJECT_NAME,DAYS,HRS,MINUTES from $temp_table_name";
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
    }
//FETCHING DATA TABLE FRM DB FOR PROJECT REVENUE BY DATE RANGE
    if($_REQUEST['option']=="projectname_dtebyrange")
    {
        $projectname=$_REQUEST['REV_projectname'];
        $REV_start_datevalue=$_REQUEST['REV_start_datevalue'];
        $REV_start_finaldatevalue = date('Y-m-d',strtotime($REV_start_datevalue));
        $REV_end_datevalue=$_REQUEST['REV_end_datevalue'];
        $REV_end_finaldatevalue = date('Y-m-d',strtotime($REV_end_datevalue));
        $result = $con->query("CALL SP_PROJECT_REVENUE_BY_PROJECT_NAME('2','$projectname','$REV_start_finaldatevalue','$REV_end_finaldatevalue','$USERSTAMP',@TEMP_PROJECT_REVENUE)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @TEMP_PROJECT_REVENUE');
        $result = $select->fetch_assoc();
        $temp_table_name= $result['@TEMP_PROJECT_REVENUE'];
        $select_data="select USER_NAME,TOTAL_DAYS,TOTAL_HRS,TOTAL_MINUTES from $temp_table_name";
        $select_data_rs=mysqli_query($con,$select_data);
        $final_values=array();
        $values_array=false;
        while($row=mysqli_fetch_array($select_data_rs)){
            $username=$row['USER_NAME'];
            $noofdays=$row['TOTAL_DAYS'];
            $total_hrs=$row['TOTAL_HRS'];
            $total_minutes=$row['TOTAL_MINUTES'];
            $final_values=array('username'=>$username,'noofdays'=>$noofdays,'total_hrs'=>$total_hrs,'total_minutes'=>$total_minutes);
            $values_array[]=$final_values;
        }
        $drop_query="DROP TABLE $temp_table_name ";
        mysqli_query($con,$drop_query);
        echo JSON_ENCODE($values_array);
    }
}
?>