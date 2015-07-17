<!--//*******************************************FILE DESCRIPTION*********************************************//
//**********************************************REVENUE*******************************************************//
//DONE BY:LALITHA
//VER 0.02-SD:20/06/2015 ED:20/06/2015,hided the lbl etc,responsive
//DONE BY:RENUKA
//VER 0.01-INITIAL VERSION, SD:08/10/2014 ED:15/10/2014,TRACKER NO:97
//************************************************************************************************************-->
<?php
include "../TSLIB/TSLIB_HEADER.php";
?>
<html>
<head>
    <!--SCRIPT TAG START-->
<head>
    <script>
        //GLOBAL DECLARATION
        var err_msg_array=[];
        var project_recver='';
        //READY FUNCTION START
        $(document).ready(function(){
            $(".preloader").hide();
            $('#REV_btn_emplist_pdf').hide();
            $('#REV_btn_emp_pdf').hide();
            $('#REV_btn_empsrch').hide();
            $('#REV_lbl_emptitle').hide();
            var project_recver='';
            $(".preloader").show();
            first();
            var err_msg_array=[];
            var values_array;
            var project_names;
            var uld_id;
            var uld_name;
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var values_array=JSON.parse(xmlhttp.responseText);
                    err_msg_array=values_array[0];
                    uld_id=values_array[1];
                    uld_name=values_array[2];
                }
            }
            var option="common";
            xmlhttp.open("GET","USER/DB_DAILY_REPORT_USER_PROJECT_REVENUE.do?option="+option);
            xmlhttp.send();
// CHANGE EVENT FOR PROJECT LISTBOX
            function first(){
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        values_array=JSON.parse(xmlhttp.responseText);
                        project_names='<option>SELECT</option>';
                        for (var j=0;j<values_array.length;j++) {
                            project_names += '<option value="' + values_array[j] + '">' + values_array[j] + '</option>';
                        }
                        $('#REV_lb_empproject').html(project_names);
                    }
                }
                var option="SPECICIFIED_PROJECT_NAME";
                xmlhttp.open("GET","USER/DB_DAILY_REPORT_USER_PROJECT_REVENUE.do?option="+option);
                xmlhttp.send();
            }
