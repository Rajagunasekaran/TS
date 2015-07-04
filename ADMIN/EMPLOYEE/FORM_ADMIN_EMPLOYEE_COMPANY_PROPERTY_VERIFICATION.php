<!--//*******************************************FILE DESCRIPTION*********************************************//
//***********************************************COMPANY PROPERTY VERFICATION*******************************//
//DONE BY:LALITHA
//VER 0.04 SD:02/07/2015 ED:02/07/2015,github ver :7.1,text area responsive fixed,added ne err msg nd showned fr no laptop empl nd hided other fields,after reset ta width fixed
//DONE BY:LALITHA
//VER 0.03 SD:09/12/2014 ED:08/12/2014,TRACKER NO:74,Changed preloader position,login id changed to emp name in mail part
//VER 0.02 SD:06/12/2014 ED:08/12/2014,TRACKER NO:74,Updated preloader position nd message box position,Changed loginid to emp name
//VER 0.01-INITIAL VERSION, SD:03/11/2014 ED:04/11/2014,TRACKER NO:97
//************************************************************************************************************-->
<?php
include "../../TSLIB/TSLIB_HEADER.php";
?>
<!--HTML TAG START-->
<html>
<head>
    <!--SCRIPT TAG START-->
    <script>
        //GLOBAL DECLARATION
        var CPVD_login_id=[];
        var loginid=[];
        var err_msg_array=[];
        var active_loginid=[];
        //READY FUNCTION START
        $(document).ready(function(){
            $("#CPVD_ta_reason").hide();
            $("#CPVD_lb_loginid").hide();
            var CPVD_lb_loginid_val;
            //JQUERY LIB VALIDATION START
            $('textarea').autogrow({onInitialize: true});
            //JQUERY LIB VALIDATION END
            //GETTING ERR MSGS
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var values_array=JSON.parse(xmlhttp.responseText);
                    err_msg_array=values_array[0];
                    active_loginid=values_array[1];
                    var CPVD_act_loginid_list='<option>SELECT</option>';
                    for (var i=0;i<active_loginid.length;i++) {
                        CPVD_act_loginid_list += '<option value="' + active_loginid[i][1]+ '">' + active_loginid[i][0]+ '</option>';
                    }
                    $('#CPVD_lb_chckdby').html(CPVD_act_loginid_list);

                }
            }
            var option="INITIAL_DATAS";
            xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_EMPLOYEE_COMPANY_PROPERTY_VERIFICATION.do?option="+option);
            xmlhttp.send();
            var data='';
            var action = '';
            showTable()
            var flag=0;
            //FUNCTION FOR LOADING ACTIVE LOGIN ID
            function showTable(){
                $.ajax({
                    url:"ADMIN/EMPLOYEE/DB_EMPLOYEE_COMPANY_PROPERTY_VERIFICATION.do",
                    type:"POST",
                    data:"option=showData",
                    cache: false,
                    success: function(response){
                        loginid=response;
                        if(loginid!=0)
                        {
                            $(".preloader").hide();
                            $('#CPVD_lbl_loginid').show();
                            $('#CPVD_lb_loginid').html(loginid).show();
                        }
                        else
                        {
                            $('#CPVD_lbl_nologinid').text(err_msg_array[2]).show();
                        }
                    }
                });
            }
            var CPVD_laptop_no=[];
            //CHANGE FUNCTION FOR LOGIN ID
            $(document).on('change','#CPVD_lb_loginid',function(){
                $(".preloader").show();
                $('#CPVD_ta_reason').val('');
                $('#CPVD_lbl_nolaptop').hide();
                $('#CPVD_lbl_laptopno').hide();
                $('#CPVD_tb_laptopno').hide();
                $('#CPVD_lbl_chargerno').hide();
                $("#CPVD_btn_send").attr("disabled", "disabled");
                var CPVD_lb_loginid=$('#CPVD_lb_loginid').val();
                CPVD_lb_loginid_val=$("#CPVD_lb_loginid option:selected").text();
                if(CPVD_lb_loginid_val!='SELECT')
                {
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            var value_array=JSON.parse(xmlhttp.responseText);
                            $(".preloader").hide();
                            for(var i=0;i<value_array.length;i++){
                                var CPVD_laptop_no=value_array[i].CPVD_lap_no;
                                var CPVD_charger_no=value_array[i].CPVD_charger_no;
                            }
                            if(CPVD_laptop_no!=null) {
                                $('#CPVD_lbl_laptopno').show();
                                $('#CPVD_tb_laptopno').val(CPVD_laptop_no).show();
                                $('#CPVD_lbl_chargerno').show();
                                var EMPSRC_UPD_DEL_employlstnmes = (CPVD_charger_no).length + 7;
                                $('#CPVD_tb_chargerno').attr("size", EMPSRC_UPD_DEL_employlstnmes);
                                $('#CPVD_tb_chargerno').val(CPVD_charger_no).show();
                                $('#CPVD_lbl_chckdby').show();
                                $('#CPVD_lb_chckdby').show();
                                $('#CPVD_lbl_chckdby').show();
                                $('#CPVD_lbl_reason').show();
                                $('#CPVD_ta_reason').show();
                                $('#CPVD_btns_sendreset').show();
                            }
                            else
                            {
                                $('#CPVD_lbl_nolaptop').text(err_msg_array[3]).show();
                            }
                        }
                    }
                    var option="COMPANY_PROPERTY";
                    xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_EMPLOYEE_COMPANY_PROPERTY_VERIFICATION.do?option="+option+"&CPVD_lb_loginid="+CPVD_lb_loginid);
                    xmlhttp.send();
                }
                else
                {
                    $(".preloader").hide();
                    $('#CPVD_lbl_reason').hide();
                    $('#CPVD_ta_reason').hide();
                    $('#CPVD_lbl_laptopno').hide();
                    $('#CPVD_tb_laptopno').hide();
                    $('#CPVD_lbl_chargerno').hide();
                    $('#CPVD_tb_chargerno').hide();
                    $('#CPVD_lbl_chckdby').hide();
                    $('#CPVD_lb_chckdby').hide();
                    $('#CPVD_btns_sendreset').hide();
                }
            });
            //BLUR FUNCTION FOR TRIM REASON
            $("#CPVD_ta_reason").blur(function(){
                $(".preloader").hide();
                $('#CPVD_ta_reason').val($('#CPVD_ta_reason').val().toUpperCase())
                var trimfunc=($('#CPVD_ta_reason').val()).trim()
                $('#CPVD_ta_reason').val(trimfunc)
            });
            //CHANGE FUNCTION FOR VALIDATION
            $(document).on('change','#CPVD_form_cmpnypropverfictn',function(){
                $("#CPVD_btn_send").attr("disabled", "disabled");
                var CPVD_tb_laptopno=$('#CPVD_tb_laptopno').val();
                var CPVD_tb_chargerno=$('#CPVD_tb_chargerno').val();
                var CPVD_lb_loginid=$('#CPVD_lb_loginid').val();
                var CPVD_lb_chckdby=$('#CPVD_lb_chckdby').val();
                var CPVD_ta_reason=$('#CPVD_ta_reason').val();
                if((CPVD_lb_loginid=="SELECT") ||(CPVD_tb_laptopno=="") || (CPVD_tb_chargerno=="")|| (CPVD_ta_reason=="")|| (CPVD_lb_chckdby=="SELECT"))
                {
                    $("#CPVD_btn_send").attr("disabled", "disabled");
                }
                else
                {
                    $("#CPVD_btn_send").removeAttr("disabled");
                }
            });
            //CLICK EVENT FOR SAVE BUTTON
            $(document).on('click','#CPVD_btn_send',function(){
                $(".preloader").show();
                var formElement = document.getElementById("CPVD_form_cmpnypropverfictn");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var msg_alert=xmlhttp.responseText;
                        if(msg_alert==1)
                        {
                            $(".preloader").hide();
                            show_msgbox("COMPANY PROPERTY VERIFICATION",err_msg_array[0],"success",false);
                            CPVD_rset()
                            showTable()
                        }
                        else
                        {
                            $(".preloader").hide();
                            show_msgbox("COMPANY PROPERTY VERIFICATION",err_msg_array[1],"success",false);
                        }
                    }
                }
                var choice="CMPNY_PROPETIES_SAVE"
                xmlhttp.open("POST","ADMIN/EMPLOYEE/DB_EMPLOYEE_COMPANY_PROPERTY_VERIFICATION.do?option="+choice,true);
                xmlhttp.send(new FormData(formElement));
            });
            //CLICK EVENT FUCNTION FOR RESET
            $('#CPVD_btn_reset').click(function()
            {
                CPVD_rset()
            });
            //CLEAR ALL FIELDS
            function CPVD_rset()
            {
                $('#CPVD_lb_loginid').val('SELECT');
                $('#CPVD_lbl_reason').hide();
                $('#CPVD_ta_reason').hide();
                $('#CPVD_lbl_laptopno').hide();
                $('#CPVD_tb_laptopno').hide();
                $('#CPVD_lbl_chargerno').hide();
                $('#CPVD_tb_chargerno').hide();
                $('#CPVD_lbl_chckdby').hide();
                $('#CPVD_lb_chckdby').hide();
                $('#CPVD_lb_chckdby').val('SELECT');
                $('#CPVD_btns_sendreset').hide();
                $('#CPVD_ta_reason').prop("size","20");
                $('#CPVD_ta_reason').css('height', 150);
            }
