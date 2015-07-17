<?php
include "../TSLIB/TSLIB_HEADER.php";
//include "NEW_MENU.php";
?>
<style type="text/css" xmlns="http://www.w3.org/1999/html">
    .calendar-off table.ui-datepicker-calendar {
        display:none !important;
    }
</style>
<html>
<head>
    <!--SCRIPT TAG START-->
    <script>
        //READY FUNCTION START
        $(document).ready(function(){
            $(".preloader").hide();
            $("#REP_tb_strtdtebyrange").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true,beforeShow:function(input, inst) {
                $(inst.dpDiv).removeClass('calendar-off');
            }});
            $("#REP_tb_enddtebyrange").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
            var REP_chk_errorAarray=[];
            var uld_id;
            var uld_name;
            var CLK_errorAarray;
            var errmsg;
            var pdferrmsg;
            var msg;
            var CLK_start_dates='';
            var CLK_end_dates='';
            var daterange_val=[];
            var uld_id;
            var uld_name;
            $(document).on('click','.clock_click',function(){
                var radiooption=$(this).val();
                if(radiooption=='clockinout')
                {
                    $('#REP_lbl_report_entry').html('CLOCK IN/OUT DETAILS');
                    $('#clockinout').show();
                    $('#clockmissed').hide();
                    $('#REP_btn_searchdaterange').attr('disabled','disabled');
                    $('#no_of_days').hide();
                    $('#src_lbl_error_login').hide();
                    $('#CLK_btn_emp_pdf').hide();
                    $('#CLK_nodata_pdflextbles').hide();
                    $('#CLK_div_actvenon_dterange').hide();
                    $('#CLK_db_selectmnths').val('');
                    $('#CLK_nodata_rc').hide();
                    $('#REP_btn_pdf').hide();
                    REP_chk_errorAarray=[];
                    //GEETING INITIAL DATA FROM DB
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            var values_array=JSON.parse(xmlhttp.responseText);
                            $("#REP_tb_strtdtebyrange").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                            $("#REP_tb_enddtebyrange").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                            REP_chk_errorAarray=values_array[3];
                            $('#REP_nodata_rc').text(REP_chk_errorAarray[3]).show();
                            $('#REP_chk_lbl_srchby').hide();
                        }
                    }
                    var option="common";
                    xmlhttp.open("GET","USER/DB_DAILY_REPORTS_USER_CLOCK_IN_OUT_DETAILS.do?option="+option);
                    xmlhttp.send();
                    date();
                    daterange_val=[];
                    uld_id;
                    uld_name;
                }
                else if(radiooption=='clockmissed')
                {
                    $('#REP_lbl_report_entry').html('CLOCK OUT MISSED DETAILS');
                    $('#CLK_nodata_rc').hide();
                    $('#clockinout').hide();
                    $('#clockmissed').show();
                    $('#REP_tb_strtdtebyrange').val('');
                    $('#REP_tb_enddtebyrange').val('');
                    $('#REP_lbl_daterange').hide();
                    $('#REP_btn_pdf').hide();
                    $('#REP_tablecontainer_bydaterange').hide();
                    $('.date-pickers').html('');
                    $('#CLK_nodata_rc').hide();
//                    $('#REP_lbl_report_entry').hide();
                    $('CLK_btn_search').hide();
                    $('CLK_lbl_selectmnths').hide();
                    uld_id;
                    uld_name;
                    CLK_errorAarray;
                    errmsg;
                    pdferrmsg;
                    msg;
                    err();
                    $(".ui-datepicker-calendar").hide();
                    $('#CLK_btn_emp_pdf').hide();
                    $(".preloader").show();
                    function err(){
                        var xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                                $(".preloader").hide();
                                var values_array=JSON.parse(xmlhttp.responseText);
                                $('.date-pickers').html('');
                                CLK_errorAarray=values_array;
                            }
                            else
                            {

//                        $('#CLK_nodata_rc').text(CLK_errorAarray[2]).show();
                            }
                        }
                        var option="common";
                        xmlhttp.open("GET","USER/DB_DAILY_REPORTS_USER_CLOCK_OUT_MISSED_DETAILS.do?option="+option);
                        xmlhttp.send();
                    }
                    CLK_start_dates='';
                    CLK_end_dates='';
                }
            });

            //FUNCTION FOR SETTINF MIN ND MAX DATE
            function date()
            {
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        daterange_val=JSON.parse(xmlhttp.responseText);
                        var REV_start_dates=daterange_val[0];
                        var REV_end_dates=daterange_val[1];
                        uld_id=daterange_val[2];
                        uld_name=daterange_val[3];
                        $("#REP_tb_strtdtebyrange").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                        $("#REP_tb_enddtebyrange").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                        $('#REP_tb_strtdtebyrange').datepicker("option","minDate",new Date(REV_start_dates));
                        $('#REP_tb_strtdtebyrange').datepicker("option","maxDate",new Date(REV_end_dates));
                        $('#REP_tb_enddtebyrange').datepicker("option","maxDate",new Date(REV_end_dates));
                        if(REV_start_dates==null){
                            $('#REP_nodata_btwrange').text(REP_chk_errorAarray[3]).show();
                            $('#REP_tb_strtdtebyrange').hide();
                            $('#REP_tb_enddtebyrange').hide();
                            $('#REP_lbl_strtdtebyrange').hide();
                            $('#REP_btn_searchdaterange').hide();
                            $('#REP_lbl_enddte').hide();

                        }
                        else{
                            $('#REP_nodata_btwrange').hide();
                            $('#REP_tb_strtdtebyrange').show();
                            $('#REP_tb_enddtebyrange').show();
                            $('#REP_lbl_strtdtebyrange').show();
                            $('#REP_btn_searchdaterange').show();
                            $('#REP_lbl_enddte').show();

                        }
                    }
                }
                var choice="set_datemin_max";
                xmlhttp.open("GET","USER/DB_DAILY_REPORTS_USER_CLOCK_IN_OUT_DETAILS.do?option="+choice);
                xmlhttp.send();
            }
            //SET END DATE
            $(document).on('change','#REP_tb_strtdtebyrange',function(){
                $('#REV_lbl_emptitle').hide();
                $('#REP_lbl_daterange').hide();
                $('#REP_btn_pdf').hide();
                var USRC_UPD_startdate = $('#REP_tb_strtdtebyrange').datepicker('getDate');
                var date = new Date( Date.parse( USRC_UPD_startdate ));
                date.setDate( date.getDate()  );
                var USRC_UPD_todate = date.toDateString();
                USRC_UPD_todate = new Date( Date.parse( USRC_UPD_todate ));
                $('#REP_tb_enddtebyrange').datepicker("option","minDate",USRC_UPD_todate);
            });

            // CHANGE EVENT FOR STARTDATE AND ENDDATE
            $(document).on('change','.valid',function(){
                $('#REV_lbl_emptitle').hide();
                $('#REP_lbl_daterange').hide();
                $('#REP_btn_pdf').hide();
                $('#section').html('');
                $('#REP_tablecontainer_bydaterange').hide();
                if(($("#REP_tb_strtdtebyrange").val()=='')||($("#REP_tb_enddtebyrange").val()==''))
                {
                    $("#REP_btn_searchdaterange").attr("disabled", "disabled");
                }
                else
                {
                    $("#REP_btn_searchdaterange").removeAttr("disabled");
                }
            });
            var allvalues_array=[];
            var REP_strtend_errmsgs;
            $(document).on('click','#REP_btn_searchdaterange',function(){
                $(".preloader").show();
                $("#REP_btn_searchdaterange").attr("disabled","disabled");
                $('#REP_tablecontainer_bydaterange').show();
                $('section').html('');
                var option=$('#REP_chk_lb_srchby').val();
                var startdate=$('#REP_tb_strtdtebyrange').val();
                var enddate=$('#REP_tb_enddtebyrange').val();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        allvalues_array=JSON.parse(xmlhttp.responseText);
                        if(allvalues_array.length!=0){
                            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                            var sd=REP_chk_errorAarray[4].toString().replace("[LOGINID]",uld_name);
                            var errmsg=sd.toString().replace("[STARTDATE]",startdate);
                            REP_strtend_errmsgs=errmsg.toString().replace("[ENDDATE]",enddate);
                            $('#REP_lbl_daterange').text(REP_strtend_errmsgs).show();
                            $('#REP_btn_pdf').show();

                            var ADM_tableheader='<table id="REP_tble_absent_count" border="1"  cellspacing="0" class="srcresult" style="width:1000px" ><thead  bgcolor="#6495ed" style="color:white"><tr><th class="uk-date-column">DATE</th><th>CLOCK IN</th><th style="width:250px">CLOCK IN LOCATION</th><th>CLOCK OUT</th><th style="width:250px">CLOCK OUT LOCATION</th></tr></thead><tbody>'
                            for(var j=0;j<allvalues_array.length;j++){
                                var check_in_date=allvalues_array[j].check_in_date;
                                if((check_in_date=='null')||(check_in_date==undefined))
                                {
                                    check_in_date='';
                                }
                                var clockin=allvalues_array[j].check_in_time;
                                if((clockin=='null')||(clockin==undefined))
                                {
                                    clockin='';
                                }
                                var clockinlocation=allvalues_array[j].check_in_location;
                                if((clockinlocation=='null')||(clockinlocation==undefined))
                                {
                                    clockinlocation='';
                                }
                                var clockout=allvalues_array[j].check_out_time;
                                if((clockout=='null')||(clockout==undefined))
                                {
                                    clockout='';
                                }
                                var clockoutlocation=allvalues_array[j].check_out_location;
                                if((clockoutlocation=='null')||(clockoutlocation==undefined))
                                {
                                    clockoutlocation='';
                                }
                                ADM_tableheader+='<tr ><td style="width:75px;">'+check_in_date+'</td><td align="center">'+clockin+'</td><td align="center" style="width:250px">'+clockinlocation+'</td><td align="center">'+clockout+'</td><td align="center" style="width:250px">'+clockoutlocation+'</td></tr>';
                            }
                            ADM_tableheader+='</tbody></table>';
                            $('section').html(ADM_tableheader);
                            $('#REP_tble_absent_count').DataTable( {
                                "aaSorting": [],
                                "pageLength": 10,
                                "sPaginationType":"full_numbers",
                                "aoColumnDefs" : [
                                    { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"} ]
                            });
                        }
                        else
                        {
                            var sd=REP_chk_errorAarray[1].toString().replace("[SDATE]",startdate);
                            var msg=sd.toString().replace("[EDATE]",enddate);
                            $('#REP_nodata_btwrange').text(msg).show();
                            $('#REP_tablecontainer_bydaterange').hide();
                            $('#REP_lbl_daterange').hide();
                            $('#REP_btn_pdf').hide();
                        }
                    }
                }
                $('#REP_tablecontainer_bydaterange').show();
                var option="BETWEEN_RANGE";
                xmlhttp.open("GET","USER/DB_DAILY_REPORTS_USER_CLOCK_IN_OUT_DETAILS.do?option="+option+"&startdate="+startdate+"&enddate="+enddate);
                xmlhttp.send();
                sorting();
            });

            //CLICK FUNCTION FOR PDF BUTTON
            $(document).on('click','#REP_btn_pdf',function(){
                var inputValOne=$('#REP_tb_strtdtebyrange').val();
                inputValOne = inputValOne.split("-").reverse().join("-");
                var inputValTwo=$('#REP_tb_enddtebyrange').val();
                inputValTwo = inputValTwo.split("-").reverse().join("-");
                var inputValThree=uld_id;
                var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=24&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&inputValThree='+inputValThree+'&title='+REP_strtend_errmsgs;
            });
            //DATE PICKER FUNCTION
            $('.date-pickers').datepicker( {
                changeMonth: true,      //provide option to select Month
                changeYear: true,       //provide option to select year
                showButtonPanel: true,   // button panel having today and done button
                dateFormat: 'MM-yy',
                beforeShow:function(input, inst) {
                    $(inst.dpDiv).addClass('calendar-off');
                },
                //set date format
                //ONCLOSE FUNCTION
                onClose: function(dateText, inst) {
//                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
//                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    var month =inst.selectedMonth;
                    var year = inst.selectedYear;
                    $(this).datepicker('setDate', new Date(year, month, 1));//here set the date when closing.
//            $(this).blur();//remove focus input box
                    $("#CLK_btn_search").attr("disabled");
                    validationdp()
                }
            });

