<?php
//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMAIL TEMPLATE ENTRY*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:27/10/2014 ED:28/10/2014,TRACKER NO:99
//*********************************************************************************************************//
error_reporting(0);
if(isset($_REQUEST)){
    include "../TSLIB/TSLIB_CONNECTION.php";
    include "../TSLIB/TSLIB_COMMON.php";
    include "../TSLIB/TSLIB_GET_USERSTAMP.php";
    $USERSTAMP=$UserStamp;
    global $con;
    //FUNCTION FOR ALREADY EXIST FOR SCRIPT NAME
    if($_REQUEST['option']=="ET_ENTRY_already_result"){
        $ET_ENTRY_scriptname=$_REQUEST['ET_ENTRY_scriptname'];
        $sql="SELECT ET_EMAIL_SCRIPT FROM EMAIL_TEMPLATE WHERE ET_EMAIL_SCRIPT='$ET_ENTRY_scriptname'";
        $sql_result= mysqli_query($con,$sql);
        $row=mysqli_num_rows($sql_result);
        $x=$row;
        if($x > 0)
        {
            $ET_ENTRY_chkscriptname=1;
        }
        else{
            $ET_ENTRY_chkscriptname=0;
        }
        echo ($ET_ENTRY_chkscriptname);
    }
    //FUNCTION FOR TO SAVE THE EMAIL TEMPLATE
    if($_REQUEST['option']=="ET_ENTRY_insert"){
        $ET_ENTRY_scriptname=$_POST['ET_ENTRY_tb_scriptname'];
        $ET_ENTRY_subject=$_POST['ET_ENTRY_ta_subject'];
        $ET_ENTRY_subject= $con->real_escape_string($ET_ENTRY_subject);
        $ET_ENTRY_body=$_POST['ET_ENTRY_ta_body'];
        $ET_ENTRY_body= $con->real_escape_string($ET_ENTRY_body);
        $result = $con->query("CALL SP_TS_EMAIL_TEMPLATE_INSERT('$ET_ENTRY_scriptname','$ET_ENTRY_subject','$ET_ENTRY_body','$USERSTAMP',@EMAILINSERT_FLAG)");
        if(!$result) die("CALL failed: (" . $con->errno . ") " . $con->error);
        $select = $con->query('SELECT @EMAILINSERT_FLAG');
        $result = $select->fetch_assoc();
        $return_flag= $result['@EMAILINSERT_FLAG'];
        echo $return_flag;
    }
}
if($_POST['option']=='edit')
{
 $select="SELECT A.ETD_ID,B.ET_ID,A.ETD_EMAIL_SUBJECT,A.ETD_EMAIL_BODY,B.ET_EMAIL_SCRIPT,C.ULD_LOGINID, DATE_FORMAT(CONVERT_TZ(A.ETD_TIMESTAMP,'+00:00','+05:30'),'%d-%m-%Y %T') AS TIMESTAMP FROM EMAIL_TEMPLATE_DETAILS A LEFT JOIN EMAIL_TEMPLATE B ON A.ET_ID = B.ET_ID LEFT JOIN USER_LOGIN_DETAILS C ON A.ULD_ID = C.ULD_ID";
  $record =mysqli_query($con,$select);
 $insert=mysqli_num_rows($record);
 $y=$insert;
   $appendTable1="<table id='reg' border='1'  cellspacing='0'><thead style='background-color: #498af3; color: white; font-weight: bold'><tr><td align='center'>SCRIPT NAME</td><td align='center'>EMAIL SUBJECT</td><td align='center'>EMAIL BODY</td><td align='center'>USERSTAMP</td><td align='center'>TIMESTAMP</td></tr></thead><tbody>";
   while($insert=mysqli_fetch_array($record))
   {
       $ET_ENTRY_scriptname=$insert["ET_EMAIL_SCRIPT"];
      $ET_ENTRY_subject=$insert["ETD_EMAIL_SUBJECT"];
      $ET_ENTRY_body=$insert["ETD_EMAIL_BODY"];
       $ET_ENTRY_userstamp=$insert["ULD_LOGINID"];
       $ET_ENTRY_timestamp=$insert["TIMESTAMP"];
       $ET_ENTRY_el_id=$insert['ETD_ID'];
       $appendTable1.='<tr><td id=sname_'.$ET_ENTRY_id.' class="edit">'.$ET_ENTRY_scriptname.'</td><td id=emailsubject_'.$ET_ENTRY_el_id.' class="edit">'.$ET_ENTRY_subject.'</td><td id=emailbody_'.$ET_ENTRY_el_id.' class="edit">'.$ET_ENTRY_body.'</td><td id=user_'.$ET_ENTRY_el_id.' class="user"> '.$ET_ENTRY_userstamp.'</td><td id=time_'.$ET_ENTRY_el_id.' class="time"> '.$ET_ENTRY_timestamp.'</td></tr>';

//      $appendTable1.='<tr><td id=sname_'+id+' class="sname">'+ET_ENTRY_scriptname+'</td><td id=name_'+id+' class="emailsubject">'+$ET_ENTRY_subject+'</td><td id=body_'+id+' class="emailbody">'+ET_ENTRY_emailbody+'</td><td>'+ET_ENTRY_userstamp+'</td><td>'+ET_ENTRY_timestmp+'</td></tr></tdody></table>';
//('#ET_SRC_UPD_DEL_tble_htmltable').append(ET_SRC_UPD_DEL_table_value).show();
  }
 $appendTable1 .='</tbody></table>';
 echo $appendTable1;
}

