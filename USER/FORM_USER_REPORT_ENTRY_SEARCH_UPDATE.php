<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************DAILY REPORTS USER REPORT ENTRY **************************************//
//DONE BY:ARTHI
//VER 0.08-SD:10/07/2015 ED:10/07/2015, ALIGN RADIO BUTTON,CHECK THE PDF ISSUE
//DONE BY:JAYAPRIYA
//VER 0.08-SD:11/06/2015 ED:11/06/2015, CHANGE THE FORM TO BE RESPONSIVE
//DONE BY:RAJA
//VER 0.07-SD:10/01/2015 ED:10/01/2015, TRACKER NO:74,DESC:CHANGED PRELOADER POSITION IMPLEMENTED AUTOFOCUS
//DONE BY:RAJA
//VER 0.06-SD:05/01/2015 ED:06/01/2015, TRACKER NO:175,179,DESC:CHANGED LOGIN ID AS EMPLOYEE NAME, SETTING PRELOADER POSITON, MSGBOX POSITION
//DONE BY:SASIKALA
//VER 0.05-SD:06/01/2015 ED:06/01/2015, TRACKER NO:74,DESC:ADDED GEOLOCATION FOR MULTIPLE ENTRY
//DONE BY:SASIKALA
//VER 0.04-SD:28/12/2014 ED:28/12/2014, TRACKER NO:74,DESC:ADDED GEOLOCATION AND CHECKOUT TIME VALIDATION
//DONE BY:LALITHA
//VER 0.03-SD:01/12/2014 ED:01/12/2014,TRACKER NO:74,Changed Preloder funct,Removed Confirmation fr err msgs
//DONE BY:SASIKALA
//VER 0.02 SD:17/10/2014 ED 18/10/2014,TRACKER NO:74,DESC:DID PERMISSION AS MANDATORY AND BUTTON VALIDATION
//VER 0.01-INITIAL VERSION, SD:08/08/2014 ED:01/10/2014,TRACKER NO:74
//*********************************************************************************************************//-->
<?php
include "../TSLIB/TSLIB_HEADER.php";
//include "../TSLIB/TSLIB_COMMON.php";
?>
<html>
<head>
    <script>
        var checkoutlocation;
        function displayLocation(latitude,longitude){
            var request = new XMLHttpRequest();
            var method = 'GET';
            var url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='+latitude+','+longitude+'&sensor=true';
            var async = true;

            request.open(method, url, async);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var data = JSON.parse(request.responseText);
                    var address = data.results[0];
                    checkoutlocation=address.formatted_address;
                }
            };
            request.send();
        };
        var successCallback = function(position){
            var x = position.coords.latitude;
            var y = position.coords.longitude;
            displayLocation(x,y);
        };

        var errorCallback = function(error){
            var errorMessage = 'Unknown error';
            switch(error.code) {
                case 1:
                    errorMessage = 'Permission denied';
                    break;
                case 2:
                    errorMessage = 'Position unavailable';
                    break;
                case 3:
                    errorMessage = 'Timeout';
                    break;
            }
            document.write(errorMessage);
        };

        var options = {
            enableHighAccuracy: true,
            timeout: 30000,
            maximumAge: 0
        };

        navigator.geolocation.getCurrentPosition(successCallback,errorCallback,options);
        //READY FUNCTION START
        $(document).ready(function(){
            $(".preloader").hide();
            // global variable
            var permission_array=[];
            var project_array=[];
            var min_date;
            var max_date;
            var search_max_date;
            var err_msg=[];
            var empname;
            var join_date;
            var mindatesplit;
            var maxdate;
            var month;
            var year;
            var date;
            var max_date;
            var datepicker_maxdate;
            var msgalert;
            var response;
            var msg_alert;
            var errmsgs;
            var msg;
            var sd;
            var USRC_UPD_table_header;
            var formElement;
            var project_list;
            var project_array;
            $(document).on('click','.rdclick',function(){
//    $('.rdclick').click(function(){
                $(".preloader").show();
                var radiooption=$(this).val();
                if(radiooption=='entry')
                {
                    UARD_clear();
                    $('#URE_lbl_report_entry').html('USER REPORT ENTRY');
                    $('#URE_rd_sinentry').prop('checked', false);
                    $('#URE_rd_mulentry').prop('checked', false);
                    $('#URE_lbl_dte').hide();
                    $('#URE_tb_date').val('').hide();
                    $('#entry').show();
                    $('#search_update').hide();
                    $('#URE_lbl_checkmsg').hide();
                    $('#USRC_UPD_lbl_report,#USRC_UPD_ta_report,#USRC_UPD_tble_enterthereport').empty();
                    $('#USRC_UPD_tb_strtdte').val('');
                    $('#USRC_UPD_tb_enddte').val('');
                    $('#USRC_UPD_div_header').hide();
                    $('#USRC_UPD_btn_pdf').hide();
                    $('#USRC_UPD_div_tablecontainer').hide();
                    $('#USRC_UPD_btn_srch').hide();
                    $('#USRC_UPD_lbl_dte').hide();
                    $('#USRC_UPD_tb_date').hide();
                    $('#USRC_UPD_tble_attendence').hide();
                    $('#USRC_UPD_tble_reasonlbltxtarea').hide();
                    $('#USRC_UPD_tble_projectlistbx').hide();
                    $('#USRC_UPD_tble_bandwidth').hide();
                    $('#URE_lbl_errmsg').hide();
                    $('#USRC_UPD_lbl_report').hide();
                    $('#USRC_UPD_ta_report').hide();
                    $('#USRC_UPD_btn_submit').hide();
                    $('#URE_tbl_multipleday').hide();
                    $('#URE_lbl_msg').hide();
                    $('#URE_btn_submit').hide();
                    $('#URE_btn_save').hide();
//            $("#URE_lb_attendance option[value='2']").detach();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            var value_array=JSON.parse(xmlhttp.responseText);
                            $("#URE_lb_attendance option[value='2']").detach();
                            permission_array=value_array[0];
                            project_array=value_array[1];
                            min_date=value_array[2];
                            err_msg=value_array[3];
                            var userstamp=value_array[4];
                            var wfh_flag=value_array[5];
//                    if(wfh_flag == 'X')
//                    {
//                        $('#URE_lb_attendance').append("<option value='2'>WORK FROM HOME</option>")
//                    }
                            if(wfh_flag == 'X')
                            {

                                $('#URE_lb_attendance').append("<option value='2'>WORK FROM HOME</option>")
                                $('#URE_lb_attendance').children('option[value="1"]').css('display','none');
                                $('#URE_lb_attendance').children('option[value="0"]').css('display','none');
                                $('#URE_lb_attendance').children('option[value="OD"]').css('display','none');
                            }
                            else
                            {

                                $('#URE_lb_attendance').children('option[value="1"]').show();
                                $('#URE_lb_attendance').children('option[value="0"]').show();
                                $('#URE_lb_attendance').children('option[value="OD"]').show();
                            }
                            if(project_array.length==0){
                                var msg=err_msg[10].replace('[LOGIN ID]',userstamp);
                                $('#URE_form_dailyuserentry').replaceWith('<p><label class="errormsg">'+ msg +'</label></p>');

                            }
                            else{
//                            $("#URE_tb_date").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
//                            $("#URE_ta_fromdate").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                                mindatesplit=min_date.split('-');
                                maxdate=new Date();
                                month=maxdate.getMonth()+1;
                                year=maxdate.getFullYear();
                                date=maxdate.getDate();
                                max_date = new Date(year,month,date);
                                datepicker_maxdate=new Date(Date.parse(max_date));
                                $('#URE_tb_date').datepicker("option","maxDate",datepicker_maxdate);
                                $('#URE_tb_date').datepicker("option","minDate",min_date);
                                $('#URE_ta_fromdate').datepicker("option","maxDate",datepicker_maxdate);
                                $('#URE_ta_fromdate').datepicker("option","minDate",min_date);
                            }
                        }
                    }
                    var chioce="user_report_entry";
                    xmlhttp.open("GET","TSLIB/TSLIB_COMMON.do?option="+chioce);
                    xmlhttp.send();
                }
                else if(radiooption=='search_update')
                {
                    $('#URE_lbl_report_entry').html('USER REPORT SEARCH UPDATE');
                    $('#USRC_UPD_lbl_strtdte').show();
                    $('#USRC_UPD_tb_strtdte').show();
                    $('#USRC_UPD_lbl_enddte').show();
                    $('#USRC_UPD_tb_enddte').show();
                    $("#USRC_UPD_btn_search").attr("disabled", "disabled").show();
                    $('#search_update').show();
                    $('#entry').hide();
                    $('#USRC_UPD_btn_pdf').hide();
                    $('#USRC_UPD_btn_submit').hide();
                    $('#USRC_UPD_btn_srch').hide();
                    $('#USRC_UPD_tb_date').hide();
                    $("#USRC_UPD_btn_search").attr("disabled", "disabled");
                    var errmsgs;
                    $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            var value_array=JSON.parse(xmlhttp.responseText);
                            $("#USRC_UPD_lb_attendance option[value='2']").detach();
                            $(".preloader").hide();
                            permission_array=value_array[0];
                            project_array=value_array[1];
                            min_date=value_array[2];
                            err_msg=value_array[4];
                            if(min_date=='01-01-1970')
                            {
                                $('#URE_form_dailyuserentry').replaceWith('<p><label class="errormsg">'+ err_msg[10] +'</label></p>');
                            }
                            else
                            {
                                search_max_date=value_array[3];
                                join_date=value_array[5];
                                empname=value_array[6];
                                var wfh_flag=value_array[7];
                                if(wfh_flag == 'X')
                                {
                                    $('#USRC_UPD_lb_attendance').append("<option value='2'>WORK FROM HOME</option>")
                                }
                                mindatesplit=join_date.split('-');
                                max_date=new Date();
                                month=max_date.getMonth()+1;
                                year=max_date.getFullYear();
                                date=max_date.getDate();
                                max_date = new Date(year,month,date);
                                datepicker_maxdate=new Date(Date.parse(max_date));
                                $('#USRC_UPD_tb_strtdte').datepicker("option","minDate",min_date);
                                $('#USRC_UPD_tb_strtdte').datepicker("option","maxDate",search_max_date);
                                $('#USRC_UPD_tb_enddte').datepicker("option","maxDate",search_max_date);
                                $('#USRC_UPD_lbl_strtdte').show();
                                $('#USRC_UPD_tb_strtdte').show();
                                $('#USRC_UPD_lbl_enddte').show();
                                $('#USRC_UPD_tb_enddte').show();
                                $("#USRC_UPD_btn_search").attr("disabled", "disabled").show();
                                $('#USRC_UPD_tb_date').datepicker("option","maxDate",datepicker_maxdate);
                                $('#USRC_UPD_tb_date').datepicker("option","minDate",join_date);
                                $('#USRC_UPD_errmsg').hide();
                            }
                        }
                    }
                    var option="user_search_update";
                    xmlhttp.open("GET","TSLIB/TSLIB_COMMON.do?option="+option);
                    xmlhttp.send();
                }
                else
                {
                    $('#entry').hide();
                    $('#search_update').hide();
                }
            });
            <!--ENTRY-->
            //DATE PICKER FUNCTION
            $('#URE_tb_date').datepicker({
                dateFormat:"dd-mm-yy",
                changeYear: true,
                changeMonth: true
            });
//    //JQUERY LIB VALIDATION START//ths one need to check nd implement
            $("#URE_tb_band").prop("title","NUMBERS ONLY");
            $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:4,imaginary:2}});
            // CLICK EVENT FOR PERMISSION RADIO BUTN
            $(document).on('click','#URE_rd_permission',function()
            {
                if($('#URE_rd_permission').attr("checked","checked"))
                {
                    $('#URE_lb_timing').val('SELECT').show();
                }
                else
                {
                    $('#URE_lb_timing').hide();
                    $('#URE_lb_timing').prop('selectedIndex',0);
                }
            });
// CLICK EVENT FOR NOPERMISSION RADIO BUTN
            $(document).on('click','#URE_rd_nopermission',function()
            {
                $('#URE_lb_timing').hide();
                $('#URE_lb_timing').prop('selectedIndex',0);

            });
