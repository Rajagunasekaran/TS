<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************DAILY REPORTS ADMIN SEARCH UPDATE DELETE***********************************//
//DONE BY:ARTHI
//VER 0.13-SD:30/06/2015 ED:30/06/2015,DESC: reduce the space for onduty entry in admin entry,done recreate for date
//DONE BY:ARTHI
//VER 0.13-SD:02/06/2015 ED:05/06/2015,DESC:MERGED ADMIN ENTRY AND SEARCH,RESPONSIVE FORM,UPDATED THAT FORM IS RUNNING ONLY ONCES
//DONE BY:RAJA
//VER 0.12-SD:10/01/2015 ED:10/01/2015, TRACKER NO:74,DESC:ADDED LOCATION COLUMN IN DATATABLE, CHANGED PRELOADER POSITON AND
//DONE BY:RAJA
//VER 0.11-SD:02/01/2015 ED:07/01/2015, TRACKER NO:74,DESC:IMPLEMENTED PDF BUTTON AND VALIDATED AND GAVE INPUT TO DB, CHANGED LOGIN ID AS EMPLOYEE NAME, SETTING PRELOADER POSITON, MSGBOX POSITION
//DONE BY:SASIKALA
//VER 0.10-SD:06/01/2015 ED:06/01/2015, TRACKER NO:74,DESC:ADDED GEOLOCATION FOR REPORT UPDATE
//DONE BY:LALITHA
//VER 0.09-SD:29/12/2014 ED:29/12/2014,tracker no:84, updated delete function
//VER 0.08-SD:05/12/2014 ED:05/12/2014,TRACKER NO:74,Implemented If reason means updated Onduty(am/pm)/Absent(am/pm) with checked condition) nd changed query also,Updated to showned nd hide the header err msg,Updated pdf file name frm err msgs,Changed listbx name
//VER 0.07 SD:01/12/2014 ED:01/12/2014,TRACKER NO:74,Changed Preloder funct
//VER 0.06 SD:20/11/2014 ED:20/11/2014,TRACKER NO:74,DESC:Updated to showned point by point line for report nd reason,Showned permission in report fr all active employee flextble nd also Changed flex tble query
//VER 0.05 SD:14/11/2014 ED 14/11/2014,TRACKER NO:74,DESC:Fixed width
//VER 0.04 SD:06/11/2014 ED 06/11/2014,TRACKER NO:74,DESC:Impmlemented auto focus in radio nd search btn clicking,Fixed width fr all db column,Removed(report:)lbl,Replaced name login(loginid),Hide the err msg while changing dp
//DONE BY:SASIKALA
//VER 0.03 SD:17/10/2014 ED 18/10/2014,TRACKER NO:74,DESC:DID PERMISSION AS MANDATORY AND BUTTON VALIDATION
//VER 0.02 SD:08/10/2014 ED:08/10/2014,TRACKER NO:74,DESC:UPDATED MAIL SEND WHEN UPDATION OCCUR
//VER 0.01-INITIAL VERSION, SD:08/08/2014 ED:01/10/2014,TRACKER NO:74
//*********************************************************************************************************//-->
<?php
include '../TSLIB/TSLIB_HEADER.php';
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
        $(document).ready(function(){
            $(".preloader").hide();
            $("#ARE_tb_date").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
            $('#ARE_lb_loginid').val('SELECT').show();
            $('#ARE_tble_attendence').val('SELECT').show();
            var permission_array=[];
            var project_array=[];
            var min_date;
            var err_msg=[];
            var login_id=[];
            var loginid;
            var maxdate;
            var month;
            var year;
            var date;
            var max_date;
            var pdfmsg;
            var permission_array=[];
            var project_array=[];
            var allmindate;
            var allmaxdate;
            var err_msg=[];
            var active_emp=[];
            var nonactive_emp=[];
            var odmindate;
            var odmaxdate;
            var userstamp;
            var flag;
            var rprt_min_date;
            var rprt_max_date
            var datepicker_maxdate;
            var msg;
            $(document).on('click','.radio_click',function(){
                $(".preloader").show();
                var click=$(this).val();
                $("#ARE_tb_dte").hide();
                $('#ARE_lbl_dte').hide();
                $("#ARE_chk_notinfrmd").html('')
                $('#ARE_tble_ondutyentry').hide();
                $('#ASRC_chk_notinformed').hide();
                $('#ASRC_UPD_DEL_btn_srch').hide();
                if(click=="entries")
                {
                    ASRC_UPD_DEL_clear();
                    search_clear();
                    $('#ARE_lbl_report_entry').html('ADMIN REPORT ENTRY');
                    $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                    $('#ARE_rd_sinentry').prop('checked', false);
                    $('#ARE_rd_mulentry').prop('checked', false);
                    $('#entries').show();
                    $('#search').hide();
                    $('#ARE_msg').hide();
                    $('#ARE_lbl_date_err').text(err_msg[10]).hide();
                    $('#ARE_lbl_optn').show();
                    $('#option').val('SELECT').show();
                    $('#ARE_lb_loginid').val('SELECT').hide();
                    $('#ARE_tb_date').val('').hide();
                    $('#ARE_lbl_dte').hide();
                    $('#ARE_tble_attendence').hide();
                    $('#ARE_tbl_attendence').hide();
                    $('#ARE_btn_save').hide();
                    $('#ARE_ta_reason').hide();
                    $('#ARE_lbl_reason').hide();
                    $("#ARE_btn_odsubmit").hide();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            var value_array=JSON.parse(xmlhttp.responseText);
                            $("#ARE_lb_attendance option[value='2']").detach();
                            permission_array=value_array[0];
                            min_date=value_array[2];
                            err_msg=value_array[3];
                            login_id=value_array[4];
                            var login_list='<option>SELECT</option>';
                            for (var i=0;i<login_id.length;i++) {
                                login_list += '<option value="' + login_id[i][1] + '">' + login_id[i][0] + '</option>';
                            }
                            $('#ARE_lb_loginid').html(login_list);
                            $("#ARE_tb_date").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                            maxdate=new Date();
                            month=maxdate.getMonth()+1;
                            year=maxdate.getFullYear();
                            date=maxdate.getDate();
                            max_date = new Date(year,month,date);
                            datepicker_maxdate=new Date(Date.parse(max_date));
                            $('#ARE_tb_date').datepicker("option","maxDate",datepicker_maxdate);
                            $('#ARE_tb_date').datepicker("option","minDate",min_date);
                            var login_list='<option>SELECT</option>';
                            for (var i=0;i<login_id.length;i++) {
                                login_list += '<option value="' + login_id[i][1] + '">' + login_id[i][0] + '</option>';
                            }
                            $('#ARE_lb_lgnid').html(login_list);
                        }
                    }
                    var option="admin_report_entry";
                    xmlhttp.open("GET","TSLIB/TSLIB_COMMON.do?option="+option);
                    xmlhttp.send();
                }
                else if(click=="search")
                {
                    $('#ARE_lbl_report_entry').html('ADMIN REPORT SEARCH/UPDATE');
                    $('#options').val('SELECT');
                    $('#entries').hide();
                    $('#search').show();
                    entryclear();
                    ARE_clear();
                    $('#ASRC_UPD_DEL_btn_search').hide();
                    $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
                    $('#ASRC_UPD_DEL_div_headers').hide();
                    $('#ASRC_UPD_DEL_btn_allsearch').hide();
                    $('#ASRC_UPD_DEL_od_btn').hide();
                    $('#ASRC_UPD_DEL_lbl_sdte').hide();
                    $('#ASRC_UPD_DEL_tb_sdte').hide();
                    $('#ASRC_UPD_DEL_lbl_edte').hide();
                    $('#ASRC_UPD_DEL_tb_edte').hide();
                    $('#ASRC_UPD_DEL_lbl_nopermission').hide();
                    $('#ASRC_UPD_btn_pdf').hide();
                    $('#ASRC_UPD_btn_od_pdf').hide();
                    $('#ASRC_UPD_DEL_tble_attendence').hide();
                    $('#ASRC_UPD_DEL_tbl_entry').hide();
                    $('#ASRC_UPD_DEL_btn_del').hide();
                    $('#ASRC_UPD_DEL_btn_submit').hide();
                    $('#ASRC_UPD_DEL_errmsg').hide();
                    $('#ASRC_UPD_DEL_tbl_htmltable').hide();
                    $('#ASRC_UPD_DEL_odsrch_btn').hide();
                    $('#updatepart').hide();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            var value_array=JSON.parse(xmlhttp.responseText);
                            permission_array=value_array[0];
                            allmindate=value_array[2];
                            err_msg=value_array[3];
                            if(allmindate=='01-01-1970')
                            {
                                $('#reports_entry').show();
                                $('#reports_search').show();
                                $('#admin_report_entry').show();
                                $('#admin_report_search').show();
                                $('#ARE_lbl_optn').hide();
                                $('#ASRC_UPD_DEL_lbl_optn').hide();
                                $('#options').hide();
                                $('#ARE_lbl_report_entry').hide();
                                $('#option').hide();
                                $('#ARE_lbl_date_err').text(err_msg[10]).show();
//                                $('#ARE_form_adminreportentry').replaceWith('<p><label class="errormsg">'+err_msg[10]+'</label></p>');
                            }
                            else
                            {
                                $("#ASRC_UPD_DEL_lb_attendance option[value='2']").detach();

                                active_emp=value_array[5];
                                nonactive_emp=value_array[6];
                                allmaxdate=value_array[7];
                                odmindate=value_array[8];
                                odmaxdate=value_array[9];
                                userstamp=value_array[10];
                                flag=value_array[10];
                                if(flag == 'X')
                                {
                                    $('#ASRC_UPD_DEL_lb_attendance').append("<option value='2'>WORK FROM HOME</option>")
                                }
                                $('#ASRC_UPD_DEL_tb_dte').datepicker("option","minDate",allmindate);
                                $('#ASRC_UPD_DEL_tb_dte').datepicker("option","maxDate",allmaxdate);
                                $('#ASRC_UPD_DEL_tb_sdte').datepicker("option","minDate",odmindate);
                                $('#ASRC_UPD_DEL_tb_sdte').datepicker("option","maxDate",odmaxdate);
                                $('#ASRC_UPD_DEL_tb_edte').datepicker("option","maxDate",odmaxdate);
                                $('#ASRC_UPD_DEL_lbl_optn').show();
                                $('#option').val('SELECT').show();
                            }
                        }
                    }
                    var option="admin_search_update";
                    xmlhttp.open("GET","TSLIB/TSLIB_COMMON.do?option="+option);
                    xmlhttp.send();
                }
            });
            $("#ASRC_UPD_DEL_tb_strtdte").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
            $("#ASRC_UPD_DEL_tb_enddte").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
            $('textarea').autogrow({onInitialize: true});
            $("#ARE_tb_band,#ARE_tb_date").html('')
            $('#ARE_tb_band').hide();
            $('#ARE_btn_submit').hide();
            //DATE PICKER FUNCTION
            $('#ARE_tb_date').datepicker({
                dateFormat:"dd-mm-yy",
                changeYear: true,
                changeMonth: true
            });
            $('#ARE_tb_dte').datepicker({
                dateFormat:"dd-mm-yy",
                changeYear: true,
                changeMonth: true
            });
            function entryclear()
            {
                $('#ARE_rd_sinentry').hide();
                $('#ARE_rd_mulentry').hide();
                $('#ARE_lbl_loginid').hide();
                $('#ARE_lb_loginid').hide();
                $('#ARE_lb_lgnid').hide();
                $('#ARE_lbl_lgnid').hide();
                $('#ARE_lbl_dte').hide();
                $('#ARE_tb_date').hide();
                $('#ARE_lbl_sinentry').hide();
                $('#ARE_lbl_mulentry').hide();
                $('#ARE_lbl_multipleday').hide();
                $('#ARE_lbl_sinemp').hide();
                $('#ARE_rd_sinemp').hide();
                $('#ARE_lbl_allemp').hide();
                $('#ARE_rd_allemp').hide();
                $('#ARE_lbl_sdte').hide();
                $('#ARE_tb_sdate').hide();
                $('#ARE_lbl_edte').hide();
                $('#ARE_tb_edate').hide();
//                $('#ARE_lbl_dte').hide();
                $('#ARE_tb_dte').val('');
                $('#ARE_lbl_des').hide();
                $('#ARE_ta_des').hide();
                $('#ARE_btn_odsubmit').hide();
                $('#ARE_lbl_oderrmsg').hide();
//                $('#ARE_tbl_reason').hide();
//                $('#ARE_ta_reason').hide();
            }
            var od_maxdate=new Date();
            month=od_maxdate.getMonth()+1;
            year=od_maxdate.getFullYear();
            date=od_maxdate.getDate();
            var OD_max_date = new Date(year,month,date);
            $('#ARE_tb_dte').datepicker("option","maxDate",OD_max_date);
            //CHANGE EVENT FOR LOGIN LIST BX
            $(document).on('change','#ARE_lb_loginid',function(){
                $(".preloader").show();
                $('#single_emp').html('');
                $("html, body").animate({ scrollTop: $(document).height() }, "1000");
                var ARE_loginidlistbx= $("#ARE_lb_loginid").val();
                $('#ARE_tb_date').val('');
                $('#ARE_lbl_errmsg').hide();
                $('#ARE_lbl_checkmsg').hide();
                $('#ARE_tble_attendence').hide();
                $("#ARE_chk_notinfrmd").html('')
                if(ARE_loginidlistbx=='SELECT')
                {
                    $('#ARE_tble_attendence').hide();
                    $('#ARE_lbl_dte').hide();
                    $('#ARE_tb_date').hide();
                    $('#ARE_lbl_session').hide();
                    $('#ARE_lbl_reason').hide();
                    $('#ARE_ta_reason').hide();
                    $('#ARE_lb_ampm').hide();
                    $('#ARE_lbl_report').hide();
                    $('#ARE_ta_report').hide();
                    $('#ARE_rd_permission').hide();
                    $('#ARE_lbl_permission').hide();
                    $('#ARE_rd_nopermission').hide();
                    $('#ARE_lbl_nopermission').hide();
                    $('#ARE_lb_timing').hide();
                    $('#ARE_lbl_band').hide();
                    $('#ARE_tb_band').hide();
                    $("#ARE_btn_submit,#ARE_mrg_projectlistbx").html('');
                    ARE_clear()

                }
                else
                {
                    $("#ARE_lb_attendance option[value='2']").detach();
                    var loginid=$('#ARE_lb_loginid').val();
                    $('#ARE_lbl_dte').show();
                    $('#ARE_tb_date').show();
                    $("#ARE_tb_date").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            var final_array=JSON.parse(xmlhttp.responseText);
                            $(".preloader").hide();
                            $("#ARE_tb_date").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                            mindate=final_array[0];
                            project_array=final_array[1];
                            var wfh_flag=final_array[2];
//                            if(wfh_flag == 'X')
//                            {
//                                $('#ARE_lb_attendance').append("<option value='2'>WORK FROM HOME</option>")
//                            }
                            if(wfh_flag=='X')
                            {
                                $('#ARE_lb_attendance').append("<option value='2'>WORK FROM HOME</option>")
                                $('#ARE_lb_attendance').children('option[value="1"]').css('display','none');
                                $('#ARE_lb_attendance').children('option[value="0"]').css('display','none');
                                $('#ARE_lb_attendance').children('option[value="OD"]').css('display','none');
                            }
                            else
                            {
                                $('#ARE_lb_attendance').children('option[value="1"]').show();
                                $('#ARE_lb_attendance').children('option[value="0"]').show();
                                $('#ARE_lb_attendance').children('option[value="OD"]').show();
                            }
                            if(project_array.length==0){

                                var msg=err_msg[10].replace('[LOGIN ID]',$("#ARE_lb_loginid option:selected").text());
                                $('#ARE_lbl_norole_err').text(msg).show();
                                $('#ARE_tb_date').hide();
                                $('#ARE_lbl_dte').hide();

                            }
                            else{
                                $('#ARE_tb_date').datepicker("option","minDate",mindate);
                                $('#ARE_lbl_norole_err').hide();
                                $('#ARE_tb_date').show();
                                $('#ARE_lbl_dte').show();
                            }

//                    $('#ARE_tb_date').datepicker("option","minDate",mindate);
                        }
                    }
                    var choice="login_id"
                    xmlhttp.open("GET","ADMIN/DB_DAILY_REPORTS_ADMIN_REPORT_ENTRY.do?login_id="+loginid+"&option="+choice,true);
                    xmlhttp.send();
                    ARE_clear()
                    $('#ARE_lbl_dte').show();
                    $('#ARE_tb_date').val('').show();
                    $('#ARE_lbl_session').hide();
                    $('#ARE_lbl_reason').hide();
                    $('#ARE_ta_reason').hide();
                    $("#ARE_btn_submit,#ARE_mrg_projectlistbx").html('');
                    $('#ARE_lb_ampm').hide();
                    $('#ARE_lbl_report').hide();
                    $('#ARE_ta_report').hide();
                    $('#ARE_rd_permission').hide();
                    $('#ARE_lbl_permission').hide();
                    $('#ARE_rd_nopermission').hide();
                    $('#ARE_lbl_nopermission').hide();
                    $('#ARE_lb_timing').hide();
                    $('#ARE_lbl_band').hide();
                    $('#ARE_tb_band').hide();
                }
            });
            //JQUERY LIB VALIDATION START
            $("#ARE_tb_band").doValidation({rule:'numbersonly',prop:{realpart:10,leadzero:true}});
            $("#ARE_tb_band").prop("title","NUMBERS ONLY")
            $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:5,imaginary:2}});
            //JQUERY LIB VALIDATION END
            // CHANGE EVENT FOR DATE
            $(document).on('change','.singledayentry',function(){
                $('#ARE_chk_notinfrmd').html('');
//            $("#ARE_tb_date").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                $(".preloader").show();
                var loginid=$('#ARE_lb_loginid').val();
                var reportdate=$('#ARE_tb_date').val();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var msgalert=xmlhttp.responseText;
                        $('#ARE_lbl_checkmsg').hide();
//                        $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        if(msgalert==1)
                        {
                            var msg=err_msg[3].toString().replace("[DATE]",reportdate)
                            ARE_clear()
                            $("#ARE_tb_date").val("");
//                    $("#ARE_lbl_dte").hide();
                            $('#ARE_tble_attendence').hide();
                            $('#ARE_lbl_errmsg').text(msg).show();
//                    $('#ARE_lb_loginid').val('SELECT').show();
                        }
                        else
                        {
                            ARE_clear()
                            $('#ARE_tble_attendence').val('SELECT').show();
                            $('#ARE_lbl_errmsg').hide();
                            $('#ARE_lb_attendance').prop('selectedIndex',0);

                        }
                    }
                }
                var choice="DATE"
                xmlhttp.open("GET","ADMIN/DB_DAILY_REPORTS_ADMIN_REPORT_ENTRY.do?date_change="+reportdate+"&login_id="+loginid+"&option="+choice,true);
                xmlhttp.send();
            });
            // CHANGE EVENT FOR ATTENDANCE
            $('#ARE_lb_attendance').change(function(){
                $('#ARE_tble_frstsel_projectlistbx').html('');
//        $('#ARE_btn_submit').attr('disabled','disabled');
                $('#ARE_tble_reasonlbltxtarea').html('');
                $("#ARE_chk_notinfrmd").html('')
                if($('#ARE_lb_attendance').val()=='SELECT')
                {
                    $('#ARE_rd_permission').hide();
                    $('#ARE_lbl_permission').hide();
                    $('#ARE_rd_nopermission').hide();
                    $('#ARE_lbl_nopermission').hide();
                    $('#ARE_lb_timing').hide();
                    $('#ARE_tbl_enterthereport').html('');
                    $('#ARE_tble_projectlistbx').hide();
                    $('#ARE_tble_bandwidth').html('');
                    $('#ARE_lbl_session').hide();
                    $('#ARE_lb_ampm').hide();
                    $('#ARE_btn_submit').hide();
                    $('#ARE_lbl_errmsg').hide();
                    $('#ARE_lbl_checkmsg').hide();
                }
                else if($('#ARE_lb_attendance').val()=='1')
                {
                    $(".preloader").show();
                    var reportdate=$('#ARE_tb_date').val();
                    var loginid=$('#ARE_lb_loginid').val();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                            $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
//                    $("html, body").animate({ scrollTop: $(document).height() }, "1000");
                            var response=JSON.parse(xmlhttp.responseText);
                            $('#ARE_rd_permission').attr('checked',false);
                            $('#ARE_rd_nopermission').attr('checked',false);
                            $('#ARE_rd_permission').removeAttr("disabled");
                            $('#ARE_rd_nopermission').removeAttr("disabled");
                            if((response[0]==1) && (response[1]==0))
                            {
                                $('#ARE_lbl_checkmsg').text(err_msg[11]).show();
                                $('#ARE_lb_timing').hide();
                                $('#ARE_rd_permission').hide();
                                $('#ARE_lbl_permission').hide();
                                $('#ARE_rd_nopermission').hide();
                                $('#ARE_lbl_nopermission').hide();
                                $('#ARE_lbl_session').hide();
                                $('#ARE_lb_ampm').hide();
                                $('#ARE_lbl_txtselectproj').hide();
                                $('#ARE_tble_projectlistbx').hide();
                                $('#ARE_btn_submit').hide();
                                $('#ARE_lbl_errmsg').hide();

                            }
                            else{
                                $('#ARE_tbl_enterthereport,#ARE_ta_reason,#ARE_tble_bandwidth').html('');
//
                                $('#ARE_lb_timing').hide();
                                $('#ARE_rd_permission').show();
                                $('#ARE_lbl_permission').show();
                                $('#ARE_rd_nopermission').show();
                                $('#ARE_lbl_nopermission').show();
                                $('#permission').show();
                                var permission_list='<option>SELECT</option>';
                                for (var i=0;i<permission_array.length;i++) {
                                    permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                                }
                                $('#ARE_lb_timing').html(permission_list);
                                $('#ARE_lbl_session').hide();
                                $('#ARE_lb_ampm').hide();
                                $('#ARE_lbl_txtselectproj').show();
                                $('#ARE_tble_projectlistbx').show();
                                projectlist();
                                ARE_report();
                                ARE_tble_bandwidth();
                                $('#ARE_btn_submit').hide();

                                $('#ARE_lbl_errmsg').hide();
                                $('#ARE_lbl_checkmsg').hide();
                            }
                        }
                    }
                    var option="PRESENT";
                    xmlhttp.open("POST","ADMIN/DB_DAILY_REPORTS_ADMIN_REPORT_ENTRY.do?option="+option+"&reportdate="+reportdate+"&loginid="+loginid);
                    xmlhttp.send();
                }
                else if($('#ARE_lb_attendance').val()=='2')
                {
                    $(".preloader").show();
                    var reportdate=$('#ARE_tb_date').val();
                    var loginid=$('#ARE_lb_loginid').val();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            $("html, body").animate({ scrollTop: $(document).height() }, "1000");
                            var response=JSON.parse(xmlhttp.responseText);
//                            if((response[0]==1) && (response[1]==0))
//                            {
//                                $('#ARE_lbl_checkmsg').text(err_msg[12]).show();
//                                $('#ARE_lb_timing').hide();
//                                $('#ARE_rd_permission').hide();
//                                $('#ARE_lbl_permission').hide();
//                                $('#ARE_rd_nopermission').hide();
//                                $('#ARE_lbl_nopermission').hide();
//                                $('#ARE_lbl_session').hide();
//                                $('#ARE_lb_ampm').hide();
//                                $('#ARE_lbl_txtselectproj').hide();
//                                $('#ARE_tble_projectlistbx').hide();
//                                $('#ARE_btn_submit').hide();
//                                $('#ARE_lbl_errmsg').hide();
//                            }
//                            else{
                            $('#ARE_tbl_enterthereport,#ARE_ta_reason,#ARE_tble_bandwidth').html('');
                            $('#ARE_lb_timing').hide();
                            $('#ARE_rd_permission').hide();
                            $('#ARE_lbl_permission').hide();
                            $('#ARE_rd_nopermission').hide();
                            $('#ARE_lbl_nopermission').hide();
                            $('#permission').hide();
                            $('#ARE_lbl_session').hide();
                            $('#ARE_lb_ampm').hide();
                            $('#ARE_lbl_txtselectproj').show();
                            $('#ARE_tble_projectlistbx').show();
                            projectlist();
                            ARE_report();
                            $('#ARE_btn_submit').hide();
                            $('#ARE_lbl_errmsg').hide();
                            $('#ARE_lbl_checkmsg').hide();
//                            }
                        }
                    }
                    var option="PRESENT";
                    xmlhttp.open("POST","ADMIN/DB_DAILY_REPORTS_ADMIN_REPORT_ENTRY.do?option="+option+"&reportdate="+reportdate+"&loginid="+loginid);
                    xmlhttp.send();
                }
                else if($('#ARE_lb_attendance').val()=='0')
                {

                    $('#ARE_rd_permission').attr('checked',false);
                    $('#ARE_rd_nopermission').attr('checked',false);
                    $('#ARE_lb_timing').hide();
                    $('#ARE_rd_permission').show();
                    $('#ARE_lbl_permission').show();
                    $('#ARE_rd_nopermission').show();
                    $('#ARE_lbl_nopermission').show();
//
//                    var ARE_chk_notinformed='<div class="form-inline col-lg-1"><div class="checkbox"><input type="checkbox" name="URSRC_chk_votersid" id="URSRC_chk_votersid" class="login_submitvalidate"></div></div><label name="URSRC_lbl_votersid" id="URSRC_lbl_votersid">&nbsp;&nbsp;</label> </div>';
                    var ARE_chk_notinformed='<div class="form-group"><label class="col-sm-2"></label><div class="col-sm-9"><div class="col-md-3"><div class="row-fluid"><div class="checkbox"><label name="ARE_noinformed"  id="ARE_lbl_notinformed"><input type="checkbox" name="notinformed" id="ARE_rd_notinformed" value="NOTINFORMED" >NOT INFORMED</label></div></div></div></div></div>';
                    $('#ARE_chk_notinfrmd').html(ARE_chk_notinformed);
                    $('#ARE_chk_notinfrmd').show();
                    var permission_list='<option>SELECT</option>';
                    for (var i=0;i<4;i++) {
                        permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                    }
                    $('#ARE_lb_timing').html(permission_list);
                    $('#ARE_lbl_session').show();
                    $('#ARE_lb_ampm').val('SELECT').show();
                    $('#ARE_tble_projectlistbx').hide();
                    $('#ARE_tble_reasonlbltxtarea').html('');
                    $('#ARE_tbl_enterthereport').html('');
                    $('#ARE_tble_bandwidth').html('');
                    $('#ARE_btn_submit').hide();
                    $('#ARE_rd_permission').attr('disabled','disabled');
                    $('#ARE_rd_nopermission').attr('disabled','disabled');
                    $('#ARE_lbl_errmsg').hide();
                    $('#ARE_lbl_checkmsg').hide();
                }
                else if($('#ARE_lb_attendance').val()=='OD')
                {
                    $('#ARE_rd_permission').attr('checked',false);
                    $('#ARE_rd_nopermission').attr('checked',false);
                    $('#ARE_lb_timing').hide();
                    $('#ARE_rd_permission').show();
                    $('#ARE_lbl_permission').show();
                    $('#ARE_rd_nopermission').show();
                    $('#ARE_lbl_nopermission').show();
//                    var ARE_chk_notinformed=' <div class="row-fluid form-group"><label name="ARE_noinformed" class="col-sm-8" id="ARE_lbl_notinformed"><div class="col-sm-3"><div class="checkbox"><input type="checkbox" name="notinformed" id="ARE_rd_notinformed" value="NOTINFORMED" >NOT INFORMED</label></div></div>';
//                    $('#ARE_chk_notinfrmd').html(ARE_chk_notinformed);
                    var permission_list='<option>SELECT</option>';
                    for (var i=0;i<4;i++) {
                        permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                    }
                    $('#ARE_lb_timing').html(permission_list);
                    $('#ARE_lbl_session').show();
                    $('#ARE_lb_ampm').val('SELECT').show();
                    $('#ARE_tble_projectlistbx').hide();
                    $('#ARE_tble_reasonlbltxtarea').html('');
                    $('#ARE_tbl_enterthereport').html('');
                    $('#ARE_tble_bandwidth').html('');
                    $('#ARE_btn_submit').hide();
                    $('#ARE_rd_permission').attr('disabled','disabled');
                    $('#ARE_rd_nopermission').attr('disabled','disabled');
                    $('#ARE_lbl_errmsg').hide();
                    $('#ARE_lbl_checkmsg').hide();
                }
            });
            // CLICK EVENT PERMISSION RADIO BUTTON
            $(document).on('click','#ARE_rd_permission',function()
            {
//        $("html, body").animate({ scrollTop: $(document).height() }, "1000");
                if($('#ARE_rd_permission').attr("checked","checked"))
                {
                    $('#ARE_lb_timing').val('SELECT').show();
                }
                else
                {
                    $('#ARE_lb_timing').hide();
                    $('#ARE_lb_timing').prop('selectedIndex',0);
                }
            });
            // CLICK EVENT NOPERMISSION RADIO BUTTON
            $(document).on('click','#ARE_rd_nopermission',function()
            {
                $('#ARE_lb_timing').hide();
                $('#ARE_lb_timing').prop('selectedIndex',0);
            });
