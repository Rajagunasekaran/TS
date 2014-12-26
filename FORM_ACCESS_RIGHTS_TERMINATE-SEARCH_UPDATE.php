<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************ACCESS_RIGHTS_TERMINATE_SEARCH_UPDATE*********************************************//
done by :safi
//VER 0.03 SD:28/11/14 ED:1/12/2014 TRACKER NO:74 DESC:MERGED LOGIN CREATION/UPDATION AND EMPLOYEE CREATION FORM
//DONE BY:shalini
//VER 0.01-INITIAL VERSION, SD:20/08/2014 ED:11/09/2014,TRACKER NO:81
//*********************************************************************************************************//-->



<?php
include "COMMON.php";
include "HEADER.php";
?>
<script>
$(document).ready(function(){
    $(".preloader").show();
    $('textarea').autogrow({onInitialize: true});
    var URT_SRC_terminate_array=[];
    var js_errormsg_array=[];
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $(".preloader").hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            URT_SRC_terminate_array=value_array[1];
            js_errormsg_array=value_array[0];
            var URSRC_emptype_array=value_array[2];
            var URT_SRC_radio_role='<tr><td><label class="srctitle" >SELECT ROLE ACCESS<em>*</em></label></td></tr>'
            for (var i=0;i<URT_SRC_terminate_array.length;i++) {
                var id="URT_SRC_tble_table"+i;
                var id1="URT_SRC_terminate_array"+i;
                var value=URT_SRC_terminate_array[i][1].replace(" ","_")
                URT_SRC_radio_role+='<tr ><td><input type="radio" name="URT_SRC_radio_nrole" id='+id1+' value='+value+' class="URT_SRC_radio_clsrole"  />' + URT_SRC_terminate_array[i][1] + '</td></tr>';
            }
            $('#URT_SRC_tble_roles').html(URT_SRC_radio_role);
            var emp_type='<option value="SELECT">SELECT</option>';

            for(var k=0;k<URSRC_emptype_array.length;k++){
                emp_type += '<option value="' + URSRC_emptype_array[k] + '">' + URSRC_emptype_array[k] + '</option>';
            }
            $('#URSRC_lb_selectemptype').html(emp_type);
        }
    }
    var choice='USER_RIGHTS_TERMINATE';
    xmlhttp.open("POST","COMMON.do?option="+choice,true);
    xmlhttp.send();
    $(document).on('click','#URT_SRC_btn_termination',function(){
        $('.preloader', window.parent.document).show();
        var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();

                var msg_alert=JSON.parse(xmlhttp.responseText);
                var success_flag=msg_alert[0];
                var ss_flag=msg_alert[1];
                var cal_flag=msg_alert[2];
                if((success_flag==1)&&(ss_flag==1)&&(cal_flag==1)){
                    var loggin=$("#URT_SRC_lb_loginterminate").val();
                    var msg=js_errormsg_array[1].toString().replace("[LOGIN ID]",loggin);
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg}});
                    $("#URT_SRC_lbl_datepickertermination").hide();
                    $("#URT_SRC_lb_loginterminate").hide();
                    $("#URT_SRC_lbl_loginterminate").hide();
                    $("#URT_SRC_tb_datepickertermination").hide();
                    $("#URT_SRC_lbl_reasontermination").hide();
                    $("#URT_SRC_ta_reasontermination").hide();
                    $("#URT_SRC_btn_termination").hide();
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                }
                if(success_flag==0){

                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[6]}});
                    $("#URT_SRC_lbl_datepickertermination").hide();
                    $("#URT_SRC_lb_loginterminate").hide();
                    $("#URT_SRC_lbl_loginterminate").hide();
                    $("#URT_SRC_tb_datepickertermination").hide();
                    $("#URT_SRC_lbl_reasontermination").hide();
                    $("#URT_SRC_ta_reasontermination").hide();
                    $("#URT_SRC_btn_termination").hide();
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);


                }

                if((success_flag==1)&&(ss_flag==0)){

                    var fileid=msg_alert[3];
                    var msg= js_errormsg_array[7].replace("[SSID]",fileid)
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg}});
                    $("#URT_SRC_lbl_datepickertermination").hide();
                    $("#URT_SRC_lb_loginterminate").hide();
                    $("#URT_SRC_lbl_loginterminate").hide();
                    $("#URT_SRC_tb_datepickertermination").hide();
                    $("#URT_SRC_lbl_reasontermination").hide();
                    $("#URT_SRC_ta_reasontermination").hide();
                    $("#URT_SRC_btn_termination").hide();
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);




                }


                if((success_flag==1)&&(ss_flag==1)&&(cal_flag==0)){

                    var msg= js_errormsg_array[8];
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg}});
                    $("#URT_SRC_lbl_datepickertermination").hide();
                    $("#URT_SRC_lb_loginterminate").hide();
                    $("#URT_SRC_lbl_loginterminate").hide();
                    $("#URT_SRC_tb_datepickertermination").hide();
                    $("#URT_SRC_lbl_reasontermination").hide();
                    $("#URT_SRC_ta_reasontermination").hide();
                    $("#URT_SRC_btn_termination").hide();
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);




                }

            }
        }
        var choice='TERMINATE';
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?option="+choice,true);
        xmlhttp.send(new FormData(formElement));
    });
    $(document).on('click','#URT_SRC_btn_rejoin',function(){
        $('.preloader', window.parent.document).show();
        var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
//                $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:xmlhttp.responseText}});
//                alert(xmlhttp.responseText);
                var msg_alert=JSON.parse(xmlhttp.responseText);
//                alert(msg_alert);
                var success_flag=msg_alert[0];
                var ss_flag=msg_alert[1];
                var cal_flag=msg_alert[2];
                if((success_flag==1)&&(ss_flag==1)&&(cal_flag==1)){
                    var loggin=$("#URT_SRC_lb_loginrejoin").val();
                    var msg=js_errormsg_array[2].toString().replace("[LOGIN ID]",loggin);
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:msg}});
                    $("#URT_SRC_lbl_datepickerrejoin").hide();
                    $("#URT_SRC_lbl_loginrejoin").show();
                    $("#URT_SRC_tble_roles").hide();
                    $("#URT_SRC_lb_loginupdate").hide();
                    $("#URT_SRC_tb_datepickerrejoin").hide();
                    $("#URT_SRC_btn_rejoin").hide();
                    $('#URT_SRC_lbl_loginupdate').hide();
                    $('#URT_SRC_lbl_datepickerupdate').hide();
                    $('#URT_SRC_tb_datepickerupdate').hide();
                    $('#URT_SRC_lbl_reasonupdate').hide();
                    $('#URT_SRC_ta_reasonupdate').hide();
                    $('#URT_SRC_btn_update').hide();
                    $('#URT_SRC_radio_selectrejoin').hide();
                    $('#URT_SRC_radio_selectsearchupdate').hide()
                    $('#URT_SRC_lb_loginrejoin').hide();
                    $('#URT_SRC_lbl_loginrejoin').hide();
                    $('#URT_SRC_lbl_selectsearchupdate').hide();
                    $('#URT_SRC_lbl_selectrejoin').hide();
                    $('#URT_SRC_lbl_selectoption').hide();
                    $("#URSRC_lbl_emptype").hide();
                    $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                    $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
                }

                if(success_flag==0){

                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:js_errormsg_array[6]}});
                    $("#URT_SRC_lbl_datepickerrejoin").hide();
                    $("#URT_SRC_lbl_loginrejoin").show();
                    $("#URT_SRC_tble_roles").hide();
                    $("#URT_SRC_lb_loginupdate").hide();
                    $("#URT_SRC_tb_datepickerrejoin").hide();
                    $("#URT_SRC_btn_rejoin").hide();
                    $('#URT_SRC_lbl_loginupdate').hide();
                    $('#URT_SRC_lbl_datepickerupdate').hide();
                    $('#URT_SRC_tb_datepickerupdate').hide();
                    $('#URT_SRC_lbl_reasonupdate').hide();
                    $('#URT_SRC_ta_reasonupdate').hide();
                    $('#URT_SRC_btn_update').hide();
                    $('#URT_SRC_radio_selectrejoin').hide();
                    $('#URT_SRC_radio_selectsearchupdate').hide()
                    $('#URT_SRC_lb_loginrejoin').hide();
                    $('#URT_SRC_lbl_loginrejoin').hide();
                    $('#URT_SRC_lbl_selectsearchupdate').hide();
                    $('#URT_SRC_lbl_selectrejoin').hide();
                    $('#URT_SRC_lbl_selectoption').hide();
                    $("#URSRC_lbl_emptype").hide();
                    $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                    $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);

                }
                if((success_flag==1)&&(ss_flag==0)){

                    var fileid=msg_alert[3];
                    var msg= js_errormsg_array[7].replace("[SSID]",fileid)
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:msg}});
                    $("#URT_SRC_lbl_datepickerrejoin").hide();
                    $("#URT_SRC_lbl_loginrejoin").show();
                    $("#URT_SRC_tble_roles").hide();
                    $("#URT_SRC_lb_loginupdate").hide();
                    $("#URT_SRC_tb_datepickerrejoin").hide();
                    $("#URT_SRC_btn_rejoin").hide();
                    $('#URT_SRC_lbl_loginupdate').hide();
                    $('#URT_SRC_lbl_datepickerupdate').hide();
                    $('#URT_SRC_tb_datepickerupdate').hide();
                    $('#URT_SRC_lbl_reasonupdate').hide();
                    $('#URT_SRC_ta_reasonupdate').hide();
                    $('#URT_SRC_btn_update').hide();
                    $('#URT_SRC_radio_selectrejoin').hide();
                    $('#URT_SRC_radio_selectsearchupdate').hide()
                    $('#URT_SRC_lb_loginrejoin').hide();
                    $('#URT_SRC_lbl_loginrejoin').hide();
                    $('#URT_SRC_lbl_selectsearchupdate').hide();
                    $('#URT_SRC_lbl_selectrejoin').hide();
                    $('#URT_SRC_lbl_selectoption').hide();
                    $("#URSRC_lbl_emptype").hide();
                    $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                    $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
                }

                if((success_flag==1)&&(ss_flag==1)&&(cal_flag==0)){
                    var msg= js_errormsg_array[8];
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:msg}});
                    $("#URT_SRC_lbl_datepickerrejoin").hide();
                    $("#URT_SRC_lbl_loginrejoin").show();
                    $("#URT_SRC_tble_roles").hide();
                    $("#URT_SRC_lb_loginupdate").hide();
                    $("#URT_SRC_tb_datepickerrejoin").hide();
                    $("#URT_SRC_btn_rejoin").hide();
                    $('#URT_SRC_lbl_loginupdate').hide();
                    $('#URT_SRC_lbl_datepickerupdate').hide();
                    $('#URT_SRC_tb_datepickerupdate').hide();
                    $('#URT_SRC_lbl_reasonupdate').hide();
                    $('#URT_SRC_ta_reasonupdate').hide();
                    $('#URT_SRC_btn_update').hide();
                    $('#URT_SRC_radio_selectrejoin').hide();
                    $('#URT_SRC_radio_selectsearchupdate').hide()
                    $('#URT_SRC_lb_loginrejoin').hide();
                    $('#URT_SRC_lbl_loginrejoin').hide();
                    $('#URT_SRC_lbl_selectsearchupdate').hide();
                    $('#URT_SRC_lbl_selectrejoin').hide();
                    $('#URT_SRC_lbl_selectoption').hide();
                    $("#URSRC_lbl_emptype").hide();
                    $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);;
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                    $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
                }

            }
        }
        var option='REJOIN';
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?option="+option,true);
        xmlhttp.send(new FormData(formElement));
    });


    $(document).on('click','#URT_SRC_btn_update',function(){
        $('.preloader', window.parent.document).show();
        var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                var msg_alert=xmlhttp.responseText;
                if(msg_alert==1){
                    var loggin=$("#URT_SRC_lb_loginupdate").val();
                    var msg=js_errormsg_array[0].toString().replace("[LOGIN ID]",loggin);
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg}});
                }
                $('#URT_SRC_lbl_loginrejoin').hide();
                $('#URT_SRC_lb_loginrejoin').hide();
                $('#URT_SRC_lbl_loginterminate').hide();
                $('#URT_SRC_lb_loginterminate').hide();
                $("#URT_SRC_tble_roles").hide();
                $("#URT_SRC_tb_datepickerrejoin").hide();
                $("#URT_SRC_lbl_datepickerrejoin").hide();
                $("#URT_SRC_btn_rejoin").hide();
                $("#URT_SRC_lbl_loginupdate").show();
                $('#URT_SRC_lbl_datepickerupdate').hide();
                $('#URT_SRC_tb_datepickerupdate').hide();
                $('#URT_SRC_lbl_reasonupdate').hide();
                $('#URT_SRC_ta_reasonupdate').hide();
                $('#URT_SRC_btn_update').hide();
                $('#URT_SRC_lbl_loginupdate').hide();
                $('#URT_SRC_lb_loginupdate').hide();
                $('#URT_SRC_lbl_selectsearchupdate').hide();
                $('#URT_SRC_lbl_selectrejoin').hide();
                $('#URT_SRC_radio_selectsearchupdate').hide();
                $('#URT_SRC_radio_selectrejoin').hide();
                $('#URT_SRC_lbl_selectoption').hide();
                $('#URT_SRC_lb_recordversion').hide();
                $('#URT_SRC_lbl_recordversion').hide();
                $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
            }
        }
        var choice='UPDATE';
        xmlhttp.open("POST","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?option="+choice,true);
        xmlhttp.send(new FormData(formElement));
    });
    $('#URT_SRC_lb_loginupdate').change(function(){
        $('.preloader', window.parent.document).show();
        var URT_SRC_loggin=$(this).val();

        var recver_array=[];
        if(URT_SRC_loggin !=""){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
                    var values_array=JSON.parse(xmlhttp.responseText);
//                    alert(JSON.stringify(values_array.recver));
                    var rec_ver='<option value="SELECT">SELECT</option>';
                    recver_array=(values_array.recver);
                    for(var k=0;k<recver_array.length;k++){
                        rec_ver += '<option value="' + recver_array[k] + '">' + recver_array[k] + '</option>';
                    }
                    $('#URT_SRC_lb_recordversion').html(rec_ver);
                    var recver=(values_array.recver).length;
                    if(recver==1){
                        var min_date=values_array.enddate;
                        var mindate=min_date.toString().split('-');
                        var month=mindate[1]-1;
                        var year=mindate[2];
                        var date=parseInt(mindate[0])+1;
                        var minimumdate = new Date(year,month,date);
                        $('#URT_SRC_tb_datepickerupdate').val(values_array.enddate);
                        $('#URT_SRC_ta_reasonupdate').val(values_array.reasonn);
                        $('#URT_SRC_lb_recordversion').val(values_array.recver);
                        $('#URT_SRC_tb_datepickerupdate').datepicker("option","minDate",minimumdate);
                        var mindate=min_date.toString().split('-');
                        var month=parseInt(mindate[1]-1)+1;//mindate[1]-1;
                        var year=mindate[2];
                        var date=parseInt(mindate[0])+1;
                        var minimumdate = new Date(year,month,date);
                        $('#URT_SRC_tb_datepickerupdate').datepicker("option","maxDate",new Date());
                    }
                    else{

                        $('#URT_SRC_tb_datepickerupdate').hide();
                        $('#URT_SRC_ta_reasonupdate').hide();
                        $('#URT_SRC_lbl_datepickerupdate').hide();
                        $('#URT_SRC_lbl_reasonupdate').hide();
                        $('#URT_SRC_btn_update').hide();
                        $('#URT_SRC_lb_recordversion').show();

                        $('#URT_SRC_lbl_recordversion').show();

                    }

                }
            }
        }
        var option='FETCH';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
        xmlhttp.send();
    });

    $('#URT_SRC_lb_recordversion').change(function(){


        var recver= $('#URT_SRC_lb_recordversion').val();
        var URT_SRC_loggin=$('#URT_SRC_lb_loginupdate').val();
        if(recver!='SELECT'){

            $('.preloader', window.parent.document).show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
                    var values_array=JSON.parse(xmlhttp.responseText);
                    var min_date=values_array.enddate;
                    var mindate=min_date.toString().split('-');
                    var month=mindate[1]-1;
                    var year=mindate[2];
                    var date=parseInt(mindate[0]);
                    var minimumdate = new Date(year,month,date);
                    $('#URT_SRC_tb_datepickerupdate').val(values_array.enddate);
                    $('#URT_SRC_ta_reasonupdate').val(values_array.reasonn);
                    $('#URT_SRC_tb_datepickerupdate').datepicker("option","minDate",minimumdate);
                    $('#URT_SRC_tb_datepickerupdate').datepicker("option","maxDate",new Date());
                    $('#URT_SRC_tb_datepickerupdate').show();
                    $('#URT_SRC_ta_reasonupdate').show();
                    $('#URT_SRC_lbl_datepickerupdate').show();
                    $('#URT_SRC_lbl_reasonupdate').show();
                    $('#URT_SRC_btn_update').show();
                    $('#URT_SRC_lb_recordversion').show();
                    $('#URT_SRC_lbl_recordversion').show();
                }

            }

            var option='FETCH DATA';
            xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option+"&recver="+recver,true);
            xmlhttp.send();
        }
        else{
            $('#URT_SRC_tb_datepickerupdate').hide();
            $('#URT_SRC_ta_reasonupdate').hide();
            $('#URT_SRC_lbl_datepickerupdate').hide();
            $('#URT_SRC_lbl_reasonupdate').hide();
            $('#URT_SRC_btn_update').hide();
        }

    });

    $('#URT_SRC_lb_loginterminate').change(function(){
        $('#URT_SRC_errdate').hide();
        $('.preloader', window.parent.document).show();
        var URT_SRC_loggin=$(this).val();
        if(URT_SRC_loggin !=""){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
                    var min_date=(xmlhttp.responseText);
                    var mindate=min_date.toString().split('-');
                    var month=mindate[1]-1;
                    var year=mindate[2];
                    var date=parseInt(mindate[0])+1;
                    var minimumdate = new Date(year,month,date);
                    $('#URT_SRC_tb_datepickertermination').datepicker("option","minDate",minimumdate);
                    $(".URT_SRC_tb_termindatepickerclass").datepicker("option","maxDate",new Date())
                }
            }
        }
        var option='GETDATE';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
        xmlhttp.send();
    });
    var err_flag=0;
    $('#URT_SRC_tb_datepickertermination').change(function(){

        $('.preloader', window.parent.document).show();
        var URT_SRC_loggin=$('#URT_SRC_lb_loginterminate').val();
        var date_value=$('#URT_SRC_tb_datepickertermination').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {

            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();

                var final_value=xmlhttp.responseText;
                if(final_value!=''){
                    err_flag=1;
                    var msg=js_errormsg_array[9].replace('[LOGIN ID]',URT_SRC_loggin);
                    msg=msg.replace('[DATE]',final_value);
                    $('#URT_SRC_errdate').text(msg).show();
                    $('#URT_SRC_btn_termination').attr('disabled','disabled');
                }
                else{
                    err_flag=0;
                    $('#URT_SRC_errdate').hide();
//                    $('#URT_SRC_btn_termination').removeAttribute('disabled');
                    URT_SRC_validation();
                }

            }



        }
        var option='GET_VALUE';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option+"&date_value="+date_value,true);
        xmlhttp.send();

    });


    $('#URT_SRC_lb_loginrejoin').change(function(){
        $('.preloader', window.parent.document).show();
        var URT_SRC_loggin=$(this).val();
        if(URT_SRC_loggin !=""){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
                    var min_date=(xmlhttp.responseText);
                    var mindate=min_date.toString().split('-');
                    var month=mindate[1]-1;
                    var year=mindate[2];
                    var date=parseInt(mindate[0])+1;
                    var minimumdate = new Date(year,month,date);
                    $('#URT_SRC_tb_datepickerrejoin').datepicker("option","minDate",minimumdate);

                    $('#URT_SRC_tb_datepickerrejoin').datepicker("option","maxDate",new Date());
                }
            }
        }
        var option='GETENDDATE';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
        xmlhttp.send();
    });
    $('#URT_SRC_radio_logintermination').click(function(){
        err_flag=0;
        $('.preloader', window.parent.document).show();
        var radio_value_loginidsearch=$(this).val();
        $('#URT_SRC_lb_recordversion').hide();
        $('#URT_SRC_lbl_recordversion').hide();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                var loginid_array=JSON.parse(xmlhttp.responseText);
                if(loginid_array.length!=0){
                    var URT_SRC_loginid_options='<option>SELECT</option>'
                    for(var l=0;l<loginid_array.length;l++){
                        URT_SRC_loginid_options+= '<option value="' + loginid_array[l] + '">' + loginid_array[l]+ '</option>';
                    }
                    $('#URT_SRC_lb_loginterminate').html(URT_SRC_loginid_options);
                    $('#URT_SRC_lb_loginterminate').show().prop('selectedIndex',0);

                }
                else
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[3]}});
                }

            }
        }
        var option='TERMINATIONLB';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
        xmlhttp.send();

    });

    $('#URT_SRC_radio_selectrejoin').click(function(){
        $('.preloader', window.parent.document).show();
        var radio_value_loginidsearch=$(this).val();
        $('#URT_SRC_lb_recordversion').hide();
        $('#URT_SRC_lbl_recordversion').hide();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                var loginid_array=JSON.parse(xmlhttp.responseText);
                if(loginid_array.length!=0){
                    var URT_SRC_loginid_options='<option>SELECT</option>'
                    for(var l=0;l<loginid_array.length;l++){
                        URT_SRC_loginid_options+= '<option value="' + loginid_array[l] + '">' + loginid_array[l]+ '</option>';
                    }
                    $('#URT_SRC_lb_loginrejoin').html(URT_SRC_loginid_options);
                    $('#URT_SRC_lb_loginrejoin').show().prop('selectedIndex',0);

                }
                else
                {
                    $('#URT_SRC_lbl_loginrejoin').hide();
                    $('#URT_SRC_lb_loginrejoin').hide();
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[4]}});
                }
            }
        }
        var option='REJOINLB';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
        xmlhttp.send();
    });
    $('#URT_SRC_radio_selectsearchupdate').click(function(){
        $('.preloader', window.parent.document).show();
        var radio_value_loginidsearch=$(this).val();
        $('#URT_SRC_lb_recordversion').hide();
        $('#URT_SRC_lbl_recordversion').hide();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                var loginid_array=JSON.parse(xmlhttp.responseText);
                if(loginid_array.length!=0){
                    var URT_SRC_loginid_options='<option>SELECT</option>'
                    for(var l=0;l<loginid_array.length;l++){
                        URT_SRC_loginid_options+= '<option value="' + loginid_array[l] + '">' + loginid_array[l]+ '</option>';
                    }
                    $('#URT_SRC_lb_loginupdate').html(URT_SRC_loginid_options);
                    $('#URT_SRC_lb_loginupdate').show().prop('selectedIndex',0);

                }
                else
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[5]}});

                    $('#URT_SRC_lbl_loginupdate').hide();
                    $('#URT_SRC_lb_loginupdate').hide();
                }
            }
        }
        var option='SEARCHLB';
        xmlhttp.open("GET","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
        xmlhttp.send();

    });
    $("#URT_SRC_btn_rejoin").hide();
    $("#URT_SRC_btn_termination").hide();
    $("#URT_SRC_btn_update").hide();
    $("#URT_SRC_tble_roles").hide();
    $("#URT_SRC_lbl_logintermination").show();

    $("#URT_SRC_lbl_loginsearchupdate").show();

    $('#URT_SRC_radio_logintermination').change(function(){
        $("#URT_SRC_lb_loginupdate").hide();
        $("#URT_SRC_lbl_loginterminate").show();
        $("#URT_SRC_tble_roles").hide();
        $("#URT_SRC_tb_datepickerrejoin").hide();
        $("#URT_SRC_lbl_datepickerrejoin").hide();
        $("#URT_SRC_btn_rejoin").hide();
        $("#URT_SRC_lbl_loginterminate").val("SELECT");
        $("#URT_SRC_lb_loginterminate").show();
        $("#URT_SRC_lbl_selectoption").hide();
        $("#URSRC_lbl_emptype").hide();
        $('#URSRC_lb_selectemptype').hide();
        $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
        $("#URT_SRC_radio_selectrejoin").hide();
        $("#URT_SRC_lbl_selectrejoin").hide();
        $("#URT_SRC_radio_selectsearchupdate").hide();
        $("#URT_SRC_lbl_selectsearchupdate").hide();
        $("#URT_SRC_lbl_loginrejoin").hide();
        $("#URT_SRC_lb_loginrejoin").hide();
        $('#URT_SRC_lbl_loginupdate').hide();
        $('#URT_SRC_lbl_datepickerupdate').hide();
        $('#URT_SRC_tb_datepickerupdate').hide();
        $('#URT_SRC_lbl_reasonupdate').hide();
        $('#URT_SRC_ta_reasonupdate').hide();
        $('#URT_SRC_btn_update').hide();
    });

    $('#URT_SRC_lb_loginterminate').change(function(){
        var loginid=$('#URT_SRC_lb_loginterminate').val();
        if(loginid=='SELECT')
        {
            $("#URT_SRC_lbl_datepickertermination").hide();
            $("#URT_SRC_tb_datepickertermination").hide();
            $("#URT_SRC_lbl_reasontermination").hide();
            $("#URT_SRC_ta_reasontermination").hide();
            $("#URT_SRC_btn_termination").hide();
        }
        else
        {
            $("#URT_SRC_lbl_datepickertermination").show();
            $("#URT_SRC_tb_datepickertermination").val('').show();
            $("#URT_SRC_lbl_reasontermination").show();
            $("#URT_SRC_ta_reasontermination").val('').show();
            $("#URT_SRC_btn_termination").show();
        }

    });

    $('#URT_SRC_lb_loginupdate').change(function(){

        var loginvalue=$('#URT_SRC_lb_loginupdate').val();
        if(loginvalue=='SELECT')
        {
            $('#URT_SRC_lbl_datepickerupdate').hide();
            $('#URT_SRC_tb_datepickerupdate').hide();
            $('#URT_SRC_lbl_reasonupdate').hide();
            $('#URT_SRC_ta_reasonupdate').hide();
            $('#URT_SRC_btn_update').hide();

        }
        else
        {
            $('#URT_SRC_lbl_datepickerupdate').show();
            $('#URT_SRC_tb_datepickerupdate').val('').show();
            $('#URT_SRC_lbl_reasonupdate').show();
            $('#URT_SRC_ta_reasonupdate').val('').show();
            $('#URT_SRC_btn_update').show();
        }
    });
    $('#URT_SRC_lb_loginrejoin').change(function(){

        var loginidvalue=$('#URT_SRC_lb_loginrejoin').val();
        if(loginidvalue=='SELECT')
        {
            $("#URT_SRC_tble_roles").hide();
            $("#URT_SRC_tb_datepickerrejoin").hide();
            $("#URT_SRC_btn_rejoin").hide();
            $("#URT_SRC_lbl_datepickerrejoin").hide();
            $("#URSRC_lbl_emptype").hide();
            $('#URSRC_lb_selectemptype').hide();
            $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
        }
        else
        {
            $("#URT_SRC_tble_roles").show();
            $("#URT_SRC_tb_datepickerrejoin").val('').show();
            $("#URT_SRC_lbl_datepickerrejoin").show();
            $("#URT_SRC_btn_rejoin").show();
            $("#URSRC_lbl_emptype").show();
            $('#URSRC_lb_selectemptype').show();


        }
    });


    $('#URT_SRC_radio_loginsearchupdate').change(function(){
        err_flag=0;
        $("#URT_SRC_lbl_selectoption").show();
        $('#URT_SRC_errdate').hide();
        $("#URT_SRC_radio_selectrejoin").show();
        $("#URT_SRC_lbl_selectrejoin").show();
        $("#URT_SRC_lbl_selectsearchupdate").show();
        $("#URT_SRC_radio_selectsearchupdate").show();
        $("#URT_SRC_lbl_loginterminate").hide();
        $("#URSRC_lbl_emptype").hide();
        $('#URT_SRC_lb_recordversion').hide();
        $('#URT_SRC_lbl_recordversion').hide();
        $('#URSRC_lb_selectemptype').hide();
        $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
        $("#URT_SRC_lb_loginterminate").hide();
        $("#URT_SRC_lbl_datepickertermination").hide();
        $("#URT_SRC_tb_datepickertermination").hide();
        $("#URT_SRC_lbl_reasontermination").hide();
        $("#URT_SRC_ta_reasontermination").hide();
        $("#URT_SRC_btn_termination").hide();
        $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
    });

    $('#URT_SRC_radio_selectsearchupdate').change(function(){
        $('#URT_SRC_errdate').hide();
        $('#URT_SRC_lbl_loginrejoin').hide();
        $('#URT_SRC_lb_loginrejoin').hide();
        $('#URT_SRC_lbl_loginterminate').hide();
        $('#URT_SRC_lb_loginterminate').hide();
        $("#URT_SRC_tble_roles").hide();
        $("#URSRC_lbl_emptype").hide();
        $('#URSRC_lb_selectemptype').hide();
        $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
        $("#URT_SRC_tb_datepickerrejoin").hide();
        $("#URT_SRC_lbl_datepickerrejoin").hide();
        $("#URT_SRC_btn_rejoin").hide();
        $("#URT_SRC_lbl_loginupdate").show();
        $('#URT_SRC_lbl_datepickerupdate').hide();
        $('#URT_SRC_tb_datepickerupdate').hide();
        $('#URT_SRC_lbl_reasonupdate').hide();
        $('#URT_SRC_ta_reasonupdate').hide();
        $('#URT_SRC_btn_update').hide();
        $('#URT_SRC_lb_loginupdate').show();
    });

    $('#URT_SRC_radio_selectrejoin').change(function(){
        $("#URT_SRC_lbl_datepickerrejoin").hide();
        $("#URT_SRC_lbl_loginrejoin").show();
        $("#URT_SRC_tble_roles").hide();
        $("#URT_SRC_lb_loginupdate").hide();
        $("#URT_SRC_tb_datepickerrejoin").hide();
        $("#URT_SRC_btn_rejoin").hide();
        $('#URT_SRC_lbl_loginupdate').hide();
        $('#URT_SRC_lbl_datepickerupdate').hide();
        $('#URT_SRC_tb_datepickerupdate').hide();
        $('#URT_SRC_lbl_reasonupdate').hide();
        $('#URT_SRC_ta_reasonupdate').hide();
        $('#URT_SRC_btn_update').hide();
        $("input[name=URT_SRC_radio_nrole]:checked").attr('checked',false);

    });
