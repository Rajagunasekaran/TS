<?php

include "../../TSLIB/TSLIB_CONNECTION.php";
include "../../TSLIB/TSLIB_COMMON.php";

//GETTING ERR MSG
$errormessages=array();
$errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (83,99)");
while($row=mysqli_fetch_array($errormsg)){
    $errormessages[]=$row["EMC_DATA"];
}
if($_REQUEST['option']=="ALL ACTIVE EMPLOYEE")
{
    $date= mysqli_query($con,"SELECT CONCAT(EMP.EMP_FIRST_NAME,' ',EMP.EMP_LAST_NAME) AS EMPLOYEE_NAME,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CPD.CPD_LAPTOP_BAG,CPD.CPD_MOUSE,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET FROM COMPANY_PROPERTIES CP,COMPANY_PROPERTIES_DETAILS CPD,EMPLOYEE_DETAILS EMP,USER_ACCESS UA WHERE CP.CP_ID=CPD.CP_ID  AND EMP.EMP_ID=CPD.EMP_ID AND UA.ULD_ID=EMP.ULD_ID AND UA.UA_TERMINATE IS NULL ORDER BY EMPLOYEE_NAME ASC");
    $ure_values=array();
    $final_values=array();
    while($row=mysqli_fetch_array($date)){
        $AE_empname=$row['EMPLOYEE_NAME'];
        $CPD_laptopno=$row['CP_LAPTOP_NUMBER'];
        $CPD_chargerno=$row['CP_CHARGER_NUMBER'];
        $CPD_laptopbag=$row['CPD_LAPTOP_BAG'];
        $CPD_mouse=$row['CPD_MOUSE'];
        $CPD_dooraccess=$row['CPD_DOOR_ACCESS'];
        $CPD_idcard=$row['CPD_ID_CARD'];
        $CPD_headset=$row['CPD_HEADSET'];
        $final_values=array('empname'=>$AE_empname,'laptopno'=>$CPD_laptopno,'chargerno'=>$CPD_chargerno,'laptopbag'=> $CPD_laptopbag,'mouse'=>$CPD_mouse,'dooraccess'=>$CPD_dooraccess,'idcard'=>$CPD_idcard,'headset'=>$CPD_headset);
        $ure_values[]=$final_values;
    }
    $finalvalue=array($ure_values,$errormessages);
    echo JSON_ENCODE($finalvalue);
}
if($_REQUEST['option']=="ALL NONACTIVE EMPLOYEE")
{
    $date= mysqli_query($con,"SELECT V.EMPLOYEE_NAME,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CPD.CPD_LAPTOP_BAG,CPD.CPD_MOUSE,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET FROM VW_TS_ALL_NON_ACTIVE_EMPLOYEE_DETAILS V,COMPANY_PROPERTIES CP,COMPANY_PROPERTIES_DETAILS CPD,EMPLOYEE_DETAILS EMP WHERE CP.CP_ID=CPD.CP_ID AND EMP.EMP_ID=CPD.EMP_ID AND V.ULD_ID=EMP.ULD_ID ORDER BY V.EMPLOYEE_NAME ASC");
    $ure_values=array();
    $final_values=array();
    while($row=mysqli_fetch_array($date)){
        $AE_empname=$row['EMPLOYEE_NAME'];
        $CPD_laptopno=$row['CP_LAPTOP_NUMBER'];
        $CPD_chargerno=$row['CP_CHARGER_NUMBER'];
        $CPD_laptopbag=$row['CPD_LAPTOP_BAG'];
        $CPD_mouse=$row['CPD_MOUSE'];
        $CPD_dooraccess=$row['CPD_DOOR_ACCESS'];
        $CPD_idcard=$row['CPD_ID_CARD'];
        $CPD_headset=$row['CPD_HEADSET'];
        $final_values=array('empname'=>$AE_empname,'laptopno'=>$CPD_laptopno,'chargerno'=>$CPD_chargerno,'laptopbag'=> $CPD_laptopbag,'mouse'=>$CPD_mouse,'dooraccess'=>$CPD_dooraccess,'idcard'=>$CPD_idcard,'headset'=>$CPD_headset);
        $ure_values[]=$final_values;
    }
    $finalvalue=array($ure_values,$errormessages);
    echo JSON_ENCODE($finalvalue);
}
if($_REQUEST['option']=="ACTIVE_EMPLOYEE")
{
    $empdet_active_emp=get_active_emp_id();
    $empdet_active_nonemp=get_nonactive_emp_id();
    $final_values=array($empdet_active_emp,$empdet_active_nonemp);
    echo JSON_ENCODE($final_values);
}

