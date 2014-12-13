<?php
error_reporting(0);
include "CONNECTION.php";
include 'GET_USERSTAMP.php';
include "COMMON.php";
$USERSTAMP=$UserStamp;
if(isset($_REQUEST))
{
    if($_REQUEST['option']=='SAVE')
    {
        $project_name=$_REQUEST['PE_tb_prjectname'];
        $project_des=$_REQUEST['PE_ta_prjdescrptn'];
        $project_status=$_REQUEST['PE_tb_status'];
        $sdate=$_REQUEST['PE_tb_sdate'];
        $project_sdate=date("Y-m-d",strtotime($sdate));
        $edate=$_REQUEST['PE_tb_edate'];
        $project_edate=date("Y-m-d",strtotime($edate));
        $projectid=0;
        $psid=0;
        $result = $con->query("CALL SP_TS_PROJECT_DETAILS_INSERT_UPDATE('$projectid','$project_name','$project_des','$psid','$project_status','$project_sdate','$project_edate','$USERSTAMP','INSERT',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
        echo $flag;

    }

    else if($_REQUEST['option']=='CHECK')
    {
        $project_name=$_REQUEST['checkproject_name'];

        $sql="SELECT * FROM PROJECT_DETAILS WHERE PD_PROJECT_NAME='$project_name'";
        $sql_result= mysqli_query($con,$sql);
        $row=mysqli_num_rows($sql_result);

        $x=$row;
        if($x > 0)
        {
            $flag=1;
        }
        else{
            $flag=0;
        }
        echo $flag;
    }
    else if($_REQUEST['option']=='RANDOM')
    {
        $project_name=$_REQUEST['checkproject_name'];

        $sql="SELECT PD_ID FROM PROJECT_DETAILS WHERE PD_PROJECT_NAME='$project_name'";
        $sql_result= mysqli_query($con,$sql);
        while($row=mysqli_fetch_array($sql_result)){
            $pdid=$row['PD_ID'];

        }
        $select_sql="select DISTINCT PD.PD_PROJECT_NAME FROM PROJECT_DETAILS PD JOIN USER_ADMIN_REPORT_DETAILS UARD ON PD.PD_ID=UARD.UARD_PDID WHERE PD.PD_ID='$pdid'";
        $sql_result= mysqli_query($con,$select_sql);
        $row=mysqli_num_rows($sql_result);
        $x=$row;
        if($x > 0)
        {
            $flag=1;
        }
        else{
            $flag=0;
        }
        echo $flag;
    }


    else if($_REQUEST['option']=='STATUS')
    {

        $status=mysqli_query($con,"SELECT PC_DATA from PROJECT_CONFIGURATION WHERE CGN_ID=7 ");
        $pro_status=array();
        while($row=mysqli_fetch_array($status)){
            $pro_status[]=$row["PC_DATA"];
        }
        echo json_encode( $pro_status);

    }
    else if($_REQUEST['option']=='AUTO')
    {
        global $con;
        $auto=mysqli_query($con," SELECT PD_PROJECT_NAME FROM PROJECT_DETAILS WHERE PD_ID=(SELECT PD_ID FROM PROJECT_STATUS WHERE PC_ID=3)");
        $pro_auto=array();
        while($row=mysqli_fetch_array($auto)){
            $pro_auto[]=$row["PD_PROJECT_NAME"];
        }
        $erro_msg=get_error_msg('62,63,79,80,81');
        $values=array($pro_auto,$erro_msg);
        echo json_encode($values);

    }
}
if(isset($_REQUEST['option']) && $_REQUEST['option']!=''){
    $actionfunction = $_REQUEST['option'];

    call_user_func($actionfunction,$_REQUEST,$con);
}
function showData($data,$con){
    $sql = "SELECT PC.PC_DATA,PC.PC_ID,PS.PS_ID,DATE_FORMAT(PS.PS_END_DATE,'%d-%m-%Y')as PS_END_DATE,DATE_FORMAT(PS.PS_START_DATE,'%d-%m-%Y') as PS_START_DATE,PD.PD_ID,PD.PD_PROJECT_NAME,PD.PD_PROJECT_DESCRIPTION,DATE_FORMAT(CONVERT_TZ(PD.PD_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') as TIMESTAMP,ULD.ULD_LOGINID as  ULD_USERSTAMP FROM PROJECT_DETAILS PD JOIN PROJECT_STATUS PS on PD.PD_ID = PS.PD_ID JOIN PROJECT_CONFIGURATION PC on PS.PC_ID = PC.PC_ID JOIN USER_LOGIN_DETAILS ULD on PD.ULD_ID=ULD.ULD_ID";
    $data = $con->query($sql);
    $str='<thead  bgcolor="#6495ed" style="color:white"><tr class="head"><th>PROJECT NAME</th><th>PROJECT DESCRIPTION</th><th>STATUS</th><th>START DATE</th><th>END DATE</th><th>USERSTAMP</th><th>TIMESTAMP</th><th>EDIT</th></tr></thead><tbody>';
    if($data->num_rows>0){
        while( $row = $data->fetch_array(MYSQLI_ASSOC)){
            $str.="<tr id='".$row['PD_ID']."'><td>".$row['PD_PROJECT_NAME']."</td><td>".$row['PD_PROJECT_DESCRIPTION']."</td><td>".$row['PC_DATA']."</td><td>".$row['PS_START_DATE']."</td><td>".$row['PS_END_DATE']."</td><td>".$row['ULD_USERSTAMP']."</td><td nowrap>".$row['TIMESTAMP']."</td><td><input type='button' id='editbtn' class='ajaxedit btn' value='Edit'/> </td></tr>";
        }
    }
    else{
        $str.= "<td colspan='5'>No Data Available</td>";
    }
    echo "</tbody>".$str;
}
function updateData($data,$con){
global $USERSTAMP;
    $pname = $con->real_escape_string($data['name']);
    $pdes = $con->real_escape_string($data['des']);
    $pstatus = $con->real_escape_string($data['sta']);
    $date = $con->real_escape_string($data['ssd']);
    $psdate = date("Y-m-d",strtotime($date));
    $date = $con->real_escape_string($data['eed']);
    $enddate = date("Y-m-d",strtotime($date));
    $EDIT = $con->real_escape_string($data['editid']);
    $query="select PS_ID FROM PROJECT_STATUS WHERE PD_ID =$EDIT";
    $result=mysqli_query($con,$query);
    while($row=mysqli_fetch_array($result)){
        $PS_ID=$row["PS_ID"];
    }

    $QUERY= "CALL SP_TS_PROJECT_DETAILS_INSERT_UPDATE('$EDIT','$pname','$pdes','$PS_ID','$pstatus','$psdate','$enddate','$USERSTAMP','UPDATE',@success_flag)";
    $result = $con->query($QUERY);
    if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
    $select = $con->query('SELECT @success_flag');
    $result = $select->fetch_assoc();
    $flag= $result['@success_flag'];
    echo $flag;
}
function deleteData($data,$con){
    $delid = $con->real_escape_string($data['deleteid']);
    $sql = "delete from ajaxtable where id=$delid";
    if($con->query($sql)){
        showData($data,$con);
    }
    else{
        echo "error";
    }
}
?>