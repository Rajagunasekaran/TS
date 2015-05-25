<?php
/**
 * Created by PhpStorm.
 * User: SSOMENS-021
 * Date: 9/1/15
 * Time: 9:38 AM
 */
if(isset($_REQUEST)){
    include "CONNECTION.php";
    include "COMMON.php";
    include "GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
    $query="select ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID ='".$USERSTAMP."'";
    $result=mysqli_query($con,$query);
    while($row=mysqli_fetch_array($result)){
        $ULD_ID=$row["ULD_ID"];
    }
    if($_REQUEST['option']=="CONFIG_ENTRY_load_mod")
    {
        // GET ERR MSG
        $CONFIG_ENTRY_errmsg=get_error_msg('71,127,130,131');
        // CONFIGURATION LIST
        $CONFIG_ENTRY_mod = mysqli_query($con,"SELECT  DISTINCT CP.CNP_ID,CP.CNP_DATA FROM CONFIGURATION_PROFILE CP,CONFIGURATION C WHERE CP.CNP_ID=C.CNP_ID AND (C.CGN_NON_IP_FLAG is null) ORDER BY CP.CNP_DATA");
        $CONFIG_ENTRY_arr_mod=array();
        while($row=mysqli_fetch_array($CONFIG_ENTRY_mod)){
            $CONFIG_ENTRY_arr_mod[]=array($row[0],$row[1]);
        }
        $CONFIG_ENTRY_errmsg_modlist=array($CONFIG_ENTRY_errmsg,$CONFIG_ENTRY_arr_mod);
        echo JSON_ENCODE($CONFIG_ENTRY_errmsg_modlist);
    }
    if($_REQUEST['option']=="CONFIG_ENTRY_load_type")
    {
        $CONFIG_ENTRY_mod=$_REQUEST['module'];
        //CONFIG TYPE LIST
        $CONFIG_ENTRY_type = mysqli_query($con,"SELECT * FROM CONFIGURATION WHERE CNP_ID='$CONFIG_ENTRY_mod' AND (CGN_NON_IP_FLAG is null) ORDER BY CGN_TYPE ASC");
        $CONFIG_ENTRY_arr_type=array();
        while($row=mysqli_fetch_array($CONFIG_ENTRY_type)){
            $CONFIG_ENTRY_arr_type[]=array($row[0],$row[2]);
        }
        echo JSON_ENCODE($CONFIG_ENTRY_arr_type);
    }
    //SAVE CODING
    if($_REQUEST['option']=="CONFIG_ENTRY_save")
    {
        $flag=$_REQUEST['CONFIG_ENTRY_lb_module'];
//        $flag_type=$_REQUEST['CONFIG_ENTRY_lb_type'];
        $CONFIG_ENTRY_data=$_REQUEST['CONFIG_ENTRY_tb_data'];
        $CONFIG_ENTRY_type=$_REQUEST['CONFIG_ENTRY_lb_type'];
        $LAP=$_REQUEST['LN_CONFIG_ENTRY_tb_data'];
        $CHARGER=$_REQUEST['CN_CONFIG_ENTRY_tb_data'];

        $CONFIG_ENTRY_arr_config=array(4=>array("ATTENDANCE_CONFIGURATION","AC_DATA"),5=>array("PROJECT_CONFIGURATION","PC_DATA"),2=>array("REPORT_CONFIGURATION","RC_DATA"),3=>array("USER_RIGHTS_CONFIGURATION","URC_DATA"),6=>array("COMPANY_PROPERTIES","CP_LAPTOP_NUMBER","CP_CHARGER_NUMBER"));
        $sql1= "SELECT ".$CONFIG_ENTRY_arr_config[$flag][1]." FROM ".$CONFIG_ENTRY_arr_config[$flag][0]." CCN WHERE CCN.CGN_ID=(SELECT C.CGN_ID FROM CONFIGURATION C WHERE C.CGN_ID='$CONFIG_ENTRY_type') AND ".$CONFIG_ENTRY_arr_config[$flag][1]."='$CONFIG_ENTRY_data'";
        $CONFIG_ENTRY_type1 = mysqli_query($con,$sql1);
        $CONFIG_ENTRY_save=0;
        if($row=mysqli_fetch_array($CONFIG_ENTRY_type1)){
            $CONFIG_ENTRY_save= 2;
        }
        $con->autocommit(false);
        $CONFIG_ENTRY_arr=array(4=>array("attendance_configuration","AC_DATA,ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),5=>array("PROJECT_CONFIGURATION","PC_DATA,ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),2=>array("REPORT_CONFIGURATION","RC_DATA,ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),3=>array("USER_RIGHTS_CONFIGURATION","URC_DATA,URC_USERSTAMP","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"));
       if(($flag==3) && ($CONFIG_ENTRY_type==23))
       {
           $sql="INSERT INTO COMPANY_PROPERTIES (CP_LAPTOP_NUMBER,CP_CHARGER_NUMBER,ULD_ID) VALUES  ('$LAP','$CHARGER','$ULD_ID')";
       }
       elseif(($flag==3) && ($CONFIG_ENTRY_type==24))
       {
            $sql="INSERT INTO EMPLOYEE_DESIGNATION (ED_DESIGNATION,ULD_ID) VALUES ('$CONFIG_ENTRY_data','$ULD_ID')";
       }
     elseif(($flag==3) && ($CONFIG_ENTRY_type==22))
        {
            $sql="INSERT INTO USER_RIGHTS_CONFIGURATION (CGN_ID,URC_DATA,URC_USERSTAMP) VALUES ('$CONFIG_ENTRY_type','$CONFIG_ENTRY_data','$USERSTAMP')";
        }
     elseif(($flag==3) && ($CONFIG_ENTRY_type==10)){
            $sql="INSERT INTO ".$CONFIG_ENTRY_arr[$flag][0]." (CGN_ID, ".$CONFIG_ENTRY_arr[$flag][1].") VALUES ('$CONFIG_ENTRY_type', '$CONFIG_ENTRY_data', '$USERSTAMP')";
        }
       else{
            $sql="INSERT INTO ".$CONFIG_ENTRY_arr[$flag][0]." (CGN_ID, ".$CONFIG_ENTRY_arr[$flag][1].") VALUES ('$CONFIG_ENTRY_type', '$CONFIG_ENTRY_data', (SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP'))";
       }
           if($CONFIG_ENTRY_save!=2){
            if (!mysqli_query($con,$sql)) {
                die('Error: ' . mysqli_error($con));
                $CONFIG_ENTRY_save=4;
            }
            else{
                $CONFIG_ENTRY_save=1;
            }
            $con->commit();
        }
        echo $CONFIG_ENTRY_save;
    }
    //CHECK DUPLICATE DATA
    if($_REQUEST['option']=="CONFIG_ENTRY_check_data")
    {
        $CONFIG_ENTRY_arr_config=array(4=>array("ATTENDANCE_CONFIGURATION","AC_DATA"),5=>array("PROJECT_CONFIGURATION","PC_DATA"),2=>array("REPORT_CONFIGURATION","RC_DATA"),3=>array("USER_RIGHTS_CONFIGURATION","URC_DATA"));
        $flag=$_REQUEST['CONFIG_ENTRY_lb_module'];
        $CONFIG_ENTRY_type=$_REQUEST['CONFIG_ENTRY_lb_type'];
        $CONFIG_ENTRY_data=$_REQUEST['CONFIG_ENTRY_tb_data'];
        $sql= "SELECT ".$CONFIG_ENTRY_arr_config[$flag][1]." FROM ".$CONFIG_ENTRY_arr_config[$flag][0]." CCN WHERE CCN.CGN_ID=(SELECT C.CGN_ID FROM CONFIGURATION C WHERE C.CGN_ID='$CONFIG_ENTRY_type') AND ".$CONFIG_ENTRY_arr_config[$flag][1]."='$CONFIG_ENTRY_data'";
        $CONFIG_ENTRY_type = mysqli_query($con,$sql);
        $CONFIG_ENTRY_data_flag=0;
        if($row=mysqli_fetch_array($CONFIG_ENTRY_type)){
            $CONFIG_ENTRY_data_flag=1;
        }
        echo $CONFIG_ENTRY_data_flag;
    }
}