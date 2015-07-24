<?php
set_include_path( get_include_path() . PATH_SEPARATOR . 'google-api-php-client-master/src' );
//require_once 'google/appengine/api/mail/Message.php';
require_once ('google-api-php-client-master/src/Google/Client.php');
include 'google-api-php-client-master/src/Google/Service/Drive.php';
include 'google-api-php-client-master/src/Google/Service/Calendar.php';
//use google\appengine\api\mail\Message;
include "../TSLIB/TSLIB_CONNECTION.php";
include '../TSLIB/TSLIB_GET_USERSTAMP.php';
include "../TSLIB/TSLIB_COMMON.php";
$USERSTAMP=$UserStamp;
global $con;
if(isset($_REQUEST)){
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
        $CONFIG_ENTRY_data=$_REQUEST['CONFIG_ENTRY_tb_data'];
        $CONFIG_ENTRY_type=$_REQUEST['CONFIG_ENTRY_lb_type'];
        $CONFIG_ENTRY_laptop=$_REQUEST['LN_CONFIG_ENTRY_tb_data'];
        $CONFIG_ENTRY_charger=$_REQUEST['CN_CONFIG_ENTRY_tb_data'];
        $CONFIG_ENTRY_battery=$_REQUEST['BN_CONFIG_ENTRY_tb_data'];
        $CONFIG_ENTRY_lapbag=$_REQUEST['LBN_CONFIG_ENTRY_tb_data'];
        $CONFIG_ENTRY_mouse=$_REQUEST['MN_CONFIG_ENTRY_tb_data'];

        $CONFIG_ENTRY_arr_config=array(4=>array("ATTENDANCE_CONFIGURATION","AC_DATA"),5=>array("PROJECT_CONFIGURATION","PC_DATA"),2=>array("REPORT_CONFIGURATION","RC_DATA"),3=>array("USER_RIGHTS_CONFIGURATION","URC_DATA"),6=>array("COMPANY_PROPERTIES","CP_LAPTOP_NUMBER,CP_CHARGER_NUMBER,CP_BATTERY_SERIAL_NUMBER,CP_LAPTOP_BAG_NUMBER,CP_MOUSE_NUMBER,ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"));

        $sql1= "SELECT ".$CONFIG_ENTRY_arr_config[$flag][1]." FROM ".$CONFIG_ENTRY_arr_config[$flag][0]." CCN WHERE CCN.CGN_ID=(SELECT C.CGN_ID FROM CONFIGURATION C WHERE C.CGN_ID='$CONFIG_ENTRY_type') AND ".$CONFIG_ENTRY_arr_config[$flag][1]."='$CONFIG_ENTRY_data'";


        $CONFIG_ENTRY_type1 = mysqli_query($con,$sql1);
        $CONFIG_ENTRY_save=0;
        if($row=mysqli_fetch_array($CONFIG_ENTRY_type1)){
            $CONFIG_ENTRY_save= 2;
        }
        $con->autocommit(false);
        $CONFIG_ENTRY_arr=array(4=>array("attendance_configuration","AC_DATA,ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),5=>array("PROJECT_CONFIGURATION","PC_DATA,ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),2=>array("REPORT_CONFIGURATION","RC_DATA,ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),3=>array("USER_RIGHTS_CONFIGURATION","URC_DATA,URC_USERSTAMP","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),6=>array("COMPANY_PROPERTIES","CP_LAPTOP_NUMBER,CP_CHARGER_NUMBER,CP_BATTERY_SERIAL_NUMBER,CP_LAPTOP_BAG_NUMBER,CP_MOUSE_NUMBER,ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"));
        if($flag==3 && $CONFIG_ENTRY_type!=23)
        {

            $sql="INSERT INTO ".$CONFIG_ENTRY_arr[$flag][0]." (CGN_ID, ".$CONFIG_ENTRY_arr[$flag][1].") VALUES ('$CONFIG_ENTRY_type', '$CONFIG_ENTRY_data', '$USERSTAMP')";
        }
        if($flag==3 && $CONFIG_ENTRY_type==23)
        {
            $sql="INSERT INTO COMPANY_PROPERTIES(CP_LAPTOP_NUMBER,CP_CHARGER_NUMBER,CP_BATTERY_SERIAL_NUMBER,CP_LAPTOP_BAG_NUMBER,CP_MOUSE_NUMBER,ULD_ID) VALUES('$CONFIG_ENTRY_laptop','$CONFIG_ENTRY_charger','$CONFIG_ENTRY_battery','$CONFIG_ENTRY_lapbag','$CONFIG_ENTRY_mouse',(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP'))";
            $CONFIG_ENTRY_save= 1;

        }
        else

            $sql="INSERT INTO ".$CONFIG_ENTRY_arr[$flag][0]." (CGN_ID, ".$CONFIG_ENTRY_arr[$flag][1].") VALUES ('$CONFIG_ENTRY_type', '$CONFIG_ENTRY_data', (SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP'))";
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

//search start
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_load_mod")
    {
        // GET ERR MSG
        $CONFIG_SRCH_UPD_errmsg=get_error_msg('17,60,113,125,126,127,128,129,131,132');
        // CONFIGURATION LIST
        $CONFIG_SRCH_UPD_mod = mysqli_query($con,"SELECT  DISTINCT CP.CNP_ID,CP.CNP_DATA FROM CONFIGURATION_PROFILE CP,CONFIGURATION C WHERE CP.CNP_ID=C.CNP_ID AND (C.CGN_NON_IP_FLAG is null) ORDER BY CP.CNP_DATA");
        $CONFIG_SRCH_UPD_arr_mod=array();
        while($row=mysqli_fetch_array($CONFIG_SRCH_UPD_mod)){
            $CONFIG_SRCH_UPD_arr_mod[]=array($row[0],$row[1]);
        }
        $CONFIG_SRCH_UPD_errmsg_modlist=array($CONFIG_SRCH_UPD_errmsg,$CONFIG_SRCH_UPD_arr_mod);
        echo JSON_ENCODE($CONFIG_SRCH_UPD_errmsg_modlist);
    }
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_load_type")
    {

        $CONFIG_SRCH_UPD_mod=$_REQUEST['module'];
        //CONFIG TYPE LIST
        $CONFIG_SRCH_UPD_type = mysqli_query($con,"SELECT * FROM CONFIGURATION WHERE CNP_ID='$CONFIG_SRCH_UPD_mod' AND (CGN_NON_IP_FLAG != 'XX' or CGN_NON_IP_FLAG is null)  ORDER BY CGN_TYPE ASC");
        $CONFIG_SRCH_UPD_arr_type=array();
        while($row=mysqli_fetch_array($CONFIG_SRCH_UPD_type)){
            $CONFIG_SRCH_UPD_arr_type[]=array($row[0],$row[2]);
        }
        echo JSON_ENCODE($CONFIG_SRCH_UPD_arr_type);
    }
//LOAD DATA FOR FLEX TABLE
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_load_data")
    {

        $flag=$_REQUEST['CONFIG_SRCH_UPD_lb_module'];
        $CONFIG_SRCH_UPD_data=$_REQUEST['CONFIG_SRCH_UPD_tb_data'];
        $CONFIG_SRCH_UPD_type=$_REQUEST['CONFIG_SRCH_UPD_lb_type'];
        $arrTableWidth=array(3=>1400,2=>1400,4=>1400,5=>1400);
        $arrHeaderWidth=array(3=>array(500),5=>array(100));
        $CONFIG_SRCH_UPD_arr_data=array(4=>array("ATTENDANCE_CONFIGURATION","AC_DATA","DT.AC_ID,DT.AC_DATA,DT.AC_INITIALIZE_FLAG,ULD.ULD_LOGINID,DATE_FORMAT(CONVERT_TZ(DT.AC_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T')"),5=>array("PROJECT_CONFIGURATION","PC_DATA","DT.PC_ID,DT.PC_DATA,DT.PC_INITIALIZE_FLAG,ULD.ULD_LOGINID,DATE_FORMAT(CONVERT_TZ(DT.PC_TIMESTAMP,'+00:00','+05:30'),'%d-%m-%Y %T')"),2=>array("REPORT_CONFIGURATION","RC_DATA","DT.RC_ID,DT.RC_DATA,DT.RC_INITIALIZE_FLAG,ULD.ULD_LOGINID,DATE_FORMAT(CONVERT_TZ(DT.RC_TIMESTAMP,'+00:00','+05:30'),'%d-%m-%Y %T')"),3=>array("USER_RIGHTS_CONFIGURATION","URC_DATA","DT.URC_ID,DT.URC_DATA,DT.URC_INITIALIZE_FLAG,DT.URC_USERSTAMP,DATE_FORMAT(CONVERT_TZ(DT.URC_TIMESTAMP,'+00:00','+05:30'),'%d-%m-%Y %T')"));

        if(($flag==3) && ($CONFIG_SRCH_UPD_type==23))
        {
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con,"SELECT CP.CP_ID,CP.CP_LAPTOP_NUMBER, CP.CP_CHARGER_NUMBER,CP.CP_BATTERY_SERIAL_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,ULD.ULD_LOGINID,DATE_FORMAT(CONVERT_TZ(CP.CP_TIMESTAMP,'+00:00','+05:30'),'%d-%m-%Y %T') AS TIMESTAMP FROM COMPANY_PROPERTIES CP, USER_LOGIN_DETAILS ULD WHERE CP.ULD_ID=ULD.ULD_ID  ORDER BY CP.CP_LAPTOP_NUMBER  ASC");
        }
        elseif(($flag==3) && ($CONFIG_SRCH_UPD_type==24))
        {
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con,"SELECT ED.ED_ID,ED.ED_DESIGNATION,ULD.ULD_LOGINID,DATE_FORMAT(CONVERT_TZ(ED.ED_TIMESTAMP,'+00:00','+05:30'),'%d-%m-%Y %T') AS TIMESTAMP FROM EMPLOYEE_DESIGNATION ED,USER_LOGIN_DETAILS ULD WHERE ED.ULD_ID=ULD.ULD_ID");
        }
        elseif($flag==3){
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con, "SELECT ". $CONFIG_SRCH_UPD_arr_data[$flag][2]. " AS TIMESTAMP FROM ". $CONFIG_SRCH_UPD_arr_data[$flag][0]. " DT,CONFIGURATION C,CONFIGURATION_PROFILE CP WHERE  CP.CNP_ID='$flag' AND DT.CGN_ID=C.CGN_ID AND C.CGN_ID= '$CONFIG_SRCH_UPD_type' ORDER BY DT. ". $CONFIG_SRCH_UPD_arr_data[$flag][1]. " ASC");
        }
        else{
            $CONFIG_SRCH_UPD_sql_data = mysqli_query($con, "SELECT ". $CONFIG_SRCH_UPD_arr_data[$flag][2]. " AS TIMESTAMP FROM ". $CONFIG_SRCH_UPD_arr_data[$flag][0]. " DT,CONFIGURATION C,CONFIGURATION_PROFILE CP,USER_LOGIN_DETAILS ULD WHERE  ULD.ULD_ID=DT.ULD_ID AND CP.CNP_ID='$flag' AND DT.CGN_ID=C.CGN_ID AND C.CGN_ID= '$CONFIG_SRCH_UPD_type' ORDER BY DT. ". $CONFIG_SRCH_UPD_arr_data[$flag][1]. " ASC");
        }

        if($CONFIG_SRCH_UPD_type==23)
            $appendTable="<br><div id='CONFIG_SRCH_UPD_div_errmsg'></div><br><table id='CONFIG_SRCH_UPD_tble_config' border=1 cellspacing='0' class='srcresult'><thead  bgcolor='#6495ed' style='color:white'><tr class='head'><th>LAPTOP NUMBER</th><th>CHARGER NUMBER</th><th>BATTERY NUMBER</th><th>LAPBAG NUMBER</th><th>MOUSE NUMBER</th><th>USERSTAMP</th><th>TIMESTAMP</th></tr></thead><tbody>";
        else
            $appendTable="<br><div id='CONFIG_SRCH_UPD_div_errmsg'></div><br><table id='CONFIG_SRCH_UPD_tble_config' border=1 cellspacing='0' class='srcresult'><thead  bgcolor='#6495ed' style='color:white'><tr class='head'><th>DATA</th><th>USERSTAMP</th><th>TIMESTAMP</th><th>EDIT/UPDATE/DELETE</th></tr></thead><tbody>";
        while($row=mysqli_fetch_array($CONFIG_SRCH_UPD_sql_data)){
            $appendTable .='<tr  id='.$row[0].'><td id='.'CONFIG_'.$row[0].'>'.$row[1].'</td>';
            if(($CONFIG_SRCH_UPD_type==23)){
                for($x = 2; $x < 8; $x++) {
                    $appendTable .="<td width='".$arrHeaderWidth[$flag][$x]."px'  >".$row[$x]."</td>";
                }
            }
            elseif(($CONFIG_SRCH_UPD_type==24)){
                for($x = 2; $x < 4; $x++) {
                    $appendTable .="<td width='".$arrHeaderWidth[$flag][$x]."px'  >".$row[$x]."</td>";
                }
            }
            else{
                for($x = 3; $x < 5; $x++) {
                    $appendTable .="<td width='".$arrHeaderWidth[$flag][$x]."px'  >".$row[$x]."</td>";
                }
            }
            if(($CONFIG_SRCH_UPD_type==23)){

            }
            else{
                if($row[2]=='X')
                {
                    $deleteoption='<input type="button"  id="edit"  class="edit msgbtn nondelete" value="EDIT"><input type="button"  id="cancl" class="cancl btn" value="CANCEL">';

                }
                else{
                    $deleteoption='<input type="button"  id="edit"  class="edit msgbtn deletion" value="EDIT"><input type="button"  id="cancel" class="delete btn" value="DELETE">';
//                $deleteoption='<input type="button"  id="edit" style="max-width: 60px!important;" class="edit msgbtn deletion" value="EDIT"><input type="button" style="max-width: 90px!important; text-align: center!important; " id="cancel" class="delete btn" value="DELETE">';
                }
                $appendTable .='<td align="center">'.$deleteoption.'</td></tr>';
            }
        }
        $appendTable .='</tbody></table>';
        echo JSON_ENCODE($appendTable);
    }