// BUTTON VALIDATION.
            $(document).on('change','.vali',function(){
                $('#REV_btn_empsrch').attr("disabled","disabled");
                $('#REV_div_loginid').hide();
                $('#REV_lbl_empday').hide();
                $('#REV_btn_emp_pdf').hide();
                $('#REV_lbl_emptitle').hide();
                $('#REV_lbl_emptitle').text(employeetitles).hide();
                $('sections').html('');
                $('#REV_nodata_loginid').hide();
                $('#REV_nodata_loginiddivid').hide();
                var project_name=$('#REV_lb_empproject').val();
                if((project_name != 'SELECT'))
                {
                    $('#REV_btn_empsrch').removeAttr("disabled");
                }
                else
                {
                    $('#REV_btn_empsrch').attr("disabled","disabled").hide();
                    $('#REV_div_loginid').hide();
                    $('#REV_lb_recver').hide();
                    $('#REV_lb_recverdivid').hide();
                    $('#REV_lbl_recver').hide();
                    $('#REV_lbl_empday').hide();
                    $('#REV_btn_emp_pdf').hide();
                    $('#REV_lbl_emptitle').hide();
                    $('#REV_lbl_emptitle').text(employeetitles).hide();
                }
                $(".preloader").show();
                var formElement = document.getElementById("REV_form_revenue");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var values_array=JSON.parse(xmlhttp.responseText);
                        $(".preloader").hide();
                        var REV_project_recver=values_array;
                        var recver_list='';
                        if($('#REV_lb_empproject').val()=="SELECT")
                        {
                            $('#REV_btn_empsrch').show();
                            $('#REV_btn_empsrch').attr("disabled","disabled");
                            $('#REV_lbl_recver').hide();
                            $('#REV_lb_recver').hide();
                            $('#REV_lb_recverdivid').hide();
                            $('#REV_div_loginid').hide();
                            $('#REV_lbl_empday').hide();
                            $('#REV_lbl_emptitle').hide();
                            $('#REV_lbl_emptitle').text(employeetitles).hide();
                            $('#REV_btn_emp_pdf').hide();
                            $(".preloader").hide();
                        }
                        else
                        {
                            $('#REV_btn_empsrch').show();
                            var length=REV_project_recver.length;
                            recver_list='<option>SELECT</option>';
                            for (var i=0;i<REV_project_recver.length;i++) {
                                recver_list += '<option value="' + REV_project_recver[i] + '">' + REV_project_recver[i] + '</option>';
                            }
                            $('#REV_lb_recver').html(recver_list);
                            if(length == 1)
                            {
                                $('#REV_lb_recver').hide();
                                $('#REV_lb_recverdivid').hide();
                                $('#REV_lbl_recver').hide();
                                $('#REV_lbl_emptitle').hide();
                                $('#REV_lbl_emptitle').text(employeetitles).hide();
                                $('#REV_btn_emp_pdf').hide();

                            }
                            else{
                                $('#REV_lbl_recver').show();
                                $('#REV_lb_recver').show();
                                $('#REV_lb_recverdivid').show();
                                $('#REV_btn_empsrch').attr("disabled","disabled");
                                var rd_ver = $('#REV_lb_recver').val();
                                if(rd_ver != 'SELECT')
                                {
                                    $('#REV_btn_empsrch').removeAttr("disabled");
                                }
                                else
                                {
                                    $('#REV_btn_empsrch').attr("disabled","disabled");
                                }
                            }
                        }
                    }
                }
                var choice="PROJECTRECVERSION";
                xmlhttp.open("POST","USER/DB_DAILY_REPORT_USER_PROJECT_REVENUE.do?option="+choice);
                xmlhttp.send(new FormData(formElement));
            });

            $(document).on('change','#REV_lb_recver',function(){
                $('#REV_nodata_loginid').hide();
                $('#REV_nodata_loginiddivid').hide();
                $('#REV_lbl_emptitle').hide();
                $('#REV_btn_emp_pdf').hide();
                $('#REV_lbl_empday').hide();
                $('#REV_lbl_emptitle').text(employeetitles).hide();
                $('#REV_btn_empsrch').attr("disabled","disabled");
                $('#REV_div_loginid').hide(); $('sections').html('');
                var rd_ver = $('#REV_lb_recver').val();
                if(rd_ver != 'SELECT')
                {

                    $('#REV_btn_empsrch').removeAttr("disabled");
                }
                else
                {

                    $('#REV_btn_empsrch').attr("disabled","disabled");
                }
            });
            $(document).on('click','#REV_rd_withoutproject',function(){
                $('#REV_tb_strtdte').hide();
                $('#REV_tb_enddte').hide();
                $('#REV_lb_empproject').hide();
                $('#REV_lbl_empproject').hide();
                $('#REV_lbl_empprojectdivid').hide();
                $('#REV_btn_empsrch').removeAttr("disabled").show();
                $('#REV_lb_recver').hide();
                $('#REV_nodata_loginid').hide();
                $('#REV_lb_recverdivid').hide();
                $('#REV_lbl_recver').hide();
                $('#REV_btn_emp_pdf').hide();
                $('#REV_btn_emplist_pdf').hide();
                $('#REV_div_loginid').hide();
                $('#REV_lbl_empday').hide();
                $('#REV_lbl_emptitle').hide();
                $('#REV_lbl_emptitle').text(employeetitles).hide();
            });

            $(document).on('click','#REV_rd_withproject',function(){
                $('#REV_tb_strtdte').hide();
                $('#REV_tb_enddte').hide();
                $('#REV_lbl_empproject').show();
                $('#REV_lbl_empprojectdivid').show();
//        $('#REV_lb_empproject').val("SELECT").show();
                project_names='<option>SELECT</option>';
                for (var j=0;j<values_array.length;j++) {
                    project_names += '<option value="' + values_array[j] + '">' + values_array[j] + '</option>';
                }
                $('#REV_lb_empproject').html(project_names).show();
                $('#REV_lb_recver').hide();
                $('#REV_lb_recverdivid').hide();
                $('#REV_lbl_recver').hide();
                $('#REV_btn_emp_pdf').hide();
                $('#REV_btn_emplist_pdf').hide();
                $('#REV_btn_empsrch').attr("disabled","disabled");
                $('#REV_div_loginid').hide();
                $('#REV_lbl_empday').hide();
                $('#REV_btn_empsrch').hide();
                $('#REV_lbl_emptitle').hide();
                $('#REV_lbl_emptitle').text(employeetitles).hide();

            });

