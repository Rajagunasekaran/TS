<!--//*******************************************FILE DESCRIPTION*********************************************//
//**********************************************EMPLOYEE DETAILS*******************************************************//
//DONE BY:RENUKADEVI
//INITIAL VERSION
//************************************************************************************************************-->
<?php
include "../../TSLIB/TSLIB_HEADER.php";
//include  "NEW_MENU.php";
?>
<!--HTML TAG START-->
<html>
<head>
    <!--HEAD TAG START-->
    <script>
        //DOCUMENT READY FUNCTION START
        $(document).ready(function(){
            $('#ED_lbl_norole_err').hide();
            $('#emp_active_lb').hide();
            $('#emp_active_lbl').hide();
            $('#emp_nonactive_lb').hide();
            $('#emp_nonactive_lbl').hide();
            $('#ED_btn_pdf').hide();
            var empdet_active_emp=[];
            var empdet_active_nonemp=[];
            var errmsg=[];
            $(".preloader").hide();
            var formElement = document.getElementById("ED_form_user");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var values_array=JSON.parse(xmlhttp.responseText);
                    empdet_active_emp=values_array[0];
                    empdet_active_nonemp=values_array[1];
                    errmsg=values_array[2];

                    if(empdet_active_emp.length!=0)
                    {
                        var active_employee='<option>SELECT</option>';
                        for (var i=0;i<empdet_active_emp.length;i++) {
                            active_employee += '<option value="' + empdet_active_emp[i][1] + '">' + empdet_active_emp[i][0] + '</option>';
                        }
                        $('#emp_active_lb').html(active_employee);
                    }
                    else
                    {
                        $('#ED_lbl_norole_err').text(err_msg_array[0]).show();
                    }
                }
            }
            var option="ACTIVE_EMPLOYEE";
            xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_EMPLOYEE_DETAILS.do?option="+option);
            xmlhttp.send(new FormData(formElement));
            $(document).on('click','#employee_allactive',function(){
                $(".preloader").show();
                $('#ED_lbl_norole_err').hide();
                $('#emp_active_lb').hide();
                $('#emp_active_lbl').hide();
                $('#ED_errormsg_cmpy').hide();
                $('#ED_errormsg_bank').hide();
                $('#ED_errormsg_personal').hide();
//        $('#employee_active_dt').hide();
                $('#active_bank_details').hide();
                $('#employee_active_dt').hide();
                $('#personal_details').hide();
//        $('#ED_table_header').hide();
                $('#BD_table_header').hide();
                $('#emp_nonactive_lb').hide();
                $('#emp_nonactive_lbl').hide();
                $('#ED_btn_pdf').hide();

                var title;
                var values_arraystotal=[];
                var values_array=[];
                table();

                //FUNCTION FOR FORM TABLE DATE FORMAT
                function FormTableDateFormat(inputdate){
                    var string = inputdate.split("-");
                    return string[2]+'-'+ string[1]+'-'+string[0];
                }
                function table(){
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            values_arraystotal=JSON.parse(xmlhttp.responseText);
                            values_array=values_arraystotal[0];
                            var ED_errorAarray=values_arraystotal[1];
                            if(values_array.length!=0)
                            {

                                $('#ED_errormsg_cmpy').text(errmsg[3]).show();

                                var ED_table_header='<table id="ED_tble_htmltable" border="1"  cellspacing="0" class="srcresult" style="width:100px" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>EMPLOYEE NAME</th><th>LAPTOP NUMBER</th><th>CHARGER NUMBER</th><th>LAPTOP BAG</th><th>MOUSE</th><th>DOOR ACCESS</th><th>ID CARD</th><th>HEADSET</th></tr></thead><tbody>';
                                for(var j=0;j<values_array.length;j++){
                                    var AE_empname=values_array[j].empname;
                                    var CPD_laptopno=values_array[j].laptopno;
                                    if((CPD_laptopno=='null')||(CPD_laptopno==undefined))
                                    {
                                        CPD_laptopno='';
                                    }
                                    var CPD_chargerno=values_array[j].chargerno;
                                    if((CPD_chargerno=='null')||(CPD_chargerno==undefined))
                                    {
                                        CPD_chargerno='';
                                    }
                                    var CPD_laptopbag=values_array[j].laptopbag;
                                    if((CPD_laptopbag=='null')||(CPD_laptopbag==undefined))
                                    {
                                        CPD_laptopbag='';
                                    }
                                    var CPD_mouse=values_array[j].mouse;
                                    if((CPD_mouse=='null')||(CPD_mouse==undefined))
                                    {
                                        CPD_mouse='';
                                    }
                                    var CPD_dooraccess=values_array[j].dooraccess;
                                    if((CPD_dooraccess=='null')||(CPD_dooraccess==undefined))
                                    {
                                        CPD_dooraccess='';
                                    }
                                    var CPD_idcard=values_array[j].idcard;
                                    if((CPD_idcard=='null')||(CPD_idcard==undefined))
                                    {
                                        CPD_idcard='';
                                    }
                                    var CPD_headset=values_array[j].headset;
                                    if((CPD_headset=='null')||(CPD_headset==undefined))
                                    {
                                        CPD_headset='';
                                    }
                                    ED_table_header+='<tr><td nowrap align="center">'+AE_empname+'</td>' + '<td align="center">'+CPD_laptopno+'</td>' + '<td STYLE="width: 10PX" align="center">'+CPD_chargerno+'</td><td align="center">'+CPD_laptopbag+'</td>' +  '<td align="center">'+CPD_mouse+'</td><td align="center">'+CPD_dooraccess+'</td>' + '<td align="center">'+CPD_idcard+'</td><td align="center">'+CPD_headset+'</td></tr>';
                                }
                                ED_table_header+='</tbody></table>';

                                $('#ED_table_header').html(ED_table_header);
                                $('#ED_tble_htmltable').DataTable( {
                                    "aaSorting": [],
                                    "pageLength": 10,
                                    "container":"box",
                                    "width":100,
                                    "sPaginationType":"full_numbers",
                                    "aoColumnDefs" : [
                                        { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                                });
                            }
                            else
                            {
                                $('#ED_lbl_norole_err').text(ED_errorAarray[0]).show();
                                $('#ED_lbl_title').hide();
                                $('#ED_btn_pdf').hide();
                            }
                        }
                    }
                    $('#employee_active_dt').show();
                    var option="ALL ACTIVE EMPLOYEE";
                    xmlhttp.open("POST","ADMIN/EMPLOYEE/DB_EMPLOYEE_DETAILS.do?option="+option);
                    xmlhttp.send();
                }
            });

            $(document).on('click','#employee_allnonactive',function(){
                $(".preloader").show();
                $('#ED_lbl_norole_err').hide();
                $('#employee_active_dt').hide();
                $('#emp_active_lb').hide();
                $('#emp_active_lbl').hide();
                $('#ED_errormsg_cmpy').hide();
                $('#ED_errormsg_bank').hide();
                $('#ED_errormsg_personal').hide();
                $('#active_bank_details').hide();
                $('#employee_active_dt').hide();
                $('#personal_details').hide();
                $('#BD_table_header').hide();
                $('#emp_nonactive_lb').hide();
                $('#emp_nonactive_lbl').hide();
                $('#ED_btn_pdf').hide();
                var title;
                var values_arraystotal=[];
                var values_array=[];
                tablenon();
                //FUNCTION FOR FORM TABLE DATE FORMAT
                function FormTableDateFormat(inputdate){
                    var string = inputdate.split("-");
                    return string[2]+'-'+ string[1]+'-'+string[0];
                }
                function tablenon(){
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            values_arraystotal=JSON.parse(xmlhttp.responseText);
                            values_array=values_arraystotal[0];
                            var ED_errorAarray=values_arraystotal[1];
                            if(values_array.length!=0)
                            {

                                $('#ED_errormsg_cmpy').text(errmsg[4]).show();
                                $('#ED_btn_pdf').show();

                                var ED_table_header='<table id="ED_tble_htmltable" border="1"  cellspacing="0" class="srcresult" style="width:100px" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>EMPLOYEE NAME</th><th>LAPTOP NUMBER</th><th>CHARGER NUMBER</th><th>LAPTOP BAG</th><th>MOUSE</th><th>DOOR ACCESS</th><th>ID CARD</th><th>HEADSET</th></tr></thead><tbody>'
                                for(var j=0;j<values_array.length;j++){
                                    var AE_empname=values_array[j].empname;
                                    var CPD_laptopno=values_array[j].laptopno;
                                    if((CPD_laptopno=='null')||(CPD_laptopno==undefined))
                                    {
                                        CPD_laptopno='';
                                    }
                                    var CPD_chargerno=values_array[j].chargerno;
                                    if((CPD_chargerno=='null')||(CPD_chargerno==undefined))
                                    {
                                        CPD_chargerno='';
                                    }
                                    var CPD_laptopbag=values_array[j].laptopbag;
                                    if((CPD_laptopbag=='null')||(CPD_laptopbag==undefined))
                                    {
                                        CPD_laptopbag='';
                                    }
                                    var CPD_mouse=values_array[j].mouse;
                                    if((CPD_mouse=='null')||(CPD_mouse==undefined))
                                    {
                                        CPD_mouse='';
                                    }
                                    var CPD_dooraccess=values_array[j].dooraccess;
                                    if((CPD_dooraccess=='null')||(CPD_dooraccess==undefined))
                                    {
                                        CPD_dooraccess='';
                                    }
                                    var CPD_idcard=values_array[j].idcard;
                                    if((CPD_idcard=='null')||(CPD_idcard==undefined))
                                    {
                                        CPD_idcard='';
                                    }
                                    var CPD_headset=values_array[j].headset;
                                    if((CPD_headset=='null')||(CPD_headset==undefined))
                                    {
                                        CPD_headset='';
                                    }
                                    ED_table_header+='<tr><td nowrap align="center">'+AE_empname+'</td>' + '<td align="center">'+CPD_laptopno+'</td>' + '<td STYLE="width: 10PX" align="center">'+CPD_chargerno+'</td><td align="center">'+CPD_laptopbag+'</td>' +  '<td align="center">'+CPD_mouse+'</td><td align="center">'+CPD_dooraccess+'</td>' + '<td align="center">'+CPD_idcard+'</td><td align="center">'+CPD_headset+'</td></tr>';
                                }
                                ED_table_header+='</tbody></table>';

                                $('#ED_table_header').html(ED_table_header);
                                $('#ED_tble_htmltable').DataTable( {
                                    "aaSorting": [],
                                    "pageLength": 10,
                                    "container":"box",
                                    "width":100,
                                    "sPaginationType":"full_numbers",
                                    "aoColumnDefs" : [
                                        { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                                });
                            }
                            else
                            {
                                $('#ED_lbl_norole_err').text(ED_errorAarray[0]).show();
                                $('#ED_lbl_title').hide();
                                $('#ED_btn_pdf').hide();
                            }
                        }
                    }

                    $('#employee_active_dt').show();
                    var option="ALL NONACTIVE EMPLOYEE";
                    xmlhttp.open("POST","ADMIN/EMPLOYEE/DB_EMPLOYEE_DETAILS.do?option="+option);
                    xmlhttp.send();
                }

            });
            $(document).on('click','#employee_active',function(){
                $(".preloader").show();
                $('#emp_nonactive_lb').hide();
                $('#emp_nonactive_lbl').hide();
                $('#employee_active_dt').hide();
                $('#ED_errormsg_cmpy').hide();
                $('#ED_errormsg_bank').hide();
                $('#ED_errormsg_personal').hide();
                $('#ED_lbl_norole_err').hide();
                $('#ED_lbl_title').hide();
                $('#ED_btn_pdf').hide();
                $(".preloader").hide();
                var active_employee='<option>SELECT</option>';
                for (var i=0;i<empdet_active_emp.length;i++) {
                    active_employee += '<option value="' + empdet_active_emp[i][1] + '">' + empdet_active_emp[i][0] + '</option>';
                }
                $('#emp_active_lb').html(active_employee);
                $('#emp_active_lb').show();
                $('#emp_active_lbl').show();

                $(document).on('change','#emp_active_lb',function(){

                    $(".preloader").show();
                    $('#employee_active_dt').hide();
                    $('#ED_lbl_norole_err').hide();
                    var login_id=$('#emp_active_lb').val();
                    if($('#emp_active_lb').val()=="SELECT")
                    {

                    }
                    else
                    {
                        var title;
                        var values_arraystotal=[];
                        var values_array=[];
                        var values_array1=[];
                        var values_array2=[];
                        $(".preloader").show();
                        var xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                                $(".preloader").hide();
                                values_arraystotal=JSON.parse(xmlhttp.responseText);
                                values_array=values_arraystotal[0];
                                var ED_errorAarray=values_arraystotal[3];
                                values_array1=values_arraystotal[1];
//                        var BD_errorAarray=values_arraystotal[3];
                                values_array2=values_arraystotal[2];
//                        var PD_errorAarray=values_arraystotal[3];

                                if(values_array.length!=0)
                                {
                                    var login_id=$('#emp_active_lb').val();
                                    var title=errmsg[0].toString().replace("[EMP NAME]",$("#emp_active_lb option:selected").text());
                                    $('#ED_errormsg_cmpy').text(title).show();
                                    $('#ED_btn_pdf').show();
                                    var ED_table_header='<table id="ED_tble_htmltable" border="1"  cellspacing="0" class="srcresult" style="width:100px" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>EMPLOYEE NAME</th><th>LAPTOP NUMBER</th><th>CHARGER NUMBER</th><th>LAPTOP BAG</th><th>MOUSE</th><th>DOOR ACCESS</th><th>ID CARD</th><th>HEADSET</th></tr></thead><tbody>'
                                    for(var j=0;j<values_array.length;j++){
                                        var AE_empname=values_array[j].empname;

                                        var CPD_laptopno=values_array[j].laptopno;
                                        if((CPD_laptopno=='null')||(CPD_laptopno==undefined))
                                        {
                                            CPD_laptopno='';
                                        }
                                        var CPD_chargerno=values_array[j].chargerno;
                                        if((CPD_chargerno=='null')||(CPD_chargerno==undefined))
                                        {
                                            CPD_chargerno='';
                                        }
                                        var CPD_laptopbag=values_array[j].laptopbag;
                                        if((CPD_laptopbag=='null')||(CPD_laptopbag==undefined))
                                        {
                                            CPD_laptopbag='';
                                        }
                                        var CPD_mouse=values_array[j].mouse;
                                        if((CPD_mouse=='null')||(CPD_mouse==undefined))
                                        {
                                            CPD_mouse='';
                                        }
                                        var CPD_dooraccess=values_array[j].dooraccess;
                                        if((CPD_dooraccess=='null')||(CPD_dooraccess==undefined))
                                        {
                                            CPD_dooraccess='';
                                        }
                                        var CPD_idcard=values_array[j].idcard;
                                        if((CPD_idcard=='null')||(CPD_idcard==undefined))
                                        {
                                            CPD_idcard='';
                                        }
                                        var CPD_headset=values_array[j].headset;
                                        if((CPD_headset=='null')||(CPD_headset==undefined))
                                        {
                                            CPD_headset='';
                                        }
                                        ED_table_header+='<tr><td nowrap align="center">'+AE_empname+'</td>' + '<td align="center">'+CPD_laptopno+'</td>' + '<td STYLE="width: 10PX" align="center">'+CPD_chargerno+'</td><td align="center">'+CPD_laptopbag+'</td>' +  '<td align="center">'+CPD_mouse+'</td><td align="center">'+CPD_dooraccess+'</td>' + '<td align="center">'+CPD_idcard+'</td><td align="center">'+CPD_headset+'</td></tr>';
                                    }
                                    ED_table_header+='</tbody></table>';

                                    $('#ED_table_header').html(ED_table_header);
                                    $('#ED_tble_htmltable').DataTable( {

                                        "aaSorting": [],
                                        "pageLength": 10,
                                        "container":"box",
                                        "width":100,
                                        "sPaginationType":"full_numbers",
                                        "aoColumnDefs" : [
                                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                                    });
                                }
                                if(values_array1.length!=0){
                                    var login_id=$('#emp_active_lb').val();
                                    var title=errmsg[1].toString().replace("[EMP NAME]",$("#emp_active_lb option:selected").text());
                                    $('#ED_errormsg_bank').text(title).show();
                                    $('#ED_btn_pdf').show();
                                    var BD_table_header='<table id="BD_tble_htmltable" border="1" cellspacing="0" class="srcresult" style="width:100px"><thead bgcolor="#6495ed" style="color:white"><tr><th>EMPLOYEE NAME</th><th>ACCOUNT NAME</th><th>ACCOUNT NUMBER</th><th>BANK NAME</th><th>BRANCH NAME</th><th>ACCOUNT TYPE</th><th>IFSC CODE</th><th>BRANCH ADDRESS</th></tr></thead><tbody>';
                                    for(var i=0;i<values_array1.length;i++){
                                        var BD_empname=values_array1[i].empname;
                                        var BD_acctname=values_array1[i].accountname;
                                        var BD_acctno=values_array1[i].accountno;
                                        var BD_bankname=values_array1[i].bankname;
                                        var BD_branchname=values_array1[i].branchname;
                                        var BD_accttype=values_array1[i].accttype;
                                        var BD_IFSC=values_array1[i].ifsc;
                                        var BD_branchaddr=values_array1[i].branchaddr;
                                        BD_table_header+='<tr><td nowrap align="center">'+BD_empname+'</td>' + '<td align="center">'+BD_acctname+'</td>' + '<td STYLE="width: 10PX" align="center">'+BD_acctno+'</td><td align="center">'+BD_bankname+'</td>' +  '<td align="center">'+BD_branchname+'</td><td align="center">'+BD_accttype+'</td>' + '<td align="center">'+BD_IFSC+'</td><td align="center">'+BD_branchaddr+'</td></tr>';
                                    }
                                    BD_table_header+='</tbody></table>';
                                    $('#BD_table_header').html(BD_table_header);

                                    $('#BD_tble_htmltable').DataTable( {
                                        "aaSorting": [],
                                        "pageLength": 10,
                                        "container":"box",
                                        "width":100,
                                        "sPaginationType":"full_numbers",
                                        "aoColumnDefs" : [
                                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                                    });
                                }

                                if(values_array2.length!=0){

                                    var login_id=$('#emp_active_lb').val();
                                    var title=errmsg[2].toString().replace("[EMP NAME]",$("#emp_active_lb option:selected").text());
                                    $('#ED_errormsg_personal').text(title).show();
                                    var PD_personal_details='<table id="PD_tble_htmltable" border="1" cellspacing="0" class="srcresult" style="width:100px"><thead bgcolor="#6495ed" style="color:white"><tr><th align="center">EMPLOYEE NAME</th><th align="center">DATE OF BIRTH</th><th align="center">DESIGNATION</th><th align="center">MOBILE NUMBER</th><th align="center">KIN NAME</th><th align="center">RELATIONHOOD</th><th align="center">ALT MOBILE NUMBER</th><th align="center">HOUSE NO</th><th align="center">STREET NAME</th><th align="center">AREA</th><th align="center">PIN CODE</th><th align="center">AADHAAR NO</th><th align="center">PASSPORT NO</th><th align="center">VOTER ID</th><th align="center">COMMENTS</th><th align="center">USERSTAMP</th><th style="width:150px;" class="uk-timestp-column">TIMESTAMP</th></tr></thead><tbody>';
                                    for(i=0;i<values_array2.length;i++){
                                        var PD_empname=values_array2[i].empname;

                                        var PD_dob=values_array2[i].date_of_birth;

                                        var PD_desgn=values_array2[i].designation;

                                        var PD_mblnumber=values_array2[i].mobilenumber;
                                        var PD_kinname=values_array2[i].kiname;
                                        var PD_relationhood=values_array2[i].relation;
                                        var PD_altmblno=values_array2[i].altmblnumber;
                                        var PD_houseno=values_array2[i].houseno;

                                        var PD_streetname=values_array2[i].streetname;
                                        var PD_area=values_array2[i].area;
                                        var PD_pincode=values_array2[i].pincode;

                                        var PD_addhar=values_array2[i].addhar;
                                        if((PD_addhar=='null')||(PD_addhar==undefined))
                                        {
                                            PD_addhar='';
                                        }
                                        var PD_passport=values_array2[i].passport;
                                        if((PD_passport=='null')||(PD_passport==undefined))
                                        {
                                            PD_passport='';
                                        }
                                        var PD_voter_id=values_array2[i].voter;
                                        if((PD_voter_id=='null')||(PD_voter_id==undefined))
                                        {
                                            PD_voter_id='';
                                        }
                                        var PD_comments=values_array2[i].comments;
                                        if((PD_comments=='null')||(PD_comments==undefined))
                                        {
                                            PD_comments='';
                                        }
                                        var userstamp=values_array2[i].userstamp;
                                        var timestamp=values_array2[i].timestamp;
                                        PD_personal_details +='<tr><td align="center">'+PD_empname+'</td>'+'<td align="center">'+PD_dob+'</td>'+'<td align="center">'+PD_desgn+'</td>'+'<td align="center">'+PD_mblnumber+'</td>'+'<td align="center">'+PD_kinname+'</td>'+'<td align="center">'+PD_relationhood+'</td>'+'<td align="center">'+PD_altmblno+'</td>'+'<td align="center">'+PD_houseno+'</td>'+'<td align="center">'+PD_streetname+'</td>'+'<td align="center">'+PD_area+'</td>'+'<td align="center">'+PD_pincode+'</td>'+'<td align="center">'+PD_addhar+'</td>'+'<td align="center">'+PD_passport+'</td>'+'<td align="center">'+PD_voter_id+'</td>'+'<td align="center">'+PD_comments+'</td>'+'<td align="center">'+userstamp+'</td>'+'<td align="center">'+timestamp+'</td></tr>';
                                    }
                                    PD_personal_details+='</tbody></table>';
                                    $('#PD_personal_Details').html(PD_personal_details);
                                    $('#PD_tble_htmltable').DataTable( {
                                        "aaSorting": [],
                                        "pageLength": 10,
                                        "container":"box",
                                        "width":100,
                                        "sPaginationType":"full_numbers",
                                        "aoColumnDefs" : [
                                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                                    });
                                }



                                else
                                {
                                    $('#ED_lbl_norole_err').text(ED_errorAarray[0]).show();
                                    $('#employee_active_dt').hide();
                                    $('#ED_lbl_title').hide();
                                    $('#ED_btn_pdf').hide();
                                }
                            }
                        }

                        $('#employee_active_dt').show();
                        $('#active_bank_details').show();
                        $('#personal_details').show();
                        $('#BD_table_header').show();
                        var option="ACTIVE_EMPLOYEE_companydet";
                        xmlhttp.open("POST","ADMIN/EMPLOYEE/DB_EMPLOYEE_DETAILS.do?option="+option+"&login_id="+login_id);
                        xmlhttp.send();
                    }
                });
                //FUNCTION FOR FORM TABLE DATE FORMAT
                function FormTableDateFormat(inputdate){
                    var string = inputdate.split("-");
                    return string[2]+'-'+ string[1]+'-'+string[0];
                }
            });
            $(document).on('click','#employee_nonactive',function(){
                $(".preloader").show();
                $('#emp_active_lb').hide();
                $('#emp_active_lbl').hide();
                $('#employee_active_dt').hide();
                $('#ED_errormsg_cmpy').hide();
                $('#ED_errormsg_bank').hide();
                $('#ED_errormsg_personal').hide();
                $('#active_bank_details').hide();
                $('#personal_details').hide();
                $('#ED_lbl_norole_err').hide();
                $('#employee_active_dt').hide();
                $('#ED_lbl_title').hide();
                $('#ED_btn_pdf').hide();
                $(".preloader").hide();
                var nonactive_employee='<option>SELECT</option>';
                for (var i=0;i<empdet_active_nonemp.length;i++) {
                    nonactive_employee += '<option value="' + empdet_active_nonemp[i][1] + '">' + empdet_active_nonemp[i][0] + '</option>';
                }
                $('#emp_nonactive_lb').html(nonactive_employee);
                $('#emp_nonactive_lb').show();
                $('#emp_nonactive_lbl').show();

                $(document).on('change','#emp_nonactive_lb',function(){
                    $(".preloader").show();
                    $('#employee_active_dt').hide();
                    $('#ED_lbl_norole_err').hide();
                    var login_id=$('#emp_nonactive_lb').val();
                    if($('#emp_nonactive_lb').val()=="SELECT")
                    {

                    }
                    else
                    {
                        var title;
                        var values_arraystotal=[];
                        var values_array=[];
                        $(".preloader").show();
                        var xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                                $(".preloader").hide();
                                values_arraystotal=JSON.parse(xmlhttp.responseText);
                                values_array=values_arraystotal[0];
                                var ED_errorAarray=values_arraystotal[1];
                                if(values_array.length!=0)
                                {
                                    title=ED_errorAarray[1].toString().replace("PROJECT","EMPLOYEE");
                                    $('#ED_lbl_title').text(title).show();
                                    $('#ED_btn_pdf').show();

                                    var ED_table_header='<table id="ED_tble_htmltable" border="1"  cellspacing="0" class="srcresult" style="width:100px" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>EMPLOYEE NAME</th><th>LAPTOP NUMBER</th><th>CHARGER NUMBER</th><th>LAPTOP BAG</th><th>MOUSE</th><th>DOOR ACCESS</th><th>ID CARD</th><th>HEADSET</th></tr></thead><tbody>'
                                    for(var j=0;j<values_array.length;j++){
                                        var AE_empname=values_array[j].empname;
                                        var CPD_laptopno=values_array[j].laptopno;
                                        if((CPD_laptopno=='null')||(CPD_laptopno==undefined))
                                        {
                                            CPD_laptopno='';
                                        }
                                        var CPD_chargerno=values_array[j].chargerno;
                                        if((CPD_chargerno=='null')||(CPD_chargerno==undefined))
                                        {
                                            CPD_chargerno='';
                                        }
                                        var CPD_laptopbag=values_array[j].laptopbag;
                                        if((CPD_laptopbag=='null')||(CPD_laptopbag==undefined))
                                        {
                                            CPD_laptopbag='';
                                        }
                                        var CPD_mouse=values_array[j].mouse;
                                        if((CPD_mouse=='null')||(CPD_mouse==undefined))
                                        {
                                            CPD_mouse='';
                                        }
                                        var CPD_dooraccess=values_array[j].dooraccess;
                                        if((CPD_dooraccess=='null')||(CPD_dooraccess==undefined))
                                        {
                                            CPD_dooraccess='';
                                        }
                                        var CPD_idcard=values_array[j].idcard;
                                        if((CPD_idcard=='null')||(CPD_idcard==undefined))
                                        {
                                            CPD_idcard='';
                                        }
                                        var CPD_headset=values_array[j].headset;
                                        if((CPD_headset=='null')||(CPD_headset==undefined))
                                        {
                                            CPD_headset='';
                                        }
                                        ED_table_header+='<tr><td nowrap align="center">'+AE_empname+'</td>' + '<td align="center">'+CPD_laptopno+'</td>' + '<td STYLE="width: 10PX" align="center">'+CPD_chargerno+'</td><td align="center">'+CPD_laptopbag+'</td>' +  '<td align="center">'+CPD_mouse+'</td><td align="center">'+CPD_dooraccess+'</td>' + '<td align="center">'+CPD_idcard+'</td><td align="center">'+CPD_headset+'</td></tr>';
                                    }
                                    ED_table_header+='</tbody></table>';

                                    $('section').html(ED_table_header);
                                    $('#ED_tble_htmltable').DataTable( {
                                        "aaSorting": [],
                                        "pageLength": 10,
                                        "container":"box",
                                        "width":100,
                                        "sPaginationType":"full_numbers",
                                        "aoColumnDefs" : [
                                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                                    });
                                }
                                else
                                {
                                    $('#employee_active_dt').hide();
                                    $('#ED_lbl_norole_err').text(ED_errorAarray[0]).show();
                                    $('#ED_lbl_title').hide();
                                    $('#ED_btn_pdf').hide();
                                }
                            }
                        }

                        $('#employee_active_dt').show();

                        var option="NONACTIVE_EMPLOYEE_companydet";
                        xmlhttp.open("POST","ADMIN/EMPLOYEE/DB_EMPLOYEE_DETAILS.do?option="+option+"&login_id="+login_id);
                        xmlhttp.send();
                    }
                });
                //FUNCTION FOR FORM TABLE DATE FORMAT
                function FormTableDateFormat(inputdate){
                    var string = inputdate.split("-");
                    return string[2]+'-'+ string[1]+'-'+string[0];
                }
            });



        });
        //DOCUMENT READY FUNCTION END
    </script>
    <!--SCRIPT TAG END-->
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="title text-center"><b><h4>EMPLOYEE DETAILS</h4></b></div>
    <form class="form-horizontal content" role="form"  name="ED_form_user" id="ED_form_user" autocomplete="off" >
        <div class="panel-body">
            <fieldset>
                <div style="padding-bottom: 15px">
                    <div class="radio">
                        <label class="control-label"><input type="radio" name="emp_detail" class="emp_detail" id="employee_active" value="active">ACTIVE EMPLOYEE</label>
                    </div>
                    <div class="radio">
                        <label class="control-label"><input type="radio" name="emp_detail"  class="emp_detail" id="employee_nonactive" value="nonactive">NONACTIVE EMPLOYEE</label>
                    </div>
                    <div class="radio">
                        <label class="control-label"><input type="radio" name="emp_detail"  class="emp_detail" id="employee_allactive" value="allactive">ALL ACTIVE EMPLOYEE</label>
                    </div>
                    <div class="radio">
                        <label class="control-label"><input type="radio" name="emp_detail"  class="emp_detail" id="employee_allnonactive" value="allnonactive">ALL NONACTIVE EMPLOYEE</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2" name="emp_active_lbl" id="emp_active_lbl">EMPLOYEE NAME<em>*</em></label>
                    <div class="col-sm-4">
                        <select id="emp_active_lb" class="form-control" style="display: inline" name="emp_active_lb" hidden>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2" name="emp_nonactive_lbl" id="emp_nonactive_lbl">EMPLOYEE NAME<em>*</em></label>
                    <div class="col-sm-4">
                        <select id="emp_nonactive_lb" class="form-control" style="display: inline" name="emp_nonactive_lb" hidden>
                        </select>
                    </div>
                </div>
                <div><label id="ED_lbl_title" name="ED_lbl_title" class="srctitle"></label></div>
                <label name="ED_errormsg_cmpy" id="ED_errormsg_cmpy" class="srctitle col-sm-12"></label>
                <div><input type="button" id='ED_btn_pdf' class="btnpdf" value="PDF"></div>
                <div id ="employee_active_dt" class="table-responsive form-group"  style="max-width:1000px;padding-left: 9px" hidden>
                    <section id="ED_table_header" style="max-width:800px"></section></div>
                <label name="ED_errormsg_bank" id="ED_errormsg_bank" class="srctitle col-sm-12"></label>
                <div id="active_bank_details" class="table-responsive form-group" style="max-width:1000px;padding-left: 10px" hidden>
                    <section id="BD_table_header" style="max-width:900px"></section>
                </div>
                <label name="ED_errormsg_personal" id="ED_errormsg_personal" class="srctitle col-sm-12"></label>
                <div id="personal_details" class="table-responsive form-group" style="max-width:1000px;padding-left: 10px" hidden>
                    <section id="PD_personal_Details"></section>
                </div>
                <div><label id="ED_lbl_norole_err" name="ED_lbl_norole_err" class="errormsg" hidden></label></div>
            </fieldset>
        </div>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->