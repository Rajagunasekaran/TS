<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************PUBLIC HOLIDAY ENTRY*********************************************//
//DONE BY:LALITHA
//VER 0.04-SD:17/12/2014 ED:18/12/2014,TRACKER NO:74,Checked conditn nd put err msgs,Added uld nd timestmp fields
//VER 0.03-SD:01/12/2014 ED:01/12/2014,TRACKER NO:74,Changed Preloder funct
//VER 0.02-SD:28/11/2014 ED:28/11/2014,TRACKER NO:74,Updated Validation,Err msg,Reset Function,Checked condition of alrdy ext nd valid id in save part,Aftr saved reset fn called
//DONE BY:SAFI
//VER 0.01-INITIAL VERSION, SD:02/10/2014 ED:06/10/2014,TRACKER NO:74,Designed Form,Get data from ss nd insert in db part
//*********************************************************************************************************//
<?php
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
    var PH_ENTRY_errorAarray=[];
    //START DOCUMENT READY FUNCTION
    $(document).ready(function(){
        //GETTING ERR MSG FROM DB
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var value_array=JSON.parse(xmlhttp.responseText);
                PH_ENTRY_errorAarray=value_array[0];
            }
        }
        var option="PUBLIC_HOLIDAY";
        xmlhttp.open("GET","COMMON.do?option="+option);
        xmlhttp.send();
        //DO VALIDATION START
        $(".autosizealph").doValidation({rule:'alphanumeric',prop:{whitespace:true,autosize:true,uppercase:false}});
        //DO VALIDATION END
        //FORM VALIDATION PART
        $(document).on('change','#PH_entry_form',function(){
            var PH_ENTRY_ssid= $("#PH_ENTRY_tb_ss").val();
            var PH_ENTRY_gid =$("#PH_ENTRY_tb_gid").val();
            if(PH_ENTRY_ssid!='' && PH_ENTRY_gid!='' ){
                $("#PH_ENTRY_btn_save").removeAttr("disabled");
            }
        });
        //CLICK FUNCTION FOR SAVE BUTTON
        $(document).on('click','#PH_ENTRY_btn_save',function(){
            $('.preloader', window.parent.document).show();
            var formElement = document.getElementById("PH_entry_form");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
                    var msg_alert_array=JSON.parse(xmlhttp.responseText);
                    var valid_ss=msg_alert_array[2];
                    var ph_date_already_exixst=msg_alert_array[0];
                    var ph_saved=msg_alert_array[1];
                    if(ph_date_already_exixst==0 && ph_saved==1 && valid_ss!=0){
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PUBLIC HOLIDAY ENTRY",msgcontent:PH_ENTRY_errorAarray[1]}});
                        PH_ENTRY_holiday_rset();
                    }
                    else if(ph_date_already_exixst==1){
                        //ALREADY EXIT MSG
                        $('#PH_ENTRY_lbl_already').text(PH_ENTRY_errorAarray[3]).show();
                        $("#PH_ENTRY_btn_save").attr("disabled","disabled");
                    }
                    else if(valid_ss==0){
                        //VALID KEY NS SS ID
                        $('#PH_ENTRY_lbl_valid').text(PH_ENTRY_errorAarray[2]).show();
                        $('#PH_ENTRY_lbl_valid1').text(PH_ENTRY_errorAarray[2]).show();
                        $("#PH_ENTRY_btn_save").attr("disabled","disabled");
                    }
                    else if(valid_ss>='1186'){
                        $('#PH_ENTRY_lbl_valid1').text(PH_ENTRY_errorAarray[2]).show();
                        $('#PH_ENTRY_lbl_valid').text(PH_ENTRY_errorAarray[2]).show();
                        $("#PH_ENTRY_btn_save").attr("disabled","disabled");
                    }
                    else
                    {
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PUBLIC HOLIDAY ENTRY",msgcontent:PH_ENTRY_errorAarray[0]}});
                    }
                }
            }
            var choice="ph_save"
            xmlhttp.open("POST","DB_PUBLIC_HOLIDAY_ENTRY.do?option="+choice,true);
            xmlhttp.send(new FormData(formElement));
        });
        //SAVE BUTTON VALIDATION
        $(document).on('change','#PH_entry_form',function(){
            $('#PH_ENTRY_lbl_valid').hide();
            $('#PH_ENTRY_lbl_already').hide();
            $('#PH_ENTRY_lbl_valid1').hide();
            $("#PH_ENTRY_tb_ss").removeClass('invalid');
            $("#PH_ENTRY_tb_gid").removeClass('invalid');
            var PH_ENTRY_ss= $("#PH_ENTRY_tb_ss").val();
            var PH_ENTRY_gid =$("#PH_ENTRY_tb_gid").val();
            if((PH_ENTRY_ss!='') && (PH_ENTRY_gid!='' ))
            {
                $("#PH_ENTRY_btn_save").removeAttr("disabled");
            }
            else
            {
                $("#PH_ENTRY_btn_save").attr("disabled","disabled");
            }
        });
        //RESET CLICK FUNCTION
        $(document).on('click','#PH_ENTRY_btn_reset',function(){
            PH_ENTRY_holiday_rset();
        });
        //CLEAR ALL FIELDS
        function PH_ENTRY_holiday_rset()
        {
            $("#PH_entry_form")[0].reset();
            $("#PH_ENTRY_btn_save").attr("disabled", "disabled");
            $('.sizefix').prop("size","20");
            $('#PH_ENTRY_lbl_valid').hide();
            $('#PH_ENTRY_lbl_valid1').hide();
            $('#PH_ENTRY_lbl_already').hide();
        }
        //END DOCUMENT READY FUNCTION
    });
    <!--SCRIPT TAG END-->
</script>
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>PUBLIC HOLIDAY ENTRY</h3><p></div></div>
    <form  name="PH_entry_form" id="PH_entry_form" class="content" >
        <table id="PH_ENTRY_table" >
            <td>
                <label name="PH_ENTRY_lbl_ss" id="PH_ENTRY_lbl_ss">SS KEY<em>*</em></label></td>
            <td><input type="text" name="PH_ENTRY_tb_ss" id="PH_ENTRY_tb_ss"  class="autosizealph sizefix title_alpha" >
                <label id="PH_ENTRY_lbl_valid" name="PH_ENTRY_lbl_valid" class="errormsg"></label></td>
            </tr>
            <tr>
                <td>
                    <label name="PH_ENTRY_lbl_gid" id="PH_ENTRY_lbl_gid">GID <em>*</em></label></td>
                <td><input type="text" name="PH_ENTRY_tb_gid" id="PH_ENTRY_tb_gid"  class="autosizealph sizefix title_alpha">
                    <label id="PH_ENTRY_lbl_already" name="PH_ENTRY_lbl_already" class="errormsg"></label>
                    <label id="PH_ENTRY_lbl_valid1" name="PH_ENTRY_lbl_valid1" class="errormsg"></label></td>
            </tr>
        </table>
        <tr>
            <td  align="right"><input class="btn" type="button"  id="PH_ENTRY_btn_save" name="SAVE" value="SAVE" disabled  /></td>
            <td align="left"><input type="button" class="btn" name="PH_ENTRY_btn_reset" id="PH_ENTRY_btn_reset" value="RESET"></td></tr>
        </tr>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->