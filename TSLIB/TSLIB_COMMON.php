
<?php
error_reporting(1);
include "../TSLIB/TSLIB_CONNECTION.php";
//include "../TSLIB/TSLIB_COMMON.php";
include "../TSLIB/TSLIB_GET_USERSTAMP.php";
$USERSTAMP=$UserStamp;
date_default_timezone_set('Asia/Kolkata');
global $con;
global $emp_uldid;
function getTimezone()
{
    return ("'+00:00','+05:30'");
}
//GET SINGLE EMP_NAME
function get_empname(){
    global $USERSTAMP;
    global $con;
    $uld_id=mysqli_query($con,"select EMPLOYEE_NAME from VW_TS_ALL_EMPLOYEE_DETAILS where ULD_LOGINID='$USERSTAMP' AND ULD_ID=(select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP')");
    while($row=mysqli_fetch_array($uld_id)){
        $ure_empname=$row["EMPLOYEE_NAME"];
    }
    return $ure_empname;
}
// service function
function get_servicedata(){
    global $USERSTAMP;
    global $con;
    $uld_id=mysqli_query($con,"select URC_DATA from USER_RIGHTS_CONFIGURATION where URC_ID in (28,29,30,31,32,33)");
    while($row=mysqli_fetch_array($uld_id)){
        $Client[]=$row["URC_DATA"];
    }
    return $Client;
}