// FUNCTION FOR CLEAR FORM ELEMENTS
            function ARE_clear(){
                $('#ARE_tble_attendence').hide();
                $('#ARE_tble_reasonlbltxtarea').html('');
                $('#ARE_tble_frstsel_projectlistbx').html('');
                $('#ARE_tbl_enterthereport').html('');
                $('#ARE_tble_bandwidth').html('');
                $('#ARE_btn_submit').html('');
                $('#ARE_lbl_session').hide();
                $('#ARE_rd_permission').hide();
                $('#ARE_lbl_permission').hide();
                $('#ARE_rd_nopermission').hide();
                $('#ARE_lbl_nopermission').hide();
                $('#ARE_lb_timing').hide();
                $('#ARE_lb_timing').prop('selectedIndex',0);
                $('#ARE_lb_ampm').hide();
                $('#ARE_btn_submit').hide();
                $('#ARE_tble_projectlistbx').hide();
                $('#ARE_lbl_norole_err').hide();
            }
            // CHANGE EVENT FOR SESSION LIST BOX
            $('#ARE_lb_ampm').change(function(){
                flag=1;
                $('#ARE_tble_reasonlbltxtarea,#ARE_tbl_enterthereport,#ARE_tble_bandwidth,#ARE_tble_frstsel_projectlistbx').html('');
                if($('#ARE_lb_ampm').val()=='SELECT')
                {
                    $('#ARE_tble_reasonlbltxtarea').html('');
                    $('#ARE_tble_frstsel_projectlistbx').html('');
                    $('#ARE_tbl_enterthereport').html('');
                    $('#ARE_tble_projectlistbx').hide();
                    $('#ARE_tble_bandwidth').html('');
                    $('#ARE_btn_submit').hide();
                    $('#ARE_lbl_errmsg').hide();
                    $('#ARE_lbl_checkmsg').hide();
                }
                else if($('#ARE_lb_ampm').val()=='FULLDAY')
                {
                    $('#ARE_tble_projectlistbx').hide();
                    ARE_reason();
                    $('#ARE_lb_timing').hide();
                    $('#ARE_rd_permission').hide();
                    $('#ARE_lbl_permission').hide();
                    $('#ARE_rd_nopermission').hide();
                    $('#ARE_lbl_nopermission').hide();
                    $('#permission').hide();
                    $('#ARE_rd_permission').attr('disabled','disabled');
                    $('#ARE_rd_nopermission').attr('disabled','disabled');
                    $('#ARE_btn_submit').show();
                    $('#ARE_lbl_errmsg').hide();
                    $('#ARE_lbl_checkmsg').hide();
                }
                else
                {
//                    $('.preloader', window.parent.document).show();
                    $(".preloader").show();
                    var reportdate=$('#ARE_tb_date').val();
                    var loginid=$('#ARE_lb_loginid').val();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                            $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
//                    $("html, body").animate({ scrollTop: $(document).height() }, "1000");
                            var response=JSON.parse(xmlhttp.responseText);
                            if((response[0]==1) && (response[1]==0))
                            {
                                $('#ARE_lbl_checkmsg').text(err_msg[11]).show();
                                $('#ARE_btn_submit').hide();
                                $('#ARE_rd_permission').hide();
                                $('#ARE_lbl_permission').hide();
                                $('#ARE_rd_nopermission').hide();
                                $('#permission').hide();
                                $('#ARE_lbl_nopermission').hide();
                                $('#ARE_lb_timing').hide();
                                $('#ARE_tble_projectlistbx').hide();
                                $('#ARE_lbl_txtselectproj').hide();
                                $('#ARE_lbl_errmsg').hide();
                            }
                            else
                            {
                                ARE_reason();
                                $('#ARE_btn_submit').hide();
                                $('#ARE_rd_permission').attr('checked',false);
                                $('#ARE_rd_nopermission').attr('checked',false);
                                $('#ARE_rd_permission').removeAttr("disabled");
                                $('#ARE_rd_nopermission').removeAttr("disabled");
                                $('#ARE_rd_permission').show();
                                $('#ARE_lbl_permission').show();
                                $('#ARE_rd_nopermission').show();
                                $('#ARE_lbl_nopermission').show();
                                $('#permission').show();
                                $('#ARE_lb_timing').hide();
                                $('#ARE_tble_projectlistbx').show();
                                $('#ARE_lbl_txtselectproj').show();
                                projectlist();
                                ARE_report();
                                ARE_tble_bandwidth();
                                $('#ARE_lbl_errmsg').hide();
                                $('#ARE_lbl_checkmsg').hide();
                            }
                        }
                    }
                    var option="HALFDAYABSENT";
                    xmlhttp.open("POST","ADMIN/DB_DAILY_REPORTS_ADMIN_REPORT_ENTRY.do?option="+option+"&reportdate="+reportdate+"&logind="+loginid);
                    xmlhttp.send();
                }
            });
            //CHANGE EVENT FOR REPORT TEXTAREA
            $(document).on('change','#ARE_ta_report',function(){

                $('#ARE_btn_submit').show();
                $('#ARE_btn_submit').attr('disabled','disabled');
                $('#ARE_lbl_errmsg').hide();
//        $('#ARE_tble_bandwidth').html('');
            });
            //CHANGE EVENT FOR BANDWIDTH TEXTBX
            $(document).on('change blur','#ARE_tb_band',function(){
                var bandwidth=$('#ARE_tb_band').val();
                if(bandwidth > 1000)
                {
                    var msg=err_msg[9].toString().replace("[BW]",bandwidth);
                    $('#ARE_lbl_errmsg').text(msg).show();
                    $("ARE_btn_submit").attr("disabled", "disabled");
                }
                else
                {
                    $('#ARE_lbl_errmsg').hide();
                }
            });
            // FUNCTION FOR PROJECT LIST
            function projectlist(){
                var project_list;
                for (var i=0;i<project_array.length;i++) {
                    project_list += '<tr><td><input type="checkbox" id ="checkbox" name="checkbox[]" value="' + project_array[i][1] + '">' + project_array[i][0] + ' - '+ project_array[i][2]+'</td></tr>';
                }
                $('#ARE_tble_frstsel_projectlistbx').append(project_list).show();
            }
            //FUNCTION FOR REASON
            function ARE_reason(){
                $('<div class="row-fluid" style="padding-top:10px"><label name="ARE_lbl_reason" class="col-sm-2"  id="ARE_lbl_reason" >REASON<em>*</em></label><div class="col-lg-10"><textarea  name="ARE_ta_reason" id="ARE_ta_reason" class="form-control tarea"></textarea></div></div>').appendTo($("#ARE_tble_reasonlbltxtarea"));
                $('textarea').autogrow({onInitialize: true});
            }
            //FUNCTION FOR MULTIPLE ENTRY REASON
            function ARE_mulreason(){
                $('<div class="row-fluid"><label name="ARE_lbl_reason"  class="col-sm-2" id="ARE_lbl_reason" >REASON<em>*</em></label><div class="col-lg-10"><textarea  name="ARE_ta_reason" id="ARE_ta_reason" class="form-control tarea"></textarea></div></div>').appendTo($("#ARE_tbl_reason"));
                $('textarea').autogrow({onInitialize: true});
            }
            // FUNCTION FOR REPORT
            function ARE_report(){
                $('<div class="row-fluid"><label name="ARE_lbl_report" class="col-sm-2" id="ARE_lbl_report">ENTER THE REPORT<em>*</em></label><div class="col-sm-8"><textarea  name="ARE_ta_report" id="ARE_ta_report" class="form-control tarea"></textarea></div></div>').appendTo($("#ARE_tbl_enterthereport"));
                $('textarea').autogrow({onInitialize: true});
            }
            // FUNCTION FOR BANDWIDTH
            function ARE_tble_bandwidth(){
                $('<div class="row-fluid" style="padding-left: 3px"><label name="ARE_lbl_band" class="col-sm-2" id="ARE_lbl_band" >BANDWIDTH<em>*</em></label><div class="col-sm-4"><input type="text" name="ARE_tb_band" id="ARE_tb_band" class="autosize amountonly" style="width:75px;"><label name="ARE_lbl_band" id="ARE_lbl_band">MB</label></div></div>').appendTo($("#ARE_tble_bandwidth"));
                $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:4,imaginary:2}});
            }
            //FORM VALIDATION
            $(document).on('change','#ARE_form_adminreportentry',function(){
                if($("input[name=entry]:checked").val()=="SINGLE DAY ENTRY"){
                    var ARE_sessionlstbx= $("#ARE_lb_ampm").val();
                    var ARE_reasontxtarea =$("#ARE_ta_reason").val();
                    var ARE_reportenter =$("#ARE_ta_report").val();
                    var ARE_bndtxt = $("#ARE_tb_band").val();
                    var ARE_projectselectlistbx = $("input[id=checkbox]").is(":checked");
                    var ARE_permissionlstbx = $("#ARE_lb_timing").val();
                    var ARE_permission=$("input[name=permission]:checked").val()=="PERMISSION";
                    var ARE_nopermission=$("input[name=permission]:checked").val()=="NOPERMISSION";
                    var ARE_presenthalfdysvld=$("#ARE_lb_attendance").val();
                    if(((ARE_presenthalfdysvld=='0') && (ARE_sessionlstbx=='AM' || ARE_sessionlstbx=="PM")) || ((ARE_presenthalfdysvld=='OD') && (ARE_sessionlstbx=='AM' || ARE_sessionlstbx=="PM") ))
                    {
                        if(((ARE_reasontxtarea.trim()!="")&&(ARE_reportenter!='')&&( ARE_projectselectlistbx==true) &&(ARE_bndtxt!='')&& (ARE_bndtxt<=1000) && ((ARE_permission==true) || (ARE_nopermission==true))))
                        {
                            if(ARE_permission==true)
                            {
                                if(ARE_permissionlstbx!='SELECT')
                                {
                                    $("#ARE_btn_submit").removeAttr("disabled");
                                }
                                else
                                {
                                    $("#ARE_btn_submit").attr("disabled", "disabled");
                                }
                            }
                            else
                            {
                                $("#ARE_btn_submit").removeAttr("disabled");
                            }
                        }
                        else
                        {
                            $("#ARE_btn_submit").attr("disabled", "disabled");
                        }
                    }
                    else if((ARE_presenthalfdysvld=='0' && ARE_sessionlstbx=='FULLDAY') || (ARE_presenthalfdysvld=='OD' && ARE_sessionlstbx=='FULLDAY'))
                    {
                        if(ARE_reasontxtarea.trim()=="")
                        {
                            $("#ARE_btn_submit").attr("disabled", "disabled");
                        }
                        else
                        {
                            $("#ARE_btn_submit").removeAttr("disabled");
                        }
                    }
                    else if(ARE_presenthalfdysvld=='1')
                    {
                        if(((ARE_reportenter.trim()!="")&&(ARE_bndtxt!='')&&(ARE_bndtxt<=1000)&&( ARE_projectselectlistbx==true) && ((ARE_permission==true) || (ARE_nopermission==true))))
                        {
                            if(ARE_permission==true)
                            {
                                if(ARE_permissionlstbx!='SELECT')
                                {
                                    $("#ARE_btn_submit").removeAttr("disabled");
                                }
                                else
                                {
                                    $("#ARE_btn_submit").attr("disabled", "disabled");
                                }
                            }
                            else
                            {
                                $("#ARE_btn_submit").removeAttr("disabled");
                            }
                        }
                        else
                        {
                            $("#ARE_btn_submit").attr("disabled", "disabled");
                        }
                    }
                    else if(ARE_presenthalfdysvld=='2')
                    {
                        if(((ARE_reportenter.trim()!="")&&( ARE_projectselectlistbx==true)))

                            if((ARE_reportenter.trim()!=""))
                            {
                                if(ARE_permissionlstbx!="")
                                {

                                    $("#ARE_btn_submit").removeAttr("disabled");
                                }
                                else
                                {

                                    $("#ARE_btn_submit").attr("disabled", "disabled");
                                }
                            }
                            else
                            {
                                $("#ARE_btn_submit").attr("disabled", "disabled");
                            }
                    }
                }
                else if($("input[name=entry]:checked").val()=="MULTIPLE DAY ENTRY"){
                    var ARE_reasontxtarea =$("#ARE_ta_reason").val();
                    var ARE_presenthalfdysvld=$("#ARE_lb_attdnce").val();
                    if((ARE_presenthalfdysvld=='0') || (ARE_presenthalfdysvld=='OD'))
                    {
                        if(ARE_reasontxtarea.trim()=="")
                        {
                            $("#ARE_btn_save").attr("disabled", "disabled");
                        }
                        else
                        {
                            $("#ARE_btn_save").removeAttr("disabled");
                        }
                    }
                }
            });
            // CLICK EVENT FOR SAVE BUTTON
            $(document).on('click','#ARE_btn_submit',function(){
//                $('#ARE_chk_notinfrmd').html('');
//                $('.preloader', window.parent.document).show();
                $(".preloader").show();
                var formElement = document.getElementById("ARE_form_adminreportentry");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var msg_alert=xmlhttp.responseText;
                        $(".preloader").hide();
                        if(msg_alert==1){
//                            if(flag == "X")
//                            {
//                                $('input:checkbox[id=notinformed]').attr('checked',true)
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN REPORT ENTRY",msgcontent:err_msg[0],position:{top:100,left:100}}});
                            show_msgbox("ADMIN REPORT ENTRY",err_msg[0],"success",false);
                            $('#ARE_lbl_dte').hide();
                            $('#ARE_tb_date').hide();
                            ARE_clear();
                            $("#ARE_lb_loginid").val('SELECT').show();
                            $('#ARE_chk_notinfrmd').hide();
                        }
//                        }
                        else if(msg_alert==0)
                        {
//                            $('input:checkbox[id=notinformed]').attr('checked',false)
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN REPORT ENTRY",msgcontent:err_msg[4],position:{top:100,left:100}}});
                            show_msgbox("ADMIN REPORT ENTRY",err_msg[4],"success",false);
                            $('#ARE_lbl_dte').hide();
                            $('#ARE_tb_date').hide();
                            ARE_clear();
                            $("#ARE_lb_loginid").val('SELECT').show();
                            $('#ARE_chk_notinfrmd').hide();
                        }
                        else
                        {
                            show_msgbox("ADMIN REPORT ENTRY",msg_alert,"success",false);
                            $('#ARE_lbl_dte').hide();
                            $('#ARE_tb_date').hide();
                            ARE_clear();
                            $("#ARE_lb_loginid").val('SELECT').show();
                            $('#ARE_chk_notinfrmd').hide();
                        }

                    }
                }
                var choice="SINGLE DAY ENTRY";
                xmlhttp.open("POST","ADMIN/DB_DAILY_REPORTS_ADMIN_REPORT_ENTRY.do?choice="+choice+"&checkoutlocation="+checkoutlocation,true);
                xmlhttp.send(new FormData(formElement));
            });
            // CHANGE EVENT FOR OPTION BUTTON
            $(document).on('change','.adminselectoption',function(){
//        $('.adminselectoption').change(function(){
                var adminselectoptionvalue=$('#option').val()
                $('#ARE_rd_sinentry').attr('checked',false);
                $('#ARE_rd_mulentry').attr('checked',false);
                $('#ARE_form_dailyuserentry').hide();
                $('#ARE_lbl_checkmsg').hide();
                $('#ARE_lbl_multipleday').hide();
                $('#single_emp').hide();
                $('#all_emp').hide();
                $("#ARE_chk_notinfrmd").html('')
                if(adminselectoptionvalue=='ADMIN REPORT ENTRY')
                {

                    $('#day_entry').html('').append('<div class="row-fluid"><div class="radio"><label name="entry" class="col-sm-12" id="ARE_lbl_sinentry" hidden><input type="radio" id="ARE_rd_sinentry"  name="entry" value="SINGLE DAY ENTRY" hidden/>SINGLE DAY ENTRY</label></div></div>');
                    $('#day_entry').show();
                    $('#multiple_day').html('').append(' <div class="row-fluid"><div class="radio"><label name="entry" class="col-sm-12" id="ARE_lbl_mulentry" hidden><input type="radio" id="ARE_rd_mulentry" name="entry" value="MULTIPLE DAY ENTRY" hidden/>MULTIPLE DAY ENTRY</label></div></div>');
                    $('#multiple_day').show();
                    $('#multiple_label').html('').append('<div class="row-fluid "><label name="ARE_lbl_multipleday" id="ARE_lbl_multipleday" class="srctitle" hidden>MULTIPLE DAY ENTRY</label></div>');
                    $("#ARE_lbl_sinentry").show();
                    $("#ARE_rd_sinentry").show();
                    $("#ARE_lbl_mulentry").show();
                    $("#ARE_rd_mulentry").show();
                    $('#ARE_rd_sinentry').attr('checked',false);
                    $('#ARE_rd_mulentry').attr('checked',false);
                    $('#ARE_tble_ondutyentry').hide();
                    $('#ARE_lbl_oderrmsg').hide();
                }
                else if((adminselectoptionvalue=='ONDUTY REPORT ENTRY'))
                {
                    $('#ARE_tble_ondutyentry').show();
                    $('#onduty_date').html('').append('<div class="row-fluid"><label name="ARE_lbl_dte" class="col-sm-2" id="ARE_lbl_dte">DATE</label><div class="col-sm-8"><input type="text" id="ARE_tb_dte" name="ARE_tb_dte" class="proj datemandtry enable ondutydayentry" style="width:75px;"/></div></div>');
                    $('#onduty_date').show();
                    $('#day_entry').html('');
                    $('#multiple_day').html('');
                    $('#ARE_notinfrmd').html('');
                    $('#ARE_rd_notinformed').html('');
                    $('#ARE_lbl_notinformed').html('');
                    $('#multiple_label').html('');
                    $('#single_emp').html('');
                    $('#all_emp').html('');
                    $('#ARE_tble_singledayentry').hide();
                    $('#ARE_lbl_emp').hide();
                    $('#onduty_des').hide();
                    $('#ARE_btn_odsubmit').hide();
                    $('#ARE_lbl_multipleday').hide();
                    $('#ARE_tb_dte').datepicker({
                        dateFormat:"dd-mm-yy",
                        changeYear: true,
                        changeMonth: true
                    });
                    $('#ARE_lbl_session').hide();
                    $('#ARE_tble_singledayentry').hide();
                    $('#ARE_tble_mutipledayentry').hide();
                    $('#ARE_tble_attendence').hide();
                    ARE_clear()
                    ARE_mulclear()
                    $('#ARE_lbl_errmsg').text('').show();
                    $("#ARE_ta_des").val('');
                    $("#ARE_tb_dte").val('').show();
                    $('#ARE_lbl_dte').show();
                    $("#ARE_btn_odsubmit").attr("disabled", "disabled");
                    $("#ARE_lbl_sinentry").hide();
                    $("#ARE_rd_sinentry").hide();
                    $("#ARE_lbl_mulentry").hide();
                    $("#ARE_rd_mulentry").hide();
                    $('#ARE_msg').hide();
                    $("#ARE_lbl_multipleday").hide();
                    $("#ARE_rd_sinemp").hide();
                    $("#ARE_lbl_sinemp").hide();
                    $("#ARE_rd_allemp").hide();
                    $("#ARE_lbl_allemp").hide();
                }
                else
                {
                    $('#ARE_tble_singledayentry').hide();
                    $('#ARE_tble_mutipledayentry').hide();
                    $('#ARE_tble_ondutyentry').hide();
                    $('#ARE_tble_attendence').hide();
                    ARE_clear()
                    $('#ARE_rd_sinentry').attr('checked',false);
                    $('#ARE_rd_mulentry').attr('checked',false);
                    $("#ARE_lbl_sinentry").hide();
                    $("#ARE_rd_sinentry").hide();
                    $("#ARE_lbl_mulentry").hide();
                    $("#ARE_rd_mulentry").hide();
                }
            });
            // CLICK EVENT FOR SINGLEDAYENTRY RADIO BUTTON
            $(document).on('click','#day_entry',function(){
                $('#ARE_rd_sinentry').attr('checked',true)
                $('#ARE_lbl_loginid').show();
                $('#ARE_chk1_notinfrmd').html('');
                $('#single_emp').html('');
                $('#all_emp').html('');
                $('#ARE_lb_loginid').val('SELECT').show();
                var login_list='<option>SELECT</option>';
                for (var i=0;i<login_id.length;i++) {
                    login_list += '<option value="' + login_id[i][1] + '">' + login_id[i][0] + '</option>';
                }
                $('#ARE_lb_loginid').html(login_list);
                $('#ARE_tble_singledayentry').show();
                $('#ARE_lbl_session').hide();
                $('#ARE_tble_ondutyentry').hide();
                $('#ARE_tble_mutipledayentry').hide();
                $('#ARE_lbl_reason').hide();
                $('#ARE_ta_reason').hide();
                $('#ARE_tbl_attendence').hide();
                $('#ARE_btn_save').hide();
                $('#ARE_lbl_dte').hide();
                $('#ARE_tb_date').hide();
                $('#ARE_lbl_attdnce').hide();
                $('#ARE_lb_attdnce').hide();
                $("#ARE_lbl_multipleday").hide();
                $("#ARE_rd_sinemp").hide();
                ARE_clear()
                $("#ARE_lbl_sinemp").hide();
                $("#ARE_rd_allemp").hide();
                $("#ARE_lbl_allemp").hide();
                $('#ARE_msg').hide();
            });
            // CLICK EVENT FOR MULTIPLEDAYENTRY RADIO BUTTON
            $("#multiple_day").click(function(){
                $('#ARE_chk1_notinfrmd').html('');
                $('#single_emp').html('').append('<div class="row-fluid"><div class="radio"><label name="ARE_lbl_emp" class="col-sm-12" id="ARE_lbl_sinemp" hidden><input type="radio" id="ARE_rd_sinemp" name="ARE_rd_emp" value="FOR SINGLE EMPLOYEE" hidden/>FOR SINGLE EMPLOYEE</label></div></div>');
                $('#single_emp').show();
                $('#all_emp').html('').append('<div  class="row-fluid" ><div class="radio"><label name="ARE_lbl_emp" class="col-sm-12" id="ARE_lbl_allemp" hidden><input type="radio" id="ARE_rd_allemp" name="ARE_rd_emp" value="FOR ALL EMPLOYEE"hidden/>FOR ALL EMPLOYEE</label></div></div>');
                $('#all_emp').show();
                $('#report_search').attr('checked',true);
                $('#ARE_rd_mulentry').attr('checked',true);
                $("#ARE_lbl_multipleday").show();
//        $('#multiple_label').show();
                $("#ARE_rd_sinemp").show();
                $('#ARE_lb_attdnce').hide();
                $('#ARE_lbl_attdnce').hide();
                $("#ARE_lbl_sinemp").show();
                $("#ARE_rd_allemp").show();
                $("#ARE_lbl_allemp").show();
                $('#ARE_lbl_loginid').hide();
                $('#ARE_lb_loginid').hide();
                $('#ARE_lbl_dte').hide();
                $('#ARE_tb_date').hide();
                $('#ARE_chk_notinfrmd').hide();
                $('#ARE_lbl_errmsg').hide();
                $('#ARE_rd_sinemp').attr('checked',false);
                $('#ARE_rd_allemp').attr('checked',false);
                ARE_clear()
                $('#ARE_lbl_checkmsg').hide();
            });
            //CLICK EVENT FOR SINGLE EMPLOYEE RADIO BUTTON
            $("#single_emp").click(function(){
                $('#ARE_chk1_notinfrmd').html('');
//                $('#ARE_lb_attdnce').hide();
//                $('#ARE_lbl_attdnce').hide();
                $('#ARE_tble_mutipledayentry').show();
                $('#ARE_tble_singledayentry').hide();
                $('#ARE_lbl_lgnid').show();
                $('#ARE_lb_lgnid').val('SELECT').show();
                $("#ARE_lbl_sdte").hide();
                $("#ARE_tb_sdate").hide().val('');
                $("#ARE_lbl_edte").hide();
                $("#ARE_tb_edate").hide().val('');
                $('#ARE_tb_sdate').datepicker("option","minDate",mindate);
                $('#ARE_tb_sdate').datepicker("option","maxDate",max_date);
                $('#ARE_msg').hide();
                ARE_clear()
                ARE_mulclear()
            });
            // CLICK EVENT FOR ALL EMPLOYEE RADIO BUTTON
            $("#all_emp").click(function(){
                $('#ARE_chk1_notinfrmd').html('');
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var allmindate=xmlhttp.responseText;
                        $('#ARE_tb_sdate').datepicker("option","minDate",allmindate);
                        var maxdate=new Date();
                        var month=maxdate.getMonth()+1;
                        var year=maxdate.getFullYear();
                        var date=maxdate.getDate();
                        var allmaxdate = new Date(year,month,date);
                        $('#ARE_tb_sdate').datepicker("option","maxDate",allmaxdate);
                    }
                }
                var choice="ALLEMPDATE"
                xmlhttp.open("GET","ADMIN/DB_DAILY_REPORTS_ADMIN_REPORT_ENTRY.do?&option="+choice,true);
                xmlhttp.send();
                $('#ARE_tble_mutipledayentry').show();
                $('#ARE_tble_singledayentry').hide();
                $('#ARE_lbl_lgnid').hide();
                $('#ARE_lb_lgnid').hide();
                $('#ARE_lb_lgnid').prop('selectedIndex',0);
                $("#ARE_lbl_sdte").show();
                $("#ARE_tb_sdate").show().val('');
                $("#ARE_lbl_edte").show();
                $("#ARE_tb_edate").show().val('');