//READY FUNCTION END
        });
        <!--SCRIPT TAG END-->
    </script>
    <!--BODY TAG START-->
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="title text-center"><h4><b>COMPANY PROPERTY VERIFICATION</b></h4></div>
    <form   id="CPVD_form_cmpnypropverfictn" class="form-horizontal content" role="form">
        <div class="panel-body">
            <fieldset>
                <div class="row-fluid form-group">
                    <label name="CPVD_lbl_loginid" class="col-sm-3" id="CPVD_lbl_loginid" hidden>EMPLOYEE NAME<em>*</em></label>
                    <div class="col-sm-5">
                        <select name="CPVD_lb_loginid" id="CPVD_lb_loginid" class="form-control" hidden>
                            <option>SELECT</option>
                        </select>
                    </div></div>

                <div><label id="CPVD_lbl_nologinid" name="CPVD_lbl_nologinid" class="errormsg"></label></div>
                <div><label id="CPVD_lbl_nolaptop" name="CPVD_lbl_nolaptop" class="errormsg"></label></div>

                <div class="row-fluid form-group">
                    <label name="CPVD_lbl_laptopno"  class="col-sm-3" id="CPVD_lbl_laptopno" hidden>LAPTOP NUMBER</label>
                    <div class="col-sm-5">
                        <input type="text" name="CPVD_tb_laptopno" id="CPVD_tb_laptopno" hidden maxlength='25' class="alphanumeric sizefix form-control" style="display: none" readonly>
                    </div></div>

                <div class="row-fluid form-group">
                    <label  name="CPVD_lbl_chargerno" class="col-sm-3" id="CPVD_lbl_chargerno" hidden>CHARGER NUMBER</label>
                    <div class="col-sm-5">
                        <input type="text" name="CPVD_tb_chargerno" id="CPVD_tb_chargerno" maxlength='25' class="alphanumeric sizefix form-control" style="display:none" hidden readonly>
                    </div></div>

                <div class="row-fluid form-group">
                    <label name="CPVD_lbl_chckdby" class="col-sm-3" id="CPVD_lbl_chckdby" hidden>CHECKED BY<em>*</em></label>
                    <div class="col-sm-5">
                        <select name="CPVD_lb_chckdby" id="CPVD_lb_chckdby" class="form-control"  style="display:none">
                            <option>SELECT</option>
                        </select>
                    </div></div>

                <div class="row-fluid form-group">
                    <label name="CPVD_lbl_reason" class="col-sm-3"  id="CPVD_lbl_reason" hidden>COMMENTS<em>*</em></label>
                    <div class="col-sm-5">
               <textarea rows="4" cols="50" name="CPVD_ta_reason" id="CPVD_ta_reason"  class="form-control tarea" hidden>
                    </textarea>
                    </div></div>

                <div id="CPVD_btns_sendreset"   hidden>
                    <div>
                        <td width="150px" align="right"><input class="btn" type="button"  id="CPVD_btn_send" name="SAVE" value="SAVE" disabled hidden /></td>
                        <td align="left"><input type="button" class="btn" name="CPVD_btn_reset" id="CPVD_btn_reset" value="RESET"></td>
                    </div>
                </div>
            </fieldset>
        </div>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->