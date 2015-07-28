<?php
if(isset($_REQUEST)){
    include "../TSLIB/TSLIB_CONNECTION.php";
    include "../TSLIB/TSLIB_GET_USERSTAMP.php";
    include "../TSLIB/TSLIB_COMMON.php";
    $USERSTAMP=$UserStamp;

    if($_REQUEST['option']=='SAVE')
    {

        $date= date('Y-m-d',strtotime($_POST['CC_tb_dates']));
        $clientname=$_POST['CC_tb_name'];
        $mailid=$_POST['CC_tb_mail'];
        $contactno=$_POST['CC_tb_contact'];
        $address=$_POST['CC_tb_address'];
        $projectname=$_POST['CC_tb_pname'];
        $time=$_POST['CC_tb_ctime'];
        $comments=$_POST['CC_tb_cname'];
        $checked=$_POST['EMP_ID'];
        $query="select ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID ='$USERSTAMP'";
        $result=mysqli_query($con,$query);
        while($row=mysqli_fetch_array($result)){
            $rep_getuld_id=$row["ULD_ID"];
        }
        $query="SELECT EMP_ID FROM EMPLOYEE_DETAILS WHERE ULD_ID=(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')";
        $result=mysqli_query($con,$query);
        while($row=mysqli_fetch_array($result)){
            $rep_getuld_id=$row["EMP_ID"];
        }

//        echo("CALL SP_CLIENT_CONTACT_DETAILS_INSERT('1',NULL,'$date','$clientname','$mailid','$contactno','$address','$projectname','$time','$comments','$checked','$USERSTAMP',@FLAG)");exit;
        $result = $con->query("CALL SP_CLIENT_CONTACT_DETAILS_INSERT('1',NULL,'$date','$clientname','$mailid','$contactno','$address','$projectname','$time','$comments','$checked','$USERSTAMP',@FLAG)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @flag');
        $result = $select->fetch_assoc();
        $flag= $result['@flag'];
        echo $flag;
    }
    if($_REQUEST['option']=='error')
    {
        $CC_error=get_error_msg('3','7');
        $final_array=array($CC_error);
        echo json_encode($final_array);
    }
}
?>