//        $('#ARE_tb_sdate').datepicker("option","minDate",null);
//        $('#ARE_tb_sdate').datepicker("option","maxDate",null);
                $('#ARE_table_attendence').hide();
                $('#ARE_lbl_attdnce').hide();
                $('#ARE_lb_attdnce').hide();
                $('#ARE_lbl_reason').hide();
                $('#ARE_ta_reason').hide();
                $('#ARE_btn_save').hide();
                $('#ARE_msg').hide();
                ARE_clear()
            });
            // CHANGE EVENT FOR MULTIPLEDAY ENTRY LOGIN LIST BOX
            $(document).on('change','#ARE_lb_lgnid',function(){
                $("#ARE_lbl_sdte").show();
                $("#ARE_tb_sdate").show().val('');
                $("#ARE_lbl_edte").show();
                $("#ARE_tb_edate").show().val('');
            });
            // DATEPICKER FUNCTION FOR FROMDATE AND TODATE
            $('.change').datepicker({
                dateFormat:"dd-mm-yy",
                changeYear: true,
                changeMonth: true
            });
            var mindate;
            var max_date;
            // CHANGE EVENT FOR MULTIPLE DAY LOGINID LISTBOX
            $(document).on('change','#ARE_lb_lgnid',function(){
//        $("html, body").animate({ scrollTop: $(document).height() }, "1000");
                $('#ARE_lbl_errmsg').hide();
                $('#ARE_chk1_notinfrmd').hide();
                $('#ARE_msg').hide();
                var ARE_loginidlist= $("#ARE_lb_lgnid").val();
                $('#ARE_tble_attendence').hide();
                if(ARE_loginidlist=='SELECT')
                {
                    $('#ARE_table_attendence').hide();
                    $("#ARE_lbl_sdte").hide();
                    $('#ARE_lb_attdnce').hide();
                    $('#ARE_msg').hide();
                    $('#ARE_lbl_attdnce').hide();
                    $("#ARE_tb_sdate").hide()
                    $("#ARE_lbl_edte").hide();
                    $("#ARE_tb_edate").hide();
                    $('#ARE_lbl_reason').hide();
                    $('#ARE_ta_reason').hide();
                    ARE_clear()
                }
                else
                {
//                    $('.preloader', window.parent.document).show();
                    $(".preloader").show();
                    var loginid=$('#ARE_lb_lgnid').val();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {

                            var mindate=xmlhttp.responseText;
//                            $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
                            $('#ARE_tb_sdate').datepicker("option","minDate",mindate);
                            var max_date=new Date();
                            var month=max_date.getMonth()+1;
                            var year=max_date.getFullYear();
                            var date=max_date.getDate();
                            var max_date = new Date(year,month,date);
                            $('#ARE_tb_sdate').datepicker("option","maxDate",max_date);
                        }
                    }
                    var choice="LOGINID"
                    xmlhttp.open("GET","ADMIN/DB_DAILY_REPORTS_ADMIN_REPORT_ENTRY.do?login_id="+loginid+"&option="+choice,true);
                    xmlhttp.send();

                    ARE_clear()
                    $('#ARE_lbl_sdte').show();
                    $('#ARE_tb_sdate').val('').show();
                    $("#ARE_lbl_edte").show();
                    $("#ARE_tb_edate").show();
                    $('#ARE_lbl_reason').hide();
                    $('#ARE_ta_reason').hide();
                    $('#ARE_tble_attendence').hide();
                    $('#ARE_lbl_attdnce').hide();
                    $('#ARE_lb_attdnce').hide();
                    $('#ARE_btn_save').hide();
                }
            });
            // CHANGE EVENT FOR FROMDATE
            $(document).on('change','#ARE_tb_sdate',function(){
//        $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                var ARE_fromdate = $('#ARE_tb_sdate').datepicker('getDate');
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                var date = new Date( Date.parse( ARE_fromdate ));
                date.setDate( date.getDate()  );
                var ARE_todate = date.toDateString();
                ARE_todate = new Date( Date.parse( ARE_todate ));
                $('#ARE_tb_edate').datepicker("option","minDate",ARE_todate);
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                var max_date=new Date();
                var month=max_date.getMonth()+1;
                var year=max_date.getFullYear();
                var date=max_date.getDate();
                var max_date = new Date(year,month,date);
                $('#ARE_tb_edate').datepicker("option","maxDate",max_date);
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
            });
            // CHANGE FUNCTIOn FOR TODATE
            $(document).on('change','.valid',function(){
//        $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                $('#ASRC_chk_notinformed').hide();
                var loginid=$('#ARE_lb_lgnid').val();
                var fromdate=$('#ARE_tb_sdate').val();
                var todate=$('#ARE_tb_edate').val();
                if(fromdate!='' && todate!='')
                {
//                    $('.preloader', window.parent.document).show();
                    $(".preloader").show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                            $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
                            var date_array=JSON.parse(xmlhttp.responseText);
                            var error_date='';
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
                                $('#ARE_tbl_attendence').show();
                                $('#ARE_lbl_attdnce').show();
                                $('#ARE_lb_attdnce').val('SELECT').show();
                                $('#ARE_msg').text(msg).hide();
                            }
                            else
                            {
                                var msg=err_msg[3].toString().replace("[DATE]",error_date);
                                $('#ARE_msg').text(msg).show();
//                    $('#ARE_tb_sdate').val('').show();
//                    $('#ARE_tb_edate').val('').show();
                                $('#ARE_tbl_attendence').hide();
                                $('#ARE_lbl_attdnce').hide();
                                $('#ARE_lb_attdnce').val('SELECT').hide();
                            }
                        }
                    }
                    var option="BETWEEN DATE";
                    xmlhttp.open("GET","ADMIN/DB_DAILY_REPORTS_ADMIN_REPORT_ENTRY.do?option="+option+"&fromdate="+fromdate+"&todate="+todate+"&loginid="+loginid,true);
                    xmlhttp.send();
                }
            });
            //CHANGE EVENT FOR MULTIPLEDAY ATTENDANCE
            $('#ARE_lb_attdnce').change(function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "1000");
                $('#ARE_tbl_reason').html('');
                if($('#ARE_lb_attdnce').val()=='SELECT')
                {
                    $('#ARE_lbl_reason').hide();
                    $('#ARE_ta_reason').hide();
                    $('#ARE_btn_save').hide();
                }
                else if($('#ARE_lb_attdnce').val()=='0')
                {

                    ARE_mulreason()
//                    alert('alert')
                    var ARE_chk_notinformed='<div class="form-group"><label class="col-sm-2"></label><div class="col-sm-9"><div class="col-md-4"><div class="row-fluid"><div class="checkbox"><label name="ARE_noinformed" class="col-sm-8" id="ARE_lbl_notinformed"><input type="checkbox" name="notinformed" id="ARE_rd_notinformed" value="NOTINFORMED" >NOT INFORMED</label></div></div></div></div></div>';
                    $('#ARE_chk1_notinfrmd').html(ARE_chk_notinformed).show();
                    $('#ARE_lbl_reason').show();
                    $('#ARE_ta_reason').show();
                    $('#ARE_btn_save').show();
                }
                else if($('#ARE_lb_attdnce').val()=='OD')
                {
                    ARE_mulreason()
                    $('#ARE_chk1_notinfrmd').html('');
                    $('#ARE_lbl_reason').show();
                    $('#ARE_ta_reason').show();
                    $('#ARE_btn_save').show();
                }
            });
            //CLICK EVENT FOR MULTIPLEDAY SAVE BUTTON
            $('#ARE_btn_save').click(function(){
                $(".preloader").show();
                var formElement = document.getElementById("ARE_form_adminreportentry");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var msg_alert=xmlhttp.responseText;
                        $(".preloader").hide();
                        if(msg_alert==1){
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN REPORT ENTRY",msgcontent:err_msg[0],position:{top:100,left:100}}});
                            show_msgbox("ADMIN REPORT ENTRY",err_msg[0],"success",false);
                            ARE_mulclear()
                            $('#ARE_lbl_lgnid').hide();
                            $('#ARE_lb_lgnid').hide();
                            $('#ARE_rd_sinemp').attr('checked',false);
                            $('#ARE_rd_allemp').attr('checked',false);
                            $('#ARE_chk1_notinfrmd').html('');
                        }
                        else if(msg_alert==0)
                        {
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN REPORT ENTRY",msgcontent:err_msg[4],position:{top:100,left:100}}});
                            show_msgbox("ADMIN REPORT ENTRY",err_msg[4],"success",false);
                            ARE_mulclear()
                            $('#ARE_lbl_lgnid').hide();
                            $('#ARE_lb_lgnid').hide();
                            $('#ARE_rd_sinemp').attr('checked',false);
                            $('#ARE_rd_allemp').attr('checked',false);
                            $('#ARE_chk1_notinfrmd').html('');
                        }
                        else
                        {
                            show_msgbox("ADMIN REPORT ENTRY",msg_alert,"success",false);
                            ARE_mulclear()
                            $('#ARE_lbl_lgnid').hide();
                            $('#ARE_lb_lgnid').hide();
                            $('#ARE_rd_sinemp').attr('checked',false);
                            $('#ARE_rd_allemp').attr('checked',false);
                            $('#ARE_chk1_notinfrmd').html('');
                        }
                    }
                }
                var choice="MULTIPLE DAY ENTRY";
                xmlhttp.open("POST","ADMIN/DB_DAILY_REPORTS_ADMIN_REPORT_ENTRY.do?choice="+choice+"&reportlocation="+checkoutlocation,true);
                xmlhttp.send(new FormData(formElement));
            });
// FUNCTION FORM MULTIPLE ENTRY CLEAR FUNCTION
            function ARE_mulclear()
            {
                $('#ARE_lbl_sdte').hide();
                $('#ARE_tb_sdate').hide();
                $('#ARE_lbl_edte').hide();
                $('#ARE_tb_edate').hide();
                $('#ARE_lbl_reason').hide();
                $('#ARE_ta_reason').hide();
                $('#ARE_tbl_reason').html('');
                $('#ARE_btn_save').hide();
                $('#ARE_tbl_attendence').hide();
                $('#ARE_lbl_attdnce').hide();
                $('#ARE_lb_attdnce').hide();
                $('#ARE_lbl_norole_err').hide();
            }
            // ONDUTY ENTRY VALIDATION STARTS
            $('#onduty_des').change(function(){
                if($("#ARE_ta_des").val()=='')
                {
                    $("#ARE_btn_odsubmit").attr("disabled", "disabled");
                }
                else
                {
                    $("#ARE_btn_odsubmit").removeAttr("disabled");
                    $("#ARE_btn_odsubmit").show();
                }
            });
            //CHANGE EVENT ONDUTY ENTRY DATE FUNCTION
            $('#onduty_date').change(function(){
                $(".preloader").show();
                var reportdate=$('#ARE_tb_dte').val();
                $('#ARE_btn_odsubmit').attr('disabled','disabled');
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var msgalert=xmlhttp.responseText;
                        $(".preloader").hide();
                        if(msgalert==1)
                        {

                            var msg=err_msg[3].toString().replace("[DATE]",reportdate);
                            $('#ARE_lbl_oderrmsg').text(msg).show();
                            $('#ARE_tb_dte').val('').show();
                            $('#onduty_des').hide();

                            $('#ARE_btn_odsubmit').hide();
                        }
                        else
                        {
                            $('#ARE_lbl_oderrmsg').text(msg).hide();
                            $('#ARE_rd_notinformed').show();
                            $('#ARE_lbl_notinformed').show();
                            $('#ARE_lbl_des').show();
                            $('#onduty_des').html('').append('<div class="row-fluid "><label name="ARE_lbl_des" class="col-sm-2" id="ARE_lbl_des" hidden>DESCRIPTION</label><div class="col-lg-10"><textarea id="ARE_ta_des" name="ARE_ta_des" class="enable form-control tarea" hidden></textarea></div></div>');
                            $('#onduty_des').show();
                            $("#ARE_lbl_des").show();
                            $("#ARE_ta_des").show();
                            $('#onduty_button').html('').append('<div style="padding-left:15px"><input type="button" id="ARE_btn_odsubmit" name="ARE_btn_odsubmit" value="SAVE" class="btn" disabled hidden /></div>');
                            $('#onduty_button').show();
                            $("#ARE_btn_odsubmit").show();
                        }
                    }

                }
                var choice="ODDATE"
                xmlhttp.open("GET","ADMIN/DB_DAILY_REPORTS_ADMIN_REPORT_ENTRY.do?date_change="+reportdate+"&option="+choice,true);
                xmlhttp.send();
            });
// CLICK EVENT ONDUTY SAVE BUTTON
            $('#onduty_button').click(function(){
                $(".preloader").show();
                var formElement = document.getElementById("ARE_form_adminreportentry");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var msg_alert=xmlhttp.responseText;
                        $(".preloader").hide();
                        show_msgbox("ONDUTY ENTRY",msg_alert,"success",false);
                        $('#ARE_tb_dte').val('');
                        $('#ARE_tb_dte').hide();
                        $('#ARE_ta_des').val('');
                        $('#ARE_lbl_des').hide();
                        $("#ARE_ta_des").hide();
                        $("#ARE_lbl_dte").hide();
                        $("#ARE_btn_odsubmit").hide();
                        $('#ARE_lbl_dte').hide();
                        $('#ARE_tble_ondutyentry').hide();
                    }
                }
                var choice="ONDUTY";
                xmlhttp.open("POST","ADMIN/DB_DAILY_REPORTS_ADMIN_REPORT_ENTRY.do?choice="+choice,true);
                xmlhttp.send(new FormData(formElement));
            });

            //search update

            $('textarea').autogrow({onInitialize: true});
            $('#ASRC_UPD_DEL_btn_search').hide();
            $('#ASRC_UPD_DEL_btn_srch').hide();
            $('#ASRC_UPD_DEL_btn_srchupd').hide();
            $('#ASRC_UPD_DEL_btn_allsearch').hide();
            $('#ASRC_UPD_DEL_btn_srch').hide()
            //DATE PICKER FUNCTION
            $('.ASRC_UPD_DEL_date').datepicker({
                dateFormat:"dd-mm-yy",
                changeYear: true,
                changeMonth: true
            });
            //CHANGE EVENT FOR STARTDATE AND ENDDATE
            $(document).on('change','#ASRC_UPD_DEL_tb_strtdte,#ASRC_UPD_DEL_tb_enddte',function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                ASRC_UPD_DEL_clear()
                $('#ASRC_UPD_DEL_div_header').hide();
                $('#ASRC_UPD_btn_pdf').hide();
                $('#ASRC_UPD_DEL_div_headers').hide();
                $('#ASRC_UPD_btn_od_pdf').hide();
                $('#ASRC_UPD_DEL_tbl_htmltable').html('');
                $('#ASRC_UPD_DEL_btn_srch').hide();
                $('#ASRC_UPD_DEL_btn_del').hide();
                $('#ASRC_UPD_DEL_errmsg').hide();
                $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                $('#ASRC_UPD_DEL_ta_reportdate').hide();
                $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                $("#ASRC_UPD_DEL_chk_flag").hide();
                $("#ASRC_UPD_DEL_lbl_flag").hide();
                $('#ASRC_UPD_DEL_banerrmsg').hide();
            });