// CLICK EVENT FOR LOGINID SEARCH BUTTON
            var loginidvalues=[];
            var employeetitles;
            var heading="DETAILS FOR WORKED PROJECTS";
            $(document).on('click','#REV_btn_empsrch',function(){
                $('sections').html('');
                var REV_withproject=$('#REV_rd_withproject').val();
                $('#REV_nodata_loginid').hide();
                $('#REV_nodata_loginiddivid').hide();
                var REV_prjctname=$('#REV_lb_empproject').val();
                var REV_start_datevalue=$('#REV_tb_strtdte').val()
                var REV_end_datevalue=$('#REV_tb_enddte').val()
                var rec_ver=$('#REV_lb_recver').val();
                var REV_project_recver=$('#REV_lb_recver').val();
                $('#REV_lbl_empday').hide();
                $('#REV_lbl_emptitle').hide();
                $('#REV_lbl_emptitle').text(employeetitles).hide();
                $('#REV_btn_emp_pdf').hide();
                $('#REV_div_loginid').hide();
                var formElement = document.getElementById("REV_form_revenue");
                var REV_prjctname=$('#REV_lb_empproject').val();
                var seacrhby_prjct=$("input[name=REV_rd_project]:checked").val();
//            var project_recver='';
                if(seacrhby_prjct=='project')
                {
                    project_recver=$('#REV_lb_recver').val();
                    if(project_recver=='SELECT')
                    {

                        project_recver=1;

                    }
                }
                else
                {

                    project_recver='';
                }
                $(".preloader").show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                        $(".preloader").hide();
                        loginidvalues=JSON.parse(xmlhttp.responseText);
                        if(loginidvalues==false)
                        {
                            var err=err_msg_array[4];
                            $('#REV_nodata_loginid').text(err).show();
                            $('#REV_btn_empsrch').attr("disabled","disabled");
                            $('#REV_nodata_loginiddivid').show();
                            $('#REV_lbl_emptitle').hide();
                            $('#REV_lbl_emptitle').text(employeetitles).hide();
                            $('#REV_btn_emp_pdf').hide();
                            $('#REV_div_loginid').hide();
                        }
                        if(loginidvalues!=null)
                        {
                            if(seacrhby_prjct=='project')
                            {
                                var emptitle=err_msg_array[7].toString().replace("[LOGINID]",uld_name);
                                employeetitles=emptitle.replace("[PROJECTNAME]",REV_prjctname);
                                if(REV_project_recver.length>1)
                                {
//                                $('#REV_lbl_emptitle').text(employeetitles+' '+'VER'+ - +project_recver).show();
                                }
                                else
                                {
                                    $('#REV_lbl_emptitle').text(employeetitles).hide();
                                    $('#REV_lbl_emptitle').hide();
                                }
                                var emptitle=err_msg_array[7].toString().replace("[LOGINID]",uld_name);
                                if(REV_project_recver.length>1)
                                {
                                    employeetitles=emptitle.replace("[PROJECTNAME]",REV_prjctname+' '+'VER'+ - +project_recver);
                                }
                                else
                                {
                                    employeetitles=emptitle.replace("[PROJECTNAME]",REV_prjctname);
                                }
                                var total_days= loginidvalues[0].working_day;
                                $('#REV_lbl_empday').text("TOTAL NO OF DAYS: "  +   total_days).show();
                                $('#REV_lbl_emptitle').text(employeetitles).show();
                                $('#REV_div_loginid').show();
                                $('#REV_btn_emp_pdf').show();
                                $('#REV_btn_emplist_pdf').hide();
                                var REV_table_header1='<table id="REV_tble_empday_nonactveemp1" border="1"  cellspacing="0" class="srcresult" style="width:500px";><thead  bgcolor="#6495ed" style="color:white"><tr><th class="uk-date-column" style="width:150px" nowrap>PROJECT DATE</th><th style="max-width:10px; !important;">DAYS</th><th style="max-width:10px; !important;">HOURS</th><th style="max-width:10px; !important;">MINUTES</th></tr></thead><tbody>'
                                for(var i=0;i<loginidvalues.length;i++){
                                    var projectdate=loginidvalues[i].projectdate;
                                    var project_days=loginidvalues[i].project_days;
                                    var project_hrs=loginidvalues[i].project_hrs;
                                    var project_mints=loginidvalues[i].project_mints;
                                    REV_table_header1+='<tr><td style="width:150px" nowrap align="center">'+projectdate+'</td><td align="center" style="max-width:10px; !important;">'+project_days+'</td><td align="center" style="max-width:10px; !important;">'+project_hrs+'</td><td align="center" style="max-width:10px; !important;">'+project_mints+'</td></tr>';
                                }
                            }
                            else
                            {
                                var loginname=uld_name;
                                var emptitle=err_msg_array[7].toString().replace("[LOGINID]",uld_name);
                                $('#REV_lbl_emptitle').text(heading).show();
//                        $('#REV_lbl_emptitle').hide();
                                $('#REV_btn_emplist_pdf').show();
                                var REV_table_header1='<table id="REV_tble_empday_nonactveemp1" border="1"  cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white"><tr><th class="uk-date-column"nowrap>PROJECT NAME</th><th>DAYS</th><th>HOURS</th><th>MINUTES</th></tr></thead><tbody>'
                                for(var i=0;i<loginidvalues.length;i++){
                                    var projectname=loginidvalues[i].projectname;
                                    var project_days=loginidvalues[i].project_days;
                                    var project_hrs=loginidvalues[i].project_hrs;
                                    var project_mints=loginidvalues[i].project_mints;
                                    REV_table_header1+='<tr><td style="width:150px" nowrap>'+projectname+'</td><td align="center">'+project_days+'</td><td align="center">'+project_hrs+'</td><td align="center">'+project_mints+'</td></tr>';
                                }
                            }
                            REV_table_header1+='</tbody></table>';
                            $('#REV_btn_empsrch').attr("disabled","disabled");
                            $('sections').html(REV_table_header1);
                            $('#REV_div_loginid').show();
                            $('#REV_tble_empday_nonactveemp1').DataTable({
                                "aaSorting": [],
                                "pageLength": 10,
                                "sPaginationType":"full_numbers",
                                "aoColumnDefs" : [
                                    { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"} ]
                            });
                        }
                        else
                        {
                            $(".preloader").hide();
                            var sd=err_msg_array[2].toString().replace("[LOGINID]",uld_name);

                            $('#REV_nodata_loginid').text(sd).show();
                            $('#REV_nodata_loginiddivid').show();

                            $('#REV_div_loginid').hide();
                        }
                    }
                }
                $('#REV_div_loginid').show();
                var option="nonactiveempdatatble";
                xmlhttp.open("GET","USER/DB_DAILY_REPORT_USER_PROJECT_REVENUE.do?option="+option+"&REV_prjctname="+REV_prjctname+"&REV_withproject="+seacrhby_prjct+"&project_recver="+project_recver);
                xmlhttp.send(formElement);
                sorting();
            });
            $(document).on('click','#REV_btn_emp_pdf',function(){
                if($("input[id=REV_rd_withproject]:checked").val()=="project"){
                    var inputValOne=uld_id;
                    var inputValTwo=$('#REV_lb_empproject').val();
                    var inputValThree=project_recver;
                    var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=6&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&inputValThree='+inputValThree+'&title='+employeetitles;
                }
            });
            $(document).on('click','#REV_btn_emplist_pdf',function(){

                if($("input[id=REV_rd_withoutproject]:checked").val()=="withoutproject"){
                    var inputValOne=uld_id;
                    var inputValFour=$('#REV_tb_strtdte').val();

                    inputValFour = inputValFour.split("-").reverse().join("-");
                    var inputValFive=$('#REV_tb_enddte').val();
                    inputValFive = inputValFive.split("-").reverse().join("-");
                    var heading="DETAILS FOR WORKED PROJECTS";
                    var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=7&inputValOne='+inputValOne+'&title='+heading;
//            var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=9&inputValOne='+inputValOne+'&inputValFour='+inputValFour+'&inputValFive='+inputValFive+'&title='+heading;
                }
            });
        });
    </script>
