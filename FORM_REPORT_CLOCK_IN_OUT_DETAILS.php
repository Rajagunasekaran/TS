<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************CLOCK IN/OUT DETAILS*********************************************//
//DONE BY:LALITHA
//VER 0.02-SD:09/01/2015 ED:10/01/2015,TRACKER NO:74,Updated Sorting function
//VER 0.01-INITIAL VERSION, SD:03/01/2015 ED:05/01/2015,TRACKER NO:74
//*********************************************************************************************************//
<?php
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
//GLOBAL DECLARATION
var REP_chk_errorAarray=[];
var REP_chk_active_emp=[];
var REP_chk_nonactive_emp=[];
var REP_chk_config=[];
//READY FUNCTION START
$(document).ready(function(){
    $('#REP_chk_lbl_srchby').hide();
    var REP_strtend_errmsgs;
    var REP_allactve_msg;
    $('#REP_chk_btn_search').hide();
    $('#REP_btn_searchdaterange').hide();
    $('#REP_btn_pdf').hide();
    $('#REP_btn_pdfs').hide();
    //GEETING INITIAL DATA FROM DB
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var values_array=JSON.parse(xmlhttp.responseText);
            REP_chk_active_emp=values_array[0];
            REP_chk_nonactive_emp=values_array[1];
            REP_chk_config=values_array[2];
            REP_chk_errorAarray=values_array[3];
            if(REP_chk_config.length!=0){
                var project_list='<option>SELECT</option>';
                for (var i=0;i<REP_chk_config.length;i++) {
                    project_list += '<option value="' + REP_chk_config[i][1] + '">' + REP_chk_config[i][0] + '</option>';
                }
                $('#REP_chk_lb_srchby').html(project_list);
                $('#REP_chk_lb_srchby').show();
                $('#REP_chk_lbl_srchby').show();
            }
            else
            {
                $('#REP_nodata_rc').text(REP_chk_errorAarray[3]).show();
                $('#REP_chk_lbl_srchby').hide();
            }
        }
    }
    var option="common";
    xmlhttp.open("GET","DB_REPORT_CLOCK_IN_OUT_DETAILS.do?option="+option);
    xmlhttp.send();
    //FUNCTION FOR FORMTABLEDATEFORMAT
    function FormTableDateFormat(inputdate){
        var string = inputdate.split("-");
        return string[2]+'-'+ string[1]+'-'+string[0];
    }
    // CHANGE EVENT FOR SEARCH BY LIST BX
    $(document).on('change','#REP_chk_lb_srchby',function(){
        $('#REP_tble_searchbtn').hide();
        $('#REP_chk_lbl_btwnrange').hide();
        $('#REP_chk_rd_allactveemp').hide();
        $('#REP_chk_lbl_allactveemp').hide();
        $('#REP_chk_lbl_btwnranges').hide();
        $('#REP_chk_rd_btwnrange').hide();
        $('#REP_chk_rd_btwnrange').hide();
        $('#REP_chk_lbl_btwnrange').hide();
        $('#REP_chk_rd_allactveemp').hide();
        $('#REP_chk_lbl_allactveemp').hide();
        $('#REP_chk_tble_actnonact').hide();
        $('#REP_chk_rd_actveemp').hide();
        $('#REP_chk_lbl_actveemp').hide();
        $('#REP_chk_rd_nonemp').hide();
        $('#REP_chk_lbl_nonactveemp').hide();
        $('#REV_lbl_actveemps').hide();
        $('#REV_lbl_nonactveemps').hide();
        $('#REP_chk_lbl_strtdte').hide();
        $('#REP_chk_tb_strtdte').hide();
        $('#REP_chk_lbl_enddte').hide();
        $('#REP_chk_tb_enddte').hide();
        $('#REP_chk_btn_search').hide();
        $('#REP_lb_loginid').hide();
        $('#REP_chk_db_selectmnths').hide();
        $('#REP_chk_lbl_selectmnths').hide();
        $('#REP_lbl_loginid').hide();
        $('#REP_chk_rd_actveemp').hide();
        $('#REP_chk_rd_actveemp').attr("checked",false);
        $('#REP_chk_rd_nonemp').attr("checked",false);
        $('#REP_chk_rd_btwnrange').attr("checked",false);
        $('#REP_chk_rd_allactveemp').attr("checked",false);
        $('#REP_chk_lbl_actveemp').hide();
        $('#REP_chk_rd_nonemp').hide();
        $('#REP_chk_lbl_nonactveemp').hide();
        $('#REP_chk_tble_actnonact').hide();
        $('#REP_lbl_strtdtebyrange').hide();
        $('#REP_tb_strtdtebyrange').hide();
        $('#REP_lbl_enddte').hide();
        $('#REP_tb_enddtebyrange').hide();
        $('#REP_btn_searchdaterange').hide();
        $('#REP_tble_absent_count').hide();
        $('#REP_tablecontainer_bydaterange').hide();
        $('#REP_nodata_btwrange').hide();
        $('#REP_lbl_daterange').hide();
        $('#REP_lbl_nodata_allactive').hide();
        $('#REP_lbl_dteranges').hide();
        $('#REP_btn_pdf').hide();
        $('#REP_btn_pdfs').hide();
        $('section').html('');
        $('sectionid').html('');
        $('#REP_lbl_date').hide();
        $('#REP_tb_date').hide();
        $('#REP_btn_date').hide();
        $('#REP_chk_lbl_allactveemps').hide();
        var formElement = document.getElementById("REP_chk_form_details");
        var option=$('#REP_chk_lb_srchby').val();
        if(option=='13')
        {
            $('#REP_chk_rd_btwnrange').show();
            $('#REP_chk_lbl_btwnrange').show();
            $('#REP_chk_rd_allactveemp').show();
            $('#REP_chk_lbl_allactveemp').show();
        }
    });
    //VALIDATION SEARCH BTN FOR PROJECT NAME BY DATE RANGE
    $(document).on('change','#REP_chk_rd_allactveemp',function(){
        $('#REP_chk_rd_actveemp').hide();
        $('#REP_chk_lbl_actveemp').hide();
        $('#REP_chk_rd_nonemp').hide();
        $('#REP_chk_lbl_nonactveemp').hide();
        $('#REV_lbl_actveemps').hide();
        $('#REV_lbl_nonactveemps').hide();
        $('#REP_chk_lbl_btwnranges').hide();
        $('#REP_lb_loginid').hide();
        $('#REP_lbl_loginid').hide();
        $('#REP_lbl_strtdtebyrange').hide();
        $('#REP_tb_strtdtebyrange').hide();
        $('#REP_lbl_enddte').hide();
        $('#REP_tb_enddtebyrange').hide();
        $('#REP_btn_searchdaterange').hide();
        $('#REP_nodata_btwrange').hide();
        $('#REP_lbl_daterange').hide();
        $('#REP_lbl_nodata_allactive').hide();
        $('#REP_lbl_dteranges').hide();
        $('#REP_btn_pdf').hide();
        $('#REP_btn_pdfs').hide();
        $('#REP_chk_lbl_allactveemps').show();
        $('#REP_tble_absent_count').hide();
        $('section').html('');
        $('<tr><td width="150"><label name="REP_lbl_date" id="REP_lbl_date" >DATE<em>*</em></label></td><td><input type="text" name="REP_tb_date" id="REP_tb_date" class="enable clear REP_date_picker datemandtry" style="width:75px;" ></td></tr>').appendTo('#REP_tble_date')
        $('<tr><td><input type="button" class="btn" name="REP_btn_date" id="REP_btn_date"  value="SEARCH" disabled></td></tr>').appendTo('#REP_tble_searchbutton')
        //DATE PICKER FUNCTION
        $('.REP_date_picker').datepicker(
            {
                dateFormat: 'dd-mm-yy',
                changeYear: true,
                changeMonth: true
            });
        //END DATE PICKER FUNCTION
        var daterange_val=[];
        var formElement = document.getElementById("REP_chk_form_details");
        var REV_project_name=$("#REV_lb_projectname").val();
        //FUNCTION FOR SETTINF MIN ND MAX DATE
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.maskpanel',window.parent.document).removeAttr('style').hide();
                $('.preloader').hide();
                daterange_val=JSON.parse(xmlhttp.responseText);
                var REV_start_dates=daterange_val[0];
                var REV_end_dates=daterange_val[1];
                $('#REP_tb_date').datepicker("option","minDate",new Date(REV_start_dates));
                $('#REP_tb_date').datepicker("option","maxDate",new Date(REV_end_dates));
            }
        }
        var choice="datemin_max";
        xmlhttp.open("GET","DB_REPORT_CLOCK_IN_OUT_DETAILS.do?REV_project_name="+REV_project_name+"&option="+choice,true);
        xmlhttp.send(new FormData(formElement));
    });
    //VALIDATION FOR START ND END DATE
    $(document).on('change','.enable',function(){
        $('#REP_tablecontainer').hide();
        $('#REP_nodata_btwrange').hide();
        $('#REP_lbl_daterange').hide();
        $('#REP_lbl_nodata_allactive').hide();
        $('#REP_lbl_dteranges').hide();
        $('#REP_btn_pdf').hide();
        $('#REP_btn_pdfs').hide();
        $('#sectionid').html('');
        if($("#REP_tb_date").val()=='')
        {
            $("#REP_btn_date").attr("disabled", "disabled");
        }
        else
        {
            $("#REP_btn_date").removeAttr("disabled");
            $("#REP_btn_date").show();
        }
    });
    //CHANGE FUNCTION FOR BETWEEN  RANGE
    $(document).on('change','#REP_chk_rd_btwnrange',function(){
        $('#REP_nodata_btwrange').hide();
        $('#REP_lbl_daterange').hide();
        $('#REP_lbl_nodata_allactive').hide();
        $('#REP_lbl_dteranges').hide();
        $('#REP_btn_pdf').hide();
        $('#REP_btn_pdfs').hide();
        $('#REP_chk_lbl_allactveemps').hide();
        $('#REP_lbl_strtdtebyrange').hide();
        $('#REP_tb_strtdtebyrange').hide();
        $('#REP_lbl_enddte').hide();
        $('#REP_tb_enddtebyrange').hide();
        $('#REP_btn_searchdaterange').hide();
        $('#REP_chk_rd_actveemp').attr("checked",false);
        $('#REP_chk_rd_nonemp').attr("checked",false);
        $('#REP_chk_tble_actnonact').show();
        $('#REP_chk_rd_actveemp').show();
        $('#REP_chk_lbl_actveemp').show();
        $('#REP_chk_rd_nonemp').show();
        $('#REP_chk_lbl_nonactveemp').show();
        $('#REP_chk_lbl_btwnranges').show();
        $('#REP_tble_searchbtn').html('');
        $('#REP_tble_date').html('');
        $('#REP_tble_searchbutton').html('');
        $('#REP_tablecontainer').hide();
        $('#sectionid').html('');
    });
    // CLICK EVENT FOR ACTIVE RADIO BTN
    $(document).on('click','#REP_chk_rd_actveemp',function(){
        $('#REV_lbl_actveemps').show();
        $('#REP_nodata_btwrange').hide();
        $('#REP_lbl_daterange').hide();
        $('#REP_lbl_nodata_allactive').hide();
        $('#REP_lbl_dteranges').hide();
        $('#REP_btn_pdf').hide();
        $('#REP_btn_pdfs').hide();
        $('#REV_lbl_nonactveemps').hide();
        $('#REP_chk_db_selectmnths').hide();
        $('#REP_chk_lbl_selectmnths').hide();
        $('#REP_lbl_strtdtebyrange').hide();
        $('#REP_tb_strtdtebyrange').hide();
        $('#REP_lbl_enddte').hide();
        $('#REP_tb_enddtebyrange').hide();
        $('#REP_btn_searchdaterange').hide();
        $('#REP_chk_btn_search').hide();
        $('#REP_tble_absent_count').hide();
        $('section').html('');
        if(REP_chk_active_emp.length!=0)
        {
            var active_employee='<option>SELECT</option>';
            for (var i=0;i<REP_chk_active_emp.length;i++) {
                active_employee += '<option value="' + REP_chk_active_emp[i][1] + '">' + REP_chk_active_emp[i][0] + '</option>';
            }
            $('#REP_lb_loginid').html(active_employee);
            $('#REP_lbl_loginid').show();
            $('#REP_lb_loginid').show();
        }
        else
        {
            $('#REP_nodata_uld').text(REP_chk_errorAarray[0]).show();
            $('#REV_lbl_actveemps').hide();
        }
    });
    // CLICK EVENT FOR NON ACTIVE RADIO BTN
    $(document).on('click','#REP_chk_rd_nonemp',function(){
        $('#REP_nodata_btwrange').hide();
        $('#REP_lbl_daterange').hide();
        $('#REP_lbl_nodata_allactive').hide();
        $('#REP_lbl_dteranges').hide();
        $('#REP_btn_pdf').hide();
        $('#REP_btn_pdfs').hide();
        $('#REP_lbl_strtdtebyrange').hide();
        $('#REP_tb_strtdtebyrange').hide();
        $('#REP_lbl_enddte').hide();
        $('#REP_tb_enddtebyrange').hide();
        $('#REP_btn_searchdaterange').hide();
        $('#REV_lbl_actveemps').hide();
        $('#REP_chk_db_selectmnths').hide();
        $('#REP_chk_lbl_selectmnths').hide();
        $('#REP_chk_btn_search').hide();
        $('#REP_tble_absent_count').hide();
        $('section').html('');
        $('#REV_lbl_nonactveemps').show();
        if(REP_chk_nonactive_emp.length!=0)
        {
            var active_employee='<option>SELECT</option>';
            for (var i=0;i<REP_chk_nonactive_emp.length;i++) {
                active_employee += '<option value="' + REP_chk_nonactive_emp[i][1] + '">' + REP_chk_nonactive_emp[i][0] + '</option>';
            }
            $('#REP_lb_loginid').html(active_employee);
            $('#REP_lbl_loginid').show();
            $('#REP_lb_loginid').show();
        }
        else
        {
            $('#REP_nodata_uld').text(REP_chk_errorAarray[0]).show();
            $('#REV_lbl_nonactveemps').hide();
        }
    });
    // CHANGE EVENT FOR LOGIN ID LIST BX
    $(document).on('change','#REP_lb_loginid',function(){
        $('#REP_tble_absent_count').hide();
        $('#REP_nodata_btwrange').hide();
        $('#REP_lbl_daterange').hide();
        $('#REP_lbl_nodata_allactive').hide();
        $('#REP_lbl_dteranges').hide();
        $('#REP_btn_pdf').hide();
        $('#REP_btn_pdfs').hide();
        $('#REP_tb_strtdtebyrange').val('');
        $('#REP_tb_enddtebyrange').val('');
        $('section').html('');
        var formElement = document.getElementById("REP_chk_form_details");
        var date_val=[];
        $('#REP_chk_db_selectmnths').val('');
        $('#REP_btn_searchdaterange').attr("disabled","disabled");
        $('#REP_chk_lbl_selectmnths').hide();
        $('#REP_chk_db_selectmnths').hide();
        $('#REV_nodata_pdflextbles').hide();
        var REP_chk_loginid=$('#REP_lb_loginid').val();
        if($('#REP_BND_lb_loginid').val()=="SELECT")
        {
            $('#REP_chk_btn_search').hide();
            $('#REP_chk_btn_search').attr("disabled","disabled");
            $('#REP_chk_lbl_selectmnths').hide();
            $('#REP_chk_db_selectmnths').hide();
        }
        else
        {
            $('.preloader', window.parent.document).show();
            //DATE PICKER FUNCTION
            $('.REP_datepicker').datepicker(
                {
                    dateFormat: 'dd-mm-yy',
                    changeYear: true,
                    changeMonth: true
                });
            //END DATE PICKER FUNCTION
            var daterange_val=[];
            var formElement = document.getElementById("REP_chk_form_details");
            var REV_project_name=$("#REV_lb_projectname").val();
            //FUNCTION FOR SETTINF MIN ND MAX DATE
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
                    daterange_val=JSON.parse(xmlhttp.responseText);
                    var REV_start_dates=daterange_val[0];
                    var REV_end_dates=daterange_val[1];
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
            xmlhttp.open("GET","DB_REPORT_CLOCK_IN_OUT_DETAILS.do?REP_chk_loginid="+REP_chk_loginid+"&option="+choice,true);
            xmlhttp.send(new FormData(formElement));

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
        }
    });
    // CHANGE EVENT FOR STARTDATE AND ENDDATE
    $(document).on('change','.valid',function(){
        $('#REP_tble_absent_count').hide();
        $('#REP_nodata_btwrange').hide();
        $('#REP_lbl_daterange').hide();
        $('#REP_lbl_nodata_allactive').hide();
        $('#REP_lbl_dteranges').hide();
        $('#REP_btn_pdf').hide();
        $('#REP_btn_pdfs').hide();
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
    var allvalues_array;
    //CLICK FUNCTION FOR ALL ACTIVE DATE BX
    $(document).on('click','#REP_btn_date',function(){
        $('.preloader', window.parent.document).show();
        $("#REP_btn_date").attr("disabled","disabled");
        $('#REP_tablecontainer').hide();
        $('#REP_tble_allactive_count').hide();
        var option=$('#REP_chk_lb_srchby').val();
        var date=$('#REP_tb_date').val();
        var loginid=$('#REP_lb_loginid').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                allvalues_array=JSON.parse(xmlhttp.responseText);
                if(allvalues_array.length!=0){
                    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                    REP_allactve_msg=REP_chk_errorAarray[5].toString().replace("[DATE]",date);
                    $('#REP_lbl_dteranges').text(REP_allactve_msg).show();
                    $("#REP_btn_pdfs").show();
                    var ADM_tableheader='<table id="REP_tble_allactive_count" border="1"  cellspacing="0" class="srcresult" style="width:1000px" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>EMPLOYEE NAME</th><th>CLOCK IN</th><th style="width:250px">CLOCK IN LOCATION</th><th>CLOCK OUT</th><th  style="width:250px">CLOCK OUT LOCATION</th></tr></thead><tbody>'
                    for(var j=0;j<allvalues_array.length;j++){
                        var check_in_empname=allvalues_array[j].check_in_empname;
                        if((check_in_empname=='null')||(check_in_empname==undefined))
                        {
                            check_in_empname='';
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
                        ADM_tableheader+='<tr ><td>'+check_in_empname+'</td><td align="center">'+clockin+'</td><td  style="width:250px">'+clockinlocation+'</td><td align="center">'+clockout+'</td><td style="width:250px">'+clockoutlocation+'</td></tr>';
                    }
                    ADM_tableheader+='</tbody></table>';
                    $('sectionid').html(ADM_tableheader);
                    $('#REP_tble_allactive_count').DataTable( {
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers"
                    });
                    $('#REP_tablecontainer').show();
                }
                else
                {
                    $('#REP_tble_absent_count').hide();
                    var msg=REP_chk_errorAarray[2].toString().replace("[DATE]",date);
                    $('#REP_lbl_nodata_allactive').text(msg).show();
                    $('#REP_tablecontainer').hide();
                    $('#REP_lbl_dteranges').hide();
                    $('#REP_btn_pdf').hide();
                    $('#REP_btn_pdfs').hide();
                    $('#REP_tble_allactive_count').hide();
                }
            }
        }
        var option="ALL_ACTIVE_RANGE";
        xmlhttp.open("GET","DB_REPORT_CLOCK_IN_OUT_DETAILS.do?option="+option+"&date="+date+"&loginid="+loginid);
        xmlhttp.send();
    });
    var allvalues_array;
    //CLICK  FUNCTION FOR DATE BX
    $(document).on('click','#REP_btn_searchdaterange',function(){
        $('.preloader', window.parent.document).show();
        $("#REP_btn_searchdaterange").attr("disabled","disabled");
        $('#REP_tble_absent_count').html('');
        $('section').html('');
        var option=$('#REP_chk_lb_srchby').val();
        var loginid=$('#REP_lb_loginid').val();
        var loginid_val=$("#REP_lb_loginid option:selected").text();
        var startdate=$('#REP_tb_strtdtebyrange').val();
        var enddate=$('#REP_tb_enddtebyrange').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                allvalues_array=JSON.parse(xmlhttp.responseText);
                if(allvalues_array.length!=0){
                    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                    $('#REP_btn_pdf').show();
                    var sd=REP_chk_errorAarray[4].toString().replace("[LOGINID]",loginid_val);
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
                    $('#REP_btn_pdfs').hide();
                }
            }
        }
        $('#REP_tablecontainer_bydaterange').show();
        var option="BETWEEN_RANGE";
        xmlhttp.open("GET","DB_REPORT_CLOCK_IN_OUT_DETAILS.do?option="+option+"&startdate="+startdate+"&enddate="+enddate+"&loginid="+loginid);
        xmlhttp.send();
        sorting();
    });
    //CLICK FUNCTION FOR PDF BUTTON
    $(document).on('click','#REP_btn_pdf',function(){
        var inputValOne=$('#REP_tb_strtdtebyrange').val();
        inputValOne = inputValOne.split("-").reverse().join("-");
        var inputValTwo=$('#REP_tb_enddtebyrange').val();
        inputValTwo = inputValTwo.split("-").reverse().join("-");
        var inputValThree=$('#REP_lb_loginid').val();
        var url=document.location.href='COMMON_PDF.do?flag=24&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&inputValThree='+inputValThree+'&title='+REP_strtend_errmsgs;
    });
    //CLICK FUNCTION FOR PDF BUTTON
    $(document).on('click','#REP_btn_pdfs',function(){
        var inputValFour=$('#REP_tb_date').val();
        inputValFour = inputValFour.split("-").reverse().join("-");
        var url=document.location.href='COMMON_PDF.do?flag=23&inputValFour='+inputValFour+'&title='+REP_allactve_msg;
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
});
<!--SCRIPT TAG END-->
</script>
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title"><div style="padding-left:500px; text-align:left;" ><p><h3>CLOCK IN/OUT DETAILS</h3><p></div></div>
    <form   id="REP_chk_form_details" class="content" >
        <table>
            <table>
                <tr>
                    <td width="150"><label name="REP_chk_lbl_srchby" id="REP_chk_lbl_srchby">SEARCH BY<em>*</em></label></td>
                    <td width="150">
                        <select id="REP_chk_lb_srchby" name="REP_chk_lb_srchby" hidden>
                        </select>
                    </td>
                </tr>
            </table>
            <tr><td><label id="REP_nodata_rc" name="REP_nodata_rc" class="errormsg"></label></td></tr>
            <table id="REP_tble_startdate"></table>
            <table id="REP_tble_searchbtn"></table>
            <table>
                <tr>
                    <td><input type="radio" name="REP_chk_rd_btwnrange" id="REP_chk_rd_btwnrange" value="RANGES" class='attnd' hidden>
                        <label name="REP_chk_lbl_btwnrange" id="REP_chk_lbl_btwnrange" hidden>BETWEEN RANGE</label></td>
                </tr>
                <tr>
                    <td><input type="radio" name="REP_chk_rd_btwnrange" id="REP_chk_rd_allactveemp"   value="RANGES" class='attnd' hidden>
                        <label name="REP_chk_lbl_allactveemp" id="REP_chk_lbl_allactveemp" hidden>ALL ACTIVE EMPLOYEE</label></td>
                </tr>
                <tr>
                    <td><label name="REP_chk_lbl_allactveemps" id="REP_chk_lbl_allactveemps" class="srctitle" hidden>ALL ACTIVE EMPLOYEE</label></td>
                </tr>
                <table id="REP_tble_date"></table>
                <table id="REP_tble_searchbutton"></table>

                <tr>
                    <td><label name="REP_chk_lbl_dte" id="REP_chk_lbl_dte" hidden>DATE</label></td>
                    <td> <input type="text" name="REP_chk_tb_dte" id="REP_chk_tb_dte" class="ASRC_UPD_DEL_date enable"   style="width:75px;"  hidden ></td><br>
                </tr>
                <tr>
                    <td><label name="REP_chk_lbl_btwnranges" id="REP_chk_lbl_btwnranges" class="srctitle" hidden>BETWEEN RANGE</label></td>
                </tr>
                <table>
                    <tr>
                        <td><input type="radio" name="REP_chk_rd_actveemp" id="REP_chk_rd_actveemp" value="EMPLOYEE" hidden >
                            <label name="REP_chk_lbl_actveemp" id="REP_chk_lbl_actveemp"  hidden>ACTIVE EMPLOYEE</label></td>
                    </tr>
                    <tr>
                        <td><input type="radio" name="REP_chk_rd_actveemp" id="REP_chk_rd_nonemp"   value="EMPLOYEE" class='attnd' hidden>
                            <label name="REP_chk_lbl_nonactveemp" id="REP_chk_lbl_nonactveemp"  hidden>NON ACTIVE EMPLOYEE </label></td>
                    </tr>
                    <tr>
                        <td><label name="REV_lbl_actveemps" id="REV_lbl_actveemps" class="srctitle" hidden>ACTIVE EMPLOYEE</label></td>
                    </tr>
                    <tr>
                        <td><label name="REV_lbl_nonactveemps" id="REV_lbl_nonactveemps" class="srctitle" hidden>NON ACTIVE EMPLOYEE </label></td>
                    </tr>
                </table>
            </table>
            <tr>
                <td><table>
                        <tr><td width="150">
                                <label name="REP_lbl_loginid" id="REP_lbl_loginid"  hidden>EMPLOYEE NAME<em>*</em></label></td>
                            <td>
                                <select name="REP_lb_loginid" id="REP_lb_loginid" hidden>
                                </select>
                            </td>
                        </tr></table></td>
            </tr>
            <tr><td><label id="REP_nodata_uld" name="REP_nodata_uld" class="errormsg"></label></td></tr>
            <table>
                <tr>
                    <td width="150"> <label name="REP_lbl_strtdtebyrange" id="REP_lbl_strtdtebyrange" hidden>START DATE<em>*</em></label></td>
                    <td><input type="text" id="REP_tb_strtdtebyrange" name="REP_tb_strtdtebyrange" hidden class='valid REP_datepicker datemandtry' style="width:75px;"/></td>
                </tr>
                <tr>
                    <td width="150"> <label name="REP_lbl_enddte" id="REP_lbl_enddte" hidden>END DATE<em>*</em></label></td>
                    <td><input type="text" id="REP_tb_enddtebyrange" name="REP_tb_enddtebyrange" hidden class='valid REP_datepicker datemandtry' style="width:75px;"/></td>
                </tr>
                <tr>
                    <td><input type="button" id="REP_btn_searchdaterange" name="REP_btn_searchdaterange"  value="SEARCH" class="btn"  disabled /></td>
                </tr>
            </table>
            <tr>
                <td><input type="button" class="btn" name="REP_chk_btn_search" id="REP_chk_btn_search"  value="SEARCH" disabled hidden></td>
            </tr>
            <tr>
                <td><label id="REP_lbl_daterange" name="REP_lbl_daterange"  class="srctitle" hidden></label></td>
            </tr><br>
            <tr>
                <input type="button" id="REP_btn_pdf" class="btnpdf" value="PDF">
            </tr>
            <div class="container" id="REP_tablecontainer_bydaterange" style="width:1000px;" hidden>
                <section style="width:1000px;">
                </section>
            </div>
            <tr><td><label id="REP_lbl_nodata_allactive" name="REP_lbl_nodata_allactive" class="errormsg"></label></td></tr>
            <tr><td><label id="REP_nodata_btwrange" name="REP_nodata_btwrange" class="errormsg"></label></td></tr>
            <tr>
                <td><label id="REP_lbl_dteranges" name="REP_lbl_dteranges"  class="srctitle" hidden></label></td>
            </tr><br>
            <tr>
                <input type="button" id="REP_btn_pdfs" class="btnpdf" value="PDF"></tr>
            <div class="container" id="REP_tablecontainer" style="width:500px;" hidden>
                <sectionid style="width:500px;">
                </sectionid>
            </div>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->