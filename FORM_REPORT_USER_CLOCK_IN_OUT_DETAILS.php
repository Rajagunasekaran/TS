<?php
include "HEADER.php";
//include "NEW_MENU.php";
?>
<html>
<head>
<!--SCRIPT TAG START-->
<script>
    //READY FUNCTION START
    $(document).ready(function(){
        $(".preloader").hide();
        $(document).on('click','#clock_in_out',function(){
            $('#REP_lbl_report_entry').html('CLOCK IN/OUT DETAILS');
//            $('#inout').html('CLOCK IN/OUT DETAILS');
            $('#clockinout').show();
            $('#clockmissed').hide();
            $('#no_of_days').hide();
            $('#src_lbl_error_login').hide();
            $('#CLK_btn_emp_pdf').hide();
            $('#CLK_nodata_pdflextbles').hide();
            $('#CLK_div_actvenon_dterange').hide();
            $('#CLK_db_selectmnths').val('');

            $('#REP_btn_pdf').hide();
        //DATE PICKER FUNCTION
        $('.REP_datepicker').datepicker(
            {
                dateFormat: 'dd-mm-yy',
                changeYear: true,
                changeMonth: true
            });
var REP_chk_errorAarray=[];
        //GEETING INITIAL DATA FROM DB
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var values_array=JSON.parse(xmlhttp.responseText);
                REP_chk_errorAarray=values_array[3];
//                alert(REP_chk_errorAarray);
                    $('#REP_nodata_rc').text(REP_chk_errorAarray[3]).show();
                    $('#REP_chk_lbl_srchby').hide();
            }
        }
        var option="common";
        xmlhttp.open("GET","DB_DAILY_REPORTS_USER_CLOCK_IN_OUT_DETAILS.do?option="+option);
        xmlhttp.send();

        // CAL FUNCTION FOR START AND END DATE.
        date();

        var daterange_val=[];
        var uld_id;
        var uld_name;
        //FUNCTION FOR SETTINF MIN ND MAX DATE
        function date()
        {
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader', window.parent.document).hide();
                $(".preloader").hide();
//                alert(xmlhttp.responseText);
                daterange_val=JSON.parse(xmlhttp.responseText);
                var REV_start_dates=daterange_val[0];
                var REV_end_dates=daterange_val[1];
                uld_id=daterange_val[2];
                uld_name=daterange_val[3];
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
        xmlhttp.open("GET","DB_DAILY_REPORTS_USER_CLOCK_IN_OUT_DETAILS.do?option="+choice);
        xmlhttp.send();
        }
        //SET END DATE
        $(document).on('change','#REP_tb_strtdtebyrange',function(){
            $('#REV_lbl_emptitle').hide();
            var USRC_UPD_startdate = $('#REP_tb_strtdtebyrange').datepicker('getDate');
            var date = new Date( Date.parse( USRC_UPD_startdate ));
            date.setDate( date.getDate()  );
            var USRC_UPD_todate = date.toDateString();
            USRC_UPD_todate = new Date( Date.parse( USRC_UPD_todate ));
            $('#REP_tb_enddtebyrange').datepicker("option","minDate",USRC_UPD_todate);
        });

        // CHANGE EVENT FOR STARTDATE AND ENDDATE
        $(document).on('change','.valid',function(){
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
//            $('.preloader', window.parent.document).show();
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
//                    $('.preloader', window.parent.document).hide();
                    $(".preloader").hide();
//                    alert(xmlhttp.responseText);
                    allvalues_array=JSON.parse(xmlhttp.responseText);
                    if(allvalues_array.length!=0){
                        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                        $('#REP_btn_pdf').show();
                        var sd=REP_chk_errorAarray[4].toString().replace("[LOGINID]",uld_name);
                        var errmsg=sd.toString().replace("[STARTDATE]",startdate);
                        REP_strtend_errmsgs=errmsg.toString().replace("[ENDDATE]",enddate);
                        $('#REP_lbl_daterange').text(REP_strtend_errmsgs).show();
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
//                      alert(ADM_tableheader)
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
            xmlhttp.open("GET","DB_DAILY_REPORTS_USER_CLOCK_IN_OUT_DETAILS.do?option="+option+"&startdate="+startdate+"&enddate="+enddate);
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
            var url=document.location.href='COMMON_PDF.do?flag=24&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&inputValThree='+inputValThree+'&title='+REP_strtend_errmsgs;
        });
        });
        $(document).on('click','#clock_missed',function(){
            $('#REP_lbl_report_entry').html('CLOCK OUT MISSED DETAILS');
//            $('#inout').html('CLOCK OUT MISSED DETAILS');
            $('#clockinout').hide();
            $('#clockmissed').show();
            $('#REP_tb_strtdtebyrange').val('');
            $('#REP_tb_enddtebyrange').val('');
            $('#REP_lbl_daterange').hide();
            $('#REP_btn_pdf').hide();
            $('#REP_tablecontainer_bydaterange').hide();

            var uld_id;
            var uld_name;
            var CLK_errorAarray;
            var errmsg;
            var pdferrmsg;
            var msg;
            $(".ui-datepicker-calendar").hide();
            $('#CLK_btn_emp_pdf').hide();
            err();
//        $('.preloader', window.parent.document).show();
            $(".preloader").show();
            function err(){
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader', window.parent.document).hide()
                        $(".preloader").hide();
//                alert(xmlhttp.responseText);
                        var values_array=JSON.parse(xmlhttp.responseText);
                        CLK_errorAarray=values_array;
                    }
                    else
                    {
                        $('#CLK_nodata_rc').text(CLK_errorAarray[2]).show();
                    }
                }
                var option="common";
                xmlhttp.open("GET","DB_DAILY_REPORTS_USER_CLOCK_OUT_MISSED_DETAILS.do?option="+option);
                xmlhttp.send();
            }
            var CLK_start_dates='';
            var CLK_end_dates='';
//        alert('aaaa');
//        $('.preloader', window.parent.document).show();

            //DATE PICKER FUNCTION
            $('.date-pickers').datepicker( {
                changeMonth: true,      //provide option to select Month
                changeYear: true,       //provide option to select year
                showButtonPanel: true,   // button panel having today and done button
                dateFormat: 'MM-yy',    //set date format
                //ONCLOSE FUNCTION
                onClose: function(dateText, inst) {
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).datepicker('setDate', new Date(year, month, 1));//here set the date when closing.
                    $(this).blur();//remove focus input box
                    $("#CLK_btn_search").attr("disabled");
                    validationdp()
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


            //FUNCTION FOR SETTINF MIN ND MAX DATE
//        alert('first function');
            $(".preloader").show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                alert('success')
//                $('.preloader', wixmlhttp.responseTextndow.parent.document).hide();
                    $(".preloader").hide();
//                alert(xmlhttp.responseText);
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
            xmlhttp.open("GET","DB_DAILY_REPORTS_USER_CLOCK_OUT_MISSED_DETAILS.do?option="+choice);
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
                }
                if(($('#CLK_db_selectmnths').val()!='undefined')&&($('#CLK_db_selectmnths').val()!='')&&($('#CLK_lb_loginid').val()!="SELECT"))
                {
                    $("#CLK_btn_search").removeAttr("disabled");
                }
                else
                {
                    $("#CLK_btn_search").attr("disabled","disabled");
                }
            }


//    }

            // CLICK EVENT FOR LOGIN ID SEARCH BTN
            var CLK_actnon_values=[];
            var errmsg;
            $(document).on('click','#CLK_btn_search',function(){
                $('#CLK_nodata_pdflextbles').hide();
                $('#CLK_div_actvenon_dterange').hide();
                $('#CLK_tble_lgn').html('');
                $('#CLK_btn_search').attr("disabled","disabled");
                var CLK_monthyear=$('#CLK_db_selectmnths').val();
//        alert(CLK_monthyear);
                var CLK_reportdte;
                var CLK_loginid=$('#CLK_lb_loginid').val();
//                $('.preloader', window.parent.document).show();
                $(".preloader").show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                        $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
//                alert(xmlhttp.responseText);
                        var CLK_actnon_values=JSON.parse(xmlhttp.responseText);

                        if(CLK_actnon_values[0].length!=0)
                        {
                            var CLK_reportdate= CLK_actnon_values[0];
                            var total= CLK_actnon_values[1];
                            for(var i=0;i<total.length;i++){
                                CLK_reportdte=total[i].count;
                            }
//                    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
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

//                    alert(CLK_table_header)
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
                xmlhttp.open("GET","DB_DAILY_REPORTS_USER_CLOCK_OUT_MISSED_DETAILS.do?option="+option+"&CLK_monthyear="+CLK_monthyear);
                xmlhttp.send();
            });
            //CLICK EVENT FOR PDF BUTTON
            $(document).on('click','#CLK_btn_mnth_pdf',function(){
                var inputValOne=$('#CLK_db_selectmnth').val();
                var url=document.location.href='COMMON_PDF.do?flag=26&inputValOne='+inputValOne+'&title='+msg;
            });
            $(document).on('click','#CLK_btn_emp_pdf',function(){
                var inputValOne=$("#CLK_db_selectmnths").val();
                var inputValThree =uld_id;
//        alert(errmsg)
                var url=document.location.href='COMMON_PDF.do?flag=27&inputValOne='+inputValOne+'&inputValThree='+inputValThree+'&title='+errmsg;
            });
        });

        });