//UPDATE CODING
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_save")
    {
        $ff=1;
        $flag=$_REQUEST['CONFIG_SRCH_UPD_lb_module'];
        $CONFIG_SRCH_UPD_data=$_REQUEST['CONFIG_SRCH_UPD_tb_data'];
        $CONFIG_SRCH_UPD_type=$_REQUEST['CONFIG_SRCH_UPD_lb_type'];
        $CONFIG_SRCH_UPD_id=$_REQUEST['CONFIG_SRCH_UPD_id'];
        $CONFIG_SRCH_UPD_arr_config=array(4=>array("ATTENDANCE_CONFIGURATION","AC_DATA"),5=>array("PROJECT_CONFIGURATION","PC_DATA"),2=>array("REPORT_CONFIGURATION","RC_DATA"),3=>array("USER_RIGHTS_CONFIGURATION","URC_DATA"));
        $sql1= "SELECT ".$CONFIG_SRCH_UPD_arr_config[$flag][1]." FROM ".$CONFIG_SRCH_UPD_arr_config[$flag][0]." CCN WHERE CCN.CGN_ID=(SELECT C.CGN_ID FROM CONFIGURATION C WHERE C.CGN_ID='$CONFIG_SRCH_UPD_type') AND ".$CONFIG_SRCH_UPD_arr_config[$flag][1]."='$CONFIG_SRCH_UPD_data'";
        $CONFIG_SRCH_UPD_type1 = mysqli_query($con,$sql1);
        $CONFIG_SRCH_UPD_save=0;
        //SERVICE
        $drive = new Google_Client();
        $Client=get_servicedata();
        $ClientId=$Client[0];
        $ClientSecret=$Client[1];
        $RedirectUri=$Client[2];
        $DriveScopes=$Client[3];
        $CalenderScopes=$Client[4];
        $Refresh_Token=$Client[5];
        $drive->setClientId($ClientId);
        $drive->setClientSecret($ClientSecret);
        $drive->setRedirectUri($RedirectUri);
        $drive->setScopes(array($DriveScopes,$CalenderScopes));
        $drive->setAccessType('online');
        $authUrl = $drive->createAuthUrl();
        $refresh_token= $Refresh_Token;
        $drive->refreshToken($refresh_token);
        $service = new Google_Service_Drive($drive);
        //SERVICE
        $select_document_owner=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=14");
        if($row=mysqli_fetch_array($select_document_owner)){
            $ss_document_owner=$row["URC_DATA"];
        }
//SHARIN FUNCTION PART
        if($CONFIG_SRCH_UPD_type==13){
            $service = new Google_Service_Calendar($drive);
            try{
                $acl = $service->acl->listAcl($CONFIG_SRCH_UPD_data);
                $ff=1;
            }
            catch (Exception $e){
                $ff=0;
            }
            if($ff==1){
                $select_calenderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=10");
                if($row=mysqli_fetch_array($select_calenderid)){
                    $oldcalenderid=$row["URC_DATA"];
                }
                try{
                    $acl = $service->acl->listAcl($oldcalenderid);
                    foreach ($acl->getItems() as $rule) {

                        $emailadrress[]=$rule->id;
                        $role[]=$rule->role;
                    }
                }
                catch (Exception $e){
                    $ff=0;
                }
                for($x=0;$x<=count($emailadrress);$x++){
                    if($role[$x]=='reader'){
                        $email=explode(":", $emailadrress[$x]);
                        $email=$email[1];
                        $rule = new Google_Service_Calendar_AclRule();
                        $scope = new Google_Service_Calendar_AclRuleScope();
                        $scope->setType("user");
                        $scope->setValue($email);
                        $rule->setScope($scope);
                        $rule->setRole("none");
                        try{
                            $createdRule = $service->acl->insert($oldcalenderid, $rule);
                        }
                        catch(Exception $e){
                            echo $e;
                        }
                    }
                }
                for($x=0;$x<=count($emailadrress);$x++){
                    if($role[$x]=='reader'){
                        $email=explode(":",$emailadrress[$x]);
                        $email=$email[1];
                        $rule = new Google_Service_Calendar_AclRule();
                        $scope = new Google_Service_Calendar_AclRuleScope();
                        $scope->setType("user");
                        $scope->setValue($email);
                        $rule->setScope($scope);
                        $rule->setRole($role[$x]);
                        $createdRule = $service->acl->insert($CONFIG_SRCH_UPD_data, $rule);
                    }
                }
            }
        }
//        url id
        if($CONFIG_SRCH_UPD_type==9)
        {
            $new_url=$CONFIG_SRCH_UPD_data;
            if($new_url!=null){
                $new_url_id =explode("/", $new_url);
                $new_fileId=$new_url_id[7];
                $file_id=$new_fileId;
            }
            try{
                $file = $service->files->get($new_fileId);
                $ff=1;
            }
            catch (Exception $e) {
                $ff=0;
            }
            if($ff==1){
                $select_fileid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=5");
                if($row=mysqli_fetch_array($select_fileid)){
                    $ss_fileid=$row["URC_DATA"];
                }
                $url=$ss_fileid;
                if($url!=null){
                    $url_id =explode("/", $url);
                    $fileId=$url_id[7];
                    $file_id=$fileId;
                }
                try {
                    $permissions = $service->permissions->listPermissions($fileId);
                    $return_value= $permissions->getItems();
                } catch (Exception $e) {
                    $ff=0;
                }
                $permission_id=array();
                foreach ($return_value as $key => $value) {
                    $permission_id[]=$value->id;
                    $emailadrress[]=$value->emailAddress;
                    $role_array[]=$value->role;
//    $value->
                }
                for($y=0;$y<=count($permission_id);$y++){
                    if($permission_id!=''){
                        if($permission_id[$y]!=''){
                            try {
                                if($role_array[$y]!='owner'){
                                    $service->permissions->delete($fileId, $permission_id[$y]);
                                    $ff=1;
                                }
                            } catch (Exception $e) {
                                $ff=0;
                            }
                        }
                    }
                }
                for($k=0;$k<=count($emailadrress);$k++)
                {
//                    if($role_array[$k]!='owner')
                    shareDocument($service,$emailadrress[$k],$role_array[$k],$new_fileId);
                }
//                shareDocument($service,$ss_document_owner,'owner',$new_fileId);
            }
        }
        //file id
        if($CONFIG_SRCH_UPD_type==12||$CONFIG_SRCH_UPD_type==17 )
        {
            $file_id=$CONFIG_SRCH_UPD_data;
            try{
                $file = $service->files->get($CONFIG_SRCH_UPD_data);
                $url_link=$file->getDefaultOpenWithLink();
                $ff=1;
            }
            catch (Exception $e) {
                $ff=0;
            }
            if($ff==1){
                if($CONFIG_SRCH_UPD_type==12){
                    $select_fileid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=9");
                    if($row=mysqli_fetch_array($select_fileid)){
                        $ss_fileid=$row["URC_DATA"];
                    }
                }
                else if($CONFIG_SRCH_UPD_type==17){
                    $select_fileid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=13");
                    if($row=mysqli_fetch_array($select_fileid)){
                        $ss_fileid=$row["URC_DATA"];
                    }
                }
                $fileId=$ss_fileid;
                $file_id=$fileId;
                try {
                    $permissions = $service->permissions->listPermissions($fileId);
                    $return_value= $permissions->getItems();
                } catch (Exception $e) {
                    $ff=0;
                }
                $permission_id=array();
                $emailadrress=array();
                $role_array=array();
                foreach ($return_value as $key => $value) {
                    $permission_id[]=$value->id;
                    $emailadrress[]=$value->emailAddress;
                    $role_array[]=$value->role;
//    $value->
                }
                for($y=0;$y<=count($permission_id);$y++){
                    if($permission_id[$y]!=''){
                        try {
                            if($role_array[$y]!='owner'){
                                $service->permissions->delete($fileId, $permission_id[$y]);
                                $ff=1;
                            }
                        } catch (Exception $e) {
                            $ff=0;
                        }
                    }
                }
                for($k=0;$k<=count($emailadrress);$k++)
                {
//                    if($role_array[$k]!='owner')
                    shareDocument($service,$emailadrress[$k],$role_array[$k],$CONFIG_SRCH_UPD_data);

                }
//                shareDocument($service,$ss_document_owner,'owner',$CONFIG_SRCH_UPD_data);
            }
        }
        //COMMON
        if($row=mysqli_fetch_array($CONFIG_SRCH_UPD_type1)){
            $CONFIG_SRCH_UPD_save= 2;
        }
        $con->autocommit(false);
        $CONFIG_SRCH_UPD_arr=array(4=>array("attendance_configuration","AC_ID","AC_DATA","ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),5=>array("PROJECT_CONFIGURATION","PC_ID","PC_DATA","ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),2=>array("REPORT_CONFIGURATION","RC_ID","RC_DATA","ULD_ID","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"),3=>array("USER_RIGHTS_CONFIGURATION","URC_ID","URC_DATA","URC_USERSTAMP","(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP')"));
        if($flag==3){
            if((($ff==1)&&($CONFIG_SRCH_UPD_type==12))){

                $sql="UPDATE ".$CONFIG_SRCH_UPD_arr[$flag][0]." SET ".$CONFIG_SRCH_UPD_arr[$flag][2]."= '$CONFIG_SRCH_UPD_data',".$CONFIG_SRCH_UPD_arr[$flag][3]."='$USERSTAMP' WHERE ".$CONFIG_SRCH_UPD_arr[$flag][1]."=".$CONFIG_SRCH_UPD_id;
                $sql1="UPDATE ".$CONFIG_SRCH_UPD_arr[$flag][0]." SET ".$CONFIG_SRCH_UPD_arr[$flag][2]."= '$url_link',".$CONFIG_SRCH_UPD_arr[$flag][3]."='$USERSTAMP' WHERE ".$CONFIG_SRCH_UPD_arr[$flag][1]."=5";
            }
            else if((($CONFIG_SRCH_UPD_type==9)&&($ff==1) )){
                $sql="UPDATE ".$CONFIG_SRCH_UPD_arr[$flag][0]." SET ".$CONFIG_SRCH_UPD_arr[$flag][2]."= '$CONFIG_SRCH_UPD_data',".$CONFIG_SRCH_UPD_arr[$flag][3]."='$USERSTAMP' WHERE ".$CONFIG_SRCH_UPD_arr[$flag][1]."=".$CONFIG_SRCH_UPD_id;
                $sql1="UPDATE ".$CONFIG_SRCH_UPD_arr[$flag][0]." SET ".$CONFIG_SRCH_UPD_arr[$flag][2]."= '$new_fileId',".$CONFIG_SRCH_UPD_arr[$flag][3]."='$USERSTAMP' WHERE ".$CONFIG_SRCH_UPD_arr[$flag][1]."=9";


            }else if((($ff==1)&&($CONFIG_SRCH_UPD_type==17))){

                $sql="UPDATE ".$CONFIG_SRCH_UPD_arr[$flag][0]." SET ".$CONFIG_SRCH_UPD_arr[$flag][2]."= '$CONFIG_SRCH_UPD_data',".$CONFIG_SRCH_UPD_arr[$flag][3]."='$USERSTAMP' WHERE ".$CONFIG_SRCH_UPD_arr[$flag][1]."=".$CONFIG_SRCH_UPD_id;
            }
            else if(($ff==1)&&($CONFIG_SRCH_UPD_type==13)){

                $sql="UPDATE ".$CONFIG_SRCH_UPD_arr[$flag][0]." SET ".$CONFIG_SRCH_UPD_arr[$flag][2]."= '$CONFIG_SRCH_UPD_data',".$CONFIG_SRCH_UPD_arr[$flag][3]."='$USERSTAMP' WHERE ".$CONFIG_SRCH_UPD_arr[$flag][1]."=".$CONFIG_SRCH_UPD_id;
            }
            else if(($ff==1)&&($CONFIG_SRCH_UPD_type==24)){
//                echo "UPDATE EMPLOYEE_DESIGNATION SET ED_DESIGNATION"."= '$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP') WHERE ED_ID=".$CONFIG_SRCH_UPD_id;
                $sql="UPDATE EMPLOYEE_DESIGNATION SET ED_DESIGNATION"."= '$CONFIG_SRCH_UPD_data',ULD_ID=(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP') WHERE ED_ID=".$CONFIG_SRCH_UPD_id;
            }
            else if($CONFIG_SRCH_UPD_type!=12 || $CONFIG_SRCH_UPD_type!=9 ||$CONFIG_SRCH_UPD_type!=17 || $CONFIG_SRCH_UPD_type!=13){

                $sql="UPDATE ".$CONFIG_SRCH_UPD_arr[$flag][0]." SET ".$CONFIG_SRCH_UPD_arr[$flag][2]."= '$CONFIG_SRCH_UPD_data',".$CONFIG_SRCH_UPD_arr[$flag][3]."='$USERSTAMP' WHERE ".$CONFIG_SRCH_UPD_arr[$flag][1]."=".$CONFIG_SRCH_UPD_id;
            }

        }
        else{
            $sql="UPDATE ".$CONFIG_SRCH_UPD_arr[$flag][0]." SET ".$CONFIG_SRCH_UPD_arr[$flag][2]."= '$CONFIG_SRCH_UPD_data',".$CONFIG_SRCH_UPD_arr[$flag][3]."=(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP') WHERE ".$CONFIG_SRCH_UPD_arr[$flag][1]."=".$CONFIG_SRCH_UPD_id;
        }

        if($ff==1){
            if($CONFIG_SRCH_UPD_save!=2){
                if (!mysqli_query($con,$sql)) {
                    die('Error: ' . mysqli_error($con));
                    $CONFIG_SRCH_UPD_save=4;
                }
                else{
                    $CONFIG_SRCH_UPD_save=1;
                }
                if($CONFIG_SRCH_UPD_type==12||$CONFIG_SRCH_UPD_type==9){
                    if (!mysqli_query($con,$sql1)) {
                        die('Error: ' . mysqli_error($con));
                    }
                }
                $con->commit();
            }
        }
        $final_array=[$CONFIG_SRCH_UPD_save,$ff,$file_id];
        echo json_encode($final_array);
    }