//FUNCTION FOR FORM CLEAR
            function UARD_clear(){
                $('#URE_tble_attendence').hide();
                $('#URE_lb_attendance').prop('selectedIndex',0);
                $('#URE_tble_reasonlbltxtarea').html('');
                $('#URE_tble_frstsel_projectlistbx').html('');
                $('#URE_tble_enterthereport').html('');
                $('#URE_tble_bandwidth').html('');
                $('#URE_btn_submit').html('');
                $('#URE_lbl_session').hide();
                $('#URE_chk_permission').hide();
                $('#URE_lbl_permission').hide();
                $('#URE_rd_permission').hide();
                $('#URE_lbl_permission').hide();
                $('#URE_rd_nopermission').hide();
                $('#URE_lbl_nopermission').hide();
                $('#URE_lb_timing').hide();
                $('#URE_lb_timing').prop('selectedIndex',0);
                $('#URE_lb_ampm').hide();
                $('#URE_btn_submit').hide();
                $('#URE_tble_projectlistbx').hide();
            }
            //CHANGE EVENT FOR BANDWIDTH TEXTBX
            $(document).on('change blur','#URE_tb_band',function(){
                var bandwidth=$('#URE_tb_band').val();
                if(bandwidth > 1000)
                {
                    var msg=err_msg[9].toString().replace("[BW]",bandwidth);
                    $('#URE_lbl_errmsg').text(msg).show();
                }
                else
                {
                    $('#URE_lbl_errmsg').hide();
                }
            });
            // CHANGE EVENT FOR REPORT TEXTAREA
            $(document).on('change','#URE_ta_report',function(){
                $('#URE_btn_submit').show();
                $('#URE_btn_submit').attr('disabled','disabled');
                $('#URE_lbl_errmsg').hide();
            });
            // FUNCTION FOR REASON
            function URE_tble_reason(){
                $('<div class="row-fluid" style="padding-top: 10px"><label name="URE_lbl_reason" class="col-sm-2" id="URE_lbl_reason" >REASON<em>*</em></label><div class="col-lg-10"><textarea  name="URE_ta_reason" id="URE_ta_reason" class="tarea form-control "></textarea></div></div>').appendTo($("#URE_tble_reasonlbltxtarea"));
                $('textarea').autogrow({onInitialize: true});
            }
            // FUNCTION FOR MULTIPLE DAY REASON
            function URE_mulreason(){
                $('<div class="row-fluid" style="padding-top: 10px"><label name="URE_lbl_reason" class="col-sm-2" id="URE_lbl_reason" >REASON<em>*</em></label><div class="col-lg-10"><textarea  name="URE_ta_reason" id="URE_ta_reason" class="tarea form-control " ></textarea></div></div>').appendTo($("#URE_tble_reason"));
                $('textarea').autogrow({onInitialize: true});
            }
            // FUNCTION FOR REPORT TEXTAREA
            function URE_report(){
                $('<div class="row-fluid" style="padding-top: 10px"><label name="URE_lbl_report" class="col-sm-2" id="URE_lbl_report" >REPORT<em>*</em></label><div class="col-lg-10"><textarea  name="URE_ta_report" id="URE_ta_report" class="tarea form-control " ></textarea></div></div>').appendTo($("#URE_tble_enterthereport"));
                $('textarea').autogrow({onInitialize: true});
            }
            // FUCNTION FOR BANDWIDTH
            function URE_tble_bandwidth(){
                $('<div class="row-fluid"><label name="URE_lbl_band" class="col-sm-2" id="URE_lbl_band" >BANDWIDTH<em>*</em></label><div class="col-sm-4"><input type="text" name="URE_tb_band" id="URE_tb_band" class="autosize amountonly" style="width:75px;"><label name="URE_lbl_band" id="URE_lbl_band">MB</label></div></div>').appendTo($("#URE_tble_bandwidth"));
                $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:4,imaginary:2}});
            }
            //FORM VALIDATION
            $(document).on('change blur','#URE_form_dailyuserentry',function(){
                if($("input[name=entry]:checked").val()=="SINGLE DAY ENTRY"){
                    var URE_sessionlstbx= $("#URE_lb_ampm").val();
                    var URE_tble_reasontxtarea =$("#URE_ta_reason").val();
                    var URE_reportenter =$("#URE_ta_report").val();
                    var URE_bndtxt = $("#URE_tb_band").val();
                    var URE_projectselectlistbx = $("input[id=checkbox]").is(":checked");
                    var URE_permissionlstbx = $("#URE_lb_timing").val();
                    var URE_permission=$("input[name=permission]:checked").val()=="PERMISSION";
                    var URE_nopermission=$("input[name=permission]:checked").val()=="NOPERMISSION";
                    var URE_presenthalfdysvld=$("#URE_lb_attendance").val();
                    if(((URE_presenthalfdysvld=='0') && (URE_sessionlstbx=='AM' || URE_sessionlstbx=="PM")) || ((URE_presenthalfdysvld=='OD') && (URE_sessionlstbx=='AM' || URE_sessionlstbx=="PM") ))
                    {
                        if(((URE_tble_reasontxtarea.trim()!="")&&(URE_reportenter!='')&&( URE_projectselectlistbx==true) && (URE_bndtxt!='') &&(URE_bndtxt<=1000) && ((URE_permission==true) || (URE_nopermission==true))))
                        {
                            if(URE_permission==true)
                            {
                                if(URE_permissionlstbx!='SELECT')
                                {
                                    $("#URE_btn_submit").removeAttr("disabled");
                                }
                                else
                                {
                                    $("#URE_btn_submit").attr("disabled", "disabled");
                                }
                            }
                            else
                            {
                                $("#URE_btn_submit").removeAttr("disabled");
                            }
                        }
                        else
                        {
                            $("#URE_btn_submit").attr("disabled", "disabled");
                        }
                    }
                    else if((URE_presenthalfdysvld=='0' && URE_sessionlstbx=='FULLDAY') || (URE_presenthalfdysvld=='OD' && URE_sessionlstbx=='FULLDAY'))
                    {
                        if(URE_tble_reasontxtarea.trim()=="")
                        {
                            $("#URE_btn_submit").attr("disabled", "disabled");
                        }
                        else
                        {
                            $("#URE_btn_submit").removeAttr("disabled");
                        }
                    }
                    else if(URE_presenthalfdysvld=='1')
                    {
                        if(((URE_reportenter.trim()!="")&&(URE_bndtxt!='') && (URE_bndtxt<=1000) &&( URE_projectselectlistbx==true) && ((URE_permission==true) || (URE_nopermission==true))))
                        {
                            if(URE_permission==true)
                            {
                                if(URE_permissionlstbx!='SELECT')
                                {
                                    $("#URE_btn_submit").removeAttr("disabled");
                                }
                                else
                                {
                                    $("#URE_btn_submit").attr("disabled", "disabled");
                                }
                            }
                            else
                            {
                                $("#URE_btn_submit").removeAttr("disabled");
                            }
                        }
                        else
                        {
                            $("#URE_btn_submit").attr("disabled", "disabled");
                        }
                    }
                    else if(URE_presenthalfdysvld=='2')
                    {
                        if(((URE_reportenter.trim()!="")))
                        {
                            if(URE_projectselectlistbx==true)
                            {
                                if(URE_projectselectlistbx!='SELECT')
                                {
                                    $("#URE_btn_submit").removeAttr("disabled");
                                }
                                else
                                {
                                    $("#URE_btn_submit").attr("disabled", "disabled");
                                }
                            }
                            else
                            {
                                $("#URE_btn_submit").removeAttr("disabled");
                            }
                        }
                        else
                        {
                            $("#URE_btn_submit").attr("disabled", "disabled");
                        }
                    }
                }
                else if($("input[name=entry]:checked").val()=="MULTIPLE DAY ENTRY"){
                    var URE_tble_reasontxtarea =$("#URE_ta_reason").val();
                    var URE_presenthalfdysvld=$("#URE_lb_attdnce").val();
                    if((URE_presenthalfdysvld=='0') || (URE_presenthalfdysvld=='OD'))
                    {
                        if(URE_tble_reasontxtarea.trim()=="")
                        {
                            $("#URE_btn_save").attr("disabled", "disabled");
                        }
                        else
                        {
                            $("#URE_btn_save").removeAttr("disabled");
                        }
                    }
                }
            });
            // CLICK EVENT FOR SINGLE DAY RADIO BUTTON
            $('#URE_rd_sinentry').click(function(){
                $('#URE_tbl_singleday').show();
                $('#URE_lbl_dte').show();
                $('#URE_tb_date').val('').show();
                $('#URE_tbl_multipleday').hide();
                $('#URE_lbl_reason').hide();
                $('#URE_ta_reason').hide();
                $('#URE_tbl_attendence').hide();
                $('#URE_btn_save').hide();
                $('#URE_lbl_attdnce').hide();
                $('#URE_lb_attdnce').hide();
                $('#URE_btn_save').hide();
                $('#URE_lbl_msg').hide();
            });
            //CLICK EVENT FOR MULTIPLE DAY RADIO BUTTON
            $('#URE_rd_mulentry').click(function(){
                $('#URE_tbl_singleday').hide();
                $('#URE_tbl_multipleday').show();
                $('#URE_lbl_errmsg').hide();
                $('#URE_ta_fromdate').val('');
                $('#URE_ta_todate').val('');
                $('#URE_lbl_checkmsg').hide();
                UARD_clear();
            });
