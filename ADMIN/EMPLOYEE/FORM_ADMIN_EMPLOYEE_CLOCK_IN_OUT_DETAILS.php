<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************CLOCK IN/OUT DETAILS*********************************************//
//DONE BY:ARTHI
//VER 0.04-SD:10/07/2015 ED:10/07/2015,fixed the konth nd year date picker issue and pdf issue
//DONE BY:ARTHI
//VER 0.04-SD:03/07/2015 ED:03/07/2015,REDUCE THE SPACE AND DONE RECREATION
//DONE BY:LALITHA
//VER 0.03-SD:26/06/2015 ED:26/01/2015,ISSUE CLEARED FOR FORM LOADING PROPERLY PROBLEM ND MONTH/YEAR DP
//DONE BY:LALITHA
//VER 0.02-SD:09/01/2015 ED:10/01/2015,TRACKER NO:74,Updated Sorting function
//VER 0.01-INITIAL VERSION, SD:03/01/2015 ED:05/01/2015,TRACKER NO:74
//*********************************************************************************************************//
<?php
include "../../TSLIB/TSLIB_HEADER.php";
//include  "NEW_MENU.php";

?>
<!--HIDE THE CALENDER EVENT FOR DATE PICKER-->
<style type="text/css" xmlns="http://www.w3.org/1999/html">
    .calendar-off table.ui-datepicker-calendar {
        display:none !important;
    }
</style>
<!--HTML START TAG-->
<html>
<!--HEAD START TAG-->
<head>
    <!--SCRIPT TAG START-->
    <script>
        //GLOBAL DECLARATION
        var REP_chk_errorAarray=[];
        var REP_chk_active_emp=[];
        var REP_chk_nonactive_emp=[];
        var REP_chk_config=[];
        var CLK_errorAarray=[];
        //READY FUNCTION START
        $(document).ready(function(){
            $(".preloader").hide();
            $("#REP_tb_date").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
            $("#REP_tb_strtdtebyrange").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true,beforeShow:function(input, inst) {
                $(inst.dpDiv).removeClass('calendar-off');
            } });
            $("#REP_tb_enddtebyrange").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
            var REP_strtend_errmsgs;
            var REP_allactve_msg;
            var errmsg;
            var pdferrmsg;
            var msg;
            var CLK_reportdte;
            var CLK_reportconfig_listbx=[];
            var CLK_active_emp=[];
            var CLK_nonactive_emp=[];
            $(document).on('change','.clock_out',function(){
                var radiooption=$(this).val();
                if(radiooption=='clockinout')
                {
                    $('#REP_lbl_report_entry').html('CLOCK IN/OUT DETAILS');
                    $('#clockinout').show();
                    $('#clockmissed').hide();
                    $('#CLK_db_selectmnth').val('');
                    $('#REP_tb_date').hide();
                    $('#REP_lbl_date').hide();
                    $('#REP_btn_date').hide();
                    $('#CLK_lbl_loginid').hide();
                    $('#CLK_lb_loginid').hide();
                    $('#CLK_btn_search').hide();
                    $('#CLK_lbl_selectmnths').hide();
                    $('#CLK_selectmnths').hide();
                    $('#CLK_div_monthyr').hide();
                    $('#CLK_div_actvenon_dterange').hide();
                    $('#CLK_lbl_actveemps').hide();
                    $('#CLK_lbl_nonactveemps').hide();
                    $('#CLK_nodata_pdflextble').hide();
                    $('#CLK_nodata_pdflextbles').hide();
                    $('#CLK_nodata_lgnid').hide();
                    $('#src_lbl_error').hide();
                    $('#CLK_btn_mnth_pdf').hide();
                    $('#src_lbl_error_login').hide();
                    $('#no_of_days').hide();
                    $('#CLK_btn_emp_pdf').hide();
                    $("#CLK_btn_mysearch").attr("disabled","disabled");
                    $('input:radio[name=CLK_rd_actveemp]').attr('checked',false);
                    $('.preloader').hide();
                    $('#REP_chk_lbl_srchby').hide();
                    REP_strtend_errmsgs;
                    REP_allactve_msg;
                    $('#REP_chk_btn_search').hide();
                    $('#REP_btn_searchdaterange').hide();
                    $('#REP_btn_pdf').hide();
                    $('#REP_btn_pdfs').hide();
                    $('#REP_chk_lb_srchby').hide();
                    $('#REP_chk_lbl_srchby').hide();
                    $('#REP_lbl_report_entry').hide();

//        $('.preloader', window.parent.document).show();
                    $(".preloader").show();
                    //GEETING INITIAL DATA FROM DB
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            var values_array=JSON.parse(xmlhttp.responseText);
//                $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
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
                    xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_CLOCK_IN_OUT_DETAILS.do?option="+option);
                    xmlhttp.send();
                }
                else if(radiooption=='clockmissed')
                {
//            $("#CLK_db_selectmnth").datepicker({dateFormat: "mm-yy" ,changeYear:true,changeMonth:true });
                    $(".preloader").show();
                    $('#REP_lbl_report_entry').html('CLOCK OUT MISSED DETAILS');
                    $('#clockinout').hide();
                    $('#clockmissed').show();
                    $('#CLK_rd_actveemp').hide();
                    $('CLK_lbl_actveemp').hide();
                    $('CLK_rd_nonemp').hide();
                    $('CLK_lbl_nonactveemp').hide();
                    $('#CLK_lb_reportconfig').hide();
                    $('#CLK_lbl_reportconfig').hide();
                    $('#REP_tble_searchbtn').hide();
                    $('#REP_chk_lbl_btwnrange').hide();
                    $('#REP_chk_rd_allactveemp').hide();
                    $('#REP_chk_lbl_btwnranges').hide();
                    $('#REP_chk_rd_btwnrange').hide();
                    $('#REP_chk_lbl_allactveemp').hide();
                    $('#CLK_div_actvenon_dterange').hide();
                    $('#REP_chk_tble_actnonact').hide();
                    $('#REP_chk_rd_actveemp').hide();
                    $('#REP_chk_lbl_actveemp').hide();
                    $('#REP_chk_rd_nonemp').hide();
                    $('#REP_chk_lbl_nonactveemp').hide();
                    $('#CLK_rd_actveemp').hide();
                    $('#CLK_rd_nonemp').hide();
                    $('#CLK_rd_actveem').hide();
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
                    $('#REP_chk_lb_srchby').hide();
                    $('#REP_chk_tble_actnonact').hide();
                    $('#REP_tb_date').hide();
                    $('#REP_lbl_date').hide();
                    $('#REP_btn_date').hide();
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
                    $('#CLK_rd_actveemp').hide();
                    $('#CLK_rd_nonemp').hide();
                    $('#REP_chk_lbl_allactveemps').hide();
                    errmsg;
                    pdferrmsg;
                    msg;
                    CLK_reportdte;
                    $('#CLK_nodata_rc').hide();
                    $('#CLK_btn_mnth_pdf').hide();
                    $('#CLK_btn_emp_pdf').hide();
                    $('#CLK_db_selectmnth').hide();
                    $('#CLK_lbl_selectmnth').hide();
                    $(".ui-datepicker-calendar").hide();
                    $('#CLK_lb_loginid').hide();
                    CLK_reportconfig_listbx=[];
                    CLK_active_emp=[];
                    CLK_nonactive_emp=[];
//        $('.preloader', window.parent.document).show()
                    $(".preloader").show();
                    $('#CLK_btn_search').hide();
                    $('#CLK_btn_mysearch').hide();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader', window.parent.document).hide()
                            $(".preloader").hide();
                            var values_array=JSON.parse(xmlhttp.responseText);

                            CLK_reportconfig_listbx=values_array[0];
                            CLK_active_emp=values_array[1];
                            CLK_nonactive_emp=values_array[2];
                            CLK_errorAarray=values_array[3];
                            if(CLK_reportconfig_listbx.length!=0){
                                var CLK_config_list='<option>SELECT</option>';
                                for (var i=0;i<CLK_reportconfig_listbx.length;i++) {
                                    CLK_config_list += '<option value="' + CLK_reportconfig_listbx[i][1] + '">' + CLK_reportconfig_listbx[i][0] + '</option>';
                                }
                                $('#CLK_lb_reportconfig').html(CLK_config_list);
                                $('#CLK_lbl_reportconfig').show();
                                $('#CLK_lb_reportconfig').show();
                            }
                            else
                            {
                                $('#CLK_nodata_rc').text(CLK_errorAarray[2]).show();
                            }
                        }
                    }
                    var option="common";
                    xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_CLOCK_OUT_MISSED_DETAILS.do?option="+option);
                    xmlhttp.send();
                }
            });
