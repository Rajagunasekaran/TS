<?php
//*********************************************GLOBAL DECLARATION******************************************//
//*********************************************************************************************************//
//*******************************************FILE DESCRIPTION*********************************************//
//****************************************DOOR ACCESS DETAILS*************************************************//
//DONE BY:SARADAMBAL
//VER 0.04-SD:06/01/2015 ED:06/01/2015,TRACKER NO:74,IMPLEMENTED PRELOADER POSITION,CHANGED LOGIN ID INTO EMPLOYEE NAME
//DONE BY: RAJA
//VER 0.03-SD:31/12/2014 ED:31/12/2014, TRACKER NO:166, DESC:IMPLEMENTED PDF BUTTON AND VALIDATED AND GAVE INPUT TO DB
//VER 0.02-SD:05/12/2014 ED:05/12/2014,TRACKER NO:74,IMPLEMENTED HEADER NAME FOR PDF AND DATA TABLE
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION,SD:04/11/2014 ED:04/11/2014,TRACKER NO:97
//*********************************************************************************************************//
error_reporting(0);
include "../../TSLIB/TSLIB_CONNECTION.php";
//GETTING ERR MSG
$errormessages=array();
$errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (15,102)");
while($row=mysqli_fetch_array($errormsg)){
    $errormessages[]=$row["EMC_DATA"];
}
//FETCHING DOOR_ACCESS RECORDS
$date= mysqli_query($con,"SELECT VW.EMPLOYEE_NAME,CPD.CPD_DOOR_ACCESS from VW_TS_ALL_ACTIVE_EMPLOYEE_DETAILS VW,COMPANY_PROPERTIES_DETAILS CPD,EMPLOYEE_DETAILS ED,USER_LOGIN_DETAILS ULD WHERE ED.ULD_ID=ULD.ULD_ID AND CPD.EMP_ID=ED.EMP_ID AND ULD.ULD_LOGINID=VW.ULD_LOGINID order by VW.EMPLOYEE_NAME");
$ure_values=array();
$final_values=array();
while($row=mysqli_fetch_array($date)){
    $DR_ACC_loginid=$row["EMPLOYEE_NAME"];
    $DR_ACC_draccess=$row["CPD_DOOR_ACCESS"];
    $final_values=array('loginid' =>$DR_ACC_loginid,'DR_ACC_draccess' =>$DR_ACC_draccess);
    $ure_values[]=$final_values;
}
$finalvalue=array($ure_values,$errormessages);
echo JSON_ENCODE($finalvalue);
?>