// DATEPICKER FUNCTION
            $('.dtpic').datepicker({
                dateFormat:"dd-mm-yy",
                changeYear: true,
                changeMonth: true
            });
            // CHANGE EVENT FOR FROM DATE
            $(document).on('change','#URE_ta_fromdate',function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                var URE_fromdate = $('#URE_ta_fromdate').datepicker('getDate');
                var date = new Date( Date.parse( URE_fromdate ));
                date.setDate( date.getDate()  );
//                $('.preloader').show();
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                var URE_todate = date.toDateString();
//                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                URE_todate = new Date( Date.parse( URE_todate ));
                $('#URE_ta_todate').datepicker("option","minDate",URE_todate);
                max_date=new Date();
                month=max_date.getMonth()+1;
                year=max_date.getFullYear();
                date=max_date.getDate();
                max_date = new Date(year,month,date);
//                $('.preloader').hide();
                $('#URE_ta_todate').datepicker("option","maxDate",max_date);
            });
            // CHANGE EVENT FOR MUTIPLE DAY ATTENDANCE
            $('#URE_lb_attdnce').change(function(){
                $('#URE_tble_reason').html('');
                if($('#URE_lb_attdnce').val()=='SELECT')
                {
                    $('#URE_lbl_reason').hide();
                    $('#URE_ta_reason').hide();
                    $('#URE_btn_save').hide();
                }
                else if(($('#URE_lb_attdnce').val()=='0') || ($('#URE_lb_attdnce').val()=='OD'))
                {
                    $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                    URE_mulreason()
                    $('#URE_lbl_reason').show();
                    $('#URE_ta_reason').show();
                    $('#URE_btn_save').show();
                }
            });
            // FUNCTION FOR CLEAR
            function URE_mulclear()
            {
                $('#URE_lbl_reason').hide();
                $('#URE_tble_reason').html('');
                $('#URE_ta_reason').hide();
                $('#URE_btn_save').hide();
                $('#URE_tbl_attendence').hide();
                $('#URE_lbl_attdnce').hide();
                $('#URE_lb_attdnce').hide();
            }
            //JQUERY LIB VALIDATION END
            $(document).on('change','#URE_tb_date',function(){
                $(".preloader").show();
                $('#URE_rd_permission').hide();
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                var reportdate=$('#URE_tb_date').val();

                $('#URE_lbl_checkmsg').hide();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        msgalert=xmlhttp.responseText;
                        if(msgalert==1)
                        {
                            var msg=err_msg[3].toString().replace("[DATE]",reportdate);
                            UARD_clear()
                            $("#URE_tb_date").val('');
                            $('#URE_tble_attendence').hide();
                            $('#URE_lbl_errmsg').text(msg).show();
                        }
                        else
                        {
                            UARD_clear()
                            $('#URE_tble_attendence').val('SELECT').show();
                            $('#URE_lbl_errmsg').hide();
                            $('#URE_lb_attendance').prop('selectedIndex',0);
                        }
                    }
                }
                var option="DATE";
                xmlhttp.open("GET","USER/DB_DAILY_REPORTS_USER_REPORT_ENTRY.do?date_change="+reportdate+"&option="+option);
                xmlhttp.send();
            });
            //CHANGE EVENT FOR ATTENDANCE
            $(document).on('change','#URE_lb_attendance',function(){
                $('#URE_tble_frstsel_projectlistbx').html('');
                $('#URE_btn_submit').attr('disabled','disabled');
                $('#URE_tble_reasonlbltxtarea').html('');
                $('#noopermission').show();
                if($('#URE_lb_attendance').val()=='SELECT')
                {
                    $('#URE_lbl_permission').hide();
                    $('#URE_rd_permission').hide();
                    $('#URE_rd_nopermission').hide();
                    $('#URE_lbl_nopermission').hide();
                    $('#URE_lb_timing').hide();
                    $('#URE_tble_enterthereport').html('');
                    $('#URE_tble_projectlistbx').hide();
                    $('#URE_tble_bandwidth').html('');
                    $('#URE_lbl_session').hide();
                    $('#URE_lb_ampm').hide();
                    $('#URE_btn_submit').hide();
                    $('#URE_lbl_checkmsg').hide();
                }
                else if($('#URE_lb_attendance').val()=='1')
                {

                    $(".preloader").show();
                    var reportdate=$('#URE_tb_date').val();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            response=xmlhttp.responseText;
                            if(response==1)
                            {
                                $('#entry').show();

                                $('#URE_lbl_checkmsg').text(err_msg[11]).show();
                                $('#URE_lb_timing').hide();
                                $('#URE_lbl_permission').hide();
                                $('#URE_rd_permission').hide();
                                $('#URE_rd_nopermission').hide();
                                $('#URE_lbl_nopermission').hide();
                                $('#noopermission').hide();
                                $('#URE_lbl_session').hide();
                                $('#URE_lb_ampm').hide();
                                $('#URE_tble_projectlistbx').hide();
                                $('#URE_btn_submit').hide();

                                $('#URE_rd_permission').removeAttr("disabled");
                                $('#URE_rd_nopermission').removeAttr("disabled");
                                $('#URE_lbl_errmsg').hide();
                            }
                            else if(response==0)
                            {
                                $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                                $('#URE_tble_enterthereport,#URE_ta_reason,#URE_tble_bandwidth').html('');
                                $('#URE_rd_permission').attr('checked',false);
                                $('#URE_rd_nopermission').attr("checked",false);
                                $('#URE_rd_permission').removeAttr("disabled");
                                $('#URE_rd_nopermission').removeAttr("disabled");
                                $('#USRC_UPD_tb_strtdte').hide();
                                $('#USRC_UPD_lbl_strtdte').hide();
                                $('#USRC_UPD_lbl_enddte').hide();
                                $('#USRC_UPD_tb_enddte').hide();
                                $('#USRC_UPD_btn_search').hide();
                                $('#search_update').show();
                                $('#URE_lb_timing').hide();
                                $('#URE_lbl_permission').show();
                                $('#URE_rd_permission').show();
                                $('#URE_rd_nopermission').show();
                                $('#URE_lbl_nopermission').show();
                                var permission_list='<option>SELECT</option>';
                                for (var i=0;i<permission_array.length;i++) {
                                    permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                                }
                                $('#URE_lb_timing').html(permission_list);
                                $('#URE_lbl_session').hide();
                                $('#URE_lb_ampm').hide();
                                $('#URE_tble_projectlistbx').show();
                                projectlists();
                                URE_report();
                                URE_tble_bandwidth();
                                $('#URE_btn_submit').hide();
                                $('#URE_lbl_errmsg').hide();
                                $('#URE_lbl_checkmsg').hide();
                            }
                        }

                    }
                    var option="PRESENT";
                    xmlhttp.open("POST","USER/DB_DAILY_REPORTS_USER_REPORT_ENTRY.do?option="+option+"&reportdate="+reportdate);
                    xmlhttp.send();

                }
                else if($('#URE_lb_attendance').val()=='0')
                {


                    $('#URE_rd_permission').attr('checked',false);
                    $('#URE_rd_nopermission').attr("checked",false);
                    $('#noopermission').show();
                    $('#URE_lb_timing').hide();
                    $('#URE_lbl_permission').show();
                    $('#URE_rd_permission').show();
                    $('#URE_rd_nopermission').show();
                    $('#URE_lbl_nopermission').show();
                    var permission_list='<option>SELECT</option>';
                    for (var i=0;i<4;i++) {
                        permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                    }

                    $('#URE_lb_timing').html(permission_list);
                    $('#URE_lbl_session').show();
                    $('#URE_lb_ampm').val('SELECT').show();
                    $('#URE_tble_projectlistbx').hide();
                    $('#URE_tble_reasonlbltxtarea').html('');
                    $('#URE_tble_enterthereport').html('');
                    $('#URE_tble_bandwidth').html('');
                    $('#URE_btn_submit').hide();
                    $('#URE_rd_permission').attr('disabled','disabled');
                    $('#URE_rd_nopermission').attr('disabled','disabled');
                    $('#URE_lbl_errmsg').hide();
                    $('#URE_lbl_checkmsg').hide();
                }
                else if($('#URE_lb_attendance').val()=='OD')
                {
                    $('#URE_rd_permission').attr('checked',false);
                    $('#URE_rd_nopermission').attr("checked",false);
                    $('#URE_lb_timing').hide();
                    $('#URE_lbl_permission').show();
                    $('#URE_rd_permission').show();
                    $('#URE_rd_nopermission').show();
                    $('#URE_lbl_nopermission').show();
                    $('#URE_lbl_session').show();
                    var permission_list='<option>SELECT</option>';
                    for (var i=0;i<4;i++) {
                        permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                    }
                    $('#URE_lb_timing').html(permission_list);
                    $('#URE_lb_ampm').val('SELECT').show();
                    $('#URE_tble_projectlistbx').hide();
                    $('#URE_tble_reasonlbltxtarea').html('');
                    $('#URE_tble_enterthereport').html('');
                    $('#URE_tble_bandwidth').html('');
                    $('#URE_btn_submit').hide();
                    $('#URE_rd_permission').attr('disabled','disabled');
                    $('#URE_rd_nopermission').attr('disabled','disabled');
                    $('#URE_lbl_errmsg').hide();
                    $('#URE_lbl_checkmsg').hide();
                }
                else if($('#URE_lb_attendance').val()=='2')
                {
                    $(".preloader").show();
                    var reportdate=$('#URE_tb_date').val();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            response=xmlhttp.responseText;
                            if(response==1)
                            {
                                $('#URE_lbl_checkmsg').text(err_msg[12]).show();
                                $('#URE_lb_timing').hide();
                                $('#URE_lbl_permission').hide();
                                $('#URE_rd_permission').hide();
                                $('#URE_rd_nopermission').hide();
                                $('#URE_lbl_nopermission').hide();
                                $('#noopermission').hide();
                                $('#URE_lbl_session').hide();
                                $('#URE_lb_ampm').hide();
                                $('#URE_tble_projectlistbx').hide();
                                $('#URE_btn_submit').hide();
                                $('#URE_rd_permission').removeAttr("disabled");
                                $('#URE_rd_nopermission').removeAttr("disabled");
                                $('#URE_lbl_errmsg').hide();
                            }
                            else if(response==0)
                            {
                                $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                                $('#URE_tble_enterthereport,#URE_ta_reason,#URE_tble_bandwidth').html('');
//                                $('#URE_lbl_checkmsg').text(err_msg[11]).show();
                                $('#URE_lbl_errmsg').hide();
                                $('#URE_rd_permission').hide();
                                $('#URE_rd_nopermission').hide();
                                $('#URE_lbl_nopermission').hide();
                                $('#noopermission').hide();
                                $('#URE_lbl_session').hide();
                                $('#URE_lb_ampm').hide();
                                $('#URE_tble_enterthereport').html('');
                                $('#URE_tble_bandwidth').html('');
                                $('#URE_tble_projectlistbx').show();
                                projectlists();
                                URE_report();
                                $('#URE_btn_submit').hide();
                                $("#URE_btn_submit").removeAttr("disabled");
                                $('#URE_rd_permission').attr('disabled','disabled');
                                $('#URE_rd_nopermission').attr('disabled','disabled');
                                $('#URE_lbl_errmsg').hide();
                                $('#URE_lbl_checkmsg').hide();
                            }
                        }
                    }
                    var option="PRESENT";
                    xmlhttp.open("POST","USER/DB_DAILY_REPORTS_USER_REPORT_ENTRY.do?option="+option+"&reportdate="+reportdate);
                    xmlhttp.send();
                }
            });
            // CHANGE EVENT FOR SESSION LISTBOX
            $('#URE_lb_ampm').change(function(){
                $('#URE_tble_reasonlbltxtarea,#URE_tble_enterthereport,#URE_tble_bandwidth,#URE_tble_frstsel_projectlistbx').html('');
                if($('#URE_lb_ampm').val()=='SELECT')
                {
                    $('#URE_tble_reasonlbltxtarea').html('');
                    $('#URE_tble_frstsel_projectlistbx').html('');
                    $('#URE_tble_enterthereport').html('');
                    $('#URE_tble_projectlistbx').hide();
                    $('#URE_tble_bandwidth').html('');
                    $('#URE_btn_submit').hide();
                    $('#URE_lbl_errmsg').hide();
                    $('#URE_lbl_checkmsg').hide();
                    $('#noopermission').hide();
                }
                else if($('#URE_lb_ampm').val()=='FULLDAY')
                {
                    $('#URE_tble_projectlistbx').hide();
                    $('#noopermission').hide();
                    URE_tble_reason();
                    $('#URE_rd_permission').attr('disabled','disabled');
                    $('#URE_rd_nopermission').attr('disabled','disabled');
                    $('#URE_btn_submit').show();
                    $('#URE_lb_timing').hide();
                    $('#URE_lbl_permission').hide();
                    $('#URE_rd_permission').hide();
                    $('#URE_rd_nopermission').hide();
                    $('#URE_lbl_nopermission').hide();
                    $('#URE_lbl_errmsg').hide();
                    $('#URE_lbl_checkmsg').hide();
                }
                else
                {

                    $(".preloader").show();
                    var reportdate=$('#URE_tb_date').val();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            response=xmlhttp.responseText;
                            if(response==1)
                            {
                                $('#URE_tble_projectlistbx').hide();
                                $('#URE_btn_submit').hide();
                                $('#URE_lbl_permission').hide();
                                $('#URE_rd_permission').hide();
                                $('#URE_rd_nopermission').hide();
                                $('#URE_lbl_nopermission').hide();
                                $('#URE_lb_timing').hide();
                                $('#URE_lbl_errmsg').hide();
                                $('#URE_lbl_checkmsg').text(err_msg[11]).show();
                            }
                            else
                            {

                                $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                                $('#URE_rd_permission').attr('checked',false);
                                $('#URE_rd_nopermission').attr("checked",false);
                                $('#noopermission').show();
                                $('#URE_rd_permission').removeAttr('disabled');
                                $('#URE_rd_nopermission').removeAttr('disabled');
                                $('#URE_tble_projectlistbx').show();
                                URE_tble_reason();
                                projectlists();
                                URE_report();
                                URE_tble_bandwidth();

                                $('#URE_btn_submit').hide();

                                $('#URE_lbl_permission').show();
                                $('#URE_rd_permission').show();
                                $('#URE_rd_nopermission').show();
                                $('#URE_lbl_nopermission').show();
                                $('#URE_lb_timing').hide();
                                $('#URE_lbl_errmsg').hide();
                                $('#URE_lbl_checkmsg').hide();
                            }
                        }
                    }
                    var option="HALFDAYABSENT";
                    xmlhttp.open("POST","USER/DB_DAILY_REPORTS_USER_REPORT_ENTRY.do?option="+option+"&reportdate="+reportdate);
                    xmlhttp.send();
                }
            });
            //FUNCTION FOR PROJECT LIST
            function projectlists(){
                project_list='<table>';
                for (var i=0;i<project_array.length;i++) {
                    project_list += '<tr><td><input type="checkbox" id ="checkbox" name="checkbox[]" value="' + project_array[i][1] + '">' + project_array[i][0] + ' - '+ project_array[i][2]+'</td></tr>';
                }
                project_list +='</table>';
                $('#URE_tble_frstsel_projectlistbx').html(project_list);
            }
            // CLICK EVENT FOR SAVE BUTTON
            $(document).on('click','#URE_btn_submit',function(){
                $(".preloader").show();
                var formElement = document.getElementById("URE_form_dailyuserentry");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        msg_alert=xmlhttp.responseText;
                        if(msg_alert==1)
                        {
                            show_msgbox("USER REPORT ENTRY",err_msg[0],"success",false);
                            UARD_clear();
                            $('#URE_tb_date').val('');
                            $('#ok').addClass('reload');
                        }
                        else if(msg_alert==0)
                        {
                            show_msgbox("USER REPORT ENTRY",err_msg[4],"success",false);
                            UARD_clear();
                            $('#URE_tb_date').val('');
                        }
                        else
                        {
                            show_msgbox("USER REPORT ENTRY",msg_alert,"success",false);
                            UARD_clear();
                            $('#URE_tb_date').val('');
                        }
                    }
                }
                var option="SINGLE DAY ENTRY";
                xmlhttp.open("POST","USER/DB_DAILY_REPORTS_USER_REPORT_ENTRY.do?option="+option+"&checkoutlocation="+checkoutlocation,false);
                xmlhttp.send(new FormData(formElement));
            });
            //CLICL FUNCTION FOR RELOAD THE PAGE
            $(document).on('click','.reload',function(){
                $(".preloader").show();
                window.location.reload();
                $(".preloader").hide();
            });
            // CHANGE EVENT FOR MULTIPLE DAY SAVE BUTTON
            $('#URE_btn_save').click(function(){
                $(".preloader").show();
                var formElement = document.getElementById("URE_form_dailyuserentry");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        msg_alert=xmlhttp.responseText;
                        $(".preloader").hide();
                        if(msg_alert==1){
                            show_msgbox("USER REPORT ENTRY",err_msg[0],"success",false);
                            URE_mulclear()
                            $('#URE_lbl_sdte').show();
                            $('#URE_ta_fromdate').val('').show();
                            $('#URE_lbl_edte').show();
                            $('#URE_ta_todate').val('').show();
                        }
                        else if(msg_alert==0)
                        {
                            show_msgbox("USER REPORT ENTRY",err_msg[4],"success",false);
                            $('#URE_lbl_sdte').show();
                            $('#URE_ta_fromdate').val('').show();
                            $('#URE_lbl_edte').show();
                            $('#URE_ta_todate').val('').show();
                            URE_mulclear()
                        }
                        else
                        {
                            show_msgbox("USER REPORT ENTRY",msg_alert,"success",false);
                            $('#URE_lbl_sdte').show();
                            $('#URE_ta_fromdate').val('').show();
                            $('#URE_lbl_edte').show();
                            $('#URE_ta_todate').val('').show();
                            URE_mulclear()
                        }
                    }
                }
                var option="MULTIPLE DAY ENTRY";
                xmlhttp.open("POST","USER/DB_DAILY_REPORTS_USER_REPORT_ENTRY.do?option="+option+"&checkoutlocation="+checkoutlocation,false);
                xmlhttp.send(new FormData(formElement));
            });
            // CHANGE FUNCTION FOR TO DATE ALEREADY EXISTS
            $('.valid_date' ).change(function(){
//                alert('sss');
//                if(($("#URE_ta_fromdate").val()=='')||($("#URE_ta_todate").val()==''))
                var fromdate=$('#URE_ta_fromdate').val();
                var todate=$('#URE_ta_todate').val();
                if(fromdate!='' && todate!='')
                {
                    $(".preloader").show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            var date_array=JSON.parse(xmlhttp.responseText);
                            var error_date='';
                            $(".preloader").hide();
                            for(var i=0;i<date_array.length;i++){
                                if(i==0){
                                    error_date=date_array[i]
                                }
                                else{
                                    error_date+=','+date_array[i]
                                }
                            }
                            if(error_date=='')
                            {
                                $('#URE_tbl_attendence').show();
                                $('#URE_lbl_attdnce').show();
                                $('#URE_lb_attdnce').val('SELECT').show();
                                $('#URE_lbl_msg').text(msg).hide();
                            }
                            else
                            {
                                var msg=err_msg[3].toString().replace("[DATE]",error_date);
                                $('#URE_lbl_msg').text(msg).show();
//                    $('#URE_ta_fromdate').val('').show();
//                    $('#URE_ta_todate').val('').show();
                                $('#URE_tbl_attendence').hide();
                                $('#URE_lbl_attdnce').hide();
                                $('#URE_lb_attdnce').hide();
                                $('#URE_ta_reason').hide();
                                $('#URE_lbl_reason').hide();
                                $('#URE_btn_save').hide();

                            }
                        }
                    }
                    var option="BETWEEN DATE";
                    xmlhttp.open("GET","USER/DB_DAILY_REPORTS_USER_REPORT_ENTRY.do?option="+option+"&fromdate="+fromdate+"&todate="+todate,false);
                    xmlhttp.send();
                }
            });
            <!--ENTRY END-->