//GET ULD_ID
function get_uldid(){
    global $USERSTAMP;
    global $con;
//    echo 'asdawqes';
    $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='".$USERSTAMP."'");
    while($row=mysqli_fetch_array($uld_id)){
        $ure_uld_id=$row["ULD_ID"];
    }
    return $ure_uld_id;
}
//GET PERMISSION
function get_permission(){
    global $con;
    $permission_result = mysqli_query($con,"SELECT AC_DATA FROM ATTENDANCE_CONFIGURATION WHERE CGN_ID=6");
    $get_permission_array=array();
    while($row=mysqli_fetch_array($permission_result)){
        $get_permission_array[]= $row["AC_DATA"];
    }
    return $get_permission_array;
}
//FOR GETTING PROJECT ID AND NAME FOR ENTRYFORM
function get_projectentry($ure_uld_id){
    global $con;
    $project_result=mysqli_query($con,"select * from EMPLOYEE_PROJECT_DETAILS EPD,PROJECT_DETAILS PD,PROJECT_STATUS PS where EPD.PS_ID=PS.PS_ID AND PS.PD_ID=PD.PD_ID AND PS.PC_ID!=3 AND EPD.ULD_ID='$ure_uld_id' and EPD.EPD_FLAG IS NULL order by PD.PD_PROJECT_NAME asc");
    $get_project_array=array();
    while($row=mysqli_fetch_array($project_result)){
        $get_project_array[]=array($row["PD_PROJECT_NAME"],$row["PS_ID"],$row["PS_REC_VER"]);
    }
    return $get_project_array;
}
function get_emp_projectentry(){
    global $con;
    $project_result=mysqli_query($con,"SELECT DISTINCT B.PS_ID,A.PD_ID,A.PD_PROJECT_NAME,B.PS_REC_VER FROM PROJECT_DETAILS A,PROJECT_STATUS B WHERE B.PC_ID!=3 AND A.PD_ID = B.PD_ID ORDER BY A.PD_PROJECT_NAME");
    $get_project_array=array();
    while($row=mysqli_fetch_array($project_result)){
        $get_project_array[]=array($row["PD_PROJECT_NAME"],$row["PS_ID"],$row["PS_REC_VER"]);
    }
    return $get_project_array;
}
//GET PROJECTS LIST FROM DB
function get_project($ure_uld_id){
    global $con;
    $project_result=mysqli_query($con,"select * from EMPLOYEE_PROJECT_DETAILS EPD,PROJECT_DETAILS PD,PROJECT_STATUS PS where EPD.PS_ID=PS.PS_ID AND PS.PD_ID=PD.PD_ID  AND EPD.ULD_ID='$ure_uld_id' order by PD.PD_PROJECT_NAME asc");
    $get_project_array=array();
    while($row=mysqli_fetch_array($project_result)){
        $get_project_array[]=array($row["PD_PROJECT_NAME"],$row["PS_ID"],$row["PS_REC_VER"],$row["PC_ID"]);
    }
    return $get_project_array;
}
//GET JOIN DATE FOR SELECTING LOGIN ID;
function get_joindate($ure_uld_id){
    global $con;
    $min_date=mysqli_query($con,"SELECT UA_JOIN_DATE FROM USER_ACCESS where ULD_ID='$ure_uld_id' AND UA_TERMINATE IS NULL");
    while($row=mysqli_fetch_array($min_date)){
        $mindate_array=$row["UA_JOIN_DATE"];
        $min_date = date('d-m-Y',strtotime($mindate_array));
    }
    return  $min_date;
}
if($_REQUEST["option"]=="user_report_entry"){
    $get_permission_array=get_permission();
    $uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='".$USERSTAMP."'");
    while($row=mysqli_fetch_array($uld_id)){
        $ure_uld_id=$row["ULD_ID"];
    }
//    $ure_uld_id=get_uldid();
    $ure_empname=get_empname();
    $get_project_array=get_projectentry($ure_uld_id);
    $min_date=get_joindate($ure_uld_id);
    $error='3,4,5,6,7,8,16,17,18,67,115,120,142';
    $error_array=get_error_msg($error);

//    $user_uld_id=mysqli_query($con,"select ULD_ID from USER_LOGIN_DETAILS where ULD_LOGINID='$USERSTAMP'");
//    while($row=mysqli_fetch_array($user_uld_id)){
//        $uld_id=$row["ULD_ID"];
//    }
    $select_wfh=mysqli_query($con,"select WFHA_FLAG from WORK_FROM_HOME_ACCESS where ULD_ID=$ure_uld_id");
    while($row=mysqli_fetch_array($select_wfh))
    {
        $wfh_flag=$row['WFHA_FLAG'];
    }
    $values_array=array($get_permission_array,$get_project_array,$min_date,$error_array,$ure_empname,$wfh_flag);
//    echo $values_array;
    echo JSON_ENCODE($values_array);

}
if($_REQUEST["option"]=="user_search_update"){

    $get_permission_array=get_permission();
    $ure_uld_id=get_uldid();
    $ure_empname=get_empname();
    $get_project_array=get_project($ure_uld_id);
    $error='3,4,5,6,7,8,16,17,18,67,83,98';
    $error_array=get_error_msg($error);
    $min_date=get_joindate($ure_uld_id);
    $user_searchmin_date=mysqli_query($con,"SELECT MIN(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS where ULD_ID='$ure_uld_id' ");
    while($row=mysqli_fetch_array($user_searchmin_date)){
        $user_searchmin_date_value=$row["UARD_DATE"];
        $user_searchmin_date_value = date('d-m-Y',strtotime($user_searchmin_date_value));
    }
    $user_searchmax_date=mysqli_query($con,"SELECT MAX(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS where ULD_ID='$ure_uld_id' ");
    while($row=mysqli_fetch_array($user_searchmax_date)){
        $user_searchmax_date_value=$row["UARD_DATE"];
        $user_searchmax_date_value = date('d-m-Y',strtotime($user_searchmax_date_value));
    }
    $select_wfh=mysqli_query($con,"select WFHA_FLAG from WORK_FROM_HOME_ACCESS where ULD_ID=$ure_uld_id");
    while($row=mysqli_fetch_array($select_wfh))
    {
        $wfh_flag=$row['WFHA_FLAG'];
    }
    $values_array=array($get_permission_array,$get_project_array,$user_searchmin_date_value,$user_searchmax_date_value,$error_array,$min_date,$ure_empname,$wfh_flag);
    echo JSON_ENCODE($values_array);
}
//GET ACTIVE LOGIN ID;
function get_active_login_id(){
    global $con;
//    $loginid=mysqli_query($con,"SELECT ULD_LOGINID from VW_ACCESS_RIGHTS_TERMINATE_LOGINID where URC_DATA!='SUPER ADMIN' ORDER BY ULD_LOGINID");
    $loginid=mysqli_query($con,"SELECT ULD_LOGINID from VW_TS_ALL_ACTIVE_LOGIN_ID where URC_DATA!='SUPER ADMIN' ORDER BY ULD_LOGINID");
    $login_array=array();
    while($row=mysqli_fetch_array($loginid)){
        $login_array[]=$row["ULD_LOGINID"];
    }
    return $login_array;
}
//GET NON ACTIVE LOGIN ID
function get_nonactive_login_id(){
    global $con;
    $activenonemp=mysqli_query($con,"SELECT * from VW_ACCESS_RIGHTS_REJOIN_LOGINID ORDER BY ULD_LOGINID");
    $active_nonemp=array();
    while($row=mysqli_fetch_array($activenonemp)){
        $active_nonemp[]=$row["ULD_LOGINID"];
    }
    return $active_nonemp;
}
//GET ACTIVE EMPLOYEE ID;
function get_active_emp_id(){
    global $con;
    $loginid=mysqli_query($con,"SELECT * from VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS where URC_DATA!='SUPER ADMIN' ORDER BY EMPLOYEE_NAME");
    $active_array=array();
    while($row=mysqli_fetch_array($loginid)){
        $active_array[]=array($row["EMPLOYEE_NAME"],$row["ULD_ID"],$row['ULD_LOGINID']);
    }
    return $active_array;
}
//GET NON ACTIVE EMPLOYEE ID
function get_nonactive_emp_id(){
    global $con;
    $activenonemp=mysqli_query($con,"SELECT * from VW_TS_ALL_NON_ACTIVE_EMPLOYEE_DETAILS ORDER BY EMPLOYEE_NAME");
    $active_nonemp=array();
    while($row=mysqli_fetch_array($activenonemp)){
        $active_nonemp[]=array($row["EMPLOYEE_NAME"],$row["ULD_ID"]);
    }
    return $active_nonemp;
}
function get_company_start_date(){

    global $con;
    $comp_sdate=mysqli_query($con,"SELECT * from USER_RIGHTS_CONFIGURATION WHERE URC_ID=11");
    while($row=mysqli_fetch_array($comp_sdate)){
        $comp_startdate=$row["URC_DATA"];
    }
    $comp_startdate = date('d-m-Y',strtotime($comp_startdate));
    return $comp_startdate;
}
if($_REQUEST["option"]=="admin_report_entry")
{
    $get_permission_array=get_permission();
    $ure_uld_id=get_uldid();
    $get_project_array=get_projectentry($ure_uld_id);
    $error='3,4,5,6,7,8,16,17,18,67,115,120,142';
    $error_array=get_error_msg($error);
    $min_date=get_joindate($ure_uld_id);
    $login_array=get_active_emp_id();
//    $select_wfh=mysqli_query($con,"select WFHA_FLAG from WORK_FROM_HOME_ACCESS where ULD_ID=$ure_uld_id");
//    while($row=mysqli_fetch_array($select_wfh))
//    {
//        $wfh_flag=$row['WFHA_FLAG'];
//    }
    $values_array=array($get_permission_array,$get_project_array,$min_date,$error_array,$login_array,$wfh_flag);
    echo JSON_ENCODE($values_array);
}
if($_REQUEST["option"]=="admin_search_update")
{
    $get_permission_array=get_permission();
    $ure_uld_id=get_uldid();
//    $get_project_array=get_project();
    $error='3,4,5,6,7,8,16,17,18,67,83,98,109,110';
    $error_array=get_error_msg($error);

//    $min_date=get_joindate($ure_uld_id);
    $admin_searchmin_date=mysqli_query($con,"SELECT MIN(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS  ");
    while($row=mysqli_fetch_array($admin_searchmin_date)){
        $admin_searchmin_date_value=$row["UARD_DATE"];
        $min_date = date('d-m-Y',strtotime($admin_searchmin_date_value));
    }
    $admin_searchmax_date=mysqli_query($con,"SELECT MAX(UARD_DATE) as UARD_DATE FROM USER_ADMIN_REPORT_DETAILS  ");
    while($row=mysqli_fetch_array($admin_searchmax_date)){
        $admin_searchmax_date_value=$row["UARD_DATE"];
        $max_date= date('d-m-Y',strtotime($admin_searchmax_date_value));
    }

    $login_array=get_active_emp_id();
    $active_emp=get_active_emp_id();
    $activenonemp=mysqli_query($con,"SELECT * from VW_ACCESS_RIGHTS_REJOIN_LOGINID ORDER BY ULD_LOGINID");

    $active_nonemp=get_nonactive_emp_id();
    $onduty_searchmin_date=mysqli_query($con,"SELECT MIN(OED_DATE) as OED_DATE FROM ONDUTY_ENTRY_DETAILS");
    while($row=mysqli_fetch_array($onduty_searchmin_date)){
        $onduty_searchmin_date_value=$row["OED_DATE"];
        $od_mindate = date('d-m-Y',strtotime($onduty_searchmin_date_value));
    }
    $onduty_searchmax_date=mysqli_query($con,"SELECT MAX(OED_DATE) as OED_DATE FROM ONDUTY_ENTRY_DETAILS");
    while($row=mysqli_fetch_array($onduty_searchmax_date)){
        $onduty_searchmax_date_value=$row["OED_DATE"];
        $od_maxdate= date('d-m-Y',strtotime($onduty_searchmax_date_value));
    }
//    $select_wfh=mysqli_query($con,"select WFHA_FLAG from WORK_FROM_HOME_ACCESS where ULD_ID=$ure_uld_id");
//    while($row=mysqli_fetch_array($select_wfh))
//    {
//        $wfh_flag=$row['WFHA_FLAG'];
//    }

    $values_array=array($get_permission_array,$get_project_array,$min_date,$error_array,$login_array,$active_emp,$active_nonemp,$max_date,$od_mindate,$od_maxdate,$wfh_flag);
    echo JSON_ENCODE($values_array);



}
//GET ERROR MSG
function get_error_msg($str){
    global $con;
    $errormessage=array();
    $errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN ($str)");
    while($row=mysqli_fetch_array($errormsg)){
        $errormessage[]=$row["EMC_DATA"];
    }
    return $errormessage;
}