//DATE PICKER FUNCTION
    $('.URT_SRC_tb_termindatepickerclass').datepicker({
        dateFormat:"dd-mm-yy",
//        maxDate:new Date(),
        changeYear: true,
        changeMonth: true

    });
    $('.URT_SRC_tb_rejoinndsearchdatepicker').datepicker({
        dateFormat:"dd-mm-yy",
//        maxDate:new Date(),
        changeYear: true,
        changeMonth: true

    });

    $(document).on('change','#URT_SRC_form_terminatesearchupdate',function(){


        URT_SRC_validation();

    });
    function URT_SRC_validation(){
        var Selectedradiooption = $("input[name='URT_SRC_radio_nterminndupdatesearch']:checked").val();

        if(Selectedradiooption=='URT_SRC_radio_valuelogintermination')
        {
            if(($('#URT_SRC_lb_loginterminate').val()!='SELECT') && ($("#URT_SRC_tb_datepickertermination").val()!="") && (($("#URT_SRC_ta_reasontermination").val()).trim()!="")&& (err_flag==0))
            {
                $("#URT_SRC_btn_termination").removeAttr("disabled");
            }
            else
            {

                $("#URT_SRC_btn_termination").attr("disabled", "disabled");
            }
        }
        if(Selectedradiooption=='URT_SRC_radio_valueloginsearchupdate')
        {
            var Selectedsearchradiooption = $("input[name='URT_SRC_radio_nselectoption']:checked").val();
            if(Selectedsearchradiooption=='URT_SRC_radio_valuerejoin')
            {

                if(($("#URT_SRC_lbl_loginupdate").val()!='SELECT') &&($('#URSRC_lb_selectemptype').val()!='SELECT') && ($("#URT_SRC_tb_datepickerrejoin").val()!="")&& ($("input[name=URT_SRC_radio_nrole]").is(":checked")==true) )
                {

                    $("#URT_SRC_btn_rejoin").removeAttr("disabled");
                }
                else
                {
                    $("#URT_SRC_btn_rejoin").attr("disabled", "disabled");
                }
            }
            else
            {
                if(($('#URT_SRC_lb_loginupdate').val()!='SELECT') && ($("#URT_SRC_tb_datepickerupdate").val()!='') && (($("#URT_SRC_ta_reasonupdate").val()).trim()) && ($('#URT_SRC_lb_recordversion').val()!='SELECT'))
                {
                    $("#URT_SRC_btn_update").removeAttr("disabled");
                }
                else
                {
                    $("#URT_SRC_btn_update").attr("disabled", "disabled");
                }

            }
        }
    }
});
</script>
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>ACCESS RIGHTS:TERMINATE SEARCH/UPDATE</h3><p></div></div>
    <form id="URT_SRC_form_terminatesearchupdate"  class ='content'>
        <table>

            <tr> <td> <input type="radio" name="URT_SRC_radio_nterminndupdatesearch" id="URT_SRC_radio_logintermination"  value="URT_SRC_radio_valuelogintermination" ><label name="URT_SRC_lbl_nlogintermination" id="URT_SRC_lbl_logintermination" hidden>LOGIN TERMINATION</label></td> </tr>
            <tr> <td> <input type="radio" name="URT_SRC_radio_nterminndupdatesearch" id="URT_SRC_radio_loginsearchupdate" value="URT_SRC_radio_valueloginsearchupdate" ><label name="URT_SRC_lbl_nloginsearchupdate" id="URT_SRC_lbl_loginsearchupdate"  hidden>SEARCH/UPDATE</label></td></tr>
            <!--URT_SRC_radio_nterminndupdatesearch termination-->
            <tr> <td> <label name="URT_SRC_lbl_nloginterminate" id="URT_SRC_lbl_loginterminate" class="srctitle" hidden>LOGIN ID<em>*</em> </label></td></tr>
            <tr> <td> <select name="URT_SRC_lb_nloginterminate" id="URT_SRC_lb_loginterminate" hidden> <option>SELECT</option></select></td></tr>
            <tr><td><table>
                        <tr> <td> <label name="URT_SRC_lbl_datepickertermination" id="URT_SRC_lbl_datepickertermination" class="srctitle" hidden> SELECT A END DATE <em>*</em> </label> </td> </tr>
                        <tr> <td> <input type="text" name="URT_SRC_tb_ndatepickertermination" id="URT_SRC_tb_datepickertermination" class="URT_SRC_tb_termindatepickerclass datemandtry" style="width:75px;" hidden></td><td><label id="URT_SRC_errdate" name="URT_SRC_errdate" class="errormsg"></label></td></tr>
                    </table></td></tr>
            <tr> <td> <label name="URT_SRC_lbl_nreasontermination" id="URT_SRC_lbl_reasontermination" class="srctitle" hidden> REASON OF TERMINATION <em>*</em> </label> </td> </tr>
            <tr> <td> <textarea name="URT_SRC_ta_nreasontermination" id="URT_SRC_ta_reasontermination" hidden> </textarea> </td></td> </tr>
            <tr> <td> <input type="button"  value="TERMINATE" id="URT_SRC_btn_termination" class="maxbtn" hidden> </td></tr>
            <!--select an option-->
            <tr> <td> <label name="URT_SRC_lbl_nselectoption" id="URT_SRC_lbl_selectoption" class="srctitle" hidden> SELECT A OPTION </label></td></tr>
            <tr> <td> <input type="radio" name="URT_SRC_radio_nselectoption" id="URT_SRC_radio_selectrejoin"    value="URT_SRC_radio_valuerejoin" hidden> <label name="URT_SRC_lbl_nselectrejoin" id="URT_SRC_lbl_selectrejoin"  hidden> REJOIN </label></td></tr>
            <tr> <td> <input type="radio" name="URT_SRC_radio_nselectoption" id="URT_SRC_radio_selectsearchupdate" hidden><label name="URT_SRC_lbl_nselectsearchupdate" id="URT_SRC_lbl_selectsearchupdate"  hidden> SEARCH/UPDATE </label></td></tr>

            <!--terminate rejoin-->
            <tr> <td> <label name="URT_SRC_lbl_nloginrejoin" id="URT_SRC_lbl_loginrejoin" class="srctitle" hidden> LOGIN ID <em>*</em> </label> </td> </tr>
            <tr> <td> <select name="URT_SRC_lb_nloginrejoin" id="URT_SRC_lb_loginrejoin"  hidden > <option>SELECT</option> </select></td></tr>
            <tr>
                <td ><label id="URSRC_lbl_emptype" hidden>SELECT TYPE OF EMPLOYEE<em>*</em></label></td>
            </tr>
            <tr>
                <td><select id='URSRC_lb_selectemptype' name="URSRC_lb_selectemptype"  maxlength="40" hidden  >
                        <option value='SELECT' selected="selected"> SELECT</option>
                    </select></td></tr>

            <tr> <td> <table id="URT_SRC_tble_roles"> </table></td></tr>
            <tr> <td> <label name="URT_SRC_lbl_ndatepickerrejoin" id="URT_SRC_lbl_datepickerrejoin" class=" srctitle" hidden> SELECT A JOIN DATE <em>*</em> </label> </td> </tr>
            <tr> <td> <input type="text" name="URT_SRC_tb_ndatepickerrejoin" id="URT_SRC_tb_datepickerrejoin" class="URT_SRC_tb_rejoinndsearchdatepicker datemandtry" style="width:75px;" hidden></td></tr>
            <tr> <td> <input type="button" value="REJOIN" id="URT_SRC_btn_rejoin" class="btn"  hidden> </td></tr>

            <!--terminate updation-->
            <tr> <td> <label name="URT_SRC_lbl_nloginupdate" id="URT_SRC_lbl_loginupdate" class="srctitle " hidden>LOGIN ID<em>*</em> </label></td></tr>
            <tr> <td> <select name="URT_SRC_lb_nloginupdate" id="URT_SRC_lb_loginupdate" hidden> <option>SELECT</option></select>
                    <label id="URT_SRC_lbl_recordversion" class="srctitle" hidden >RECORD VERSION<em>*</em></label></td><td><select name="URT_SRC_lb_recordversion" id="URT_SRC_lb_recordversion" hidden ></select></td></tr>
            <tr> <td> <label name="URT_SRC_lbl_ndatepickerupdate" id="URT_SRC_lbl_datepickerupdate" class=" srctitle" hidden> SELECT A END DATE <em>*</em> </label> </td> </tr>
            <tr> <td> <input type="text" name="URT_SRC_tb_ndatepickerupdate" id="URT_SRC_tb_datepickerupdate" class="URT_SRC_tb_rejoinndsearchdatepicker datemandtry" style="width:75px;" hidden></td></tr>
            <tr> <td> <label name="URT_SRC_lbl_nreasonupdate" id="URT_SRC_lbl_reasonupdate" class=" srctitle"hidden> REASON OF TERMINATION <em>*</em> </label> </td> </tr>
            <tr> <td> <textarea name="URT_SRC_ta_nreasonupdate" id="URT_SRC_ta_reasonupdate" hidden> </textarea> </td> </tr>
            <tr> <td> <input type="button" value="UPDATE" id="URT_SRC_btn_update" class="btn" hidden> </td></tr>

        </table>
    </form>
</body>
</html>