//    SEARCH UPDATE START
            $('#USRC_UPD_btn_srch').hide()
            $('#USRC_UPD_btn_submit').hide()
            //DATE PICKER FUNCTION
            $('.USRC_UPD_tb_date').datepicker({
                dateFormat:"dd-mm-yy",
                changeYear: true,
                changeMonth: true
            });
            //BUTTON VALIDATION
            $('.valid').change(function(){
                if(($("#USRC_UPD_tb_strtdte").val()=='')||($("#USRC_UPD_tb_enddte").val()==''))
                {
                    $("#USRC_UPD_btn_search").attr("disabled", "disabled");
                }
                else
                {
                    $("#USRC_UPD_btn_search").removeAttr("disabled");
                }
            });
            //CHANGE EVENT FOR STARTDATE AND ENDDATE
            $(document).on('change','#USRC_UPD_tb_strtdte,#USRC_UPD_tb_enddte',function(){
                $('#USRC_UPD_div_header').hide();
                $('#USRC_UPD_btn_pdf').hide();
                clear();
                $('#USRC_UPD_tbl_htmltable').html('');
                $('#USRC_UPD_btn_srch').hide();
                $('#USRC_UPD_lbl_dte').hide();
                $('#USRC_UPD_tb_date').hide();
                $('#USRC_UPD_errmsg').hide();
                $('#USRC_UPD_div_tablecontainer').hide();
                $('#USRC_UPD_banderrmsg').hide();
            });
            // CHANGE EVENT FOR STARTDATE
            $(document).on('change','#USRC_UPD_tb_strtdte',function(){
                $('#USRC_UPD_div_header').hide();
                $('#USRC_UPD_btn_pdf').hide();
                var USRC_UPD_startdate = $('#USRC_UPD_tb_strtdte').datepicker('getDate');
                var date = new Date( Date.parse( USRC_UPD_startdate ));
                date.setDate( date.getDate()  );
                var USRC_UPD_todate = date.toDateString();
                USRC_UPD_todate = new Date( Date.parse( USRC_UPD_todate ));
                $('#USRC_UPD_tb_enddte').datepicker("option","minDate",USRC_UPD_todate);
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
            });
            var values_array=[];
            $(document).on('click','#USRC_UPD_btn_search',function(){
                $('#USRC_UPD_div_header').hide();
                $('#USRC_UPD_btn_pdf').hide();
                $('#USRC_UPD_div_tablecontainer').hide();
                $('section').html('');
                $(".preloader").show();
                flextable();
//        $(".preloader").hide();

            });
            //DATE PICKER FUNCTION-->
            $('#USRC_UPD_tb_date').datepicker({
                dateFormat:"dd-mm-yy",
                changeYear: true,
                changeMonth: true
            });
            //FUNCTION FOR FORMTABLEDATEFORMAT
            function FormTableDateFormat(inputdate){
                var string = inputdate.split("-");
                return string[2]+'-'+ string[1]+'-'+string[0];
            }
//    var values_array;
            //FUNCTION FOR DATA TABLE
            function flextable()
            {
                $(".preloader").hide();
                var ure_after_mrg;
                var start_date=$('#USRC_UPD_tb_strtdte').val();
                var end_date=$('#USRC_UPD_tb_enddte').val();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        values_array=JSON.parse(xmlhttp.responseText);
                        if(values_array.length!=0){
                            $(".preloader").hide();
                            $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                            //HEADER ERR MSG
                            $(".preloader").hide();
                            sd=err_msg[11].toString().replace("[LOGINID]",empname);
                            msg=sd.toString().replace("[STARTDATE]",start_date);
                            errmsgs=msg.toString().replace("[ENDDATE]",end_date);
                            $('#USRC_UPD_div_header').text(errmsgs).show();
                            $('#USRC_UPD_btn_pdf').show();
                            var USRC_UPD_table_header='<table id="USRC_UPD_tbl_htmltable" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th></th><th  class="uk-date-column">DATE</th><th >REPORT</th><th class="uk-timestp-column">TIMESTAMP</th></tr></thead><tbody>'
                            for(var j=0;j<values_array.length;j++){
                                var emp_date=values_array[j].date;
                                var emp_report=values_array[j].report;
                                var emp_reason=values_array[j].reason;
                                var timestamp=values_array[j].timestamp;
                                var permission=values_array[j].permission;
                                var morningsession=values_array[j].morningsession;
                                var afternoonsession=values_array[j].afternoonsession;
                                var id=values_array[j].id;
                                if(permission==null)
                                {
                                    if(morningsession=='PRESENT'){
                                        ure_after_mrg=afternoonsession+'(PM)';
                                    }
                                    else
                                    {
                                        ure_after_mrg=morningsession+'(AM)';
                                    }
                                    if(emp_report==null)
                                    {
                                        if(morningsession=='PRESENT'){
                                            ure_after_mrg=afternoonsession;
                                        }
                                        else
                                        {
                                            ure_after_mrg=morningsession;
                                        }
                                        USRC_UPD_table_header+='<tr ><td><input type="radio" name="USRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+id+'  value='+id+'></td><td>'+emp_date+'</td><td> '+ure_after_mrg +' -  '+'REASON:'+emp_reason+'</td><td style="width:130px;" >'+timestamp+'</td></tr>';
                                    }
                                    else if(emp_reason==null)
                                    {
                                        USRC_UPD_table_header+='<tr ><td><input type="radio" name="USRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+id+'  value='+id+'></td><td>'+emp_date+'</td><td style="max-width:400px; !important;">'+emp_report+'</td><td style="width:130px;">'+timestamp+'</td></tr>';
                                    }
                                    else
                                    {
                                        USRC_UPD_table_header+='<tr ><td><input type="radio" name="USRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+id+'  value='+id+'></td><td>'+emp_date+'</td><td style="max-width:400px; !important;">'+emp_report+' <br> '+ure_after_mrg +'  -  '+'REASON:'+emp_reason+'</td><td style="width:130px;">'+timestamp+'</td></tr>';
                                    }
                                }
                                else
                                {
                                    if(morningsession=='PRESENT'){
                                        ure_after_mrg=afternoonsession+'(PM)';
                                    }
                                    else
                                    {
                                        ure_after_mrg=morningsession+'(AM)';
                                    }
                                    if(emp_report==null)
                                    {
                                        if(morningsession=='PRESENT'){
                                            ure_after_mrg=afternoonsession;
                                        }
                                        else
                                        {
                                            ure_after_mrg=morningsession;
                                        }
                                        USRC_UPD_table_header+='<tr ><td><input type="radio" name="USRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+id+'  value='+id+'></td><td>'+emp_date+'</td><td> '+ure_after_mrg +'  -  '+'REASON:'+emp_reason+'<br>PERMISSION:'+permission+' hrs</td><td style="width:130px;">'+timestamp+'</td></tr>';
                                    }
                                    else if(emp_reason==null)
                                    {
                                        USRC_UPD_table_header+='<tr ><td><input type="radio" name="USRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+id+'  value='+id+'></td><td>'+emp_date+'</td><td style="max-width:400px; !important;">'+emp_report+' <br>PERMISSION:'+permission+' hrs</td><td style="width:130px;">'+timestamp+'</td></tr>';
                                    }
                                    else
                                    {
                                        USRC_UPD_table_header+='<tr ><td><input type="radio" name="USRC_UPD_rd_flxtbl" class="USRC_UPD_class_radio" id='+id+'  value='+id+'></td><td>'+emp_date+'</td><td style="max-width:400px; !important;"> '+emp_report+' <br> '+ure_after_mrg +'  -  '+'REASON:'+emp_reason+' <br>PERMISSION:'+permission+' hrs</td><td style="width:130px;">'+timestamp+'</td></tr>';
                                    }
                                }
                            }
                            USRC_UPD_table_header+='</tbody></table>';
                            $('section').html(USRC_UPD_table_header);
                            $('#USRC_UPD_tbl_htmltable').DataTable( {
                                "aaSorting": [],
                                "pageLength": 10,
                                "sPaginationType":"full_numbers",
                                "aoColumnDefs" : [
                                    { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                            });
                        }
                        else{
                            $(".preloader").hide();
                            $('#USRC_UPD_div_tablecontainer').hide();
                            $('#USRC_UPD_div_header').hide();
                            $('#USRC_UPD_btn_pdf').hide();
                            var sd=err_msg[6].toString().replace("[SDATE]",start_date);
                            var msg=sd.toString().replace("[EDATE]",end_date);
                            $('#USRC_UPD_errmsg').text(msg).show();
                        }
                        $(".preloader").hide();
                        $("#USRC_UPD_btn_search").attr("disabled", "disabled");
                    }
                }
                $('#USRC_UPD_div_tablecontainer').show();
                var option='SEARCH';
                xmlhttp.open("GET","USER/DB_DAILY_REPORTS_USER_SEARCH_UPDATE.do?start_date="+start_date+"&end_date="+end_date+"&option="+option,false);
                xmlhttp.send();
                sorting();
            }
            //FUNCTION FOR SORTING
            function sorting(){
                jQuery.fn.dataTableExt.oSort['uk_date-asc']  = function(a,b) {
                    var x = new Date( Date.parse(FormTableDateFormat(a)));
                    var y = new Date( Date.parse(FormTableDateFormat(b)) );
                    return ((x < y) ? -1 : ((x > y) ?  1 : 0));
                };
                jQuery.fn.dataTableExt.oSort['uk_date-desc'] = function(a,b) {
                    var x = new Date( Date.parse(FormTableDateFormat(a)));
                    var y = new Date( Date.parse(FormTableDateFormat(b)) );
                    return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
                }
                jQuery.fn.dataTableExt.oSort['uk_timestp-asc']  = function(a,b) {
                    var x = new Date( Date.parse(FormTableDateFormat(a.split(' ')[0]))).setHours(a.split(' ')[1].split(':')[0],a.split(' ')[1].split(':')[1],a.split(' ')[1].split(':')[2]);
                    var y = new Date( Date.parse(FormTableDateFormat(b.split(' ')[0]))).setHours(b.split(' ')[1].split(':')[0],b.split(' ')[1].split(':')[1],b.split(' ')[1].split(':')[2]);
                    return ((x < y) ? -1 : ((x > y) ?  1 : 0));
                };
                jQuery.fn.dataTableExt.oSort['uk_timestp-desc'] = function(a,b) {
                    var x = new Date( Date.parse(FormTableDateFormat(a.split(' ')[0]))).setHours(a.split(' ')[1].split(':')[0],a.split(' ')[1].split(':')[1],a.split(' ')[1].split(':')[2]);
                    var y = new Date( Date.parse(FormTableDateFormat(b.split(' ')[0]))).setHours(b.split(' ')[1].split(':')[0],b.split(' ')[1].split(':')[1],b.split(' ')[1].split(':')[2]);
                    return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
                };
            }