//    $('#ASRC_UPD_DEL_tb_dte').datepicker({
//        dateFormat:"dd-mm-yy",
//        changeYear: true,
//        changeMonth: true
//    });
            // CHANGE EVENT FOR STARTDATE
            $(document).on('change','#ASRC_UPD_DEL_tb_strtdte',function(){
                $('#ASRC_UPD_DEL_div_header').hide();
                $('#ASRC_UPD_btn_pdf').hide();
                $('#ASRC_UPD_DEL_div_headers').hide();
                $('#ASRC_UPD_btn_od_pdf').hide();
                $("#ASRC_UPD_DEL_startdate").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                var ASRC_UPD_DEL_startdate = $('#ASRC_UPD_DEL_tb_strtdte').datepicker('getDate');
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                var date = new Date( Date.parse( ASRC_UPD_DEL_startdate ));
                date.setDate( date.getDate()  );
                $("#ASRC_UPD_DEL_todate").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                var ASRC_UPD_DEL_todate = date.toDateString();
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                ASRC_UPD_DEL_todate = new Date( Date.parse( ASRC_UPD_DEL_todate ));
                $('#ASRC_UPD_DEL_tb_enddte').datepicker("option","minDate",ASRC_UPD_DEL_todate);
            });
            //CLICK EVENT FOR ALL ACTIVE EMPLOYEE SEARCH BUTTTON
            $(document).on('click','#ASRC_UPD_DEL_btn_allsearch',function(){
                $('#ASRC_UPD_DEL_div_header').hide();
                $('#ASRC_UPD_btn_pdf').hide();
                $('#ASRC_UPD_DEL_div_headers').hide();
                $('#ASRC_UPD_btn_od_pdf').hide();
                var ure_after_mrg;
//                $('.preloader', window.parent.document).show();
                $(".preloader").show();
                $("#ASRC_UPD_DEL_btn_allsearch").attr("disabled", "disabled");
                $('section').html('')
                $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                $('#ASRC_UPD_DEL_tbl_htmltable').show();
                var date=$('#ASRC_UPD_DEL_tb_dte').val();
                var activeloginid=$('#ASRC_UPD_DEL_lb_loginid').val();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                        $('.preloader', window.parent.document).hide();
                        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                        $(".preloader").hide();
                        allvalues_array=JSON.parse(xmlhttp.responseText);
                        if(allvalues_array!=''){
//                    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                            //HEADER ERR MSG
                            var errmsg=err_msg[12].replace('[DATE]',date);
                            pdfmsg=errmsg;
                            $('#ASRC_UPD_DEL_div_header').text(errmsg).show();
                            $('#ASRC_UPD_btn_pdf').show();
                            var ASRC_UPD_DEL_tableheader='<table id="ASRC_UPD_DEL_tbl_htmltable" border="1" class="srcresult" style="width:1600px"><thead  bgcolor="#6495ed" style="color:white"><tr><th nowrap>EMPLOYEE NAME</th><th style="width:1100px">REPORT</th><th>LOCATION</th><th style="width:90px">USERSTAMP</th><th style="width:100px" class="uk-timestp-column">TIMESTAMP</th></tr></thead><tbody>'
                            for(var j=0;j<allvalues_array.length;j++){
                                var report=allvalues_array[j].admreport;
                                var reason=allvalues_array[j].admreason;
                                var morningsession=allvalues_array[j].morningsession;
                                var afternoonsession=allvalues_array[j].afternoonsession;
                                var permission=allvalues_array[j].permission;
                                var userstamp=allvalues_array[j].admuserstamp;
                                var timestamp=allvalues_array[j].admtimestamp;
                                var login=allvalues_array[j].admlogin;
                                var location=allvalues_array[j].location;
                                if(location==null)
                                {
                                    location='';
                                }
                                if(permission==null)
                                {
                                    if(morningsession=='PRESENT'){
                                        ure_after_mrg=afternoonsession+'(PM)';
                                    }
                                    else
                                    {
                                        ure_after_mrg=morningsession+'(AM)';
                                    }
                                    if(report==null)
                                    {
                                        if(morningsession=='PRESENT'){
                                            ure_after_mrg=afternoonsession;
                                        }
                                        else
                                        {
                                            ure_after_mrg=morningsession;
                                        }
                                        ASRC_UPD_DEL_tableheader+='<tr ><td nowrap>'+login+'</td><td style="max-width:1000px; !important;"> '+ure_after_mrg +'  -  '+'REASON:'+reason+'</td><td width="245">'+location+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                                    }
                                    else if(reason==null)
                                    {
                                        ASRC_UPD_DEL_tableheader+='<tr ><td nowrap>'+login+'</td><td style="max-width:1000px; !important;">'+report+'</td><td width="245">'+location+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                                    }
                                    else
                                    {
                                        ASRC_UPD_DEL_tableheader+='<tr ><td nowrap>'+login+'</td><td style="max-width:1000px; !important;">'+report+' <br>'+ure_after_mrg +'  -  '+'REASON:'+reason+'</td><td width="245">'+location+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
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
                                    if(report==null)
                                    {
                                        if(morningsession=='PRESENT'){
                                            ure_after_mrg=afternoonsession;
                                        }
                                        else
                                        {
                                            ure_after_mrg=morningsession;
                                        }
                                        ASRC_UPD_DEL_tableheader+='<tr ><td nowrap>'+login+'</td><td style="max-width:1000px; !important;"> '+ure_after_mrg +' -  '+'REASON:'+reason+'<br>PERMISSION:'+permission+' hrs</td><td width="245">'+location+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                                    }
                                    else if(reason==null)
                                    {
                                        ASRC_UPD_DEL_tableheader+='<tr ><td nowrap>'+login+'</td><td style="max-width:1000px; !important;">'+report+' <br>PERMISSION:'+permission+' hrs</td><td width="245">'+location+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                                    }
                                    else
                                    {
                                        ASRC_UPD_DEL_tableheader+='<tr ><td nowrap>'+login+'</td><td style="max-width:1000px; !important;">'+report+' <br>'+ure_after_mrg +'  - '+'REASON:'+reason+' <br>PERMISSION:'+permission+' hrs</td><td width="245">'+location+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                                    }
                                }
                            }
                            ASRC_UPD_DEL_tableheader+='</tbody></table>';
                            $('#ASRC_UPD_DEL_tbl_htmltable').show();
                            $('section').html(ASRC_UPD_DEL_tableheader);
                            $('#ASRC_UPD_DEL_tbl_htmltable').DataTable( {
                                "aaSorting": [],
                                "pageLength": 10,
                                "sPaginationType":"full_numbers",
                                "aoColumnDefs" : [
                                    { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                            });
                        }
                        else
                        {
                            var sd=err_msg[8].toString().replace("[DATE]",date);
                            $('#ASRC_UPD_DEL_errmsg').text(sd).show();
                            $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                            $('#ASRC_UPD_DEL_div_header').hide();
                            $('#ASRC_UPD_btn_pdf').hide();
                            $('#ASRC_UPD_DEL_div_headers').hide();
                            $('#ASRC_UPD_btn_od_pdf').hide();
                        }
                    }
                }
                $('#ASRC_UPD_DEL_div_tablecontainer').show();
                var choice='ALLDATE';
                xmlhttp.open("GET","ADMIN/DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?alldate="+date+"&option="+choice,true);
                xmlhttp.send();
                sorting()
            });
            //CHANGE EVENT FOR BETWEEN RANGE RADIO BTN
            $(document).on('change','#ASRC_UPD_DEL_rd_btwnrange',function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                $('#between_range').html('').append('<div  class="row-fluid"style="padding-top: 10px" ><label name="ASRC_UPD_DEL_lbl_btwnranges" id="ASRC_UPD_DEL_lbl_btwnranges" class="srctitle" hidden>BETWEEN RANGE</label></div>');
                $('#between_range').show();
                $('#active_emp').html('').append('<div class="row-fluid"><div class="radio"><label name="ASRC_UPD_DEL_lbl_actveemp" class="col-sm-12" id="ASRC_UPD_DEL_lbl_actveemp"  hidden><input type="radio" name="ASRC_UPD_DEL_rd_veemp" id="ASRC_UPD_DEL_rd_actveemp" value="EMPLOYEE" hidden>ACTIVE EMPLOYEE</label></div></div>');
                $('#active_emp').show();
                $('#non_active').html('').append('<div class="row-fluid" ><div class="radio"><label name="ASRC_UPD_DEL_lbl_nonactveemp" class="col-sm-12" id="ASRC_UPD_DEL_lbl_nonactveemp"  hidden><input type="radio" name="ASRC_UPD_DEL_rd_veemp" id="ASRC_UPD_DEL_rd_nonactveemp"  value="EMPLOYEE" class="attnd" hidden>NON ACTIVE EMPLOYEE</label></div></div>');
                $('#non_active').show();
                $('#date_click').html('');
                $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                $('#ASRC_UPD_DEL_ta_reportdate').hide();
                $('#ASRC_UPD_DEL_rd_actveemp').attr('checked',false);
                $('#ASRC_UPD_DEL_rd_nonactveemp').attr('checked',false);
                $('#ASRC_UPD_DEL_rd_actveemp').show();
                $('#ASRC_UPD_DEL_lbl_actveemp').show();
                $('#ASRC_UPD_DEL_rd_nonactveemp').show();
                $('#ASRC_UPD_DEL_lbl_nonactveemp').show();
                $('#ASRC_UPD_DEL_lbl_btwnranges').show();
                $('#ASRC_UPD_DEL_lbl_dte').hide();
                $('#ASRC_UPD_DEL_tb_dte').hide();
                $('#ASRC_UPD_DEL_lbl_allactveemps').hide();
                $('#ASRC_UPD_DEL_lbl_session').hide();
                $('#ASRC_UPD_DEL_btn_allsearch').hide();
                $('#ASRC_UPD_DEL_tbl_htmltable').hide();
                $('#ASRC_UPD_DEL_ta_reason').hide();
                $('#ASRC_UPD_DEL_errmsg').hide();
                $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                $('#ASRC_UPD_DEL_div_header').hide();
                $('#ASRC_UPD_btn_pdf').hide();
                $('#ASRC_UPD_DEL_div_headers').hide();
                $('#ASRC_UPD_btn_od_pdf').hide();
                $("#ASRC_UPD_DEL_chk_flag").hide();
                $("#ASRC_UPD_DEL_lbl_flag").hide();
                $('#ASRC_UPD_DEL_banerrmsg').hide();
            });
//    $('.enable').change(function(){
            $('#date_click').change(function(){
                if($("#ASRC_UPD_DEL_tb_dte").val()=='')
                {
                    $("#ASRC_UPD_DEL_btn_allsearch").attr("disabled", "disabled");
                }
                else
                {
                    $("#ASRC_UPD_DEL_btn_allsearch").removeAttr("disabled");
                    $("#ASRC_UPD_DEL_btn_allsearch").show();
                }
            });
            //CHANGE EVENT FOR DATE
            $('#date_click').change(function(){
                $('#ASRC_UPD_DEL_errmsg').hide();
                $('#between_range').html('');
                $('#active_emp').html('');
                $('#non_active').html('');
                $('#ASRC_UPD_DEL_tbl_htmltable').html('');
                $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                $('#ASRC_UPD_DEL_div_header').hide();
                $('#ASRC_UPD_btn_pdf').hide();
                $('#ASRC_UPD_DEL_div_headers').hide();
                $('#ASRC_UPD_btn_od_pdf').hide();
            });
            //CHANGE EVENT FOR NON ACTIVE  RADIO
            $(document).on('change','#non_active',function(){
                $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                $('#ASRC_UPD_DEL_ta_reportdate').hide();
                $('#ASRC_UPD_DEL_lbl_loginid').show();
                $('#ASRC_UPD_DEL_lb_loginid').val('SELECT').show();
                $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                $('#ASRC_UPD_DEL_div_header').hide();
                $('#ASRC_UPD_btn_pdf').hide();
                $('#ASRC_UPD_DEL_div_headers').hide();
                $('#ASRC_UPD_btn_od_pdf').hide();
                $('#ASRC_UPD_DEL_btn_searchupd').hide();
                $('#ASRC_UPD_DEL_lbl_session').hide();
                $('#ASRC_UPD_DEL_lbl_strtdte').hide();
                $('#ASRC_UPD_DEL_tb_strtdte').hide();
                $('#ASRC_UPD_DEL_lbl_enddte').hide();
                $('#ASRC_UPD_DEL_tb_enddte').hide();
                $('#ASRC_UPD_DEL_btn_search').hide();
                $("#ASRC_UPD_DEL_btn_search").attr("disabled", "disabled");
                $('#ASRC_UPD_DEL_errmsg').hide();
                $("#ASRC_UPD_DEL_chk_flag").hide();
                $("#ASRC_UPD_DEL_lbl_flag").hide();
                $('#ASRC_UPD_DEL_banerrmsg').hide();
            });
            //CHANGE EVENT FOR ALL ACTIVE  RANGE RADIO BTN
            $(document).on('change','#ASRC_UPD_DEL_rd_allactveemp',function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                $('#date_click').html('').append('<div class="row-fluid form-group"><label name="ASRC_UPD_DEL_lbl_dte" class="col-sm-2" id="ASRC_UPD_DEL_lbl_dte" hidden>DATE</label><div class="col-sm-8"><input type="text" name="ASRC_UPD_DEL_tb_dte" id="ASRC_UPD_DEL_tb_dte" class="ASRC_UPD_DEL_date valid enable"   style="width:75px;"  hidden ></div></div>');
                $('#date_click').show();
                $('#search_click').html('').append('<div class="row-fluid form-group" style="padding-left: 15px" ><input type="button" class="btn" id="ASRC_UPD_DEL_btn_allsearch" onclick="buttonchange()"  value="SEARCH" hidden disabled></div>');
                $('#search_click').show();
                $('#ASRC_UPD_DEL_tb_dte').datepicker({
                    dateFormat:"dd-mm-yy",
                    changeYear: true,
                    changeMonth: true
                });
                $('#between_range').html('');
                $('#active_emp').html('');
                $('#non_active').html('');
                $('#ASRC_chk_notinformed').html('')
                $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                $('#ASRC_UPD_DEL_ta_reportdate').hide();
                $('#ASRC_UPD_DEL_lbl_allactveemps').show();
                $('#ASRC_UPD_DEL_btn_searchupd').hide();
                $('#ASRC_UPD_DEL_btn_search').hide();
                $('#ASRC_UPD_DEL_btn_srch').hide();
                $('#ASRC_UPD_DEL_tb_dte').show();
                $('#ASRC_UPD_DEL_lbl_dte').show();
                $('#ASRC_UPD_DEL_tb_dte').val('');
                $('#ASRC_UPD_DEL_btn_allsearch').show();
                $("#button_click").attr("disabled", "disabled");
                $('#ASRC_UPD_DEL_lbl_session').hide();
                $('#ASRC_UPD_DEL_lbl_nonactveemp').hide();
                $('#ASRC_UPD_DEL_rd_nonactveemp').hide();
                $('#ASRC_UPD_DEL_lbl_actveemp').hide();
                $('#ASRC_UPD_DEL_rd_actveemp').hide();
                $('#ASRC_UPD_DEL_lbl_btwnranges').hide();
                $('#ASRC_UPD_DEL_lbl_strtdte').hide();
                $('#ASRC_UPD_DEL_tb_strtdte').hide();
                $('#ASRC_UPD_DEL_lbl_enddte').hide();
                $('#ASRC_UPD_DEL_tb_enddte').hide();
                $('#ASRC_UPD_DEL_lbl_loginid').hide();
                $('#ASRC_UPD_DEL_lb_loginid').hide();
                $('#ASRC_UPD_DEL_tbl_htmltable').hide();
                $('#ASRC_UPD_DEL_errmsg').hide();
                ASRC_UPD_DEL_clear();
                $('#ASRC_UPD_DEL_btn_del').hide();
                $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                $('#ASRC_UPD_DEL_div_header').hide();
                $('#ASRC_UPD_btn_pdf').hide();
                $('#ASRC_UPD_DEL_div_headers').hide();
                $('#ASRC_UPD_btn_od_pdf').hide();
                $("#ASRC_UPD_DEL_chk_flag").hide();
                $("#ASRC_UPD_DEL_lbl_flag").hide();
                $('#ASRC_UPD_DEL_banerrmsg').hide();
            });
//    // CHANGE EVENT FOR LOGINID LISTBOX
//    $(document).on('change','#ASRC_UPD_DEL_lb_loginid',function(){
//        ASRC_UPD_DEL_clear();
//        $('#ASRC_UPD_DEL_btn_srch').hide();
//        $('#ASRC_UPD_DEL_btn_del').hide();
//        $('#ASRC_UPD_DEL_errmsg').hide();
//        $('#ASRC_UPD_DEL_lbl_reportdte').hide();
//        $('#ASRC_UPD_DEL_errmsg').hide();
//        $('#ASRC_UPD_DEL_ta_reportdate').hide();
//        $('#ASRC_UPD_DEL_div_tablecontainer').hide();
//        $('#ASRC_UPD_DEL_div_header').hide();
//        $('#ASRC_UPD_btn_pdf').hide();
//        $('#ASRC_UPD_DEL_div_headers').hide();
//        $('#ASRC_UPD_btn_od_pdf').hide();
//        $("#ASRC_UPD_DEL_chk_flag").hide();
//        $("#ASRC_UPD_DEL_lbl_flag").hide();
//        $('#ASRC_UPD_DEL_banerrmsg').hide();
//    });
            //CHANGE EVENT FOR BETWEN ACTIVE EMPLOYEE RADIO BTN
            $(document).on('change','#active_emp',function(){
                if($('#ASRC_UPD_DEL_rd_actveemp').attr('checked',true))
                {
                    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                    var active_employee='<option>SELECT</option>';
                    for (var i=0;i<active_emp.length;i++) {
                        active_employee += '<option value="' + active_emp[i][1] + '">' + active_emp[i][0] + '</option>';
                    }
                    $('#ASRC_UPD_DEL_lb_loginid').html(active_employee);
                }
                ASRC_UPD_DEL_clear()
                $('#ASRC_UPD_DEL_lbl_loginid').show();
                $('#ASRC_UPD_DEL_lb_loginid').val('SELECT').show();
                $('#ASRC_UPD_DEL_lbl_btwnranges').show();

                $('#ASRC_UPD_DEL_btn_submit').hide();
                $('#ASRC_UPD_DEL_lbl_strtdte').hide();
                $('#ASRC_UPD_DEL_tb_strtdte').hide();
                $('#ASRC_UPD_DEL_lbl_enddte').hide();
                $('#ASRC_UPD_DEL_tb_enddte').hide();
                $('#ASRC_UPD_DEL_btn_search').hide();
                $('#ASRC_UPD_DEL_btn_srch').hide();
                $('#ASRC_UPD_DEL_lbl_session').hide();
                $('#ASRC_UPD_DEL_tbl_htmltable').hide();
                $('#ASRC_UPD_DEL_errmsg').hide();
                $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                $('#ASRC_UPD_DEL_div_header').hide();
                $('#ASRC_UPD_btn_pdf').hide();
                $('#ASRC_UPD_DEL_div_headers').hide();
                $('#ASRC_UPD_btn_od_pdf').hide();
                $('#ASRC_UPD_DEL_banerrmsg').hide();
                $('#ASRC_UPD_DEL_btn_del').hide();
                $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                $('#ASRC_UPD_DEL_ta_reportdate').hide();
            });
            //CHANGE EVENT FOR BETWEN NON ACTIVE EMPLOYEE RADIO BTN
            $(document).on('change','#ASRC_UPD_DEL_rd_nonactveemp',function(){
                if($('#ASRC_UPD_DEL_rd_nonactveemp').attr('checked',true))
                {
                    var nonactive_employee='<option>SELECT</option>';
                    for (var i=0;i<nonactive_emp.length;i++) {
                        nonactive_employee += '<option value="' + nonactive_emp[i][1] + '">' + nonactive_emp[i][0] + '</option>';
                    }
                    $('#ASRC_UPD_DEL_lb_loginid').html(nonactive_employee);
                }
                $('#ASRC_UPD_DEL_lbl_loginid').show();
                $('#ASRC_UPD_DEL_lb_loginid').val('SELECT').show();
                ASRC_UPD_DEL_clear()
                $('#ASRC_UPD_DEL_lbl_btwnranges').show();
                $('#ASRC_UPD_DEL_lbl_strtdte').hide();
                $('#ASRC_UPD_DEL_tb_strtdte').hide();
                $('#ASRC_UPD_DEL_lbl_enddte').hide();
                $('#ASRC_UPD_DEL_tb_enddte').hide();
                $('#ASRC_UPD_DEL_btn_search').hide();
                $('#ASRC_UPD_DEL_btn_srch').hide();
                $('#ASRC_UPD_DEL_lbl_session').hide();
                $('#ASRC_UPD_DEL_tbl_htmltable').hide();
                $('#ASRC_UPD_DEL_btn_del').hide();
                $('#ASRC_UPD_DEL_errmsg').hide();
                $('#ASRC_UPD_DEL_banerrmsg').hide();
            });
            //CHANGE EVENT FOR LOGIN ID LISTBX
