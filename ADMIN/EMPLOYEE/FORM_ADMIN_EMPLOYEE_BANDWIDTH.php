<!--//*******************************************FILE DESCRIPTION*********************************************//
//*************************************BANDWIDTH****************************************************************//
//DONE BY:LALITHA
//VER 0.07-SD:26/06/2015 ED:26/01/2015,ISSUE CLEARED FOR FORM LOADING PROPERLY PROBLEM ND MONTH/YEAR DP
//DONE BY:SARADAMBAL
//VER 0.06-SD:06/01/2015 ED:06/01/2015,TRACKER NO:74,IMPLEMENTED PRELOADER POSITION,INCLUDE PRELOADER WHILE SETTING MIN AND MAX DATE,CHANGED LOGIN ID INTO EMPLOYEE NAME,SHOWED ERROR MESSAGE FOR NO DATA,REMOVED DP VALIDATION IF DATE IS NULL
//DONE BY: RAJA
//VER 0.05-SD:02/01/2015 ED:02/01/2015, TRACKER NO:166, DESC:IMPLEMENTED PDF BUTTON AND VALIDATED AND GAVE INPUT TO DB
//VER 0.04-SD:05/12/2014 ED:05/12/2014,TRACKER NO:74,IMPLEMENTED HEADER NAME FOR PDF AND DATA TABLE
//DONE BY:LALITHA
//VER 0.03-SD:01/12/2014 ED:01/12/2014,TRACKER NO:74,Changed Preloder funct
//VER 0.02 SD:31/10/2014 ED:31/10/2014,TRACKER NO:97,Updated dt section id while changed other records loaded,Alingned centre the vals
//VER 0.01-INITIAL VERSION, SD:23/10/2014 ED:25/10/2014,TRACKER NO:97
//************************************************************************************************************-->
<?php
include "../../TSLIB/TSLIB_HEADER.php";
?>
<!--HIDE THE CALENDER EVENT FOR DATE PICKER-->
<style type="text/css" xmlns="http://www.w3.org/1999/html">
    .ui-datepicker-calendar {
        display: none;
    }