// CLICK EVENT FR RADIO BUTTON
            $(document).on('click','.USRC_UPD_class_radio',function(){
                err_flag=0;
                $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                $("#USRC_UPD_tble_reasonlbltxtarea,#USRC_UPD_secndselectprojct,#USRC_UPD_tble_enterthereport,#USRC_UPD_tble_bandwidth,#USRC_UPD_mrg_projectlistbx,#USRC_UPD_aftern_projectlistbx,#USRC_UPD_lb_tableafternproj,#USRC_UPD_tble_frstsel_projectlistbx,#USRC_UPD_btn_submit").html('')
                $('#USRC_UPD_btn_srch').show();
                $('#USRC_UPD_errmsg').hide();
                $('#USRC_UPD_lbl_dte').hide();
                $('#USRC_UPD_tb_date').hide();
                $("#USRC_UPD_btn_srch").removeAttr("disabled");
                $('#USRC_UPD_rd_permission').hide();
                $('#USRC_UPD_lbl_permission').hide();
                $('#USRC_UPD_rd_nopermission').hide();
                $('#USRC_UPD_lbl_nopermission').hide();
                $('#USRC_UPD_lbl_session').hide();
                $('#USRC_UPD_ta_report').hide();
                $('#USRC_UPD_tb_band').hide();
                $('#USRC_UPD_ta_reason').hide();
                $('#USRC_UPD_tble_attendence').hide();
                $('#USRC_UPD_lbl_band').hide();
                $('#USRC_UPD_lbl_reason').hide();
                $('#USRC_UPD_lbl_report').hide();
                $('#USRC_UPD_btn_submit').hide();
                $('#USRC_UPD_lb_timing').hide();
                $('#USRC_UPD_lb_ampm').hide();
                $('#USRC_UPD_lbl_txtselectproj').hide();
                $('#USRC_UPD_tble_projectlistbx').hide();
                $('#USRC_UPD_banderrmsg').hide();

            });
            // CLICK EVENT FOR SEACH BUTTON
            var attendance;
            var date;
            var report;
            var userstamp;
            var timestamp;
            var reason;
            var permission;
            var pdid;
            var morningsession;
            var afternoonsession;
            var bandwidth;
            var projectid_array;
            $(document).on('click','#USRC_UPD_btn_srch',function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                clear();
                $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                var SRC_UPD__idradiovalue=$('input:radio[name=USRC_UPD_rd_flxtbl]:checked').attr('id');
                $("#USRC_UPD_btn_srch").attr("disabled", "disabled");
                $('#USRC_UPD_lbl_txtselectproj').hide();
                for(var j=0;j<values_array.length;j++){
                    var id=values_array[j].id;
                    if(id==SRC_UPD__idradiovalue)
                    {
                        date=  values_array[j].date;
                        report=values_array[j].report1;
                        userstamp=values_array[j].userstamp;
                        timestamp=values_array[j].timestamp;
                        reason=values_array[j].reason1;
                        permission=values_array[j].permission;
                        attendance=values_array[j].attendance;
                        pdid=values_array[j].pdid;
                        morningsession=values_array[j].morningsession;
                        afternoonsession=values_array[j].afternoonsession;
                        bandwidth=values_array[j].bandwidth;
                        if(attendance=='1')
                        {
                            var permission_list='<option>SELECT</option>';
                            for (var i=0;i<permission_array.length;i++) {
                                permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                            }
                            $('#USRC_UPD_lb_timing').html(permission_list);
                        }
                        else if((attendance=='0.5') ||(attendance=='0.5OD'))
                        {
                            var permission_list='<option>SELECT</option>';
                            for (var i=0;i<4;i++) {
                                permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                            }
                            $('#USRC_UPD_lb_timing').html(permission_list);
                        }

                        if(attendance=='WORK FROM HOME')
                        {
                            $('#USRC_UPD_lb_attendance').replaceWith(
                                "<select id='USRC_UPD_lb_attendance' name='USRC_UPD_lb_attendance' class='update_validate form-control'> <option value='2'>WORK FROM HOME</option> </select>");
                        }
                        else
                        {
                            $('#USRC_UPD_lb_attendance').replaceWith(
                                "<select id='USRC_UPD_lb_attendance' name='USRC_UPD_lb_attendance' class='update_validate form-control'> <option value='1'>PRESENT</option><option value='0'>ABSENT</option><option value='OD'>ONDUTY</option></select>");
                        }
                        $('#USRC_UPD_tble_attendence').show();
                        form_show(attendance)

                    }
                }
            });

            $(document).on('click','.paginate_button',function(){
                UARD_clear();
                $("#USRC_UPD_tb_date").val('').hide()
                $('#USRC_UPD_lbl_dte').hide();
                $('input:radio[name=USRC_UPD_rd_flxtbl]').attr('checked',false);


            });
            // FUNCTION FOR PROJECTID CHECKED
            function projecdid(){
                for(var i=0;i<project_array.length;i++){
                    for(var j=0;j<projectid_array.length;j++){
                        if(projectid_array[j]==project_array[i][1]){
                            $("#" + project_array[i][1]+'p').prop( "checked", true );
                        }
                    }
                }
            }
            // FUNCTION FOR FORM SHOW
            function form_show(attendance){
                if(attendance=='1')
                {
                    projectid_array=pdid.split(",");
                    $("#USRC_UPD_rd_permission").attr("checked", false);
                    $("#USRC_UPD_rd_nopermission").attr("checked", false);
                    $('#USRC_UPD_rd_permission').removeAttr("disabled");
                    $('#USRC_UPD_rd_nopermission').removeAttr("disabled");
                    $('#permission_hide').show();
                    $('#USRC_UPD_lbl_dte').show();
                    $('#USRC_UPD_tb_date').show();
                    $('#USRC_UPD_tb_date').val(date);
                    $('#USRC_UPD_lb_attendance').val('1');
                    $('#USRC_UPD_rd_permission').show();
                    $('#USRC_UPD_lbl_permission').show();
                    $('#USRC_UPD_rd_nopermission').show();
                    $('#USRC_UPD_lbl_nopermission').show();
                    $('#USRC_UPD_lbl_session').hide();
                    $('#USRC_UPD_lb_ampm').hide();
                    $('#USRC_UPD_tble_projectlistbx').show();
                    $('#USRC_UPD_lbl_txtselectproj').show();
                    USRC_UPD_report()
                    $('#USRC_UPD_ta_report').val(report);
//            USRC_UPD_tble_bandwidth()
                    $('#USRC_UPD_tble_bandwidth').show()
                    $(' <div class="row-fluid"><label name="USRC_UPD_lbl_band" class="col-sm-2" id="USRC_UPD_lbl_band" >BANDWIDTH<em>*</em></label><div class="col-sm-8"><input type="text" name="USRC_UPD_tb_band" id="USRC_UPD_tb_band" class="autosize amountonly update_validate" style="width:75px;"><label name="USRC_UPD_lbl_band" id="USRC_UPD_lbl_band">MB</label></div></div>').appendTo($("#USRC_UPD_tble_bandwidth"));
                    $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:4,imaginary:2}});
                    $('#USRC_UPD_tb_band').val(bandwidth);
                    $('#USRC_UPD_btn_submit').show();
                    var project_list;
                    for (var i=0;i<project_array.length;i++) {
                        project_list += '<tr><td><input type="checkbox" id="' + project_array[i][1] +'p'+ '" class="update_validate" name="checkbox[]" value="' + project_array[i][1] + '" >' + project_array[i][0] +' - '+ project_array[i][2]+ '</td></tr>';
                    }
                    $('#USRC_UPD_tble_frstsel_projectlistbx').append(project_list).show();
                    $('#search_update').show();
                    for(var i=0;i<project_array.length;i++){
                        for(var j=0;j<projectid_array.length;j++){
                            if(projectid_array[j]==project_array[i][1]){
                                $("#" + project_array[i][1]+'p').prop( "checked", true );
                            }
                        }
                    }
//            projectlist();
//            projecdid();
//            $('#USRC_UPD_rd_permission').attr('disabled','enabled');
//            $('#USRC_UPD_rd_nopermission').attr('disabled','enabled');
                }
                else if(attendance=='WORK FROM HOME')
                {
                    projectid_array=pdid.split(",");
                    $('#USRC_UPD_lbl_dte').show();
                    $('#USRC_UPD_tb_date').show();
                    $('#USRC_UPD_tb_date').val(date);
                    $('#USRC_UPD_lb_attendance').val('2');
                    $('#USRC_UPD_rd_permission').show();
                    $('#USRC_UPD_lbl_permission').show();
                    $('#permission_hide').hide();
                    $('#USRC_UPD_rd_nopermission').show();
                    $('#USRC_UPD_lbl_nopermission').show();
                    $('#noopermission').show();
                    $('#USRC_UPD_lbl_session').hide();
                    $('#USRC_UPD_lb_ampm').hide();
                    $('#USRC_UPD_tble_projectlistbx').show();
                    $('#USRC_UPD_lbl_txtselectproj').show();
                    projectlist();
                    projecdid();
                    USRC_UPD_report()
                    $('#USRC_UPD_ta_report').val(report);
//            USRC_UPD_tble_bandwidth()
//            $('#USRC_UPD_tb_band').val(bandwidth);
                    $('#USRC_UPD_btn_submit').show();
                    $('#USRC_UPD_rd_permission').removeAttr("disabled");
                    $('#USRC_UPD_rd_nopermission').removeAttr("disabled");

                }
                else if(attendance=='0')
                {
                    $('#USRC_UPD_lbl_dte').show();
                    $('#USRC_UPD_tb_date').show();
                    $('#USRC_UPD_tb_date').val(date);
                    $('#USRC_UPD_lb_attendance').val('0');
                    $('#USRC_UPD_rd_permission').hide();
                    $('#USRC_UPD_lbl_permission').hide();
                    $('#permission_hide').hide();
                    $('#USRC_UPD_rd_nopermission').hide();
                    $('#USRC_UPD_lbl_nopermission').hide();
                    $('#USRC_UPD_lbl_session').show();
                    $('#USRC_UPD_lb_ampm').show();
                    $('#USRC_UPD_lb_ampm').val("FULLDAY");
//            USRC_UPD_reason()
                    $('#USRC_UPD_tble_reasonlbltxtarea').show()
                    $( '<div class="row-fluid" style="padding-top: 10px"><label name="USRC_UPD_lbl_reason" class="col-sm-2" id="USRC_UPD_lbl_reason" >REASON<em>*</em></label><div class="col-lg-10"><textarea  name="USRC_UPD_ta_reason" id="USRC_UPD_ta_reason" class="tarea form-control update_validate" ></textarea></div></div>').appendTo($("#USRC_UPD_tble_reasonlbltxtarea"));
                    $('textarea').autogrow({onInitialize: true});
                    $('#USRC_UPD_ta_reason').val(reason).show();
                    $('#USRC_UPD_btn_submit').show();
                    $('#USRC_UPD_rd_permission').attr('disabled','disabled');
                    $('#USRC_UPD_rd_nopermission').attr('disabled','disabled');
                }
                else if(attendance=='0.5')
                {
                    projectid_array=pdid.split(",");
                    $('#USRC_UPD_lbl_dte').show();
                    $('#USRC_UPD_tb_date').show();
                    $('#USRC_UPD_tb_date').val(date);
                    $('#USRC_UPD_lb_attendance').val('0');
                    $('#USRC_UPD_rd_permission').show();
                    $('#USRC_UPD_lbl_permission').show();
                    $('#USRC_UPD_rd_nopermission').show();
                    $('#USRC_UPD_lbl_nopermission').show();
                    $('#USRC_UPD_rd_permission').removeAttr("disabled");
                    $('#USRC_UPD_rd_nopermission').removeAttr("disabled");
                    $('#USRC_UPD_lbl_session').show();
                    $('#USRC_UPD_lb_ampm').show();
                    if((morningsession=='PRESENT') && (afternoonsession=='ABSENT'))
                    {
                        $('#USRC_UPD_lb_ampm').val('PM');
                        $('#USRC_UPD_tble_projectlistbx').show();
                        $('#USRC_UPD_lbl_txtselectproj').show();
                        projectlist();
                        projecdid();
                    }
                    else if((morningsession=='ABSENT' && afternoonsession=='PRESENT'))
                    {
                        $('#USRC_UPD_lb_ampm').val('AM');
                        $('#USRC_UPD_tble_projectlistbx').show();
                        $('#USRC_UPD_lbl_txtselectproj').show();
                        projectlist();
                        projecdid();
                    }
                    USRC_UPD_reason()
                    $('#USRC_UPD_ta_reason').val(reason).show();
                    USRC_UPD_report()
                    $('#USRC_UPD_ta_report').val(report);
                    USRC_UPD_tble_bandwidth()
                    $('#USRC_UPD_tb_band').val(bandwidth);
                    $('#USRC_UPD_btn_submit').show();
                }
                else if(attendance=='OD')
                {
                    $('#USRC_UPD_lb_attendance').val('OD');
                    $('#USRC_UPD_lbl_dte').show();
                    $('#USRC_UPD_tb_date').show();
                    $('#USRC_UPD_tb_date').val(date);
                    $('#USRC_UPD_rd_permission').hide();
                    $('#USRC_UPD_lbl_permission').hide();
                    $('#permission_hide').hide();
                    $('#USRC_UPD_lb_timing').hide();
                    $('#USRC_UPD_rd_nopermission').hide();
                    $('#USRC_UPD_lbl_nopermission').hide();
                    $('#USRC_UPD_lbl_session').show();
                    $('#USRC_UPD_lb_ampm').show();
                    $('#USRC_UPD_lb_ampm').val("FULLDAY");
//            USRC_UPD_reason()
                    $('#USRC_UPD_tble_reasonlbltxtarea').show()
                    $('#search_update').show()
                    //FUNCTION FOR REASON
//            function USRC_UPD_reason(){
                    $( '<div class="row-fluid" style="padding-top: 10px"><label name="USRC_UPD_lbl_reason" class="col-sm-2" id="USRC_UPD_lbl_reason" >REASON<em>*</em></label><div class="col-lg-10"><textarea  name="USRC_UPD_ta_reason" id="USRC_UPD_ta_reason" class="tarea form-control update_validate" ></textarea></div></div>').appendTo($("#USRC_UPD_tble_reasonlbltxtarea"));
                    $('textarea').autogrow({onInitialize: true});
                    $('#USRC_UPD_ta_reason').val(reason).show();
                    $('#USRC_UPD_btn_submit').show();
                    $('#USRC_UPD_rd_permission').attr('disabled','disabled');
                    $('#USRC_UPD_rd_nopermission').attr('disabled','disabled');
                }
                else if(attendance=='0.5OD')
                {
                    projectid_array=pdid.split(",");
                    $('#USRC_UPD_lbl_dte').show();
                    $('#USRC_UPD_tb_date').show();
                    $('#USRC_UPD_tb_date').val(date);
                    $('#USRC_UPD_lb_attendance').val('OD');
                    $('#USRC_UPD_rd_permission').show();
                    $('#USRC_UPD_lbl_permission').show();
                    $('#USRC_UPD_rd_nopermission').show();
                    $('#USRC_UPD_lbl_nopermission').show();
                    $('#USRC_UPD_rd_permission').removeAttr("disabled");
                    $('#USRC_UPD_rd_nopermission').removeAttr("disabled");
                    $('#USRC_UPD_lbl_session').show();
                    $('#USRC_UPD_lb_ampm').show();
                    if((morningsession=='PRESENT') && (afternoonsession=='ONDUTY'))
                    {
                        $('#USRC_UPD_lb_ampm').val('PM');
                        $('#USRC_UPD_tble_projectlistbx').show();
                        $('#USRC_UPD_lbl_txtselectproj').show();
                        projectlist();
                        projecdid();
                    }
                    else if((morningsession=='ONDUTY' && afternoonsession=='PRESENT'))
                    {
                        $('#USRC_UPD_lb_ampm').val('AM');
                        $('#USRC_UPD_tble_projectlistbx').show();
                        $('#USRC_UPD_lbl_txtselectproj').show();
                        projectlist();
                        projecdid();
                    }
                    $('#USRC_UPD_tble_bandwidth').show()
                    $(' <div class="row-fluid"><label name="USRC_UPD_lbl_band" class="col-sm-2" id="USRC_UPD_lbl_band" >BANDWIDTH<em>*</em></label><div class="col-sm-8"><input type="text" name="USRC_UPD_tb_band" id="USRC_UPD_tb_band" class="autosize amountonly update_validate" style="width:75px;"><label name="USRC_UPD_lbl_band" id="USRC_UPD_lbl_band">MB</label></div></div>').appendTo($("#USRC_UPD_tble_bandwidth"));
                    $('#USRC_UPD_tb_band').val(bandwidth);
                    $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:4,imaginary:2}});
                    USRC_UPD_reason()
                    $('#USRC_UPD_ta_reason').val(reason).show();
                    USRC_UPD_report()
                    $('#USRC_UPD_ta_report').val(report);