//TERMINATE FOR REJOIN OF APPENDING UPDATE FORM DATAS
if($_REQUEST["option"]=="USER_RIGHTS_TERMINATE"){
    $str='9,10,11,12,13,14,56,70,113,114,116,132,133,136,137,138,146';
    $errormsg_array= get_error_msg($str);
    $role_result=mysqli_query($con,"SELECT RC_NAME,RC_ID FROM ROLE_CREATION");
    $get_role_array=array();
    while($row=mysqli_fetch_array($role_result)){
        $get_role_array[]=array($row["RC_ID"],$row["RC_NAME"]);
    }
    $emp_type=mysqli_query($con,"SELECT URC_DATA FROM USER_RIGHTS_CONFIGURATION WHERE CGN_ID =10");
    $get_emptype_array=array();
    while($row=mysqli_fetch_array($emp_type)){
        $get_emptype_array[]=$row["URC_DATA"];
    }
    //desgination
    $rdesgn_result=mysqli_query($con,"SELECT * FROM EMPLOYEE_DESIGNATION ORDER BY ED_DESIGNATION");
    $get_rdesgn_array=array();
    while($row=mysqli_fetch_array($rdesgn_result)){
        $get_rdesgn_array[]=$row["ED_DESIGNATION"];
    }
    //RELATIONHOOD
    $rname_result=mysqli_query($con,"SELECT URC_DATA FROM USER_RIGHTS_CONFIGURATION WHERE CGN_ID=22 ORDER BY URC_DATA");
    $get_rname_array=array();
    while($row=mysqli_fetch_array($rname_result)){
        $get_rname_array[]=$row["URC_DATA"];
    }
    $acctype_result=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE CGN_ID=21 ORDER BY URC_DATA");
    $get_acctype_array=array();
    while($row=mysqli_fetch_array($acctype_result)){
        $get_acctype_array[]=$row["URC_DATA"];
    }
    $laptype_result=mysqli_query($con,"SELECT CP_LAPTOP_NUMBER FROM COMPANY_PROPERTIES WHERE CP_FLAG IS NULL ORDER BY CP_LAPTOP_NUMBER");
    $get_laptype_array=array();
    while($row=mysqli_fetch_array($laptype_result)){
        $get_laptype_array[]=$row["CP_LAPTOP_NUMBER"];
    }
    $value_array=array($errormsg_array,$get_role_array,$get_emptype_array,$get_rdesgn_array, $get_rname_array,$get_acctype_array,$get_laptype_array);
    echo JSON_ENCODE($value_array);
}
function get_roles(){
    global $con;
    $rolecreation_result = mysqli_query($con,"SELECT * FROM ROLE_CREATION");
    $get_rolecreation_array=array();
    while($row=mysqli_fetch_array($rolecreation_result)){
        $get_rolecreation_array[]= $row["RC_NAME"];
    }

    return  $get_rolecreation_array;
}
function renamefile($service,$logincre_foldername,$emp_folderid)
{
    $file = $service->files->get($emp_folderid);
    if($logincre_foldername!=$file->getTitle())
    {
        try {
            $file = new Google_Service_Drive_DriveFile();
            $file->setTitle($logincre_foldername);

            $updatedFile = $service->files->patch($emp_folderid, $file, array(
                'fields' => 'title'
            ));

            $emp_folderid=$updatedFile ->id;
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
            exit;
        }
    }
    return $emp_folderid;
}
function getEmpfolderName($loginidval){
    global $con,$emp_uldid;
    $loginid=$loginidval;
//    echo "SELECT ULD_ID,EMPLOYEE_NAME from VW_TS_ALL_EMPLOYEE_DETAILS where ULD_LOGINID='$loginid'";exit;
    $login_idqry=mysqli_query($con,"SELECT ULD_ID,EMPLOYEE_NAME from VW_TS_ALL_EMPLOYEE_DETAILS where ULD_LOGINID='$loginid'");
    $login_empid="";
    while($row=mysqli_fetch_array($login_idqry)){
        if(preg_match("/$row[0]/",$row[1]))
        {
            $login_empid=$row["EMPLOYEE_NAME"];
        }
        else
        {
            $login_empid=$row["EMPLOYEE_NAME"]." ".$row["ULD_ID"];
        }
        $emp_uldid=$row["ULD_ID"];
    }
    return $login_empid;
}
//function to remove permission for the file
function removeFilepermission($service,$fileId)
{
    try {
        $permissions = $service->permissions->listPermissions($fileId);
        $return_value= $permissions->getItems();
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
    foreach ($return_value as $key => $value) {
        $permission_id=$value->id;
        if($permission_id!=''){
            try {
                $service->permissions->delete($fileId, $permission_id);
            } catch (Exception $e) {
                echo "An error occurred: " . $e->getMessage();
            }
        }
    }

}
//FUNCTION TO RESTORE FILE
function restoreFile($service, $fileId) {
    try {
        return $service->files->untrash($fileId);
    } catch (Exception $e) {
        print "An error occurred: " . $e->getMessage();
    }
    return NULL;
}
//FUNCTION TO DELETE DRIVE FILE REMOVED VIA LOGIN UPDATE FORM
function trashFile($folderid,$user_filelist) {
    global $ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token;
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
    $emp_uploadfilenamelist=array();
    $emp_uploadfileidlist=array();
    $children1 = $service->children->listChildren($folderid);
    $child_filearray=$children1->getItems();
    foreach ($child_filearray as $child1) {
        if($service->files->get($child1->getId())->getExplicitlyTrashed()==1)continue;
        $emp_uploadfileidlist[]=$service->files->get($child1->getId())->id;
        $emp_uploadfilenamelist[]=$service->files->get($child1->getId())->title;

    }
    sort($emp_uploadfileidlist);
    if(count($user_filelist)>0)
    {
        sort($user_filelist);
        $final_empfilelist=array_diff($emp_uploadfileidlist,$user_filelist);
    }
    else{
        $final_empfilelist=$emp_uploadfileidlist;
    }

    $final_empfilelist1=array();
    foreach ($final_empfilelist as $item) {
        $final_empfilelist1[] = $item;
    }

    for($a=0;$a<count($final_empfilelist1);$a++)
    {
        try {
            $service->files->trash($final_empfilelist1[$a]);
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
        try {
            $file = $service->files->get($final_empfilelist1[$a]);
            $permissions = $service->permissions->listPermissions($final_empfilelist1[$a]);
            $return_value= $permissions->getItems();
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
        foreach ($return_value as $key => $value) {
            if ($value->emailAddress!=$file->owners[0]["emailAddress"]){
                $permission_id=$value->id;
                if($permission_id!=''){
                    try {
                        $service->permissions->delete($final_empfilelist1[$a], $permission_id);
                    } catch (Exception $e) {
                        echo "An error occurred: " . $e->getMessage();
                    }
                }
            }
        }
    }

    return $final_empfilelist1;
}
//FUNCTION TO GET EMPLOYEE DRIVE FOLDER ID N UPLOADED FILE LIST IN DRIVE
function UploadEmployeeFiles($formname,$loginid_result)
{
    global $con,$ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token,$emp_uldid;
    $loginid=$loginid_result;
    $emp_uploadfilelist=array();
    $login_empid=getEmpfolderName($loginid);
//    echo $login_empid;exit;
    $select_folderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=13");
    if($row=mysqli_fetch_array($select_folderid)){
        $folderid=$row["URC_DATA"];
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
    $refresh_token=$Refresh_Token;
    $drive->refreshToken($refresh_token);
    $service = new Google_Service_Drive($drive);
    $logincre_foldername=$login_empid;
    $emp_folderid="";
    $emp_uploadfilenamelist=array();
    $emp_uploadfileidlist=array();
    $children = $service->children->listChildren($folderid);
    $root_filearray=$children->getItems();
    foreach ($root_filearray as $child) {
        if($service->files->get($child->getId())->getExplicitlyTrashed()==1)continue;
        $rootfold_title=$service->files->get($child->getId())->title;
        $split_folderid=explode(" ",$rootfold_title);
        if(strcasecmp($emp_uldid, $split_folderid[count($split_folderid)-1]) != 0)continue;
        $emp_folderid=$service->files->get($child->getId())->id;
        if($rootfold_title!=$login_empid)
        {
//        //rename folder for loging updation start
            renamefile($service,$logincre_foldername,$emp_folderid);
        }
        //end
        $emp_uploadfilenamelist=array();
        $children1 = $service->children->listChildren($child->getId());
        $child_filearray=$children1->getItems();
        sort($child_filearray);
        foreach ($child_filearray as $child1) {
            if($service->files->get($child1->getId())->getExplicitlyTrashed()==1)continue;
            $emp_uploadfilenamelist[]=$service->files->get($child1->getId())->title;
        }
        break;
    }
    sort($emp_uploadfilenamelist);
    $emp_uploadfileidlist=array();
    $emp_uploadfilelinklist=array();
    for($f=0;$f<count($emp_uploadfilenamelist);$f++)
    {
        $children1 = $service->children->listChildren($emp_folderid);
        $filearray1=$children1->getItems();
        foreach ($filearray1 as $child1) {
            if($service->files->get($child1->getId())->getExplicitlyTrashed()==1)continue;
            if($service->files->get($child1->getId())->title==$emp_uploadfilenamelist[$f])
            {
                $emp_uploadfileidlist[]=$service->files->get($child1->getId())->id;
                $emp_uploadfilelinklist[]=$service->files->get($child1->getId())->alternateLink;
            }
        }
    }

    if($emp_folderid==""&&($formname=="login_creation")){
        $newFolder = new Google_Service_Drive_DriveFile();
        $newFolder->setMimeType('application/vnd.google-apps.folder');
        $newFolder->setTitle($logincre_foldername);
        if ($folderid != null) {
            $parent = new Google_Service_Drive_ParentReference();
            $parent->setId($folderid);
            $newFolder->setParents(array($parent));
        }
        try {
            $folderData = $service->files->insert($newFolder);
        } catch (Google_Service_Exception $e) {
            echo 'Error while creating <br>Message: '.$e->getMessage();
            die();
        }
        $emp_folderid=$folderData->id;
    }
    if($formname=="login_fetch")
    {
        if($emp_folderid==""){echo "Error:Folder id Not present";exit;}
        $emp_uploadfiles=array($emp_uploadfileidlist,$emp_uploadfilenamelist,$emp_uploadfilelinklist);
        return $emp_uploadfiles;
    }
    return $emp_folderid;
}
if($_REQUEST["option"]=="ACCESS_RIGHTS_SEARCH_UPDATE")
{
    $str='40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,1,2,69,70,71,72,95,113,114,132,133,136,137,138,146';
    $URSRC_errmsg=get_error_msg($str);
    $get_rolecreation_array=get_roles();
    $project_result=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION where URC_ID in (1,2,3) ");
    $get_project_array=array();
    while($row=mysqli_fetch_array($project_result)){
        $get_project_array[]=$row["URC_DATA"];
    }
    $emp_type=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION where CGN_ID =10 ");
    $get_emptype_array=array();
    while($row=mysqli_fetch_array($emp_type)){
        $get_emptype_array[]=$row["URC_DATA"];
    }

    $menuname_result=mysqli_query($con,"SELECT DISTINCT MP_MNAME FROM MENU_PROFILE MP,USER_RIGHTS_CONFIGURATION URC");
    $get_menuname_array=array();
    while($row=mysqli_fetch_array($menuname_result)){
        $get_menuname_array[]=$row["MP_MNAME"];

    }
    //BASI ROLE

    $query= "select * from  USER_RIGHTS_CONFIGURATION URC,ROLE_CREATION RC,USER_LOGIN_DETAILS ULD,USER_ACCESS UA where ULD.ULD_ID=UA.ULD_ID and RC.RC_ID=UA.RC_ID and RC.URC_ID=URC.URC_ID and ULD.ULD_LOGINID='".$USERSTAMP."' ORDER BY URC_DATA ASC";
    $URSRC_select_basicrole_result=mysqli_query($con,$query);
    while($row=mysqli_fetch_array($URSRC_select_basicrole_result)){
        $URSRC_basicrole=$row["URC_DATA"];

    }
//    $URSRC_basicroleid_array_result=mysqli_query($con,"select * from USER_RIGHTS_CONFIGURATION URC,BASIC_ROLE_PROFILE BRP where URC.URC_DATA='".$URSRC_basicrole."' and URC.URC_ID=BRP.URC_ID");
    $URSRC_basicroleid_array_result=mysqli_query($con,"select * from USER_RIGHTS_CONFIGURATION URC,BASIC_ROLE_PROFILE BRP where URC.URC_DATA='".$URSRC_basicrole."' and URC.URC_ID=BRP.URC_ID");
    $URSRC_basicroleid_array=array();
    while($row=mysqli_fetch_array($URSRC_basicroleid_array_result)){
        $URSRC_basicroleid_array[]=($row["BRP_BR_ID"]);
    }
    $get_URSRC_basicrole_profile_array=array();

    for($i=0;$i<count($URSRC_basicroleid_array);$i++){

        $URSRC_basicrole_profile_array_result=mysqli_query($con,"select * from USER_RIGHTS_CONFIGURATION URC,BASIC_ROLE_PROFILE BRP where  BRP.BRP_BR_ID=URC.URC_ID and BRP.BRP_BR_ID='".$URSRC_basicroleid_array[$i]."' order by URC_DATA asc ");
        while($row=mysqli_fetch_array($URSRC_basicrole_profile_array_result)){
            $get_URSRC_basicrole_profile_array[]=$row["URC_DATA"];
        }
    }
    $get_URSRC_basicrole_profile_array=array_values(array_unique($get_URSRC_basicrole_profile_array));
    $comp_startdate=get_company_start_date();
    $value_array=array($get_rolecreation_array,$get_project_array,$get_menuname_array,$get_URSRC_basicrole_profile_array,$URSRC_errmsg,$get_emptype_array,$comp_startdate);
    echo JSON_ENCODE($value_array);
}

if($_REQUEST["option"]=="EMAIL_TEMPLATE_ENTRY"){
    $error='71,85,86';
    $error_array=get_error_msg($error);
    $values_array=array($error_array);
    echo JSON_ENCODE($values_array);
}

if($_REQUEST['option']=="ADMIN WEEKLY REPORT SEARCH UPDATE"){
    //GET ERR MSG FROM DB
    $str='4,16,17,110,83';
    $errormsg_array= get_error_msg($str);
//SET MIN DATE ND MAX DATE
    $admin_weekly_mindate=mysqli_query($con,"SELECT MIN(AWRD_DATE) as AWRD_DATE FROM ADMIN_WEEKLY_REPORT_DETAILS");
    while($row=mysqli_fetch_array($admin_weekly_mindate)){
        $admin_searchmin_date_value=$row["AWRD_DATE"];
//        $min_date = date('d-m-Y',strtotime($admin_searchmin_date_value));
        $min_date = date('Y-m-d',strtotime($admin_searchmin_date_value));
    }
    $admin_weekly_maxdate=mysqli_query($con,"SELECT MAX(AWRD_DATE) as AWRD_DATE FROM ADMIN_WEEKLY_REPORT_DETAILS");
    while($row=mysqli_fetch_array($admin_weekly_maxdate)){
        $admin_searchmin_date_value=$row["AWRD_DATE"];
//        $max_date = date('d-m-Y',strtotime($admin_searchmin_date_value));
        $max_date = date('Y-m-d',strtotime($admin_searchmin_date_value));

        date_default_timezone_set('Asia/Kolkata');
        $a_date = $admin_searchmin_date_value;
        $date = new DateTime($a_date);
        $date->modify('last day of this month');
        $date=$date->format('d');
    }
    $value_array=array($errormsg_array,$min_date,$max_date,$date);
    echo JSON_ENCODE($value_array);

}
if($_REQUEST['option']=="ADMIN WEEKLY REPORT ENTRY"){
    $str='3,7,84';
    $errormsg_array= get_error_msg($str);
    $comp_startdate=get_company_start_date();
    $value_array=array($errormsg_array,$comp_startdate);
    echo JSON_ENCODE($value_array);
}
if($_REQUEST["option"]=="PUBLIC_HOLIDAY"){
    $error='71,93,94,96';
    $error_array=get_error_msg($error);
    $values_array=array($error_array);
    echo JSON_ENCODE($values_array);
}

?>