//    $(document).on('click','#clock_in_out',function(){

            //FUNCTION FOR FORMTABLEDATEFORMAT
            function FormTableDateFormat(inputdate){
                var string = inputdate.split("-");
                return string[2]+'-'+ string[1]+'-'+string[0];
            }
            // CHANGE EVENT FOR SEARCH BY LIST BX
            $(document).on('change','#REP_chk_lb_srchby',function(evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
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
//        alert('1');
                $('#REP_tble_date').html('').append(' <div class="row-fluid form-group" style="padding-top: 15px"><label class="col-sm-3" name="REP_lbl_date" id="REP_lbl_date" >DATE<em>*</em></label><div class="col-sm-4"><input type="text" name="REP_tb_date" id="REP_tb_date" class="enable clear REP_date_picker datemandtry" style="width:75px;"></div></div>');
                $('#REP_tble_date').show();
                $('#REP_tb_date').datepicker(
                    {
                        dateFormat: 'dd-mm-yy',
                        changeYear: true,
                        changeMonth: true,
                        beforeShow:function(input, inst) {
                            $(inst.dpDiv).removeClass('calendar-off');
                        }
                    });
                $('#REP_tb_date').val('');
                $('#REP_tb_date').show();
                $('#REP_lbl_date').show();
                $('#REP_btn_date').show();
                $('#REP_btn_date').attr('disabled','disabled');
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
//        alert('2');
//            $('<div class="row-fluid form-group"><label class="col-sm-3" name="REP_lbl_date" id="REP_lbl_date" >DATE<em>*</em></label><div class="col-sm-4"><input type="text" name="REP_tb_date" id="REP_tb_date" class="enable clear REP_date_picker datemandtry" ></div></div>').appendTo('#REP_tble_date')
//            $('<div class="col-sm-12"><input type="button" class="btn " name="REP_btn_date" id="REP_btn_date"  value="SEARCH"></div>').appendTo('#REP_tble_searchbutton')
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
//            $('.preloader', window.parent.document).show();
                $(".preloader").show();
                //FUNCTION FOR SETTINF MIN ND MAX DATE
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        $('.maskpanel',window.parent.document).removeAttr('style').hide();
//                    $('.preloader').hide();
//                alert(xmlhttp.responseText);
                        daterange_val=JSON.parse(xmlhttp.responseText);
                        $("#REP_tb_date").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                        var REV_start_dates=daterange_val[0];
                        var REV_end_dates=daterange_val[1];
                        $('#REP_tb_date').datepicker("option","minDate",new Date(REV_start_dates));
                        $('#REP_tb_date').datepicker("option","maxDate",new Date(REV_end_dates));
                    }
                }
                var choice="datemin_max";
                xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_CLOCK_IN_OUT_DETAILS.do?REV_project_name="+REV_project_name+"&option="+choice,true);
                xmlhttp.send(new FormData(formElement));
            });
            //VALIDATION FOR START ND END DATE
            $(document).on('change','.enable',function(){
//        alert('going');
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
//            alert('3');
                    $("#REP_btn_date").attr("disabled", "disabled");
                }
                else
                {
//            alert('checking');
                    $("#REP_btn_date").removeAttr("disabled");
                    $("#REP_btn_date").show();
                }
            });
            //CHANGE FUNCTION FOR BETWEEN  RANGE
            $(document).on('click','#REP_chk_rd_btwnrange',function(){
                $('#between').html('').append('<div class="row-fluid form-group"><label name="REP_chk_lbl_btwnranges" id="REP_chk_lbl_btwnranges" class="srctitle col-sm-12" hidden>BETWEEN RANGE</label></div>');
                $('#between').show();
                $('#active_emp').html('').append('<div class="row-fluid form-group"><div class="radio"><label name="REP_chk_lbl_actveemp" class="col-sm-8" id="REP_chk_lbl_actveemp"  hidden><div class="col-sm-4"><input type="radio" name="REP_chk_rd_actveemp" id="REP_chk_rd_actveemp" value="EMPLOYEE" hidden >ACTIVE EMPLOYEE</label></div></div></div>');
                $('#active_emp').show();
                $('#non_active').html('').append('<div class="row-fluid form-group"><div class="radio"><label name="REP_chk_lbl_nonactveemp" class="col-sm-8" id="REP_chk_lbl_nonactveemp"  hidden><div class="col-sm-4"><input type="radio" name="REP_chk_rd_actveemp" id="REP_chk_rd_nonemp"   value="EMPLOYEE" class="attnd" hidden>NON ACTIVE EMPLOYEE </label></div></div></div>');
                $('#non_active').show();
                $('#REP_tble_date').html();
                $('#REP_nodata_btwrange').hide();
                $('#REP_lbl_daterange').hide();
                $('#REP_lbl_nodata_allactive').hide();
                $('#REP_lbl_dteranges').hide();
                $('#REP_btn_pdf').hide();
                $('#REP_btn_pdfs').hide();
                $('#REP_tb_date').hide();
                $('#REP_lbl_date').hide();
                $('#REP_btn_date').hide();
                $('#REP_chk_lbl_btwnranges').hide();
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
            $(document).on('click','#active_emp',function(){
                $('#active_label').html('').append('<div  style="padding-top: 30px"><label name="REV_lbl_actveemps" id="REV_lbl_actveemps" class="srctitle col-sm-12 " hidden>ACTIVE EMPLOYEE</label></div>');
                $('#active_label').show();
                $('#emp_name').html('').append('<div class="row-fluid form-group"><label class="col-sm-2" name="REP_lbl_loginid" id="REP_lbl_loginid"  hidden>EMPLOYEE NAME<em>*</em></label><div class="col-sm-5"><select name="REP_lb_loginid" id="REP_lb_loginid" class="form-control" style="display:none"></select></div></div>');
                $('#emp_name').show();
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
            $(document).on('click','#non_active',function(){

                $('#non_active_label').html('').append('<div class="row-fluid form-group"><label name="REV_lbl_nonactveemps" id="REV_lbl_nonactveemps" class="srctitle col-sm-12" hidden>NON ACTIVE EMPLOYEE </label></div>');
                $('#non_active_label').show();
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
            $(document).on('change','#emp_name',function(){
                $('#REP_tablecontainer_bydaterange').hide();
                $('#REP_nodata_btwrange').hide();
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
//                $('.preloader', window.parent.document).show();
                    $(".preloader").show();
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
//                $('.preloader', window.parent.document).show();
                    $(".preloader").show();
                    //FUNCTION FOR SETTINF MIN ND MAX DATE
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                        $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
                            daterange_val=JSON.parse(xmlhttp.responseText);
                            var REV_start_dates=daterange_val[0];
                            var REV_end_dates=daterange_val[1];
                            $("#REP_tb_strtdtebyrange").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                            $("#REP_tb_enddtebyrange").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                            $('#REP_tb_strtdtebyrange').datepicker("option","minDate",new Date(REV_start_dates));
                            $('#REP_tb_strtdtebyrange').datepicker("option","maxDate",new Date(REV_end_dates));
                            $('#REP_tb_enddtebyrange').datepicker("option","maxDate",new Date(REV_end_dates));
                            if(REV_start_dates==null && ($('#REP_lb_loginid').val()!='SELECT')){
                                $('#REP_nodata_btwrange').text(REP_chk_errorAarray[3]).show();
                                $('#REP_tb_strtdtebyrange').hide();
                                $('#REP_tb_enddtebyrange').hide();
                                $('#REP_lbl_strtdtebyrange').hide();
                                $('#REP_btn_searchdaterange').hide();
                                $('#REP_lbl_enddte').hide();
                                $('#REP_lbl_strtdtebyrange').hide();
                                $('#REP_lbl_enddte').hide();
                                $('#REP_tb_enddtebyrange').hide();
                                $('#REP_btn_searchdaterange').hide();

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
                    xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_CLOCK_IN_OUT_DETAILS.do?REP_chk_loginid="+REP_chk_loginid+"&option="+choice,true);
                    xmlhttp.send(new FormData(formElement));

                    //SET END DATE
                    $(document).on('change','#REP_tb_strtdtebyrange',function(){
//                alert('start');
                        $('#REV_lbl_emptitle').hide();
                        $("#REP_tb_strtdtebyrange").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                        var USRC_UPD_startdate = $('#REP_tb_strtdtebyrange').datepicker('getDate');
                        var date = new Date( Date.parse( USRC_UPD_startdate ));
                        date.setDate( date.getDate());
                        $("#REP_tb_enddtebyrange").datepicker({dateFormat: "dd-mm-yy" ,changeYear:true,changeMonth:true });
                        var USRC_UPD_todate = date.toDateString();
                        USRC_UPD_todate = new Date( Date.parse( USRC_UPD_todate ));
                        $('#REP_tb_enddtebyrange').datepicker("option","minDate",USRC_UPD_todate);
                    });
                }
            });
            // CHANGE EVENT FOR STARTDATE AND ENDDATE
            $(document).on('change','.valid',function(){
//        alert('hhhhhhh');
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
//            alert('oooooooo');
                    $("#REP_btn_searchdaterange").attr("disabled", "disabled");
                }
                else
                {
//            alert('yyyyyyyy');
                    $("#REP_btn_searchdaterange").removeAttr("disabled");
                }
            });
            var allvalues_array;
            //CLICK FUNCTION FOR ALL ACTIVE DATE BX
            $(document).on('click','#REP_btn_date',function(){
//            $('.preloader', window.parent.document).show();
//        alert('button');
                $(".preloader").show();
                $('#datarange').html('').append('<div style="padding-left: 15px"><label id="REP_lbl_dteranges" name="REP_lbl_dteranges"  class="srctitle" hidden></label></div><br>');
                $('#datarange').show();
                $('#pdf').html('').append('<div style="padding-left: 10px"><input type="button" id="REP_btn_pdfs" class="btnpdf" value="PDF"></div>');
                $('#pdf').show();
                $('#between').html('');
                $('#active_emp').html('');
                $('#non_active').html('');
                $('#active_label').html('');
                $('sectionid').html('');
                $('#non_active_label').html('');
                $('#emp_name').html('');
                $('#REP_chk_lbl_btwnranges').hide();
                $('REP_chk_lbl_actveemp').hide();
                $('REP_chk_lbl_nonactveemp').hide();
                $('REV_lbl_actveemps').hide();
                $('REV_lbl_nonactveemps').hide();
                $('REP_lbl_loginid').hide();
                $('REP_lbl_strtdtebyrange').hide();
                $('REP_lbl_enddte').hide();
                $('REP_btn_searchdaterange').hide();
                $("#REP_btn_date").attr("disabled","disabled");
                $('#REP_tablecontainer').hide();
                $('#REP_tble_allactive_count').hide();
                $('#REP_btn_pdfs').hide();
                var option=$('#REP_chk_lb_srchby').val();
                var date=$('#REP_tb_date').val();
                var loginid=$('#REP_lb_loginid').val();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        allvalues_array=JSON.parse(xmlhttp.responseText);
                        if(allvalues_array.length!=0){
                            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                            REP_allactve_msg=REP_chk_errorAarray[5].toString().replace("[DATE]",date);
                            $('#REP_lbl_dteranges').text(REP_allactve_msg).show();
                            $("#REP_btn_pdfs").show();
                            var ADM_tableheader='<table id="REP_tble_allactive_count" border="1"  cellspacing="0" class="srcresult"  ><thead  bgcolor="#6495ed" style="color:white"><tr><th>EMPLOYEE NAME</th><th>CLOCK IN</th><th>CLOCK IN LOCATION</th><th>CLOCK OUT</th><th>CLOCK OUT LOCATION</th></tr></thead><tbody>'
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
                                ADM_tableheader+='<tr ><td align="center">'+check_in_empname+'</td><td align="center">'+clockin+'</td><td align="center">'+clockinlocation+'</td><td align="center">'+clockout+'</td><td align="center">'+clockoutlocation+'</td></tr>';
                            }
                            ADM_tableheader+='</tbody></table>';
                            $('sectionid').html(ADM_tableheader);
                            $('#REP_tble_allactive_count').DataTable( {
                                "aaSorting": [],
                                "pageLength": 10,
                                "sPaginationType":"full_numbers"
                            });

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
                $('#REP_tablecontainer').show();
                var option="ALL_ACTIVE_RANGE";
                xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_CLOCK_IN_OUT_DETAILS.do?option="+option+"&date="+date+"&loginid="+loginid);
                xmlhttp.send();
            });
            var allvalues_array;
            //CLICK  FUNCTION FOR DATE BX
            $(document).on('click','#REP_btn_searchdaterange',function(){
//            $('.preloader', window.parent.document).show();
                $(".preloader").show();
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
//                    $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        allvalues_array=JSON.parse(xmlhttp.responseText);
                        if(allvalues_array.length!=0){
                            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                            $('#REP_btn_pdf').show();
                            var sd=REP_chk_errorAarray[4].toString().replace("[LOGINID]",loginid_val);
                            var errmsg=sd.toString().replace("[STARTDATE]",startdate);
                            REP_strtend_errmsgs=errmsg.toString().replace("[ENDDATE]",enddate);
                            $('#REP_lbl_daterange').text(REP_strtend_errmsgs).show();
                            var ADM_tableheader='<table id="REP_tble_absent_count" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th class="uk-date-column" style="width:90px">DATE</th><th>CLOCK IN</th><th>CLOCK IN LOCATION</th><th>CLOCK OUT</th><th>CLOCK OUT LOCATION</th></tr></thead><tbody>'
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
                                ADM_tableheader+='<tr ><td style="">'+check_in_date+'</td><td align="center">'+clockin+'</td><td align="center" style="">'+clockinlocation+'</td><td align="center">'+clockout+'</td><td align="center" style="">'+clockoutlocation+'</td></tr>';
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
                xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_CLOCK_IN_OUT_DETAILS.do?option="+option+"&startdate="+startdate+"&enddate="+enddate+"&loginid="+loginid);
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
                var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=24&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&inputValThree='+inputValThree+'&title='+REP_strtend_errmsgs;
            });
            //CLICK FUNCTION FOR PDF BUTTON
            $(document).on('click','#REP_btn_pdfs',function(){
                var inputValFour=$('#REP_tb_date').val();
                inputValFour = inputValFour.split("-").reverse().join("-");
                var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=23&inputValFour='+inputValFour+'&title='+REP_allactve_msg;
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

            <!--CLOCK OUT MISSED DETAILS-->

//    $(document).on('click','#clock_missed',function(){
//        $('.preloader', window.parent.document).show();

            //CHANGE FUNCTION FOR BANDWIDTH LISTBX
            $(document).on('change','#CLK_lb_reportconfig',function(){
//        alert('missed');
                var formElement = document.getElementById("REP_chk_form_details");
                var date_val=[];
                $('#CLK_db_selectmnth').val('');
                $('#CLK_db_selectmnth').show();
                $('#CLK_lbl_selectmnth').show();
                $('#CLK_lbl_loginid').hide();
                $('#CLK_lb_loginid').hide();
                $('#CLK_btn_search').hide();
                $('#CLK_div_actvenon_dterange').hide();
                $('#CLK_lbl_selectmnths').hide();
                $('#CLK_selectmnths').hide();
                $('#CLK_div_monthyr').hide();
                $('#CLK_div_monthyr').hide();
                $('#CLK_lbl_actveemps').hide();
                $('#CLK_lbl_nonactveemps').hide();
                $('#CLK_nodata_pdflextble').hide();
                $('#CLK_nodata_pdflextbles').hide();
                $('#CLK_nodata_lgnid').hide();
                $('#src_lbl_error').hide();
                $('#CLK_btn_mnth_pdf').hide();
                $('#src_lbl_error_login').hide();
                $('#no_of_days').hide();
                $('#CLK_btn_emp_pdf').hide();
                $("#CLK_btn_mysearch").attr("disabled","disabled");
                $('input:radio[name=CLK_rd_actveemp]').attr('checked',false);
                var option=$("#CLK_lb_reportconfig").val();
                if(option=="SELECT")
                {
//            alert('pppppp');
                    $('#CLK_lbl_selectmnth').hide();
                    $('#CLK_db_selectmnth').hide();
                    $('#CLK_btn_mysearch').hide();
                    $('#src_lbl_error').hide();
                    $('#CLK_btn_mnth_pdf').hide();
                    $('#src_lbl_error_login').hide();
                    $('#no_of_days').hide();
                    $('#CLK_btn_emp_pdf').hide();
                    $('#CLK_tble_prjctCLKactnonact').hide();
                }
                //BANDWIDTH BY MONTH
                else if(option=='14')
                {

//                $('.preloader', window.parent.document).show();
                    $(".preloader").show();
                    //FUNCTION FOR SETTING MIN ND MAX DATE
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                        $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
//                    alert(xmlhttp.responseText);
                            date_val=JSON.parse(xmlhttp.responseText);
//                    alert('date');
                            var CLK_start_dates=date_val[0];
//                    alert('go');
                            var CLK_end_dates=date_val[1];
//                        $('.preloader', window.parent.document).hide();

                        }
                        $(".date-pickers").datepicker({
                            dateFormat: 'MM yy',
                            changeMonth: true,
                            changeYear: true,
                            showButtonPanel: true,
                            beforeShow:function(input, inst) {
                                $(inst.dpDiv).addClass('calendar-off');
                            },
                            onClose: function(dateText, inst) {
//                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
//                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                                var month =inst.selectedMonth;
                                var year = inst.selectedYear;
                                $(this).val($.datepicker.formatDate('MM yy', new Date(year, month, 1)));
                                $("#CLK_btn_mysearch").attr("disabled");
                                dpvalidation()
                            }
                        });

//            $(".date-pickers").focus(function () {
//                $(".ui-datepicker-calendar").hide();
//                $("#ui-datepicker-div").position({
//                    my: "center top",
//                    at: "center bottom",
//                    of: $(this)
//                });
//            });
//                //DATE PICKER FUNCTION START
//                $('.date-pickers').datepicker({
//                    changeMonth: true,      //provide option to select Month
//                    changeYear: true,       //provide option to select year
//                    showButtonPanel: true,   // button panel having today and done button
//                    dateFormat: 'MM-yy',    //set date format
//                    //ONCLOSE FUNCTION
//                    onClose: function(dateText, inst) {
////                                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
////                                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
//                        var month =inst.selectedMonth;
//                        var year = inst.selectedYear;
//                        $(this).datepicker('setDate', new Date(year, month, 1));//here set the date when closing.
//                        $(this).blur();//remove focus input box
//                        $("#CLK_btn_mysearch").attr("disabled");
//                        dpvalidation()
//                    }
//                });
//                //FOCUS FUNCTION
//                $('.datepickers').focus(function () {
//                    $(".ui-datepicker-calendar").show();
//                    $("#ui-datepicker-div").position({
//                        my: "center top",
//                        at: "center bottom",
//                        of: $(this)
//                    });
//                });
                        if(CLK_start_dates!='' &&CLK_start_dates !=null){
                            $('#CLK_db_selectmnth').datepicker("option","minDate", new Date(CLK_start_dates));
                            $('#CLK_db_selectmnth').datepicker("option","maxDate", new Date(CLK_end_dates));}
                        //VALIDATION FNCTION FOR DATE BX OF BW BY MONTH
                        function dpvalidation(){

                            $('section').html('');
                            $('sections').html('');
                            $('#CLK_div_monthyr').hide();
                            $('#src_lbl_error').hide();
                            $('#CLK_btn_mnth_pdf').hide();
                            $('#src_lbl_error_login').hide();
                            $('#no_of_days').hide();
                            $('#CLK_btn_emp_pdf').hide();
                            $('#CLK_nodata_pdflextble').hide();
                            $("#CLK_btn_mysearch").attr("disabled","disabled");
                            if($("#CLK_db_selectmnth").val()=='')
                            {
//
                                $("#CLK_btn_mysearch").attr("disabled","disabled");
                            }
                            if(($('#CLK_db_selectmnth').val()!='undefined')&&($('#CLK_db_selectmnth').val()!=''))
                            {

                                $("#CLK_btn_mysearch").removeAttr("disabled");
                            }
                            else
                            {

                                $("#CLK_btn_mysearch").attr("disabled");
                            }
                        }
                        $('#CLK_lbl_selectmnth').show();
                        $('#CLK_btn_mysearch').show();
                        $('#CLK_db_selectmnth').show();
                        $('#CLK_tble_prjctCLKactnonact').hide();
                    }
                    var choice="minmax_dtewth_monthyr";
                    xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_CLOCK_OUT_MISSED_DETAILS.do?option="+choice,true);
                    xmlhttp.send(new FormData(formElement));
                }
                //BANDWIDTH BY EMPLOYEE
                else if(option=='15')
                {
                    $('#CLK_tble_prjctCLKactnonact').show();
                    $('#CLK_rd_actveemp').show();
                    $('#CLK_lbl_actveemp').show();
                    $('#CLK_rd_nonemp').show();
                    $('#CLK_lbl_nonactveemp').show();
                    $('#CLK_lbl_selectmnth').hide();
                    $('#CLK_btn_mysearch').hide();
                    $('#CLK_db_selectmnth').hide();
                    $('#src_lbl_error').hide();
                    $('#CLK_btn_mnth_pdf').hide();
                    $('#src_lbl_error_login').hide();
                    $('#no_of_days').hide();
                    $('#CLK_btn_emp_pdf').hide();
                }
            });

            // CLICK EVENT FOR ACTIVE RADIO BUTTON
            $(document).on('click','#CLK_rd_actveemp',function(){
                $('#CLK_btn_search').hide();
                $('#CLK_db_selectmnth').hide();
                $('#CLK_lbl_selectmnth').hide();
                $('#CLK_lbl_selectmnths').hide();
                $('#CLK_selectmnths').hide();
                $('#CLK_nodata_uld').hide();
                $('#CLK_nodata_loginid').hide();
                $('#CLK_div_nonactve_dterange').hide();
                $('#CLK_nodata_startenddate').hide();
                $('#CLK_div_actvenon_dterange').hide();
                $('#CLK_lbl_nonactveemps').hide();
                $('#CLK_nodata_pdflextbles').hide();
                $('#CLK_nodata_lgnid').hide();
                $('#src_lbl_error').hide();
                $('#CLK_btn_mnth_pdf').hide();
                $('#src_lbl_error_login').hide();
                $('#no_of_days').hide();
                $('#CLK_btn_emp_pdf').hide();
                if(CLK_active_emp.length!=0)
                {
                    var CLK_active_employee='<option>SELECT</option>';
                    for (var i=0;i<CLK_active_emp.length;i++) {
                        CLK_active_employee += '<option value="' + CLK_active_emp[i][1] + '">' + CLK_active_emp[i][0] + '</option>';
                    }
                    $('#CLK_lb_loginid').html(CLK_active_employee);
                    $('#CLK_lbl_actveemps').show();
                    $('#CLK_lbl_loginid').show();
                    $('#CLK_lb_loginid').show();
                }
                else
                {
                    $('#CLK_nodata_lgnid').text(err_msg_array[0]).show();
                }
            });
            // CLICK EVENT FOR NON ACTIVE RADIO BUTTON
            $(document).on('click','#CLK_rd_nonemp',function(){
                $('#CLK_selectmnths').val('');
                $('#CLK_btn_search').hide();
                $('#CLK_db_selectmnth').hide();
                $('#CLK_lbl_selectmnth').hide();
                $('#CLK_db_selectmnth').hide();
                $('#CLK_lbl_selectmnths').hide();
                $('#CLK_selectmnths').hide();
                $('#CLK_nodata_uld').hide();
                $('#CLK_nodata_loginid').hide();
                $('#CLK_div_nonactve_dterange').hide();
                $('#CLK_nodata_startenddate').hide();
                $('#CLK_div_actvenon_dterange').hide();
                $('#CLK_lbl_actveemps').hide();
                $('#CLK_nodata_pdflextbles').hide();
                $('#CLK_nodata_lgnid').hide();
                $('#src_lbl_error').hide();
                $('#CLK_btn_mnth_pdf').hide();
                $('#src_lbl_error_login').hide();
                $('#no_of_days').hide();
                $('#CLK_btn_emp_pdf').hide();
                if(CLK_nonactive_emp.length!=0)
                {
                    var CLK_nonactive='<option>SELECT</option>';
                    for (var i=0;i<CLK_nonactive_emp.length;i++) {
                        CLK_nonactive += '<option value="' + CLK_nonactive_emp[i][1] + '">' + CLK_nonactive_emp[i][0] + '</option>';
                    }
                    $('#CLK_lb_loginid').html(CLK_nonactive);
                    $('#CLK_lbl_nonactveemps').show();
                    $('#CLK_lbl_loginid').show();
                    $('#CLK_lb_loginid').show();
                }
                else
                {
                    $('#CLK_nodata_lgnid').text(CLK_errorAarray[0]).show();
                }
            });
            // CHANGE EVENT FOR LOGIN ID LIST BX
            $(document).on('change','#CLK_lb_loginid',function(evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                var formElement = document.getElementById("REP_chk_form_details");
                var date_val=[];
                $('#CLK_selectmnths').val('');
                $('#CLK_btn_search').attr("disabled","disabled");
                $('#CLK_db_selectmnth').show();
                $('#CLK_lbl_selectmnth').show();
                $('#CLK_lbl_selectmnths').hide();
                $('#CLK_selectmnths').hide();
                $('#CLK_db_selectmnth').hide();
                $('#CLK_nodata_pdflextbles').hide();
                $('#CLK_lbl_selectmnth').hide();
                $('#CLK_div_actvenon_dterange').hide();
                $('#src_lbl_error_login').hide();
                $('#src_lbl_error').hide();
                $('#CLK_btn_mnth_pdf').hide();

                $('#no_of_days').hide();
                $('#CLK_btn_emp_pdf').hide();
                var CLK_loginid=$('#CLK_lb_loginid').val();
                if($('#CLK_lb_loginid').val()=="SELECT")
                {
                    $('#CLK_btn_search').hide();
                    $('#CLK_btn_search').attr("disabled","disabled");
                    $('#CLK_lbl_selectmnths').hide();
                    $('#CLK_selectmnths').hide();
                    $('#src_lbl_error').hide();
                    $('#CLK_btn_mnth_pdf').hide();
                    $('#src_lbl_error_login').hide();
                    $('#no_of_days').hide();
                    $('#CLK_btn_emp_pdf').hide();
                }
                else
                {
//                $('.preloader', window.parent.document).show();
                    $(".preloader").show();
                    //FUNCTION FOR SETTINF MIN ND MAX DATE
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                        $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
                            date_val=JSON.parse(xmlhttp.responseText);
                            var CLK_start_dates=date_val[0];
                            var CLK_end_dates=date_val[1];
                        }
                        //DATE PICKER FUNCTION
                        $('.datepickers').datepicker( {
                            changeMonth: true,      //provide option to select Month
                            changeYear: true,       //provide option to select year
                            showButtonPanel: true,   // button panel having today and done button
                            dateFormat: 'MM-yy',
                            beforeShow:function(input, inst) {
                                $(inst.dpDiv).addClass('calendar-off');
                            },
                            //ONCLOSE FUNCTION
                            onClose: function(dateText, inst) {
//                                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
//                                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                                var month =inst.selectedMonth;
                                var year = inst.selectedYear;

                                $(this).datepicker('setDate', new Date(year, month, 1));//here set the date when closing.
                                $(this).blur();//remove focus input box
                                $("#CLK_btn_search").attr("disabled");
                                validationdp()
                            }
                        });
                        //FOCUS FUNCTION
//                $(".date-pickers").focus(function () {
//                    $(".ui-datepicker-calendar").hide();
//                    $(this).datepicker('setDate', $(this).val());
//                    $("#ui-datepicker-div").position({
//                        my: "center",
//                        at: "center",
//                        of: $(this)
//                    });
//                });
                        if(CLK_start_dates!=null && CLK_start_dates!=''){

                            $('#CLK_btn_search').show();
                            $('#CLK_lbl_selectmnths').show();
                            $('#CLK_selectmnths').show();
//                    $('#CLK_nodata_lgnid').hide();
                            $('#src_lbl_error_login').hide();
                            $('#no_of_days').hide();
                            $(".datepickers").datepicker("option","minDate", new Date(CLK_start_dates));
                            $(".datepickers").datepicker("option","maxDate", new Date(CLK_end_dates));
                        }
                        else{
                            $('#CLK_btn_search').hide();
                            $('#CLK_lbl_selectmnths').hide();
                            $('#CLK_selectmnths').hide();
                            $('#src_lbl_error_login').text(CLK_errorAarray[2]).addClass('errormsg').removeClass('srctitle').show();
//                    $('#CLK_nodata_lgnid').text(CLK_errorAarray[2]).show();
//                    $('#CLK_nodata_lgnid')
                        }
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
                            if($("#CLK_selectmnths").val()=='')
                            {

                                $("#CLK_btn_search").attr("disabled","disabled");
                            }
                            if(($('#CLK_selectmnths').val()!='undefined')&&($('#CLK_selectmnths').val()!='')&&($('#CLK_lb_loginid').val()!="SELECT"))
                            {

                                $("#CLK_btn_search").removeAttr("disabled");
                            }
                            else
                            {

                                $("#CLK_btn_search").attr("disabled","disabled");
                            }
                        }

                    }
                    var choice="minmax_dtewth_loginid";
                    xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_CLOCK_OUT_MISSED_DETAILS.do?CLK_loginid="+CLK_loginid+"&option="+choice,true);
                    xmlhttp.send(new FormData(formElement));
                }
            });
            // CLICK EVENT FOR LOGIN ID SEARCH BTN
            var CLK_actnon_values=[];
            $(document).on('click','#CLK_btn_search',function(){
                $('#CLK_nodata_pdflextbles').hide();
                $('#CLK_div_actvenon_dterange').hide();
                $('#CLK_tble_lgn').html('');
                $('#CLK_btn_search').attr("disabled","disabled");
                var CLK_monthyear=$('#CLK_selectmnths').val();
                var CLK_loginid=$('#CLK_lb_loginid').val();
//            $('.preloader', window.parent.document).show();
                $(".preloader").show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        var CLK_actnon_values=JSON.parse(xmlhttp.responseText);
                        if(CLK_actnon_values[0].length!=0)
                        {
                            var CLK_reportdate= CLK_actnon_values[0];
                            var total= CLK_actnon_values[1];
                            for(var i=0;i<total.length;i++){
                                CLK_reportdte=total[i].count;
                            }
                            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                            $('#no_of_days').text("TOTAL NO OF DAYS FOR CLOCK OUT MISSED: "  +   CLK_reportdte   +  " DAYS").show();
                            errmsg=CLK_errorAarray[4].toString().replace("[MONTH]",CLK_monthyear);
                            var msg=errmsg.toString().replace("BANDWIDTH",'CLOCK OUT MISSED DETAILS');
                            errmsg=msg.replace("[LOGINID]", $("#CLK_lb_loginid option:selected").text());
                            $('#src_lbl_error_login').text(errmsg).addClass('srctitle').removeClass('errormsg').show();
                            $('#CLK_btn_emp_pdf').show();
                            var loginname;
                            var loginpos=CLK_loginid.search("@");
                            if(loginpos>0){
                                loginname=CLK_loginid.substring(0,loginpos);
                            }
                            pdferrmsg=errmsg;
                            var CLK_table_header='<table id="CLK_tble_lgn" border="1"  cellspacing="0" class="srcresult" width=300px ><thead  bgcolor="#6495ed" style="color:white"><tr><th style="width:30px">CLOCK OUT MISSED DATE</th></tr></tfoot><tbody>'
                            for(var i=0;i<CLK_reportdate.length;i++){
                                var CLK_dte=CLK_reportdate[i].date;
                                CLK_table_header+='<tr><td align="center" style="width:30px">'+CLK_dte+'</td></tr>';
                            }
                            CLK_table_header+='</tbody></table>';
                            $('section').html(CLK_table_header);
                            $('#CLK_tble_lgn').DataTable({
                            });
                            $('#CLK_div_actvenon_dterange').show();
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
                xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_CLOCK_OUT_MISSED_DETAILS.do?option="+option+"&CLK_loginid="+CLK_loginid+"&CLK_monthyear="+CLK_monthyear);
                xmlhttp.send();
            });
            // CLICK EVENT FOR MONTH ND YEAR SEARCH BTN
            var CLK_monthyr_values=[];
            $(document).on('click','#CLK_btn_mysearch',function(){
                $('#CLK_nodata_pdflextble').hide();
                $('#CLK_div_monthyr').hide();
                $('#CLK_tble_bw').html('');
                $('#src_lbl_error').hide();
                $('#CLK_btn_mnth_pdf').hide();
                $('#src_lbl_error_login').hide();
                $('#no_of_days').hide();
                $('#CLK_btn_emp_pdf').hide();
                $('#CLK_btn_mysearch').attr("disabled","disabled");
                var CLK_monthyear=$('#CLK_db_selectmnth').val();
//            $('.preloader', window.parent.document).show();
                $(".preloader").show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        var CLK_monthyr_values=JSON.parse(xmlhttp.responseText);
                        if(CLK_monthyr_values.length!=0)
                        {
                            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                            errmsg=CLK_errorAarray[3].toString().replace("BANDWIDTH USAGE",'CLOCK OUT MISSED DETAILS');
                            msg=errmsg.toString().replace("[MONTH]",CLK_monthyear);
                            $('#src_lbl_error').text(msg).show();
                            $('#CLK_btn_mnth_pdf').show();
                            var CLK_table_header='<table id="CLK_tble_bw" border="1"  cellspacing="0" class="srcresult" width=400px><thead  bgcolor="#6495ed" style="color:white"><tr><th width=200px>EMPLOYEE NAME</th><th style="width:90px">TOTAL COUNTS</th></tr></thead><tbody>'
                            for(var i=0;i<CLK_monthyr_values.length;i++){
                                var CLK_employeename=CLK_monthyr_values[i].name;
                                var CLK_count=CLK_monthyr_values[i].absent_count;
                                if(CLK_employeename!=null)
                                {
                                    CLK_table_header+='<tr><td>'+CLK_employeename+'</td><td align="center" style="width:90px">'+CLK_count+'</td></tr>';
                                }
                            }
                            CLK_table_header+='</tbody></table>';
                            $('sections').html(CLK_table_header);
                            $('#CLK_tble_bw').DataTable({
                            });
                            $('#CLK_div_monthyr').show();
                        }
                        else
                        {
                            var sd=CLK_errorAarray[1].toString().replace("[DATE]",CLK_monthyear);
                            $('#CLK_nodata_pdflextble').text(sd).show();
                            $('#CLK_div_monthyr').hide();
                        }
                    }
                }
                var option="CLK_monthyear_searchoption";
                xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_CLOCK_OUT_MISSED_DETAILS.do?option="+option+"&CLK_db_selectmnth="+CLK_monthyear);
                xmlhttp.send();
            });
            //CLICK EVENT FOR PDF BUTTON
            $(document).on('click','#CLK_btn_mnth_pdf',function(){
                var inputValOne=$('#CLK_db_selectmnth').val();
                var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=26&inputValOne='+inputValOne+'&title='+msg;
            });
            $(document).on('click','#CLK_btn_emp_pdf',function(){
                var inputValOne=$("#CLK_selectmnths").val();
                var inputValThree =$('#CLK_lb_loginid').val();
                var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=27&inputValOne='+inputValOne+'&inputValThree='+inputValThree+'&title='+errmsg;
            });
        });
        <!--SCRIPT TAG END-->
    </script>