//    var min_date;
//        $(document).on('change','#ASRC_UPD_DEL_lb_loginid',function(){
            $(document).on('change','.emplistbxactve',function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
//                var ASRC_UPD_DEL_loginidlist =$("#ASRC_UPD_DEL_lb_loginid").val();
                var ASRC_UPD_DEL_loginidlist =$('#ASRC_UPD_DEL_lb_loginid').find('option:selected').text();
                $(".preloader").show();
                $('#ASRC_UPD_DEL_errmsg').hide();
                $('#ASRC_UPD_DEL_div_header').hide();
                $('#ASRC_UPD_btn_pdf').hide();
                $('section').html('');
                $('#ASRC_UPD_DEL_btn_srch').hide();
                $('#ASRC_chk_notinformed').hide();
                $('#ASRC_UPD_DEL_btn_srch').hide();
                $('#ASRC_UPD_DEL_btn_del').hide();
                $('#ASRC_UPD_DEL_ta_reportdate').hide();
                $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                $('#ASRC_UPD_DEL_lb_attendance').hide();
                $('#ASRC_UPD_DEL_lbl_attendance').hide();
                $('#ASRC_UPD_DEL_rd_nopermission').hide();
                $('#ASRC_UPD_DEL_lbl_txtselectproj').hide();
                $('#ASRC_UPD_DEL_nopermission').hide();
                $('#ASRC_UPD_DEL_lbl_nopermission').hide();
                $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html('');
                $('#ASRC_UPD_DEL_btn_submit').hide();
                if(ASRC_UPD_DEL_loginidlist=='SELECT')
                {
                    $(".preloader").hide();
                    $('#ASRC_UPD_DEL_lbl_session').hide();
                    $('#ASRC_UPD_DEL_lbl_strtdte').hide();
                    $('#ASRC_UPD_DEL_tb_strtdte').hide();
                    $('#ASRC_UPD_DEL_lbl_enddte').hide();
                    $('#ASRC_UPD_DEL_tb_enddte').hide();
                    $('#ASRC_UPD_DEL_btn_search').hide();
                    $('#ASRC_UPD_DEL_tb_strtdte').val('').hide();
                    $('#ASRC_UPD_DEL_tb_enddte').val('').hide();
                    $('section').html('');
                    $('#ASRC_UPD_DEL_tbl_htmltable').hide();
                    $("#ASRC_UPD_DEL_btn_search").attr("disabled", "disabled");
                    $('#ASRC_UPD_DEL_tble_attendence').hide();
                    $('#ASRC_UPD_DEL_lbl_dte').hide();
                    $('#ASRC_UPD_DEL_date').hide();
                    $('#ASRC_UPD_DEL_lbl_session').hide();
                    $('#ASRC_UPD_DEL_lbl_reason').hide();
                    $('#ASRC_UPD_DEL_ta_reason').hide();
                    $('#ASRC_UPD_DEL_lb_ampm').hide();
                    $('#ASRC_UPD_DEL_lbl_report').hide();
                    $('#ASRC_UPD_DEL_ta_report').hide();
                    $('#ASRC_UPD_DEL_lbl_permission').hide();
                    $('#permissionupd').hide();
                    $('#ASRC_UPD_DEL_chk_permission').hide();
                    $('#ASRC_UPD_DEL_lb_timing').hide();
                    $('#ASRC_UPD_DEL_lbl_band').hide();
                    $('#ASRC_UPD_DEL_tb_band').hide();
//                   ASRC_UPD_DEL_clear()
                }
                else
                {
                    var min_date;
                    var loginid=$('#ASRC_UPD_DEL_lb_loginid').val();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            var finaldate=JSON.parse(xmlhttp.responseText);
                            min_date=finaldate[0];
                            max_date=finaldate[1];
                            rprt_min_date=finaldate[2];
                            project_array=finaldate[3];
                            $("#ASRC_UPD_DEL_tb_strtdte").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                            $("#ASRC_UPD_DEL_tb_enddte").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                            $('#ASRC_UPD_DEL_tb_enddte').datepicker("option","maxDate",max_date);
                            $('#ASRC_UPD_DEL_tb_strtdte').datepicker("option","minDate",min_date);
                            $('#ASRC_UPD_DEL_tb_strtdte').datepicker("option","maxDate",max_date);
                            $('#ASRC_UPD_DEL_ta_reportdate').datepicker("option","minDate",rprt_min_date);
                            $('#ASRC_UPD_DEL_ta_reportdate').datepicker("option","maxDate",rprt_max_date);
                            $('#ASRC_UPD_DEL_lbl_session').hide();
                            if(min_date=='01-01-1970')
                            {
//                                $('#ASRC_UPD_DEL_errmsg').replaceWith('<p><label class="errormsg" id="ASRC_UPD_DEL_errmsg">'+ err_msg[10] +'</label></p>');
                                $('#ASRC_UPD_DEL_errmsg').text(err_msg[10]).show();
                                $('#ASRC_UPD_DEL_lbl_strtdte').hide();
                                $('#ASRC_UPD_DEL_tb_strtdte').hide();
                                $('#ASRC_UPD_DEL_lbl_enddte').hide();
                                $('#ASRC_UPD_DEL_tb_enddte').hide();
                                $('#ASRC_UPD_DEL_btn_search').hide();
                            }
                            else{
                                $('#ASRC_UPD_DEL_errmsg').hide();
                                $('#ASRC_UPD_DEL_lbl_strtdte').show();
                                $('#ASRC_UPD_DEL_tb_strtdte').show();
                                $('#ASRC_UPD_DEL_lbl_enddte').show();
                                $('#ASRC_UPD_DEL_tb_enddte').show();
                                $('#ASRC_UPD_DEL_btn_search').show();
                                $('#ASRC_UPD_DEL_tb_strtdte').val('').show();
                                $('#ASRC_UPD_DEL_tb_enddte').val('').show();

                            }
                            $('#ASRC_UPD_DEL_tbl_htmltable').hide();
                            $("#ASRC_UPD_DEL_btn_search").attr("disabled", "disabled");
                            $("#ASRC_UPD_DEL_tble_reasonlbltxtarea,#ASRC_UPD_DEL_secndselectprojct,#ASRC_UPD_DEL_tble_enterthereport,#ASRC_UPD_DEL_tble_bandwidth,#ASRC_UPD_DEL_mrg_projectlistbx,#ASRC_UPD_DEL_aftern_projectlistbx,#ASRC_UPD_DEL_btn_allsearch").html('')
                            $('#ASRC_UPD_DEL_lbl_session').hide();
                            $('#ASRC_UPD_DEL_lbl_reason').hide();
                            $('#ASRC_UPD_DEL_ta_reason').hide();
                            $("#ASRC_UPD_DEL_btn_submit,#ASRC_UPD_DEL_mrg_projectlistbx").html('');
                            $('#ASRC_UPD_DEL_lb_ampm').hide();
                            $('#ASRC_UPD_DEL_lbl_report').hide();
                            $('#ASRC_UPD_DEL_ta_report').hide();
                            $('#ASRC_UPD_DEL_lbl_permission').hide();
                            $('#permissionupd').hide();
                            $('#ASRC_UPD_DEL_chk_permission').hide();
                            $('#ASRC_UPD_DEL_lb_timing').hide();
                            $('#ASRC_UPD_DEL_lbl_band').hide();
                            $('#ASRC_UPD_DEL_tb_band').hide();
                        }
                    }
                    var choice="login_id";
                    xmlhttp.open("GET","ADMIN/DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?login_id="+loginid+"&option="+choice,true);
                    xmlhttp.send();

                }
            });
            // CHANGE EVENT FOR STARTDATE AND ENDDATE
            $(document).on('change','.valid',function(){
                if(($("#ASRC_UPD_DEL_tb_strtdte").val()=='')||($("#ASRC_UPD_DEL_tb_enddte").val()==''))
                {

                    $("#ASRC_UPD_DEL_btn_search").attr("disabled", "disabled");
                }
                else
                {
                    $("#ASRC_UPD_DEL_btn_search").removeAttr("disabled");
                }
            });
            var values_array=[];
            $(document).on('click','#ASRC_UPD_DEL_btn_search',function(){
                $('section').html('')
                $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                $('#ASRC_UPD_DEL_div_header').hide();
                $('#ASRC_UPD_btn_pdf').hide();
                $('#ASRC_UPD_DEL_div_headers').hide();
                $('#ASRC_UPD_btn_od_pdf').hide();
//                $('.preloader', window.parent.document).show();
                $(".preloader").show();
                flextablerange()
                $("#ASRC_UPD_DEL_btn_search").attr("disabled", "disabled");
                $("#ASRC_UPD_DEL_btn_del").attr("disabled", "disabled");
            });
            //FUNCTION FOR FORMTABLEDATEFORMAT
            function FormTableDateFormat(inputdate){
                var string = inputdate.split("-");
                return string[2]+'-'+ string[1]+'-'+string[0];
            }
            // FUNCTION FOR DATATABLE
            function flextablerange(){
                var ure_after_mrg;
                $('#ASRC_UPD_DEL_lbl_attendance').show();
                var start_date=$('#ASRC_UPD_DEL_tb_strtdte').val();
                var end_date=$('#ASRC_UPD_DEL_tb_enddte').val();
                var activeloginid=$('#ASRC_UPD_DEL_lb_loginid').val();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
//                        $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        values_array=JSON.parse(xmlhttp.responseText);
                        if(values_array.length!=0){
                            var sd=err_msg[11].toString().replace("[LOGINID]",$("#ASRC_UPD_DEL_lb_loginid option:selected").text());
                            var msg=sd.toString().replace("[STARTDATE]",start_date);
                            var errmsgs=msg.toString().replace("[ENDDATE]",end_date);
                            pdfmsg=errmsgs;
                            //HEADER ERR MSG
                            var sd=err_msg[11].toString().replace("[LOGINID]",$("#ASRC_UPD_DEL_lb_loginid option:selected").text());
                            var msg=sd.toString().replace("[STARTDATE]",start_date);
                            var errmsg=msg.toString().replace("[ENDDATE]",end_date);
                            $('#ASRC_UPD_DEL_div_header').text(errmsg).show();
                            $('#ASRC_UPD_btn_pdf').show();
                            var ASRC_UPD_DEL_table_header='<table id="ASRC_UPD_DEL_tbl_htmltable" border="1" class="srcresult" style="width:1700px"><thead  bgcolor="#6495ed" style="color:white"><tr><th  style="width:10px"></th><th style="width:70px" class="uk-date-column" nowrap>DATE</th><th style="width:1100px">REPORT</th><th>LOCATION</th><th style="width:150px">USERSTAMP</th><th class="uk-timestp-column" style="width:100px">TIMESTAMP</th></tr></thead><tbody>'
                            for(var j=0;j<values_array.length;j++){
                                var emp_date=values_array[j].date;
                                var emp_report=values_array[j].report;
                                var emp_reason=values_array[j].reason;
                                var morningsession=values_array[j].morningsession;
                                var afternoonsession=values_array[j].afternoonsession;
                                var timestamp=values_array[j].timestamp;
                                var userstamp=values_array[j].user_stamp;
                                var permission=values_array[j].permission;
                                var id=values_array[j].id;
                                var flag=values_array[j].flag;
                                var location=values_array[j].location;
                                var not_informed=values_array[j].notinformed;
                                if(location==null)
                                {
                                    location='';
                                }
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
                                        ASRC_UPD_DEL_table_header+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_flxtbl" class="ASRC_UPD_DEL_class_radio" id='+id+'  value='+id+' ></td><td align="center" >'+emp_date+'</td><td style="max-width:1000px; !important;"> '+ure_after_mrg +'  -  '+'REASON:'+emp_reason+'</td><td width="250">'+location+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                                    }
                                    else if(emp_reason==null)
                                    {
                                        ASRC_UPD_DEL_table_header+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_flxtbl" class="ASRC_UPD_DEL_class_radio" id='+id+'  value='+id+' ></td><td align="center" >'+emp_date+'</td><td style="max-width:1000px; !important;">'+emp_report+'</td><td width="250">'+location+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                                    }
                                    else
                                    {
                                        ASRC_UPD_DEL_table_header+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_flxtbl" class="ASRC_UPD_DEL_class_radio" id='+id+'  value='+id+' ></td><td align="center" >'+emp_date+'</td><td style="max-width:1000px; !important;">'+emp_report+' <br>'+ure_after_mrg +'  -  '+'REASON:'+emp_reason+'</td><td width="250">'+location+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
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
                                        ASRC_UPD_DEL_table_header+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_flxtbl" class="ASRC_UPD_DEL_class_radio" id='+id+'  value='+id+' ></td><td align="center" >'+emp_date+'</td><td style="max-width:1000px; !important;"> '+ure_after_mrg +'  -  '+'REASON:'+emp_reason+'<br>PERMISSION:'+permission+' hrs</td><td width="250">'+location+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                                    }
                                    else if(emp_reason==null)
                                    {
                                        ASRC_UPD_DEL_table_header+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_flxtbl" class="ASRC_UPD_DEL_class_radio" id='+id+'  value='+id+' ></td><td align="center" >'+emp_date+'</td><td style="max-width:1000px; !important;">'+emp_report+' <br>PERMISSION:'+permission+' hrs</td><td width="250">'+location+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                                    }
                                    else
                                    {
                                        ASRC_UPD_DEL_table_header+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_flxtbl" class="ASRC_UPD_DEL_class_radio" id='+id+'  value='+id+' ></td><td align="center" >'+emp_date+'</td><td style="max-width:1000px; !important;">'+emp_report+' <br>'+ure_after_mrg +'  -  '+'REASON:'+emp_reason+' <br>PERMISSION:'+permission+' hrs</td><td width="250">'+location+'</td><td style="width:150px">'+userstamp+'</td><td style="min-width:90px;" nowrap>'+timestamp+'</td></tr>';
                                    }
                                }
                            }
                            ASRC_UPD_DEL_table_header+='</tbody></table>';
                            $('#ASRC_UPD_DEL_tbl_htmltable').show();
                            $('section').html(ASRC_UPD_DEL_table_header);
                            $('#ASRC_UPD_DEL_tbl_htmltable').DataTable( {
                                "aaSorting": [],
                                "pageLength": 10,
                                "sPaginationType":"full_numbers",
                                "aoColumnDefs" : [
                                    { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                            });
                        }
                        else
                        {
                            var sd=err_msg[6].toString().replace("[SDATE]",start_date);
                            var msg=sd.toString().replace("[EDATE]",end_date);
                            $('#ASRC_UPD_DEL_errmsg').text(msg).show();
                            $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                        }
                    }

                }
                $('#ASRC_UPD_DEL_div_tablecontainer').show();
                var choice='DATERANGE';
                xmlhttp.open("POST","ADMIN/DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?start_date="+start_date+"&end_date="+end_date+"&option="+choice+"&actionloginid="+activeloginid,true);
                xmlhttp.send();
                sorting()
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
            //CHANGE EVENT FOR RADIO BUTTTON
            $(document).on('change','.ASRC_UPD_DEL_class_radio',function(){
                err_flag=0;
                $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                $("#ASRC_UPD_DEL_tble_reasonlbltxtarea,#ASRC_UPD_DEL_secndselectprojct,#ASRC_UPD_DEL_tble_enterthereport,#ASRC_UPD_DEL_tble_bandwidth,#ASRC_UPD_DEL_mrg_projectlistbx,#ASRC_UPD_DEL_aftern_projectlistbx,#ASRC_UPD_DEL_btn_allsearch").html('')
                $('#ASRC_UPD_DEL_btn_srch').show()
                $('#ASRC_UPD_DEL_btn_del').show();
                $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                $('#ASRC_UPD_DEL_errmsg').hide();
                $('#ASRC_chk_notinformed').hide();
                $('#ARE_lbl_notinformed').hide();
                $('#ARE_rd_notinformed').hide();
                $('#ASRC_UPD_DEL_ta_reportdate').hide();
                $("#ASRC_UPD_DEL_btn_srch").removeAttr("disabled");
                $("#ASRC_UPD_DEL_btn_del").removeAttr("disabled");
                $('#ASRC_UPD_DEL_btn_submit').attr('disabled','disabled');
                $('#ASRC_UPD_DEL_tble_attendence').hide();
                $('#ASRC_UPD_DEL_btn_submit').hide();
                $('#ASRC_UPD_DEL_lbl_txtselectproj').hide();
                $('#ASRC_UPD_DEL_tble_projectlistbx').hide();
                $('#ASRC_UPD_DEL_ta_report').hide();
                $('#ASRC_UPD_DEL_lbl_report').hide();
                $('#ASRC_UPD_DEL_chk_permission').hide();
                $('#ASRC_UPD_DEL_lbl_permission').hide();
                $('#permissionupd').hide();
                $('#ASRC_UPD_DEL_lbl_nopermission').hide();
                $('#ASRC_UPD_DEL_lb_timing').hide();
                $('#ASRC_UPD_DEL_lbl_session').hide();
                $('#ASRC_UPD_DEL_lbl_reason').hide();
                $('#ASRC_UPD_DEL_ta_reason').hide();
                $('#ASRC_UPD_DEL_lb_ampm').hide();
                $('#ASRC_UPD_DEL_lbl_band').hide();
                $('#ASRC_UPD_DEL_tb_band').hide();
                $('#ASRC_UPD_DEL_lbl_report').hide();
                $('#ASRC_UPD_DEL_ta_report').hide();
                $("#ASRC_UPD_DEL_chk_flag").hide();
                $("#ASRC_UPD_DEL_lbl_flag").hide();
                $('#ASRC_UPD_DEL_banerrmsg').hide();
            });
            // CLICK EVENT FOR DELETE BUTTON
            $(document).on('click','#ASRC_UPD_DEL_btn_del',function(){
//                $('.preloader', window.parent.document).show();
                $(".preloader").show();
                var delid=$("input[name=ASRC_UPD_DEL_rd_flxtbl]:checked").val();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                        $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        var delete_msg=xmlhttp.responseText;
                        if(delete_msg==1)
                        {
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH/UPDATE/DELETE",msgcontent:err_msg[2],position:{top:150,left:500}}});
                            show_msgbox("ADMIN SEARCH/UPDATE/DELETE",err_msg[2],"success",false);
                            flextablerange()
                            $('#ASRC_UPD_DEL_btn_del').hide();
                            $('#ASRC_UPD_DEL_btn_srch').hide();
                        }
                        else if(delete_msg==0)
                        {
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH/UPDATE/DELETE",msgcontent:err_msg[5],position:{top:150,left:500}}});
                            show_msgbox("ADMIN SEARCH/UPDATE/DELETE",err_msg[5],"success",false);
                            flextablerange()
                            $('#ASRC_UPD_DEL_btn_del').hide();
                            $('#ASRC_UPD_DEL_btn_srch').hide();
                        }
                        else
                        {
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH/UPDATE/DELETE",msgcontent:delete_msg,position:{top:150,left:500}}});
                            show_msgbox("ADMIN SEARCH/UPDATE/DELETE",delete_msg,"success",false);
                            flextablerange()
                            $('#ASRC_UPD_DEL_btn_del').hide();
                            $('#ASRC_UPD_DEL_btn_srch').hide();
                        }
                    }
                }
                var choice="DELETE";
                xmlhttp.open("GET","ADMIN/DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?del_id="+delid+"&option="+choice,true);
                xmlhttp.send();
            });
            // CLICK EVENT FOR SEARCH BUTTON
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
            var flag;
            $(document).on('click','#ASRC_UPD_DEL_btn_srch',function(){
                ASRC_UPD_DEL_clear();
                $('#ASRC_chk_notinformed').hide();
                $('#ARE_lbl_notinformed').hide();
                $('#ARE_rd_notinformed').hide();
                $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                var SRC_UPD_idradiovalue=$('input:radio[name=ASRC_UPD_DEL_rd_flxtbl]:checked').attr('id');
                $("#ASRC_UPD_DEL_btn_srch").attr("disabled", "disabled");
                $("#ASRC_UPD_DEL_btn_del").attr("disabled", "disabled");

                for(var j=0;j<values_array.length;j++){
                    var id=values_array[j].id;
                    if(id==SRC_UPD_idradiovalue)
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
                        flag=values_array[j].flag;
                        var not_informed=values_array[j].notinformed;
                        if(attendance=='1')
                        {

                            var permission_list='<option>SELECT</option>';
                            for (var i=0;i<permission_array.length;i++) {
                                permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                            }
                            $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                        }
                        else if((attendance=='0.5') || (attendance=='0.5OD'))
                        {


                            var permission_list='<option>SELECT</option>';
                            for (var i=0;i<4;i++) {
                                permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                            }
                            $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                        }
                        if(attendance=='WORK FROM HOME')
                        {
                            $('#ASRC_UPD_DEL_lb_attendance').replaceWith(
                                "<select id='ASRC_UPD_DEL_lb_attendance' name='ASRC_UPD_DEL_lb_attendance' class='update_validate form-control'> <option value='2'>WORK FROM HOME</option> </select>");
                        }
                        else
                        {
                            $('#ASRC_UPD_DEL_lbl_reportdte').show();
                            $('#ASRC_UPD_DEL_lb_attendance').replaceWith(
                                "<select id='ASRC_UPD_DEL_lb_attendance' name='ASRC_UPD_DEL_lb_attendance' class='update_validate form-control'> <option value='1'>PRESENT</option><option value='0'>ABSENT</option><option value='OD'>ONDUTY</option></select>");
                        }
                        if(not_informed!=null) {
                            $('#ASRC_chk_notinformed').html('').append('<div class="form-group"><label class="col-sm-2"></label><div class="col-sm-9"><div class="col-md-4"><div class="form-group"><div class="checkbox"><label name="ARE_noinformed" class="col-sm-8" id="ARE_lbl_notinformed"><input type="checkbox" name="notinformed" id="ARE_rd_notinformed" value="NOTINFORMED" checked >NOT INFORMED</label></div></div></div></div></div>');
                            $('#ASRC_chk_notinformed').show();
                        }
                        else {
                            $('#ASRC_chk_notinformed').html('').append('<div class="form-group"><label class="col-sm-2"></label><div class="col-sm-9"><div class="col-md-4"><div class="form-group"><div class="checkbox"><label name="ARE_noinformed" class="col-sm-8" id="ARE_lbl_notinformed"><input type="checkbox" name="notinformed" id="ARE_rd_notinformed" value="NOTINFORMED" >NOT INFORMED</label></div></div></div></div></div>');
                            $('#ASRC_chk_notinformed').show();
                        }
                        $('#ARE_lbl_notinformed').show();
                        $('#ARE_rd_notinformed').show();
                        $('#ASRC_UPD_DEL_tble_attendence').show();
                        form_show(attendance)
                    }
                }
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
            // FUNCTION FOR FORM AFTER SEARCH BUTTON CLICK
            function form_show(attendance)
            {
                if(attendance=='1')
                {
                    projectid_array=pdid.split(",");
                    $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled","disabled");
                    $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled","disabled");
                    $('#ASRC_UPD_DEL_lbl_reportdte').show();
                    $('#ASRC_UPD_DEL_ta_reportdate').val(date);
                    $('#ASRC_UPD_DEL_ta_reportdate').show();
                    $('#ASRC_UPD_DEL_lb_attendance').val('1');
                    $('#ASRC_UPD_DEL_lbl_permission').show();
                    $('#ASRC_UPD_DEL_rd_permission').show();
                    $('#ASRC_UPD_DEL_lbl_nopermission').show();
                    $('#ASRC_UPD_DEL_rd_nopermission').show();
                    $('#permissionupd').show();
                    $('#ASRC_UPD_DEL_lbl_session').hide();
                    $('#ASRC_chk_notinformed').hide();
                    $('#ASRC_UPD_DEL_lb_ampm').hide();
                    $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                    $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                    projectlists();
                    projecdid();
                    ASRC_UPD_DEL_report()

                    $('#ASRC_UPD_DEL_ta_report').val(report);
                    $('#ASRC_UPD_DEL_tble_bandwidth').show()
                    $('<div class="row-fluid form-group"><label name="ASRC_UPD_DEL_lbl_band" class="col-sm-2" id="ASRC_UPD_DEL_lbl_band">BANDWIDTH<em>*</em></label><div class="col-sm-8"><input type="text" name="ASRC_UPD_DEL_tb_band" id="ASRC_UPD_DEL_tb_band" class="autosize amountonly update_validate" style="width:75px;" ><label name="ASRC_UPD_DEL_lbl_band" id="ASRC_UPD_DEL_lbl_band">MB</label></div></div>').appendTo($("#ASRC_UPD_DEL_tble_bandwidth"));
                    $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:4,imaginary:2}});
                    $('#ASRC_UPD_DEL_tb_band').val(bandwidth);
                    $('#ASRC_UPD_DEL_btn_submit').show();
                }
                if(attendance=='WORK FROM HOME')
                {
                    projectid_array=pdid.split(",");
                    $('#ASRC_UPD_DEL_lbl_reportdte').show();
                    $('#ASRC_UPD_DEL_ta_reportdate').val(date);
                    $('#ASRC_UPD_DEL_ta_reportdate').show();
                    $('#ASRC_UPD_DEL_lb_attendance').val('2');
                    $('#ASRC_UPD_DEL_lbl_permission').hide();
                    $('#ASRC_UPD_DEL_rd_permission').hide();
                    $('#ASRC_UPD_DEL_lbl_nopermission').hide();
                    $('#ASRC_UPD_DEL_rd_nopermission').hide();
                    $('#permissionupd').hide();
                    $('#ASRC_UPD_DEL_lbl_session').hide();
                    $('#ASRC_UPD_DEL_lb_ampm').hide();
                    $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                    $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                    projectlists();
                    projecdid();
                    ASRC_UPD_DEL_report()
//            $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled","disabled");
//            $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled","disabled");
                    $('#ASRC_UPD_DEL_ta_report').val(report);
//            ASRC_UPD_DEL_tble_bandwidth()
                    $('#ASRC_UPD_DEL_tb_band').hide();
                    $('#ASRC_UPD_DEL_btn_submit').show();
                }
                if(attendance=='0')
                {

                    $('#ASRC_UPD_DEL_ta_reportdate').val(date);
                    $('#ASRC_UPD_DEL_ta_reportdate').show();
                    $('#ASRC_UPD_DEL_lb_attendance').val('0');
                    $('#ASRC_UPD_DEL_lbl_permission').hide();
                    $('#ASRC_UPD_DEL_rd_permission').hide();
                    $('#ASRC_UPD_DEL_lbl_nopermission').hide();
                    $('#ASRC_UPD_DEL_rd_nopermission').hide();
                    $('#permissionupd').hide();
                    $('#ASRC_UPD_DEL_rd_permission').attr("disabled","disabled");
                    $('#ASRC_UPD_DEL_rd_nopermission').attr("disabled","disabled");
                    $('#ASRC_UPD_DEL_lbl_session').show();
                    $('#ASRC_UPD_DEL_lb_ampm').show();
                    $('#ASRC_UPD_DEL_lb_ampm').val("FULLDAY");
                    ASRC_UPD_DEL_reason()
                    $('#ASRC_UPD_DEL_ta_reason').val(reason);
                    $('#ASRC_UPD_DEL_btn_submit').show();
                    if(flag==null)
                    {
                        $('#ASRC_UPD_DEL_chk_flag').attr('checked',false);
                        $("#ASRC_UPD_DEL_chk_flag").hide();
                        $("#ASRC_UPD_DEL_lbl_flag").hide();
                        $('#flage').hide();
                    }
                    else
                    {
                        $('#ASRC_UPD_DEL_chk_flag').attr('checked','checked');
                        $("#ASRC_UPD_DEL_chk_flag").show();
                        $("#ASRC_UPD_DEL_lbl_flag").show();
                        $('#flage').show();
                    }
                }
                if(attendance=='0.5')
                {
                    $('#ASRC_UPD_DEL_lbl_reportdte').show();
                    $('#ASRC_UPD_DEL_ta_reportdate').val(date);
                    $('#ASRC_UPD_DEL_ta_reportdate').show();
                    projectid_array=pdid.split(",");
                    $('#ASRC_UPD_DEL_lb_attendance').val('0');
                    $('#ASRC_UPD_DEL_lbl_permission').show();
                    $('#ASRC_UPD_DEL_rd_permission').show();
                    $('#ASRC_UPD_DEL_lbl_nopermission').show();
                    $('#ASRC_UPD_DEL_rd_nopermission').show();
                    $('#permissionupd').show();
                    $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled");
                    $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled");
                    $('#ASRC_UPD_DEL_lbl_session').show();
                    $('#ASRC_UPD_DEL_lb_ampm').show();
                    if((morningsession=='PRESENT') && (afternoonsession=='ABSENT'))
                    {
                        $('#ASRC_UPD_DEL_lb_ampm').val('PM');
                        $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                        $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                        projectlists();
                        projecdid();


                    }
                    else if((morningsession=='ABSENT' && afternoonsession=='PRESENT'))
                    {
                        $('#ASRC_UPD_DEL_lb_ampm').val('AM');
                        $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                        $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                        projectlists();
                        projecdid();
                    }
                    ASRC_UPD_DEL_reason()
                    $('#ASRC_UPD_DEL_ta_reason').val(reason);
                    ASRC_UPD_DEL_report()
                    $('#ASRC_UPD_DEL_ta_report').val(report);
                    ASRC_UPD_DEL_tble_bandwidth()
                    $('#ASRC_UPD_DEL_tb_band').val(bandwidth);
                    $('#ASRC_UPD_DEL_btn_submit').show();

                }
                if(attendance=='OD')
                {
                    $('#ASRC_UPD_DEL_lbl_reportdte').show();
                    $('#ASRC_UPD_DEL_ta_reportdate').val(date);
                    $('#ASRC_UPD_DEL_ta_reportdate').show();
                    $('#ASRC_UPD_DEL_lb_attendance').val('OD');
                    $('#ASRC_UPD_DEL_lbl_permission').hide();
                    $('#ASRC_UPD_DEL_rd_permission').hide();
                    $('#ASRC_UPD_DEL_lbl_nopermission').hide();
                    $('#ASRC_UPD_DEL_rd_nopermission').hide();
                    $('#permissionupd').hide();
                    $('#ASRC_chk_notinformed').hide();
                    $('#ASRC_UPD_DEL_lbl_session').show();
                    $('#ASRC_UPD_DEL_lb_ampm').show();
                    $('#ASRC_UPD_DEL_lb_ampm').val("FULLDAY");
                    $('#ASRC_UPD_DEL_rd_permission').attr("disabled","disabled");
                    $('#ASRC_UPD_DEL_rd_nopermission').attr("disabled","disabled");
                    ASRC_UPD_DEL_reason()
                    $('#ASRC_UPD_DEL_ta_reason').val(reason);
                    $('#ASRC_UPD_DEL_btn_submit').show();
                }
                if(attendance=='0.5OD')
                {
                    $('#ASRC_UPD_DEL_lbl_reportdte').show();
                    $('#ASRC_UPD_DEL_ta_reportdate').val(date);
                    $('#ASRC_UPD_DEL_ta_reportdate').show();
                    projectid_array=pdid.split(",");
                    $('#ASRC_UPD_DEL_lb_attendance').val('OD');
                    $('#ASRC_UPD_DEL_lbl_permission').show();
                    $('#ASRC_UPD_DEL_rd_permission').show();
                    $('#ASRC_UPD_DEL_lbl_nopermission').show();
                    $('#ASRC_UPD_DEL_rd_nopermission').show();
                    $('#permissionupd').show();
                    $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled");
                    $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled");
                    $('#ASRC_UPD_DEL_lbl_session').show();
                    $('#ASRC_UPD_DEL_lb_ampm').show();
                    if((morningsession=='PRESENT') && (afternoonsession=='ONDUTY'))
                    {
                        $('#ASRC_UPD_DEL_lb_ampm').val('PM');
                        $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                        $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                        projectlists();
                        projecdid();
                    }
                    else if((morningsession=='ONDUTY' && afternoonsession=='PRESENT'))
                    {
                        $('#ASRC_UPD_DEL_lb_ampm').val('AM');
                        $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                        $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                        projectlists();
                        projecdid();
                    }
                    ASRC_UPD_DEL_reason()
                    $('#ASRC_UPD_DEL_ta_reason').val(reason);
                    ASRC_UPD_DEL_report()
                    $('#ASRC_UPD_DEL_ta_report').val(report);
                    ASRC_UPD_DEL_tble_bandwidth()
                    $('#ASRC_UPD_DEL_tb_band').val(bandwidth);
                    $('#ASRC_UPD_DEL_btn_submit').show();

                }
                if(permission!=null)
                {
                    $('#ASRC_UPD_DEL_rd_permission').attr('checked','checked');
                    $('#ASRC_UPD_DEL_lb_timing').show();
                    $('#ASRC_UPD_DEL_lb_timing').val(permission);
                }
                else
                {
                    $('#ASRC_UPD_DEL_rd_nopermission').attr('checked','checked');
                }

            }
            // CHANGE EVENT FOR ATTENDANCE
            $(document).on('change','#ASRC_UPD_DEL_lb_attendance',function(){
//    $('#ASRC_UPD_DEL_lb_attendance').change(function(){
                err_flag=0;
                if(attendance==$('#ASRC_UPD_DEL_lb_attendance').val())
                {
                    $('#ASRC_chk_notinformed').html('');
                    $('#ARE_lbl_notinformed').hide();
                    $('#ARE_rd_notinformed').hide();
                    $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
                    $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html('');
                    $('#ASRC_UPD_DEL_tble_enterthereport').html('');
                    $('#ASRC_UPD_DEL_tble_bandwidth').html('');
                    $('#ASRC_UPD_DEL_lb_timing').hide();
                    $('#ASRC_UPD_DEL_lbl_permission').hide();
                    $('#ASRC_UPD_DEL_rd_permission').hide();
                    $('#ASRC_UPD_DEL_lbl_nopermission').hide();
                    $('#ASRC_UPD_DEL_rd_nopermission').hide();
                    $('#permissionupd').hide();
                    $('#ASRC_UPD_DEL_tble_projectlistbx').hide();
                    form_show(attendance)
                    $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
                }
                else{
                    projectid_array='';
                    $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html('');
                    $('#ASRC_UPD_DEL_btn_submit').attr('disabled','disabled');
                    $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
                    if($('#ASRC_UPD_DEL_lb_attendance').val()=='1')
                    {
                        $('#ASRC_chk_notinformed').html('');
                        $('#ARE_lbl_notinformed').hide();
                        $('#ARE_rd_notinformed').hide();
                        $('#ASRC_UPD_DEL_tble_enterthereport,#ASRC_UPD_DEL_ta_reason,#ASRC_UPD_DEL_tble_bandwidth').html('');
                        $('#ASRC_UPD_DEL_rd_permission').attr('checked',false);
                        $('#ASRC_UPD_DEL_rd_nopermission').attr('checked',false);
                        $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled");
                        $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled");
                        $('#ASRC_UPD_DEL_lb_timing').hide();
                        $('#ASRC_UPD_DEL_lbl_permission').show();
                        $('#ASRC_UPD_DEL_rd_permission').show();
                        $('#ASRC_UPD_DEL_lbl_nopermission').show();
                        $('#ASRC_UPD_DEL_rd_nopermission').show();
                        $('#permissionupd').show();
                        var permission_list='<option>SELECT</option>';
                        for (var i=0;i<permission_array.length;i++) {
                            permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                        }
                        $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                        $('#ASRC_UPD_DEL_lbl_session').hide();
                        $('#ASRC_UPD_DEL_lb_ampm').hide();
                        $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                        $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                        projectlists();
                        ASRC_UPD_DEL_report();
                        ASRC_UPD_DEL_tble_bandwidth();
                        $('#ASRC_UPD_DEL_btn_submit').hide();

                        $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);
                        $('#ASRC_UPD_DEL_errmsg').hide();
                    }
                    else  if($('#ASRC_UPD_DEL_lb_attendance').val()=='2')
                    {
                        $('#ASRC_chk_notinformed').html('');
                        $('#ASRC_UPD_DEL_tble_enterthereport,#ASRC_UPD_DEL_ta_reason,#ASRC_UPD_DEL_tble_bandwidth').html('');
//                $('#ASRC_UPD_DEL_rd_permission').attr('checked',false);
//                $('#ASRC_UPD_DEL_rd_nopermission').attr('checked',false);
                        $('#ASRC_UPD_DEL_lb_timing').hide();
                        $('#ASRC_UPD_DEL_lbl_permission').hide();
                        $('#ASRC_UPD_DEL_rd_permission').hide();
                        $('#ASRC_UPD_DEL_lbl_nopermission').hide();
                        $('#ASRC_UPD_DEL_rd_nopermission').hide();
                        $('#permissionupd').hide();
//                var permission_list='<option>SELECT</option>';
//                for (var i=0;i<permission_array.length;i++) {
//                    permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
//                }
//                $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                        $('#ASRC_UPD_DEL_lbl_session').hide();
                        $('#ASRC_UPD_DEL_lb_ampm').hide();
                        $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                        $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                        projectlists();
                        ASRC_UPD_DEL_report();
//                ASRC_UPD_DEL_tble_bandwidth();
                        $('#ASRC_UPD_DEL_btn_submit').hide();
//                $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled");
//                $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled");
//                $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);
                        $('#ASRC_UPD_DEL_errmsg').hide();
                    }
                    else if($('#ASRC_UPD_DEL_lb_attendance').val()=='0')
                    {
                        $('#ASRC_UPD_DEL_rd_permission').attr('checked',false);
                        $('#ASRC_UPD_DEL_rd_nopermission').attr('checked',false);
                        $('#ASRC_chk_notinformed').html('').append('test')
                        $('#ASRC_chk_notinformed').html('').append('<label class="col-sm-2 form-group"></label><div class="col-sm-9"><div class="row-fluid"><div class="col-md-4" style="padding-bottom: 10px"><div class="checkbox"><label name="ARE_noinformed" class="col-sm-10" id="ARE_lbl_notinformed"><input type="checkbox" name="notinformed" id="ARE_rd_notinformed" value="NOTINFORMED" >NOT INFORMED</label></div></div></div></div>');
                        $('#ASRC_chk_notinformed').show();
                        $('#ARE_lbl_notinformed').show();
                        $('#ARE_rd_notinformed').show();
                        $('#ASRC_UPD_DEL_lb_timing').hide();
                        $('#ASRC_UPD_DEL_lbl_permission').show();
                        $('#ASRC_UPD_DEL_rd_permission').show();
                        $('#ASRC_UPD_DEL_lbl_nopermission').show();
                        $('#ASRC_UPD_DEL_rd_nopermission').show();
                        $('#permissionupd').show();
                        var permission_list='<option>SELECT</option>';
                        for (var i=0;i<4;i++) {
                            permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                        }
                        $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                        $('#ASRC_UPD_DEL_lbl_session').show();
                        $('#ASRC_UPD_DEL_lb_ampm').val('SELECT').show();
                        $('#ASRC_UPD_DEL_tble_projectlistbx').hide();
                        $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
                        $('#ASRC_UPD_DEL_tble_enterthereport').html('');
                        $('#ASRC_UPD_DEL_tble_bandwidth').html('');
                        $('#ASRC_UPD_DEL_btn_submit').hide();
                        $('#ASRC_UPD_DEL_rd_permission').attr('disabled','disabled');
                        $('#ASRC_UPD_DEL_rd_nopermission').attr('disabled','disabled');
                        $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);
                        $('#ASRC_UPD_DEL_errmsg').hide();

                    }
                    else if($('#ASRC_UPD_DEL_lb_attendance').val()=='OD')
                    {
                        $('#ASRC_UPD_DEL_rd_permission').attr('checked',false);
                        $('#ASRC_UPD_DEL_rd_nopermission').attr('checked',false);
                        $('#ASRC_chk_notinformed').html('').append('test')
//                        $('#ASRC_chk_notinformed').html('').append('<div class="form-group"><label class="col-sm-2"></label><div class="col-sm-9"><div class="form-group"><div class="col-md-4"><div class="checkbox"><label name="ARE_noinformed" class="col-sm-10" id="ARE_lbl_notinformed"><input type="checkbox" name="notinformed" id="ARE_rd_notinformed" value="NOTINFORMED" >NOT INFORMED</label></div></div></div></div></div>');
                        $('#ASRC_chk_notinformed').hide();
                        $('#ASRC_UPD_DEL_lb_timing').hide();
                        $('#ASRC_UPD_DEL_lbl_permission').show();
                        $('#ASRC_UPD_DEL_rd_permission').show();
                        $('#ASRC_UPD_DEL_lbl_nopermission').show();
                        $('#ASRC_UPD_DEL_rd_nopermission').show();
                        $('#permissionupd').show();
                        var permission_list='<option>SELECT</option>';
                        for (var i=0;i<4;i++) {
                            permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                        }
                        $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                        $('#ASRC_UPD_DEL_lbl_session').show();
                        $('#ASRC_UPD_DEL_lb_ampm').val('SELECT').show();
                        $('#ASRC_UPD_DEL_tble_projectlistbx').hide();
                        $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
                        $('#ASRC_UPD_DEL_tble_enterthereport').html('');
                        $('#ASRC_UPD_DEL_tble_bandwidth').html('');
                        $('#ASRC_UPD_DEL_btn_submit').hide();
                        $('#ASRC_UPD_DEL_rd_permission').attr('disabled','disabled');
                        $('#ASRC_UPD_DEL_rd_nopermission').attr('disabled','disabled');
                        $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);
                        $('#ASRC_UPD_DEL_errmsg').hide();

                    }
                }
            });
            var maxdate=new Date();
            var month=maxdate.getMonth()+1;
            var year=maxdate.getFullYear();
            var date=maxdate.getDate();
            var rprt_max_date = new Date(year,month,date);
            $('#ASRC_UPD_DEL_ta_reportdate').datepicker({
                dateFormat:"dd-mm-yy",
                changeYear: true,
                changeMonth: true
            });
            var err_flag=0;
            // CHANGE EVENT FOR REPORTDATE ALREADY EXISTS
            $(document).on('change','#ASRC_UPD_DEL_ta_reportdate',function(){

                var reportdate=$('#ASRC_UPD_DEL_ta_reportdate').val();
                if(reportdate!=date){
//                    $('.preloader', window.parent.document).show();
                    $(".preloader").show();
                    var loginid=$('#ASRC_UPD_DEL_lb_loginid').val();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            var msgalert=xmlhttp.responseText;
//                            $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
                            if(msgalert==1)
                            {
                                err_flag=1;
                                var msg=err_msg[3].toString().replace("[DATE]",reportdate)
                                $('#ASRC_UPD_DEL_errmsg').text(msg).show();
                                $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
                            }
                            else{
                                err_flag=0;
                                $('#ASRC_UPD_DEL_errmsg').hide();
                            }
                        }

                    }
                    var choice="DATE"
                    xmlhttp.open("GET","ADMIN/DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?reportdate="+reportdate+"&login_id="+loginid+"&option="+choice,true);
                    xmlhttp.send();
                }
                else{
                    err_flag=0;
                    $('#ASRC_UPD_DEL_errmsg').hide();


                }
            });
            $(document).on('click','.paginate_button',function(){
                ASRC_UPD_DEL_clear()
                $("#ASRC_UPD_DEL_btn_del").hide();
                $("#ASRC_UPD_DEL_btn_srch").hide();
                $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                $('#ASRC_UPD_DEL_ta_reportdate').hide();
                $('#ASRC_UPD_DEL_errmsg').hide();
                $('input:radio[name=ASRC_UPD_DEL_rd_flxtbl]').attr('checked',false);
            });
            //CLICK EVENT FOR PERMISSION RADIO BUTTON
            $(document).on('click','#ASRC_UPD_DEL_rd_permission',function()
            {
                if($('#ASRC_UPD_DEL_rd_permission').attr("checked","checked"))
                {
                    $('#ASRC_UPD_DEL_lb_timing').val('SELECT').show();
                }
                else
                {
                    $('#ASRC_UPD_DEL_lb_timing').hide();
                    $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);
                }
            });
            //CLICK EVENT FOR NOPERMISSION RADIO BUTTON
            $(document).on('click','#ASRC_UPD_DEL_rd_nopermission',function()
            {
                $('#ASRC_UPD_DEL_lb_timing').hide();
                $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);

            });
            function search_clear()
            {
                $('#ASRC_UPD_DEL_div_header').hide();
//               $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                $('#ASRC_UPD_DEL_ta_reportdate').val('').hide();
                $('#ASRC_UPD_DEL_tb_strtdte').hide();
                $('#ASRC_UPD_DEL_lbl_strtdte').hide();
                $('#ASRC_UPD_DEL_tb_enddte').val('').hide();
                $('#ASRC_UPD_DEL_lbl_enddte').hide();
                $('#ASRC_UPD_DEL_lb_loginid').hide();
                $('#ASRC_UPD_DEL_lbl_loginid').hide();
                $('#ASRC_UPD_DEL_lbl_nopermission').hide();

////                $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
//                $('#ASRC_UPD_DEL_div_headers').hide();
//                $('#ASRC_UPD_DEL_od_btn').hide();
//                $('#ASRC_UPD_DEL_lbl_sdte').hide();
//                $('#ASRC_UPD_DEL_tb_sdte').hide();
//                $('#ASRC_UPD_DEL_lbl_edte').hide();
//                $('#ASRC_UPD_DEL_tb_edte').hide();

            }
            // FUNCTION FOR FORM CLEAR
            function ASRC_UPD_DEL_clear(){
//               $('#options');
                $('#ASRC_UPD_DEL_tble_attendence').hide();
                $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
                $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html('');
                $('#ASRC_UPD_DEL_tble_enterthereport').html('');
                $('#ASRC_UPD_DEL_tble_bandwidth').html('');
                $('#ASRC_UPD_DEL_btn_allsearch').html('');
                $('#ASRC_UPD_DEL_lbl_session').hide();
                $('#ASRC_UPD_DEL_lbl_permission').hide();
                $('#permissionupd').hide();
                $('#ASRC_UPD_DEL_rd_permission').hide();
                $('#ASRC_UPD_DEL_lbl_nopermission').hide();
                $('#ASRC_UPD_DEL_rd_nopermission').hide();
                $('#ASRC_UPD_DEL_lb_timing').hide();
                $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);
                $('#ASRC_UPD_DEL_lb_ampm').hide();
                $('#ASRC_UPD_DEL_btn_submit').hide();
                $('#ASRC_UPD_DEL_tble_projectlistbx').hide();
                $('#ASRC_UPD_DEL_lbl_oddte').hide();
                $('#ASRC_UPD_DEL_tb_oddte').hide();
                $('#ASRC_UPD_DEL_lbl_des').hide();
                $('#ASRC_UPD_DEL_ta_des').hide();
                $('#ASRC_UPD_DEL_odsubmit').hide();
                $("#ASRC_UPD_DEL_chk_flag").hide();
                $("#ASRC_UPD_DEL_lbl_flag").hide();
                $('#ASRC_UPD_DEL_banerrmsg').hide();
            }
            // CHANGE EVENT FOR SESSION
            $('#ASRC_UPD_DEL_lb_ampm').change(function(){
                projectid_array='';
                $('#ASRC_UPD_DEL_tble_reasonlbltxtarea,#ASRC_UPD_DEL_tble_enterthereport,#ASRC_UPD_DEL_tble_bandwidth,#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html('');
                if($('#ASRC_UPD_DEL_lb_ampm').val()=='SELECT')
                {
                    $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
                    $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html('');
                    $('#ASRC_UPD_DEL_tble_enterthereport').html('');
                    $('#ASRC_UPD_DEL_tble_projectlistbx').hide();
                    $('#ASRC_UPD_DEL_tble_bandwidth').html('');
                    $('#ASRC_UPD_DEL_btn_submit').hide();
                    $('#ASRC_UPD_DEL_banerrmsg').hide();
                }
                else if($('#ASRC_UPD_DEL_lb_ampm').val()=='FULLDAY')
                {
                    $('#ASRC_UPD_DEL_tble_projectlistbx').hide();
                    ASRC_UPD_DEL_reason();
                    $('#ASRC_UPD_DEL_rd_permission').attr('checked',false);
                    $('#ASRC_UPD_DEL_rd_nopermission').attr('checked',false);
                    $('#ASRC_UPD_DEL_rd_permission').attr('disabled','disabled');
                    $('#ASRC_UPD_DEL_rd_nopermission').attr('disabled','disabled');
                    $('#ASRC_UPD_DEL_lb_timing').hide();
                    $('#ASRC_UPD_DEL_lbl_permission').hide();
                    $('#ASRC_UPD_DEL_rd_permission').hide();
                    $('#ASRC_UPD_DEL_lbl_nopermission').hide();
                    $('#ASRC_UPD_DEL_rd_nopermission').hide();
                    $('#permissionupd').hide();
                    $('#permission').hide();
                    $('#ASRC_UPD_DEL_btn_submit').show();
                    $('#ASRC_chk_notinformed').html('').append('<label class="col-sm-2 form-group"></label><div class="col-sm-9"><div class="row-fluid"><div class="col-md-4" style="padding-bottom: 10px"><div class="checkbox"><label name="ARE_noinformed" class="col-sm-10" id="ARE_lbl_notinformed"><input type="checkbox" name="notinformed" id="ARE_rd_notinformed" value="NOTINFORMED" >NOT INFORMED</label></div></div></div></div>');
                    $('#ASRC_chk_notinformed').show();
//            $("#ASRC_UPD_DEL_chk_flag").show();
//            $("#ASRC_UPD_DEL_lbl_flag").show();
                    $('#ASRC_UPD_DEL_banerrmsg').hide();
                }
                else
                {
                    ASRC_UPD_DEL_reason();
                    $('#ASRC_UPD_DEL_btn_submit').hide();
                    $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled");
                    $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled");
                    $('#ASRC_UPD_DEL_rd_permission').attr('checked',false);
                    $('#ASRC_UPD_DEL_rd_nopermission').attr('checked',false);
                    $('#ASRC_UPD_DEL_lb_timing').hide();
                    $('#permissionupd').show();
                    $('#ASRC_UPD_DEL_lbl_permission').show();
                    $('#ASRC_UPD_DEL_rd_permission').show();
                    $('#ASRC_UPD_DEL_lbl_nopermission').show();
                    $('#ASRC_UPD_DEL_rd_nopermission').show();
                    $('#permissionupd').show();
                    var permission_list='<option>SELECT</option>';
                    for (var i=0;i<4;i++) {
                        permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                    }
                    $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                    $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                    $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                    projectlists();
                    ASRC_UPD_DEL_report();
                    $("#ASRC_UPD_DEL_tble_bandwidth").show()
                    ASRC_UPD_DEL_tble_bandwidth();
                    $('#ASRC_UPD_DEL_banerrmsg').hide();
                }
            });
            // CHANGE EVENT FOR REPORT TEXTAREA
            $(document).on('change','#ASRC_UPD_DEL_ta_report',function(){

                $('#ASRC_UPD_DEL_btn_submit').show();
                $('#ASRC_UPD_DEL_btn_submit').attr('disabled','disabled');
                $('#ASRC_UPD_DEL_banerrmsg').hide();
            });
            //CHANGE EVENT FOR BANDWIDTH TEXTBX
            $(document).on('change blur','#ASRC_UPD_DEL_tb_band',function(){
                var bandwidth=$('#ASRC_UPD_DEL_tb_band').val();
                if(bandwidth > 1000)
                {
                    var msg=err_msg[9].toString().replace("[BW]",bandwidth);
                    $('#ASRC_UPD_DEL_banerrmsg').text(msg).show();
                }
                else
                {
                    $('#ASRC_UPD_DEL_banerrmsg').hide();
                }
            });
            // FUNCTION FOR PROJECT LIST
            function projectlists(){
                $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html("");
                var project_list;
                for (var i=0;i<project_array.length;i++) {
//            project_list += '<tr><td><input type="checkbox" id="' + project_array[i][1] +'p'+ '" class="update_validate" name="checkbox[]" value="' + project_array[i][1] + '" readonly >' + project_array[i][0] + ' - '+ project_array[i][2]+'</td></tr>';
//        }
//        else{
                    project_list += '<tr><td><input type="checkbox" id="' + project_array[i][1] +'p'+ '" class="update_validate" name="checkbox[]" value="' + project_array[i][1] + '" >' + project_array[i][0] + ' - '+ project_array[i][2]+'</td></tr>';
//            }
                }
                $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').append(project_list);
            }
            // FUNCTION FOR REASON
            function ASRC_UPD_DEL_reason(){
                $('<div class="row-fluid form-group"><label name="ASRC_UPD_DEL_lbl_reason"  class="col-sm-2" id="ASRC_UPD_DEL_lbl_reason">REASON<em>*</em></label><div class="col-lg-10"><textarea  name="ASRC_UPD_DEL_ta_reason" id="ASRC_UPD_DEL_ta_reason" class="update_validate form-control tarea"></textarea></div>').appendTo($("#ASRC_UPD_DEL_tble_reasonlbltxtarea"));
                $('textarea').autogrow({onInitialize: true});
            }
            // FUNCTION FOR REPORT
            function ASRC_UPD_DEL_report(){
                $('<div class="row-fluid form-group"><label name="ASRC_UPD_DEL_lbl_report" class="col-sm-2" id="ASRC_UPD_DEL_lbl_report" >ENTER THE REPORT<em>*</em></label><div class="col-lg-10"><textarea  name="ASRC_UPD_DEL_ta_report" id="ASRC_UPD_DEL_ta_report" class="update_validate form-control tarea"></textarea></div>').appendTo($("#ASRC_UPD_DEL_tble_enterthereport"));
                $('textarea').autogrow({onInitialize: true});
            }
//    FUNCTION FOR BANDWIDTH
            function ASRC_UPD_DEL_tble_bandwidth(){
                $('<div class="row-fluid form-group"><label name="ASRC_UPD_DEL_lbl_band" class="col-sm-2" id="ASRC_UPD_DEL_lbl_band">BANDWIDTH<em>*</em></label><div class="col-sm-8"><input type="text" name="ASRC_UPD_DEL_tb_band" id="ASRC_UPD_DEL_tb_band" class="autosize amountonly update_validate" style="width:75px;" ><label name="ASRC_UPD_DEL_lbl_band" id="ASRC_UPD_DEL_lbl_band">MB</label></div></div>').appendTo($("#ASRC_UPD_DEL_tble_bandwidth"));
                $(".amountonly").doValidation({rule:'numbersonly',prop:{realpart:4,imaginary:2}});
            }
            //FORM VALIDATION
            $(document).on('change blur','.update_validate',function(){
                ASRC_UPD_DEL_updatevalidation();

            });
            function ASRC_UPD_DEL_updatevalidation(){
                var ASRC_UPD_DEL_sessionlstbx= $("#ASRC_UPD_DEL_lb_ampm").val();
                var ASRC_UPD_DEL_reasontxtarea =$("#ASRC_UPD_DEL_ta_reason").val();
                var ASRC_UPD_DEL_reportenter =$("#ASRC_UPD_DEL_ta_report").val();
                var ASRC_UPD_DEL_bndtxt = $("#ASRC_UPD_DEL_tb_band").val();
                var ASRC_UPD_DEL_projectselectlistbx=$('input[name="checkbox[]"]:checked').length;
                var ASRC_UPD_DEL_permissionlstbx = $("#ASRC_UPD_DEL_lb_timing").val();
                var ASRC_UPD_DEL_permission=$("input[name=permission]:checked").val()=="PERMISSION";
                var ASRC_UPD_DEL_nopermission=$("input[name=permission]:checked").val()=="NOPERMISSION";
                var ASRC_UPD_DEL_presenthalfdysvld=$("#ASRC_UPD_DEL_lb_attendance").val();
                if(err_flag!=1){
                    if(((ASRC_UPD_DEL_presenthalfdysvld=='0') && (ASRC_UPD_DEL_sessionlstbx=='AM' || ASRC_UPD_DEL_sessionlstbx=="PM")) || ((ASRC_UPD_DEL_presenthalfdysvld=='OD') && (ASRC_UPD_DEL_sessionlstbx=='AM' || ASRC_UPD_DEL_sessionlstbx=="PM") ))
                    {
                        if(((ASRC_UPD_DEL_reasontxtarea.trim()!="")&&(ASRC_UPD_DEL_reportenter!='')&&( ASRC_UPD_DEL_projectselectlistbx>0) && (ASRC_UPD_DEL_bndtxt!='')&& (parseFloat(ASRC_UPD_DEL_bndtxt)!=0) && (ASRC_UPD_DEL_bndtxt<=1000) && ((ASRC_UPD_DEL_permission==true) || (ASRC_UPD_DEL_nopermission==true))))
                        {
                            if(ASRC_UPD_DEL_permission==true)
                            {
                                if(ASRC_UPD_DEL_permissionlstbx!='SELECT')
                                {
                                    $("#ASRC_UPD_DEL_btn_submit").removeAttr("disabled");
                                }
                                else
                                {
                                    $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
                                }
                            }
                            else
                            {
                                $("#ASRC_UPD_DEL_btn_submit").removeAttr("disabled");
                            }
                        }
                        else
                        {
                            $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
                        }
                    }
                    else if((ASRC_UPD_DEL_presenthalfdysvld=='0' && ASRC_UPD_DEL_sessionlstbx=='FULLDAY') || (ASRC_UPD_DEL_presenthalfdysvld=='OD' && ASRC_UPD_DEL_sessionlstbx=='FULLDAY'))
                    {
                        if(ASRC_UPD_DEL_reasontxtarea.trim()=="")
                        {
                            $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
                        }
                        else
                        {
                            $("#ASRC_UPD_DEL_btn_submit").removeAttr("disabled");
                        }
                    }
                    else if(ASRC_UPD_DEL_presenthalfdysvld=='1')
                    {
                        if(((ASRC_UPD_DEL_reportenter.trim()!="")&&(ASRC_UPD_DEL_bndtxt!='')&& (parseFloat(ASRC_UPD_DEL_bndtxt)!=0) && (ASRC_UPD_DEL_bndtxt<=1000) && (ASRC_UPD_DEL_bndtxt<=1000)&&( ASRC_UPD_DEL_projectselectlistbx>0) && ((ASRC_UPD_DEL_permission==true) || (ASRC_UPD_DEL_nopermission==true))))
                        {
                            if(ASRC_UPD_DEL_permission==true)
                            {
                                if(ASRC_UPD_DEL_permissionlstbx!='SELECT')
                                {
                                    $("#ASRC_UPD_DEL_btn_submit").removeAttr("disabled");
                                }
                                else
                                {
                                    $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
                                }
                            }
                            else
                            {
                                $("#ASRC_UPD_DEL_btn_submit").removeAttr("disabled");
                            }
                        }
                        else
                        {
                            $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
                        }
                    }
                    else if(ASRC_UPD_DEL_presenthalfdysvld=='2')
                    {
                        if((ASRC_UPD_DEL_reportenter.trim()!="")&&( ASRC_UPD_DEL_projectselectlistbx>0))
                        {
                            if(ASRC_UPD_DEL_projectselectlistbx!='SELECT')
                            {
                                $("#ASRC_UPD_DEL_btn_submit").removeAttr("disabled");
                            }
                            else
                            {
                                $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
                            }

                        }
                        else
                        {
                            $("#ASRC_UPD_DEL_btn_submit").attr("disabled", "disabled");
                        }
                    }
                }
            }

            //FUNCTION FOR UPDATE BUTTON
            $(document).on('click','#ASRC_UPD_DEL_btn_submit',function(){
                $(".preloader").show();
                var formElement = document.getElementById("ARE_form_adminreportentry");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        var msg_alert=xmlhttp.responseText;
                        if(msg_alert==1)
                        {
                            show_msgbox("ADMIN SEARCH AND UPDATE",err_msg[1],"success",false);
                            ASRC_UPD_DEL_clear()
                            flextablerange()
                            $("#ASRC_UPD_DEL_btn_del").hide();
                            $('#ASRC_chk_notinformed').hide();
                            $('#ARE_lbl_notinformed').hide();
                            $('#ARE_rd_notinformed').hide();
                            $("#ASRC_UPD_DEL_btn_srch").hide();
                            $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                            $('#ASRC_UPD_DEL_ta_reportdate').hide();
                            $('#ASRC_UPD_DEL_errmsg').hide();
                            $('#ASRC_chk_notinformed').hide();
                        }
                        else if(msg_alert==0)
                        {
                            show_msgbox("ADMIN SEARCH AND UPDATE",err_msg[7],"success",false);
                            ASRC_UPD_DEL_clear()
                            flextablerange()
                            $("#ASRC_UPD_DEL_btn_del").hide();
                            $("#ASRC_UPD_DEL_btn_srch").hide();
                            $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                            $('#ASRC_UPD_DEL_ta_reportdate').hide();
                            $('#ASRC_UPD_DEL_errmsg').hide();
                            $('#ASRC_chk_notinformed').hide();
                        }
                        else
                        {
                            show_msgbox("ADMIN SEARCH AND UPDATE",msg_alert,"success",false);
                            ASRC_UPD_DEL_clear()
                            flextablerange()
                            $("#ASRC_UPD_DEL_btn_del").hide();
                            $("#ASRC_UPD_DEL_btn_srch").hide();
                            $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                            $('#ASRC_UPD_DEL_ta_reportdate').hide();
                            $('#ASRC_UPD_DEL_errmsg').hide();
                            $('#ASRC_chk_notinformed').hide();
                        }
                    }

                }
                var option="UPDATE"
                xmlhttp.open("POST","ADMIN/DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?option="+option+"&reportlocation="+checkoutlocation,true);
                xmlhttp.send(new FormData(formElement));
            });
            // CHANGE EVENT FOR OPTION LIST BOX
            $(document).on('change','#options',function(){
                $('#ASRC_UPD_DEL_div_header').hide();
                $('#ASRC_UPD_btn_pdf').hide();
                $('#ASRC_UPD_DEL_tble_dailyuserentry').hide();
                $('#ASRC_UPD_DEL_tble_ondutyentry').hide();
                $('#ASRC_UPD_DEL_oderrmsg').hide();
                if($('#options').val()=='ADMIN REPORT SEARCH UPDATE DELETE')
                {
                    $('#ASRC_UPD_DEL_tbl_entry').show();
                    $('#ASRC_UPD_DEL_tble_dailyuserentry').show();
                    $('#ASRC_UPD_DEL_lbl_session').hide();
                    $('#ASRC_UPD_DEL_tble_ondutyentry').hide();
                    $('#ASRC_UPD_DEL_btn_del').hide();
                    $('#ASRC_UPD_DEL_rd_allactveemp').attr('checked',false);
                    $('#ASRC_UPD_DEL_rd_btwnrange').attr('checked',false);
                    $('#ASRC_UPD_DEL_lbl_allactveemps').hide();
                    $('#ASRC_UPD_DEL_tb_dte').hide();
                    $('#ASRC_UPD_DEL_btn_submit').hide();
                    $('#ASRC_UPD_DEL_tbl_htmltable').hide();
                    $('#ASRC_UPD_DEL_lbl_dte').hide();
                    $('#ASRC_UPD_DEL_lbl_btwnranges').hide();
                    $('#ASRC_UPD_DEL_rd_actveemp').hide();
                    $('#ASRC_UPD_DEL_rd_nonactveemp').hide();
                    $('#ASRC_UPD_DEL_lbl_strtdte').hide();
                    $('#ASRC_UPD_DEL_tb_strtdte').hide();
                    $('#ASRC_UPD_DEL_lbl_enddte').hide();
                    $('#ASRC_UPD_DEL_tb_enddte').hide();
                    $('#ASRC_UPD_DEL_lbl_loginid').hide();
                    $('#ASRC_UPD_DEL_lb_loginid').hide();
                    $('#ASRC_UPD_DEL_lbl_actveemp').hide();
                    $('#ASRC_UPD_DEL_lbl_nonactveemp').hide();
                    $('#ASRC_UPD_DEL_btn_search').hide();
                    $('#ASRC_UPD_DEL_tble_attendence').hide();
                    $('#ASRC_UPD_DEL_oderrmsg').hide();
                    $('#ASRC_UPD_DEL_tble_odshow').hide();
                    $('#ASRC_UPD_DEL_btn_allsearch').hide();
                    $('#ASRC_UPD_DEL_tbl_ondutyhtmltable').hide();
                    $('#ASRC_UPD_DEL_btn_srch').hide();
                    $('#ASRC_UPD_DEL_odsrch_btn').hide();
                    $('#ASRC_UPD_DEL_errmsg').text("").hide();
                    $('#ASRC_UPD_DEL_lbl_nopermission').hide();
                    ASRC_UPD_DEL_clear()
                    $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
                    $('#ASRC_UPD_DEL_lbl_nopermission').hide();
                    $('#ASRC_UPD_DEL_div_header').hide();
                    $('#ASRC_UPD_btn_pdf').hide();
                    $('#ASRC_UPD_DEL_div_headers').hide();
                    $('#ASRC_UPD_btn_od_pdf').hide();

                }
                else if($('#options').val()=='ONDUTY REPORT SEARCH UPDATE')
                {
                    ASRC_UPD_DEL_clear()
                    $('#ASRC_UPD_DEL_tble_odshow').show();


                    $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
                    $('#ASRC_UPD_DEL_div_headers').hide();
                    $('#ASRC_UPD_DEL_od_btn').show();
                    $('#ASRC_UPD_DEL_lbl_sdte').show();
                    $('#ASRC_UPD_DEL_tb_sdte').show();
                    $('#ASRC_UPD_DEL_lbl_edte').show();
                    $('#ASRC_UPD_DEL_tb_edte').show();


                    $('#ASRC_UPD_DEL_errmsg').hide();
                    $('#ASRC_UPD_DEL_btn_srch').hide();
                    $('#ASRC_UPD_DEL_tble_ondutyentry').show();
                    $('#ASRC_UPD_DEL_tbl_htmltable').hide();
                    $('#ASRC_UPD_DEL_btn_del').hide();
                    $('#ASRC_UPD_DEL_tb_sdte').val('');
                    $('#ASRC_UPD_DEL_tb_edte').val('');
                    $('#ASRC_UPD_DEL_errmsg').hide();
                    $("#ASRC_UPD_DEL_od_btn").attr("disabled", "disabled");
                    $('#ASRC_UPD_DEL_tbl_entry').hide();
                    $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                    $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                    $('#ASRC_UPD_DEL_ta_reportdate').hide();

                }
                else
                {
                    $('#ASRC_UPD_DEL_tbl_entry').hide();
                    $('#ASRC_UPD_DEL_tble_dailyuserentry').hide();
                    $('#ASRC_UPD_DEL_lbl_session').hide();
                    $('#ASRC_UPD_DEL_tble_ondutyentry').hide();
                    $('#ASRC_UPD_DEL_btn_del').hide();
                    $('#ASRC_UPD_DEL_lbl_allactveemps').hide();
                    $('#ASRC_UPD_DEL_tb_dte').hide();
                    $('#ASRC_UPD_DEL_btn_submit').hide();
                    $('#ASRC_UPD_DEL_tbl_htmltable').hide();
                    $('#ASRC_UPD_DEL_lbl_dte').hide();
                    $('#ASRC_UPD_DEL_lbl_btwnranges').hide();
                    $('#ASRC_UPD_DEL_rd_actveemp').hide();
                    $('#ASRC_UPD_DEL_rd_nonactveemp').hide();
                    $('#ASRC_UPD_DEL_lbl_strtdte').hide();
                    $('#ASRC_UPD_DEL_tb_strtdte').hide();
                    $('#ASRC_UPD_DEL_lbl_enddte').hide();
                    $('#ASRC_UPD_DEL_tb_enddte').hide();
                    $('#ASRC_UPD_DEL_lbl_loginid').hide();
                    $('#ASRC_UPD_DEL_lb_loginid').hide();
                    $('#ASRC_UPD_DEL_lbl_actveemp').hide();
                    $('#ASRC_UPD_DEL_lbl_nonactveemp').hide();
                    $('#ASRC_UPD_DEL_btn_search').hide();
                    $('#ASRC_UPD_DEL_tble_attendence').hide();
                    $('#ASRC_UPD_DEL_tble_dailyuserentry').hide();
                    $('#ASRC_UPD_DEL_tble_ondutyentry').hide();
                    $('#ASRC_UPD_DEL_tble_odshow').hide();
                    $('#ASRC_UPD_DEL_errmsg').hide();
                    ASRC_UPD_DEL_clear()
                    $('#ASRC_UPD_DEL_oderrmsg').hide();
                    $('#ASRC_UPD_DEL_btn_allsearch').hide();
                    $('#ASRC_UPD_DEL_tbl_ondutyhtmltable').hide();
                    $('#ASRC_UPD_DEL_btn_srch').hide();
                    $('#ASRC_UPD_DEL_tbl_htmltable').hide();
                    $('#ASRC_UPD_DEL_btn_del').hide();
                    $('#ASRC_UPD_DEL_tb_sdte').hide();
                    $('#ASRC_UPD_DEL_tb_edte').hide();
                    $('#ASRC_UPD_DEL_tbl_entry').hide();
                    $('#ASRC_UPD_DEL_div_tablecontainer').hide();
                    $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
                    $('#ASRC_UPD_DEL_div_header').hide();
                    $('#ASRC_UPD_btn_pdf').hide();
                    $('#ASRC_UPD_DEL_div_headers').hide();
                    $('#ASRC_UPD_btn_od_pdf').hide();
                    $('#ASRC_UPD_DEL_odsrch_btn').hide();
                    $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                    $('#ASRC_UPD_DEL_ta_reportdate').hide();
                }
            });