if($_REQUEST['option']=="ACTIVE_EMPLOYEE_companydet")
{
    $EMP_loginid=$_REQUEST['login_id'];
    // echo $EMP_loginid;
    $date=mysqli_query($con,"SELECT CONCAT(EMP.EMP_FIRST_NAME,' ',EMP.EMP_LAST_NAME) AS EMPLOYEE_NAME,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CPD.CPD_LAPTOP_BAG,CPD.CPD_MOUSE,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET FROM COMPANY_PROPERTIES CP,COMPANY_PROPERTIES_DETAILS CPD,EMPLOYEE_DETAILS EMP,USER_ACCESS UA,USER_LOGIN_DETAILS ULD WHERE CP.CP_ID=CPD.CP_ID  AND EMP.EMP_ID=CPD.EMP_ID AND UA.ULD_ID=EMP.ULD_ID AND ULD.ULD_ID=UA.ULD_ID AND ULD.ULD_ID=EMP.ULD_ID AND UA.UA_TERMINATE IS NULL AND ULD.ULD_ID=$EMP_loginid ORDER BY EMPLOYEE_NAME ASC");
    $ure_values=array();
    $final_values=array();
    while($row=mysqli_fetch_array($date)){
        $AE_empname=$row['EMPLOYEE_NAME'];
        $CPD_laptopno=$row['CP_LAPTOP_NUMBER'];
        $CPD_chargerno=$row['CP_CHARGER_NUMBER'];
        $CPD_laptopbag=$row['CPD_LAPTOP_BAG'];
        $CPD_mouse=$row['CPD_MOUSE'];
        $CPD_dooraccess=$row['CPD_DOOR_ACCESS'];
        $CPD_idcard=$row['CPD_ID_CARD'];
        $CPD_headset=$row['CPD_HEADSET'];
        $final_values=array('empname'=>$AE_empname,'laptopno'=>$CPD_laptopno,'chargerno'=>$CPD_chargerno,'laptopbag'=> $CPD_laptopbag,'mouse'=>$CPD_mouse,'dooraccess'=>$CPD_dooraccess,'idcard'=>$CPD_idcard,'headset'=>$CPD_headset);
        $ure_values[]=$final_values;
    }

    $finalvalue=array($ure_values,$errormessages,);
    echo JSON_ENCODE($finalvalue);
}

if($_REQUEST['option']=="NONACTIVE_EMPLOYEE_companydet")
{
    $EMP_loginid=$_REQUEST['login_id'];
    $date=mysqli_query($con,"SELECT V.EMPLOYEE_NAME,CP.CP_LAPTOP_NUMBER,CP.CP_CHARGER_NUMBER,CPD.CPD_LAPTOP_BAG,CPD.CPD_MOUSE,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET FROM VW_TS_ALL_NON_ACTIVE_EMPLOYEE_DETAILS V,COMPANY_PROPERTIES CP,COMPANY_PROPERTIES_DETAILS CPD,EMPLOYEE_DETAILS EMP WHERE CP.CP_ID=CPD.CP_ID AND EMP.EMP_ID=CPD.EMP_ID AND V.ULD_ID=EMP.ULD_ID AND EMP.ULD_ID=$EMP_loginid ORDER BY V.EMPLOYEE_NAME ASC");
    $ure_values=array();
    $final_values=array();
    while($row=mysqli_fetch_array($date)){
        $AE_empname=$row['EMPLOYEE_NAME'];
        $CPD_laptopno=$row['CP_LAPTOP_NUMBER'];
        $CPD_chargerno=$row['CP_CHARGER_NUMBER'];
        $CPD_laptopbag=$row['CPD_LAPTOP_BAG'];
        $CPD_mouse=$row['CPD_MOUSE'];
        $CPD_dooraccess=$row['CPD_DOOR_ACCESS'];
        $CPD_idcard=$row['CPD_ID_CARD'];
        $CPD_headset=$row['CPD_HEADSET'];
        $final_values=array('empname'=>$AE_empname,'laptopno'=>$CPD_laptopno,'chargerno'=>$CPD_chargerno,'laptopbag'=> $CPD_laptopbag,'mouse'=>$CPD_mouse,'dooraccess'=>$CPD_dooraccess,'idcard'=>$CPD_idcard,'headset'=>$CPD_headset);
        $ure_values[]=$final_values;
    }
    $finalvalue=array($ure_values,$errormessages,);
    echo JSON_ENCODE($finalvalue);
}
?>