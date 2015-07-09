
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
            $(".preloader").hide();
            var formElement = document.getElementById("ED_form_user");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
                    var values_array=JSON.parse(xmlhttp.responseText);
                    empdet_active_emp=values_array[0];
                    empdet_active_nonemp=values_array[1];
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
                                    $('#ED_lbl_norole_err').text(ED_errorAarray[0]).show();
                                    $('#employee_active_dt').hide();
                                    $('#ED_lbl_title').hide();
                                    $('#ED_btn_pdf').hide();
                                }
                            }
                        }

                        $('#employee_active_dt').show();
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
                        <label><input type="radio" name="emp_detail" class="emp_detail" id="employee_active" value="active">ACTIVE EMPLOYEE</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="emp_detail"  class="emp_detail" id="employee_nonactive" value="nonactive">NONACTIVE EMPLOYEE</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="emp_detail"  class="emp_detail" id="employee_allactive" value="allactive">ALL ACTIVE EMPLOYEE</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="emp_detail"  class="emp_detail" id="employee_allnonactive" value="allnonactive">ALL NONACTIVE EMPLOYEE</label>
                    </div>
                </div>
                <div class="row-fluid form-group">
                    <label class="col-sm-2" name="emp_active_lbl" id="emp_active_lbl">EMPLOYEE NAME<em>*</em></label>
                    <div class="col-sm-4">
                        <select id="emp_active_lb" class="form-control" style="display: inline" name="emp_active_lb" hidden>
                        </select>
                    </div>
                </div>
                <div class="row-fluid form-group">
                    <label class="col-sm-2" name="emp_nonactive_lbl" id="emp_nonactive_lbl">EMPLOYEE NAME<em>*</em></label>
                    <div class="col-sm-4">
                        <select id="emp_nonactive_lb" class="form-control" style="display: inline" name="emp_nonactive_lb" hidden>
                        </select>
                    </div>
                </div>
                <div><label id="ED_lbl_title" name="ED_lbl_title" class="srctitle"></label></div>
                <div><input type="button" id='ED_btn_pdf' class="btnpdf" value="PDF"></div>
                <div id ="employee_active_dt" class="table-responsive row-fluid form-group col-sm-2" style="max-width:1000px" hidden><section></section></div>
                <div><label id="ED_lbl_norole_err" name="ED_lbl_norole_err" class="errormsg" hidden></label></div>
            </fieldset>
        </div>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->
