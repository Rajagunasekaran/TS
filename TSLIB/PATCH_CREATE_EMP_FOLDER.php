    <?php
    /**
     * Created by PhpStorm.
     * User: SSOMENS-018_2
     * Date: 1/4/15
     * Time: 3:15 PM
     */
    ini_set('max_execution_time', 1000);
    set_include_path( get_include_path() . PATH_SEPARATOR . 'google-api-php-client-master/src' );
    require_once 'google-api-php-client-master/src/Google/Client.php';
    require_once 'google-api-php-client-master/src/Google/Service/Drive.php';
    include 'google-api-php-client-master/src/Google/Service/Calendar.php';
    include "TSLIB_CONNECTION.php";
    include "CONFIG.php";
        global $con,$ClientId,$ClientSecret,$RedirectUri,$DriveScopes,$CalenderScopes,$Refresh_Token;
        $select_folderid=mysqli_query($con,"SELECT * FROM USER_RIGHTS_CONFIGURATION WHERE URC_ID=13");
        if($row=mysqli_fetch_array($select_folderid)){
            $folderid=$row["URC_DATA"];
        }
    //echo "SELECT ULD_ID,EMPLOYEE_NAME from VW_TS_ALL_EMPLOYEE_DETAILS where URC_DATA!='SUPER ADMIN' AND ULD_ID BETWEEN 1 AND 50";
    //exit;
        $login_idqry=mysqli_query($con,"SELECT ULD_ID,EMPLOYEE_NAME from VW_TS_ALL_EMPLOYEE_DETAILS where URC_DATA!='SUPER ADMIN' AND ULD_ID BETWEEN 1 AND 10");
        $login_empidarray=array();
        while($row=mysqli_fetch_array($login_idqry)){
            if(preg_match("/$row[0]/",$row[1]))
                $login_empidarray[]=$row["EMPLOYEE_NAME"];
            else
                $login_empidarray[]=$row["EMPLOYEE_NAME"]." ".$row["ULD_ID"];
        }
    $drive = new Google_Client();
    $drive->setClientId($ClientId);
    $drive->setClientSecret($ClientSecret);
    $drive->setRedirectUri($RedirectUri);
    $drive->setScopes(array($DriveScopes,$CalenderScopes));
    $drive->setAccessType('online');
    $refresh_token=$Refresh_Token;
    $drive->refreshToken($refresh_token);
    $service = new Google_Service_Drive($drive);
    print_r($login_empidarray);
    $emp_folderid="";
    for($i=0;$i<count($login_empidarray);$i++)
    {

        $children = $service->children->listChildren($folderid);
    //    $root_filearray=$children->getItems();
    //    foreach ($root_filearray as $child) {
    //        if($service->files->get($child->getId())->getExplicitlyTrashed()==1)continue;
    //        $rootfold_title=$service->files->get($child->getId())->title;
    //        if($login_empidarray[$i]!=$rootfold_title)continue;
    //        $emp_folderid=$service->files->get($child->getId())->id;
    //        echo $emp_folderid;
    //        break;
    //    }
    //        if($emp_folderid=="")
    //        {
            $newFolder = new Google_Service_Drive_DriveFile();
            $newFolder->setMimeType('application/vnd.google-apps.folder');
            $newFolder->setTitle($login_empidarray[$i]);
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
    //        }
    //    else
    //        continue;

    }