</script>
</head>
<body>
<div class="container">
    <div  class="preloader MaskPanel"><div class="statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title"><center ><b><h3>CLOCK IN/OUT DETAILS</h3></b></center></div>
    <form  id="REP_chk_form_details" class="content" >
        <div class="panel-body">
            <fieldset>
        <div class="row-fluid form-group ">
            <label name="clock_in_entry" class="col-sm-12"  id="clock_in_entry">
                <div class="radio">
                <input type="radio" name="clock" class="rdclock" id="clock_in_out" value=clockinout>CLOCK IN-OUT DETAILS</label>

        </div></div>
        <div class="row-fluid  form-group">
            <label name="clock_missed_details" class="col-sm-12"  id="clock_missed_details">
                <div class="radio">
                <input type="radio" name="clock" class="rdclock" id="clock_missed" value="clockmissed">CLOCK-OUT-MISSED DETAILS</label>
</div>
        </div>
    <div class="row-fluid form-group">
        <label name="REP_report_entry" id="REP_lbl_report_entry" class="srctitle col-sm-12"></label>
    </div>
        <div id="clockinout" hidden>
       <div class="form-group">
                <label name="REP_lbl_strtdtebyrange" class="col-sm-2" id="REP_lbl_strtdtebyrange" >START DATE<em>*</em></label>
           <div class="col-sm-offset-1">
                <input type="text" id="REP_tb_strtdtebyrange" name="REP_tb_strtdtebyrange"  class='valid REP_datepicker datemandtry' style="width:75px;"/>
           </div>
       </div>
        <div class="form-group">
               <label name="REP_lbl_enddte" class="col-sm-2" id="REP_lbl_enddte" >END DATE<em>*</em></label>
            <div class="col-sm-offset-1">
               <input type="text" id="REP_tb_enddtebyrange" name="REP_tb_enddtebyrange"  class='valid REP_datepicker datemandtry' style="width:75px;"/>
            </div>
        </div>
        <div class="form-group">
              <input type="button" id="REP_btn_searchdaterange" name="REP_btn_searchdaterange"  value="SEARCH" class="btn"  disabled />
        </div>
        <div class="form-group">
        <label id="REP_lbl_daterange" name="REP_lbl_daterange"  class="srctitle" hidden></label>
        </div>
        <input type="button" id="REP_btn_pdf" class="btnpdf" value="PDF">

        <div id="REP_tablecontainer_bydaterange" style="width:auto" class="table-responsive" hidden>
            <section style="width:1000px;">
            </section>
        </div>
            </div>