//UPDATE DATA FOR EMAIL TEMPLATE TABLE
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_delete"){
        $flag=$_REQUEST['CONFIG_SRCH_UPD_lb_module'];
        $CONFIG_SRCH_UPD_data=$_REQUEST['CONFIG_SRCH_UPD_tb_data'];
        $CONFIG_SRCH_UPD_id=$_REQUEST['CONFIG_SRCH_UPD_id'];
        $CONFIG_SRCH_UPD_arr_delete_data=array(4=>array(17,"AC_DATA","DT.AC_ID,DT.AC_DATA,ULD.ULD_LOGINID,DATE_FORMAT(DT.AC_TIMESTAMP,'%d-%m-%Y %T')"),5=>array(22,"PC_DATA","DT.PC_ID,DT.PC_DATA,ULD.ULD_LOGINID,DATE_FORMAT(DT.PC_TIMESTAMP,'%d-%m-%Y %T')"),2=>array(16,"RC_DATA","DT.RC_ID,DT.RC_DATA,ULD.ULD_LOGINID,DATE_FORMAT(DT.RC_TIMESTAMP,'%d-%m-%Y %T')"),3=>array(15,"URC_DATA","DT.URC_ID,DT.AC_DATA,ULD.ULD_LOGINID,DATE_FORMAT(DT.URC_TIMESTAMP,'%d-%m-%Y %T')"));