// ONDUTY SEARCH AND UPDATE PART
            $('.date').datepicker({
                dateFormat:"dd-mm-yy",
                changeYear: true,
                changeMonth: true
            });

// CHANGE EVENT FOR ONDUTY START DATE
            $(document).on('change','#ASRC_UPD_DEL_tb_sdte',function(){
                var ASRC_UPD_DEL_sdate = $('#ASRC_UPD_DEL_tb_sdte').datepicker('getDate');
                var date = new Date( Date.parse( ASRC_UPD_DEL_sdate ));
                date.setDate( date.getDate()  );
                var ASRC_UPD_DEL_edate = date.toDateString();
                ASRC_UPD_DEL_edate = new Date( Date.parse( ASRC_UPD_DEL_edate ));
                $('#ASRC_UPD_DEL_tb_edte').datepicker("option","minDate",ASRC_UPD_DEL_edate);

            });
            // CHANGE EVENT FOR  STARTDATE AND ENDDATE
            $('.date').change(function(){
                if(($("#ASRC_UPD_DEL_tb_sdte").val()=='')||($("#ASRC_UPD_DEL_tb_edte").val()==''))
                {
                    $("#ASRC_UPD_DEL_od_btn").attr("disabled", "disabled");
                }
                else
                {
                    $("#ASRC_UPD_DEL_od_btn").removeAttr("disabled");
                }

            });
            $(document).on('change','#ASRC_UPD_DEL_tb_sdte,#ASRC_UPD_DEL_tb_edte',function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                $('#ASRC_UPD_DEL_tbl_ondutyhtmltable').html('');
                $('#ASRC_UPD_DEL_oderrmsg').hide();
                $('#ASRC_UPD_DEL_odsrch_btn').hide();
                $('#ASRC_UPD_DEL_lbl_oddte').hide();
                $('#ASRC_UPD_DEL_tb_oddte').hide();
                $('#ASRC_UPD_DEL_lbl_des').hide();
                $('#ASRC_UPD_DEL_ta_des').hide();
                $('#ASRC_UPD_DEL_odsubmit').hide();
                $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
                $('#ASRC_UPD_DEL_div_header').hide();
                $('#ASRC_UPD_btn_pdf').hide();
                $('#ASRC_UPD_DEL_div_headers').hide();
                $('#ASRC_UPD_btn_od_pdf').hide();
            });
            //CLICK FUNCTION FOR SEARCH BUTTON IN ONDUTY
            $(document).on('click','#ASRC_UPD_DEL_od_btn',function(){
                $('#ASRC_UPD_section_od').html('')
                $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
                $('#ASRC_UPD_DEL_div_header').hide();
                $('#ASRC_UPD_btn_pdf').hide();
                $('#ASRC_UPD_DEL_div_headers').hide();
                $('#ASRC_UPD_btn_od_pdf').hide();
//                $('.preloader', window.parent.document).show();
                $(".preloader").show();
                $('#ASRC_UPD_DEL_od_btn').attr("disabled","disabled");
                $('#ASRC_UPD_DEL_tbl_ondutyhtmltable').show();
                ondutyflextable()
            });
            // ONDUTY DATA TABLE
            function ondutyflextable(){
                var sdate=$('#ASRC_UPD_DEL_tb_sdte').val();
                var edate=$('#ASRC_UPD_DEL_tb_edte').val();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                        $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        allvalues_array=JSON.parse(xmlhttp.responseText);
                        if(allvalues_array.length!=0){
                            $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                            //HEADER ERR MSG
                            var sd=err_msg[13].toString().replace("WEEKLY","ONDUTY");
                            var msg=sd.toString().replace("[STARTDATE]",sdate);
                            var errmsg=msg.toString().replace("[ENDDATE]",edate);
                            pdfmsg=errmsg;
                            $('#ASRC_UPD_DEL_div_headers').text(errmsg).show();
                            $('#ASRC_UPD_btn_od_pdf').show();
                            var ASRC_UPD_DEL_tbleheader='<table id="ASRC_UPD_DEL_tbl_ondutyhtmltable" border="1"  cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white"><tr><th style="width:10px;"></th><th>DATE</th><th>DESCRIPTION</th><th>USERSTAMP</th><th class="uk-timestp-column">TIMESTAMP</th></tr></thead><tbody>';
                            for(var j=0;j<allvalues_array.length;j++){
                                var id=allvalues_array[j].id;
                                var description=allvalues_array[j].description;
                                var userstamp=allvalues_array[j].userstamp;
                                var timestamp=allvalues_array[j].timestamp;
                                var date=allvalues_array[j].date;
                                ASRC_UPD_DEL_tbleheader+='<tr ><td><input type="radio" name="ASRC_UPD_DEL_rd_tbl" class="ASRC_UPD_DEL_class_radio odclass" id='+id+'  value='+id+' ></td><td width="30px" align="center" nowrap>'+date+'</td><td>'+description+'</td><td>'+userstamp+'</td><td nowrap align="center">'+timestamp+'</td></tr>';
                            }
                            ASRC_UPD_DEL_tbleheader+='</tbody></table>';
                            $('#ASRC_UPD_DEL_tbl_ondutyhtmltable').show();
                            $('#ASRC_UPD_section_od').html(ASRC_UPD_DEL_tbleheader);
                            $('#ASRC_UPD_DEL_tbl_ondutyhtmltable').DataTable( {
                                "aaSorting": [],
                                "pageLength": 10,
                                "sPaginationType":"full_numbers",
                                "aoColumnDefs" : [
                                    { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                            });
                        }
                        else
                        {
                            var sd=err_msg[6].toString().replace("[SDATE]",sdate);
                            var msg=sd.toString().replace("[EDATE]",edate);
                            $('#ASRC_UPD_DEL_oderrmsg').text(msg).show();
                            $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
                            $('#ASRC_UPD_DEL_div_header').hide();
                            $('#ASRC_UPD_btn_pdf').hide();
                            $('#ASRC_UPD_DEL_div_headers').hide();
                            $('#ASRC_UPD_btn_od_pdf').hide();

                        }
                    }
                }
                $('#ASRC_UPD_DEL_div_ondutytablecontainer').show();
                var choice='ONDUTY';
                xmlhttp.open("GET","ADMIN/DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?sdate="+sdate+"&edate="+edate+"&option="+choice,true);
                xmlhttp.send();
                sorting()
            }