//if($_REQUEST['option']=="EMAIL_TEMPLATE_DETAILS"){
////    $ET_SRC_UPD_DEL_scriptname=$_POST['ET_SRC_UPD_DEL_lb_scriptname'];
////    echo "SELECT A.ETD_EMAIL_SUBJECT,A.ETD_EMAIL_BODY,B.ET_EMAIL_SCRIPT,C.ULD_LOGINID,A.ETD_TIMESTAMP FROM EMAIL_TEMPLATE_DETAILS A LEFT JOIN EMAIL_TEMPLATE B ON A.ET_ID = B.ET_ID LEFT JOIN USER_LOGIN_DETAILS C ON A.ULD_ID = C.ULD_ID";
//
//    $ET_SRC_UPD_DEL_flextbl= mysqli_query($con,"SELECT A.ETD_EMAIL_SUBJECT,A.ETD_EMAIL_BODY,B.ET_EMAIL_SCRIPT,C.ULD_LOGINID,A.ETD_TIMESTAMP FROM EMAIL_TEMPLATE_DETAILS A LEFT JOIN EMAIL_TEMPLATE B ON A.ET_ID = B.ET_ID LEFT JOIN USER_LOGIN_DETAILS C ON A.ULD_ID = C.ULD_ID");
//
//    $ET_SRC_UPD_DEL_values=array();
//    while($row=mysqli_fetch_array($ET_SRC_UPD_DEL_flextbl)){
//        $ET_SRC_UPD_DEL_scriptname=$row["ETD_EMAIL_SCRIPT"];
//        $ET_SRC_UPD_DEL_subject=$row["ETD_EMAIL_SUBJECT"];
//        $ET_SRC_UPD_DEL_body=$row["ETD_EMAIL_BODY"];
//        $ET_SRC_UPD_DEL_userstamp=$row["ULD_LOGINID"];
//        $ET_SRC_UPD_DEL_timestamp=$row["TIMESTAMP"];
//        $ET_SRC_UPD_DEL_el_id=$row['ETD_ID'];
//        $final_values=(object) ['id'=>$ET_SRC_UPD_DEL_el_id,'ET_SRC_UPD_DEL_scriptname' =>$ET_SRC_UPD_DEL_scriptname ,'ET_SRC_UPD_DEL_subject' =>$ET_SRC_UPD_DEL_subject,'ET_SRC_UPD_DEL_body' =>$ET_SRC_UPD_DEL_body,'ET_SRC_UPD_DEL_userstamp'=>$ET_SRC_UPD_DEL_userstamp,'ET_SRC_UPD_DEL_timestamp'=>$ET_SRC_UPD_DEL_timestamp];
//
//        $ET_SRC_UPD_DEL_values[]=$final_values;
//    }
//    echo JSON_ENCODE($ET_SRC_UPD_DEL_values);
//}

//$project_name=$_REQUEST['babypname'];

//$project_desc=$con->real_escape_string($_REQUEST['babypdesc']);

//$project_sdate=date("Y-m-d",strtotime($_REQUEST['babysdate']));


if($_REQUEST['option']=='update')
{
    $ETD_ID=$_REQUEST['ET_ENTRY_el_id'];
    $project_desc=$con->real_escape_string($_REQUEST['babypdesc']);
    $project_email=$con->real_escape_string($_REQUEST['babyemail']);
    $update="UPDATE EMAIL_TEMPLATE_DETAILS SET ETD_EMAIL_SUBJECT='$project_desc',ETD_EMAIL_BODY='$project_email',ULD_ID=(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP') WHERE ETD_ID='$ETD_ID'";
    if ($con->query($update) === TRUE) {
        $flag= 1;
    } else {
        echo "Error: " . $update . "<br>" . $con->error;
        $flag=0;
    }
    echo $flag;
}

//if($_REQUEST['option']=='update')
//{
//    $ET_ENTRY_el_id=$_REQUEST['ETD_ID'];
////    $ET_ENTRY_subject=$_REQUEST['subjectvalue'];
//    $project_desc=$con->real_escape_string($_REQUEST['babypdesc']);
//
//    $update="UPDATE EMAIL_TEMPLATE_DETAILS SET ETD_EMAIL_SUBJECT='$project_desc' WHERE ETD_ID=  $ET_ENTRY_el_id";
////        $update="UPDATE emp SET Contact='$Contact' WHERE id=$uid";
////        $update="UPDATE emp SET Address='$Address' WHERE id=$uid";
////        $update="UPDATE emp SET Date='$Date' WHERE id=$uid";
//    if ($con->query($update) === TRUE) {
//        $flag= 1;
//    } else {
//        echo "Error: " . $update . "<br>" . $con->error;
//        $flag=0;
//    }
//    echo $flag;
//}
//if($_REQUEST['option']=='update')
//{
//    $ET_ENTRY_el_id=$_REQUEST['ETD_ID'];
////    $ET_ENTRY_body=$_REQUEST["bodyvalue"];
//    $project_sdate=date("Y-m-d",strtotime($_REQUEST['babysdate']));
//
//    $update="UPDATE EMAIL_TEMPLATE_DETAILS SET ETD_EMAIL_BODY='$project_sdate' WHERE ETD_ID=  $ET_ENTRY_el_id";
////        $update="UPDATE emp SET Contact='$Contact' WHERE id=$uid";
////        $update="UPDATE emp SET Address='$Address' WHERE id=$uid";
////        $update="UPDATE emp SET Date='$Date' WHERE id=$uid";
//    if ($con->query($update) === TRUE) {
//        $flag= 1;
//    } else {
//        echo "Error: " . $update . "<br>" . $con->error;
//        $flag=0;
//    }
//    echo $flag;
//}
//
//
//

?>