//            USRC_UPD_tble_bandwidth()
                    $('#USRC_UPD_btn_submit').show();
                }
                if(permission!=null)
                {
                    $('#USRC_UPD_rd_permission').attr('checked','checked');
                    $('#USRC_UPD_lb_timing').show();
                    $('#USRC_UPD_lb_timing').val(permission).show();
                }
                else
                {
                    $('#USRC_UPD_rd_nopermission').attr('checked','checked');
                }
            }
            var err_flag=0;
            // CHANGE EVENT FOR DATE ALREADY EXISTS
            $(document).on('change ','#USRC_UPD_tb_date',function(){
                var reportdate=$('#USRC_UPD_tb_date').val();
                if(date!=reportdate){
                    $(".preloader").show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            var msgalert=xmlhttp.responseText;
                            $(".preloader").hide();
                            if(msgalert==1)
                            {
                                err_flag=1;
                                var msg=err_msg[3].toString().replace("[DATE]",reportdate);
                                $('#USRC_UPD_errmsg').text(msg).show();
                                $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                            }
                            else
                            {
                                err_flag=0;
                                $('#USRC_UPD_errmsg').hide();
                                USRC_UPD_form_validation();
                            }
                        }
                    }
                    var option="DATE";
                    xmlhttp.open("GET","USER/DB_DAILY_REPORTS_USER_SEARCH_UPDATE.do?date_change="+reportdate+"&option="+option);
                    xmlhttp.send();
                }
                else{
                    err_flag=0;
                    $('#USRC_UPD_errmsg').hide();
                }
            });
// CHANGE EVENT FOR ATTENDANCE
            $(document).on('change','#USRC_UPD_lb_attendance',function(){
//            $('#USRC_UPD_lb_attendance').change(function(){
                err_flag=0;
                $('#USRC_UPD_lbl_txtselectproj').hide();
                $('#permission_hide').show();
                if(attendance==$('#USRC_UPD_lb_attendance').val())
                {
                    $('#USRC_UPD_tble_reasonlbltxtarea').html('');
                    $('#USRC_UPD_tble_frstsel_projectlistbx').html('');
                    $('#USRC_UPD_tble_enterthereport').html('');
                    $('#USRC_UPD_tble_bandwidth').html('');
                    $('#USRC_UPD_lb_timing').hide();
                    $('#USRC_UPD_lbl_permission').hide();
                    $('#USRC_UPD_rd_permission').show();
                    $('#USRC_UPD_lbl_nopermission').hide();
                    $('#USRC_UPD_rd_nopermission').show();
                    form_show(attendance)
                    $('#USRC_UPD_btn_submit').attr('disabled','disabled');
                    $('#USRC_UPD_banderrmsg').hide();
                }
                else
                {
                    projectid_array='';
                    $('#USRC_UPD_tble_frstsel_projectlistbx').html('');
                    $('#USRC_UPD_btn_submit').attr('disabled','disabled');
                    $('#USRC_UPD_tble_reasonlbltxtarea').html('');
                    if($('#USRC_UPD_lb_attendance').val()=='1')
                    {
                        $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                        $('#USRC_UPD_tble_enterthereport,#USRC_UPD_ta_reason,#USRC_UPD_tble_bandwidth').html('');
//                $('#USRC_UPD_rd_permission').removeAttr("disabled","disabled");
//                $('#USRC_UPD_rd_nopermission').removeAttr("disabled","disabled");
                        $("#USRC_UPD_rd_permission").attr("checked", false);
                        $("#USRC_UPD_rd_nopermission").attr("checked", false);
                        $('#USRC_UPD_lb_timing').hide();
                        $('#USRC_UPD_lbl_permission').show();
                        $('#USRC_UPD_rd_permission').show();
                        $('#USRC_UPD_lbl_nopermission').show();
                        $('#USRC_UPD_rd_nopermission').show();
                        var permission_list='<option>SELECT</option>';
                        for (var i=0;i<permission_array.length;i++) {
                            permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                        }
                        $('#USRC_UPD_lb_timing').html(permission_list);
                        $('#USRC_UPD_lbl_session').hide();
                        $('#USRC_UPD_lb_ampm').hide();
                        $('#USRC_UPD_tble_projectlistbx').show();
                        $('#USRC_UPD_lbl_txtselectproj').show();
                        $("#USRC_UPD_tble_bandwidth").show();
//                USRC_UPD_tble_bandwidth();
                        $(' <div class="row-fluid "><label name="USRC_UPD_lbl_band" class="col-sm-2" id="USRC_UPD_lbl_band" >BANDWIDTH<em>*</em></label><div class="col-sm-8"><input type="text" name="USRC_UPD_tb_band" id="USRC_UPD_tb_band" class="autosize amountonly update_validate" style="width:75px;"><label name="USRC_UPD_lbl_band" id="USRC_UPD_lbl_band">MB</label></div></div>').appendTo($("#USRC_UPD_tble_bandwidth"));
                        $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:4,imaginary:2}});
                        $('#USRC_UPD_btn_submit').hide();
                        $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
                        $('#permission_hide').show();
                        $('#USRC_UPD_rd_permission').removeAttr("disabled");
                        $('#USRC_UPD_rd_nopermission').removeAttr("disabled");
                        $('#USRC_UPD_errmsg').hide();
                        $('#USRC_UPD_banderrmsg').hide();
                        projectlist();
                        USRC_UPD_report();
                    }
                    else if($('#USRC_UPD_lb_attendance').val()=='0')
                    {
                        $('#USRC_UPD_rd_permission').attr('checked',false);
                        $('#USRC_UPD_rd_nopermission').attr('checked',false);
                        $('#USRC_UPD_lb_timing').hide();
                        $('#permission_hide').show();
                        //   $('#USRC_UPD_lbl_txtselectproj').hide();
                        $('#USRC_UPD_lbl_permission').show();
                        $('#USRC_UPD_rd_permission').show();
                        $('#USRC_UPD_lbl_nopermission').show();
                        $('#USRC_UPD_rd_nopermission').show();
                        var permission_list='<option>SELECT</option>';
                        for (var i=0;i<4;i++) {
                            permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                        }
                        $('#USRC_UPD_lb_timing').html(permission_list);
                        $('#USRC_UPD_lbl_session').show();
                        $('#USRC_UPD_lb_ampm').val('SELECT').show();
                        $('#USRC_UPD_tble_projectlistbx').hide();
                        $('#USRC_UPD_tble_reasonlbltxtarea').html('');
                        $('#USRC_UPD_tble_enterthereport').html('');
                        $('#USRC_UPD_tble_bandwidth').html('');
                        $('#USRC_UPD_btn_submit').hide();
                        $('#USRC_UPD_rd_permission').attr('disabled','disabled');
                        $('#USRC_UPD_rd_nopermission').attr('disabled','disabled');
                        $('#USRC_UPD_errmsg').hide();
                        $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
                        $('#USRC_UPD_banderrmsg').hide();
                    }
                    else if($('#USRC_UPD_lb_attendance').val()=='2')
                    {
                        $('#USRC_UPD_lbl_permission').hide();
                        $('#USRC_UPD_rd_permission').hide();
                        $('#USRC_UPD_lbl_nopermission').hide();
                        $('#USRC_UPD_rd_nopermission').hide();
                        $('#USRC_UPD_lbl_session').hide();
                        $('#USRC_UPD_lb_ampm').hide();
                        $('#USRC_UPD_tble_projectlistbx').show();
                        $('#USRC_UPD_lbl_txtselectproj').show();
                        $('#USRC_UPD_tble_enterthereport').html('');
                        $('#USRC_UPD_tble_bandwidth').html('');
                        projectlist();
                        USRC_UPD_report();
                        $('#USRC_UPD_btn_submit').hide();
                        $('#USRC_UPD_rd_permission').removeAttr("disabled");
                        $('#USRC_UPD_rd_nopermission').removeAttr("disabled");
                        $('#USRC_UPD_errmsg').hide();
                        $('#USRC_UPD_banderrmsg').hide();
                    }
                    else if($('#USRC_UPD_lb_attendance').val()=='OD')
                    {
                        $('#USRC_UPD_rd_permission').attr('checked',false);
                        $('#USRC_UPD_rd_nopermission').attr('checked',false);
                        $('#USRC_UPD_lb_timing').hide();
                        $('#USRC_UPD_lbl_permission').show();
                        $('#USRC_UPD_rd_permission').show();
                        $('#USRC_UPD_lbl_nopermission').show();
                        $('#USRC_UPD_rd_nopermission').show();
                        var permission_list='<option>SELECT</option>';
                        for (var i=0;i<4;i++) {
                            permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                        }
                        $('#USRC_UPD_lb_timing').html(permission_list);
                        $('#USRC_UPD_lbl_session').show();
                        $('#USRC_UPD_lb_ampm').val('SELECT').show();
                        $('#USRC_UPD_tble_projectlistbx').hide();
                        $('#USRC_UPD_tble_reasonlbltxtarea').html('');
                        $('#USRC_UPD_tble_enterthereport').html('');
                        $('#USRC_UPD_tble_bandwidth').html('');
                        $('#USRC_UPD_btn_submit').hide();
                        $('#USRC_UPD_rd_permission').attr('disabled','disabled');
                        $('#USRC_UPD_rd_nopermission').attr('disabled','disabled');
                        $('#USRC_UPD_errmsg').hide();
                        $('#USRC_UPD_banderrmsg').hide();
                    }
                }
            });