// CLICK EVENT FOR ONDUTY RADIO BUTTON
            $(document).on('click','.odclass',function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "fast");
                $('#ASRC_UPD_DEL_odsrch_btn').show();
                $('#ASRC_UPD_DEL_btn_srch').hide();
                $('#ASRC_UPD_DEL_btn_del').hide();
                $('#ASRC_UPD_DEL_odsrch_btn').removeAttr("disabled","disabled");
                $('#ASRC_UPD_DEL_lbl_oddte').hide();
                $('#ASRC_UPD_DEL_tb_oddte').hide();
                $('#ASRC_UPD_DEL_lbl_des').hide();
                $('#ASRC_UPD_DEL_ta_des').hide();
                $('#ASRC_UPD_DEL_odsubmit').hide();

            });
            // CLICK EVENT FOR ONDUTY SEARCH BUTTON
            $(document).on('click','#ASRC_UPD_DEL_odsrch_btn',function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                var ASRC_UPD_DEL_radio=$('input:radio[name=ASRC_UPD_DEL_rd_tbl]:checked').attr('id');
                $("#ASRC_UPD_DEL_odsrch_btn").attr("disabled", "disabled");
                $("#updatepart").show();
                for(var j=0;j<allvalues_array.length;j++){
                    var id=allvalues_array[j].id;
                    if(id==ASRC_UPD_DEL_radio)
                    {
                        var date=  allvalues_array[j].date;
                        var description=allvalues_array[j].description;
                        $('#ASRC_UPD_DEL_lbl_oddte').show();
                        $('#ASRC_UPD_DEL_tb_oddte').val(date).show();
                        $('#ASRC_UPD_DEL_lbl_des').show();
                        $('#ASRC_UPD_DEL_ta_des').val(description).show();
                        $('#ASRC_UPD_DEL_odsubmit').show();
                        $('#ASRC_UPD_DEL_odsubmit').attr("disabled","disabled");
                        $('#ASRC_UPD_DEL_oderrmsg').hide();
                    }
                }
            });
            $('#ASRC_UPD_DEL_ta_des').change(function(){
                if($("#ASRC_UPD_DEL_ta_des").val()=='')
                {
                    $("#ASRC_UPD_DEL_odsubmit").attr("disabled", "disabled");
                }
                else
                {
                    $("#ASRC_UPD_DEL_odsubmit").removeAttr("disabled");
                    $("#ASRC_UPD_DEL_odsubmit").show();
                }
            });
            // CLICK FUNCTIO ONDUTY UPDATE BUTTON
            $('#ASRC_UPD_DEL_odsubmit').click(function(){
                $(".preloader").show();
                var formElement = document.getElementById("ARE_form_adminreportentry");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                        $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        var msg_alert=xmlhttp.responseText;
                        show_msgbox("ONDUTY SEARCH/UPDATE",msg_alert,"success",false);
                        ondutyflextable()
                        $('#ASRC_UPD_DEL_tb_oddte').hide();
                        $('#ASRC_UPD_DEL_lbl_oddte').hide();
                        $('#ASRC_UPD_DEL_ta_des').hide();
                        $('#ASRC_UPD_DEL_lbl_des').hide();
                        $("#ASRC_UPD_DEL_odsubmit").hide();
                        $('#ASRC_UPD_DEL_ta_des').css("height", "50px");
                    }
                }
                var option="ONDUTYUPDATE";
                xmlhttp.open("POST","ADMIN/DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?option="+option,true);
                xmlhttp.send(new FormData(formElement));
            });
            $(document).on('click','#ASRC_UPD_btn_pdf',function(){
                var inputValOne=$('#ASRC_UPD_DEL_lb_loginid').val();
                var inputValTwo=$('#ASRC_UPD_DEL_tb_strtdte').val();
                inputValTwo = inputValTwo.split("-").reverse().join("-");
                var inputValThree=$('#ASRC_UPD_DEL_tb_enddte').val();
                inputValThree = inputValThree.split("-").reverse().join("-");

                if($("input[id=ASRC_UPD_DEL_rd_btwnrange]:checked").val()=='RANGES'){
                    var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=22&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&inputValThree='+inputValThree+'&title='+pdfmsg;
                }
                else if($("input[id=ASRC_UPD_DEL_rd_allactveemp]:checked").val()=='RANGES'){

                    var inputValFour=$('#ASRC_UPD_DEL_tb_dte').val();
                    inputValFour = inputValFour.split("-").reverse().join("-");

                    var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=21&inputValFour='+inputValFour+'&title='+pdfmsg;
                }
            });
            $(document).on('click','#ASRC_UPD_btn_od_pdf',function(){
                var inputValOne=$('#ASRC_UPD_DEL_tb_sdte').val();
                inputValOne = inputValOne.split("-").reverse().join("-");
                var inputValTwo=$('#ASRC_UPD_DEL_tb_edte').val();
                inputValTwo = inputValTwo.split("-").reverse().join("-");
                var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=20&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&title='+pdfmsg;
            });

        });
    </script>
