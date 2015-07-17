<!--//*******************************************FILE DESCRIPTION*********************************************//
//***********************************************ATTENDANCE**************************************************//
//DONE BY:LALITHA
//VER 0.08-SD:26/06/2015 ED:26/01/2015,ISSUE CLEARED FOR FORM LOADING PROPERLY PROBLEM ND MONTH/YEAR DP
//DONE BY:SARADAMBAL
//VER 0.07-SD:06/01/2015 ED:06/01/2015,TRACKER NO:74,IMPLEMENTED PRELOADER POSITION,CHANGED LOGIN ID INTO EMPLOYEE NAME,REMOVED DP VALIDATION IF DATE IS NULL
//DONE BY:RAJA
//VER 0.06-SD:03/01/2015 ED:03/01/2015, TRACKER NO:166, DESC:IMPLEMENTED PDF BUTTON AND VALIDATED AND GAVE INPUT TO DB
//VER 0.05-SD:05/12/2014 ED:05/12/2014,TRACKER NO:74,IMPLEMENTED HEADERS FOR DATA TABLE AND PDF
//DONE BY:LALITHA
//VER 0.04-SD:01/12/2014 ED:01/12/2014,TRACKER NO:74,Changed Preloder funct
//VER 0.03-SD:07/11/2014 EED:07/11/2014,TRACKER NO:97,Updated Month nd Yr dp fr all db
//VER 0.02-SD:30/10/2014 ED:31/10/2014,TRACKER NO:97,Updated date sorting fn,Changed Header name,Updated Preloader,Increased width of Date bx,Hide the err msgs in select option,Put Mandatry symbol fr dte nd login bx,Hide the Data tble Section id while loading others records,Fixed width fr all dt column,Aligned centre the vals in dt,Updated Comments in db,Removed empty lines
//DONE BY:SAFI
//VER 0.01-INITIAL VERSION, SD:26/09/2014 ED:1/10/2014,TRACKER NO:97
//************************************************************************************************************-->
<?php
include "../../TSLIB/TSLIB_HEADER.php";
include "../../TSLIB/TSLIB_COMMON.php";
?>
<html>
<head>
    <!--HIDE THE CALENDER EVENT FOR DATE PICKER-->
    <style type="text/css" xmlns="http://www.w3.org/1999/html">
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
    <!--SCRIPT TAG START-->
    <script>
        var report_array=[];
        var err_msg_array=[];
        var mindate;
        var maxdate;
        var errmsg;
        var pdferrmsg;
        //DOCUMENT READY FUNCTION START
        $(document).ready(function(){
            $('#REP_btn_att_pdf').hide();
            $('#REP_btn_search').hide();
            //INITIAL LOADING DATAS FR LISTBX VALUES
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var final_array=JSON.parse(xmlhttp.responseText);
                    var loginid_array=final_array[0];
                    err_msg_array=final_array[1];
                    report_array=final_array[2];
                    mindate=final_array[3];
                    maxdate=final_array[4];
                    var active_employee='<option>SELECT</option>';
                    for (var i=0;i<loginid_array.length;i++) {
                        active_employee += '<option value="' + loginid_array[i][1] + '">' + loginid_array[i][0] + '</option>';
                    }
                    $('#REP_lb_loginid').html(active_employee);
                    var report_option='<option>SELECT</option>';
                    for (var i=0;i<report_array.length;i++) {
                        report_option += '<option value="' + report_array[i][1] + '">' + report_array[i][0] + '</option>';
                    }
                    $('#REP_lb_attendance').html(report_option);
                    $('#REP_td_attendance').show();
                    $('#REP_lb_attendance').show();
                }
            }
            var option="search_option";
            xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_ATTENDANCE.do?option="+option);
            xmlhttp.send();
            //CHANGE FUNCTION FOR LOGIN ID LIST BX
            $(document).on('change','#REP_lb_loginid',function(){
                $(".preloader").show();
                $("#REP_btn_search").attr("disabled","disabled");
                $('#REP_tble_absent_count').html('');
                $('#REP_tablecontainer').hide();
                $('#REP_lbl_error').hide();
                $('#no_of_working_days').hide();
                $('#no_of_days').hide();
                $('#src_lbl_error').hide();
                $('#REP_btn_att_pdf').hide();
                var loginid=$('#REP_lb_loginid').val();
                $('#REP_date').val("");
                if(loginid=="SELECT"){
                    $(".preloader").hide();
                    $('#REP_lbl_dte').hide();
                    $('#REP_date').hide();
                    $('#REP_tablecontainer').hide();
                    $('#no_of_working_days').hide();
                    $('#no_of_days').hide();
                    $("#REP_btn_search").hide();
                    $('#src_lbl_error').hide();
                    $('#REP_btn_att_pdf').hide();
                }
                else{
                    var loginid=$('#REP_lb_loginid').val();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            var finaldate=JSON.parse(xmlhttp.responseText);
                            $(".preloader").hide();
                            var min_date=finaldate[0];
                            var max_date=finaldate[1];

                            //DATE PICKER FUNCTION
                            $('.date-pickers').datepicker( {
                                changeMonth: true,      //provide option to select Month
                                changeYear: true,       //provide option to select year
                                showButtonPanel: true,   // button panel having today and done button
                                dateFormat: 'MM-yy',    //set date format
                                maxDate:new Date(),

                                //ONCLOSE FUNCTION
                                onClose: function(dateText, inst) {
//                                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
//                                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                                    var month =inst.selectedMonth;
                                    var year = inst.selectedYear;
                                    $(this).datepicker('setDate', new Date(year, month, 1));//here set the date when closing.
//                                    $(this).blur();//remove focus input box

                                    var option=$('#REP_lb_attendance').val();
                                    if(option=='1'){
                                        validationdp()
                                    }
                                    else{
                                        validation_dps()
                                    }
                                }
                            });

                            //FOCUS FUNCTION
                            $(".date-pickers").focus(function () {
                                $(".ui-datepicker-calendar").hide();
                                $("#ui-datepicker-div").position({ my: "left top", at: "left bottom", of: $(this)});
                            });
                            if(min_date!='' && min_date!=null){
                                $(".date-pickers").datepicker("option","minDate", new Date(min_date));
                                $(".date-pickers").datepicker("option","maxDate", new Date(max_date));
                                $('#REP_lbl_dte').show();
                                $('#REP_date').show();
                                $('#REP_btn_search').show();
                                $('#REP_lbl_error').hide();
                            }
                            else{
                                $('#REP_lbl_error').text(err_msg_array[1]).show();
                                $('#REP_lbl_dte').hide();
                                $('#REP_date').hide();
                                $('#REP_btn_search').hide();
                            }
                            //VALIDATION FOR DATE BX
                            function validationdp(){
                                $('#REP_tablecontainer').hide();
                                $('#no_of_working_days').hide();
                                $('#no_of_days').hide();
                                $('#REP_lbl_error').hide();
                                $('#src_lbl_error').hide();
                                $('#REP_btn_att_pdf').hide();
                                $("#REP_btn_search").attr("disabled","disabled");
                                if(($('#REP_date').val()!='undefined')&&($('#REP_date').val()!='')&&($('#REP_lb_loginid').val()!="SELECT")&&($('#REP_lb_attendance').val()!="SELECT"))
                                {
                                    $("#REP_btn_search").removeAttr("disabled");
                                }
                                else
                                {
                                    $("#REP_btn_search").attr("disabled","disabled");
                                }
                            }

                        }
                    }
                    var choice="login_id"
                    xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_ATTENDANCE.do?login_id="+loginid+"&option="+choice,true);
                    xmlhttp.send();
                }
            });
            //CHANGE FUNCTION FOR ATTENDANCE LISTBX
            $(document).on('change','#REP_lb_attendance',function(){
                $(".preloader").show();
                $('#REP_tablecontainer').hide();
                $('#REP_btn_search').hide();
                $('#REP_lbl_dte').show();
                $('#src_lbl_error').hide();
                $('#REP_btn_att_pdf').hide();
                $('#REP_date').val('');
                $('#REP_lb_loginid').hide();
                $('#REP_lb_loginid').prop('selectedIndex',0)
                $('#REP_lbl_loginid').hide();
                $('#no_of_working_days').hide();
                $('#no_of_days').hide();
                $('#REP_lbl_error').hide();
                $('#REP_tble_absent_count').html('');
                var option=$('#REP_lb_attendance').val();
                if(option=="1"){
                    $(".preloader").hide();
                    $('#REP_lb_loginid').show();
                    $('#REP_lbl_loginid').show();
                    $('#REP_lbl_dte').hide();
                    $('#REP_date').hide();
                    $('#REP_btn_search').hide();
                }
                if(option=="SELECT"){
                    $(".preloader").hide();
                    $('#REP_lbl_dte').hide();
                    $('#REP_date').hide();
                    $('#REP_tablecontainer').hide();
                    $('#REP_btn_search').hide();
                    $('#no_of_working_days').hide();
                    $('#no_of_days').hide();
                    $('#src_lbl_error').hide();
                    $('#REP_btn_att_pdf').hide();
                }
                if(option=='6' || option=='2'){
                    $(".preloader").hide();
                    $('#REP_btn_search').attr("disabled","disabled").show();



//            DATE PICKER FUNCTION
                    $('.date-pickers').datepicker( {
                        changeMonth: true,      //provide option to select Month
                        changeYear: true,       //provide option to select year
                        showButtonPanel: true,   // button panel having today and done button
                        dateFormat: 'MM-yy',    //set date format
//                //ONCLOSE FUNCTION
                        onClose: function(dateText, inst) {
//                            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
//                            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                            var month =inst.selectedMonth;
                            var year = inst.selectedYear;
                            $(this).datepicker('setDate', new Date(year, month, 1));//here set the date when closing.
//                    $(this).blur();//remove focus input box
                            validation_dps()
                        }
                    });
                    //FOCUS FUNCTION
                    $(".date-pickers").focus(function () {
                        $(".ui-datepicker-calendar").hide();
                        $("#ui-datepicker-div").position({
                            my: "center top",
                            at: "center bottom",
                            of: $(this)
                        });
                    });
                    if(mindate!='' && maxdate!=null){
                        $(".date-pickers").datepicker("option","minDate", new Date(mindate));
                        $(".date-pickers").datepicker("option","maxDate", new Date(maxdate));}
                    $('#REP_date').show();
                    //VALIDATION FOR DATE BX

                }
            });
            function validation_dps(){
                $('#REP_tablecontainer').hide();
                $('#no_of_working_days').hide();
                $('#no_of_days').hide();
                $('#REP_lbl_error').hide();
                $('#src_lbl_error').hide();
                $('#REP_btn_att_pdf').hide();
                $("#REP_btn_search").attr("disabled","disabled");
                if(($('#REP_date').val()!='undefined')&&($('#REP_date').val()!=''))
                {
                    $("#REP_btn_search").removeAttr("disabled");
                }
                else
                {
                    $("#REP_btn_search").attr("disabled","disabled");
                }
            }
            var allvalues_array;
            //CHANGE FUNCTION FOR DATE BX
            $(document).on('click','#REP_btn_search',function(){
                $(".preloader").show();
                $("#REP_btn_search").attr("disabled","disabled");
                $('#REP_tble_absent_count').html('');
                $('section').html('');
                $('#no_of_days').hide();
                $('#no_of_working_days').hide();
                $('#REP_lbl_error').hide();
                $('#src_lbl_error').hide();
                $('#REP_btn_att_pdf').hide();
                var option=$('#REP_lb_attendance').val();
                var date=$('#REP_date').val();
                var loginid=$('#REP_lb_loginid').val();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        allvalues_array=JSON.parse(xmlhttp.responseText);
                        if(allvalues_array.length!=0){
                            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                            if(option=='6'){
                                var ADM_tableheader='<table id="REP_tble_absent_count" border="1"  cellspacing="0" class="srcresult"  ><thead  bgcolor="#6495ed" style="color:white"><tr><th style="max-width:50px";>EMPLOYEE NAME</th><th style="max-width:50px">REPORT ENTRY MISSED</th></tr></thead><tbody>'
                                for(var j=0;j<allvalues_array.length;j++){
                                    var name=allvalues_array[j].name;
                                    var absent_count=allvalues_array[j].absent_count;
                                    errmsg=err_msg_array[4].toString().replace("[MONTH]",date);
                                    $('#src_lbl_error').text(errmsg).show();
                                    $('#REP_btn_att_pdf').show();
                                    pdferrmsg=errmsg;
                                    ADM_tableheader+='<tr ><td>'+name+'</td><td >'+absent_count+'</td></tr>';
                                }
                            }
                            else if(option=='2'){
                                var working_days= allvalues_array[0].total_working_days;
                                var total_days= allvalues_array[0].total_days;
                                errmsg=err_msg_array[3].toString().replace("[MONTH]",date);
                                pdferrmsg=errmsg;
                                $('#no_of_working_days').text("TOTAL NO OF WORKING DAYS: "  +  working_days  +  " DAYS").show();
                                $('#no_of_days').text("TOTAL NO OF DAYS: "  +   total_days   +  " DAYS").show();
                                $('#src_lbl_error').text(errmsg).show();
                                $('#REP_btn_att_pdf').show();
                                var ADM_tableheader='<table id="REP_tble_absent_count" border="1"  cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white;width:500px"><tr><th>EMPLOYEE NAME</th><th>NO OF PRESENT</th><th>NO OF ABSENT</th><th>NO OF ONDUTY</th><th>TOTAL HOUR(S) OF PERMISSION</th></tr></thead><tbody>'
                                for(var j=0;j<allvalues_array.length;j++){
                                    var name=allvalues_array[j].loginid;
                                    var absent_count=allvalues_array[j].absent_count;
                                    var present_count=allvalues_array[j].present_count;
                                    var onduty_count=allvalues_array[j].onduty_count;
                                    var permission_count=allvalues_array[j].permission_count;
                                    ADM_tableheader+='<tr ><td style="center">'+name+'</td><td>'+present_count+'</td><td>'+absent_count+'</td><td>'+onduty_count+'</td><td>'+permission_count+'</td></tr>';
                                }
                            }
                            else if(option=='1'){
                                var working_days= allvalues_array[0].working_day;
                                var total_days= allvalues_array[0].today_no_days;
                                errmsg=err_msg_array[2].toString().replace("[MONTH]",date);
                                errmsg=errmsg.replace("[EMPLOYEE]",$("#REP_lb_loginid option:selected").text());
                                var loginname;
                                var loginpos=loginid.search("@");
                                if(loginpos>0){
                                    loginname=loginid.substring(0,loginpos);
                                }
                                pdferrmsg=errmsg;//.replace(loginid,$("#REP_lb_loginid option:selected").text());
                                $('#no_of_working_days').text("TOTAL NO OF WORKING DAYS: "  +  working_days  +  " DAYS").show();
                                $('#no_of_days').text("TOTAL NO OF DAYS: "  +   total_days   +  " DAYS").show();
                                $('#src_lbl_error').text(errmsg).show();
                                $('#REP_btn_att_pdf').show();
                                var ADM_tableheader='<table id="REP_tble_absent_count" border="1"  cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white;width:1400px"><tr><th class="uk-date-column">DATE</th><th>PRESENT</th><th>ABSENT</th><th>ONDUTY</th><th>PERMISSION HOUR(S)</th></tr></thead><tbody>'
                                for(var j=0;j<allvalues_array.length;j++){
                                    var report_date=allvalues_array[j].reportdate;
                                    var absent_count=allvalues_array[j].absents;
                                    if(absent_count==null)
                                        absent_count='';
                                    var present_count=allvalues_array[j].presents;
                                    if(present_count==null)
                                        present_count='';
                                    var onduty_count=allvalues_array[j].ondutys;
                                    if(onduty_count==null)
                                        onduty_count='';
                                    var permission_count=allvalues_array[j].permission_counts;
                                    if(permission_count==null)
                                        permission_count='';
                                    ADM_tableheader+='<tr ><td>'+report_date+'</td><td>'+present_count+'</td><td>'+absent_count+'</td><td>'+onduty_count+'</td><td>'+permission_count+'</td></tr>';
                                }
                            }
                            ADM_tableheader+='</tbody></table>';
                            $('section').html(ADM_tableheader);
                            $('#REP_tble_absent_count').DataTable( {
                                "aaSorting": [],
                                "pageLength": 10,
                                "sPaginationType":"full_numbers",
                                "aoColumnDefs" : [
                                    { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                            });
                            $('#REP_lbl_error').hide();
                        }
                        else
                        {
                            $('#REP_tble_absent_count').hide();
                            $('#REP_div_absent_count').hide();
                            var msg=err_msg_array[0].toString().replace("[DATE]",date);
                            $('#REP_lbl_error').text(msg).show();
                            $('#REP_tablecontainer').hide();
                            $('#no_of_working_days').hide();
                            $('#no_of_days').hide();
                            $('#src_lbl_error').hide();
                            $('#REP_btn_att_pdf').hide();
                        }
                    }
                }
                $('#REP_tablecontainer').show();
                xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_ATTENDANCE.do?option="+option+"&date="+date+"&loginid="+loginid,true);
                xmlhttp.send();
            });
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
                };
            }
            $(document).on('click','#REP_btn_att_pdf',function(){
                var inputValOne=$('#REP_date').val();
                var inputValTwo=$('#REP_lb_loginid').val();
                if($('#REP_lb_attendance').val()==1){
                    var url=document.location.href='../TSLIB/TSLIB_COMMON_PDF.do?flag=11&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&title='+pdferrmsg;
                }
                else if($('#REP_lb_attendance').val()==2){
                    var url=document.location.href='../TSLIB/TSLIB_COMMON_PDF.do?flag=12&inputValOne='+inputValOne+'&title='+pdferrmsg;
                }
                else if($('#REP_lb_attendance').val()==6){
                    var url=document.location.href='../TSLIB/TSLIB_COMMON_PDF.do?flag=13&inputValOne='+inputValOne+'&title='+pdferrmsg;
                }
            });
        });
        //DOCUMENT READY FUNCTION END
    </script>
    <!--SCRIPT TAG END-->
    <!--BODY TAG START-->
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="title text-center"><h4><b>ATTENDANCE</b></h4></div>
    <form   id="REP_form_attendance" class="content" role="form" >
        <div class="panel-body">
            <fieldset>
                <div class="row-fluid form-group">
                    <div width="150" id="REP_td_attendance" hidden> </div>
                    <label name="REP_lbl_optn" id="REP_lbl_optn" class="col-sm-3">SELECT A OPTION<em>*</em></label>
                    <div class="col-sm-6">
                        <select id="REP_lb_attendance" name="option" class="form-control" hidden>
                        </select>
                    </div></div>

                <div class="row-fluid form-group">
                    <label name="REP_lbl_loginid"  class="col-sm-3" id="REP_lbl_loginid" hidden>EMPLOYEE NAME<em>*</em></label>
                    <div class="col-sm-6 ">
                        <select name="REP_lb_loginid" id="REP_lb_loginid" class="form-control" style="display:none">
                        </select>
                    </div></div>

                <div class="row-fluid form-group">
                    <label name="REP_lbl_dte" id="REP_lbl_dte" class="col-sm-3" hidden>DATE<em>*</em></label>
                    <div class="col-sm-6">
                        <input type ="text" id="REP_date" class='date-pickers datemandtry' hidden name="date" style="width:100px;" />
                    </div></div>

                <div class="row-fluid form-group  col-sm-2">
                    <input type="button" class="btn" name="REP_btn_search" id="REP_btn_search"  value="SEARCH" disabled>
                </div>

                <div class="row-fluid form-group form-inline col-sm-offset-0 col-sm-2">
                    <label id="src_lbl_error" class="srctitle"></label>
                </div><br>
                <div class="row-fluid form-group form-inline col-sm-offset-0 col-sm-2">
                    <label id="no_of_days" class="srctitle"></label>
                </div>
                <div class="row-fluid form-group form-inline col-sm-offset-0 col-sm-2">
                    <label id="no_of_working_days" class="srctitle"></label>
                </div>
                <div class="row-fluid form-group col-sm-2">
                    <input type="button" class="btnpdf" id="REP_btn_att_pdf" value="PDF">
                </div>
                <div id="REP_tablecontainer" style="max-width:800px;" class="table-responsive row-fluid form-group form-inline col-sm-offset-0 col-sm-2" hidden>
                    <section>
                    </section>
                </div>
                <label id="REP_lbl_error" class="errormsg" hidden></label>
            </fieldset>
        </div>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->