//        $flag_result=1;
//        if(($flag==3) ||( $flag==4) ||($flag==5))
//        {
        $result = $con->query("CALL SP_TS_CONFIG_CHECK_TRANSACTION(".$CONFIG_SRCH_UPD_arr_delete_data[$flag][0].",$CONFIG_SRCH_UPD_id,@CONFIGDELETE_FLAG)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @CONFIGDELETE_FLAG');
        $result = $select->fetch_assoc();
        $flag_result= $result['@CONFIGDELETE_FLAG'];
//        }
        if($flag_result==1){
            $CONFIG_SRCH_UPD_arr_delete_data=array(4=>array(17,"AC_DATA","DT.AC_ID,DT.AC_DATA,ULD.ULD_LOGINID,DATE_FORMAT(DT.AC_TIMESTAMP,'%d-%m-%Y %T')"),5=>array(22,"PC_DATA","DT.PC_ID,DT.PC_DATA,ULD.ULD_LOGINID,DATE_FORMAT(DT.PC_TIMESTAMP,'%d-%m-%Y %T')"),2=>array(16,"RC_DATA","DT.RC_ID,DT.RC_DATA,ULD.ULD_LOGINID,DATE_FORMAT(DT.RC_TIMESTAMP,'%d-%m-%Y %T')"),3=>array(15,"URC_DATA","DT.URC_ID,DT.AC_DATA,ULD.ULD_LOGINID,DATE_FORMAT(DT.URC_TIMESTAMP,'%d-%m-%Y %T')"));
            $result = $con->query("CALL SP_TS_SINGLE_TABLE_ROW_DELETION(".$CONFIG_SRCH_UPD_arr_delete_data[$flag][0].",$CONFIG_SRCH_UPD_id,'$USERSTAMP',@CONFIGDELETE_FLAG)");
            if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
            $select = $con->query('SELECT @CONFIGDELETE_FLAG');
            $result = $select->fetch_assoc();
            $flag1= $result['@CONFIGDELETE_FLAG'];
//
        }
        echo $flag1;
    }
