

<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************DAILY REPORTS ADMIN SEARCH UPDATE DELETE***********************************//
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
include "HEADER.php";
?>
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
    $('.preloader', window.parent.document).show();
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
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader', window.parent.document).hide();
//            alert(xmlhttp.responseText);
            var value_array=JSON.parse(xmlhttp.responseText);
            permission_array=value_array[0];
//            project_array=value_array[1];
            allmindate=value_array[2];
            err_msg=value_array[3];
            if(allmindate=='01-01-1970')
            {
                $('#ASRC_UPD_DEL_form_adminsearchupdate').replaceWith('<p><label class="errormsg">'+err_msg[10]+'</label></p>');
            }
            else
            {
//                $("#ASRC_UPD_DEL_lb_attendance option[value='2']").detach();
                active_emp=value_array[5];
                nonactive_emp=value_array[6];
                allmaxdate=value_array[7];
                odmindate=value_array[8];
                odmaxdate=value_array[9];
                userstamp=value_array[10];
//                flag=value_array[10];
//                alert(flag);
//                if(flag == 'X')
//                {
//                    $('#ASRC_UPD_DEL_lb_attendance').append("<option value='2'>WORK FROM HOME</option>")
//                }
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
    xmlhttp.open("GET","COMMON.do?option="+option);
    xmlhttp.send();
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
    $('#ASRC_UPD_DEL_tb_dte').datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    // CHANGE EVENT FOR STARTDATE
    $(document).on('change','#ASRC_UPD_DEL_tb_strtdte',function(){
        $('#ASRC_UPD_DEL_div_header').hide();
        $('#ASRC_UPD_btn_pdf').hide();
        $('#ASRC_UPD_DEL_div_headers').hide();
        $('#ASRC_UPD_btn_od_pdf').hide();
        var ASRC_UPD_DEL_startdate = $('#ASRC_UPD_DEL_tb_strtdte').datepicker('getDate');
        var date = new Date( Date.parse( ASRC_UPD_DEL_startdate ));
        date.setDate( date.getDate()  );
        var ASRC_UPD_DEL_todate = date.toDateString();
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
        $('.preloader', window.parent.document).show();
        $("#ASRC_UPD_DEL_btn_allsearch").attr("disabled", "disabled");
        $('section').html('')
        $('#ASRC_UPD_DEL_div_tablecontainer').hide();
        $('#ASRC_UPD_DEL_tbl_htmltable').show();
        var date=$('#ASRC_UPD_DEL_tb_dte').val();
        var activeloginid=$('#ASRC_UPD_DEL_lb_loginid').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                allvalues_array=JSON.parse(xmlhttp.responseText);
                if(allvalues_array!=''){
                    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
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
        xmlhttp.open("GET","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?alldate="+date+"&option="+choice,true);
        xmlhttp.send();
        sorting()
    });
    //CHANGE EVENT FOR BETWEEN RANGE RADIO BTN
    $(document).on('change','#ASRC_UPD_DEL_rd_btwnrange',function(){
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
    $('.enable').change(function(){
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
    $('#ASRC_UPD_DEL_tb_dte').change(function(){
        $('#ASRC_UPD_DEL_errmsg').hide();
        $('#ASRC_UPD_DEL_tbl_htmltable').html('');
        $('#ASRC_UPD_DEL_div_tablecontainer').hide();
        $('#ASRC_UPD_DEL_div_header').hide();
        $('#ASRC_UPD_btn_pdf').hide();
        $('#ASRC_UPD_DEL_div_headers').hide();
        $('#ASRC_UPD_btn_od_pdf').hide();
    });
    //CHANGE EVENT FOR NON ACTIVE  RADIO
    $(document).on('change','#ASRC_UPD_DEL_rd_nonactveemp',function(){
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
        $("#ASRC_UPD_DEL_btn_allsearch").attr("disabled", "disabled");
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
    // CHANGE EVENT FOR LOGINID LISTBOX
    $(document).on('change','#ASRC_UPD_DEL_lb_loginid',function(){
        ASRC_UPD_DEL_clear();
        $('#ASRC_UPD_DEL_btn_srch').hide();
        $('#ASRC_UPD_DEL_btn_del').hide();
        $('#ASRC_UPD_DEL_errmsg').hide();
        $('#ASRC_UPD_DEL_lbl_reportdte').hide();
        $('#ASRC_UPD_DEL_errmsg').hide();
        $('#ASRC_UPD_DEL_ta_reportdate').hide();
        $('#ASRC_UPD_DEL_div_tablecontainer').hide();
        $('#ASRC_UPD_DEL_div_header').hide();
        $('#ASRC_UPD_btn_pdf').hide();
        $('#ASRC_UPD_DEL_div_headers').hide();
        $('#ASRC_UPD_btn_od_pdf').hide();
        $("#ASRC_UPD_DEL_chk_flag").hide();
        $("#ASRC_UPD_DEL_lbl_flag").hide();
        $('#ASRC_UPD_DEL_banerrmsg').hide();
    });
    //CHANGE EVENT FOR BETWEN ACTIVE EMPLOYEE RADIO BTN
    $(document).on('change','#ASRC_UPD_DEL_rd_actveemp',function(){
        if($('#ASRC_UPD_DEL_rd_actveemp').attr('checked',true))
        {
            var active_employee='<option>SELECT</option>';
            for (var i=0;i<active_emp.length;i++) {
                active_employee += '<option value="' + active_emp[i][1] + '">' + active_emp[i][0] + '</option>';
            }
            $('#ASRC_UPD_DEL_lb_loginid').html(active_employee);
        }
        $('#ASRC_UPD_DEL_lbl_loginid').show();
        $('#ASRC_UPD_DEL_lb_loginid').val('SELECT').show();
        $('#ASRC_UPD_DEL_lbl_btwnranges').show();
        ASRC_UPD_DEL_clear()
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
    $(document).on('change','#ASRC_UPD_DEL_lb_loginid',function(){
        var ASRC_UPD_DEL_loginidlist =$("#ASRC_UPD_DEL_lb_loginid").val();
//        alert(ASRC_UPD_DEL_loginidlist)
        $('#ASRC_UPD_DEL_errmsg').hide();
        if(ASRC_UPD_DEL_loginidlist=='SELECT')
        {
//            alert('if')
            $('#ASRC_UPD_DEL_lbl_session').hide();
            $('#ASRC_UPD_DEL_lbl_strtdte').hide();
            $('#ASRC_UPD_DEL_tb_strtdte').hide();
            $('#ASRC_UPD_DEL_lbl_enddte').hide();
            $('#ASRC_UPD_DEL_tb_enddte').hide();
            $('#ASRC_UPD_DEL_btn_search').hide();
            $('#ASRC_UPD_DEL_tb_strtdte').val('').hide();
            $('#ASRC_UPD_DEL_tb_enddte').val('').hide();
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
            $('#ASRC_UPD_DEL_chk_permission').hide();
            $('#ASRC_UPD_DEL_lb_timing').hide();
            $('#ASRC_UPD_DEL_lbl_band').hide();
            $('#ASRC_UPD_DEL_tb_band').hide();
            ASRC_UPD_DEL_clear()
        }
        else
        {
            $('.preloader', window.parent.document).show();
            $("#ASRC_UPD_DEL_lb_attendance option[value='2']").detach();
            var min_date;
            var loginid=$('#ASRC_UPD_DEL_lb_loginid').val();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
//                    alert(xmlhttp.responseText)
                    var finaldate=JSON.parse(xmlhttp.responseText);
                    min_date=finaldate[0];
                    var max_date=finaldate[1];
                    var rprt_min_date=finaldate[2];
                    project_array=finaldate[3];
                    flag=finaldate[4];
                    if(flag == 'X')
                    {
                        $('#ASRC_UPD_DEL_lb_attendance').append("<option value='2'>WORK FROM HOME</option>")
                    }
                    $('#ASRC_UPD_DEL_lbl_session').hide();
                    if(min_date=='01-01-1970')
                    {
                        $('#ASRC_UPD_DEL_errmsg').replaceWith('<p><label class="errormsg" id="ASRC_UPD_DEL_errmsg">'+ err_msg[10] +'</label></p>');
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
                    $('#ASRC_UPD_DEL_chk_permission').hide();
                    $('#ASRC_UPD_DEL_lb_timing').hide();
                    $('#ASRC_UPD_DEL_lbl_band').hide();
                    $('#ASRC_UPD_DEL_tb_band').hide();

                    $('#ASRC_UPD_DEL_tb_enddte').datepicker("option","maxDate",max_date);
                    $('#ASRC_UPD_DEL_tb_strtdte').datepicker("option","minDate",min_date);
                    $('#ASRC_UPD_DEL_tb_strtdte').datepicker("option","maxDate",max_date);
                    $('#ASRC_UPD_DEL_ta_reportdate').datepicker("option","minDate",rprt_min_date);
                    $('#ASRC_UPD_DEL_ta_reportdate').datepicker("option","maxDate",rprt_max_date);
                }
            }
            var choice="login_id";
            xmlhttp.open("GET","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?login_id="+loginid+"&option="+choice,true);
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
        $('.preloader', window.parent.document).show();
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
        var start_date=$('#ASRC_UPD_DEL_tb_strtdte').val();
        var end_date=$('#ASRC_UPD_DEL_tb_enddte').val();
        var activeloginid=$('#ASRC_UPD_DEL_lb_loginid').val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                $('.preloader', window.parent.document).hide();
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
        xmlhttp.open("POST","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?start_date="+start_date+"&end_date="+end_date+"&option="+choice+"&actionloginid="+activeloginid,true);
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
        $('.preloader', window.parent.document).show();
        var delid=$("input[name=ASRC_UPD_DEL_rd_flxtbl]:checked").val();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                var delete_msg=xmlhttp.responseText;
                if(delete_msg==1)
                {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH/UPDATE/DELETE",msgcontent:err_msg[2],position:{top:150,left:500}}});
                    show_msgbox("ADMIN REPORT ENTRY",err_msg[0],"success",false);
                    flextablerange()
                    $('#ASRC_UPD_DEL_btn_del').hide();
                    $('#ASRC_UPD_DEL_btn_srch').hide();
                }
                else if(delete_msg==0)
                {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH/UPDATE/DELETE",msgcontent:err_msg[5],position:{top:150,left:500}}});
                    show_msgbox("ADMIN REPORT ENTRY",err_msg[5],"success",false);
                    flextablerange()
                    $('#ASRC_UPD_DEL_btn_del').hide();
                    $('#ASRC_UPD_DEL_btn_srch').hide();
                }
                else
                {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH/UPDATE/DELETE",msgcontent:delete_msg,position:{top:150,left:500}}});
                    show_msgbox("ADMIN REPORT ENTRY",delete_msg,"success",false);
                    flextablerange()
                    $('#ASRC_UPD_DEL_btn_del').hide();
                    $('#ASRC_UPD_DEL_btn_srch').hide();
                }
            }
        }
        var choice="DELETE";
        xmlhttp.open("GET","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?del_id="+delid+"&option="+choice,true);
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
                        "<select id='ASRC_UPD_DEL_lb_attendance' name='ASRC_UPD_DEL_lb_attendance' class='update_validate'> <option value='2'>WORK FROM HOME</option> </select>");
                }
                else
                {
                    $('#ASRC_UPD_DEL_lb_attendance').replaceWith(
                        "<select id='ASRC_UPD_DEL_lb_attendance' name='ASRC_UPD_DEL_lb_attendance' class='update_validate'> <option value='1'>PRESENT</option><option value='0'>ABSENT</option><option value='OD'>ONDUTY</option></select>");
                }
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
            $('#ASRC_UPD_DEL_lbl_reportdte').show();
            $('#ASRC_UPD_DEL_ta_reportdate').val(date);
            $('#ASRC_UPD_DEL_ta_reportdate').show();
            $('#ASRC_UPD_DEL_lb_attendance').val('1');
            $('#ASRC_UPD_DEL_lbl_permission').show();
            $('#ASRC_UPD_DEL_rd_permission').show();
            $('#ASRC_UPD_DEL_lbl_nopermission').show();
            $('#ASRC_UPD_DEL_rd_nopermission').show();
            $('#ASRC_UPD_DEL_lbl_session').hide();
            $('#ASRC_UPD_DEL_lb_ampm').hide();
            $('#ASRC_UPD_DEL_tble_projectlistbx').show();
            $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
            projectlist();
            projecdid();
            ASRC_UPD_DEL_report()
            $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled","disabled");
            $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled","disabled");
            $('#ASRC_UPD_DEL_ta_report').val(report);
            ASRC_UPD_DEL_tble_bandwidth()
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
            $('#ASRC_UPD_DEL_lbl_session').hide();
            $('#ASRC_UPD_DEL_lb_ampm').hide();
            $('#ASRC_UPD_DEL_tble_projectlistbx').show();
            $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
            projectlist();
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
            $('#ASRC_UPD_DEL_lbl_reportdte').show();
            $('#ASRC_UPD_DEL_ta_reportdate').val(date);
            $('#ASRC_UPD_DEL_ta_reportdate').show();
            $('#ASRC_UPD_DEL_lb_attendance').val('0');
            $('#ASRC_UPD_DEL_lbl_permission').hide();
            $('#ASRC_UPD_DEL_rd_permission').hide();
            $('#ASRC_UPD_DEL_lbl_nopermission').hide();
            $('#ASRC_UPD_DEL_rd_nopermission').hide();
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
            }
            else
            {
                $('#ASRC_UPD_DEL_chk_flag').attr('checked','checked');
                $("#ASRC_UPD_DEL_chk_flag").show();
                $("#ASRC_UPD_DEL_lbl_flag").show();
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
            $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled");
            $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled");
            $('#ASRC_UPD_DEL_lbl_session').show();
            $('#ASRC_UPD_DEL_lb_ampm').show();
            if((morningsession=='PRESENT') && (afternoonsession=='ABSENT'))
            {
                $('#ASRC_UPD_DEL_lb_ampm').val('PM');
                $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                projectlist();
                projecdid();


            }
            else if((morningsession=='ABSENT' && afternoonsession=='PRESENT'))
            {
                $('#ASRC_UPD_DEL_lb_ampm').val('AM');
                $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                projectlist();
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
            $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled");
            $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled");
            $('#ASRC_UPD_DEL_lbl_session').show();
            $('#ASRC_UPD_DEL_lb_ampm').show();
            if((morningsession=='PRESENT') && (afternoonsession=='ONDUTY'))
            {
                $('#ASRC_UPD_DEL_lb_ampm').val('PM');
                $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                projectlist();
                projecdid();
            }
            else if((morningsession=='ONDUTY' && afternoonsession=='PRESENT'))
            {
                $('#ASRC_UPD_DEL_lb_ampm').val('AM');
                $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                projectlist();
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
    $('#ASRC_UPD_DEL_lb_attendance').change(function(){
        err_flag=0;
        if(attendance==$('#ASRC_UPD_DEL_lb_attendance').val())
        {
            $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
            $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html('');
            $('#ASRC_UPD_DEL_tble_enterthereport').html('');
            $('#ASRC_UPD_DEL_tble_bandwidth').html('');
            $('#ASRC_UPD_DEL_lb_timing').hide();
            $('#ASRC_UPD_DEL_lbl_permission').hide();
            $('#ASRC_UPD_DEL_rd_permission').hide();
            $('#ASRC_UPD_DEL_lbl_nopermission').hide();
            $('#ASRC_UPD_DEL_rd_nopermission').hide();
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
                $('#ASRC_UPD_DEL_tble_enterthereport,#ASRC_UPD_DEL_ta_reason,#ASRC_UPD_DEL_tble_bandwidth').html('');
                $('#ASRC_UPD_DEL_rd_permission').attr('checked',false);
                $('#ASRC_UPD_DEL_rd_nopermission').attr('checked',false);
                $('#ASRC_UPD_DEL_lb_timing').hide();
                $('#ASRC_UPD_DEL_lbl_permission').show();
                $('#ASRC_UPD_DEL_rd_permission').show();
                $('#ASRC_UPD_DEL_lbl_nopermission').show();
                $('#ASRC_UPD_DEL_rd_nopermission').show();
                var permission_list='<option>SELECT</option>';
                for (var i=0;i<permission_array.length;i++) {
                    permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
                }
                $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                $('#ASRC_UPD_DEL_lbl_session').hide();
                $('#ASRC_UPD_DEL_lb_ampm').hide();
                $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                projectlist();
                ASRC_UPD_DEL_report();
                ASRC_UPD_DEL_tble_bandwidth();
                $('#ASRC_UPD_DEL_btn_submit').hide();
                $('#ASRC_UPD_DEL_rd_permission').removeAttr("disabled");
                $('#ASRC_UPD_DEL_rd_nopermission').removeAttr("disabled");
                $('#ASRC_UPD_DEL_lb_timing').prop('selectedIndex',0);
                $('#ASRC_UPD_DEL_errmsg').hide();
            }
            else  if($('#ASRC_UPD_DEL_lb_attendance').val()=='2')
            {
                $('#ASRC_UPD_DEL_tble_enterthereport,#ASRC_UPD_DEL_ta_reason,#ASRC_UPD_DEL_tble_bandwidth').html('');
//                $('#ASRC_UPD_DEL_rd_permission').attr('checked',false);
//                $('#ASRC_UPD_DEL_rd_nopermission').attr('checked',false);
                $('#ASRC_UPD_DEL_lb_timing').hide();
                $('#ASRC_UPD_DEL_lbl_permission').hide();
                $('#ASRC_UPD_DEL_rd_permission').hide();
                $('#ASRC_UPD_DEL_lbl_nopermission').hide();
                $('#ASRC_UPD_DEL_rd_nopermission').hide();
//                var permission_list='<option>SELECT</option>';
//                for (var i=0;i<permission_array.length;i++) {
//                    permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
//                }
//                $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
                $('#ASRC_UPD_DEL_lbl_session').hide();
                $('#ASRC_UPD_DEL_lb_ampm').hide();
                $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
                $('#ASRC_UPD_DEL_tble_projectlistbx').show();
                projectlist();
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
                $('#ASRC_UPD_DEL_lb_timing').hide();
                $('#ASRC_UPD_DEL_lbl_permission').show();
                $('#ASRC_UPD_DEL_rd_permission').show();
                $('#ASRC_UPD_DEL_lbl_nopermission').show();
                $('#ASRC_UPD_DEL_rd_nopermission').show();
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
                $('#ASRC_UPD_DEL_lb_timing').hide();
                $('#ASRC_UPD_DEL_lbl_permission').show();
                $('#ASRC_UPD_DEL_rd_permission').show();
                $('#ASRC_UPD_DEL_lbl_nopermission').show();
                $('#ASRC_UPD_DEL_rd_nopermission').show();
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
            $('.preloader', window.parent.document).show();
            var loginid=$('#ASRC_UPD_DEL_lb_loginid').val();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var msgalert=xmlhttp.responseText;
                    $('.preloader', window.parent.document).hide();
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
            xmlhttp.open("GET","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?reportdate="+reportdate+"&login_id="+loginid+"&option="+choice,true);
            xmlhttp.send();
        }
        else{
            err_flag=0;
            $('#ASRC_UPD_DEL_errmsg').hide();


        }
    });


    $(document).on('click','.paginate_button',function(){
//    alert('inside');
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
    // FUNCTION FOR FORM CLEAR
    function ASRC_UPD_DEL_clear(){
        $('#ASRC_UPD_DEL_tble_attendence').hide();
        $('#ASRC_UPD_DEL_tble_reasonlbltxtarea').html('');
        $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html('');
        $('#ASRC_UPD_DEL_tble_enterthereport').html('');
        $('#ASRC_UPD_DEL_tble_bandwidth').html('');
        $('#ASRC_UPD_DEL_btn_allsearch').html('');
        $('#ASRC_UPD_DEL_lbl_session').hide();
        $('#ASRC_UPD_DEL_lbl_permission').hide();
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
            $('#ASRC_UPD_DEL_btn_submit').show();
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
            $('#ASRC_UPD_DEL_lbl_permission').show();
            $('#ASRC_UPD_DEL_rd_permission').show();
            $('#ASRC_UPD_DEL_lbl_nopermission').show();
            $('#ASRC_UPD_DEL_rd_nopermission').show();
            var permission_list='<option>SELECT</option>';
            for (var i=0;i<4;i++) {
                permission_list += '<option value="' + permission_array[i] + '">' + permission_array[i] + '</option>';
            }
            $('#ASRC_UPD_DEL_lb_timing').html(permission_list);
            $('#ASRC_UPD_DEL_tble_projectlistbx').show();
            $('#ASRC_UPD_DEL_lbl_txtselectproj').show();
            projectlist();
            ASRC_UPD_DEL_report();
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
    function projectlist(){
        $('#ASRC_UPD_DEL_tble_frstsel_projectlistbx').html("");
        var project_list;


        for (var i=0;i<project_array.length;i++) {
//            alert(project_array[i][3])
//            if(project_array[i][3]==3){
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
        $('<div class="row-fluid form-group"><label name="ASRC_UPD_DEL_lbl_reason"  class="col-sm-2" id="ASRC_UPD_DEL_lbl_reason">REASON<em>*</em></label><div class="col-sm-8"><textarea  name="ASRC_UPD_DEL_ta_reason" id="ASRC_UPD_DEL_ta_reason" class="update_validate"></textarea></div>').appendTo($("#ASRC_UPD_DEL_tble_reasonlbltxtarea"));
    }
    // FUNCTION FOR REPORT
    function ASRC_UPD_DEL_report(){
        $('<div class="row-fluid form-group"><label name="ASRC_UPD_DEL_lbl_report" class="col-sm-2" id="ASRC_UPD_DEL_lbl_report" >ENTER THE REPORT<em>*</em></label><div class="col-sm-8"><textarea  name="ASRC_UPD_DEL_ta_report" id="ASRC_UPD_DEL_ta_report" class="update_validate"></textarea></div>').appendTo($("#ASRC_UPD_DEL_tble_enterthereport"));
    }
    // FUNCTIO FOR BANDWIDTH
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
                if(((ASRC_UPD_DEL_reportenter.trim()!="")&&( ASRC_UPD_DEL_projectselectlistbx>0)))
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
        $('.preloader', window.parent.document).show();
        var formElement = document.getElementById("ASRC_UPD_DEL_form_adminsearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                var msg_alert=xmlhttp.responseText;
                if(msg_alert==1)
                {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH AND UPDATE",msgcontent:err_msg[1],position:{top:150,left:500}}});
                    show_msgbox("ADMIN REPORT ENTRY",err_msg[1],"success",false);
                    ASRC_UPD_DEL_clear()
                    flextablerange()
                    $("#ASRC_UPD_DEL_btn_del").hide();
                    $("#ASRC_UPD_DEL_btn_srch").hide();
                    $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                    $('#ASRC_UPD_DEL_ta_reportdate').hide();
                    $('#ASRC_UPD_DEL_errmsg').hide();
                }
                else if(msg_alert==0)
                {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH AND UPDATE",msgcontent:err_msg[7],position:{top:150,left:500}}});
                    show_msgbox("ADMIN REPORT ENTRY",err_msg[7],"success",false);
                    ASRC_UPD_DEL_clear()
                    flextablerange()
                    $("#ASRC_UPD_DEL_btn_del").hide();
                    $("#ASRC_UPD_DEL_btn_srch").hide();
                    $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                    $('#ASRC_UPD_DEL_ta_reportdate').hide();
                    $('#ASRC_UPD_DEL_errmsg').hide();
                }
                else
                {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN SEARCH AND UPDATE",msgcontent:msg_alert,position:{top:150,left:500}}});
                    show_msgbox("ADMIN REPORT ENTRY",msg_alert,"success",false);
                    ASRC_UPD_DEL_clear()
                    flextablerange()
                    $("#ASRC_UPD_DEL_btn_del").hide();
                    $("#ASRC_UPD_DEL_btn_srch").hide();
                    $('#ASRC_UPD_DEL_lbl_reportdte').hide();
                    $('#ASRC_UPD_DEL_ta_reportdate').hide();
                    $('#ASRC_UPD_DEL_errmsg').hide();
                }
            }

        }
        var option="UPDATE"
        xmlhttp.open("POST","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?option="+option+"&reportlocation="+checkoutlocation,true);
        xmlhttp.send(new FormData(formElement));
    });
    // CHANGE EVENT FOR OPTION LIST BOX
    $(document).on('change','#option',function(){
        $('#ASRC_UPD_DEL_div_header').hide();
        $('#ASRC_UPD_btn_pdf').hide();
        $('#ASRC_UPD_DEL_tble_dailyuserentry').hide();
        $('#ASRC_UPD_DEL_tble_ondutyentry').hide();
        $('#ASRC_UPD_DEL_oderrmsg').hide();
        if($('#option').val()=='ADMIN REPORT SEARCH UPDATE DELETE')
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
            ASRC_UPD_DEL_clear()
            $('#ASRC_UPD_DEL_div_ondutytablecontainer').hide();
            $('#ASRC_UPD_DEL_div_header').hide();
            $('#ASRC_UPD_btn_pdf').hide();
            $('#ASRC_UPD_DEL_div_headers').hide();
            $('#ASRC_UPD_btn_od_pdf').hide();
        }
        else if($('#option').val()=='ONDUTY REPORT SEARCH UPDATE')
        {
            $('#ASRC_UPD_DEL_tble_odshow').show();
            $('#ASRC_UPD_DEL_errmsg').hide();
            $('#ASRC_UPD_DEL_btn_srch').hide();
            $('#ASRC_UPD_DEL_tbl_htmltable').hide();
            $('#ASRC_UPD_DEL_btn_del').hide();
            $('#ASRC_UPD_DEL_tb_sdte').val('');
            $('#ASRC_UPD_DEL_tb_edte').val('');
            $('#ASRC_UPD_DEL_errmsg').hide();
            $("#ASRC_UPD_DEL_od_btn").attr("disabled", "disabled");
            $('#ASRC_UPD_DEL_tbl_entry').hide();
            ASRC_UPD_DEL_clear()
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
        $('.preloader', window.parent.document).show();
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
                $('.preloader', window.parent.document).hide();
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
        xmlhttp.open("GET","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?sdate="+sdate+"&edate="+edate+"&option="+choice,true);
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
        $('.preloader', window.parent.document).show();
        var formElement = document.getElementById("ASRC_UPD_DEL_form_adminsearchupdate");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                var msg_alert=xmlhttp.responseText;
//                $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ONDUTY SEARCH/UPDATE",msgcontent:msg_alert,position:{top:150,left:500}}});
                show_msgbox("ADMIN REPORT ENTRY",msg_alert,"success",true);
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
        xmlhttp.open("POST","DB_DAILY_REPORTS_ADMIN_SEARCH_UPDATE_DELETE.do?option="+option,true);
        xmlhttp.send(new FormData(formElement));
    });
    $(document).on('click','#ASRC_UPD_btn_pdf',function(){
        var inputValOne=$('#ASRC_UPD_DEL_lb_loginid').val();
        var inputValTwo=$('#ASRC_UPD_DEL_tb_strtdte').val();
        inputValTwo = inputValTwo.split("-").reverse().join("-");
        var inputValThree=$('#ASRC_UPD_DEL_tb_enddte').val();
        inputValThree = inputValThree.split("-").reverse().join("-");
        var inputValFour=$('#ASRC_UPD_DEL_tb_dte').val();
        inputValFour = inputValFour.split("-").reverse().join("-");
        if($("input[id=ASRC_UPD_DEL_rd_btwnrange]:checked").val()=='RANGES'){
            var url=document.location.href='COMMON_PDF.do?flag=22&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&inputValThree='+inputValThree+'&title='+pdfmsg;
        }
        else if($("input[id=ASRC_UPD_DEL_rd_allactveemp]:checked").val()=='RANGES'){
            var url=document.location.href='COMMON_PDF.do?flag=21&inputValFour='+inputValFour+'&title='+pdfmsg;
        }
    });
    $(document).on('click','#ASRC_UPD_btn_od_pdf',function(){
        var inputValOne=$('#ASRC_UPD_DEL_tb_sdte').val();
        inputValOne = inputValOne.split("-").reverse().join("-");
        var inputValTwo=$('#ASRC_UPD_DEL_tb_edte').val();
        inputValTwo = inputValTwo.split("-").reverse().join("-");
        var url=document.location.href='COMMON_PDF.do?flag=20&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&title='+pdfmsg;
    });
});
//END DOCUMENT READY FUNCTION
</script>
<!--SCRIPT TAG END-->
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="newtitle"><center><p><h3>ADMIN REPORT SEARCH/UPDATE/DELETE</h3><p></center></div>
    <form   id="ASRC_UPD_DEL_form_adminsearchupdate" class="newcontent" >
        <div class="row-fluid form-group">
                <label name="ASRC_UPD_DEL_lbl_optn" class="col-sm-2" id="ASRC_UPD_DEL_lbl_optn">SELECT A OPTION<em>*</em></label>
            <div class="col-sm-8">
                    <select id="option" name="option">
                        <option>SELECT</option>
                        <option>ADMIN REPORT SEARCH UPDATE DELETE</option>
                        <option>ONDUTY REPORT SEARCH UPDATE</option>
                    </select>
                </div></div>

        <div id="ASRC_UPD_DEL_tble_dailyuserentry" hidden>
            <div id="ASRC_UPD_DEL_tbl_entry">
                <div class="row-fluid form-group">
                    <label name="ASRC_UPD_DEL_lbl_btwnrange"  class="col-sm-12" id="ASRC_UPD_DEL_lbl_btwnrange">
                    <input type="radio" name="ASRC_UPD_DEL_rd_range" id="ASRC_UPD_DEL_rd_btwnrange" value="RANGES" class='attnd'>BETWEEN RANGE</label>

                </div>
                <div class="row-fluid form-group">
                    <label name="ASRC_UPD_DEL_lbl_allactveemp" class="col-sm-12" id="ASRC_UPD_DEL_lbl_allactveemp">
                    <input type="radio" name="ASRC_UPD_DEL_rd_range" id="ASRC_UPD_DEL_rd_allactveemp"   value="RANGES" class='attnd'>ALL ACTIVE EMPLOYEE</label>

                </div>
                <div class="row-fluid form-group">
                    <label name="ASRC_UPD_DEL_lbl_allactveemps" id="ASRC_UPD_DEL_lbl_allactveemps" class="srctitle  col-sm-10" hidden>ALL ACTIVE EMPLOYEE</label>
                </div>
                <div class="row-fluid form-group">
                    <label name="ASRC_UPD_DEL_lbl_dte" class="col-sm-2" id="ASRC_UPD_DEL_lbl_dte" hidden>DATE</label>
                    <div class="col-sm-8">
                   <input type="text" name="ASRC_UPD_DEL_tb_dte" id="ASRC_UPD_DEL_tb_dte" class="ASRC_UPD_DEL_date valid enable"   style="width:75px;"  hidden >
                </div></div>
                <div class="row-fluid form-group">
                    <label name="ASRC_UPD_DEL_lbl_btwnranges" id="ASRC_UPD_DEL_lbl_btwnranges" class="srctitle col-sm-10" hidden>BETWEEN RANGE</label>
                </div>
                    <div class="row-fluid form-group">
                        <label name="ASRC_UPD_DEL_lbl_actveemp" class="col-sm-2" id="ASRC_UPD_DEL_lbl_actveemp"  hidden>
                   <input type="radio" name="ASRC_UPD_DEL_rd_veemp" id="ASRC_UPD_DEL_rd_actveemp" value="EMPLOYEE" hidden>ACTIVE EMPLOYEE</label>

              </div>
                <div class="row-fluid form-group">
                    <label name="ASRC_UPD_DEL_lbl_nonactveemp"  class="col-sm-2" id="ASRC_UPD_DEL_lbl_nonactveemp"  hidden>
                    <input type="radio" name="ASRC_UPD_DEL_rd_veemp" id="ASRC_UPD_DEL_rd_nonactveemp"   value="EMPLOYEE" class='attnd' hidden>NON ACTIVE EMPLOYEE</label>

                </div></div>
                <tr>
                    <td><input type="button" class="btn" id="ASRC_UPD_DEL_btn_allsearch" onclick="buttonchange()"  value="SEARCH" hidden disabled></td>
                </tr>
            <div class="row-fluid form-group">
                        <label name="ASRC_UPD_DELlbl_loginid" class="col-sm-2" id="ASRC_UPD_DEL_lbl_loginid"  hidden>EMPLOYEE NAME</label>
                <div class="col-sm-8">
                        <select name="ASRC_UPD_DEL_lb_loginid" id="ASRC_UPD_DEL_lb_loginid" hidden>
                        </select>
                    </div></div>

            <div class="row-fluid form-group">
                  <label name="ASRC_UPD_DEL_lbl_strtdte" class="col-sm-2" id="ASRC_UPD_DEL_lbl_strtdte"  hidden>START DATE<em>*</em></label>
                <div class="col-sm-8">
                    <input type="text" name="ASRC_UPD_DEL_tb_strtdte" id="ASRC_UPD_DEL_tb_strtdte" hidden class="ASRC_UPD_DEL_date valid clear" style="width:75px;">
</div></div>
            <div class="row-fluid form-group">
                   <label name="ASRC_UPD_DEL_lbl_enddte" class="col-sm-2"  id="ASRC_UPD_DEL_lbl_enddte" hidden>END DATE<em>*</em></label>
                <div class="col-sm-8">
                    <input type="text" name="ASRC_UPD_DEL_tb_enddte" id="ASRC_UPD_DEL_tb_enddte" hidden class="ASRC_UPD_DEL_date valid clear" style="width:75px;">
                </div></div>
                <div>
                    <input type="button" class="btn" name="ASRC_UPD_DEL_btn_search" id="ASRC_UPD_DEL_btn_search"  value="SEARCH" disabled hidden>
                </div>

            <div class="srctitle" name="ASRC_UPD_DEL_div_header" id="ASRC_UPD_DEL_div_header" hidden></div>
            <div><input type="button" id='ASRC_UPD_btn_pdf' class="btnpdf" value="PDF"></div>
            <div  id="ASRC_UPD_DEL_div_tablecontainer" class="table-responsive" hidden>
                <section>
                </section>
            </div>
            <div><input type="button" id="ASRC_UPD_DEL_btn_srch" class="btn" name="ASRC_UPD_DEL_btn_srch" value="SEARCH" hidden/>
                <input type="button" id="ASRC_UPD_DEL_btn_del" class="btn" name="ASRC_UPD_DEL_btn_del" value="DELETE" hidden disabled/>
            </div>
        <div class="row-fluid form-group">
                   <label name="ASRC_UPD_DEL_lbl_reportdte" class="col-sm-2" id="ASRC_UPD_DEL_lbl_reportdte" hidden>DATE</label>
            <div class="col-sm-8">
                    <input type ="text" id="ASRC_UPD_DEL_ta_reportdate" class='proj datemandtry update_validate ' hidden name="ASRC_UPD_DEL_ta_reportdate" style="width:75px;" /><label id="ASRC_UPD_DEL_errmsg" name="ASRC_UPD_DEL_errmsg" class="errormsg"></label>

               </div></div>
            <div id="ASRC_UPD_DEL_tble_attendence" class="row-fluid form-group" >

                  <label name="ASRC_UPD_DEL_lbl_attendance"class="col-sm-2"id="ASRC_UPD_DEL_lbl_attendance" >ATTENDANCE<em>*</em></label>
                <div class="col-sm-8">
                        <select id="ASRC_UPD_DEL_lb_attendance" name="ASRC_UPD_DEL_lb_attendance" class="update_validate">
                            <option value="1">PRESENT</option>
                            <option value="0">ABSENT</option>
                            <option value="OD">ONDUTY</option>
                        </select>
                    </div></div>

            <div class="row-fluid form-group">
                <label name="ASRC_UPD_DEL_permission"  class="col-sm-2" id="ASRC_UPD_DEL_lbl_permission" hiddeen>
                   <input type="radio" name="permission" id="ASRC_UPD_DEL_rd_permission" value="PERMISSION" class='permissn update_validate'  hidden >PERMISSION<em>*</em></label>
                <div class="col-sm-8">
                        <select name="ASRC_UPD_DEL_lb_timing" id="ASRC_UPD_DEL_lb_timing" class="update_validate" hidden >
                        </select>
                    </div></div>

            <div class="row-fluid form-group">
                <label name="ASRC_UPD_DEL_nopermission" class="col-sm-12" id="ASRC_UPD_DEL_lbl_nopermission" hiddeen>
                   <input type="radio" name="permission" id="ASRC_UPD_DEL_rd_nopermission" value="NOPERMISSION" class='permissn update_validate'  hidden >NO PERMISSION<em>*</em></label>

               </div>
            <div class="row-fluid form-group">
                   <label name="ASRC_UPD_DEL_lbl_session"  class="col-sm-2" id="ASRC_UPD_DEL_lbl_session" hidden >SESSION</label>
                <div class="col-sm-8">
                    <select name="ASRC_UPD_DEL_lb_ampm" id="ASRC_UPD_DEL_lb_ampm" class="update_validate">
                            <option>SELECT</option>
                            <option>FULLDAY</option>
                            <option>AM</option>
                            <option>PM</option>
                        </select>
                </div></div>
            </div>
            <div id="ASRC_UPD_DEL_tble_reasonlbltxtarea">
            </div>
        <div class="row-fluid form-group">
            <label name="ASRC_UPD_DEL_lbl_flag" class="col-sm-2" id="ASRC_UPD_DEL_lbl_flag" hidden>
             <input type="checkbox" name="flag" id="ASRC_UPD_DEL_chk_flag" class='update_validate'  hidden >FLAG</label>

            </div>
            <div id="ASRC_UPD_DEL_tble_projectlistbx"  class="row-fluid form-group"  hidden>
                <label name="ASRC_UPD_DEL_lbl_txtselectproj" class="col-sm-2"  id="ASRC_UPD_DEL_lbl_txtselectproj" >PROJECT<em>*</em></label>
                   <div id="ASRC_UPD_DEL_tble_frstsel_projectlistbx" class="col-sm-10"></div>
                </div>

            <div id="ASRC_UPD_DEL_tble_enterthereport"></div>
            <div id="ASRC_UPD_DEL_tble_bandwidth"></div>
            <div>
                <label id="ASRC_UPD_DEL_banerrmsg" name="ASRC_UPD_DEL_banerrmsg" class="errormsg"></label>
            </div>
            <div>
                <input type="button"  class="btn" name="ASRC_UPD_DEL_btn_submit" id="ASRC_UPD_DEL_btn_submit"  value="UPDATE" disabled>
            </div>

        <div id="ASRC_UPD_DEL_tble_ondutyentry" hidden>
            <div id="ASRC_UPD_DEL_tble_odshow" hidden>
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
            <div class="srctitle" name="ASRC_UPD_DEL_div_headers" id="ASRC_UPD_DEL_div_headers" hidden></div>
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
                    <div class="col-sm-8">
                    <textarea id="ASRC_UPD_DEL_ta_des" name="ASRC_UPD_DEL_ta_des" class='odenable'></textarea>
                </div></div>
                <div>
                    <input type="button" id="ASRC_UPD_DEL_odsubmit" name="ASRC_UPD_DEL_odsubmit" value="UPDATE" class="btn" disabled  />
                </div>
            </div>
        </div>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->