// CLICK EVENT PERMISSION RADIO BTN
            $(document).on('click','#USRC_UPD_rd_permission',function()
            {
                if($('#USRC_UPD_rd_permission').attr("checked","checked"))
                {
                    $('#USRC_UPD_lb_timing').val('SELECT').show();
                }
                else
                {
                    $('#USRC_UPD_lb_timing').hide();
                    $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
                }
            });
            // CLICK EVENT NOPERMISSION RADIO BTN
            $(document).on('click','#USRC_UPD_rd_nopermission',function()
            {
                $('#USRC_UPD_lb_timing').hide();
                $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
            });
            // FUNCTION FOR CLEAR
            function clear(){
                $('#USRC_UPD_tble_attendence').hide();
                $('#USRC_UPD_tble_reasonlbltxtarea').html('');
                $('#USRC_UPD_tble_frstsel_projectlistbx').html('');
                $('#USRC_UPD_tble_enterthereport').html('');
                $('#USRC_UPD_tble_bandwidth').html('');
                $('#USRC_UPD_btn_submit').html('');
                $('#USRC_UPD_lbl_session').hide();
                $('#USRC_UPD_lbl_permission').hide();
                $('#USRC_UPD_rd_permission').hide();
                $('#USRC_UPD_lbl_nopermission').hide();
                $('#USRC_UPD_rd_nopermission').hide();
                $('#USRC_UPD_lb_timing').hide();
                $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
                $('#USRC_UPD_lb_ampm').hide();
                $('#USRC_UPD_btn_submit').hide();
                $('#USRC_UPD_tble_projectlistbx').hide();
                $('#USRC_UPD_banderrmsg').hide();
            }
            // CHANGE EVENT SESSION LISTBX
            $(document).on('change','#USRC_UPD_lb_ampm',function(){
                projectid_array='';
                $('#USRC_UPD_tble_reasonlbltxtarea,#USRC_UPD_tble_enterthereport,#USRC_UPD_tble_bandwidth,#USRC_UPD_tble_frstsel_projectlistbx').html('');
                if($('#USRC_UPD_lb_ampm').val()=='SELECT')
                {
                    $('#USRC_UPD_tble_reasonlbltxtarea').html('');
                    $('#USRC_UPD_tble_frstsel_projectlistbx').html('');
                    $('#USRC_UPD_tble_enterthereport').html('');
                    $('#USRC_UPD_tble_projectlistbx').hide();
                    $('#USRC_UPD_tble_bandwidth').html('');
                    $('#USRC_UPD_btn_submit').hide();
                    $('#USRC_UPD_banderrmsg').hide();
                    $('#permission_hide').hide();
                }
                else if($('#USRC_UPD_lb_ampm').val()=='FULLDAY')
                {
//            USRC_UPD_reason();
                    $('#permission_hide').hide();
                    $('#USRC_UPD_tble_reasonlbltxtarea').show()
                    $('#search_update').show()
                    //FUNCTION FOR REASON
//            function USRC_UPD_reason(){
                    $( '<div class="row-fluid" style="padding-top: 10px"><label name="USRC_UPD_lbl_reason" class="col-sm-2" id="USRC_UPD_lbl_reason" >REASON<em>*</em></label><div class="col-lg-10"><textarea  name="USRC_UPD_ta_reason" id="USRC_UPD_ta_reason" class="tarea form-control update_validate" ></textarea></div></div>').appendTo($("#USRC_UPD_tble_reasonlbltxtarea"));
//            }
                    $('textarea').autogrow({onInitialize: true});
                    $('#USRC_UPD_tble_projectlistbx').hide();

                    $('#USRC_UPD_rd_permission').attr('checked',false);
                    $('#USRC_UPD_rd_nopermission').attr('checked',false);
                    $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
                    $('#USRC_UPD_rd_permission').attr('disabled','disabled');
                    $('#USRC_UPD_rd_nopermission').attr('disabled','disabled');
                    $('#USRC_UPD_lb_timing').hide();
                    $('#USRC_UPD_lbl_permission').hide();
                    $('#USRC_UPD_rd_permission').hide();
                    $('#USRC_UPD_lbl_nopermission').hide();
                    $('#USRC_UPD_rd_nopermission').hide();
                    $('#USRC_UPD_btn_submit').show();
                    $('#USRC_UPD_banderrmsg').hide();
                }
                else
                {
                    $('#USRC_UPD_btn_submit').attr("disabled","disabled")
                    $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                    $('#USRC_UPD_rd_permission').attr('checked',false);
                    $('#USRC_UPD_rd_nopermission').attr('checked',false);
                    $('#permission_hide').show();
                    $('#USRC_UPD_rd_permission').removeAttr("disabled");
                    $('#USRC_UPD_rd_nopermission').removeAttr("disabled");
                    $('#USRC_UPD_lbl_permission').show();
                    $('#USRC_UPD_rd_permission').show();
                    $('#USRC_UPD_lbl_nopermission').show();
                    $('#USRC_UPD_rd_nopermission').show();
                    USRC_UPD_reason();
                    $('#USRC_UPD_tble_projectlistbx').show();
                    projectlist();
                    USRC_UPD_report();
//                USRC_UPD_tble_bandwidth();
                    $(' <div class="row-fluid"><label name="USRC_UPD_lbl_band" class="col-sm-2" id="USRC_UPD_lbl_band" >BANDWIDTH<em>*</em></label><div class="col-sm-8"><input type="text" name="USRC_UPD_tb_band" id="USRC_UPD_tb_band" class="autosize amountonly update_validate" style="width:75px;"><label name="USRC_UPD_lbl_band" id="USRC_UPD_lbl_band">MB</label></div></div>').appendTo($("#USRC_UPD_tble_bandwidth"));
                    $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:4,imaginary:2}});
                    $('#USRC_UPD_lb_timing').hide();
                    var permission_list='<option>SELECT</option>';
                    for (var i=0;i<4;i++) {
                        permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                    }
                    $('#USRC_UPD_lb_timing').html(permission_list);
                    $('#USRC_UPD_lbl_txtselectproj').show();
                    $('#USRC_UPD_btn_submit').hide();
                    $('#USRC_UPD_lb_timing').prop('selectedIndex',0);
                    $('#USRC_UPD_banderrmsg').hide();
                }
            });
// CHANGE EVENT FOR REPORT TEXTAREA
            $(document).on('change','#USRC_UPD_ta_report',function(){
                $('#USRC_UPD_btn_submit').show();
                $('#USRC_UPD_btn_submit').attr('disabled','disabled');
            });
            //CHANGE EVENT FOR BANDWIDTH TEXTBX
            $(document).on('change blur','#USRC_UPD_tb_band',function(){
                var bandwidth=$('#USRC_UPD_tb_band').val();
                if(bandwidth > 1000)
                {
                    var msg=err_msg[9].toString().replace("[BW]",bandwidth);
                    $('#USRC_UPD_banderrmsg').text(msg).show();
                }
                else
                {
                    $('#USRC_UPD_banderrmsg').hide();
                }
            });
            //FUNCTION FOR PROJECT LIST
            function projectlist(){
                var project_list;
                for (var i=0;i<project_array.length;i++) {
                    project_list += '<tr><td><input type="checkbox" id="' + project_array[i][1] +'p'+ '" class="update_validate" name="checkbox[]" value="' + project_array[i][1] + '" >' + project_array[i][0] +' - '+ project_array[i][2]+ '</td></tr>';
                }
                $('#USRC_UPD_tble_frstsel_projectlistbx').append(project_list).show();
            }
            //FUNCTION FOR REASON
            function USRC_UPD_reason(){
                $( '<div class="row-fluid" style="padding-top: 10px"><label name="USRC_UPD_lbl_reason" class="col-sm-2" id="USRC_UPD_lbl_reason" >REASON<em>*</em></label><div class="col-lg-10"><textarea  name="USRC_UPD_ta_reason" id="USRC_UPD_ta_reason" class="tarea form-control update_validate" ></textarea></div></div>').appendTo($("#USRC_UPD_tble_reasonlbltxtarea"));
                $('textarea').autogrow({onInitialize: true});
            }
            // FUNCTIO FOR REPORT
            function USRC_UPD_report(){
                $(' <div class="row-fluid" style="padding-top: 10px"><label name="USRC_UPD_lbl_report" class="col-sm-2" id="USRC_UPD_lbl_report" >ENTER THE REPORT<em>*</em></label><div class="col-lg-10"><textarea  name="USRC_UPD_ta_report" id="USRC_UPD_ta_report" class="tarea form-control update_validate" ></textarea></div></div>').appendTo($("#USRC_UPD_tble_enterthereport"));
                $('textarea').autogrow({onInitialize: true});
            }
            // FUNCTION FOR BANDWIDTH
            function USRC_UPD_tble_bandwidth(){
                $(' <div class="row-fluid"><label name="USRC_UPD_lbl_band" class="col-sm-2" id="USRC_UPD_lbl_band" >BANDWIDTH<em>*</em></label><div class="col-sm-8"><input type="text" name="USRC_UPD_tb_band" id="USRC_UPD_tb_band" class="autosize amountonly update_validate" style="width:75px;"><label name="USRC_UPD_lbl_band" id="USRC_UPD_lbl_band">MB</label></div></div>').appendTo($("#USRC_UPD_tble_bandwidth"));
                $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:4,imaginary:2}});
            }
            //FORM VALIDATION
            $(document).on('change blur','.update_validate',function(){
                USRC_UPD_form_validation();
            });
            function USRC_UPD_form_validation(){
                var USRC_UPD_sessionlstbx= $("#USRC_UPD_lb_ampm").val();
                var USRC_UPD_reasontxtarea =$("#USRC_UPD_ta_reason").val();
                var USRC_UPD_reportenter =$("#USRC_UPD_ta_report").val();
                var USRC_UPD_bndtxt = $("#USRC_UPD_tb_band").val();
                var USRC_UPD_projectselectlistbx=$('input[name="checkbox[]"]:checked').length;
                var USRC_UPD_permissionlstbx = $("#USRC_UPD_lb_timing").val();
                var USRC_UPD_permission=$("input[name=permission]:checked").val()=="PERMISSION";
                var USRC_UPD_nopermission=$("input[name=permission]:checked").val()=="NOPERMISSION";
                var USRC_UPD_presenthalfdysvld=$("#USRC_UPD_lb_attendance").val();
                if(err_flag!=1){
                    if(((USRC_UPD_presenthalfdysvld=='0') && (USRC_UPD_sessionlstbx=='AM' || USRC_UPD_sessionlstbx=="PM")) || ((USRC_UPD_presenthalfdysvld=='OD') && (USRC_UPD_sessionlstbx=='AM' || USRC_UPD_sessionlstbx=="PM") ))
                    {
                        if(((USRC_UPD_reasontxtarea.trim()!="")&&(USRC_UPD_reportenter!='')&&( USRC_UPD_projectselectlistbx>0) && (USRC_UPD_bndtxt!='')&& (parseFloat(USRC_UPD_bndtxt)!=0)&&(USRC_UPD_bndtxt<=1000) &&((USRC_UPD_permission==true) || (USRC_UPD_nopermission==true))))
                        {
                            if(USRC_UPD_permission==true)
                            {
                                if(USRC_UPD_permissionlstbx!='SELECT')
                                {
                                    $("#USRC_UPD_btn_submit").removeAttr("disabled");
                                }
                                else
                                {
                                    $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                                }
                            }
                            else
                            {
                                $("#USRC_UPD_btn_submit").removeAttr("disabled");
                            }
                        }
                        else
                        {
                            $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                        }
                    }
                    else if((USRC_UPD_presenthalfdysvld=='0' && USRC_UPD_sessionlstbx=='FULLDAY') || (USRC_UPD_presenthalfdysvld=='OD' && USRC_UPD_sessionlstbx=='FULLDAY'))
                    {
                        if(USRC_UPD_reasontxtarea.trim()=="")
                        {
                            $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                        }
                        else
                        {
                            $("#USRC_UPD_btn_submit").removeAttr("disabled");
                        }
                    }
                    else if(USRC_UPD_presenthalfdysvld=='1')
                    {
                        if(((USRC_UPD_reportenter.trim()!="")&&(USRC_UPD_bndtxt!='') && (parseFloat(USRC_UPD_bndtxt)!=0) && (USRC_UPD_bndtxt<=1000) &&( USRC_UPD_projectselectlistbx>0) && ((USRC_UPD_permission==true) || (USRC_UPD_nopermission==true))))
                        {
                            if(USRC_UPD_permission==true)
                            {
                                if(USRC_UPD_permissionlstbx!='SELECT')
                                {
                                    $("#USRC_UPD_btn_submit").removeAttr("disabled");
                                }
                                else
                                {
                                    $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                                }
                            }
                            else
                            {
                                $("#USRC_UPD_btn_submit").removeAttr("disabled");
                            }
                        }
                        else
                        {
                            $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                        }
                    }
                    else if(USRC_UPD_presenthalfdysvld=='2')
                    {
                        if(((USRC_UPD_reportenter.trim()!="")))
                        {
                            if( USRC_UPD_projectselectlistbx==true)
                            {
                                if( USRC_UPD_projectselectlistbx!='SELECT')
                                {
                                    $("#USRC_UPD_btn_submit").removeAttr("disabled");
                                }
                                else
                                {
                                    $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                                }
                            }
                            else
                            {
                                $("#USRC_UPD_btn_submit").removeAttr("disabled");
                            }
                        }
                        else
                        {
                            $("#USRC_UPD_btn_submit").attr("disabled", "disabled");
                        }
                    }
                }
            }
            // CHANGE EVENT FOR UPDATE BUTTON
            $(document).on('click','#USRC_UPD_btn_submit',function(evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                $(".preloader").show();
                formElement = document.getElementById("URE_form_dailyuserentry");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        msg_alert=xmlhttp.responseText;
                        if(msg_alert==1)
                        {
                            show_msgbox("USER SEARCH AND UPDATE",err_msg[1],"success",false);
                            clear();
                            flextable();
                            $(".preloader").hide();
                            $("#USRC_UPD_tb_date").val('').hide()
                            $('#USRC_UPD_lbl_dte').hide();
                        }
                        else if(msg_alert==0)
                        {
                            show_msgbox("USER SEARCH AND UPDATE",err_msg[7],"success",false);
                            clear();
                            flextable();
                            $(".preloader").hide();
                            $("#USRC_UPD_tb_date").val('').hide()
                            $('#USRC_UPD_lbl_dte').hide();
                        }
                        else
                        {
                            show_msgbox("USER SEARCH AND UPDATE",msg_alert,"success",false);
                            clear();
                            flextable();
                            $(".preloader").hide();
                            $("#USRC_UPD_tb_date").val('').hide()
                            $('#USRC_UPD_lbl_dte').hide();
                        }
                    }
                }
                var option="UPDATE"
                xmlhttp.open("POST","USER/DB_DAILY_REPORTS_USER_SEARCH_UPDATE.do?option="+option+"&reportlocation="+checkoutlocation,false);
                xmlhttp.send(new FormData(formElement));
            });
            $(document).on('click','#USRC_UPD_btn_pdf',function(){
                var inputValOne=$('#USRC_UPD_tb_strtdte').val();
                inputValOne = inputValOne.split("-").reverse().join("-");
                var inputValTwo=$('#USRC_UPD_tb_enddte').val();
                inputValTwo = inputValTwo.split("-").reverse().join("-");
                var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=18&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&title='+errmsgs;
            });