</style>
<!--SCRIPT TAG START-->
<script>
    //GLOBAL DECLARATION
    var REP_BND_errorAarray=[];
    //READY FUNCTION START
    $(document).ready(function(){
        var errmsg;
        var pdferrmsg;
        $('#REP_BND_nodata_rc').hide();
        $('#REP_BND_btn_mnth_pdf').hide();
        $('#REP_BND_btn_emp_pdf').hide();
        $(".ui-datepicker-calendar").hide();
        var REP_BND_reportconfig_listbx=[];
        var REP_BND_active_emp=[];
        var REP_BND_nonactive_emp=[];
        $(".preloader").show()
        $('#REP_BND_btn_search').hide();
        $('#REP_BND_btn_mysearch').hide();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide()
                var values_array=JSON.parse(xmlhttp.responseText);
                REP_BND_reportconfig_listbx=values_array[0];
                REP_BND_active_emp=values_array[1];
                REP_BND_nonactive_emp=values_array[2];
                REP_BND_errorAarray=values_array[3];
                if(REP_BND_reportconfig_listbx.length!=0){
                    var REP_BND_config_list='<option>SELECT</option>';
                    for (var i=0;i<REP_BND_reportconfig_listbx.length;i++) {
                        REP_BND_config_list += '<option value="' + REP_BND_reportconfig_listbx[i][1] + '">' + REP_BND_reportconfig_listbx[i][0] + '</option>';
                    }
                    $('#REP_BND_lb_reportconfig').html(REP_BND_config_list);
                    $('#REP_BND_lbl_reportconfig').show();
                    $('#REP_BND_lb_reportconfig').show();
                }
                else
                {
                    $('#REP_BND_nodata_rc').text(REP_BND_errorAarray[2]).show();
                }
            }
        }
        var option="common";
        xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_BANDWIDTH.do?option="+option);
        xmlhttp.send();
        //CHANGE FUNCTION FOR BANDWIDTH LISTBX
        $(document).on('change','#REP_BND_lb_reportconfig',function(){
            var formElement = document.getElementById("REP_BND_form_bandwidth");
            var date_val=[];
            $('#REP_BND_db_selectmnth').val('');
            $('#REP_BND_lbl_loginid').hide();
            $('#REP_BND_lb_loginid').hide();
            $('#REP_BND_btn_search').hide();
            $('#REP_BND_lbl_selectmnths').hide();
            $('#REP_BND_db_selectmnths').hide();
            $('#REP_BND_div_monthyr').hide();
            $('#REP_BND_div_actvenon_dterange').hide();
            $('#REP_BND_div_monthyr').hide();
            $('#REP_BND_lbl_actveemps').hide();
            $('#REP_BND_lbl_nonactveemps').hide();
            $('#REP_BND_nodata_pdflextble').hide();
            $('#REV_nodata_pdflextbles').hide();
            $('#REP_BND_nodata_lgnid').hide();
            $('#src_lbl_error').hide();
            $('#REP_BND_btn_mnth_pdf').hide();
            $('#src_lbl_error_login').hide();
            $('#REP_BND_btn_emp_pdf').hide();
            $("#REP_BND_btn_mysearch").attr("disabled","disabled");
            $('input:radio[name=REP_BND_rd_actveemp]').attr('checked',false);
            var option=$("#REP_BND_lb_reportconfig").val();
            if(option=="SELECT")
            {
                $('#REP_BND_lbl_selectmnth').hide();
                $('#REP_BND_db_selectmnth').hide();
                $('#REP_BND_btn_mysearch').hide();
                $('#src_lbl_error').hide();
                $('#REP_BND_btn_mnth_pdf').hide();
                $('#src_lbl_error_login').hide();
                $('#REP_BND_btn_emp_pdf').hide();
                $('#REP_BND_tble_prjctrevactnonact').hide();
            }
            //BANDWIDTH BY MONTH
            else if(option=='11')
            {
                $(".preloader").show();
                //FUNCTION FOR SETTING MIN ND MAX DATE
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        date_val=JSON.parse(xmlhttp.responseText);
                        var REP_BND_start_dates=date_val[0];
                        var REP_BND_end_dates=date_val[1];
                        $(".preloader").hide();
                    }
                    //DATE PICKER FUNCTION START
                    $('#REP_BND_db_selectmnth').datepicker( {
                        changeMonth: true,      //provide option to select Month
                        changeYear: true,       //provide option to select year
                        showButtonPanel: true,   // button panel having today and done button
                        dateFormat: 'MM-yy',    //set date format
                        //ONCLOSE FUNCTION
                        onClose: function(dateText, inst) {
//                        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
//                        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                            var month =inst.selectedMonth;
                            var year = inst.selectedYear;
                            $(this).datepicker('setDate', new Date(year, month, 1));//here set the date when closing.
                            $(this).blur();//remove focus input box
                            $("#REP_BND_btn_mysearch").attr("disabled");
                            dpvalidation()
                        }
                    });
                    //FOCUS FUNCTION
                    $("#REP_BND_db_selectmnth").focus(function () {
                        $(".ui-datepicker-calendar").hide();
                        $("#ui-datepicker-div").position({
                            my: "center top",
                            at: "center bottom",
                            of: $(this)
                        });
                    });
                    if(REP_BND_start_dates!='' &&REP_BND_start_dates !=null){
                        $("#REP_BND_db_selectmnth").datepicker("option","minDate", new Date(REP_BND_start_dates));
                        $("#REP_BND_db_selectmnth").datepicker("option","maxDate", new Date(REP_BND_end_dates));}
                    //VALIDATION FNCTION FOR DATE BX OF BW BY MONTH
                    function dpvalidation(){
                        $('section').html('');
                        $('sections').html('');
                        $('#REP_BND_div_monthyr').hide();
                        $('#src_lbl_error').hide();
                        $('#REP_BND_btn_mnth_pdf').hide();
                        $('#src_lbl_error_login').hide();
                        $('#REP_BND_btn_emp_pdf').hide();
                        $('#REP_BND_nodata_pdflextble').hide();
                        $("#REP_BND_btn_mysearch").attr("disabled","disabled");
                        if($("#REP_BND_db_selectmnth").val()=='')
                        {
                            $("#REP_BND_btn_mysearch").attr("disabled","disabled");
                        }
                        if(($('#REP_BND_db_selectmnth').val()!='undefined')&&($('#REP_BND_db_selectmnth').val()!=''))
                        {
                            $("#REP_BND_btn_mysearch").removeAttr("disabled");
                        }
                        else
                        {
                            $("#REP_BND_btn_mysearch").attr("disabled");
                        }
                    }
                    $('#REP_BND_lbl_selectmnth').show();
                    $('#REP_BND_btn_mysearch').show();
                    $('#REP_BND_db_selectmnth').show();
                    $('#REP_BND_tble_prjctrevactnonact').hide();
                }
                var choice="minmax_dtewth_monthyr";
                xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_BANDWIDTH.do?option="+choice,true);
                xmlhttp.send(new FormData(formElement));
            }
            //BANDWIDTH BY EMPLOYEE
            else if(option=='12')
            {
                $('#REP_BND_tble_prjctrevactnonact').show();
                $('#REP_BND_rd_actveemp').show();
                $('#REP_BND_lbl_actveemp').show();
                $('#REP_BND_rd_nonemp').show();
                $('#REP_BND_lbl_nonactveemp').show();
                $('#REP_BND_lbl_selectmnth').hide();
                $('#REP_BND_btn_mysearch').hide();
                $('#REP_BND_db_selectmnth').hide();
                $('#src_lbl_error').hide();
                $('#REP_BND_btn_mnth_pdf').hide();
                $('#src_lbl_error_login').hide();
                $('#REP_BND_btn_emp_pdf').hide();
            }
        });
        // CLICK EVENT FOR ACTIVE RADIO BUTTON
        $(document).on('click','#REP_BND_rd_actveemp',function(){
            $('#REP_BND_btn_search').hide();
            $('#REP_BND_lbl_selectmnths').hide();
            $('#REP_BND_db_selectmnths').hide();
            $('#REV_nodata_uld').hide();
            $('#REV_nodata_loginid').hide();
            $('#REV_div_nonactve_dterange').hide();
            $('#REV_nodata_startenddate').hide();
            $('#REP_BND_div_actvenon_dterange').hide();
            $('#REP_BND_lbl_nonactveemps').hide();
            $('#REV_nodata_pdflextbles').hide();
            $('#REP_BND_nodata_lgnid').hide();
            $('#src_lbl_error').hide();
            $('#REP_BND_btn_mnth_pdf').hide();
            $('#src_lbl_error_login').hide();
            $('#REP_BND_btn_emp_pdf').hide();
            if(REP_BND_active_emp.length!=0)
            {
                var REP_BND_active_employee='<option>SELECT</option>';
                for (var i=0;i<REP_BND_active_emp.length;i++) {
                    REP_BND_active_employee += '<option value="' + REP_BND_active_emp[i][1] + '">' + REP_BND_active_emp[i][0] + '</option>';
                }
                $('#REP_BND_lb_loginid').html(REP_BND_active_employee);
                $('#REP_BND_lbl_actveemps').show();
                $('#REP_BND_lbl_loginid').show();
                $('#REP_BND_lb_loginid').show();
            }
            else
            {
                $('#REP_BND_nodata_lgnid').text(err_msg_array[0]).show();
            }
        });
        // CLICK EVENT FOR NON ACTIVE RADIO BUTTON
        $(document).on('click','#REP_BND_rd_nonemp',function(){
            $('#REP_BND_db_selectmnths').val('');
            $('#REP_BND_btn_search').hide();
            $('#REP_BND_lbl_selectmnths').hide();
            $('#REP_BND_db_selectmnths').hide();
            $('#REV_nodata_uld').hide();
            $('#REV_nodata_loginid').hide();
            $('#REV_div_nonactve_dterange').hide();
            $('#REV_nodata_startenddate').hide();
            $('#REP_BND_div_actvenon_dterange').hide();
            $('#REP_BND_lbl_actveemps').hide();
            $('#REV_nodata_pdflextbles').hide();
            $('#REP_BND_nodata_lgnid').hide();
            $('#src_lbl_error').hide();
            $('#REP_BND_btn_mnth_pdf').hide();
            $('#src_lbl_error_login').hide();
            $('#REP_BND_btn_emp_pdf').hide();
            if(REP_BND_nonactive_emp.length!=0)
            {
                var REP_BND_nonactive='<option>SELECT</option>';
                for (var i=0;i<REP_BND_nonactive_emp.length;i++) {
                    REP_BND_nonactive += '<option value="' + REP_BND_nonactive_emp[i][1] + '">' + REP_BND_nonactive_emp[i][0] + '</option>';
                }
                $('#REP_BND_lb_loginid').html(REP_BND_nonactive);
                $('#REP_BND_lbl_nonactveemps').show();
                $('#REP_BND_lbl_loginid').show();
                $('#REP_BND_lb_loginid').show();
            }
            else
            {
                $('#REP_BND_nodata_lgnid').text(REP_BND_errorAarray[0]).show();
            }
        });
        // CHANGE EVENT FOR LOGIN ID LIST BX
        $(document).on('change','#REP_BND_lb_loginid',function(){
            var formElement = document.getElementById("REP_BND_form_bandwidth");
            var date_val=[];
            $('#REP_BND_db_selectmnths').val('');
            $('#REP_BND_btn_search').attr("disabled","disabled");
            $('#REP_BND_lbl_selectmnths').hide();
            $('#REP_BND_db_selectmnths').hide();
            $('#REP_BND_div_actvenon_dterange').hide();
            $('#REV_nodata_pdflextbles').hide();
            $('#src_lbl_error').hide();
            $('#REP_BND_btn_mnth_pdf').hide();
            $('#src_lbl_error_login').hide();
            $('#REP_BND_btn_emp_pdf').hide();
            var REP_BND_loginid=$('#REP_BND_lb_loginid').val();
            if($('#REP_BND_lb_loginid').val()=="SELECT")
            {
                $('#REP_BND_btn_search').hide();
                $('#REP_BND_btn_search').attr("disabled","disabled");
                $('#REP_BND_lbl_selectmnths').hide();
                $('#REP_BND_db_selectmnths').hide();
                $('#src_lbl_error').hide();
                $('#REP_BND_btn_mnth_pdf').hide();
                $('#src_lbl_error_login').hide();
                $('#REP_BND_btn_emp_pdf').hide();
            }
            else
            {
                $(".preloader").show();
                //FUNCTION FOR SETTINF MIN ND MAX DATE
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        date_val=JSON.parse(xmlhttp.responseText);
                        var REP_BND_start_dates=date_val[0];
                        var REP_BND_end_dates=date_val[1];
                    }
                    //DATE PICKER FUNCTION
                    $('.date-pickers').datepicker( {
                        changeMonth: true,      //provide option to select Month
                        changeYear: true,       //provide option to select year
                        showButtonPanel: true,   // button panel having today and done button
                        dateFormat: 'MM-yy',    //set date format
                        //ONCLOSE FUNCTION
                        onClose: function(dateText, inst) {
//                        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
//                        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                            var month =inst.selectedMonth;
                            var year = inst.selectedYear;
                            $(this).datepicker('setDate', new Date(year, month, 1));//here set the date when closing.
                            $(this).blur();//remove focus input box
                            $("#REP_BND_btn_search").attr("disabled");
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
                    if(REP_BND_start_dates!=null && REP_BND_start_dates!=''){

                        $('#REP_BND_btn_search').show();
                        $('#REP_BND_lbl_selectmnths').show();
                        $('#REP_BND_db_selectmnths').show();
//                    $('#REP_BND_nodata_lgnid').hide();
                        $('#src_lbl_error_login').hide();
                        $(".date-pickers").datepicker("option","minDate", new Date(REP_BND_start_dates));
                        $(".date-pickers").datepicker("option","maxDate", new Date(REP_BND_end_dates));
                    }
                    else{
                        $('#REP_BND_btn_search').hide();
                        $('#REP_BND_lbl_selectmnths').hide();
                        $('#REP_BND_db_selectmnths').hide();
                        $('#src_lbl_error_login').text(REP_BND_errorAarray[2]).addClass('errormsg').removeClass('srctitle').show();
//                    $('#REP_BND_nodata_lgnid').text(REP_BND_errorAarray[2]).show();
//                    $('#REP_BND_nodata_lgnid')
                    }
                    //VALIDATION FOR DATE BX
                    function validationdp(){
                        $('section').html('');
                        $('sections').html('');
                        $('#REP_BND_div_actvenon_dterange').hide();
                        $('#REV_nodata_pdflextbles').hide();
                        $('#src_lbl_error').hide();
                        $('#REP_BND_btn_mnth_pdf').hide();
                        $('#src_lbl_error_login').hide();
                        $('#REP_BND_btn_emp_pdf').hide();
                        $("#REP_BND_btn_search").attr("disabled","disabled");
                        if($("#REP_BND_db_selectmnths").val()=='')
                        {
                            $("#REP_BND_btn_search").attr("disabled","disabled");
                        }
                        if(($('#REP_BND_db_selectmnths').val()!='undefined')&&($('#REP_BND_db_selectmnths').val()!='')&&($('#REP_BND_lb_loginid').val()!="SELECT"))
                        {
                            $("#REP_BND_btn_search").removeAttr("disabled");
                        }
                        else
                        {
                            $("#REP_BND_btn_search").attr("disabled","disabled");
                        }
                    }

                }
                var choice="minmax_dtewth_loginid";
                xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_BANDWIDTH.do?REP_BND_loginid="+REP_BND_loginid+"&option="+choice,true);
                xmlhttp.send(new FormData(formElement));
            }
        });
        // CLICK EVENT FOR LOGIN ID SEARCH BTN
        var REP_BND_actnon_values=[];
        $(document).on('click','#REP_BND_btn_search',function(){
            $('#REV_nodata_pdflextbles').hide();
            $('#REP_BND_div_actvenon_dterange').hide();
            $('#REP_BND_tble_lgn').html('');
            $('#REP_BND_btn_search').attr("disabled","disabled");
            var REP_BND_monthyear=$('#REP_BND_db_selectmnths').val();
            var REP_BND_loginid=$('#REP_BND_lb_loginid').val();
            $(".preloader").show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var REP_BND_actnon_values=JSON.parse(xmlhttp.responseText);
                    if(REP_BND_actnon_values[0]!=null)
                    {
                        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                        errmsg=REP_BND_errorAarray[4].toString().replace("[MONTH]",REP_BND_monthyear);
                        errmsg=errmsg.replace("[LOGINID]", $("#REP_BND_lb_loginid option:selected").text());
                        $('#src_lbl_error_login').text(errmsg).addClass('srctitle').removeClass('errormsg').show();
                        $('#REP_BND_btn_emp_pdf').show();
                        var loginname;
                        var loginpos=REP_BND_loginid.search("@");
                        if(loginpos>0){
                            loginname=REP_BND_loginid.substring(0,loginpos);
                        }
                        pdferrmsg=errmsg;
                        var REP_BND_reportdate= REP_BND_actnon_values[0];
                        var total= REP_BND_actnon_values[1];
                        var REP_BND_table_header='<table id="REP_BND_tble_lgn" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>REPORT DATE</th><th style="text-align: center">BANDWIDTH</th></tr></thead><tfoot><tr> <th colspan="1" style="text-align:right">TOTAL:</th><th></th></tr></tfoot><tbody>'
                        for(var i=0;i<REP_BND_reportdate.length;i++){
                            var REP_BND_reportdte=REP_BND_reportdate[i].REP_BND_rptdte;
                            var REP_BND_bandwidthmb=REP_BND_reportdate[i].REP_BND_bndwdth;
                            REP_BND_table_header+='<tr><td>'+REP_BND_reportdte+'</td><td>'+REP_BND_bandwidthmb+'</td></tr>';
                        }
                        REP_BND_table_header+='</tbody></table>';
                        $('section').html(REP_BND_table_header);
                        $('#REP_BND_tble_lgn').DataTable({
                            //FOOTER FUNCTION
                            "footerCallback": function ( row, data, start, end, display ) {
                                var api = this.api(), data;
                                if(REP_BND_reportdate.length==1){
                                    $( api.column( 1 ).footer() ).html(
                                        total.REP_BND_bndwdth
                                    );}else{
                                    // Remove the formatting to get integer data for summation
                                    var intVal = function ( i ) {
                                        return typeof i === 'string' ?
                                        i.replace(/[\$,]/g, '')*1 :
                                            typeof i === 'number' ?
                                                i : 0;
                                    };
                                    // Total over all pages
                                    data = api.column( 1 ).data();
                                    total = data.length ?
                                        data.reduce( function (a, b) {
                                            return intVal(a) + intVal(b);
                                        } ) :
                                        0;
                                    // Total over this page
                                    data = api.column( 1, { page: 'current'} ).data();
                                    pageTotal = data.length ?
                                        data.reduce( function (a, b) {
                                            return intVal(a) + intVal(b);
                                        } ) :
                                        0;
                                    // Update footer
                                    var pt=pageTotal.toFixed(2)
                                    var n=total.toFixed(2)
                                    $( api.column( 1 ).footer() ).html(
                                        +pt +' ('+ n +' total)'
                                    );
                                }}
                        });
                    }
                    else
                    {
                        var sd=REP_BND_errorAarray[1].toString().replace("[DATE]",REP_BND_monthyear);
                        $('#REV_nodata_pdflextbles').show();
                        $('#REV_nodata_pdflextbles').text(sd);
                        $('#REP_BND_div_actvenon_dterange').hide();
                    }
                }
            }
            $('#REP_BND_div_actvenon_dterange').show();
            var option="REP_BND_loginid_searchoption";
            xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_BANDWIDTH.do?option="+option+"&REP_BND_loginid="+REP_BND_loginid+"&REP_BND_monthyear="+REP_BND_monthyear);
            xmlhttp.send();
        });
        // CLICK EVENT FOR MONTH ND YEAR SEARCH BTN
        var REP_BND_monthyr_values=[];
        $(document).on('click','#REP_BND_btn_mysearch',function(){
            $('#REP_BND_nodata_pdflextble').hide();
            $('#REP_BND_div_monthyr').hide();
            $('#REP_BND_tble_bw').html('');
            $('#src_lbl_error').hide();
            $('#REP_BND_btn_mnth_pdf').hide();
            $('#src_lbl_error_login').hide();
            $('#REP_BND_btn_emp_pdf').hide();
            $('#REP_BND_btn_mysearch').attr("disabled","disabled");
            var REP_BND_monthyear=$('#REP_BND_db_selectmnth').val();
            $(".preloader").show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var REP_BND_monthyr_values=JSON.parse(xmlhttp.responseText);
                    if(REP_BND_monthyr_values[0]!='' && REP_BND_monthyr_values[0]!=null)
                    {
                        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                        errmsg=REP_BND_errorAarray[3].toString().replace("[MONTH]",REP_BND_monthyear);
                        $('#src_lbl_error').text(errmsg).show();
                        $('#REP_BND_btn_mnth_pdf').show();
                        var REP_BND_userbndwdth= REP_BND_monthyr_values[0];
                        var total= REP_BND_monthyr_values[1];
                        var REP_BND_table_header='<table id="REP_BND_tble_bw" border="1"  cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white"><tr><th  style="max-width: 20px">EMPLOYEE NAME</th><th style="max-width: 20px">BANDWIDTH</th></tr></thead><tfoot><tr> <th colspan="1">TOTAL:</th><th></th></tr></tfoot><tbody>'
                        for(var i=0;i<REP_BND_userbndwdth.length;i++){
                            var REP_BND_loginid=REP_BND_userbndwdth[i].REP_BND_lgnid;
                            var REP_BND_bandwidthmb=REP_BND_userbndwdth[i].REP_BND_bndwdth;
                            REP_BND_table_header+='<tr><td>'+REP_BND_loginid+'</td><td align="center">'+REP_BND_bandwidthmb+'</td></tr>';
                        }
                        REP_BND_table_header+='</tbody></table>';
                        $('sections').html(REP_BND_table_header);
                        $('#REP_BND_tble_bw').DataTable({
                            //FOOTER FUNCTION
                            "footerCallback": function ( row, data, start, end, display ) {
                                var api = this.api(), data;
                                if(REP_BND_userbndwdth.length==1){
                                    $( api.column( 1 ).footer() ).html(
                                        total.REP_BND_bndwdth
                                    );}else{
                                    // Remove the formatting to get integer data for summation
                                    var intVal = function ( i ) {
                                        return typeof i === 'string' ?
                                        i.replace(/[\$,]/g, '')*1 :
                                            typeof i === 'number' ?
                                                i : 0;
                                    };
                                    // Total over all pages
                                    data = api.column( 1 ).data();
                                    total = data.length ?
                                        data.reduce( function (a, b) {
                                            return intVal(a) + intVal(b);
                                        } ) :
                                        0;
                                    // Total over this page
                                    data = api.column( 1, { page: 'current'} ).data();
                                    pageTotal = data.length ?
                                        data.reduce( function (a, b) {
                                            return intVal(a) + intVal(b);
                                        } ) :
                                        0;
                                    // Update footer
                                    var pt=pageTotal.toFixed(2)
                                    var n=total.toFixed(2)
                                    $( api.column( 1 ).footer() ).html(
                                        +pt +' ('+ n +' total)'
                                    );
                                }}
                        });
                    }
                    else
                    {
                        var sd=REP_BND_errorAarray[1].toString().replace("[DATE]",REP_BND_monthyear);
                        $('#REP_BND_nodata_pdflextble').text(sd).show();
                        $('#REP_BND_div_monthyr').hide();
                    }
                }
            }
            $('#REP_BND_div_monthyr').show();
            var option="REP_BND_monthyear_searchoption";
            xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_BANDWIDTH.do?option="+option+"&REP_BND_db_selectmnth="+REP_BND_monthyear);
            xmlhttp.send();
        });
        //CLICK EVENT FOR PDF BUTTON
        $(document).on('click','#REP_BND_btn_mnth_pdf',function(){
            var inputValOne=$("#REP_BND_db_selectmnth").val();
            var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=14&inputValOne='+inputValOne+'&title='+errmsg;
        });
        $(document).on('click','#REP_BND_btn_emp_pdf',function(){
            var inputValOne=$("#REP_BND_db_selectmnths").val();
            var inputValTwo=$('#REP_BND_lb_loginid').val();
            var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=15&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&title='+pdferrmsg;
        });
    });
    <!--SCRIPT TAG END-->
