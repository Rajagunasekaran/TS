<?php

include "CONNECTION.php";

//GETTING ERR MSG
$errormessages=array();
$errormsg=mysqli_query($con,"SELECT DISTINCT EMC_DATA FROM ERROR_MESSAGE_CONFIGURATION WHERE EMC_ID IN (83,99)");
while($row=mysqli_fetch_array($errormsg)){
    $errormessages[]=$row["EMC_DATA"];
}

//FETCHING USER LOGIN DETAILS RECORDS

//    $date= mysqli_query($con,"SELECT ED.EMP_ID,ED.ULD_ID,CPD.CPD_ID,AE.EMPLOYEE_NAME,ED.EMP_DOB,ED.EMP_DESIGNATION,ED.EMP_MOBILE_NUMBER,ED.EMP_NEXT_KIN_NAME,ED.EMP_RELATIONHOOD,ED.EMP_ALT_MOBILE_NO,ED.EMP_USERSTAMP_ID,ED.EMP_TIMESTAMP,ED.EMP_BANK_NAME,ED.EMP_BRANCH_NAME,ED.EMP_ACCOUNT_NAME,ED.EMP_ACCOUNT_NO,ED.EMP_IFSC_CODE,ED.EMP_ACCOUNT_TYPE,ED.EMP_BRANCH_ADDRESS,ED.EMP_AADHAAR_NO,ED.EMP_PASSPORT_NO,ED.EMP_VOTER_ID,ED.EMP_COMMENTS,CPD.CPD_LAPTOP_NUMBER,CPD.CPD_CHARGER_NUMBER,CPD.CPD_LAPTOP_BAG,CPD.CPD_MOUSE,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET,CPD.CPD_TIMESTAMP FROM EMPLOYEE_DETAILS ED LEFT JOIN company_properties_details CPD ON ED.EMP_ID=CPD.EMP_ID, VW_TS_ALL_EMPLOYEE_DETAILS AE WHERE   ED.ULD_ID = AE.ULD_ID GROUP BY ED.EMP_ID ORDER BY AE.EMPLOYEE_NAME");
    $date= mysqli_query($con,"SELECT AE.EMPLOYEE_NAME,DATE_FORMAT(ED.EMP_DOB,'%d-%m-%Y') AS EMP_DOB,ED.EMP_DESIGNATION,ED.EMP_MOBILE_NUMBER,ED.EMP_NEXT_KIN_NAME,ED.EMP_RELATIONHOOD,ED.EMP_ALT_MOBILE_NO,ULD.ULD_USERSTAMP,DATE_FORMAT(CONVERT_TZ(ED.EMP_TIMESTAMP,'+00:00','+05:30'),'%d-%m-%Y %T') AS EMP_TIMESTAMP,ED.EMP_BANK_NAME,ED.EMP_BRANCH_NAME,ED.EMP_ACCOUNT_NAME,ED.EMP_ACCOUNT_NO,ED.EMP_IFSC_CODE,ED.EMP_ACCOUNT_TYPE,ED.EMP_BRANCH_ADDRESS,ED.EMP_AADHAAR_NO,ED.EMP_PASSPORT_NO,ED.EMP_VOTER_ID,ED.EMP_COMMENTS,CPD.CPD_LAPTOP_NUMBER,CPD.CPD_CHARGER_NUMBER,CPD.CPD_LAPTOP_BAG,CPD.CPD_MOUSE,CPD.CPD_DOOR_ACCESS,CPD.CPD_ID_CARD,CPD.CPD_HEADSET FROM USER_LOGIN_DETAILS ULD, EMPLOYEE_DETAILS ED LEFT JOIN COMPANY_PROPERTIES_DETAILS CPD ON ED.EMP_ID=CPD.EMP_ID, VW_TS_ALL_EMPLOYEE_DETAILS AE WHERE  ED.ULD_ID = ULD.ULD_ID AND ED.ULD_ID = AE.ULD_ID GROUP BY ED.EMP_ID ORDER BY AE.EMPLOYEE_NAME");
    $ure_values=array();
    $final_values=array();
    while($row=mysqli_fetch_array($date)){

        $AE_empname=$row['EMPLOYEE_NAME'];
        $ED_empdob=$row['EMP_DOB'];
        $ED_desgn=$row['EMP_DESIGNATION'];
        $ED_empmblno=$row['EMP_MOBILE_NUMBER'];
        $ED_empnkn=$row['EMP_NEXT_KIN_NAME'];
        $ED_relation=$row['EMP_RELATIONHOOD'];
        $ED_empaltmbl=$row['EMP_ALT_MOBILE_NO'];
        $ED_empuserstamp=$row['ULD_USERSTAMP'];
        $ED_emptimestamp=$row['EMP_TIMESTAMP'];
        $ED_empbank=$row['EMP_BANK_NAME'];
        $ED_empbranch=$row['EMP_BRANCH_NAME'];
        $ED_empaccntname=$row['EMP_ACCOUNT_NAME'];
        $ED_empaccntno=$row['EMP_ACCOUNT_NO'];
        $ED_empifsc=$row['EMP_IFSC_CODE'];
        $ED_empaccnttype=$row['EMP_ACCOUNT_TYPE'];
        $ED_empbranchaddrs=$row['EMP_BRANCH_ADDRESS'];
        $ED_empaadhaar=$row['EMP_AADHAAR_NO'];
        $ED_emppassport=$row['EMP_PASSPORT_NO'];
        $ED_empvoterid=$row['EMP_VOTER_ID'];
        $ED_empcomments=$row['EMP_COMMENTS'];
        $CPD_laptopno=$row['CPD_LAPTOP_NUMBER'];
        $CPD_chargerno=$row['CPD_CHARGER_NUMBER'];
        $CPD_laptopbag=$row['CPD_LAPTOP_BAG'];
        $CPD_mouse=$row['CPD_MOUSE'];
        $CPD_dooraccess=$row['CPD_DOOR_ACCESS'];
        $CPD_idcard=$row['CPD_ID_CARD'];
        $CPD_headset=$row['CPD_HEADSET'];

        $final_values=array('empname'=>$AE_empname,'empdob'=> $ED_empdob,'desgn'=>$ED_desgn,'empmblno'=>$ED_empmblno,'empnkn'=>$ED_empnkn,'relation'=>$ED_relation,'empaltmbl'=>$ED_empaltmbl,'empuserstamp'=>$ED_empuserstamp,'emptimestamp'=>$ED_emptimestamp,'empbank'=>$ED_empbank,'empbranch'=>$ED_empbranch, 'empaccntname'=>$ED_empaccntname,'ampaccntno'=>$ED_empaccntno,'ampifsc'=>$ED_empifsc,'empaccnttype'=>$ED_empaccnttype,'empbranchaddrs'=>$ED_empbranchaddrs,'empaadhaar'=>$ED_empaadhaar,'emppassport'=>$ED_emppassport,'empvoterid'=> $ED_empvoterid,'empcomments'=>$ED_empcomments,'laptopno'=>$CPD_laptopno,'chargerno'=>$CPD_chargerno,'laptopbag'=> $CPD_laptopbag,'mouse'=>$CPD_mouse,'dooraccess'=>$CPD_dooraccess,'idcard'=>$CPD_idcard,'headset'=>$CPD_headset);
        $ure_values[]=$final_values;

    }
$finalvalue=array($ure_values,$errormessages);
echo JSON_ENCODE($finalvalue);

?>