</head>
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="title text-center"><h4><b>REVENUE</b></h4></div>
    <form   id="REV_form_revenue" class="content">
        <div class="panel-body">
            <fieldset>
                <div style="padding-bottom: 15px">
                    <div style="padding-left:10px">
                        <div class="radio">
                            <label name="REV_lbl_withproject" id="REV_lbl_withproject"><input type="radio" name="REV_rd_project" id="REV_rd_withproject" value="project">LIST REVENUE BY PROJECT</label>
                        </div>
                    </div>
                    <div style="padding-left:10px">
                        <div class="radio">
                            <label name="REV_lbl_withoutproject" id="REV_lbl_withoutproject"><input type="radio" name="REV_rd_project" id="REV_rd_withoutproject"   value="withoutproject">LIST OF PROJECT REVENUE</label>
                        </div>
                    </div></div>
                <div class="row-fluid form-group" id="REV_lbl_empprojectdivid">
                    <label name="REV_lbl_empproject" class="col-sm-2" id="REV_lbl_empproject"  hidden>PROJECT<em>*</em></label>
                    <div class="col-sm-4">
                        <select id="REV_lb_empproject" name="REV_lb_empproject" class="vali form-control" style="display:none"></select>
                    </div>
                </div>
                <div class="row-fluid form-group" id="REV_lb_recverdivid">
                    <label name="REV_lbl_recver" class="col-sm-2" id="REV_lbl_recver"  hidden >RECORD VERSION<em>*</em></label>
                    <div class="col-sm-4">
                        <select id="REV_lb_recver" name="REV_lb_recver" class="rev_vali form-control"  style="display:none">
                        </select>
                    </div>
                </div>
                <div id="min_date" hidden>
                    <div class="form-group">
                        <div width="150"><label name="REV_lbl_strtdte" id="REV_lbl_strtdte" hidden>START DATE<em>*</em></label>
                            <input type="text" name="REV_tb_strtdte" id="REV_tb_strtdte" class=" valid clear REV_datepicker datemandtry" style="width:75px;" hidden>
                        </div></div>
                    <div>
                        <div width="150"><label name="REV_lbl_enddte" id="REV_lbl_enddte" hidden >END DATE<em>*</em></label>
                            <input type="text" name="REV_tb_enddte" id="REV_tb_enddte" class=" valid clear REV_datepicker datemandtry" style="width:75px;" hidden></td><br>
                        </div></div>
                </div>
                <div class="form-group"  style="padding-left: 10px">
                    <input type="button" id="REV_btn_empsrch" name="REV_btn_empsrch" value="SEARCH" class="btn" disabled/>
                </div>
                <div class="form-group" id="REV_nodata_loginiddivid"><label id="REV_nodata_loginid" name="REV_nodata_loginid" class="errormsg"></label></div>
                <div  style="padding-left: 10px">
                    <label id="REV_lbl_emptitle" name="REV_lbl_emptitle"  class="srctitle" hidden></label>
                </div>
                <div class="form-group" style="padding-left: 10px">
                    <label id="REV_lbl_empday" name="REV_lbl_empday"  class="srctitle" hidden></label>
                </div>
                <div style="padding-left: 10px"><input type="button" id="REV_btn_emp_pdf" class="btnpdf" value="PDF"></div>
                <div  style="padding-left: 10px"><input type="button" id="REV_btn_emplist_pdf" class="btnpdf" value="PDF"></div>
                <div id="REV_div_loginid" class="table-responsive row-fluid form-group col-sm-2" style="max-width:550px"  hidden>
                    <sections>
                    </sections>
                </div>
            </fieldset>
        </div>
    </form>
</div>
</body>
</html>