</script>
<!--BODY TAG START-->
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="title text-center"><h4><b>BANDWIDTH</b></h4></div>
    <form   id="REP_BND_form_bandwidth"  class="form-horizontal content" role="form" >
        <div class="panel-body">
            <fieldset>
                <div class="row-fluid form-group">
                    <label name="REP_BND_lbl_reportconfig" class="col-sm-3" id="REP_BND_lbl_reportconfig">BANDWIDTH<em>*</em></label>
                    <div class="col-sm-4">
                        <select id="REP_BND_lb_reportconfig" name="REP_BND_lb_reportconfig" class="form-control">
                        </select>
                    </div></div>

                <div><label id="REP_BND_nodata_rc" name="REP_BND_nodata_rc" class="errormsg"></label></div>

                <div class="row-fluid form-group">
                    <label name="REP_BND_lbl_selectmnth"  class="col-sm-3" id="REP_BND_lbl_selectmnth" hidden>SELECT MONTH<em>*</em></label>
                    <div class="col-sm-4">
                        <input type="text" name="REP_BND_db_selectmnth" id="REP_BND_db_selectmnth" class="date-picker datemandtry validation" style="width:75px;" hidden>
                    </div></div>

                <div class="row-fluid form-group">
                    <label name="REP_BND_lbl_selectmnth"  class="col-sm-3" id="REP_BND_lbl_selectmnth" hidden>SELECT MONTH<em>*</em></label>
                    <div class="col-sm-4">
                        <input type="text" name="REP_BND_db_selectmnth" id="REP_BND_db_selectmnth" class="date-picker datemandtry validation" style="width:75px;" hidden>
                    </div></div>

                <div>
                    <input type="button" class="btn" name="REP_BND_btn_mysearch" id="REP_BND_btn_mysearch"  value="SEARCH" disabled>
                </div>

                <div>
                    <label id="src_lbl_error" class="srctitle"></label>
                </div>

                <div><input type="button" id="REP_BND_btn_mnth_pdf" class="btnpdf" value="PDF"></div>
                <div><label id="REP_BND_nodata_pdflextble" name="REP_BND_nodata_pdflextble" class="errormsg"></label></div>

                <div id ="REP_BND_div_monthyr" class="table-responsive" style="max-width: 400px" hidden>
                    <sections>
                    </sections>
                </div>

                <div id="REP_BND_tble_prjctrevactnonact" hidden>
                    <div class="row-fluid form-group">
                        <div class="radio">
                            <label name="REP_BND_lbl_actveemp" class="col-sm-8" id="REP_BND_lbl_actveemp"  hidden>
                                <div class="col-sm-4">
                                    <input type="radio" name="REP_BND_rd_actveemp" id="REP_BND_rd_actveemp" value="EMPLOYEE" hidden >ACTIVE EMPLOYEE</label>
                        </div></div></div>

                <div class="row-fluid form-group">
                    <div class="radio">
                        <label name="REP_BND_lbl_nonactveemp" class="col-sm-8" id="REP_BND_lbl_nonactveemp"  hidden>
                            <div class="col-sm-4">
                                <input type="radio" name="REP_BND_rd_actveemp"  id="REP_BND_rd_nonemp"   value="EMPLOYEE" class='attnd' hidden>NON ACTIVE EMPLOYEE </label>
                    </div></div>
        </div>
        <div class="row-fluid form-group">
            <label name="REP_BND_lbl_actveemps" id="REP_BND_lbl_actveemps" class="srctitle col-sm-12" hidden>ACTIVE EMPLOYEE</label>
        </div>
        <div class="row-fluid form-group">
            <label name="REP_BND_lbl_nonactveemps" id="REP_BND_lbl_nonactveemps" class="srctitle col-sm-12" hidden>NON ACTIVE EMPLOYEE </label>
        </div>