//            //FOCUS FUNCTION
//            $(".date-pickers").focus(function () {
//                $(".ui-datepicker-calendar").hide();
//                $("#ui-datepicker-div").position({
//                    my: "center top",
//                    at: "center bottom",
//                    of: $(this)
//                });
//            });


            //FUNCTION FOR SETTINF MIN ND MAX DATE
//

            $(".preloader").show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var date_val=JSON.parse(xmlhttp.responseText);
                    CLK_start_dates=date_val[0];
                    CLK_end_dates=date_val[1];
                    uld_id=date_val[2];
                    uld_name=date_val[3];
                    if(CLK_start_dates!=null && CLK_start_dates!=''){

                        $('#CLK_btn_search').show();
                        $('#CLK_lbl_selectmnths').show();
                        $('#CLK_db_selectmnths').show();
                        $('#CLK_nodata_lgnid').hide();
                        $('#src_lbl_error_login').hide();
                        $('#no_of_days').hide();
                        $(".date-pickers").datepicker("option","minDate", new Date(CLK_start_dates));
                        $(".date-pickers").datepicker("option","maxDate", new Date(CLK_end_dates));
                    }
                    else{

                        $('#CLK_btn_search').hide();
                        $('#CLK_lbl_selectmnths').hide();
                        $('#CLK_db_selectmnths').hide();
                        $('#src_lbl_error_login').text(CLK_errorAarray[2]).addClass('errormsg').removeClass('srctitle').show();
                    }
                }
            }
            var choice="minmax_dtewth_loginid";
            xmlhttp.open("GET","USER/DB_DAILY_REPORTS_USER_CLOCK_OUT_MISSED_DETAILS.do?option="+choice);
            xmlhttp.send();




            //VALIDATION FOR DATE BX
            function validationdp(){
                $('section').html('');
                $('sections').html('');
                $('#CLK_div_actvenon_dterange').hide();
                $('#CLK_nodata_pdflextbles').hide();
                $('#src_lbl_error').hide();
                $('#no_of_days').hide();
                $('#CLK_btn_mnth_pdf').hide();
                $('#src_lbl_error_login').hide();
                $('#CLK_btn_emp_pdf').hide();
                $("#CLK_btn_search").attr("disabled","disabled");
                if($("#CLK_db_selectmnths").val()=='')
                {
                    $("#CLK_btn_search").attr("disabled","disabled");
                    $('#CLK_nodata_rc').hide();
                }
                if(($('#CLK_db_selectmnths').val()!='undefined')&&($('#CLK_db_selectmnths').val()!='')&&($('#CLK_lb_loginid').val()!="SELECT"))
                {
                    $("#CLK_btn_search").removeAttr("disabled");
                    $('#CLK_nodata_rc').hide();
                }
                else
                {
                    $("#CLK_btn_search").attr("disabled","disabled");
                    $('#CLK_nodata_rc').hide();
                }
            }

            // CLICK EVENT FOR LOGIN ID SEARCH BTN
            var CLK_actnon_values=[];
            var errmsg;
            $(document).on('click','#CLK_btn_search',function(){
                $('#CLK_nodata_pdflextbles').hide();
                $('#CLK_div_actvenon_dterange').hide();
                $('#CLK_tble_lgn').html('');
                $('#CLK_btn_search').attr("disabled","disabled");
                var CLK_monthyear=$('#CLK_db_selectmnths').val();
                var CLK_reportdte;
                var CLK_loginid=$('#CLK_lb_loginid').val();
                $(".preloader").show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        var CLK_actnon_values=JSON.parse(xmlhttp.responseText);
                        if(CLK_actnon_values[0].length!=0)
                        {
                            var CLK_reportdate= CLK_actnon_values[0];
                            var total= CLK_actnon_values[1];
                            for(var i=0;i<total.length;i++){
                                CLK_reportdte=total[i].count;
                            }
                            $('#no_of_days').text("TOTAL NO OF DAYS FOR CLOCK OUT MISSED: "  +   CLK_reportdte   +  " DAYS").show();
                            errmsg=CLK_errorAarray[4].toString().replace("[MONTH]",CLK_monthyear);
                            var msg=errmsg.toString().replace("BANDWIDTH",'CLOCK OUT MISSED DETAILS');
                            errmsg=msg.replace("[LOGINID]", uld_name);
                            $('#src_lbl_error_login').text(errmsg).addClass('srctitle').removeClass('errormsg').show();
                            $('#CLK_btn_emp_pdf').show();
                            $('#CLK_div_actvenon_dterange').show();

                            var CLK_table_header='<table id="CLK_tble_lgn" border="1"  cellspacing="0" class="srcresult" width=300px ><thead  bgcolor="#6495ed" style="color:white"><tr><th style="width:30px">CLOCK OUT MISSED DATE</th></tr></tfoot><tbody>'
                            for(var i=0;i<CLK_reportdate.length;i++){
                                var CLK_dte=CLK_reportdate[i].date;
                                CLK_table_header+='<tr><td align="center" style="width:30px">'+CLK_dte+'</td></tr>';
                            }
                            CLK_table_header+='</tbody></table>';
                            $('section').html(CLK_table_header);
                            $('#CLK_tble_lgn').DataTable({
                            });

                        }
                        else
                        {
                            var sd=CLK_errorAarray[1].toString().replace("[DATE]",CLK_monthyear);
                            $('#CLK_nodata_pdflextbles').show();
                            $('#CLK_nodata_pdflextbles').text(sd);
                            $('#CLK_div_actvenon_dterange').hide();
                            $('#CLK_tble_lgn').html('');
                        }
                    }
                }
                var option="CLK_loginid_searchoption";
                xmlhttp.open("GET","USER/DB_DAILY_REPORTS_USER_CLOCK_OUT_MISSED_DETAILS.do?option="+option+"&CLK_monthyear="+CLK_monthyear);
                xmlhttp.send();
            });
            //CLICK EVENT FOR PDF BUTTON
            $(document).on('click','#CLK_btn_mnth_pdf',function(){
                var inputValOne=$('#CLK_db_selectmnth').val();
                var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=26&inputValOne='+inputValOne+'&title='+msg;
            });
            $(document).on('click','#CLK_btn_emp_pdf',function(){
                var inputValOne=$("#CLK_db_selectmnths").val();
                var inputValThree =uld_id;
                var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=27&inputValOne='+inputValOne+'&inputValThree='+inputValThree+'&title='+errmsg;
            });

        });
    </script>