//CHECK DUPLICATE DATA
    if($_REQUEST['option']=="CONFIG_SRCH_UPD_check_data")
    {
        $CONFIG_SRCH_UPD_arr_config=array(4=>array("ATTENDANCE_CONFIGURATION","AC_DATA"),5=>array("PROJECT_CONFIGURATION","PC_DATA"),2=>array("REPORT_CONFIGURATION","RC_DATA"),3=>array("USER_RIGHTS_CONFIGURATION","URC_DATA"));
        $flag=$_REQUEST['CONFIG_SRCH_UPD_lb_module'];
        $CONFIG_SRCH_UPD_type=$_REQUEST['CONFIG_SRCH_UPD_lb_type'];
        $CONFIG_SRCH_UPD_data=$_REQUEST['CONFIG_SRCH_UPD_tb_data'];
        $sql= "SELECT ".$CONFIG_SRCH_UPD_arr_config[$flag][1]." FROM ".$CONFIG_SRCH_UPD_arr_config[$flag][0]." CCN WHERE CCN.CGN_ID=(SELECT C.CGN_ID FROM CONFIGURATION C WHERE C.CGN_ID='$CONFIG_SRCH_UPD_type') AND ".$CONFIG_SRCH_UPD_arr_config[$flag][1]."='$CONFIG_SRCH_UPD_data'";
        $CONFIG_SRCH_UPD_type = mysqli_query($con,$sql);
        $CONFIG_SRCH_UPD_data_flag=0;
        if($row=mysqli_fetch_array($CONFIG_SRCH_UPD_type)){
            $CONFIG_SRCH_UPD_data_flag=1;
        }
        echo $CONFIG_SRCH_UPD_data_flag;

    }
}
//FUNCTION FOR SHARING DOCUMENT
function shareDocument($service,$emailadrress,$role_array,$CONFIG_SRCH_UPD_data){
    $value=$emailadrress;
    $type='user';
    $role=$role_array;
    $email=$emailadrress;
    $newPermission = new Google_Service_Drive_Permission();
    $newPermission->setValue($value);
    $newPermission->setType($type);
    $newPermission->setRole($role);
    $newPermission->setEmailAddress($email);
    try {
        $service->permissions->insert($CONFIG_SRCH_UPD_data, $newPermission);
    } catch (Exception $e) {
    }
}