</div>

<div><label id="REP_BND_nodata_lgnid" name="REP_BND_nodata_lgnid" class="errormsg"></label>
    <div class="row-fluid form-group">
        <label name="REP_BND_lbl_loginid"  class="col-sm-3" id="REP_BND_lbl_loginid" hidden>EMPLOYEE NAME<em>*</em></label>
        <div class="col-sm-4">
            <select name="REP_BND_lb_loginid" id="REP_BND_lb_loginid" class="form-control" style="display:none">
            </select>
        </div>
    </div></div>

<div class="row-fluid form-group">
    <label name="REP_BND_lbl_selectmnths" class="col-sm-3" id="REP_BND_lbl_selectmnths" hidden>SELECT MONTH<em>*</em></label>
    <div class="col-sm-3">
        <input type="text" name="REP_BND_db_selectmnths" id="REP_BND_db_selectmnths" class="date-pickers datemandtry valid" style="width: 100px" hidden>
    </div></div>

<div>
    <input type="button" class="btn" name="REP_BND_btn_search" id="REP_BND_btn_search"  value="SEARCH" disabled>
</div>

<div>
    <label id="src_lbl_error_login"></label>
</div>

<div><input type="button" id="REP_BND_btn_emp_pdf" class="btnpdf" value="PDF"></div>

<div><label id="REV_nodata_pdflextbles" name="REP_BND_nodatas_pdflextble" class="errormsg"></label></div>

<div id ="REP_BND_div_actvenon_dterange"class="table-responsive"  style="max-width: 500px" hidden>
    <section>
    </section>
</div>

</fieldset>
</div>
</form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->