</head>
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/></div>
    <div class="title text-center"><h4><b>CLOCK IN/OUT DETAILS</b></h4></div>
    <form  id="REP_chk_form_details" class="content" role="form">
        <div class="panel-body">
            <fieldset>
                <div style="padding-left: 15px" >
                    <div class="radio">
                        <label name="clock_in_entry" class="col-sm-12"  id="clock_in_entry">
                            <input type="radio" name="clock" class="clock_click" id="clock_in_out" value=clockinout>CLOCK IN/OUT DETAILS</label>
                    </div></div>
                <div style="padding-left: 15px">
                    <div class="radio">
                        <label name="clock_missed_details" class="col-sm-12"  id="clock_missed_details">
                            <input type="radio" name="clock" class="clock_click" id="clock_missed" value="clockmissed">CLOCK MISSED DETAILS</label>
                    </div></div>
                <div class="row-fluid form-group" style="padding-top: 15px">
                    <label name="REP_report_entry" id="REP_lbl_report_entry" class="srctitle col-sm-12"></label>
                </div>
                <div id="clockinout" hidden>
                    <div class="form-group" >
                        <label name="REP_lbl_strtdtebyrange" class="col-sm-2" id="REP_lbl_strtdtebyrange" >START DATE<em>*</em></label>
                        <div class="col-sm-offset-1"style="padding-left: 15px" >
                            <input type="text" id="REP_tb_strtdtebyrange" name="REP_tb_strtdtebyrange"  class='valid REP_datepicker datemandtry' style="width:75px;"/>
                        </div>
                    </div>
                    <div class="form-group" style="padding-right: 20px">
                        <label name="REP_lbl_enddte" class="col-sm-2" id="REP_lbl_enddte" >END DATE<em>*</em></label>
                        <div class="col-sm-offset-1"style="padding-left: 15px">
                            <input type="text" id="REP_tb_enddtebyrange" name="REP_tb_enddtebyrange"  class='valid REP_datepicker datemandtry' style="width:75px;"/>
                        </div>
                    </div>
                    <div class="form-group" style="padding-left:10px">
                        <input type="button" id="REP_btn_searchdaterange" name="REP_btn_searchdaterange"  value="SEARCH" class="btn"  disabled />
                    </div>
                    <div class="form-group" style="padding-left:15px">
                        <label id="REP_lbl_daterange" name="REP_lbl_daterange"  class="srctitle" hidden></label>
                    </div>
                    <div style="padding-left:15px">
                        <input type="button" id="REP_btn_pdf" class="btnpdf" value="PDF">
                    </div>
                    <div id="REP_tablecontainer_bydaterange" style="width:auto; padding-left:15px" class="table-responsive" hidden>
                        <section style="width:1000px;">
                        </section>
                    </div>
                </div>
                <div id="clockmissed" hidden>
                    <div class="form-group"><label id="CLK_nodata_rc" name="CLK_nodata_rc" class="errormsg"></label></div>
                    <div class="row-fluid">
                        <label name="CLK_lbl_selectmnths" class="col-sm-2" id="CLK_lbl_selectmnths">SELECT MONTH<em>*</em></label>
                        <div class="col-sm-offset-1" style="padding-left:15px" >
                            <input type="text" name="CLK_db_selectmnths" id="CLK_db_selectmnths" class="date-pickers datemandtry valid" style="width:110px;"><br>
                        </div>
                    </div>
                    <div class="form-group" style="padding-left:15px ">
                        <input type="button" class="btn" name="CLK_btn_search" id="CLK_btn_search"  value="SEARCH" disabled>
                    </div>

                    <div class="form-group" style="padding-left:15px ">
                        <label id="no_of_days" class="srctitle"></label>
                    </div>
                    <div class="form-group" style="padding-left:15px ">
                        <label id="src_lbl_error_login" class="srctitle"></label>
                    </div>

                    <div class="form-group" style="padding-left:15px "><input type="button" id="CLK_btn_emp_pdf" class="btnpdf" value="PDF"></div>

                    <div class="form-group" style="padding-left:15px "><label id="CLK_nodata_pdflextbles" name="CLK_nodatas_pdflextble" class="errormsg" hidden></label></div>

                    <div class="table-responsive" id ="CLK_div_actvenon_dterange" style="width:auto;padding-left:15px" hidden>
                        <section style="width:400px;">
                        </section>
                    </div>
                </div>
            </fieldset>
        </div>
    </form>
</div>
</body>
</html>