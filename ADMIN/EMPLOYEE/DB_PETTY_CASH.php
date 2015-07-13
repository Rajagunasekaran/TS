<?php
error_reporting(0);
include "../../TSLIB/TSLIB_CONNECTION.php";
include "../../TSLIB/TSLIB_GET_USERSTAMP.php";
include "../../TSLIB/TSLIB_COMMON.php";

global $USERSTAMP;
global $con;
//FUNTION FOR CURRENT BALANCE LOAD
if($_POST['Option']=='load')
{
    $sql="SELECT PC_BALANCE FROM PETTY_CASH WHERE PC_ID=(SELECT MAX(PC_ID) FROM PETTY_CASH)";
    $result=mysqli_query($con,$sql);
    while($row=mysqli_fetch_array($result))
    {
        $opt=$row["PC_BALANCE"];
    }
    if ($opt==null)
    {
        $opt=0;
    }
    echo ($opt);
}
if($_POST['Option']=='Insert')
{
    $Date=date("Y-m-d",strtotime($_POST['PC_tb_DATE']));
    $amount=$_POST['PC_tb_atm'];
    $inviceitem=$_POST['PC_tb_INVOICE_ITEMS'];
    $Report=$_POST['PC_tb_COMMENTS'];
    $cash=$_POST['PC_cash_radio'];

    $sql1="select SUM(PC_CASH_IN)  as PC_CASH_IN from PETTY_CASH";

    $result1=mysqli_query($con,$sql1);

    while($row=mysqli_fetch_array($result1))
    {
        $cashin=$row["PC_CASH_IN"];
    }
    if($cash=='PC_CASH_IN')
    {
        $cashin=$cashin+$amount;
    }
    else
    {
        $cashin=$cashin;
    }

    $sql2=("select SUM(PC_CASH_OUT) as PC_CASH_OUT from PETTY_CASH");
    $result2=mysqli_query($con,$sql2);
    while($row=mysqli_fetch_array($result2))
    {
        $cashout=$row["PC_CASH_OUT"];
    }

    if($cashout==null)
    {
        $cashout=0;
    }
    if($cash=='PC_CASH_OUT')
    {
        $cashout=$cashout+$amount;
    }
    else
    {
        $cashout=$cashout;
    }

    $op= $cashin - $cashout;

    if ($_POST['PC_cash_radio']=="PC_CASH_IN")
    {
        $sql="INSERT INTO PETTY_CASH(PC_DATE,PC_CASH_IN ,PC_CASH_OUT ,PC_BALANCE ,PC_INVOICE_ITEMS,PC_COMMENTS,ULD_ID) VALUES ('$Date','$amount',null,'$op','$inviceitem','$Report',(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP'))";
    }
    if($_POST['PC_cash_radio']=="PC_CASH_OUT")
    {
        $sql="INSERT INTO PETTY_CASH (PC_DATE,PC_CASH_IN ,PC_CASH_OUT ,PC_BALANCE ,PC_INVOICE_ITEMS,PC_COMMENTS,ULD_ID) VALUES ('$Date',null,'$amount','$op','$inviceitem','$Report',(SELECT ULD_ID FROM USER_LOGIN_DETAILS WHERE ULD_LOGINID='$USERSTAMP'))";
    }

    $flag='';
    if(!mysqli_query($con,$sql))
    {
        $flag=0;
    }
    else
    {
        $flag=1;
    }

    $array=array($op,$flag);
    echo json_encode($array);
}
// FUNTION FOR SHOW DATABLE
if($_POST['option']=='ShowDetails')
{
    $select="SELECT * FROM PETTY_CASH ";
    $record =mysqli_query($con,$select);
    $insert=mysqli_num_rows($record);
    //$y=$insert;

    $appendTable1="<table id='Pettycash' border='1' class='display' style='width:1200px'><thead style='background-color: #498af3; color: white; font-weight: bold'><tr><td>DATE</td><td>CASHIN</td><td>CASH OUT</td><td>CURRENT BALANCE</td><td>INV0ICE ITEM</td><td>COMMENTS</td></tr></thead><tbody>";
    while($insert=mysqli_fetch_array($record))
    {
        $rowid=$insert["PC_ID"];
        $date=$insert["PC_DATE"];
        $cashin=$insert["PC_CASH_IN"];
        $cashout=$insert["PC_CASH_OUT"];
        $total=$insert["PC_BALANCE"];
        $invoiceitem=$insert["PC_INVOICE_ITEMS"];
        $Report=$insert["PC_COMMENTS"];

        $appendTable1.='<tr><td id=PCDATE_'.$rowid.' class="edit" >'.$date.'</td>
                     <td id=PCCASHIN_'.$rowid.' class="edit">'.$cashin.'</td>
                     <td id=PCCASHOUT_'.$rowid.' class="edit">'.$cashout.'</td>
                     <td id=PCBALANCE_'.$rowid.' class="edit">'.$total.'</td>
                     <td id=PCINVOICEITEMS_'.$rowid.' class="edit">'.$invoiceitem.'</td>
                     <td id=PCCOMMENTS_'.$rowid.' class="edit">'.$Report.'</td>
                     </tr>';

    }
    $appendTable1 .='</tbody></table>';
    echo $appendTable1;
}
//INLINE EDIT
if($_REQUEST['option']=='update')
{

    $PC_ID=$_REQUEST['rowid'];
    $PC_Cashin=$_REQUEST['PCCASHIN'];
    $PC_Cashout=$_REQUEST['PCCASHOUT'];
    $PC_Balance=$_REQUEST['PCBALANCE'];
    $PC_invoice=$_REQUEST['PCINVOICEITEMS'];
    $PC_Comments=$_REQUEST['PCCOMMENTS'];
    $PC_sdate=date("Y-m-d",strtotime($_REQUEST['PCDATE']));

    if ($PC_Cashin ==null)
    {
        $PC_Cashin=00.00;

    }
    else
    {
        $PC_Cashin=$PC_Cashin;
    }
    if ($PC_Cashout==null)
    {
        $PC_Cashout=00.00;

    }
    else
    {
        $PC_Cashout=$PC_Cashout;
    }

    $update="UPDATE PETTY_CASH SET PC_DATE='$PC_sdate',PC_CASH_IN=$PC_Cashin,PC_CASH_OUT=$PC_Cashout,PC_BALANCE=$PC_Balance,PC_INVOICE_ITEMS='$PC_invoice' AND PC_COMMENTS='$PC_Comments' WHERE PC_ID=$PC_ID";
    ECHO($update);
    exit;
    if ($con->query($update) === TRUE)
    {
        $flag=1;
    }
    else
    {
        echo "Error: " . $update . "<br>" . $con->error;
        $flag=0;
    }
    echo $flag;
}
?>