<?php
//WITHOUT FOLDER CALLING WISE
set_include_path( get_include_path() . PATH_SEPARATOR . 'google-api-php-client-master/src' );
require_once 'google/appengine/api/mail/Message.php';
require_once ('google-api-php-client-master/src/Google/Client.php');
include 'google-api-php-client-master/src/Google/Service/Drive.php';
include 'google-api-php-client-master/src/Google/Service/Calendar.php';

include "../TSLIB/TSLIB_CONNECTION.php";
include "../TSLIB/TSLIB_COMMON.php";
include "../TSLIB/TSLIB_GET_USERSTAMP.php";
//include "../TSLIB/TSLIB_CONFIG.php";
use google\appengine\api\mail\Message;
//error_reporting(0);
set_time_limit(0);
error_reporting(E_ERROR | E_PARSE);
if(isset($_REQUEST)){
    $USERSTAMP=$UserStamp;
    global $con;
    //ALREADY EXISTS FUNCTION FOR LOGIN ID
    if($_REQUEST['option']=="check_login_id"){
        $loginid=$_GET['URSRC_login_id'];
        $sql="select * from USER_LOGIN_DETAILS where ULD_LOGINID='$loginid'";
        $sql_result= mysqli_query($con,$sql);
        $row=mysqli_num_rows($sql_result);
        $x=$row;
        if($x > 0)
        {
            $URSRC_already_exist_flag=1;
        }
        else{
            $URSRC_already_exist_flag=0;
        }
        $rcname_result=mysqli_query($con,"SELECT * FROM ROLE_CREATION ORDER BY RC_NAME");
        $get_rcname_array=array();
        while($row=mysqli_fetch_array($rcname_result)){
            $get_rcname_array[]=$row["RC_NAME"];
        }
        //RELATIONHOOD
        $rname_result=mysqli_query($con,"SELECT URC_DATA FROM USER_RIGHTS_CONFIGURATION WHERE CGN_ID=22 ORDER BY URC_DATA");
        $get_rname_array=array();
        while($row=mysqli_fetch_array($rname_result)){
            $get_rname_array[]=$row["URC_DATA"];
        }
        //LAPTOP NUMBER
//        $lname_result=mysqli_query($con,"SELECT CP_LAPTOP_NUMBER FROM COMPANY_PROPERTIES  ORDER BY CP_LAPTOP_NUMBER");
        $lname_result=mysqli_query($con,"SELECT CP_LAPTOP_NUMBER FROM COMPANY_PROPERTIES WHERE CP_FLAG IS NULL ORDER BY CP_LAPTOP_NUMBER");
        $get_lname_array=array();
        while($row=mysqli_fetch_array($lname_result)){
            $get_lname_array[]=$row["CP_LAPTOP_NUMBER"];
        }
        //DESIGNATION
        $rdesgn_result=mysqli_query($con,"SELECT * FROM EMPLOYEE_DESIGNATION ORDER BY ED_DESIGNATION");
        $get_rdesgn_array=array();
        while($row=mysqli_fetch_array($rdesgn_result)){
            $get_rdesgn_array[]=$row["ED_DESIGNATION"];
        }
        //ACCOUNT TYPE
        $acctype_result=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE CGN_ID=21 ORDER BY URC_DATA");
        $get_acctype_array=array();
        while($row=mysqli_fetch_array($acctype_result)){
            $get_acctype_array[]=$row["URC_DATA"];
        }
        $URSRC_final_array=array();
        $URSRC_role_array=array();
        $URSRC_role_array=$get_rcname_array;
        $URSRC_final_array=array($URSRC_already_exist_flag,$URSRC_role_array,$get_rname_array,$get_lname_array,$get_rdesgn_array,$get_acctype_array);
        echo json_encode($URSRC_final_array);
    }
    //GETTING CHARGER NUMBER
    if($_REQUEST['option']=="COMPANY_PROPERTY")
    {
        $URSRC_lb_laptopno=$_REQUEST['URSRC_lb_laptopno'];
//        $URSRC_cmpny_prop=mysqli_query($con,"SELECT CP_CHARGER_NUMBER FROM COMPANY_PROPERTIES WHERE CP_LAPTOP_NUMBER = '$URSRC_lb_laptopno'");
        $URSRC_cmpny_prop=mysqli_query($con,"SELECT CP_CHARGER_NUMBER,CP_LAPTOP_BAG_NUMBER,CP_MOUSE_NUMBER,CP_BATTERY_SERIAL_NUMBER FROM COMPANY_PROPERTIES WHERE CP_LAPTOP_NUMBER = '$URSRC_lb_laptopno'");
        while($row=mysqli_fetch_array($URSRC_cmpny_prop)){
            $URSRC_charger_no=$row["CP_CHARGER_NUMBER"];
            $URSRC_laptop_no=$row["CP_LAPTOP_BAG_NUMBER"];
            $URSRC_mouse_no=$row["CP_MOUSE_NUMBER"];
            $URSRC_btrysrl_no=$row["CP_BATTERY_SERIAL_NUMBER"];
            $URSRC_report_values=array('chargerno'=>$URSRC_charger_no,'laptopno'=>$URSRC_laptop_no,'mouse'=>$URSRC_mouse_no,'btry'=>$URSRC_btrysrl_no);
            $URSRC_values[]=$URSRC_report_values;
        }
        echo json_encode($URSRC_values);
    }
//LOGIN CREATION SAVE PART
//    if($_REQUEST['option']=="loginsave")
    if ($_POST['SAVE']=="CREATE")
    {
        $loginid=$_POST['URSRC_tb_loginid'];
        $emp_type=$_POST['URSRC_lb_selectemptype'];
        $role_accessradiovalue =$_POST['roles1'];//$_REQUEST['radio_checked'];
        $final_radioval=str_replace("_"," ",$role_accessradiovalue);
        $date=$_POST['URSRC_tb_joindate'];
        $finaldate = date('Y-m-d',strtotime($date));
        $URSRC_firstname=$_POST['URSRC_tb_firstname'];
        $URSRC_lastname=$_POST['URSRC_tb_lastname'];
        $URSRC_dob=$_POST['URSRC_tb_dob'];
        $URSRC_finaldob = date('Y-m-d',strtotime($URSRC_dob));
        $URSRC_designation=$_POST['URSRC_tb_designation'];
        $URSRC_Mobileno=$_POST['URSRC_tb_permobile'];
        $URSRC_kinname=$_POST['URSRC_tb_kinname'];
        $URSRC_relationhd=$_POST['URSRC_lb_selectrelationhd'];
        $URSRC_mobile=$_POST['URSRC_tb_mobile'];
        $URSRC_Houseno=$_POST['URSRC_tb_houseno'];
        $URSRC_Streetname=$_POST['URSRC_tb_strtname'];
        $URSRC_Area=$_POST['URSRC_tb_area'];
        $URSRC_Postalcode=$_POST['URSRC_tb_pstlcode'];
        $URSRC_bankname=$_POST['URSRC_tb_bnkname'];
        $URSRC_brancname=$_POST['URSRC_tb_brnchname'];
        $URSRC_acctname=$_POST['URSRC_tb_accntname'];
        $URSRC_acctno=$_POST['URSRC_tb_accntno'];
        $URSRC_ifsccode=$_POST['URSRC_tb_ifsccode'];
        $URSRC_acctype=$_POST['URSRC_tb_accntyp'];
        $URSRC_branchaddr1=$_POST['URSRC_ta_brnchaddr'];
        $URSRC_branchaddr= $con->real_escape_string($URSRC_branchaddr1);
//        $URSRC_laptopno=$_POST['URSRC_tb_laptopno'];
        $URSRC_laptopno=$_POST['URSRC_lb_selectlaptopno'];
//        echo $URSRC_laptopno;
//        exit;
        if($URSRC_laptopno=='SELECT')
        {
            $URSRC_laptopno='';

        }
        else{
            $URSRC_laptopno;
        }
        $URSRC_chrgrno=$_POST['URSRC_tb_chargerno'];
        $URSRC_bag=$_POST['URSRC_tb_laptopno'];
        $URSRC_aadharno=$_POST['URSRC_tb_aadharno'];
        $URSRC_passportno=$_POST['URSRC_tb_passportno'];
        $URSRC_voterid=$_POST['URSRC_tb_votersid'];
        $comment=$_POST['URSRC_ta_comments'];
        $comment= $con->real_escape_string($comment);
//        $URSRC_bag=$_POST['URSRC_chk_bag'];
//        if($URSRC_bag=='on')
//        {
//            $URSRC_bag= 'X';
//            $bag='YES';
//        }
//        else
//        {
//            $URSRC_bag='';
//            $bag='NO';
//        }
        $URSRC_chklapbag=$_REQUEST['URSRC_chk_bag'];
        $URSRC_bag=$_REQUEST['URSRC_tb_laptopno'];
        $URSRC_chkmouse=$_REQUEST['URSRC_chk_mouse'];
        $URSRC_mouse=$_POST['URSRC_tb_mouse'];
        if($URSRC_chkmouse=='on')
        {
            $URSRC_mouse;
        }
        else
        {
            $URSRC_mouse='';
        }
//        if($URSRC_mouse=='on')
//        {
//            $URSRC_mouse= 'X';
//            $mouse='YES';
//        }
//        else
//        {
//            $URSRC_mouse='';
//            $mouse='NO';
//        }
        $URSRC_btryslno=$_POST['URSRC_tb_btry'];
        $URSRC_dooracess=$_POST['URSRC_chk_dracess'];
        if($URSRC_dooracess=='on')
        {
            $URSRC_dooracess= 'X';
            $dooraccess='YES';
        }
        else
        {
            $URSRC_dooracess='';
            $dooraccess='NO';
        }
        $URSRC_idcard=$_POST['URSRC_chk_idcrd'];
        if($URSRC_idcard=='on')
        {
            $URSRC_idcard= 'X';
            $idcard='YES';
        }
        else
        {
            $URSRC_idcard='';
            $idcard='NO';
        }
        $URSRC_headset=$_POST['URSRC_chk_headset'];
        if($URSRC_headset=='on')
        {
            $URSRC_headset= 'X';
            $headset='YES';
        }
        else
        {
            $URSRC_headset='';
            $headset='NO';
        }
        $URSRC_chkaadharno=$_POST['URSRC_chk_aadharno'];
        if($URSRC_chkaadharno=='on')
        {
            $URSRC_aadharno;
        }
        else
        {
            $URSRC_aadharno='';
        }
        $URSRC_chkpassportno=$_POST['URSRC_chk_passportno'];
        if($URSRC_chkpassportno=='on')
        {
            $URSRC_passportno;
        }
        else
        {
            $URSRC_passportno='';
        }
        $URSCR_chkvoterid=$_POST['URSRC_chk_votersid'];
        if($URSCR_chkvoterid=='on')
        {
            $URSRC_voterid;
        }
        else
        {
            $URSRC_voterid='';
        }
        $con->autocommit(false);
//        echo "CALL SP_TS_LOGIN_CREATION_INSERT(1,'$loginid',' ','$final_radioval','$finaldate','$emp_type','$URSRC_firstname','$URSRC_lastname','$URSRC_finaldob','$URSRC_designation','$URSRC_Mobileno','$URSRC_kinname','$URSRC_relationhd','$URSRC_mobile','$URSRC_bankname','$URSRC_brancname','$URSRC_acctname','$URSRC_acctno','$URSRC_ifsccode','$URSRC_acctype','$URSRC_branchaddr','$URSRC_aadharno','$URSRC_passportno','$URSRC_voterid','$comment','$URSRC_laptopno','$URSRC_chrgrno','$URSRC_bag','$URSRC_mouse','$URSRC_dooracess','$URSRC_idcard','$URSRC_headset','$USERSTAMP','$URSRC_Houseno','$URSRC_Streetname','$URSRC_Area','$URSRC_Postalcode','$URSRC_btryslno',@success_flag)";
//       exit;
        $result = $con->query("CALL SP_TS_LOGIN_CREATION_INSERT(1,'$loginid',' ','$final_radioval','$finaldate','$emp_type','$URSRC_firstname','$URSRC_lastname','$URSRC_finaldob','$URSRC_designation','$URSRC_Mobileno','$URSRC_kinname','$URSRC_relationhd','$URSRC_mobile','$URSRC_bankname','$URSRC_brancname','$URSRC_acctname','$URSRC_acctno','$URSRC_ifsccode','$URSRC_acctype','$URSRC_branchaddr','$URSRC_aadharno','$URSRC_passportno','$URSRC_voterid','$comment','$URSRC_laptopno','$URSRC_chrgrno','$URSRC_bag','$URSRC_mouse','$URSRC_dooracess','$URSRC_idcard','$URSRC_headset','$USERSTAMP','$URSRC_Houseno','$URSRC_Streetname','$URSRC_Area','$URSRC_Postalcode','$URSRC_btryslno',@success_flag)");
//        $result = $con->query("CALL SP_TS_LOGIN_CREATION_INSERT(1,'$loginid',' ','$final_radioval','$finaldate','$emp_type','$URSRC_firstname','$URSRC_lastname','$URSRC_finaldob','$URSRC_designation','$URSRC_Mobileno','$URSRC_kinname','$URSRC_relationhd','$URSRC_mobile','$URSRC_bankname','$URSRC_brancname','$URSRC_acctname','$URSRC_acctno','$URSRC_ifsccode','$URSRC_acctype','$URSRC_branchaddr','$URSRC_aadharno','$URSRC_passportno','$URSRC_voterid','$comment','$URSRC_laptopno','$URSRC_chrgrno','$URSRC_bag','$URSRC_mouse','$URSRC_dooracess','$URSRC_idcard','$URSRC_headset','$USERSTAMP','$URSRC_Houseno','$URSRC_Streetname','$URSRC_Area','$URSRC_Postalcode',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
        if($flag==1){
            $select_admin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='ADMIN'";
            $select_sadmin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='SUPER ADMIN'";
            $admin_rs=mysqli_query($con,$select_admin);
            $sadmin_rs=mysqli_query($con,$select_sadmin);
            if($row=mysqli_fetch_array($admin_rs)){
                $admin=$row["ULD_LOGINID"];//get admin
            }
            if($row=mysqli_fetch_array($sadmin_rs)){
                $sadmin=$row["ULD_LOGINID"];//get super admin
            }
            $cclist=array($admin,$sadmin);
            $select_link=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=4");
            if($row=mysqli_fetch_array($select_link)){
                $site_link=$row["URC_DATA"];
            }
            $select_ss_link=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=5");
            if($row=mysqli_fetch_array($select_ss_link)){
                $ss_link=$row["URC_DATA"];
            }

            $select_fileid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=9");
            if($row=mysqli_fetch_array($select_fileid)){
                $ss_fileid=$row["URC_DATA"];
            }
            $select_codefileid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=35");
            if($row=mysqli_fetch_array($select_codefileid)){
                $ss_codefileid=$row["URC_DATA"];
            }

            $select_calenderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=10");
            if($row=mysqli_fetch_array($select_calenderid)){
                $calenderid=$row["URC_DATA"];
            }

            $select_youtubelink=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=12");
            if($row=mysqli_fetch_array($select_youtubelink)){
                $youtubelink=$row["URC_DATA"];
            }
            //CODE  OPTIMIZATION
            $select_code=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=34");
            if($row=mysqli_fetch_array($select_code)){
                $codeoptimi=$row["URC_DATA"];
            }
            $select_folderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=13");
            if($row=mysqli_fetch_array($select_folderid)){
                $folderid=$row["URC_DATA"];
            }


            $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=1";
            $select_template_rs=mysqli_query($con,$select_template);
            if($row=mysqli_fetch_array($select_template_rs)){
                $mail_subject=$row["ETD_EMAIL_SUBJECT"];
                $body=$row["ETD_EMAIL_BODY"];
            }
            $select_displayname="SELECT URC_DATA FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=26";
            $select_displayname_rs=mysqli_query($con,$select_displayname);
            if($row=mysqli_fetch_array($select_displayname_rs)){
                $mail_displayname=$row["URC_DATA"];
            }
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
            $fileId=$ss_fileid;
            $codeopti_fileId=$ss_codefileid;
            $value=$loginid;
            $type='user';
            $role='reader';
            $email=$loginid;

            $newPermission = new Google_Service_Drive_Permission();
            $newPermission->setValue($value);
            $newPermission->setType($type);
            $newPermission->setRole($role);
            $newPermission->setEmailAddress($email);
            try {
                $service->permissions->insert($fileId, $newPermission);
                $service->permissions->insert($codeopti_fileId, $newPermission);
                $ss_flag=1;
            } catch (Exception $e) {
                $ss_flag=0;
                $con->rollback();
            }
            $loginid_name = strtoupper(substr($loginid, 0, strpos($loginid, '@')));
            if(substr($loginid_name, 0, strpos($loginid_name, '.'))){
                $loginid_name = strtoupper(substr($loginid_name, 0, strpos($loginid_name, '.')));
            }
            else{
                $loginid_name=$loginid_name;
            }
            $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$loginid'");
            while($row=mysqli_fetch_array($uld_id)){
                $URSC_uld_id=$row["ULD_ID"];
            }
//            echo "uldid".$URSC_uld_id.$URSRC_firstname.' '.$URSRC_lastname.$URSC_uld_id;
            if($ss_flag==1){
                $upload_flag=0;
                $file_array=array();
                $new_empfolderid=UploadEmployeeFiles("login_creation",$loginid);
                //end create folder
                if(!empty($_FILES))
                {
//                    echo 'asdsad';
//                    echo $_FILES;
                    foreach($_FILES['UPD_uploaded_files']['name'] as $idx => $name) {
//                        echo 'fr';
//                        print_r($_FILES);
                        if($name=="")continue;
                        $upload_flag=1;
                        $ufilename=$name;
                        $ufiletempname=$_FILES['UPD_uploaded_files']['tmp_name'][$idx];
                        $ufiletype=$_FILES['UPD_uploaded_files']['type'][$idx];
//                        $file_id_value='';
                        $file_id_value =insertFile($service,$ufilename,'PersonalDetails',$new_empfolderid,$ufiletype,$ufiletempname);
                        if($file_id_value!=''){
                            array_push($file_array,$file_id_value);
                        }

                    }
                }
                if($upload_flag==1&&count($file_array)==0){
                    $file_flag=0;
                    URSRC_unshare_document($loginid,$fileId);
                    URSRC_unshare_document($loginid,$codeopti_fileId);
                    $con->rollback();
                }
                //end of file upload
            }

            if($upload_flag==1){
                if((count($file_array)>0) && ($ss_flag==1)){
                    $cal_flag= URSRC_calendar_create($URSRC_firstname,$URSC_uld_id,$finaldate,$calenderid,'JOIN DATE');
                    $cal_flag= URSRC_calendar_create($URSRC_firstname,$URSC_uld_id,$URSRC_finaldob,$calenderid,'BIRTH DAY');
                    if($cal_flag==0){
                        URSRC_unshare_document($loginid,$fileId);
                        URSRC_unshare_document($loginid,$codeopti_fileId);
                        for($i=0;$i<count($file_array);$i++){
                            delete_file($service,$file_array[$i]);

                        }
                        delete_file($service,$new_empfolderid);
                        $con->rollback();
                    }
                }
            }
            else{

                if($ss_flag==1){

                    $cal_flag= URSRC_calendar_create($URSRC_firstname,$URSC_uld_id,$finaldate,$calenderid,'JOIN DATE');

                    $cal_flag= URSRC_calendar_create($URSRC_firstname,$URSC_uld_id,$URSRC_finaldob,$calenderid,'BIRTH DAY');

                    if($cal_flag==0){
                        URSRC_unshare_document($loginid,$fileId);
                        URSRC_unshare_document($loginid,$codeopti_fileId);
                        $con->rollback();
                    }

                }

            }
            if(($ss_flag==1)&&($cal_flag==1)){
                $email_body;
                $body_msg =explode("^", $body);
                $length=count($body_msg);
                for($i=0;$i<$length;$i++){
                    $email_body.=$body_msg[$i].'<br><br>';
                }
                $replace= array("[LOGINID]", "[LINK]","[SSLINK]", "[VLINK]","[DES]","[CLINK]");
                $str_replaced  = array($URSRC_firstname,$site_link, $ss_link, $youtubelink,'<b>'.$URSRC_designation.'</b>',$codeoptimi);
                $final_message = str_replace($replace, $str_replaced, $email_body);
                $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=10";
                $select_template_rs=mysqli_query($con,$select_template);
                if($row=mysqli_fetch_array($select_template_rs)){
                    $mail_subject1=$row["ETD_EMAIL_SUBJECT"];
                    $body=$row["ETD_EMAIL_BODY"];
                }
                //not aplicable
                $URSRC_bag=$_POST['URSRC_tb_laptopno'];
                if($URSRC_bag!='')
                {
                    $URSRC_bag;
                }
                else{
                    $URSRC_bag="N/A";
                }
//                if($URSRC_mouse=='')
//                {
//                    $URSRC_mouse="N/A";
//                }
//                else{
//                    $URSRC_mouse=$_POST['URSRC_tb_mouse'];
//                }
                if($URSRC_laptopno=='')
                {
                    $URSRC_laptopno="N/A";
                }
                else{
                    $URSRC_laptopno=$_POST['URSRC_lb_selectlaptopno'];
                }
                if($URSRC_chrgrno=='')
                {
                    $URSRC_chrgrno="N/A";
                }
                else{
                    $URSRC_chrgrno=$_POST['URSRC_tb_chargerno'];
                }
                if($URSRC_btryslno=='')
                {
                    $URSRC_btryslno="N/A";
                }
                else{
                    $URSRC_btryslno=$_POST['URSRC_tb_btry'];
                }
                if($URSRC_chkaadharno=='on')
                {
                    $URSRC_aadharno;
                }
                else
                {
                    $URSRC_aadharno="N/A";
                }
                if($URSRC_chkpassportno=='on')
                {
                    $URSRC_passportno;
                }
                else
                {
                    $URSRC_passportno="N/A";
                }
                if($URSCR_chkvoterid=='on')
                {
                    $URSRC_voterid;
                }
                else
                {
                    $URSRC_voterid="N/A";
                }
                if($URSRC_chkmouse=='on')
                {
                    $URSRC_mouse;
                }
                else
                {
                    $URSRC_mouse="N/A";
                }
                //not applicable
                //STRING REPLACE FUNCTION
                $emp_email_body;
                $body_msg =explode("^", $body);
                $length=count($body_msg);
                for($i=0;$i<$length;$i++){
                    $emp_email_body.=$body_msg[$i].'<br><br>';
                }
                $commentper =explode("\n", $comment);
                $commnet_length=count($commentper);
                for($i=0;$i<$commnet_length;$i++){
                    $comment_permsg.=$commentper[$i].'<br>';
                }
                $comment =explode("\n", $URSRC_branchaddr1);
                $commnet_length=count($comment);
                for($i=0;$i<$commnet_length;$i++){
                    $comment_msg.=$comment[$i].'<br>';
                }

                $replace= array( "[FNAME]","[LNAME]", "[DOB]","[DESG]","[MOBNO]","[KINNAME]","[REL]","[ALTMOBNO]","[LAPNO]","[CHRNO]","[LAPBAG]","[MOUSE]","[DACC]","[IDCARD]","[HEADSET]","[BANKNAME]","[BRANCHNAME]","[ACCNAME]","[ACCNO]","[IFSCCODE]","[ACCTYPE]","[BANKADDRESS]","PERSONAL DETAILS:","COMPANY PROPERTIES DETAILS:","BANK ACCOUNT DETAILS:","[AADHAAR NO]","[PASSPORT NO]","[VOTERS ID NO]","[HOUSE NO]","[STREET NAME]","[PINCODE]","[AREA]","[COMMENTS]","[BSNO]");
                $str_replaced  = array($URSRC_firstname, $URSRC_lastname, $URSRC_dob,$URSRC_designation,$URSRC_Mobileno,$URSRC_kinname,$URSRC_relationhd,$URSRC_mobile,$URSRC_laptopno,$URSRC_chrgrno,$URSRC_bag,$URSRC_mouse,$dooraccess,$idcard,$headset,$URSRC_bankname,$URSRC_brancname,$URSRC_acctname,$URSRC_acctno,$URSRC_ifsccode,$URSRC_acctype,$comment_msg,'<b>'."PERSONAL DETAILS:".'</b>','<b>'."COMPANY PROPERTIES DETAILS:".'</b>','<b>'."BANK ACCOUNT DETAILS:".'</b>',$URSRC_aadharno,$URSRC_passportno,$URSRC_voterid,$URSRC_Houseno,$URSRC_Streetname,$URSRC_Postalcode,$URSRC_Area,$comment_permsg,$URSRC_btryslno);

//                $replace= array( "[FNAME]","[LNAME]", "[DOB]","[DESG]","[MOBNO]","[KINNAME]","[REL]","[ALTMOBNO]","[LAPNO]","[CHRNO]","[LAPBAG]","[MOUSE]","[DACC]","[IDCARD]","[HEADSET]","[BANKNAME]","[BRANCHNAME]","[ACCNAME]","[ACCNO]","[IFSCCODE]","[ACCTYPE]","[BANKADDRESS]","PERSONAL DETAILS:","COMPANY PROPERTIES DETAILS:","BANK ACCOUNT DETAILS:","[AADHAAR NO]","[PASSPORT NO]","[VOTERS ID NO]");
//                $str_replaced  = array($URSRC_firstname, $URSRC_lastname, $URSRC_dob,$URSRC_designation,$URSRC_Mobileno,$URSRC_kinname,$URSRC_relationhd,$URSRC_mobile,$URSRC_laptopno,$URSRC_chrgrno,$bag,$mouse,$dooraccess,$idcard,$headset,$URSRC_bankname,$URSRC_brancname,$URSRC_acctname,$URSRC_acctno,$URSRC_ifsccode,$URSRC_acctype,$comment_msg,'<b>'."PERSONAL DETAILS:".'</b>','<b>'."COMPANY PROPERTIES DETAILS:".'</b>','<b>'."BANK ACCOUNT DETAILS:".'</b>',$URSRC_aadharno,$URSRC_passportno,$URSRC_voterid);

                $newphrase = str_replace($replace, $str_replaced, $emp_email_body);
                $final_message=$final_message.'<br>'.$newphrase;
                //SENDING MAIL OPTIONS
                $name = $mail_displayname;
                $from = 'lalitha.rajendiran@ssomens.com';//$admin;
                try {
                    $message1 = new Message();
                    $message1->setSender($name.'<'.$from.'>');
                    $message1->addTo('lalitha.rajendiran@ssomens.com');
//                    $message1->addCc($cclist);
                    $message1->setSubject($mail_subject);
                    $message1->setHtmlBody($final_message);
                    $message1->send();
                } catch (\InvalidArgumentException $e) {
                    echo $e;
                }
                $select_intro_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=14";
                $select_introtemplate_rs=mysqli_query($con,$select_intro_template);
                if($row=mysqli_fetch_array($select_introtemplate_rs)){
                    $intro_mail_subject=$row["ETD_EMAIL_SUBJECT"];
                    $intro_body=$row["ETD_EMAIL_BODY"];
                }
                $select_intro_displayname="SELECT URC_DATA FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=26";
                $select_displayname_rs=mysqli_query($con,$select_intro_displayname);
                if($row=mysqli_fetch_array($select_displayname_rs)){
                    $intro_mail_displayname=$row["URC_DATA"];
                }
                $intro_email_body;
                $intro_body_msg =explode("^", $intro_body);
                $intro_length=count($intro_body_msg);
                for($i=0;$i<$intro_length;$i++){
                    $intro_email_body.=$intro_body_msg[$i].'<br><br>';
                }
                $replace= array("[DATE]", "[employee name]","[emailid]","[designation]");
                $str_replaced  = array(date("d-m-Y"),'<b>'.$URSRC_firstname.'</b>', $loginid,'<b>'.$URSRC_designation.'</b>');
                $intro_message = str_replace($replace, $str_replaced, $intro_email_body);
                $cc_array=get_active_login_id();
//                $cc_array=['punitha.shanmugam@ssomens.com'];
                //SENDING MAIL OPTIONS
                try {
                    $name = $intro_mail_displayname;
                    $from = 'lalitha.rajendiran@ssomens.com';//$admin;
                    $message1 = new Message();
                    $message1->setSender($name.'<'.$from.'>');
//                    $message1->addTo($cc_array);
                    $message1->addTo('lalitha.rajendiran@ssomens.com');
//                    $message1->addCc($sadmin);
                    $message1->setSubject($intro_mail_subject);
                    $message1->setHtmlBody($intro_message);
                    $message1->send();
                } catch (\InvalidArgumentException $e) {
                    echo $e;
                }
            }
            $flag_array=[$flag,$ss_flag,$cal_flag,$fileId,$file_flag,$folderid];
        }
        else{

            $flag_array=[$flag];
        }
        $con->commit();
        echo json_encode($flag_array);
    }

    //FETCHING LOGIN DETAILS
    if($_REQUEST['option']=="loginfetch")
    {
        $loginid_result = $_REQUEST['URSRC_login_id'];
        $ACR_loginid= $_REQUEST['loinid_lap_val'];
        //RELATIONHOOD
        $ULDID_result=mysqli_query($con," SELECT ULD_ID FROM VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS WHERE ULD_LOGINID='$ACR_loginid'");
//        $get_uldid_array=array();
        while($row=mysqli_fetch_array($ULDID_result)){
            $get_uldid_array=$row["ULD_ID"];
        }
        //LAPTOP NUMBER
//        $lname_result=mysqli_query($con,"SELECT CP_LAPTOP_NUMBER FROM COMPANY_PROPERTIES WHERE CP_FLAG IS NULL  ORDER BY CP_LAPTOP_NUMBER");
        $lname_result=mysqli_query($con,"SELECT CP_LAPTOP_NUMBER FROM COMPANY_PROPERTIES WHERE CP_ID=(SELECT CP_ID FROM COMPANY_PROPERTIES_DETAILS WHERE EMP_ID=(SELECT EMP_ID FROM EMPLOYEE_DETAILS WHERE ULD_ID='$get_uldid_array'))UNION SELECT CP_LAPTOP_NUMBER FROM COMPANY_PROPERTIES WHERE CP_FLAG IS NULL");
        $get_lname_array=array();
        while($row=mysqli_fetch_array($lname_result)){
            $get_lname_array[]=$row["CP_LAPTOP_NUMBER"];
        }
        $emp_uploadfilelist=array();
        $emp_uploadfilelist=UploadEmployeeFiles("login_fetch",$loginid_result);
//        $loginsearch_fetchingdata= mysqli_query($con," SELECT DISTINCT RC.RC_NAME,UA.UA_JOIN_DATE,URC1.URC_DATA,EMP.EMP_ID,EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME,DATE_FORMAT(EMP.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,EMP.EMP_DESIGNATION,EMP.EMP_MOBILE_NUMBER,EMP.EMP_NEXT_KIN_NAME,EMP.EMP_RELATIONHOOD,EMP.EMP_ALT_MOBILE_NO,EMP.EMP_BANK_NAME,EMP.EMP_BRANCH_NAME,EMP.EMP_ACCOUNT_NAME,EMP.EMP_ACCOUNT_NO,EMP.EMP_IFSC_CODE,EMP.EMP_ACCOUNT_TYPE,EMP.EMP_BRANCH_ADDRESS,EMP.EMP_AADHAAR_NO,EMP.EMP_PASSPORT_NO,EMP.EMP_VOTER_ID,EMP.EMP_COMMENTS,CPD.CPD_LAPTOP_NUMBER,CPD.CPD_CHARGER_NUMBER,CPD.CPD_LAPTOP_BAG,CPD.CPD_MOUSE,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET,ULD.ULD_LOGINID,DATE_FORMAT(CONVERT_TZ(EMP.EMP_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS EMP_TIMESTAMP FROM EMPLOYEE_DETAILS EMP left join COMPANY_PROPERTIES_DETAILS CPD on EMP.EMP_ID=CPD.EMP_ID,USER_LOGIN_DETAILS ULD,USER_ACCESS UA ,USER_RIGHTS_CONFIGURATION URC,USER_RIGHTS_CONFIGURATION URC1,ROLE_CREATION RC  WHERE EMP.ULD_ID=ULD.ULD_ID AND UA.UA_EMP_TYPE=URC1.URC_ID and ULD.ULD_ID=UA.ULD_ID and URC.URC_ID=RC.URC_ID and RC.RC_ID=UA.RC_ID and ULD_LOGINID='$loginid_result' and UA.UA_REC_VER=(select max(UA_REC_VER) from USER_ACCESS UA,USER_LOGIN_DETAILS ULD where ULD.ULD_ID=UA.ULD_ID and ULD_LOGINID='$loginid_result' and UA_JOIN is not null) ORDER BY EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME");
//        echo "SELECT DISTINCT RC.RC_NAME,UA.UA_JOIN_DATE,URC1.URC_DATA,EMP.EMP_ID,EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME,DATE_FORMAT(EMP.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,ED.ED_DESIGNATION AS EMP_DESIGNATION,EMP.EMP_MOBILE_NUMBER,EMP.EMP_NEXT_KIN_NAME,URC2.URC_DATA AS EMP_RELATIONHOOD,EMP.EMP_ALT_MOBILE_NO,EMP.EMP_HOUSE_NO,EMP.EMP_STREET_NAME,EMP.EMP_AREA,EMP.EMP_PIN_CODE,EMP.EMP_BANK_NAME,EMP.EMP_BRANCH_NAME,EMP.EMP_ACCOUNT_NAME,EMP.EMP_ACCOUNT_NO,EMP.EMP_IFSC_CODE,URC3.URC_DATA AS EMP_ACCOUNT_TYPE,EMP.EMP_BRANCH_ADDRESS,EMP.EMP_AADHAAR_NO,EMP.EMP_PASSPORT_NO,EMP.EMP_VOTER_ID,EMP.EMP_COMMENTS,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CPD.CPD_LAPTOP_BAG,CPD.CPD_MOUSE,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET,ULD.ULD_LOGINID,DATE_FORMAT(CONVERT_TZ(EMP.EMP_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS EMP_TIMESTAMP FROM COMPANY_PROPERTIES CP, EMPLOYEE_DETAILS EMP LEFT JOIN COMPANY_PROPERTIES_DETAILS CPD ON EMP.EMP_ID=CPD.EMP_ID,USER_LOGIN_DETAILS ULD,USER_ACCESS UA ,USER_RIGHTS_CONFIGURATION URC,USER_RIGHTS_CONFIGURATION URC1,USER_RIGHTS_CONFIGURATION URC2,USER_RIGHTS_CONFIGURATION URC3,ROLE_CREATION RC,EMPLOYEE_DESIGNATION ED WHERE EMP.ULD_ID=ULD.ULD_ID AND UA.UA_EMP_TYPE=URC1.URC_ID AND ULD.ULD_ID=UA.ULD_ID AND URC.URC_ID=RC.URC_ID AND RC.RC_ID=UA.RC_ID AND ED.ED_ID=EMP.EMP_DESIGNATION AND EMP.EMP_RELATIONHOOD=URC2.URC_ID AND EMP.EMP_ACCOUNT_TYPE=URC3.URC_ID AND CP.CP_ID = CPD.CP_ID AND ULD_LOGINID='$loginid_result' AND UA.UA_REC_VER=(SELECT MAX(UA_REC_VER) FROM USER_ACCESS UA,USER_LOGIN_DETAILS ULD WHERE ULD.ULD_ID=UA.ULD_ID AND ULD_LOGINID='$loginid_result' AND UA_JOIN IS NOT NULL) ORDER BY EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME";
        $loginsearch_fetchingdata= mysqli_query($con,"SELECT DISTINCT RC.RC_NAME,UA.UA_JOIN_DATE,URC1.URC_DATA,EMP.EMP_ID,EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME,
DATE_FORMAT(EMP.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,ED.ED_DESIGNATION AS EMP_DESIGNATION,EMP.EMP_MOBILE_NUMBER,
EMP.EMP_NEXT_KIN_NAME,URC2.URC_DATA AS EMP_RELATIONHOOD,EMP.EMP_ALT_MOBILE_NO,EMP.EMP_HOUSE_NO,
EMP.EMP_STREET_NAME,EMP.EMP_AREA,EMP.EMP_PIN_CODE,EMP.EMP_BANK_NAME,EMP.EMP_BRANCH_NAME,
EMP.EMP_ACCOUNT_NAME,EMP.EMP_ACCOUNT_NO,EMP.EMP_IFSC_CODE,URC3.URC_DATA AS EMP_ACCOUNT_TYPE,
EMP.EMP_BRANCH_ADDRESS,EMP.EMP_AADHAAR_NO,EMP.EMP_PASSPORT_NO,EMP.EMP_VOTER_ID,EMP.EMP_COMMENTS,
CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CP.CP_LAPTOP_BAG_NUMBER,CP.CP_MOUSE_NUMBER,CPD.CPD_DOOR_ACCESS,CP.CP_BATTERY_SERIAL_NUMBER,
CPD.CPD_ID_CARD,CPD.CPD_HEADSET,ULD.ULD_LOGINID,DATE_FORMAT(CONVERT_TZ(EMP.EMP_TIMESTAMP,'+00:00','+05:30'), '%d-%m-%Y %T') AS EMP_TIMESTAMP
FROM EMPLOYEE_DETAILS EMP LEFT JOIN COMPANY_PROPERTIES_DETAILS CPD ON EMP.EMP_ID=CPD.EMP_ID LEFT JOIN COMPANY_PROPERTIES CP ON CPD.CP_ID=CP.CP_ID,
USER_LOGIN_DETAILS ULD,USER_ACCESS UA ,USER_RIGHTS_CONFIGURATION URC,USER_RIGHTS_CONFIGURATION URC1,
USER_RIGHTS_CONFIGURATION URC2,USER_RIGHTS_CONFIGURATION URC3,ROLE_CREATION RC,
EMPLOYEE_DESIGNATION ED WHERE EMP.ULD_ID=ULD.ULD_ID AND UA.UA_EMP_TYPE=URC1.URC_ID AND
ULD.ULD_ID=UA.ULD_ID AND URC.URC_ID=RC.URC_ID AND RC.RC_ID=UA.RC_ID AND
ED.ED_ID=EMP.EMP_DESIGNATION AND EMP.EMP_RELATIONHOOD=URC2.URC_ID AND
EMP.EMP_ACCOUNT_TYPE=URC3.URC_ID AND ULD.ULD_LOGINID='$loginid_result' AND
UA.UA_REC_VER=(SELECT MAX(UA_REC_VER) FROM USER_ACCESS UA,USER_LOGIN_DETAILS ULD WHERE ULD.ULD_ID=UA.ULD_ID AND ULD_LOGINID='$loginid_result' AND UA_JOIN IS NOT NULL)
ORDER BY EMP.EMP_FIRST_NAME,EMP.EMP_LAST_NAME");
        $URSRC_values=array();
        $rolecreation_result = mysqli_query($con,"SELECT * FROM ROLE_CREATION");
        $get_rolecreation_array=array();
        while($row=mysqli_fetch_array($rolecreation_result)){
            $get_rolecreation_array[]= $row["RC_NAME"];
        }
        while($row=mysqli_fetch_array($loginsearch_fetchingdata)){
            $URSRC_joindate=$row["UA_JOIN_DATE"];
            $join_date=date('d-m-Y',strtotime($URSRC_joindate));
            $URSRC_rcname=$row["RC_NAME"];
            $URSRC_EMP_TYPE=$row['URC_DATA'];
            $URSRC_firstname=$row['EMP_FIRST_NAME'];
            $URSRC_lastname=$row['EMP_LAST_NAME'];
            $URSRC_dob=$row['EMP_DOB'];
            $URSRC_designation=$row['EMP_DESIGNATION'];
            $URSRC_mobile=$row['EMP_MOBILE_NUMBER'];
            $URSRC_kinname=$row['EMP_NEXT_KIN_NAME'];
            $URSRC_relationhd=$row['EMP_RELATIONHOOD'];
            $URSRC_Mobileno=$row['EMP_ALT_MOBILE_NO'];
            $URSRC_Houseno=$row['EMP_HOUSE_NO'];
            $URSRC_Streetname=$row['EMP_STREET_NAME'];
            $URSRC_Area=$row['EMP_AREA'];
            $URSRC_Postalcode=$row['EMP_PIN_CODE'];
            $URSRC_laptopno=$row['CP_LAPTOP_NUMBER'];
            $URSRC_chrgrno=$row['CP_CHARGER_NUMBER'];
            $URSRC_bag=$row['CP_LAPTOP_BAG_NUMBER'];
            $URSRC_mouse=$row['CP_MOUSE_NUMBER'];
            $URSRC_btrysrlno=$row['CP_BATTERY_SERIAL_NUMBER'];
            $URSRC_dooracess=$row['CPD_DOOR_ACCESS'];
            $URSRC_idcard=$row['CPD_ID_CARD'];
            $URSRC_headset=$row['CPD_HEADSET'];
            $URSRC_bankname=$row['EMP_BANK_NAME'];
            $URSRC_brancname=$row['EMP_BRANCH_NAME'];
            $URSRC_acctname=$row['EMP_ACCOUNT_NAME'];
            $URSRC_acctno=$row['EMP_ACCOUNT_NO'];
            $URSRC_acctype=$row['EMP_ACCOUNT_TYPE'];
            $URSRC_ifsccode=$row['EMP_IFSC_CODE'];
            $URSRC_branchaddr=$row['EMP_BRANCH_ADDRESS'];
            $URSRC_aadharno=$row['EMP_AADHAAR_NO'];
            $URSRC_passportno=$row['EMP_PASSPORT_NO'];
            $URSRC_voterid=$row['EMP_VOTER_ID'];
            $URSRC_comment=$row['EMP_COMMENTS'];
            $final_values=(object)['joindate'=>$join_date,'rcname' => $URSRC_rcname,'emp_type'=>$URSRC_EMP_TYPE,'firstname'=>$URSRC_firstname,'lastname'=>$URSRC_lastname,'dob'=>$URSRC_dob,'designation'=>$URSRC_designation,'mobile'=>$URSRC_mobile,'kinname'=>$URSRC_kinname,'relationhood'=>$URSRC_relationhd,'altmobile'=>$URSRC_Mobileno,'Houseno'=>$URSRC_Houseno,'Streetname'=>$URSRC_Streetname,'Area'=>$URSRC_Area,'Postalcode'=>$URSRC_Postalcode,'laptop'=>$URSRC_laptopno,'chargerno'=>$URSRC_chrgrno,'bag'=>$URSRC_bag,'mouse'=>$URSRC_mouse,'dooraccess'=>$URSRC_dooracess,'idcard'=>$URSRC_idcard,'headset'=>$URSRC_headset,'bankname'=>$URSRC_bankname,'branchname'=>$URSRC_brancname,'accountname'=>$URSRC_acctname,'accountno'=>$URSRC_acctno,'ifsccode'=>$URSRC_ifsccode,'accountype'=>$URSRC_acctype,'branchaddress'=>$URSRC_branchaddr,'aadharno'=>$URSRC_aadharno,'passportno'=>$URSRC_passportno,'voterid'=>$URSRC_voterid,'comment'=>$URSRC_comment,'batryslno'=>$URSRC_btrysrlno];
        }
        $URSRC_values[]=array($final_values,$get_rolecreation_array,$emp_uploadfilelist[0],$emp_uploadfilelist[1],$emp_uploadfilelist[2],$get_lname_array);
        echo json_encode($URSRC_values);
    }

    if($_REQUEST['option']=="login_db"){
//        $ACR_loginid= $_REQUEST['loinid_lap_val'];
//        echo $ACR_loginid;
//        exit;
        $active_emp=get_active_emp_id();

        //RELATIONHOOD
//        $ULDID_result=mysqli_query($con," SELECT ULD_ID FROM VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS WHERE ULD_LOGINID='$ACR_loginid'");
//        $get_uldid_array=array();
//        while($row=mysqli_fetch_array($ULDID_result)){
//            $get_uldid_array[]=$row["ULD_ID"];
//        }
        //RELATIONHOOD
        $rname_result=mysqli_query($con,"SELECT URC_DATA FROM USER_RIGHTS_CONFIGURATION WHERE CGN_ID=22 ORDER BY URC_DATA");
        $get_rname_array=array();
        while($row=mysqli_fetch_array($rname_result)){
            $get_rname_array[]=$row["URC_DATA"];
        }
        //LAPTOP NUMBER
        $lname_result=mysqli_query($con,"SELECT CP_LAPTOP_NUMBER FROM COMPANY_PROPERTIES WHERE CP_FLAG IS NULL  ORDER BY CP_LAPTOP_NUMBER");
//        $lname_result=mysqli_query($con,"SELECT CP_LAPTOP_NUMBER FROM COMPANY_PROPERTIES WHERE CP_ID=(SELECT CP_ID FROM COMPANY_PROPERTIES_DETAILS WHERE EMP_ID=(SELECT EMP_ID FROM EMPLOYEE_DETAILS WHERE ULD_ID='$get_uldid_array'))UNION SELECT CP_LAPTOP_NUMBER FROM COMPANY_PROPERTIES WHERE CP_FLAG IS NULL");
        $get_lname_array=array();
        while($row=mysqli_fetch_array($lname_result)){
            $get_lname_array[]=$row["CP_LAPTOP_NUMBER"];
        }
//        print_r($get_uldid_array);
//        exit;
        //DESIGNATION
        $rdesgn_result=mysqli_query($con,"SELECT * FROM EMPLOYEE_DESIGNATION ORDER BY ED_DESIGNATION");
        $get_rdesgn_array=array();
        while($row=mysqli_fetch_array($rdesgn_result)){
            $get_rdesgn_array[]=$row["ED_DESIGNATION"];
        }
        //ACCOUNT TYPE
        $acctype_result=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE CGN_ID=21 ORDER BY URC_DATA");
        $get_acctype_array=array();
        while($row=mysqli_fetch_array($acctype_result)){
            $get_acctype_array[]=$row["URC_DATA"];
        }
        $final_array=array($active_emp,$get_rname_array,$get_lname_array,$get_rdesgn_array,$get_acctype_array);
        echo json_encode($final_array);
    }
//LOGIN CREATION UPATE FORM
//    if($_REQUEST['option']=="loginupdate")
    if ($_POST['URSRC_submitupdate']=="UPDATE")
    {
        $user_filelist=array();
        $user_filelist=$_POST['uploadfilelist'];
        $rolename=$_POST['roles1'];
        $rolename=str_replace("_"," ",$rolename);
        $joindate=$_POST['URSRC_tb_joindate'];
        $emp_type=$_POST['URSRC_lb_selectemptype'];
        $loginid=$_POST['URSRC_tb_loginidupd'];
        $oldloginid=$_POST['URSRC_lb_loginid'];
        $URSRC_firstname=$_POST['URSRC_tb_firstname'];
        $URSRC_lastname=$_POST['URSRC_tb_lastname'];
        $URSRC_dob=$_POST['URSRC_tb_dob'];
        $URSRC_finaldob = date('Y-m-d',strtotime($URSRC_dob));
        $URSRC_designation=$_POST['URSRC_tb_designation'];
        $URSRC_Mobileno=$_POST['URSRC_tb_permobile'];
        $URSRC_kinname=$_POST['URSRC_tb_kinname'];
        $URSRC_relationhd=$_POST['URSRC_lb_selectrelationhd'];
        $URSRC_mobile=$_POST['URSRC_tb_mobile'];
        $URSRC_Houseno=$_POST['URSRC_tb_houseno'];
        $URSRC_Streetname=$_POST['URSRC_tb_strtname'];
        $URSRC_Area=$_POST['URSRC_tb_area'];
        $URSRC_Postalcode=$_POST['URSRC_tb_pstlcode'];
        $URSRC_bankname=$_POST['URSRC_tb_bnkname'];
        $URSRC_brancname=$_POST['URSRC_tb_brnchname'];
        $URSRC_acctname=$_POST['URSRC_tb_accntname'];
        $URSRC_acctno=$_POST['URSRC_tb_accntno'];
        $URSRC_ifsccode=$_POST['URSRC_tb_ifsccode'];
        $URSRC_acctype=$_POST['URSRC_tb_accntyp'];
        $URSRC_branchaddr1=$_POST['URSRC_ta_brnchaddr'];
        $URSRC_branchaddr= $con->real_escape_string($URSRC_branchaddr1);
        $URSRC_laptopno=$_POST['URSRC_lb_selectlaptopno'];
        $URSRC_chrgrno=$_POST['URSRC_tb_chargerno'];
//        echo $URSRC_laptopno;
        if($URSRC_laptopno=='SELECT')
        {
            $URSRC_laptopno="''";
            $URSRC_chrgrno="''";
        }
        else{
            $URSRC_laptopno="'$URSRC_laptopno'";
            $URSRC_chrgrno="'$URSRC_chrgrno'";
        }
        $URSRC_aadharno=$_POST['URSRC_tb_aadharno'];
        $URSRC_passportno=$_POST['URSRC_tb_passportno'];
        $URSRC_voterid=$_POST['URSRC_tb_votersid'];
        $URSRC_comment=$_POST['URSRC_ta_comments'];
        $URSRC_comment= $con->real_escape_string($URSRC_comment);

        $URSRC_bag=$_POST['URSRC_tb_laptopno'];
//        if($URSRC_bag=='on')
//        {
//            $URSRC_bag= 'X';
//            $bag='YES';
//        }
//        else
//        {
//            $URSRC_bag='';
//            $bag='NO';
//        }
        $URSRC_mouse=$_POST['URSRC_tb_mouse'];
        $URSRC_chklapbag=$_REQUEST['URSRC_chk_bag'];
        $URSRC_bag=$_POST['URSRC_tb_laptopno'];
        $URSRC_chkmouse=$_REQUEST['URSRC_chk_mouse'];
        $URSRC_mouse=$_POST['URSRC_tb_mouse'];
        if($URSRC_chkmouse=='on')
        {
            $URSRC_mouse;
        }
        else
        {
            $URSRC_mouse='';
        }
        $URSRC_btryslno=$_POST['URSRC_tb_btry'];
//        if($URSRC_mouse=='on')
//        {
//            $URSRC_mouse= 'X';
//            $mouse='YES';
//        }
//        else
//        {
//            $URSRC_mouse='';
//            $mouse='NO';
//        }
        $URSRC_dooracess=$_POST['URSRC_chk_dracess'];
        if($URSRC_dooracess=='on')
        {
            $URSRC_dooracess= 'X';
            $dooraccess='YES';
        }
        else
        {
            $URSRC_dooracess='';
            $dooraccess='NO';
        }
        $URSRC_idcard=$_POST['URSRC_chk_idcrd'];
        if($URSRC_idcard=='on')
        {
            $URSRC_idcard= 'X';
            $idcard='YES';
        }
        else
        {
            $URSRC_idcard='';
            $idcard='NO';
        }
        $URSRC_headset=$_POST['URSRC_chk_headset'];
        if($URSRC_headset=='on')
        {
            $URSRC_headset= 'X';
            $headset='YES';
        }
        else
        {
            $URSRC_headset='';
            $headset='NO';
        }
        $URSRC_chkaadharno=$_POST['URSRC_chk_aadharno'];
        if($URSRC_chkaadharno=='on')
        {
            $URSRC_aadharno;
        }
        else
        {
            $URSRC_aadharno='';
        }
        $URSRC_chkpassportno=$_POST['URSRC_chk_passportno'];
        if($URSRC_chkpassportno=='on')
        {
            $URSRC_passportno;
        }
        else
        {
            $URSRC_passportno='';
        }
        $URSCR_chkvoterid=$_POST['URSRC_chk_votersid'];
        if($URSCR_chkvoterid=='on')
        {
            $URSRC_voterid;
        }
        else
        {
            $URSRC_voterid='';
        }
        $sql="select * from USER_LOGIN_DETAILS where ULD_LOGINID='$oldloginid'";
        $sql_result= mysqli_query($con,$sql);
        if($row=mysqli_fetch_array($sql_result)){
            $ULD_id=$row["ULD_ID"];
        }
        $finaldate = date('Y-m-d',strtotime($joindate));
        $select_last_joindate= "SELECT  DATE_FORMAT(UA_JOIN_DATE,'%Y-%m-%d') as UA_JOIN_DATE  FROM USER_ACCESS where UA_REC_VER=(select MAX(UA_REC_VER) as UA_REC_VER_MAX from USER_ACCESS where ULD_ID='$ULD_id' AND UA_TERMINATE IS NULL)AND ULD_ID='$ULD_id'";
        $select_last_joindate_result=mysqli_query($con,$select_last_joindate);
        if($row=mysqli_fetch_array($select_last_joindate_result)){

            $lastdate=$row['UA_JOIN_DATE'];
        }
        $con->autocommit(false);
//        echo "CALL SP_TS_LOGIN_UPDATE($ULD_id,'$loginid','$rolename','$finaldate','$emp_type','$URSRC_firstname','$URSRC_lastname','$URSRC_finaldob','$URSRC_designation','$URSRC_Mobileno','$URSRC_kinname','$URSRC_relationhd','$URSRC_mobile','$URSRC_bankname','$URSRC_brancname','$URSRC_acctname','$URSRC_acctno','$URSRC_ifsccode','$URSRC_acctype','$URSRC_branchaddr','$URSRC_aadharno','$URSRC_passportno','$URSRC_voterid','$URSRC_comment',$URSRC_laptopno,$URSRC_chrgrno,'$URSRC_bag','$URSRC_mouse','$URSRC_dooracess','$URSRC_idcard','$URSRC_headset','$USERSTAMP','$URSRC_Houseno','$URSRC_Streetname','$URSRC_Area','$URSRC_Postalcode','$URSRC_btryslno',@success_flag)";
//        exit;
//exit;
        $result = $con->query("CALL SP_TS_LOGIN_UPDATE($ULD_id,'$loginid','$rolename','$finaldate','$emp_type','$URSRC_firstname','$URSRC_lastname','$URSRC_finaldob','$URSRC_designation','$URSRC_Mobileno','$URSRC_kinname','$URSRC_relationhd','$URSRC_mobile','$URSRC_bankname','$URSRC_brancname','$URSRC_acctname','$URSRC_acctno','$URSRC_ifsccode','$URSRC_acctype','$URSRC_branchaddr','$URSRC_aadharno','$URSRC_passportno','$URSRC_voterid','$URSRC_comment',$URSRC_laptopno,$URSRC_chrgrno,'$URSRC_bag','$URSRC_mouse','$URSRC_dooracess','$URSRC_idcard','$URSRC_headset','$USERSTAMP','$URSRC_Houseno','$URSRC_Streetname','$URSRC_Area','$URSRC_Postalcode','$URSRC_btryslno',@success_flag)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @success_flag');
        $result = $select->fetch_assoc();
        $flag= $result['@success_flag'];
//        echo $flag;
        if($flag==1){
//            echo 'echo 1';
            $cal_flag=1;
            if($lastdate!=$finaldate){
                $cal_flag= URSRC_delete_create_calendarevent($ULD_id,$URSRC_firstname,$finaldate);
                $updatemailflag=1;
                if($cal_flag==0){
                    $updatemailflag=0;
                    $con->rollback();
                }
            }
            $select_folderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=13");
            if($row=mysqli_fetch_array($select_folderid)){
                $folderid=$row["URC_DATA"];
            }

            $loginid_name = strtoupper(substr($loginid, 0, strpos($loginid, '@')));
            if(substr($loginid_name, 0, strpos($loginid_name, '.'))){

                $loginid_name = strtoupper(substr($loginid_name, 0, strpos($loginid_name, '.')));

            }
            else{
                $loginid_name=$loginid_name;
            }
            $select_fileid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=9");
            if($row=mysqli_fetch_array($select_fileid)){
                $ss_fileid=$row["URC_DATA"];
            }
            $select_codefileid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=35");
            if($row=mysqli_fetch_array($select_codefileid)){
                $ss_codefileid=$row["URC_DATA"];
            }
//COMMON FUNCTION FOR GEETING ADMIN FROM ID
//            echo 'echo 2';
            $select_admin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='ADMIN'";
            $select_sadmin="SELECT * FROM VW_ACCESS_RIGHTS_TERMINATE_LOGINID WHERE URC_DATA='SUPER ADMIN'";
            $admin_rs=mysqli_query($con,$select_admin);
            $sadmin_rs=mysqli_query($con,$select_sadmin);
//            $admin=array();
            while($row=mysqli_fetch_array($admin_rs)){
                $admin=$row["ULD_LOGINID"];//get admin
            }
            if($row=mysqli_fetch_array($sadmin_rs)){
                $sadmin=$row["ULD_LOGINID"];//get super admin
            }
            $cclist=array($admin,$sadmin);
            //END

//            echo 'echo 3';
            if($oldloginid!=$loginid){

                $select_link=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=4");
                if($row=mysqli_fetch_array($select_link)){
                    $site_link=$row["URC_DATA"];
                }
                $select_ss_link=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=5");
                if($row=mysqli_fetch_array($select_ss_link)){
                    $ss_link=$row["URC_DATA"];
                }

                $select_youtubelink=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=12");
                if($row=mysqli_fetch_array($select_youtubelink)){
                    $youtubelink=$row["URC_DATA"];
                }
                //CODE  OPTIMIZATION
                $select_code=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=34");
                if($row=mysqli_fetch_array($select_code)){
                    $codeoptimi=$row["URC_DATA"];
                }

                $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=1";
                $select_template_rs=mysqli_query($con,$select_template);
                if($row=mysqli_fetch_array($select_template_rs)){
                    $mail_subject=$row["ETD_EMAIL_SUBJECT"];
                    $body=$row["ETD_EMAIL_BODY"];
                }

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
                $fileId=$ss_fileid;
                $codeopti_fileId=$ss_codefileid;
                $value=$loginid;
                $type='user';
                $role='reader';
                $email=$loginid;

                $newPermission = new Google_Service_Drive_Permission();
                $newPermission->setValue($value);
                $newPermission->setType($type);
                $newPermission->setRole($role);
                $newPermission->setEmailAddress($email);

//                echo 'ech 4';
                try {
                    $service->permissions->insert($fileId, $newPermission);
                    $service->permissions->insert($codeopti_fileId, $newPermission);
                    $ss_flag=1;
                } catch (Exception $e) {
                    $ss_flag=0;
                    $con->rollback();
                }
                if($ss_flag==1){
                    $upload_flag=0;
                    //File upload function
                    $file_array=array();

//                    for($iv=0;i<count($user_filelist);$iv++)
//                    {
//                        echo $user_filelist[$iv];
//                        removeFilepermission($service,$user_filelist[$iv]);
//                    }
                    if($cal_flag==1){
                        $new_empfolderid=UploadEmployeeFiles("login_update",$loginid);
                        if($new_empfolderid==""){
                            URSRC_unshare_document($loginid,$fileId);
                            URSRC_unshare_document($loginid,$codeopti_fileId);
                            $con->rollback();
                            echo "Error:Folder id Not present";
                            exit;}
                        $removedfilelist= trashFile($new_empfolderid,$user_filelist);
                        if(!empty($_FILES))
                        {
                            foreach($_FILES['UPD_uploaded_files']['name'] as $idx => $name) {
                                if($name=="")continue;
                                $upload_flag=1;
                                $ufilename=$name;
                                $ufiletempname=$_FILES['UPD_uploaded_files']['tmp_name'][$idx];
                                $ufiletype=$_FILES['UPD_uploaded_files']['type'][$idx];
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
                                $access_token=$drive->getAccessToken();
                                $refresh_token=$Refresh_Token;
                                $drive->refreshToken($refresh_token);
                                $service = new Google_Service_Drive($drive);
                                $file_id_value =insertFile($service,$ufilename,'PersonalDetails',$new_empfolderid,$ufiletype,$ufiletempname);
                                if($file_id_value!=''){
                                    array_push($file_array,$file_id_value);
                                }
                            }
                        }
                        if($upload_flag==1&&count($file_array)==0){
                            $file_flag=0;
                            URSRC_unshare_document($loginid,$fileId);
                            URSRC_unshare_document($loginid,$codeopti_fileId);
                            $con->rollback();
                        }
                    }

                    //End of File Uploads
                }
//echo 'echo 5';
                if($upload_flag==1){
                    if(($ss_flag==1) && (count($file_array)>0)){
//                    $cal_flag= URSRC_delete_create_calendarevent($ULD_id,$URSRC_firstname,$finaldate);
                        if($cal_flag==0){
                            URSRC_unshare_document($loginid,$fileId);
                            URSRC_unshare_document($loginid,$codeopti_fileId);
//                        $new_empfolderid=UploadEmployeeFiles("login_update",$loginid);
//                        for($i=0;$i<count($file_array);$i++){
//                            delete_file($service,$file_array[$i]);
//
//                        }
                            $con->rollback();
//                        $login_empid=getEmpfolderName($loginid);
//                        renamefile($service,$login_empid,$new_empfolderid);
//                        for($v=0;$v<count($removedfilelist);$v++)
//                        {
//                            restoreFile($service,$removedfilelist[$v]);
//                        }
                        }
                    }
                }
                else{

                    if(($ss_flag==1)){
//                        $cal_flag= URSRC_delete_create_calendarevent($ULD_id,$URSRC_firstname,$finaldate);
                        if($cal_flag==0){
                            URSRC_unshare_document($loginid,$fileId);
                            URSRC_unshare_document($loginid,$codeopti_fileId);
                            $con->rollback();
//                            $login_empid=getEmpfolderName($loginid);
//                            renamefile($service,$login_empid,$new_empfolderid);
                        }

                    }

                }

//                echo 'echo 6';
                if(($ss_flag==1)&&($cal_flag==1)){
                    URSRC_unshare_document($oldloginid,$fileId);
                    URSRC_unshare_document($oldloginid,$codeopti_fileId);
                    $email_body;
                    $body_msg =explode("^", $body);
                    $length=count($body_msg);
                    for($i=0;$i<$length;$i++){
                        $email_body.=$body_msg[$i].'<br><br>';
                    }
                    $replace= array("[LOGINID]", "[LINK]","[SSLINK]", "[VLINK]","[DES]","[CLINK]");
                    $str_replaced  = array($URSRC_firstname,$site_link, $ss_link, $youtubelink,'<b>'.$URSRC_designation.'</b>',$codeoptimi);
                    $final_message = str_replace($replace, $str_replaced, $email_body);
                    $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=10";
                    $select_template_rs=mysqli_query($con,$select_template);
                    if($row=mysqli_fetch_array($select_template_rs)){
                        $mail_subject1=$row["ETD_EMAIL_SUBJECT"];
                        $body=$row["ETD_EMAIL_BODY"];
                    }
                    $select_displayname="SELECT URC_DATA FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=26";
                    $select_displayname_rs=mysqli_query($con,$select_displayname);
                    if($row=mysqli_fetch_array($select_displayname_rs)){
                        $mail_dispalyname1=$row["URC_DATA"];
                    }
                    //not aplicable
                    $URSRC_bag=$_POST['URSRC_tb_laptopno'];
                    if($URSRC_bag!='')
                    {
                        $URSRC_bag;
                    }
                    else{
                        $URSRC_bag="N/A";
                    }
                    if($URSRC_laptopno=='')
                    {
                        $URSRC_laptopno="N/A";
                    }
                    else{
                        $URSRC_laptopno=$_POST['URSRC_lb_selectlaptopno'];
                    }
                    if($URSRC_chrgrno=='')
                    {
                        $URSRC_chrgrno="N/A";
                    }
                    else{
                        $URSRC_chrgrno=$_POST['URSRC_tb_chargerno'];
                    }
                    if($URSRC_btryslno=='')
                    {
                        $URSRC_btryslno="N/A";
                    }
                    else{
                        $URSRC_btryslno=$_POST['URSRC_tb_btry'];
                    }
                    if($URSRC_chkaadharno=='on')
                    {
                        $URSRC_aadharno;
                    }
                    else
                    {
                        $URSRC_aadharno="N/A";
                    }
                    if($URSRC_chkpassportno=='on')
                    {
                        $URSRC_passportno;
                    }
                    else
                    {
                        $URSRC_passportno="N/A";
                    }
                    if($URSCR_chkvoterid=='on')
                    {
                        $URSRC_voterid;
                    }
                    else
                    {
                        $URSRC_voterid="N/A";
                    }
                    if($URSRC_chkmouse=='on')
                    {
                        $URSRC_mouse;
                    }
                    else
                    {
                        $URSRC_mouse="N/A";
                    }
                    //not applicable
//STRING REPLACE FUNCTION
//                    echo 'echo 7';
                    $emp_email_body;
                    $body_msg =explode("^", $body);
                    $length=count($body_msg);
                    for($i=0;$i<$length;$i++){
                        $emp_email_body.=$body_msg[$i].'<br><br>';
                    }
                    $comment_per =explode("\n", $URSRC_comment);
                    $commnet_length=count($comment_per);
                    for($i=0;$i<$commnet_length;$i++){
                        $comment_msgper.=$comment_per[$i].'<br>';
                    }
                    $comment =explode("\n", $URSRC_branchaddr1);
                    $commnet_length=count($comment);
                    for($i=0;$i<$commnet_length;$i++){
                        $comment_msg.=$comment[$i].'<br>';
                    }
//                    echo $URSRC_btryslno.'a',$URSRC_chrgrno.'b',$URSRC_bag.'c',$URSRC_mouse.'d',$dooraccess;
                    $replace= array("[LOGINID]","[FNAME]","[LNAME]", "[DOB]","[JDATE]","[DESG]","[MOBNO]","[KINNAME]","[REL]","[ALTMOBNO]","[LAPNO]","[CHRNO]","[LAPBAG]","[MOUSE]","[DACC]","[IDCARD]","[HEADSET]","[BANKNAME]","[BRANCHNAME]","[ACCNAME]","[ACCNO]","[IFSCCODE]","[ACCTYPE]","[BANKADDRESS]","PERSONAL DETAILS:","COMPANY PROPERTIES DETAILS:","BANK ACCOUNT DETAILS:","[AADHAAR NO]","[PASSPORT NO]","[VOTERS ID NO]","[HOUSE NO]","[STREET NAME]","[PINCODE]","[AREA]","[COMMENTS]","[BSNO]");
                    $str_replaced  = array($URSRC_firstname,$URSRC_firstname, $URSRC_lastname, $URSRC_dob,$joindate,$URSRC_designation,$URSRC_Mobileno,$URSRC_kinname,$URSRC_relationhd,$URSRC_mobile,$URSRC_laptopno,$URSRC_chrgrno,$URSRC_bag,$URSRC_mouse,$dooraccess,$idcard,$headset,$URSRC_bankname,$URSRC_brancname,$URSRC_acctname,$URSRC_acctno,$URSRC_ifsccode,$URSRC_acctype,$comment_msg,'<b>'."PERSONAL DETAILS:".'</b>','<b>'."COMPANY PROPERTIES DETAILS:".'</b>','<b>'."BANK ACCOUNT DETAILS:".'</b>',$URSRC_aadharno,$URSRC_passportno,$URSRC_voterid,$URSRC_Houseno,$URSRC_Streetname,$URSRC_Postalcode,$URSRC_Area,$comment_permsg,$URSRC_btryslno);


//                    $replace= array( "[FNAME]","[LNAME]", "[DOB]","[DESG]","[MOBNO]","[KINNAME]","[REL]","[ALTMOBNO]","[LAPNO]","[CHRNO]","[LAPBAG]","[MOUSE]","[DACC]","[IDCARD]","[HEADSET]","[HOUSENO]","[STREETNAME]","[AREA]","[POSTALCODE]","[BANKNAME]","[BRANCHNAME]","[ACCNAME]","[ACCNO]","[IFSCCODE]","[ACCTYPE]","[BANKADDRESS]","PERSONAL DETAILS:","COMPANY PROPERTIES DETAILS:","BANK ACCOUNT DETAILS:","[AADHAAR NO]","[PASSPORT NO]","[VOTERS ID NO]","[HOUSE NO]","[STREET NAME]","[PINCODE]","[AREA]","[COMMENTS]","[BSNO]");
//                    $str_replaced  = array($URSRC_firstname, $URSRC_lastname, $URSRC_dob,$URSRC_designation,$URSRC_Mobileno,$URSRC_kinname,$URSRC_relationhd,$URSRC_mobile,$URSRC_Houseno,$URSRC_Streetname,$URSRC_Area,$URSRC_Postalcode,$URSRC_laptopno,$URSRC_chrgrno,$URSRC_bag,$URSRC_mouse,$dooraccess,$idcard,$headset,$URSRC_bankname,$URSRC_brancname,$URSRC_acctname,$URSRC_acctno,$URSRC_ifsccode,$URSRC_acctype,$comment_msg,'<b>'."PERSONAL DETAILS:".'</b>','<b>'."COMPANY PROPERTIES DETAILS:".'</b>','<b>'."BANK ACCOUNT DETAILS:".'</b>',$URSRC_aadharno,$URSRC_passportno,$URSRC_voterid,$URSRC_Houseno,$URSRC_Streetname,$URSRC_Postalcode,$URSRC_Area,$comment_msgper,$URSRC_btryslno);
                   $newphrase = str_replace($replace, $str_replaced, $emp_email_body);
                    $final_message=$final_message.'<br>'.$newphrase;
                    //SENDING MAIL OPTIONS
                    $name = $mail_dispalyname1;
                    $from = 'lalitha.rajendiran@ssomens.com';//$admin;
                    try {
                        $message1 = new Message();
                        $message1->setSender($name.'<'.$from.'>');
                        $message1->addTo('lalitha.rajendiran@ssomens.com');
//                        $message1->addCc($cclist);
                        $message1->setSubject($mail_subject1);
                        $message1->setHtmlBody($final_message);
                        $message1->send();
                    } catch (\InvalidArgumentException $e) {
                        echo $e;
                    }
                    $select_intro_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=14";
                    $select_introtemplate_rs=mysqli_query($con,$select_intro_template);
                    if($row=mysqli_fetch_array($select_introtemplate_rs)){
                        $intro_mail_subject=$row["ETD_EMAIL_SUBJECT"];
                        $intro_body=$row["ETD_EMAIL_BODY"];
                    }
                    $select_intro_displayname="SELECT URC_DATA FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=26";
                    $select_displayname_rs=mysqli_query($con,$select_intro_displayname);
                    if($row=mysqli_fetch_array($select_displayname_rs)){
                        $intro_mail_displayname=$row["URC_DATA"];
                    }
                    $intro_email_body;
                    $intro_body_msg =explode("^", $intro_body);
                    $intro_length=count($intro_body_msg);
                    for($i=0;$i<$intro_length;$i++){
                        $intro_email_body.=$intro_body_msg[$i].'<br><br>';
                    }
                    $replace= array("[DATE]", "[employee name]","[emailid]","[designation]");
                    $str_replaced  = array(date("d-m-Y"),'<b>'.$URSRC_firstname.'</b>', $loginid,'<b>'.$URSRC_designation.'</b>');
                    $intro_message = str_replace($replace, $str_replaced, $intro_email_body);
                    $cc_array=get_active_login_id();
//                    $cc_array=['punitha.shanmugam@ssomens.com'];
                    //SENDING MAIL OPTIONS
                    $name = $intro_mail_displayname;
                    $from = 'lalitha.rajendiran@ssomens.com';//$admin;
                    try {
                        $message1 = new Message();
                        $message1->setSender($name.'<'.$from.'>');
//                        $message1->addTo($cc_array);
                        $message1->addTo('lalitha.rajendiran@ssomens.com');
//                        $message1->addCc($sadmin);
                        $message1->setSubject($intro_mail_subject);
                        $message1->setHtmlBody($intro_message);
                        $message1->send();
                    } catch (\InvalidArgumentException $e) {
                        echo $e;
                    }


                }
            }
            else{
                $ss_flag=1;
                if($cal_flag!=0)
                {
                    $cal_flag=1;
                }
                if($cal_flag==1)
                {
                    //File upload function
                    $file_array=array();
                    $new_empfolderid=UploadEmployeeFiles("login_update",$loginid);
                    if($new_empfolderid==""){
                        URSRC_unshare_document($loginid,$fileId);
                        URSRC_unshare_document($loginid,$codeopti_fileId);
                        $con->rollback();
                        echo "Error:Folder id Not present";
                        exit;}
                    $removedfilelist=trashFile($new_empfolderid,$user_filelist);
                    if(!empty($_FILES))
                    {
                        foreach($_FILES['UPD_uploaded_files']['name'] as $idx => $name) {
                            if($name=="")continue;
                            $upload_flag=1;
                            $ufilename=$name;
                            $ufiletempname=$_FILES['UPD_uploaded_files']['tmp_name'][$idx];
                            $ufiletype=$_FILES['UPD_uploaded_files']['type'][$idx];
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
                            $access_token=$drive->getAccessToken();
                            $refresh_token=$Refresh_Token;
                            $drive->refreshToken($refresh_token);
                            $service = new Google_Service_Drive($drive);
                            $file_id_value =insertFile($service,$ufilename,'PersonalDetails',$new_empfolderid,$ufiletype,$ufiletempname);
                            if($file_id_value!=''){
                                array_push($file_array,$file_id_value);
                            }
                        }
                    }
                }
                if($upload_flag==1&&count($file_array)==0){
                    $file_flag=0;
                    URSRC_unshare_document($loginid,$fileId);
                    URSRC_unshare_document($loginid,$codeopti_fileId);
                    $con->rollback();
                    $login_empid=getEmpfolderName($loginid);
                    renamefile($service,$login_empid,$new_empfolderid);
                }
                //UPDATE PART SENDING MAIL
                $select_template="SELECT * FROM EMAIL_TEMPLATE_DETAILS WHERE ET_ID=16";
                $select_template_rs=mysqli_query($con,$select_template);
                if($row=mysqli_fetch_array($select_template_rs)){
                    $mail_subject1=$row["ETD_EMAIL_SUBJECT"];
                    $body=$row["ETD_EMAIL_BODY"];
                }
                $select_template="SELECT URC_DATA FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=26";
                $select_template_rs=mysqli_query($con,$select_template);
                if($row=mysqli_fetch_array($select_template_rs)){
                    $mail_display1=$row["URC_DATA"];
                }
                //not aplicable
                $URSRC_bag=$_POST['URSRC_tb_laptopno'];
                if($URSRC_bag!='')
                {
                    $URSRC_bag;
                }
                else{
                    $URSRC_bag="N/A";
                }
                if($URSRC_laptopno=='')
                {
                    $URSRC_laptopno="N/A";
                }
                else{
                    $URSRC_laptopno=$_POST['URSRC_lb_selectlaptopno'];
                }
                if($URSRC_chrgrno=='')
                {
                    $URSRC_chrgrno="N/A";
                }
                else{
                    $URSRC_chrgrno=$_POST['URSRC_tb_chargerno'];
                }
                if($URSRC_chkaadharno=='on')
                {
                    $URSRC_aadharno;
                }
                else
                {
                    $URSRC_aadharno="N/A";
                }
                if($URSRC_chkpassportno=='on')
                {
                    $URSRC_passportno;
                }
                else
                {
                    $URSRC_passportno="N/A";
                }
                if($URSCR_chkvoterid=='on')
                {
                    $URSRC_voterid;
                }
                else
                {
                    $URSRC_voterid="N/A";
                }
                if($URSRC_chkmouse=='on')
                {
                    $URSRC_mouse;
                }
                else
                {
                    $URSRC_mouse="N/A";
                }
                //not applicable
                //STRING REPLACE FUNCTION
                $emp_email_body;
                $body_msg =explode("^", $body);
                $length=count($body_msg);
                for($i=0;$i<$length;$i++){
                    $emp_email_body.=$body_msg[$i].'<br><br>';
                }
                $comment_per =explode("\n", $URSRC_comment);
                $commnet_length=count($comment_per);
                for($i=0;$i<$commnet_length;$i++){
                    $comment_msgper.=$comment_per[$i].'<br>';
                }
                $comment =explode("\n", $URSRC_branchaddr1);
                $commnet_length=count($comment);
                for($i=0;$i<$commnet_length;$i++){
                    $comment_msg.=$comment[$i].'<br>';
                }
                $replace= array("[LOGINID]", "[FNAME]","[LNAME]", "[DOB]","[JDATE]","[DESG]","[MOBNO]","[KINNAME]","[REL]","[ALTMOBNO]","[LAPNO]","[CHRNO]","[LAPBAG]","[MOUSE]","[DACC]","[IDCARD]","[HEADSET]","[BANKNAME]","[BRANCHNAME]","[ACCNAME]","[ACCNO]","[IFSCCODE]","[ACCTYPE]","[BANKADDRESS]","PERSONAL DETAILS:","COMPANY PROPERTIES DETAILS:","BANK ACCOUNT DETAILS:","[AADHAAR NO]","[PASSPORT NO]","[VOTERS ID NO]","[HOUSE NO]","[STREET NAME]","[PINCODE]","[AREA]","[COMMENTS]","[BSNO]");
                $str_replaced  = array($URSRC_firstname,$URSRC_firstname, $URSRC_lastname, $URSRC_dob,$joindate,$URSRC_designation,$URSRC_Mobileno,$URSRC_kinname,$URSRC_relationhd,$URSRC_mobile,$URSRC_laptopno,$URSRC_chrgrno,$URSRC_bag,$URSRC_mouse,$dooraccess,$idcard,$headset,$URSRC_bankname,$URSRC_brancname,$URSRC_acctname,$URSRC_acctno,$URSRC_ifsccode,$URSRC_acctype,$comment_msg,'<b>'."PERSONAL DETAILS:".'</b>','<b>'."COMPANY PROPERTIES DETAILS:".'</b>','<b>'."BANK ACCOUNT DETAILS:".'</b>',$URSRC_aadharno,$URSRC_passportno,$URSRC_voterid,$URSRC_Houseno,$URSRC_Streetname,$URSRC_Postalcode,$URSRC_Area,$comment_permsg,$URSRC_btryslno);

//                echo $URSRC_laptopno.'a',$URSRC_chrgrno.'b',$URSRC_bag.'c',$URSRC_mouse.'d',$dooraccess;
//                $replace= array("[LOGINID]", "[FNAME]","[LNAME]", "[DOB]", "[JDATE]","[DESG]","[MOBNO]","[KINNAME]","[REL]","[ALTMOBNO]","[LAPNO]","[CHRNO]","[LAPBAG]","[MOUSE]","[DACC]","[IDCARD]","[HEADSET]","[HOUSENO]","[STREETNAME]","[AREA]","[POSTALCODE]","[BANKNAME]","[BRANCHNAME]","[ACCNAME]","[ACCNO]","[IFSCCODE]","[ACCTYPE]","[BANKADDRESS]","PERSONAL DETAILS:","COMPANY PROPERTIES DETAILS:","BANK ACCOUNT DETAILS:","[AADHAAR NO]","[PASSPORT NO]","[VOTERS ID NO]","[HOUSE NO]","[STREET NAME]","[PINCODE]","[AREA]","[COMMENTS]","[BSNO]");
//                $str_replaced  = array($URSRC_firstname,$URSRC_firstname, $URSRC_lastname, $URSRC_dob,$joindate,$URSRC_designation,$URSRC_Mobileno,$URSRC_kinname,$URSRC_relationhd,$URSRC_mobile,$URSRC_Houseno,$URSRC_Streetname,$URSRC_Area,$URSRC_Postalcode,$URSRC_laptopno,$URSRC_chrgrno,$URSRC_bag,$URSRC_mouse,$dooraccess,$idcard,$headset,$URSRC_bankname,$URSRC_brancname,$URSRC_acctname,$URSRC_acctno,$URSRC_ifsccode,$URSRC_acctype,$comment_msg,'<b>'."PERSONAL DETAILS:".'</b>','<b>'."COMPANY PROPERTIES DETAILS:".'</b>','<b>'."BANK ACCOUNT DETAILS:".'</b>',$URSRC_aadharno,$URSRC_passportno,$URSRC_voterid,$URSRC_Houseno,$URSRC_Streetname,$URSRC_Postalcode,$URSRC_Area,$comment_msgper,$URSRC_btryslno);

//                $replace= array("[LOGINID]", "[FNAME]","[LNAME]", "[DOB]", "[JDATE]","[DESG]","[MOBNO]","[KINNAME]","[REL]","[ALTMOBNO]","[LAPNO]","[CHRNO]","[LAPBAG]","[MOUSE]","[DACC]","[IDCARD]","[HEADSET]","[HOUSENO]","[STREETNAME]","[AREA]","[POSTALCODE]","[BANKNAME]","[BRANCHNAME]","[ACCNAME]","[ACCNO]","[IFSCCODE]","[ACCTYPE]","[BANKADDRESS]","PERSONAL DETAILS:","COMPANY PROPERTIES DETAILS:","BANK ACCOUNT DETAILS:","[AADHAAR NO]","[PASSPORT NO]","[VOTERS ID NO]");
//                $str_replaced  = array($URSRC_firstname,$URSRC_firstname, $URSRC_lastname, $URSRC_dob,$joindate,$URSRC_designation,$URSRC_Mobileno,$URSRC_kinname,$URSRC_relationhd,$URSRC_mobile,$URSRC_Houseno,$URSRC_Streetname,$URSRC_Area,$URSRC_Postalcode,$URSRC_laptopno,$URSRC_chrgrno,$bag,$mouse,$dooraccess,$idcard,$headset,$URSRC_bankname,$URSRC_brancname,$URSRC_acctname,$URSRC_acctno,$URSRC_ifsccode,$URSRC_acctype,$comment_msg,'<b>'."PERSONAL DETAILS:".'</b>','<b>'."COMPANY PROPERTIES DETAILS:".'</b>','<b>'."BANK ACCOUNT DETAILS:".'</b>',$URSRC_aadharno,$URSRC_passportno,$URSRC_voterid);
                $newphrase = str_replace($replace, $str_replaced, $emp_email_body);
                $final_message=$final_message.'<br>'.$newphrase;
                //SENDING MAIL OPTIONS
                if(($lastdate!=$finaldate) && ($updatemailflag==1)){

                    $name = $mail_display1;
                    $from = 'lalitha.rajendiran@ssomens.com';//$admin;
                    try {
                        $message1 = new Message();
                        $message1->setSender($name.'<'.$from.'>');
                        $message1->addTo('lalitha.rajendiran@ssomens.com');
//                        $message1->addCc($cclist);
                        $message1->setSubject($mail_subject1);
                        $message1->setHtmlBody($final_message);
                        $message1->send();
                    } catch (\InvalidArgumentException $e) {
                        echo $e;
                    }
                }
                else if(($updatemailflag==0) && ($lastdate!=$finaldate)){
                    $cal_flag=0;
                }
                else{
                    if($lastdate==$finaldate){
                        $cal_flag=1;

                        $name = $mail_display1;
                        $from = 'lalitha.rajendiran@ssomens.com';//$admin;
                        try {
                            $message1 = new Message();
                            $message1->setSender($name.'<'.$from.'>');
                            $message1->addTo('lalitha.rajendiran@ssomens.com');
//                            $message1->addCc($cclist);
                            $message1->setSubject($mail_subject1);
                            $message1->setHtmlBody($final_message);
                            $message1->send();
                        } catch (\InvalidArgumentException $e) {
                            echo $e;
                        }
                    }
                }
            }

            $flag_array=[$flag,$ss_flag,$cal_flag,$ss_fileid,$file_flag,$folderid];
//            echo $flag_array;
        }
        else{
            $flag_array=[$flag];

        }
        $con->commit();
        echo json_encode($flag_array);
    }
    //ROLE CREATION ENTRY
    if($_REQUEST['option']=="URSRC_check_role_id"){
        $URSRC_roleid=$_GET['URSRC_roleidval'];
        $sql="SELECT * FROM ROLE_CREATION where RC_NAME='$URSRC_roleid'";
        $sql_result= mysqli_query($con,$sql);
        $row=mysqli_num_rows($sql_result);
        $x=$row;
        if($x > 0)
        {
            $URSRC_already_exist_flag=1;
        }
        else{
            $URSRC_already_exist_flag=0;
        }
        echo ($URSRC_already_exist_flag);
    }
    //TREE VIEW
    if($_REQUEST['option']=="URSRC_tree_views"){
        $role_customemrole_name = $_REQUEST['URSRC_lbrole_srchndupdate'];
        $role_customemrole_name=str_replace("_"," ",$role_customemrole_name);
        $rcname_result=mysqli_query($con,"SELECT * FROM ROLE_CREATION RC,USER_RIGHTS_CONFIGURATION URC where URC.URC_ID=RC.URC_ID and RC_NAME='".$role_customemrole_name."' ORDER BY URC_DATA ");
        while($row=mysqli_fetch_array($rcname_result)){
            $get_urcdata_array=$row["URC_DATA"];
        }
        $mpid_result=mysqli_query($con,"SELECT * FROM ROLE_CREATION RC,USER_MENU_DETAILS  UMD,MENU_PROFILE MP where MP.MP_ID=UMD.MP_ID and UMD.RC_ID=RC.RC_ID and RC_NAME='".$role_customemrole_name."' ");
        $get_mpid_array=array();
        while($row=mysqli_fetch_array($mpid_result)){
            $get_mpid_array[]=$row["MP_ID"];
        }
        $get_urcdata_array=str_replace("_"," ",$get_urcdata_array);
        $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$get_urcdata_array."' ORDER BY MP_MNAME ASC ");
        $ure_values=array();
        $URSC_Main_menu_array=array();
        $i=0;
        while($row=mysqli_fetch_array($main_menu_data)){
            $URSC_Main_menu_array[]=$row["MP_MNAME"];
            $sub_menu_data= mysqli_query($con,"SELECT  MP_MSUB, MP.MP_ID FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$get_urcdata_array."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND MP.MP_MSUB IS NOT NULL GROUP BY MP_MSUB ORDER BY MP.MP_MSUB ASC ");
            $URSC_sub_menu_row=array();
            $URSC_sub_sub_menu_row_col=array();
            $URSC_sub_sub_menu_row_col_data=array();
            $j=0;
            while($row=mysqli_fetch_array($sub_menu_data))  {
                $URSC_sub_menu_row[]=array($row["MP_ID"],$row["MP_MSUB"]);
                $sub_sub_menu_data= mysqli_query($con,"SELECT MP.MP_ID, MP_MSUBMENU FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$get_urcdata_array ."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND  MP.MP_MSUB='".$URSC_sub_menu_row[$j][1]."' AND MP.MP_MSUBMENU IS NOT NULL  ORDER BY MP_MSUBMENU ASC" );
                $URSC_sub_sub_menu_row=array();
                $URSC_sub_sub_menu_row_data=array();
                while($row=mysqli_fetch_array($sub_sub_menu_data)){
                    $URSC_sub_sub_menu_row_data[]=array($row["MP_ID"],$row["MP_MSUBMENU"]);
                }
                $URSC_sub_sub_menu_row_col[]=$URSC_sub_sub_menu_row;
                $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
                $j++;
            }
            $URSC_sub_sub_menu_array[]=$URSC_sub_sub_menu_row_col;
            $URSC_sub_menu_array[]=$URSC_sub_menu_row;
            $i++;
        }
        $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array);
        $role_mpid_array=array($get_urcdata_array,$get_mpid_array,$final_values);
        echo json_encode($role_mpid_array);
    }
    //Role creation save and update & Basic role menu creation save and update
    if($_REQUEST['option']=="rolecreationsave")
    {
        $URSRC_radio_button_select_value = $_REQUEST['URSRC_mainradiobutton'];
        $URSRC_radio_button_select_value=str_replace("_"," ",$URSRC_radio_button_select_value);
        $URSRC_customrolename=$_POST['URSRC_tb_customrole'];
        $URSRC_customrolenameupd=$_POST['URSRC_lb_rolename'];
        $URSRC_basicrole=$_POST['basicroles'];
        $URSRC_basicrole=str_replace("_"," ",$URSRC_basicrole);
        $URSRC_menu=$_POST['menu'];
        $URSRC_menuid;
        $URSRC_sub_submenu=$_POST['Sub_menu1'];
        $URSRC_submenu=$_POST['Sub_menu'];
        $URSRC_sub_submenu_array=array();
        $submenu_array=array();
        $menu_array=array();
        $sub_menu_menus=array();
        $length=count($URSRC_submenu);
        $sub_menu1_length=count($URSRC_sub_submenu);
        $URSRC_checkbox_basicrole=$_POST['URSRC_cb_basicroles1'];
        $URSRC_checkbox_basicrole=str_replace("_"," ",$URSRC_checkbox_basicrole);
        $URSRC_rd_basicrole=$_POST['URSRC_radio_basicroles1'];
        $URSRC_rd_basicrole=str_replace("_"," ",$URSRC_rd_basicrole);
        $projectid;
        $id;
        $ids;
        $flag=0;
        for($i=0;$i<$length;$i++){
            if (!(preg_match('/&&/',$URSRC_submenu[$i])))
            {
                $sub_menu_menus[]=$URSRC_submenu[$i];
            }
        }
        if($sub_menu1_length!=0){
            for($j=0;$j<$sub_menu1_length;$j++){
                $sub_menu_menus[]=$URSRC_sub_submenu[$j];
            }
        }
        for($j=0;$j<count($sub_menu_menus);$j++){
            if($j==0){
                $id=$sub_menu_menus[$j];
            }
            else{
                $id=$id .",".$sub_menu_menus[$j];
            }
        }
        if($URSRC_radio_button_select_value=="ROLE CREATION"){
            $result = $con->query("CALL SP_TS_ROLE_CREATION_INSERT('$URSRC_customrolename','$URSRC_basicrole','$id','$USERSTAMP','timesheet',@ROLE_CRTNINSRTFLAG)");
            if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
            $select = $con->query('SELECT @ROLE_CRTNINSRTFLAG');
            $result = $select->fetch_assoc();
            $flag= $result['@ROLE_CRTNINSRTFLAG'];
            echo $flag;
        }
        else if($URSRC_radio_button_select_value=="BASIC ROLE MENU CREATION"||$URSRC_radio_button_select_value=="BASIC ROLE MENU SEARCH UPDATE"){
            $length=count($URSRC_checkbox_basicrole);
            $URSRC_checkbox_basicrole_array;
            for($i=0;$i<$length;$i++){
                if($i==0){
                    $URSRC_checkbox_basicrole_array=$URSRC_checkbox_basicrole[$i];
                }
                else{
                    $URSRC_checkbox_basicrole_array=$URSRC_checkbox_basicrole_array .",".$URSRC_checkbox_basicrole[$i];
                }
            }
            if($URSRC_radio_button_select_value=="BASIC ROLE MENU CREATION"){
                $result = $con->query("CALL  SP_TS_USER_RIGHTS_BASIC_PROFILE_SAVE('$USERSTAMP','$URSRC_rd_basicrole','$URSRC_checkbox_basicrole_array','$id',@BASIC_PROFILESAVEFLAG)");
                if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
                $select = $con->query('SELECT @BASIC_PROFILESAVEFLAG');
                $result = $select->fetch_assoc();
                $flag= $result['@BASIC_PROFILESAVEFLAG'];
                echo $flag;
            }
            else if($URSRC_radio_button_select_value=="BASIC ROLE MENU SEARCH UPDATE"){
                $result = $con->query("CALL  SP_TS_USER_RIGHTS_BASIC_PROFILE_UPDATE('$USERSTAMP','$URSRC_rd_basicrole','$URSRC_checkbox_basicrole_array','$id',@BASIC_PRFUPDATE)");
                if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
                $select = $con->query('SELECT @BASIC_PRFUPDATE');
                $result = $select->fetch_assoc();
                $flag= $result['@BASIC_PRFUPDATE'];
                echo $flag;
            }
        }
        else{
            $result = $con->query("CALL SP_TS_ROLE_CREATION_UPDATE('$URSRC_customrolenameupd','$URSRC_basicrole','$id','$USERSTAMP','timesheet',@ROLE_CREATIONUPDATE)");
            if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
            $select = $con->query('SELECT @ROLE_CREATIONUPDATE');
            $result = $select->fetch_assoc();
            $flag= $result['@ROLE_CREATIONUPDATE'];
            echo $flag;
        }
    }
    //BASIC ROLE MENU CREATION URSRC_check_basicrole
    if($_REQUEST['option']=='URSRC_check_basicrolemenu')
    {
        $role=$_REQUEST['URSRC_basicradio_value'];
        $role=str_replace("_"," ",$role);
        $URSRC_select_check_basicrole_menu=mysqli_query($con,"select * from BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where URC.URC_ID=BMP.URC_ID and URC.URC_DATA='$role'");
        $row=mysqli_num_rows($URSRC_select_check_basicrole_menu);
        $x=$row;
        if($x > 0)
        {
            $URSRC_check_basicrole_menu=0;//TRUE
        }
        else{
            $URSRC_check_basicrole_menu=1;//FALSE
        }
        echo ($URSRC_check_basicrole_menu);
    }
    //FUNCTION to get basic role menus
    if($_REQUEST['option']=="URSRC_loadbasicrole_menu"){
        $URSRC_basicrole_values_array=array();
        $URSRC_basic_roleval=$_REQUEST['URSRC_basicradio_value'];
        $URSRC_basic_roleval=str_replace("_"," ",$URSRC_basic_roleval);
        $URSRC_basicrole_menu_array=array();
        $URSRC_basicroleid_array=array();
        $URSRC_select_basicrole_menu= "select * from USER_RIGHTS_CONFIGURATION URC,BASIC_MENU_PROFILE BMP where URC.URC_ID=BMP.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."'";
        $URSRC_basicrole_menu_rs=mysqli_query($con,$URSRC_select_basicrole_menu);
        while($row=mysqli_fetch_array($URSRC_basicrole_menu_rs)){
            $URSRC_basicrole_menu_array[]=$row["MP_ID"];
        }
        $select_basicrole_id= "select * from USER_RIGHTS_CONFIGURATION URC,BASIC_ROLE_PROFILE BRP where URC.URC_DATA='".$URSRC_basic_roleval."' and URC.URC_ID=BRP.URC_ID";
        $URSRC_basicroleid_rs=mysqli_query($con,$select_basicrole_id);
        while($row=mysqli_fetch_array($URSRC_basicroleid_rs)){
            $URSRC_basicroleid_array[]=$row["BRP_BR_ID"];
        }
        $URSRC_basicrole_array=array();

        for($i=0;$i<count($URSRC_basicroleid_array);$i++){
            $select_basicrole=mysqli_query($con,"select * from USER_RIGHTS_CONFIGURATION URC,BASIC_ROLE_PROFILE BRP where  BRP.BRP_BR_ID=URC.URC_ID and BRP.BRP_BR_ID='".$URSRC_basicroleid_array[$i]."' order by URC_DATA asc ");
            while($row=mysqli_fetch_array($select_basicrole)){
                $URSRC_basicrole_array[]=$row["URC_DATA"];
            }
        }
        //UNIQUE FUNCTION
        $URSRC_basicrole_array=array_values(array_unique($URSRC_basicrole_array));
        $fullarray=URSRC_getmenubasic_folder1();
        $value_array=array($URSRC_basicrole_menu_array,$URSRC_basicrole_array,$fullarray);
//        $URSRC_basicrole_values_array[]=($value_array);
//        $URSRC_getmenu_folder_values=  URSRC_getmenubasic_folder($URSRC_basic_roleval);
//        $URSRC_basicrole_values_array[]=[$URSRC_getmenu_folder_values,$value_array];
        echo JSON_ENCODE($value_array);
    }
    //FUNCTION to get role menus
    if($_REQUEST['option']=="URSRC_tree_view"){
        $menunameradiovalues = $_GET['radio_value'];
        $URSRC_basic_roleval=str_replace("_"," ",$menunameradiovalues);
        $URSRC_getmenu_folder_values=URSRC_getmenu_folder($URSRC_basic_roleval);
        echo JSON_ENCODE($URSRC_getmenu_folder_values);
    }
    //FUNCTION to get basic menus
    if($_REQUEST['option']=="URSRC_tree_view_basic"){
        $menunameradiovalues = $_GET['radio_value'];
        $URSRC_basic_roleval=str_replace("_"," ",$menunameradiovalues);
        $URSRC_getmenu_folder_values=URSRC_getmenubasic_folder1();
        echo JSON_ENCODE($URSRC_getmenu_folder_values);
    }
    //FUNCTION TO LOAD INITIAL VALUES ROLE LST bX
    if($_REQUEST['option']=="ACCESS_RIGHTS_SEARCH_UPDATE_BASICROLE"){
        $URSRC_role_array=get_roles();
//        print_r($URSRC_role_array);
//        exit;
        echo JSON_ENCODE($URSRC_role_array);
    }
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

function URSRC_unshare_document($loggin,$fileId){

    global $con,$ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token;
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


    try {
        $permissions = $service->permissions->listPermissions($fileId);
        $return_value= $permissions->getItems();
    } catch (Exception $e) {
//        print "An error occurred: " . $e->getMessage();
        $ss_flag=0;
    }
    foreach ($return_value as $key => $value) {
        if ($value->emailAddress==$loggin) {
            $permission_id=$value->id;
        }
    }
    if($permission_id!=''){
        try {
            $service->permissions->delete($fileId, $permission_id);
//        $ss_flag=1;
        } catch (Exception $e) {
//        print "An error occurred: " . $e->getMessage();
//        $ss_flag=0;
        }
    }

}
function URSRC_calendar_create($loginid_name,$URSC_uld_id,$finaldate,$calenderid,$status){

    global $con,$ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token;
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
    $cal = new Google_Service_Calendar($drive);
    $event = new Google_Service_Calendar_Event();
    $event->setsummary($loginid_name.'  '.$status);
    if($status=='JOIN DATE'){
        $event->setDescription($URSC_uld_id);
    }
    $start = new Google_Service_Calendar_EventDateTime();
    $start->setDate($finaldate);//setDate('2014-11-18');
    $event->setStart($start);
    $event->setEnd($start);
    try{
        $createdEvent = $cal->events->insert($calenderid, $event);
        $cal_flag=1;
    }
    catch(Exception $e){

        $cal_flag=0;
    }

    return $cal_flag;


}
function URSRC_delete_create_calendarevent($ULD_id,$loginid_name,$finaldate){
    global $con,$ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token;
    $select_calenderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=10");
    if($row=mysqli_fetch_array($select_calenderid)){
        $calenderid=$row["URC_DATA"];
    }
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

    $cal = new Google_Service_Calendar($drive);
    $service = new Google_Service_Calendar($drive);
    try{
        $events = $service->events->listEvents($calenderid);
    }
    catch(Exception $e){

        $cal_flag=0;
        return $cal_flag;
    }
    while(true) {
        foreach ($events->getItems() as $newevent) {
            $desc=$newevent->getDescription();
            if($desc==$ULD_id){
                $event_id=$newevent->getId();
                $service->events->delete($calenderid,$event_id);
            }

        }
        $pageToken = $events->getNextPageToken();
        if ($pageToken) {
            $optParams = array('pageToken' => $pageToken);
            $events = $service->events->listEvents($calenderid, $optParams);
        } else {
            break;
        }
    }
    $cal_flag=URSRC_calendar_create($loginid_name,$ULD_id,$finaldate,$calenderid,'JOIN DATE');
    return $cal_flag;


}
//COMMON TREE SEARCH ND UPDATE FUNCTION
function URSRC_getmenu_folder($URSRC_basic_roleval){
    global $con;
    $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."' ORDER BY MP_MNAME ASC ");
    $ure_values=array();
    $URSC_Main_menu_array=array();
    $i=0;
    while($row=mysqli_fetch_array($main_menu_data)){
        $URSC_Main_menu_array[]=$row["MP_MNAME"];
        $sub_menu_data= mysqli_query($con,"SELECT  MP_MSUB, MP.MP_ID FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND MP.MP_MSUB IS NOT NULL GROUP BY MP_MSUB ORDER BY MP.MP_MSUB ASC ");
        $URSC_sub_menu_row=array();
        $URSC_sub_sub_menu_row_col=array();
        $URSC_sub_sub_menu_row_col_data=array();
        $j=0;
        while($row=mysqli_fetch_array($sub_menu_data))  {
            $URSC_sub_menu_row[]=array($row["MP_ID"],$row["MP_MSUB"]);
            $sub_sub_menu_data= mysqli_query($con,"SELECT MP.MP_ID, MP_MSUBMENU FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval ."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND  MP.MP_MSUB='".$URSC_sub_menu_row[$j][1]."' AND MP.MP_MSUBMENU IS NOT NULL  ORDER BY MP_MSUBMENU ASC" );
            $URSC_sub_sub_menu_row=array();
            $URSC_sub_sub_menu_row_data=array();
            while($row=mysqli_fetch_array($sub_sub_menu_data)){
                $URSC_sub_sub_menu_row_data[]=array($row["MP_ID"],$row["MP_MSUBMENU"]);
            }
            $URSC_sub_sub_menu_row_col[]=$URSC_sub_sub_menu_row;
            $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
            $j++;
        }
        $URSC_sub_sub_menu_array[]=$URSC_sub_sub_menu_row_col;
        $URSC_sub_menu_array[]=$URSC_sub_menu_row;
        $i++;
    }
    $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array);
    return $final_values;
}

//COMMON TREE SEARCH ND UPDATE FUNCTION
function URSRC_getmenubasic_folder($URSRC_basic_roleval){
    global $con;
    $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."' ORDER BY MP_MNAME ASC ");
    $ure_values=array();
    $URSC_Main_menu_array=array();
    $i=0;
    while($row=mysqli_fetch_array($main_menu_data)){
        $URSC_Main_menu_array[]=$row["MP_MNAME"];
        $sub_menu_data= mysqli_query($con,"SELECT  MP_MSUB, MP.MP_ID FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."'  and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' and URC.URC_DATA='".$URSRC_basic_roleval."' AND MP.MP_MSUB IS NOT NULL GROUP BY MP_MSUB ORDER BY MP.MP_MSUB ASC ");
        $URSC_sub_menu_row=array();
        $URSC_sub_sub_menu_row_col=array();
        $URSC_sub_sub_menu_row_col_data=array();
        $j=0;
        while($row=mysqli_fetch_array($sub_menu_data))  {
            $URSC_sub_menu_row[]=array($row["MP_ID"],$row["MP_MSUB"]);
            $sub_sub_menu_data= mysqli_query($con,"SELECT MP.MP_ID, MP_MSUBMENU FROM MENU_PROFILE MP,BASIC_MENU_PROFILE BMP,USER_RIGHTS_CONFIGURATION URC where BMP.MP_ID=MP.MP_ID and BMP.URC_ID=URC.URC_ID and URC.URC_DATA='".$URSRC_basic_roleval."' and MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND  MP.MP_MSUB='".$URSC_sub_menu_row[$j][1]."' AND MP.MP_MSUBMENU IS NOT NULL  ORDER BY MP_MSUBMENU ASC" );
            $URSC_sub_sub_menu_row=array();
            $URSC_sub_sub_menu_row_data=array();
            while($row=mysqli_fetch_array($sub_sub_menu_data)){
                $URSC_sub_sub_menu_row_data[]=array($row["MP_ID"],$row["MP_MSUBMENU"]);
            }
            $URSC_sub_sub_menu_row_col[]=$URSC_sub_sub_menu_row;
            $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
            $j++;
        }
        $URSC_sub_sub_menu_array[]=$URSC_sub_sub_menu_row_col;
        $URSC_sub_menu_array[]=$URSC_sub_menu_row;
        $i++;
    }
    $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array);
    return $final_values;
}
function URSRC_getmenubasic_folder1(){
    global $con;
    $main_menu_data= mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM MENU_PROFILE MP ORDER BY MP_MNAME ASC ");
    $ure_values=array();
    $URSC_Main_menu_array=array();
    $i=0;
    while($row=mysqli_fetch_array($main_menu_data)){
        $URSC_Main_menu_array[]=$row["MP_MNAME"];
        $sub_menu_data= mysqli_query($con,"SELECT  MP_MSUB, MP.MP_ID FROM MENU_PROFILE MP WHERE MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND MP.MP_MSUB IS NOT NULL GROUP BY MP_MSUB ORDER BY MP.MP_MSUB ASC ");
        $URSC_sub_menu_row=array();
        $URSC_sub_sub_menu_row_col=array();
        $URSC_sub_sub_menu_row_col_data=array();
        $j=0;
        while($row=mysqli_fetch_array($sub_menu_data))  {
            $URSC_sub_menu_row[]=array($row["MP_ID"],$row["MP_MSUB"]);
            $sub_sub_menu_data= mysqli_query($con,"SELECT MP.MP_ID, MP_MSUBMENU FROM MENU_PROFILE MP WHERE MP.MP_MNAME='".$URSC_Main_menu_array[$i]."' AND  MP.MP_MSUB='".$URSC_sub_menu_row[$j][1]."' AND MP.MP_MSUBMENU IS NOT NULL  ORDER BY MP_MSUBMENU ASC" );
            $URSC_sub_sub_menu_row=array();
            $URSC_sub_sub_menu_row_data=array();
            while($row=mysqli_fetch_array($sub_sub_menu_data)){
                $URSC_sub_sub_menu_row_data[]=array($row["MP_ID"],$row["MP_MSUBMENU"]);
            }
            $URSC_sub_sub_menu_row_col[]=$URSC_sub_sub_menu_row;
            $URSC_sub_sub_menu_data_array[]=$URSC_sub_sub_menu_row_data;
            $j++;
        }
        $URSC_sub_sub_menu_array[]=$URSC_sub_sub_menu_row_col;
        $URSC_sub_menu_array[]=$URSC_sub_menu_row;
        $i++;
    }
    $final_values=array($URSC_Main_menu_array, $URSC_sub_menu_array,$URSC_sub_sub_menu_data_array);
    return $final_values;
}
//File Upload Function Script
function insertFile($service, $title, $description, $parentId,$mimeType,$uploadfilename)
{

    $file = new Google_Service_Drive_DriveFile();
    $file->setTitle($title);
    $file->setDescription($description);
    $file->setMimeType($mimeType);
    if ($parentId != null) {
        $parent = new Google_Service_Drive_ParentReference();
        $parent->setId($parentId);
        $file->setParents(array($parent));
    }
    try
    {
        $data =file_get_contents($uploadfilename);
        $createdFile = $service->files->insert($file, array(
            'data' => $data,
            'mimeType' => $mimeType,
            'uploadType' => 'media',
        ));

        $fileid = $createdFile->getId();


    }
    catch (Exception $e)
    {
        $file_flag=0;

    }
    return $fileid;

}
function delete_file($service,$fileid){

    try {
        $f=$service->files->delete($fileid);
    } catch (Exception $e) {
        $f= "An error occurred: " . $e->getMessage();
    }
    return $f;

}
?>