//    SEARCH UPDATE END
        });
    </script>
    <!--SCRIPT TAG END-->
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body>
<div class="container-fluid">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="title text-center"><h4><b>USER REPORT ENTRY SEARCH/UPDATE</b></h4></div>
    <form id="URE_form_dailyuserentry" name="URE_form_dailyuserentry" class="content form-horizontal" role="form" >
        <div class="panel-body">
            <fieldset>
                <div style="padding-bottom: 15px">

                    <div class="radio">
                        <label><input type="radio" name="UR_ESU" value="entry" class="rdclick">ENTRY</label>
                    </div>

                    <div class="radio">
                        <label><input type="radio" name="UR_ESU" value="search_update" class="rdclick">SEARCH/UPDATE/DELETE</label>
                    </div>
                </div>
                <div class="row-fluid">
                    <label name="URE_report_entry" id="URE_lbl_report_entry" class="srctitle"></label>
                </div>
                <div id="entry" hidden>
                    <div style="padding-bottom: 15px">
                        <div class="radio">
                            <label name="entry" class="col-sm-12" id="URE_lbl_sinentry"><input type="radio" id="URE_rd_sinentry" name="entry" value="SINGLE DAY ENTRY"/>SINGLE DAY ENTRY</label>
                        </div>

                        <div class="radio">
                            <label name="entry" class="col-sm-12" id="URE_lbl_mulentry" >
                                <input type="radio" id="URE_rd_mulentry" name="entry" value="MULTIPLE DAY ENTRY"/>MULTIPLE DAY ENTRY</label>
                        </div>
                    </div>


                    <div id="URE_tbl_singleday" class="form-group" hidden>
                        <div class="row-fluid" style="padding-right: 15px">
                            <label name="URE_lbl_dte" class="col-sm-2" id="URE_lbl_dte" >DATE</label>
                            <div class="col-sm-4">
                                <input type ="text" id="URE_tb_date" class='proj datemandtry formshown form-control' name="URE_tb_date" style="width:100px;" />

                            </div></div>


                        <div id="URE_tble_attendence" hidden >
                            <div class="row-fluid">
                                <label name="URE_lbl_attendance"  class="col-sm-2"  id="URE_lbl_attendance">ATTENDANCE</label>
                                <div class="col-sm-2">
                                    <select id="URE_lb_attendance" name="URE_lb_attendance" class="form-control">
                                        <option>SELECT</option>
                                        <option value="1">PRESENT</option>
                                        <option value="0">ABSENT</option>
                                        <option value="OD">ONDUTY</option>
                                        <!--                        <option value="2">WORK FROM HOME</option>-->
                                    </select>
                                </div></div>
                            <div id="noopermission" hidden>
                                <div class="row-fluid" style="padding-right: 100px">
                                    <label class="col-sm-2"></label>
                                    <div class="col-sm-9">
                                        <div class="row-fluid">
                                            <div class="col-md-2">
                                                <div class="radio">
                                                    <label name="URE_permission"  id="URE_lbl_permission">
                                                        <input type="radio" id="URE_rd_permission" name="permission" value="PERMISSION"/>PERMISSION<em>*</em></label>
                                                </div></div>
                                            <div class="col-sm-2" style="padding-top: 10px">
                                                <select name="URE_lb_timing" id="URE_lb_timing" class="form-control" style="display: none" >
                                                </select>
                                            </div></div>
                                        <div class="row-fluid">
                                            <div class="col-md-3">
                                                <div class="radio">
                                                    <label name="URE_nopermission"  id="URE_lbl_nopermission">
                                                        <input type="radio" id="URE_rd_nopermission" name="permission" value="NOPERMISSION"/>NO PERMISSION<em>*</em></label>
                                                </div></div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row-fluid" style="padding-top:10px">
                                <label name="URE_lbl_session" class="col-sm-2" id="URE_lbl_session" hidden >SESSION</label>
                                <div class="col-sm-4">
                                    <select name="URE_lb_ampm" id="URE_lb_ampm" class="form-control" >
                                        <option>SELECT</option>
                                        <option>FULLDAY</option>
                                        <option>AM</option>
                                        <option>PM</option>
                                    </select>
                                </div></div>
                        </div>

                        <div id="URE_tble_reasonlbltxtarea"></div>

                        <div id="URE_tble_projectlistbx" class="row-fluid" hidden>
                            <label name="URE_lbl_txtselectproj" class="col-sm-2" id="URE_lbl_txtselectproj">PROJECT<em>*</em></label>
                            <div id="URE_tble_frstsel_projectlistbx" class="col-sm-10" ></div>
                        </div>

                        <div id="URE_tble_enterthereport"></div>
                        <div id="URE_tble_bandwidth"></div>
                        <div style="padding-left: 15px"><input type="button"  class="btn" name="URE_btn_submit" id="URE_btn_submit"  value="SAVE" disabled></div>

                        <div  style="padding-left: 15px"><label id="URE_lbl_errmsg" name="URE_lbl_errmsg" class="errormsg"></label></div>
                    </div>

                    <div id="URE_tbl_multipleday" class="form-group" hidden>
                        <div class="row-fluid">
                            <label name="URE_lbl_sdte" class="col-sm-2"  id="URE_lbl_dte" >FROM DATE</label>
                            <div class="col-sm-4">
                                <input type ="text" id="URE_ta_fromdate" class='proj datemandtry formshown dtpic valid form-control' name="URE_ta_fromdate" style="width:100px;" />
                            </div></div>

                        <div class="row-fluid">
                            <label name="URE_lbl_edte" class="col-sm-2" id="URE_lbl_dte" >TO DATE</label>
                            <div class="col-sm-4">
                                <input type ="text" id="URE_ta_todate" class='proj datemandtry formshown dtpic valid_date form-control' name="URE_ta_todate" style="width:100px;" />
                            </div></div>

                        <div id="URE_tbl_attendence"  hidden>
                            <div class="row-fluid">
                                <label name="URE_lbl_attdnce" class="col-sm-2" id="URE_lbl_attdnce">ATTENDANCE</label>
                                <div class="col-sm-4">
                                    <select id="URE_lb_attdnce" name="URE_lb_attdnce" class="form-control">
                                        <option>SELECT</option>
                                        <option value="0">ABSENT</option>
                                        <option value="OD">ONDUTY</option>
                                    </select>
                                </div>
                            </div></div>

                        <div id="URE_tble_reason"></div>
                        <div style="padding-left: 15px">
                            <input type="button"  class="btn" name="URE_btn_save" id="URE_btn_save"  value="SAVE" disabled>
                        </div>
                    </div>

                    <div style="padding-left: 15px"><label id="URE_lbl_msg" name="URE_lbl_msg" class="errormsg"></label></div>
                    <div style="padding-left: 15px"><label id="URE_lbl_checkmsg" name="URE_lbl_checkmsg" class="errormsg"></label></div>
                </div>

                <div id="search_update" class="form-group" hidden>
                    <div class="row-fluid">
                        <label name="USRC_UPD_lbl_strtdte" class="col-sm-2"  id="USRC_UPD_lbl_strtdte" >START DATE<em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="USRC_UPD_tb_strtdte" id="USRC_UPD_tb_strtdte" class="USRC_UPD_tb_date valid clear form-control" style="width:100px;">
                        </div></div>

                    <div class="row-fluid">
                        <label name="USRC_UPD_lbl_enddte" class="col-sm-2"  id="USRC_UPD_lbl_enddte" >END DATE<em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="USRC_UPD_tb_enddte" id="USRC_UPD_tb_enddte" class="USRC_UPD_tb_date valid clear form-control" style="width:100px;">
                        </div></div>

                    <div class="row-fluid" style="padding-left: 15px"><input type="button" class="btn" name="USRC_UPD_btn_search" id="USRC_UPD_btn_search" value="SEARCH" disabled ></div>

                    <div class="srctitle row-fluid" style="padding-left: 15px" name="USRC_UPD_div_header" id="USRC_UPD_div_header" hidden></div>
                    <div style="padding-left: 15px"><input type="button" id='USRC_UPD_btn_pdf'  class="btnpdf" value="PDF"></div>
                    <!--            <div class="errormsg" name="USRC_UPD_errmsg" id="USRC_UPD_errmsg" hidden></div>-->
                    <div id="USRC_UPD_div_tablecontainer" style="max-width: 800px;padding-left: 15px" class="table-responsive" hidden>
                        <section>
                        </section>
                    </div>

                    <div style="padding-left: 15px" ><input type="button" id="USRC_UPD_btn_srch" class="btn" name="USRC_UPD_btn_srch" value="SEARCH" hidden/></div>

                    <div class="row-fluid" style="padding-top: 10px;padding-right:15px">
                        <label name="USRC_UPD_lbl_dte"  class="col-sm-2" id="USRC_UPD_lbl_dte" hidden>DATE</label>
                        <div class="col-sm-4">
                            <input type ="text" id="USRC_UPD_tb_date" class='proj datemandtry formshown update_validate form-control' name="USRC_UPD_tb_date" style="width:100px;" hidden/>
                            <label id="USRC_UPD_errmsg" name="USRC_UPD_errmsg" class="errormsg" hidden></label>
                        </div></div>

                    <div id="USRC_UPD_tble_attendence" hidden>
                        <div class="row-fluid">
                            <label name="USRC_UPD_lbl_attendance" class="col-sm-2" id="USRC_UPD_lbl_attendance" >ATTENDANCE</label>
                            <div class="col-sm-2">
                                <select id="USRC_UPD_lb_attendance" name="USRC_UPD_lb_attendance" class="update_validate form-control">
                                    <option value="1">PRESENT</option>
                                    <option value="0">ABSENT</option>
                                    <option value="OD">ONDUTY</option>
                                </select>
                            </div></div>
                        <div id="permission_hide" hidden>
                            <div class="row-fluid" style="padding-right: 100px">
                                <label class="col-sm-2"></label>
                                <div class="col-sm-9">
                                    <div class="row-fluid">
                                        <div class="col-md-2">
                                            <div class="radio">
                                                <label name="USRC_UPD_permission" class="col-sm-10" id="USRC_UPD_lbl_permission"><input type="radio" id="USRC_UPD_rd_permission" name="permission" value="PERMISSION" class="update_validate"/>PERMISSION<em>*</em>
                                            </div>
                                            </label>
                                        </div>
                                        <div class="col-sm-2" style="padding-top: 11px">
                                            <select  name="USRC_UPD_lb_timing" id="USRC_UPD_lb_timing" class="update_validate form-control" style="display:none">
                                                <option>SELECT</option>
                                            </select>
                                        </div></div>
                                    <div class="row-fluid">
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <label name="USRC_UPD_permission" class="col-sm-10" id="USRC_UPD_lbl_nopermission" ><input type="radio" id="USRC_UPD_rd_nopermission" name="permission" value="NOPERMISSION" class="update_validate"/>NO PERMISSION<em>*</em></label>
                                            </div></div></div>

                                </div></div></div>

                        <div class="row-fluid" style="padding-top: 10px">
                            <label name="USRC_UPD_lbl_session" class="col-sm-2" id="USRC_UPD_lbl_session" hidden >SESSION</label>
                            <div class="col-sm-2">
                                <select name="USRC_UPD_lb_ampm" id="USRC_UPD_lb_ampm" class="update_validate form-control" style="display: none" >
                                    <option>SELECT</option>
                                    <option>FULLDAY</option>
                                    <option>AM</option>
                                    <option>PM</option>
                                </select>
                            </div></div>
                    </div>
                    <div id="USRC_UPD_tble_reasonlbltxtarea" ></div>
                    <div id="USRC_UPD_tble_projectlistbx" class="row-fluid" hidden>
                        <label name="USRC_UPD_lbl_txtselectproj" class="col-sm-2" id="USRC_UPD_lbl_txtselectproj" >PROJECT<em>*</em></label>
                        <div id="USRC_UPD_tble_frstsel_projectlistbx" class="col-sm-10"></div>

                    </div>
                    <div id="USRC_UPD_tble_enterthereport"></div>
                    <div id="USRC_UPD_tble_bandwidth"></div>

                    <div> <label id="USRC_UPD_banderrmsg" name="USRC_UPD_banderrmsg" class="errormsg" hidden></label></div>

                    <div style="padding-left: 15px">
                        <input type="button"  class="btn" name="USRC_UPD_btn_submit" id="USRC_UPD_btn_submit"  value="UPDATE" disabled ></div>

                    <div id="USRC_UPD_btn_submit"></div>
                </div>
            </fieldset>
        </div>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