<div id="clockmissed" hidden>
    <div class="form-group "><label id="CLK_nodata_rc" name="CLK_nodata_rc" class="errormsg"></label></div>
    <div class="form-group" >
        <label name="CLK_lbl_selectmnths" class="col-sm-2" id="CLK_lbl_selectmnths">SELECT MONTH<em>*</em></label>
        <div class="col-sm-offset-1">
            <input type="text" name="CLK_db_selectmnths" id="CLK_db_selectmnths" class="date-pickers datemandtry valid" style="width:110px;"><br>
        </div>
    </div>

    <div class="form-group">
        <td><input type="button" class="btn" name="CLK_btn_search" id="CLK_btn_search"  value="SEARCH" disabled></td>
    </div>

    <div class="form-group">
        <label id="no_of_days" class="srctitle"></label>
    </div>
    <div class="form-group">
        <label id="src_lbl_error_login" class="srctitle"></label>
    </div>

    <div class="form-group"><input type="button" id="CLK_btn_emp_pdf" class="btnpdf" value="PDF"></div>

    <div class="form-group"><label id="CLK_nodata_pdflextbles" name="CLK_nodatas_pdflextble" class="errormsg" hidden></label></div>

    <div class="table-responsive" id ="CLK_div_actvenon_dterange" style="width:auto" hidden>
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