<body>
<div class="container-fluid">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="title text-center"><h4><b>ADMIN REPORT ENTRY /SEARCH /UPDATE</b></h4></div>
    <form id="ARE_form_adminreportentry" class="content form-horizontal">
        <div class="panel-body">
            <fieldset>
                <div style="padding-bottom: 15px">
                    <div class="radio">
                        <label name="reports_entry" id="reports_entry"><input type="radio" name="admin_report_entry" class="radio_click" id="admin_report_entry" value="entries">ENTRY</label>
                    </div>

                    <div class="radio">
                        <label id="reports_search" name="reports_search"><input type="radio" name="admin_report_entry" class="radio_click" id="admin_report_search" value="search">SEARCH/UPDATE</label>
                    </div></div>
                <label id="ARE_lbl_date_err" name="ARE_lbl_date_err" class="errormsg" ></label>
                <div class=" form-group" >
                    <label name="ARE_report_entry" id="ARE_lbl_report_entry" class="srctitle col-sm-12"></label>
                </div>
                <div id="entries" hidden>

                    <div class="row-fluid form-group">
                        <label name="ARE_lbl_optn" class="col-sm-2" id="ARE_lbl_optn">SELECT A OPTION<em>*</em></label>
                        <div class="col-sm-3">
                            <select id="option" name="option" class="adminselectoption form-control">
                                <option>SELECT</option>
                                <option>ADMIN REPORT ENTRY</option>
                                <option>ONDUTY REPORT ENTRY</option>
                            </select>
                        </div></div>

                    <div id="day_entry"></div>
                    <!--                    <div style="padding-bottom: 15px">-->
                    <!--                        <div class="radio">-->
                    <!--                            <label name="entry" class="col-sm-8" id="ARE_lbl_sinentry" hidden>-->
                    <!--                                <div class="col-sm-4">-->
                    <!--                                        <input type="radio" id="ARE_rd_sinentry"  name="entry" value="SINGLE DAY ENTRY" hidden/>SINGLE DAY ENTRY</label>-->
                    <!--                        </div></div></div>-->
                    <div id="multiple_day"></div>
                    <!--     <div style="padding-top: 10px">-->
                    <!--         <div class="radio">-->
                    <!--                    <label name="entry" class="col-sm-8" id="ARE_lbl_mulentry" hidden>-->
                    <!--                        <div class="col-sm-4">-->
                    <!--                                <input type="radio" id="ARE_rd_mulentry" name="entry" value="MULTIPLE DAY ENTRY" hidden/>MULTIPLE DAY ENTRY</label>-->
                    <!--                </div></div></div>-->
                    <div id="multiple_label"></div>
                    <!--<div style="padding-top: 30px">-->
                    <!--        <label name="ARE_lbl_multipleday" id="ARE_lbl_multipleday" class="srctitle col-sm-12" hidden>MULTIPLE DAY ENTRY</label>-->
                    <!--    </div>-->
                    <div id="single_emp"></div>
                    <!--<div class="row-fluid form-group">-->
                    <!--    <div class="radio">-->
                    <!--    <label name="ARE_lbl_emp" class="col-sm-8" id="ARE_lbl_sinemp" hidden>-->
                    <!--        <div class="col-sm-4">-->
                    <!--                <input type="radio" id="ARE_rd_sinemp" name="ARE_rd_emp" value="FOR SINGLE EMPLOYEE"hidden/>FOR SINGLE EMPLOYEE</label>-->
                    <!--</div></div></div>-->
                    <div id="all_emp"></div>
                    <!--<div style="padding-bottom: 30px">-->
                    <!--    <div class="radio">-->
                    <!--    <label name="ARE_lbl_emp" class="col-sm-8" id="ARE_lbl_allemp" hidden>-->
                    <!--        <div class="col-sm-4">-->
                    <!--                <input type="radio" id="ARE_rd_allemp" name="ARE_rd_emp" value="FOR ALL EMPLOYEE"hidden/>FOR ALL EMPLOYEE</label>-->
                    <!--</div></div></div>-->
                    <div class="form-group">
                        <div id="ARE_tble_singledayentry" hidden>

                            <!--    <div class="row-fluid form-group">-->
                            <div class="row-fluid" style="padding-top: 10px">
                                <label name="ARE_lbl_loginid" id="ARE_lbl_loginid" class="col-sm-2">EMPLOYEE NAME</label>
                                <div class="col-sm-3">
                                    <select name="ARE_lb_loginid" id="ARE_lb_loginid" class="form-control" style="display: inline">
                                        <option>SELECT</option>
                                    </select><br>
                                    <label id="ARE_lbl_norole_err" name="ARE_lbl_norole_err" class="errormsg" ></label>
                                </div></div>
                            <div class="row-fluid" style="padding-top: 10px">
                                <label name="ARE_lbl_dte" class="col-sm-2" id="ARE_lbl_dte" hidden>DATE</label>
                                <div class="col-sm-4">
                                    <input type ="text" id="ARE_tb_date" class='tb_date proj datemandtry singledayentry' hidden name="ARE_tb_date" style="width:75px;" />
                                </div></div>
                            <div id="ARE_tble_attendence" class="row-fluid">
                                <label name="ARE_lbl_attendance" class="col-sm-2" id="ARE_lbl_attendance" >ATTENDANCE</label>
                                <div class="col-sm-3">
                                    <select id="ARE_lb_attendance" name="ARE_lb_attendance" class="form-control">
                                        <option>SELECT</option>
                                        <option value="1">PRESENT</option>
                                        <option value="0">ABSENT</option>
                                        <option value="OD">ONDUTY</option>
                                    </select>
                                </div>
                            </div>
                            <div id="ARE_chk_notinfrmd"></div>
                            <div id="permission" style="padding-right: 20px" hidden>
                                <div class="form-group">
                                    <label class="col-sm-2"></label>
                                    <div class="col-sm-9">
                                        <div class="row-fluid">
                                            <div class="col-md-2">
                                                <div class="radio">
                                                    <label name="ARE_permission" class="col-sm-8" id="ARE_lbl_permission">
                                                        <input type="radio" name="permission" id="ARE_rd_permission" class='permissn'value="PERMISSION" hidden >PERMISSION<em>*</em>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <select name="ARE_lb_timing" id="ARE_lb_timing" class="form-control" style="display:none" hidden>
                                                    <option>SELECT</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="col-md-4">
                                                <div class="radio">
                                                    <label name="ARE_nopermission" class="col-sm-8" id="ARE_lbl_nopermission">
                                                        <input type="radio" name="permission" id="ARE_rd_nopermission" class='permissn' value="NOPERMISSION" hidden >NO PERMISSION<em>*</em></label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row-fluid">
                                <label name="ARE_lbl_session" class="col-sm-2" id="ARE_lbl_session" hidden >SESSION</label>
                                <div class="col-sm-3">
                                    <select name="ARE_lb_ampm" id="ARE_lb_ampm" class="form-control" >
                                        <option>SELECT</option>
                                        <option>FULLDAY</option>
                                        <option>AM</option>
                                        <option>PM</option>
                                    </select>
                                </div></div>
                        </div>

                        <div id="ARE_tble_reasonlbltxtarea"></div>
                        <div id="ARE_tble_projectlistbx"  class="row-fluid" hidden>
                            <label name="ARE_lbl_txtselectproj" class="col-sm-2" id="ARE_lbl_txtselectproj" >PROJECT<em>*</em></label>
                            <div id="ARE_tble_frstsel_projectlistbx" class="col-sm-10"></div>
                        </div>

                        <div id="ARE_tbl_enterthereport"></div>
                        <div id="ARE_tble_bandwidth"></div>
                        <div style="padding-left: 3px">
                            <input type="button"  class="btn" name="ARE_btn_submit" id="ARE_btn_submit"  value="SAVE" >
                        </div>
                    </div>
                    <div><label id="ARE_lbl_errmsg" name="ARE_lbl_errmsg" class="errormsg"></label></div>
                    <div><label id="ARE_lbl_checkmsg" name="ARE_lbl_checkmsg" class="errormsg"></label></div>
                    <div class="form-group">
                        <div id="ARE_tble_mutipledayentry" hidden>
                            <div class="row-fluid">
                                <label name="ARE_lbl_lgnid" class="col-sm-2" id="ARE_lbl_lgnid" >EMPLOYEE NAME</label>
                                <div class="col-sm-4">
                                    <select name="ARE_lb_lgnid" id="ARE_lb_lgnid" class="form-control">
                                    </select>
                                </div></div>
                            <div class="row-fluid" style="padding-top: 10px">
                                <label name="ARE_lbl_sdte" class="col-sm-2" id="ARE_lbl_sdte" hidden>FROM DATE</label>
                                <div class="col-sm-4">
                                    <input type ="text" id="ARE_tb_sdate" class='proj datemandtry change valid form-control' hidden name="ARE_tb_sdate" style="width:100px;" />
                                </div></div>
                            <div class="row-fluid">
                                <label name="ARE_lbl_edte" class="col-sm-2" id="ARE_lbl_edte" hidden>TO DATE</label>
                                <div class="col-sm-4">
                                    <input type ="text" id="ARE_tb_edate" class='proj datemandtry change valid form-control' hidden name="ARE_tb_edate" style="width:100px;" />
                                </div></div></div>

                        <!--    <div style="padding-right: 25px">-->
                        <div id="ARE_tbl_attendence" class="row-fluid">
                            <label name="ARE_lbl_attdnce"  class="col-sm-2" id="ARE_lbl_attdnce" >ATTENDANCE</label>
                            <div class="col-sm-4">
                                <select id="ARE_lb_attdnce" name="ARE_lb_attdnce" class="form-control" >
                                    <option>SELECT</option>
                                    <option value="0">ABSENT</option>
                                    <option value="OD">ONDUTY</option>
                                </select>
                            </div></div>
                        <!--</div>-->

                        <div id="ARE_chk1_notinfrmd"></div>
                        <div id="ARE_tbl_reason"></div>
                        <div style="padding-left: 3px"><input type="button"  class="btn" name="ARE_btn_save" id="ARE_btn_save"  value="SAVE" ></div>

                        <div><label id="ARE_msg" name="ARE_msg" class="errormsg"></label></div>

                        <div id="ARE_tble_ondutyentry" hidden>
                            <div id="onduty_date"></div>
                            <div id="onduty_des"></div>
                            <div id="onduty_button"></div>
                            <div><label id="ARE_lbl_oderrmsg" name="ARE_lbl_oderrmsg" class="errormsg"></label></div>
                        </div>
                    </div>
                </div>
                <!--search update-->
                <div id="search" hidden>
                    <div class="row-fluid form-group">
                        <label name="ASRC_UPD_DEL_lbl_optn" class="col-sm-2" id="ASRC_UPD_DEL_lbl_optn">SELECT A OPTION<em>*</em></label>
                        <div class="col-sm-4">
                            <select id="options" name="option"class="form-control" style="display: inline">
                                <option>SELECT</option>
                                <option>ADMIN REPORT SEARCH UPDATE DELETE</option>
                                <option>ONDUTY REPORT SEARCH UPDATE</option>
                            </select>
                        </div></div>

                    <div id="ASRC_UPD_DEL_tble_dailyuserentry" hidden>
                        <div id="ASRC_UPD_DEL_tbl_entry" hidden>
                            <div class="row-fluid" >
                                <div class="radio">
                                    <label name="ASRC_UPD_DEL_lbl_btwnrange" class="col-sm-12" id="ASRC_UPD_DEL_lbl_btwnrange">

                                        <input type="radio" name="ASRC_UPD_DEL_rd_range" id="ASRC_UPD_DEL_rd_btwnrange" value="RANGES" class='attnd'>BETWEEN RANGE</label>
                                </div></div>

                            <div  class="row-fluid">
                                <div class="radio">
                                    <label name="ASRC_UPD_DEL_lbl_allactveemp" class="col-sm-12" id="ASRC_UPD_DEL_lbl_allactveemp">

                                        <input type="radio" name="ASRC_UPD_DEL_rd_range" id="ASRC_UPD_DEL_rd_allactveemp"   value="RANGES" class='attnd'>ALL ACTIVE EMPLOYEE</label>
                                </div></div>

                            <div class="row-fluid" style="padding-top: 10px">
                                <label name="ASRC_UPD_DEL_lbl_allactveemps" id="ASRC_UPD_DEL_lbl_allactveemps" class="srctitle" hidden>ALL ACTIVE EMPLOYEE</label>
                            </div>
                            <div id="date_click"></div>

                            <div id="between_range"></div>

                            <div id="active_emp"></div>

                            <div id="non_active"></div>

                        </div>
                        <div id="search_click"></div>

                        <div class="row-fluid form-group">
                            <label name="ASRC_UPD_DELlbl_loginid" class="col-sm-2" id="ASRC_UPD_DEL_lbl_loginid"  hidden>EMPLOYEE NAME</label>
                            <div class="col-sm-4">
                                <select name="ASRC_UPD_DEL_lb_loginid" id="ASRC_UPD_DEL_lb_loginid" class="form-control emplistbxactve" hidden>
                                </select>
                            </div></div>

                        <div class="form-group row-fluid">
                            <label name="ASRC_UPD_DEL_lbl_strtdte" class="col-sm-2" id="ASRC_UPD_DEL_lbl_strtdte"  hidden>START DATE<em>*</em></label>
                            <div class="col-sm-8">
                                <input type="text" name="ASRC_UPD_DEL_tb_strtdte" id="ASRC_UPD_DEL_tb_strtdte" hidden class="ASRC_UPD_DEL_date valid clear form-control" style="width:100px;">
                            </div></div>
                        <div class="form-group row-fluid">
                            <label name="ASRC_UPD_DEL_lbl_enddte" class="col-sm-2"  id="ASRC_UPD_DEL_lbl_enddte" hidden>END DATE<em>*</em></label>
                            <div class="col-sm-8">
                                <input type="text" name="ASRC_UPD_DEL_tb_enddte" id="ASRC_UPD_DEL_tb_enddte" hidden class="ASRC_UPD_DEL_date valid clear form-control" style="width:100px;">
                            </div></div>
                        <div>
                            <input type="button" class="btn" name="ASRC_UPD_DEL_btn_search" id="ASRC_UPD_DEL_btn_search"  value="SEARCH" disabled hidden>
                        </div>

                        <div class="row-fluid form-group srctitle col-sm-2"  name="ASRC_UPD_DEL_div_header" id="ASRC_UPD_DEL_div_header" hidden></div>

                        <div ><input type="button" id='ASRC_UPD_btn_pdf' class="btnpdf" value="PDF"></div>
                        <div id="ASRC_UPD_DEL_div_tablecontainer" class="table-responsive"  hidden>
                            <section>
                            </section>
                        </div>

                        <div ><input type="button" id="ASRC_UPD_DEL_btn_srch" class="btn" name="ASRC_UPD_DEL_btn_srch" value="SEARCH" hidden/>
                            <input type="button" id="ASRC_UPD_DEL_btn_del" class="btn" name="ASRC_UPD_DEL_btn_del" value="DELETE" hidden disabled/>
                        </div>
                        <div class="row-fluid form-group" style="padding-top: 25px">
                            <label name="ASRC_UPD_DEL_lbl_reportdte" class="col-sm-2" id="ASRC_UPD_DEL_lbl_reportdte" hidden>DATE</label>
                            <div class="col-sm-8">
                                <input type ="text" id="ASRC_UPD_DEL_ta_reportdate" class='proj datemandtry update_validate ' hidden name="ASRC_UPD_DEL_ta_reportdate" style="width:75px;" /><label id="ASRC_UPD_DEL_errmsg" name="ASRC_UPD_DEL_errmsg" class="errormsg"></label>

                            </div></div>
                        <div id="ASRC_UPD_DEL_tble_attendence" class="row-fluid form-group" >

                            <label name="ASRC_UPD_DEL_lbl_attendance"class="col-sm-2"id="ASRC_UPD_DEL_lbl_attendance" >ATTENDANCE<em>*</em></label>
                            <div class="col-sm-2">
                                <select id="ASRC_UPD_DEL_lb_attendance" name="ASRC_UPD_DEL_lb_attendance" class="update_validate form-control">
                                    <option value="1">PRESENT</option>
                                    <option value="0">ABSENT</option>
                                    <option value="OD">ONDUTY</option>
                                </select>
                            </div></div>
                        <div id="ASRC_chk_notinformed"></div>
                        <div id="permissionupd"  hidden>
                            <!--    <div class="form-group">-->
                            <label class="col-sm-2 form-group"></label>
                            <div class="col-sm-9">
                                <div class="row-fluid">
                                    <div class="col-md-2">
                                        <div class="radio">
                                            <label name="ASRC_UPD_DEL_permission" class="col-sm-8" id="ASRC_UPD_DEL_lbl_permission" hidden>  <input type="radio" name="permission" id="ASRC_UPD_DEL_rd_permission" value="PERMISSION" class='permissn update_validate'  hidden>PERMISSION<em>*</em>
                                        </div>
                                        </label>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                        <select name="ASRC_UPD_DEL_lb_timing" id="ASRC_UPD_DEL_lb_timing" class="update_validate form-control">
                                        </select>
                                        </select>
                                    </div></div>
                                <div class="row-fluid">
                                    <div class="col-md-4">
                                        <div class="radio">
                                            <label name="ASRC_UPD_DEL_nopermission" class="col-sm-10"  id="ASRC_UPD_DEL_lbl_nopermission" hidden> <input type="radio" name="permission" id="ASRC_UPD_DEL_rd_nopermission" value="NOPERMISSION" class='permissn update_validate'  hidden>NO PERMISSION<em>*</em></label>
                                        </div></div>
                                </div>

                            </div></div>
                        <!--</div>-->

                        <div class="form-group row-fluid" style="padding-top: 10px">
                            <label name="ASRC_UPD_DEL_lbl_session"  class="col-sm-2" id="ASRC_UPD_DEL_lbl_session" hidden >SESSION</label>
                            <div class="col-sm-2">
                                <select name="ASRC_UPD_DEL_lb_ampm" id="ASRC_UPD_DEL_lb_ampm" class="update_validate form-control">
                                    <option>SELECT</option>
                                    <option>FULLDAY</option>
                                    <option>AM</option>
                                    <option>PM</option>
                                </select>
                            </div></div>
                    </div>
                    <div id="ASRC_UPD_DEL_tble_reasonlbltxtarea">
                    </div>
                    <div id="flage" hidden>
                        <label name="ASRC_UPD_DEL_lbl_flag" class="col-sm-2" id="ASRC_UPD_DEL_lbl_flag" hidden>
                            <input type="checkbox" name="flag" id="ASRC_UPD_DEL_chk_flag" class='update_validate'  hidden >FLAG</label>

                    </div>
                    <div id="ASRC_UPD_DEL_tble_projectlistbx"  class="form-group"  hidden>
                        <label name="ASRC_UPD_DEL_lbl_txtselectproj" class="col-sm-2"  id="ASRC_UPD_DEL_lbl_txtselectproj" >PROJECT<em>*</em></label>
                        <div class="checkbox col-md-2">
                            <div id="ASRC_UPD_DEL_tble_frstsel_projectlistbx" class="col-sm-10"></div>
                        </div>
                    </div>
                    <div id="ASRC_UPD_DEL_tble_enterthereport"></div>
                    <div id="ASRC_UPD_DEL_tble_bandwidth"></div>
                    <div>
                        <label id="ASRC_UPD_DEL_banerrmsg" name="ASRC_UPD_DEL_banerrmsg" class="errormsg"></label>
                    </div>
                    <div >
                        <input type="button"  class="btn" name="ASRC_UPD_DEL_btn_submit" id="ASRC_UPD_DEL_btn_submit"  value="UPDATE" disabled>
                    </div>

                    <div id="ASRC_UPD_DEL_tble_ondutyentry">
                        <div id="ASRC_UPD_DEL_tble_odshow"hidden>
                            <div class="row-fluid form-group">
                                <label name="ASRC_UPD_DEL_lbl_sdte"  class="col-sm-2" id="ASRC_UPD_DEL_lbl_sdte">START DATE</label>
                                <div class="col-sm-8">
                                    <input type="text" id="ASRC_UPD_DEL_tb_sdte" name="ASRC_UPD_DEL_tb_sdte" class='date datemandtry' style="width:75px;"/>
                                </div></div>
                            <div class="row-fluid form-group">
                                <label name="ASRC_UPD_DEL_lbl_edte" class="col-sm-2" id="ASRC_UPD_DEL_lbl_edte">END DATE</label>
                                <div class="col-sm-8">
                                    <input type="text" id="ASRC_UPD_DEL_tb_edte" name="ASRC_UPD_DEL_tb_edte" class='date datemandtry' style="width:75px;"/>
                                </div></div>

                            <div>
                                <input type="button" id="ASRC_UPD_DEL_od_btn" name="ASRC_UPD_DEL_od_btn" value="SEARCH" class="btn"  disabled />
                            </div>
                        </div>
                        <div class="row-fluid form-group srctitle col-sm-2" name="ASRC_UPD_DEL_div_headers" id="ASRC_UPD_DEL_div_headers" hidden></div>

                        <div><input type="button" id='ASRC_UPD_btn_od_pdf' class="btnpdf" value="PDF"></div>

                        <div id="ASRC_UPD_DEL_div_ondutytablecontainer" class="table-responsive" hidden>
                            <section id="ASRC_UPD_section_od">
                            </section>
                        </div>
                        <div>
                            <input type="button" id="ASRC_UPD_DEL_odsrch_btn" name="ASRC_UPD_DEL_odsrch_btn" value="SEARCH" class="btn"  disabled  />
                        </div>
                        <div>
                            <label id="ASRC_UPD_DEL_oderrmsg" name="ASRC_UPD_DEL_oderrmsg" class="errormsg" hidden></label>
                        </div>
                        <div id="updatepart">
                            <div class="row-fluid form-group">
                                <label name="ASRC_UPD_DEL_lbl_oddte"  class="col-sm-2" id="ASRC_UPD_DEL_lbl_oddte">DATE</label>
                                <div class="col-sm-8">
                                    <input type="text" id="ASRC_UPD_DEL_tb_oddte" name="ASRC_UPD_DEL_tb_oddte" class='odenable datemandtry' style="width:75px;" readonly/>
                                </div></div>

                            <div class="row-fluid form-group">
                                <label name="ASRC_UPD_DEL_lbl_des" class="col-sm-2" id="ASRC_UPD_DEL_lbl_des">DESCRIPTION</label>
                                <div class="col-lg-10">
                                    <textarea id="ASRC_UPD_DEL_ta_des" name="ASRC_UPD_DEL_ta_des" class='odenable form-control tarea'></textarea>
                                </div></div>
                            <div>
                                <input type="button" id="ASRC_UPD_DEL_odsubmit" name="ASRC_UPD_DEL_odsubmit" value="UPDATE" class="btn" disabled  />
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </form>
    <!--</div>-->
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->