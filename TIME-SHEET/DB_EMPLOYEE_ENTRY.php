<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE DETAIL ENTRY*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:02/10/2014 ED:06/10/2014,TRACKER NO:79
//*********************************************************************************************************//
error_reporting(0);
if(isset($_REQUEST)){
    include "CONNECTION.php";
    include "COMMON.php";
    include "GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
//FETCHING INITIAL DATAS
    if($_REQUEST['option']=="INITIAL_DATAS"){
        $EMP_ENTRY_errmsg=get_error_msg_arry();
        $get_loginid_array=array();
        $get_loginid_array_result=mysqli_query($con,"SELECT ULD_LOGINID from VW_ACCESS_RIGHTS_TERMINATE_LOGINID where URC_DATA!='SUPER ADMIN' AND ULD_LOGINID IN (SELECT ULD_LOGINID FROM USER_LOGIN_DETAILS WHERE ULD_ID NOT IN (SELECT ULD_ID FROM EMPLOYEE_DETAILS)) ORDER BY ULD_LOGINID");
        while($row=mysqli_fetch_array($get_loginid_array_result))
        {
            $get_loginid_array[]=$row["ULD_LOGINID"];
        }
        $EMP_ENTRY_errmsg=get_error_msg_arry();
        $final_values=array($EMP_ENTRY_errmsg,$get_loginid_array);
        echo json_encode($final_values);
    }
//FUNCTION FOR TO SAVE THE EMPLOYEE DETAILS ND COMPANY DETAILS
    if($_REQUEST['option']=="EMPLOYDETAILS_SAVE"){
        $EMP_ENTRY_loginid=$_POST['EMP_ENTRY_tb_loginid'];
        $EMP_ENTRY_firstname=$_POST['EMP_ENTRY_tb_firstname'];
        $EMP_ENTRY_lastname=$_POST['EMP_ENTRY_tb_lastname'];
        $EMP_ENTRY_dob=$_POST['EMP_ENTRY_tb_dob'];
        $EMP_ENTRY_finaldob = date('Y-m-d',strtotime($EMP_ENTRY_dob));
        $EMP_ENTRY_designation=$_POST['EMP_ENTRY_tb_designation'];
        $EMP_ENTRY_Mobileno=$_POST['EMP_ENTRY_tb_permobile'];
        $EMP_ENTRY_kinname=$_POST['EMP_ENTRY_tb_kinname'];
        $EMP_ENTRY_relationhd=$_POST['EMP_ENTRY_tb_relationhd'];
        $EMP_ENTRY_mobile=$_POST['EMP_ENTRY_tb_mobile'];
        $EMP_ENTRY_laptopno=$_POST['EMP_ENTRY_tb_laptopno'];
        $EMP_ENTRY_chrgrno=$_POST['EMP_ENTRY_tb_chargerno'];
        $EMP_ENTRY_bag=$_POST['EMP_ENTRY_chk_bag'];
        if($EMP_ENTRY_bag=='on')
        {
            $EMP_ENTRY_bag= 'X';
        }
        else
        {
            $EMP_ENTRY_bag='';
        }
        $EMP_ENTRY_mouse=$_POST['EMP_ENTRY_chk_mouse'];
        if($EMP_ENTRY_mouse=='on')
        {
            $EMP_ENTRY_mouse= 'X';
        }
        else
        {
            $EMP_ENTRY_mouse='';
        }
        $EMP_ENTRY_dooracess=$_POST['EMP_ENTRY_chk_dracess'];
        if($EMP_ENTRY_dooracess=='on')
        {
            $EMP_ENTRY_dooracess= 'X';
        }
        else
        {
            $EMP_ENTRY_dooracess='';
        }
        $EMP_ENTRY_idcard=$_POST['EMP_ENTRY_chk_idcrd'];
        if($EMP_ENTRY_idcard=='on')
        {
            $EMP_ENTRY_idcard= 'X';
        }
        else
        {
            $EMP_ENTRY_idcard='';
        }
        $EMP_ENTRY_headset=$_POST['EMP_ENTRY_chk_headset'];
        if($EMP_ENTRY_headset=='on')
        {
            $EMP_ENTRY_headset= 'X';
        }
        else
        {
            $EMP_ENTRY_headset='';
        }

        $result = $con->query("CALL SP_TS_EMPLOYEE_AND_COMPANY_PROPERTIES_DETAILS_INSERT('$EMP_ENTRY_loginid','$EMP_ENTRY_firstname','$EMP_ENTRY_lastname','$EMP_ENTRY_finaldob','$EMP_ENTRY_designation','$EMP_ENTRY_Mobileno','$EMP_ENTRY_kinname','$EMP_ENTRY_relationhd','$EMP_ENTRY_mobile','$EMP_ENTRY_laptopno','$EMP_ENTRY_chrgrno','$EMP_ENTRY_bag','$EMP_ENTRY_mouse','$EMP_ENTRY_dooracess','$EMP_ENTRY_idcard','$EMP_ENTRY_headset','$USERSTAMP',@EMP_INSERT_FLAG)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @EMP_INSERT_FLAG');
        $result = $select->fetch_assoc();
        $return_flag= $result['@EMP_INSERT_FLAG'];
        echo $return_flag;
    }
}
//GET ERR MSG
function get_error_msg_arry(){
    global $con;
    $errormessages=array();
    $errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (1,2,69,70,71,72)");
    while($row=mysqli_fetch_array($errormsg)){
        $errormessages[]=$row["EMC_DATA"];
    }
    return $errormessages;
}
?>
