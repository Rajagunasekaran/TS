<!--//*******************************************FILE DESCRIPTION*********************************************//
//*************************************CLOCK OUT MISSED DETAILS****************************************************************//
//VER 0.01-INITIAL VERSION, SD:20/02/2015 ED:21/02/2015,TRACKER NO:90
//************************************************************************************************************-->
<?php
include "HEADER.php";
?>
<!--HIDE THE CALENDER EVENT FOR DATE PICKER-->
<style type="text/css">
    .ui-datepicker-calendar {
        display: none;
    }
</style>
<!--SCRIPT TAG START-->
<script>
//GLOBAL DECLARATION
var CLK_errorAarray=[];
//READY FUNCTION START
$(document).ready(function(){
    var errmsg;
    var pdferrmsg;
    var msg;
    var CLK_reportdte;
    $('#CLK_nodata_rc').hide();
    $('#CLK_btn_mnth_pdf').hide();
    $('#CLK_btn_emp_pdf').hide();
    $(".ui-datepicker-calendar").hide();
    var CLK_reportconfig_listbx=[];
    var CLK_active_emp=[];
    var CLK_nonactive_emp=[];
    $('.preloader', window.parent.document).show()
    $('#CLK_btn_search').hide();
    $('#CLK_btn_mysearch').hide();
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader', window.parent.document).hide()
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
    xmlhttp.open("GET","DB_REPORT_CLOCK_OUT_MISSED_DETAILS.do?option="+option);
    xmlhttp.send();
    //CHANGE FUNCTION FOR BANDWIDTH LISTBX
    $(document).on('change','#CLK_lb_reportconfig',function(){
        var formElement = document.getElementById("CLK_form_bandwidth");
        var date_val=[];
        $('#CLK_db_selectmnth').val('');
        $('#CLK_lbl_loginid').hide();
        $('#CLK_lb_loginid').hide();
        $('#CLK_btn_search').hide();
        $('#CLK_lbl_selectmnths').hide();
        $('#CLK_db_selectmnths').hide();
        $('#CLK_div_monthyr').hide();
        $('#CLK_div_actvenon_dterange').hide();
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
            $('.preloader', window.parent.document).show();
            //FUNCTION FOR SETTING MIN ND MAX DATE
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
                    date_val=JSON.parse(xmlhttp.responseText);
                    var CLK_start_dates=date_val[0];
                    var CLK_end_dates=date_val[1];
                    $('.preloader', window.parent.document).hide();
                }
                //DATE PICKER FUNCTION START
                $('#CLK_db_selectmnth').datepicker( {
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
                        $("#CLK_btn_mysearch").attr("disabled");
                        dpvalidation()
                    }
                });
                //FOCUS FUNCTION
                $("#CLK_db_selectmnth").focus(function () {
                    $(".ui-datepicker-calendar").hide();
                    $("#ui-datepicker-div").position({
                        my: "center top",
                        at: "center bottom",
                        of: $(this)
                    });
                });
                if(CLK_start_dates!='' &&CLK_start_dates !=null){
                    $("#CLK_db_selectmnth").datepicker("option","minDate", new Date(CLK_start_dates));
                    $("#CLK_db_selectmnth").datepicker("option","maxDate", new Date(CLK_end_dates));}
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
            xmlhttp.open("GET","DB_REPORT_CLOCK_OUT_MISSED_DETAILS.do?option="+choice,true);
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
        $('#CLK_lbl_selectmnths').hide();
        $('#CLK_db_selectmnths').hide();
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
        $('#CLK_db_selectmnths').val('');
        $('#CLK_btn_search').hide();
        $('#CLK_lbl_selectmnths').hide();
        $('#CLK_db_selectmnths').hide();
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
    $(document).on('change','#CLK_lb_loginid',function(){
        var formElement = document.getElementById("CLK_form_bandwidth");
        var date_val=[];
        $('#CLK_db_selectmnths').val('');
        $('#CLK_btn_search').attr("disabled","disabled");
        $('#CLK_lbl_selectmnths').hide();
        $('#CLK_db_selectmnths').hide();
        $('#CLK_div_actvenon_dterange').hide();
        $('#CLK_nodata_pdflextbles').hide();
        $('#src_lbl_error').hide();
        $('#CLK_btn_mnth_pdf').hide();
        $('#src_lbl_error_login').hide();
        $('#no_of_days').hide();
        $('#CLK_btn_emp_pdf').hide();
        var CLK_loginid=$('#CLK_lb_loginid').val();
        if($('#CLK_lb_loginid').val()=="SELECT")
        {
            $('#CLK_btn_search').hide();
            $('#CLK_btn_search').attr("disabled","disabled");
            $('#CLK_lbl_selectmnths').hide();
            $('#CLK_db_selectmnths').hide();
            $('#src_lbl_error').hide();
            $('#CLK_btn_mnth_pdf').hide();
            $('#src_lbl_error_login').hide();
            $('#no_of_days').hide();
            $('#CLK_btn_emp_pdf').hide();
        }
        else
        {
            $('.preloader', window.parent.document).show();
            //FUNCTION FOR SETTINF MIN ND MAX DATE
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
                    date_val=JSON.parse(xmlhttp.responseText);
                    var CLK_start_dates=date_val[0];
                    var CLK_end_dates=date_val[1];
                }
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
                if(CLK_start_dates!=null && CLK_start_dates!=''){

                    $('#CLK_btn_search').show();
                    $('#CLK_lbl_selectmnths').show();
                    $('#CLK_db_selectmnths').show();
//                    $('#CLK_nodata_lgnid').hide();
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

            }
            var choice="minmax_dtewth_loginid";
            xmlhttp.open("GET","DB_REPORT_CLOCK_OUT_MISSED_DETAILS.do?CLK_loginid="+CLK_loginid+"&option="+choice,true);
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
        var CLK_monthyear=$('#CLK_db_selectmnths').val();
        var CLK_loginid=$('#CLK_lb_loginid').val();
        $('.preloader', window.parent.document).show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
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
        xmlhttp.open("GET","DB_REPORT_CLOCK_OUT_MISSED_DETAILS.do?option="+option+"&CLK_loginid="+CLK_loginid+"&CLK_monthyear="+CLK_monthyear);
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
        $('.preloader', window.parent.document).show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
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
        xmlhttp.open("GET","DB_REPORT_CLOCK_OUT_MISSED_DETAILS.do?option="+option+"&CLK_db_selectmnth="+CLK_monthyear);
        xmlhttp.send();
    });
    //CLICK EVENT FOR PDF BUTTON
    $(document).on('click','#CLK_btn_mnth_pdf',function(){
        var inputValOne=$('#CLK_db_selectmnth').val();
        var url=document.location.href='COMMON_PDF.do?flag=26&inputValOne='+inputValOne+'&title='+msg;
    });
    $(document).on('click','#CLK_btn_emp_pdf',function(){
        var inputValOne=$("#CLK_db_selectmnths").val();
        var inputValThree =$('#CLK_lb_loginid').val();
        var url=document.location.href='COMMON_PDF.do?flag=27&inputValOne='+inputValOne+'&inputValThree='+inputValThree+'&title='+errmsg;
    });
});
<!--SCRIPT TAG END-->
</script>
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title"><div style="padding-left:500px; text-align:left;" ><p><h3>CLOCK OUT MISSED DETAILS</h3><p></div></div>
    <form   id="CLK_form_bandwidth" class="content" >
        <table>
            <table>
                <tr>
                    <td width="150"><label name="CLK_lbl_reportconfig" id="CLK_lbl_reportconfig" hidden>SEARCH BY<em>*</em></label></td>
                    <td width="150">
                        <select id="CLK_lb_reportconfig" name="CLK_lb_reportconfig" hidden>
                        </select>
                    </td>
                </tr>
            </table>
            <tr><td><label id="CLK_nodata_rc" name="CLK_nodata_rc" class="errormsg"></label></td></tr>
            <table>
                <tr>
                    <td width="150"><label name="CLK_lbl_selectmnth" id="CLK_lbl_selectmnth" hidden>SELECT MONTH<em>*</em></label></td>
                    <td><input type="text" name="CLK_db_selectmnth" id="CLK_db_selectmnth" class="date-picker datemandtry validation" style="width:110px;" hidden></td><br>
                </tr>
                <tr>
                    <td><input type="button" class="btn" name="CLK_btn_mysearch" id="CLK_btn_mysearch"  value="SEARCH" disabled></td>
                </tr>
            </table>
            <div>
                <label id="src_lbl_error" class="srctitle"></label>
            </div>
            <div><input type="button" id="CLK_btn_mnth_pdf" class="btnpdf" value="PDF"></div>
            <div><label id="CLK_nodata_pdflextble" name="CLK_nodata_pdflextble" class="errormsg"></label></div>
            <div id ="CLK_div_monthyr" class="container" style="width:500px" hidden>
                <sections style="width:500px;">
                </sections>
            </div>
            <table id="CLK_tble_prjctCLKactnonact" hidden>
                <tr>
                    <td><input type="radio" name="CLK_rd_actveemp" id="CLK_rd_actveemp" value="EMPLOYEE" hidden >
                        <label name="CLK_lbl_actveemp" id="CLK_lbl_actveemp"  hidden>ACTIVE EMPLOYEE</label></td>
                </tr>
                <tr>
                    <td><input type="radio" name="CLK_rd_actveemp" id="CLK_rd_nonemp"   value="EMPLOYEE" class='attnd' hidden>
                        <label name="CLK_lbl_nonactveemp" id="CLK_lbl_nonactveemp"  hidden>NON ACTIVE EMPLOYEE </label></td>
                </tr>
                <tr>
                    <td><label name="CLK_lbl_actveemps" id="CLK_lbl_actveemps" class="srctitle" hidden>ACTIVE EMPLOYEE</label></td>
                </tr>
                <tr>
                    <td><label name="CLK_lbl_nonactveemps" id="CLK_lbl_nonactveemps" class="srctitle" hidden>NON ACTIVE EMPLOYEE </label></td>
                </tr>
            </table>
            <tr><td><label id="CLK_nodata_lgnid" name="CLK_nodata_lgnid" class="errormsg"></label></td></tr>
            <table>
                <tr><td width="150">
                        <label name="CLK_lbl_loginid" id="CLK_lbl_loginid"  hidden>EMPLOYEE NAME<em>*</em></label></td>
                    <td>
                        <select name="CLK_lb_loginid" id="CLK_lb_loginid" hidden>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="150"><label name="CLK_lbl_selectmnths" id="CLK_lbl_selectmnths" hidden>SELECT MONTH<em>*</em></label></td>
                    <td><input type="text" name="CLK_db_selectmnths" id="CLK_db_selectmnths" class="date-pickers datemandtry valid" style="width:110px;" hidden></td><br>
                </tr>
                <tr>
                    <td><input type="button" class="btn" name="CLK_btn_search" id="CLK_btn_search"  value="SEARCH" disabled></td>
                </tr>
            </table>
            <div>
                <label id="no_of_days" class="srctitle"></label>
            </div>
            <div>
                <label id="src_lbl_error_login" class="srctitle"></label>
            </div>
            <div><input type="button" id="CLK_btn_emp_pdf" class="btnpdf" value="PDF"></div>
            <tr><td><label id="CLK_nodata_pdflextbles" name="CLK_nodatas_pdflextble" class="errormsg" hidden></label></td></tr>
            <div id ="CLK_div_actvenon_dterange" class="container" style="width:300px" hidden>
                <section style="width:400px;">
                </section>
            </div>
        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->
	
