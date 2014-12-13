<?php
error_reporting(0);
//get uld_id
function getULD_ID_from_ULD_LOGINID1($ULD_LOGINID){
    global $con;
    $query="select ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID ='".$ULD_LOGINID."'";
    $result=mysqli_query($con,$query);
    while($row=mysqli_fetch_array($result)){
        $ULD_ID=$row["ULD_ID"];
    }

    return $ULD_ID;
}
if(isset($_REQUEST))
{
    include "CONNECTION.php";
    include "COMMON.php";
    include "GET_USERSTAMP.php";
    $userstamp=$UserStamp;
    if($_REQUEST['option']=='TERMINATIONLB')
    {
        $active_emp=get_active_login_id();
        echo json_encode( $active_emp);
    }

    else if($_REQUEST['option']=='REJOINLB')
    {

        $active_nonemp=get_nonactive_login_id();
        echo json_encode($active_nonemp);

    }

    else if($_REQUEST['option']=='SEARCHLB')

    {
        $active_nonemp=get_nonactive_login_id();
        echo json_encode($active_nonemp);

    }
    else if($_REQUEST['option']=='FETCH')
    {
        $loginid_result = $_REQUEST['URT_SRC_loggin'];
        $query= "SELECT UA_REASON,UA_END_DATE FROM USER_ACCESS where ULD_ID =(select ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID ='".$loginid_result."')";
        $loginsearch_fetchingdata= mysqli_query($con, $query);
        $URT_SRC_values=array();
        while($row=mysqli_fetch_array($loginsearch_fetchingdata)){
            $URT_SRC_enddate=$row["UA_END_DATE"];
            $URT_SRC_reason=$row["UA_REASON"];
            $final_date = date('d-m-Y',strtotime( $URT_SRC_enddate));
            $URT_SRC_values=array('enddate'=>$final_date,'reasonn' => $URT_SRC_reason);
        }
        echo json_encode($URT_SRC_values);
    }
    else if($_REQUEST['option']=='GETDATE')
    {
        $loginid_result = $_REQUEST['URT_SRC_loggin'];
        $ULD_ID=getULD_ID_from_ULD_LOGINID1($loginid_result);
        $query= "SELECT  DATE_FORMAT(UA_JOIN_DATE,'%d-%m-%Y') as UA_JOIN_DATE  FROM USER_ACCESS where UA_REC_VER=(select MAX(UA_REC_VER) as UA_REC_VER_MAX from USER_ACCESS where ULD_ID=$ULD_ID AND UA_TERMINATE IS NULL)AND ULD_ID=$ULD_ID";
        $joindate_data= mysqli_query($con, $query);
        $URT_SRC_values=array();
        while($row=mysqli_fetch_array($joindate_data)){
            $mindate=$row["UA_JOIN_DATE"];
        }
        echo $mindate;
    }
    else if($_REQUEST['option']=='GETENDDATE')
    {
        $loginid_result = $_REQUEST['URT_SRC_loggin'];
        $ULD_ID=getULD_ID_from_ULD_LOGINID1($loginid_result);
        $query= "SELECT  DATE_FORMAT(UA_END_DATE,'%d-%m-%Y') as UA_END_DATE  FROM USER_ACCESS where UA_REC_VER=(select MAX(UA_REC_VER) as UA_REC_VER_MAX from USER_ACCESS where ULD_ID=$ULD_ID AND UA_TERMINATE IS NOT NULL)AND ULD_ID=$ULD_ID";
        $enddate_data= mysqli_query($con, $query);
        $URT_SRC_values=array();
        while($row=mysqli_fetch_array($enddate_data)){
            $mindate=$row["UA_END_DATE"];
        }
        echo $mindate;

    }
    else if($_REQUEST['option']=='UPDATE')
    {
        $reason_update=$_REQUEST['URT_SRC_ta_nreasonupdate'];
        $loggin=$_REQUEST['URT_SRC_lb_nloginupdate'];
        $date=$_REQUEST['URT_SRC_tb_ndatepickerupdate'];
        $enddate = date("Y-m-d",strtotime($date));
        $sql="UPDATE USER_ACCESS SET UA_END_DATE='$enddate',UA_REASON='$reason_update',UA_USERSTAMP='$userstamp' where ULD_ID=(SELECT ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='".$loggin."')";
        if (!mysqli_query($con,$sql)) {
            die('Error: ' . mysqli_error($con));
            $flag=0;
        }
        else{
            $flag=1;
        }
        echo $flag;
    }
    else if($_REQUEST['option']=='REJOIN')
    {
        $loggin=$_REQUEST['URT_SRC_lb_nloginrejoin'];
        $role_access = $_REQUEST['URT_SRC_radio_nrole'];
        $final_radioval=str_replace("_"," ",$role_access);
        $date=$_REQUEST['URT_SRC_tb_ndatepickerrejoin'];
        $emp_type=$_REQUEST['URSRC_lb_selectemptype'];
        $joindate = date("Y-m-d",strtotime($date));
        echo "CALL SP_TS_LOGIN_CREATION_INSERT('$loggin','$final_radioval','$joindate','$userstamp','$emp_type',@success_flag)";

        $result = $con->query("CALL SP_TS_LOGIN_CREATION_INSERT('$loggin','$final_radioval','$joindate','$userstamp','$emp_type',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
        echo $flag;
    }
    else if($_REQUEST['option']=='TERMINATE')
    {
        $reason_termin=$_POST['URT_SRC_ta_nreasontermination'];
        $loggin=$_POST['URT_SRC_lb_nloginterminate'];
        $date=$_POST['URT_SRC_tb_ndatepickertermination'];
        $enddate = date("Y-m-d",strtotime($date));
        $result = $con->query("CALL SP_TS_LOGIN_TERMINATE_SAVE('$loggin','$enddate','$reason_termin','$userstamp',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
        echo $flag;
    }

}