<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE DETAIL ENTRY*********************************************//
//DONE BY:LALITHA
//VER 0.04-SD:17/12/2014 ED:18/12/2014,TRACKER NO:74,Checked conditn nd put err msgs,Added uld nd timestmp fields
//VER 0.03-SD:01/12/2014 ED:01/12/2014,TRACKER NO:74,Changed Preloder funct
//VER 0.02-SD:28/11/2014 ED:28/11/2014,TRACKER NO:74,Updated Validation,Err msg,Reset Function,Checked condition of alrdy ext nd valid id in save part,Aftr saved reset fn called
//DONE BY:SAFI
//VER 0.01-INITIAL VERSION, SD:02/10/2014 ED:06/10/2014,TRACKER NO:74,Designed Form,Get data from ss nd insert in db part
//*********************************************************************************************************//
error_reporting(0);
include '../TSLIB/TSLIB_CONNECTION.php';
include "../TSLIB/TSLIB_GET_USERSTAMP.php";
$USERSTAMP=$UserStamp;
//SAVE PART FOR PUBLIC HOLIDAY
if($_REQUEST['option']=="ph_save"){
    $ph_ssid=$_POST['PH_ENTRY_tb_ss'];
    $ph_gid=$_POST['PH_ENTRY_tb_gid'];
    $feed='https://docs.google.com/spreadsheets/d/'.$ph_ssid.'/export?gid='.$ph_gid.'&format=csv';
// Arrays we'll use later
    $keys = array();
    $newArray = array();
// Function to convert CSV into associative array
    function csvToArray($file, $delimiter) {
        if (($handle = fopen($file, 'r')) !== FALSE) {
            $i = 0;
            while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) {
                for ($j = 0; $j < count($lineArray); $j++) {
                    $arr[$i][$j] = $lineArray[$j];
                }
                $i++;
            }
            fclose($handle);
        }
        return $arr;
    }
// Do it
    $data = csvToArray($feed, ',');
// Set number of elements (minus 1 because we shift off the first row)
    $count = count($data) - 1;
//Use first row for names
    $labels = array_shift($data);
    foreach ($labels as $label) {
        $keys[] = $label;
    }
// Add Ids, just in case we want them later
    $keys[] = 'id';
    for ($i = 0; $i < $count; $i++) {
        $data[$i][] = $i;
    }
// Bring it all together
    for ($j = 0; $j < $count; $j++) {
        $d = array_combine($keys, $data[$j]);
        $newArray[$j] = $d;
    }
// Print it out as JSON
    $newArray=json_encode($newArray);
    $return_value = json_decode($newArray, true);

//print_r($return_value);
    $check_valid= count($return_value);
    $successflag=0;
    if($check_valid>0)
    {
        foreach ($return_value as $key => $value) {
            $date=$value['DATE'];
            $day=$value['HOLIDAY'];
            $uld_id=$value['ULD_ID'];
            $timestamp=$value['TIMESTAMP'];
            if(($date!='')&&($day!=''))
            {
                $sql="select * from PUBLIC_HOLIDAY where PH_DATE='$date'";
                $sql_result= mysqli_query($con,$sql);
                $row=mysqli_num_rows($sql_result);
                $x=$row;
                if($x > 0)
                {
                    $PH_already_exist_flag=1;
                }
                else{
                    $PH_already_exist_flag=0;
                }
                if($PH_already_exist_flag==0)
                {
                    $ph_insert="INSERT INTO PUBLIC_HOLIDAY(PH_DESCRIPTION,PH_DATE,ULD_ID,PH_TIMESTAMP)VALUES('$day','$date','$uld_id','$timestamp')";
                    if (!mysqli_query($con,$ph_insert)) {
                        die('Error: ' . mysqli_error($con));
                        $successflag=0;
                    }
                    else{
                        $successflag=1;
                    }
                }
            }
        }
    }
    $ph_final_array=array($PH_already_exist_flag,$successflag,$check_valid);
    echo json_encode($ph_final_array);
}