</head>
<!--BODY TAG START-->
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/></div>
    <div class="title text-center"><h4><b>CLOCK DETAILS</b></h4></div>
    <form id="REP_chk_form_details" class="content" role="form">
        <div class="panel-body">
            <fieldset>
                <div style="padding-left: 15px">
                    <div class="radio">
                        <label>
                            <input type="radio" name="clock" class="clock_out" id="clock_in_out" value="clockinout">CLOCK IN/OUT DETAILS</label>
                    </div></div>
                <div style="padding-left: 15px">
                    <div class="radio">
                        <label>
                            <input type="radio" name="clock"  class="clock_out" id="clock_missed" value="clockmissed">CLOCK OUT/MISSED DETAILS</label>
                    </div></div>
                <div>
                    <label name="REP_report_entry" id="REP_lbl_report_entry" class="srctitle col-sm-12"></label>
                </div>

                <div id="clockinout" hidden>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="REP_chk_lbl_srchby" id="REP_chk_lbl_srchby">SEARCH BY<em>*</em></label>
                        <div class="col-sm-5">
                            <select id="REP_chk_lb_srchby" name="REP_chk_lb_srchby" class="form-control">
                            </select>
                        </div>
                    </div>
                    <div><label id="REP_nodata_rc" name="REP_nodata_rc" class="errormsg"></label></div>

                    <div id="REP_tble_startdate"></div>
                    <div id="REP_tble_searchbtn"></div>
                    <!--                    <div style="padding-bottom: 15px">-->
                    <div style="padding-left: 15px" >
                        <div class="radio">
                            <label name="REP_chk_lbl_btwnrange" id="REP_chk_lbl_btwnrange" hidden> <input type="radio" name="REP_chk_rd_btwnrange" id="REP_chk_rd_btwnrange" value="RANGES" class='attnd' hidden>BETWEEN RANGE</label>
                        </div>
                    </div>
                    <div  style="padding-left: 15px">
                        <div class="radio">
                            <label name="REP_chk_lbl_allactveemp" id="REP_chk_lbl_allactveemp" hidden><input type="radio" name="REP_chk_rd_btwnrange" id="REP_chk_rd_allactveemp"   value="RANGES" class='attnd' hidden>ALL ACTIVE EMPLOYEE</label>
                        </div>
                    </div>
                    <div class="row-fluid form-group">
                        <label name="REP_chk_lbl_allactveemps" id="REP_chk_lbl_allactveemps" class="srctitle col-sm-12" hidden>ALL ACTIVE EMPLOYEE</label>
                    </div>
                    <div id="REP_tble_date"></div>
                    <!--        <div class="row-fluid form-group" style="padding-top: 15px">-->
                    <!--            <label class="col-sm-3" name="REP_lbl_date" id="REP_lbl_date" >DATE<em>*</em></label>-->
                    <!--            <div class="col-sm-4">-->
                    <!--                <input type="text" name="REP_tb_date" id="REP_tb_date" class="enable clear REP_date_picker datemandtry" style="width:75px;">-->
                    <!--            </div></div>-->
                    <!--        <div id="REP_tble_searchbutton"></div>-->
                    <div class="col-sm-12">
                        <input type="button" class="btn " name="REP_btn_date" id="REP_btn_date"  value="SEARCH" disabled>
                    </div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="REP_chk_lbl_dte" id="REP_chk_lbl_dte" hidden>DATE</label>
                        <div class="col-sm-4">
                            <input type="text" name="REP_chk_tb_dte" id="REP_chk_tb_dte" class="ASRC_UPD_DEL_date enable"   style="width:75px;"  hidden >
                        </div></div>
                    <div id="between"></div>
                    <!--<div  style="padding-bottom: 30px">-->
                    <!--            <label name="REP_chk_lbl_btwnranges" id="REP_chk_lbl_btwnranges" class="srctitle col-sm-12" hidden>BETWEEN RANGE</label>-->
                    <!--        </div>-->
                    <div id="active_emp"></div>
                    <!--<div  style="padding-bottom: 15px">-->
                    <!--    <div class="radio">-->
                    <!--            <label name="REP_chk_lbl_actveemp" class="col-sm-8" id="REP_chk_lbl_actveemp"  hidden>-->
                    <!--                <div class="col-sm-4">-->
                    <!--                    <input type="radio" name="REP_chk_rd_actveemp" id="REP_chk_rd_actveemp" value="EMPLOYEE" hidden >ACTIVE EMPLOYEE</label>-->
                    <!---->
                    <!--        </div></div></div>-->
                    <div id="non_active"></div>
                    <!--<div  style="padding-top: 10px">-->
                    <!--    <div class="radio">-->
                    <!--    <label name="REP_chk_lbl_nonactveemp" class="col-sm-8" id="REP_chk_lbl_nonactveemp"  hidden>-->
                    <!--        <div class="col-sm-4">-->
                    <!--            <input type="radio" name="REP_chk_rd_actveemp" id="REP_chk_rd_nonemp"   value="EMPLOYEE" class='attnd' hidden>NON ACTIVE EMPLOYEE </label>-->
                    <!--</div></div>-->
                    <!--</div>-->
                    <div id="active_label"></div>
                    <!--<div  style="padding-top: 30px">-->
                    <!--    <label name="REV_lbl_actveemps" id="REV_lbl_actveemps" class="srctitle col-sm-12 " hidden>ACTIVE EMPLOYEE</label>-->
                    <!--</div>-->
                    <div id="non_active_label"></div>
                    <!--<div class="row-fluid form-group">-->
                    <!--    <label name="REV_lbl_nonactveemps" id="REV_lbl_nonactveemps" class="srctitle col-sm-12" hidden>NON ACTIVE EMPLOYEE </label>-->
                    <!--</div>-->
                    <div id="emp_name"></div>
                    <!--<div class="row-fluid form-group">-->
                    <!--    <label class="col-sm-2" name="REP_lbl_loginid" id="REP_lbl_loginid"  hidden>EMPLOYEE NAME<em>*</em></label>-->
                    <!--    <div class="col-sm-5">-->
                    <!--        <select name="REP_lb_loginid" id="REP_lb_loginid" class="form-control" style="display:none">-->
                    <!--        </select>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div><label id="REP_nodata_uld" name="REP_nodata_uld" class="errormsg"></label></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="REP_lbl_strtdtebyrange" id="REP_lbl_strtdtebyrange" hidden>START DATE<em>*</em></label>
                        <div class="col-sm-5">
                            <input type="text" id="REP_tb_strtdtebyrange" name="REP_tb_strtdtebyrange" hidden class='valid REP_datepicker datemandtry' style="width:75px;"/>
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="REP_lbl_enddte" id="REP_lbl_enddte" hidden>END DATE<em>*</em></label>
                        <div class="col-sm-5">
                            <input type="text" id="REP_tb_enddtebyrange" name="REP_tb_enddtebyrange" hidden class='valid REP_datepicker datemandtry' style="width:75px;"/>
                        </div></div>
                    <div class="row-fluid form-group col-sm-8">
                        <input type="button" id="REP_btn_searchdaterange" name="REP_btn_searchdaterange"  value="SEARCH" class="btn"  disabled />
                    </div>
                    <div>
                        <input type="button" class="btn" name="REP_chk_btn_search" id="REP_chk_btn_search"  value="SEARCH" disabled hidden>
                    </div>
                    <div style="padding-left: 22px">
                        <label id="REP_lbl_daterange" name="REP_lbl_daterange"  class="srctitle" hidden></label>
                    </div><br>
                    <div style="padding-left: 22px">
                        <input type="button" id="REP_btn_pdf" class="btnpdf" value="PDF">
                    </div>
                    <div id="REP_tablecontainer_bydaterange" style="padding-left: 29px" class="table-responsive">
                        <section>
                        </section>
                    </div>
                    <label id="REP_lbl_nodata_allactive" name="REP_lbl_nodata_allactive" class="errormsg"></label>
                    <label id="REP_nodata_btwrange" name="REP_nodata_btwrange" class="errormsg"></label>
                    <div id="datarange"></div>
                    <!--<div>-->
                    <!--    <label id="REP_lbl_dteranges" name="REP_lbl_dteranges"  class="srctitle" hidden></label>-->
                    <!--</div><br>-->
                    <div id="pdf"></div>
                    <!--<div>-->
                    <!--    <input type="button" id="REP_btn_pdfs" class="btnpdf" value="PDF"></div>-->
                    <div  id="REP_tablecontainer"  style="max-width:1000px; padding-left: 15px " class="table-responsive" hidden>
                        <sectionid>
                        </sectionid>
                    </div>
                </div>
                <div id="clockmissed" hidden>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="CLK_lbl_reportconfig" id="CLK_lbl_reportconfig" hidden>SEARCH BY<em>*</em></label>
                        <div class="col-sm-4">
                            <select id="CLK_lb_reportconfig" name="CLK_lb_reportconfig" class="form-control" style="display: inline"  hidden>
                            </select>
                        </div>
                    </div>
                    <div><label id="CLK_nodata_rc" name="CLK_nodata_rc" class="errormsg"></label></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="CLK_lbl_selectmnth" id="CLK_lbl_selectmnth" hidden>SELECT MONTH<em>*</em></label>
                        <div class="col-sm-3">
                            <input type="text" name="CLK_db_selectmnth" id="CLK_db_selectmnth" class="date-pickers datemandtry test validation form-control" style="width:110px;" hidden>
                        </div></div>
                    <div style="padding-left: 15px">
                        <input type="button" class="btn" name="CLK_btn_mysearch" id="CLK_btn_mysearch"  value="SEARCH" disabled>
                    </div>
                    <div style="padding-left: 15px"><label id="src_lbl_error" class="srctitle"></label></div>
                    <div style="padding-left: 15px"><input type="button" id="CLK_btn_mnth_pdf" class="btnpdf" value="PDF"></div>
                    <div style="padding-left: 15px"><label id="CLK_nodata_pdflextble" name="CLK_nodata_pdflextble" class="errormsg"></label></div>
                    <div id ="CLK_div_monthyr"  style="max-width:430px; padding-left: 15px" hidden class="table-responsive">
                        <sections>
                        </sections>
                    </div>
                    <div id="CLK_tble_prjctCLKactnonact" hidden>
                        <div class="row-fluid form-group">
                            <div class="radio">
                                <label name="CLK_lbl_actveemp" class="col-sm-8" id="CLK_lbl_actveemp"  hidden>
                                    <div class="col-sm-4">
                                        <input type="radio" name="CLK_rd_actveemp" id="CLK_rd_actveemp" value="EMPLOYEE" hidden>ACTIVE EMPLOYEE</label>
                            </div>
                        </div></div>
                    <div class="row-fluid form-group">
                        <div class="radio">
                            <label name="CLK_lbl_nonactveemp"  class="col-sm-8" id="CLK_lbl_nonactveemp"  hidden>
                                <div class="col-sm-4">
                                    <input type="radio" name="CLK_rd_actveemp" id="CLK_rd_nonemp"   value="EMPLOYEE" class='attnd' hidden>NON ACTIVE EMPLOYEE </label>
                        </div></div>
                </div>
                <div>
                    <label name="CLK_lbl_actveemps" id="CLK_lbl_actveemps" class="srctitle col-sm-12" hidden>ACTIVE EMPLOYEE</label>
                </div>
                <div>
                    <label name="CLK_lbl_nonactveemps" id="CLK_lbl_nonactveemps" class="srctitle col-sm-12" hidden>NON ACTIVE EMPLOYEE </label>
                </div>
        </div>
        <div><label id="CLK_nodata_lgnid" name="CLK_nodata_lgnid" class="errormsg"></label></div>
        <div class="row-fluid form-group" style="padding-top: 15px">
            <label class="col-sm-2" name="CLK_lbl_loginid" id="CLK_lbl_loginid"  hidden>EMPLOYEE NAME<em>*</em></label>
            <div class="col-sm-4">
                <select name="CLK_lb_loginid" class="form-control" id="CLK_lb_loginid" hidden>
                </select>
            </div>
        </div>
        <div class="row-fluid form-group">
            <label class="col-sm-2" name="CLK_lbl_selectmnths" id="CLK_lbl_selectmnths" hidden>SELECT MONTH<em>*</em></label>
            <div class="col-sm-4">
                <input type="text" name="CLK_selectmnths" id="CLK_selectmnths" class="datepickers datemandtry valid" style="width:110px;" hidden>
            </div></div>
        <div style="padding-left: 15px">
            <input type="button" class="btn" name="CLK_btn_search" id="CLK_btn_search"  value="SEARCH" disabled>
        </div>
        <div style="padding-left: 15px"><label id="no_of_days" class="srctitle"></label></div>
        <div style="padding-left: 15px"><label id="src_lbl_error_login" class="srctitle"></label></div>
        <div style="padding-left: 15px"><input type="button" id="CLK_btn_emp_pdf" class="btnpdf" value="PDF"></div>
        <div style="padding-left: 15px"><label id="CLK_nodata_pdflextbles" name="CLK_nodatas_pdflextble" class="errormsg" hidden></label></div>
        <div id ="CLK_div_actvenon_dterange" style="max-width:400px; padding-left: 15px" class="table-responsive">
            <section>
            </section>
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