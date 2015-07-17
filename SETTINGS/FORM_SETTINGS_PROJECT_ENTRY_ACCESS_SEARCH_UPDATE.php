<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE PROJECT ACCESS*********************************************//
//DONE BY:LALITHA
//VER 0.03 SD:09/01/2014 ED:09/01/2014,TRACKER NO:74,Changed preloader position,Updated auto focus
//VER 0.02 SD:06/01/2014 ED:08/01/2014,TRACKER NO:74,Updated preloader position nd message box position,Changed loginid to emp name
//VER 0.01-INITIAL VERSION, SD:24/09/2014 ED:29/09/2014,TRACKER NO:79
//*********************************************************************************************************//
<?php
include "../TSLIB/TSLIB_HEADER.php";
//include  "NEW_MENU.php";
?>
<!--SCRIPT TAG START-->
<html>
<head>
    <script xmlns="http://www.w3.org/1999/html">
        //GLOBAL DECLARATION
        var err_msg_array=[];
        var EMP_ENTRY_loginid=[];
        var project_array=[];
        var EMPSRC_UPD_loginid=[];
        var EMPSRC_UPD_proj_array=[];
        var EMPSRC_UPD_proj_id=[];
        var SubPage=1;
        //START DOCUMENT READY FUNCTION
        $(document).ready(function(){
            first()
            $(".preloader").hide();
            $('textarea').autogrow({onInitialize: true});
            $(".autosize").doValidation({rule:'general',prop:{autosize:true}});
            var EMP_ENTRY_empname;
            var EMPSRC_UPD_empname;
            var error_message=[];
            var comp_start_date;
            var project_status=[];
            var proj_auto;
            $(document).on('click','#project',function(){
                $('#prj').show();
                $('#entry').hide();
                $('#EMP_lbl_report_access').hide();
                $('#EMP_lbl_report_search').hide();
                $('#EMP_lbl_report_entrys').hide();
                $('#EMPSRC_UPD_lb_loginid').hide();
                $('#EMPSRC_UPD_lbl_loginid').hide();
                $('#EMP_ENTRY_lbl_loginid').hide();
                $('#EMP_ENTRY_lb_loginid').hide();
                $('#EMPSRC_UPD_btn_update').hide();
                $('#EMPSRC_UPD_btn_reset').hide();
                $('#project_access').removeAttr('checked');
                $('#project_search').removeAttr('checked');

            });
            $(document).on('click','#project_entry',function(){
                var radiooption=$(this).val();
                $('#PE_nodataerrormsg').hide();
                if(radiooption=='entry')
                {
                    $('#EMP_lbl_report_entrys').show();
                    $('#EMP_ENTRY_lbl_nologinid').hide();
                    $('#PE_nodataerrormsg').hide();
                    $('#prj').hide();
                    $('#entry').show();
                    var  CACS_VIEW_customername;
                    get_Values();
                    $('#EMP_lbl_report_entrys').html('PROJECT ENTRY SEARCH UPDATE');
                    $('#EMP_lbl_report_access').hide();
                    $('#EMP_lbl_report_search').hide();
                    $('#EMPSRC_UPD_btn_update').hide();
                    $('#EMPSRC_UPD_btn_reset').hide();
                    $('#EMP_ENTRY_lbl_nologinid').hide();
                    $('#EMPSRC_UPD_lb_loginid').hide();
                    $('#EMPSRC_UPD_lbl_loginid').hide();
                    $('#PE_tb_prjectname').val('');
                    $('#PE_tb_status').val('');
                    $('#PE_ta_prjdescrptn').val('');
                    $('#EMP_ENTRY_lb_loginid').hide();
                    $('#EMPSRC_UPD_lbl_nologinid').hide();
                    $('#EMPSRC_UPD_tble_projectlistbx').hide();
                    $('#EMPSRC_UPD_lbl_txtselectproj').hide();
                    $('#EMPSRC_UPD_tble_frstsel_projectlistbx').hide();
                    $('#EMP_ENTRY_btn_save').hide();
                    $('#EMP_ENTRY_btn_reset').hide();
                    $('#EMP_ENTRY_lbl_loginid').hide();
                    $('#EMP_ENTRY_lb_loginid').val('select');
                    $('#EMP_ENTRY_lbl_nologinid').hide();
                    $('#EMP_ENTRY_tble_projectlistbx').hide();
                    $('#EMP_ENTRY_lbl_txtselectproj').hide();
                    $('#EMP_ENTRY_tble_frstsel_projectlistbx').hide();
                    //AUTOCOMPLETE TEXT
                    error_message=[];
                    comp_start_date;
                    project_status=[];
                    function get_Values(){
                        var xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                                $(".preloader").hide();
                                var values=JSON.parse(xmlhttp.responseText);
                                proj_auto=values[0];
                                error_message=values[1];
                                comp_start_date=values[2];
                                project_status=values[3];
                                CACS_VIEW_customername=proj_auto;
                            }
                        }
                        var option='AUTO';
                        xmlhttp.open("GET","SETTINGS/DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?&option="+option,true);
                        xmlhttp.send();

                    }
                    showTable();
                }

            });
            //DATE PICKER FUNCTION
            $('.PE_tb_sdatedatepicker').datepicker({
                dateFormat:"dd-mm-yy",
                maxDate: Date(),
                changeYear: true,
                changeMonth: true
            });
            //DATE PICKER FUNCTION
            $('.PE_tb_edatedatepicker').datepicker({
                dateFormat:"dd-mm-yy",
                maxDate: Date(),
                changeYear: true,
                changeMonth: true
            });
            //CHANGE EVENT FOR STARTDATE
            $(document).on('change','#PE_tb_sdate',function(){
                var PE_startdate = $('#PE_tb_sdate').datepicker('getDate');
                var date = new Date( Date.parse( PE_startdate ));
                date.setDate( date.getDate()  );
                var PE_enddate = date.toDateString();
                PE_enddate = new Date( Date.parse( PE_enddate ));
                $('#PE_tb_edate').datepicker("option","minDate",PE_enddate);
                var max_date=new Date(PE_startdate);
                var month=max_date.getMonth();
                var year=max_date.getFullYear()+2;
                var date=max_date.getDate();
                var max_date = new Date(year,month,date);
                $('#PE_tb_edate').datepicker("option","maxDate",max_date);
            });
            //BLUR FUNCTION FOR PROJECT NAME
            $(document).on("change blur",'#projectname',function(){
                var checkproject_name=$(this).val();
                if(checkproject_name!=''){
                    $(".preloader").show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            var check_array=JSON.parse(xmlhttp.responseText);
                            if(check_array[0]==1){
                                $("#PE_btn_update").attr("disabled", "disabled");
                            }
                            else{
                                $("#PE_btn_update").removeAttr("disabled");
                            }
                        }
                    }
                    var option='CHECK';
                    xmlhttp.open("GET","SETTINGS/DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?checkproject_name="+checkproject_name+"&option="+option,true);
                    xmlhttp.send();
                }
            });
            //BLUR FUNCTION FOR PROJECT DESCRIPTION
            $(document).on("change blur",'#PE_ta_prjdescrptn',function(){
                $('#PE_ta_prjdescrptn').val($('#PE_ta_prjdescrptn').val().toUpperCase())
                var trimfunc=($('#PE_ta_prjdescrptn').val()).trim()
                $('#PE_ta_prjdescrptn').val(trimfunc)
            });
            $(document).on("change blur",'#projectdes',function(){
                $('#projectdes').val($('#projectdes').val().toUpperCase())
                var trimfunc=($('#projectdes').val()).trim()
                $('#projectdes').val(trimfunc)
            });
            //CHANGE EVENT FOR PROJECT TEXT BOX
            $(document).on("change blur",'#PE_tb_prjectname', function (){
                $('#PE_ta_prjdescrptn').val("");
                $('#PE_tb_edate').val('');
                $('#PE_tb_sdate').val('');
                var PE_startdate=(comp_start_date).split('-');
                var day=PE_startdate[0];
                var month=PE_startdate[1];
                var year=PE_startdate[2];
                PE_startdate=new Date(year,month-1,day);
                $('#PE_tb_sdate').datepicker("option","minDate",PE_startdate);
                var max_date=new Date();
                var month=max_date.getMonth();
                var year=max_date.getFullYear()+2;
                var date=max_date.getDate();
                var max_date = new Date(year,month,date);
                $('#PE_tb_sdate').datepicker("option","maxDate",max_date);

                var checkproject_name=($(this).val()).trim();
                if(checkproject_name!=''){
                    $('#PE_tb_prjectname').val(checkproject_name.toUpperCase())
                    $(".preloader").show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            var check_array=JSON.parse(xmlhttp.responseText);
                            var min_enddate=check_array[1];
                            var max_date=new Date(min_enddate);
                            var month=max_date.getMonth();
                            var year=max_date.getFullYear();
                            var date=max_date.getDate()+1;
                            var mindate = new Date(year,month,date);
                            var count=0;
                            for(var i=0;i<CACS_VIEW_customername.length;i++){
                                if(CACS_VIEW_customername[i]==checkproject_name){
                                    $('#PE_tb_sdate').datepicker("option","minDate",new Date(mindate));
                                    var max_date=new Date();
                                    var month=max_date.getMonth();
                                    var year=max_date.getFullYear()+2;
                                    var date=max_date.getDate();
                                    var max_date = new Date(year,month,date);
                                    $('#PE_tb_sdate').datepicker("option","maxDate",max_date);
//                            $('#PE_ta_prjdescrptn').val(desc);
                                    $('#PE_tb_status').val(project_status[1][1]);
                                    $('#PE_lbl_erromsg').hide();
                                    $('#PE_ta_prjdescrptn').val(check_array[2]);
                                    count=1;
                                    break;
                                    //reopen
                                }
                            }
                            if(count!=1){
                                if(check_array[0]==1){
                                    $('#PE_lbl_erromsg').text(error_message[0]).show();
                                    $('#PE_tb_status').val('');
                                    $("#PE_btn_save").attr("disabled", "disabled");
                                }
                                else
                                {
                                    $('#PE_lbl_erromsg').hide();
                                    $('#PE_tb_status').val(project_status[0][1]).show();
                                    validation();
                                }
                            }

                        }
                    }
                    var option='CHECK';
                    xmlhttp.open("GET","SETTINGS/DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?checkproject_name="+checkproject_name+"&option="+option,true);
                    xmlhttp.send();
                }
                else{
                    $('#PE_lbl_erromsg').hide();
                }
            });
            //FUNCTION TO HIGHLIGHT SEARCH TEXT
            function CACS_VIEW_highlightSearchText() {
                $.ui.autocomplete.prototype._renderItem = function( ul, item) {
                    var re = new RegExp(this.term, "i") ;
                    var t = item.label.replace(re,"<span class=autotxt>" + this.term + "</span>");//higlight color,class shld be same as here
                    return $( "<li></li>" )
                        .data( "item.autocomplete", item )
                        .append( "<a>" + t + "</a>" )
                        .appendTo( ul );
                }
            };
            //FUNCTION TO AUTOCOMPLETE SEARCH TEXT
            var CACS_VIEW_customername=[];
            var CACS_VIEW_customerflag;
            $("#PE_tb_prjectname").keypress(function(){
                CACS_VIEW_customerflag=0;
                CACS_VIEW_highlightSearchText();
                $("#PE_tb_prjectname").autocomplete({
                    source: CACS_VIEW_customername,
                    select:CACS_VIEW_AutoCompleteSelectHandler
                });
            });
//FUNCTION TO GET SELECTED VALUE
            function CACS_VIEW_AutoCompleteSelectHandler(event, ui) {
                CACS_VIEW_customerflag=1;
                $('#CACS_VIEW_lbl_custautoerrmsg').hide();
            }
// CLICK EVENT FOR SAVE BUTTON
            $(document).on('click','#PE_btn_save',function(){
                $(".preloader").show();
                var formElement = document.getElementById("EMP_ENTRY_form_employeename");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        var msg_alert=xmlhttp.responseText;

                        if(msg_alert==1)
                        {
                            show_msgbox("PROJECT ENTRY /ACCESS /SEARCH /UPDATE",error_message[1],"success",false);
                            $("#PE_tb_prjectname").val('').show();
                            $("#PE_ta_prjdescrptn").val('').show();
                            $("#PE_tb_sdate").val('').show();
                            $("#PE_tb_edate").val('').show();
                            $("#PE_tb_status").val('').show();
                            $("#PE_btn_save").attr("disabled", "disabled");
                            first()
                        }
                        else if(msg_alert==0)
                        {
                            show_msgbox("PROJECT ENTRY /ACCESS /SEARCH /UPDATE",error_message[2],"success",false);
                            $("#PE_tb_prjectname").val('').show();
                            $("#PE_ta_prjdescrptn").val('').show();
                            $("#PE_tb_sdate").val('').show();
                            $("#PE_tb_edate").val('').show();
                            $("#PE_tb_status").val('').show();
                            $("#PE_btn_save").attr("disabled", "disabled");
                            get_Values();
                            first()
                        }
                        else
                        {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UPDATE",msgcontent:msg_alert,position:{top:150,left:500}}});
                            show_msgbox("PROJECT ENTRY /ACCESS /SEARCH /UPDATE",msg_alert,"success",false);
                            $("#PE_tb_prjectname").val('').show();
                            $("#PE_ta_prjdescrptn").val('').show();
                            $("#PE_tb_sdate").val('').show();
                            $("#PE_tb_edate").val('').show();
                            $("#PE_tb_status").val('').show();
                            $("#PE_btn_save").attr("disabled", "disabled");
                            showTable();
                            get_Values();
                        }
                    }
                }
                var option='SAVE';
                xmlhttp.open("POST","SETTINGS/DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?option="+option,true);
                xmlhttp.send(new FormData(formElement));
            });
            //FUNCTION FOR VALIDATION
            function validation(){
                var projectname= $('#PE_tb_prjectname').val();
                var projectsdate= $("#PE_tb_sdate").val();
                var projectstatus=$("#PE_tb_status").val();
                var projectdes=$("#PE_ta_prjdescrptn").val().trim();
                var projectedate=$("#PE_tb_edate").val();
                if((projectname!="") &&(projectstatus!='')&& (projectsdate!="") && (projectdes !="")&&(projectedate!=""))
                {
                    $("#PE_btn_save").removeAttr("disabled");
                }
                else
                {
                    $("#PE_btn_save").attr("disabled", "disabled");
                }
            }
// SAVE BUTTON VALIDATION
            $(document).on('change blur','.valid',function(){
                validation();
            });
// CREATING UPDATE AND CANCEL BUTTON
            var data='';
            var action = '';
            var updatebutton = "<input type='button' id='PE_btn_update' class='ajaxupdate btn' disabled value='Update'>";
            var cancel = "<input type='button' class='ajaxcancel btn' value='Cancel'>";
            var pre_tds;
            var field_arr = new Array('text','text');
            var field_name = new Array('projectname','projectdes');
            // FUNCTION FOR DATETABLE
            function showTable(){
                $.ajax({
                    url:"SETTINGS/DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do",
                    type:"POST",
                    data:"option=showData",
                    cache: false,
                    success: function(response){
                        if(response!=0){
                            $('#PE_lbl_title').text(error_message[6]).show();
                            $('#PE_btn_pdf').show();
                            var header='<table id="demoajax" border="1" cellspacing="0" class="srcresult">';//<thead  bgcolor="#6495ed" style="color:white"><tr ><th  width=200>PROJECT NAME</th><th width=500 >PROJECT DESCRIPTION</th><th width=10>REC VER</th><th width=30>STATUS</th><th width=50 class="uk-date-column">START DATE</th><th width=50 class="uk-date-column">END DATE</th><th style="min-width:70px;">USERSTAMP</th><th style="min-width:100px;" nowrap class="uk-timestp-column">TIMESTAMP</th><th width=110>EDIT</th></tr></thead><tbody>';

                            header+=response;
//                    header+='</tbody></table>';
                            $('section').html(header);
                            $('#demoajax').DataTable({
                                "aaSorting": [],
                                "pageLength": 10,
                                "sPaginationType":"full_numbers",
                                "aoColumnDefs" : [
                                    { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                            });
                            $('#tablecontainer').show();
                            sorting();
                        }
                        else
                        {
                            $('#PE_nodataerrormsg').text(error_message[5]).show();
                            $('#PE_lbl_title').text(error_message[6]).hide();
//                    $('#PE_btn_pdf').hide();
                            $('#tablecontainer').hide();
                        }

                    }

                });

            }
            //FUNCTION FOR FORMTABLEDATEFORMAT
            function FormTableDateFormat(inputdate){
                var string = inputdate.split("-");
                return string[2]+'-'+ string[1]+'-'+string[0];
            }
// CLICK EVENT FOR EDIT BUTTON
            $(document).on('click','.ajaxedit',function(){
                $('.ajaxedit').attr("disabled","disabled");
                var combineid = $(this).parent().parent().attr('id');
                var combineid_split=combineid.split('_');
                var edittrid=combineid_split[0];
                var tds = $('#'+combineid).children('td');
                var tdstr = '';
                var td = '';
                pre_tds = tds;
                tdstr += "<td><input type='text' id='projectname' name='projectname'  class='autosize enable' style='font-weight:bold;' value='"+($(tds[0]).html()).trim()+"'></td>";
                tdstr += "<td><textarea id='projectdes' name='projectdes'  class='enable' value='"+$(tds[1]).html()+"'></textarea></td>";
                tdstr += "<td><input type='text' id='recver' name='recver' style='width:25px';  value='"+$(tds[2]).html()+"' readonly></td>";
//        if($(tds[3]).html()=='STARTED'||$(tds[3]).html()=='REOPEN'){
//            tdstr+="<td><select id='status' name='status' class='enable'><option value="+$(tds[3]).html()+">"+$(tds[3]).html()+"</option><option value='CLOSED'>CLOSED</option></select></td>";
//        }
//        else if($(tds[3]).html()=='CLOSED'){
//            tdstr+="<td><select id='status' name='status' class='enable'><option value="+$(tds[3]).html()+">"+$(tds[3]).html()+"</option><option value='STARTED'>STARTED</option></select></td>";
//        }
                tdstr+="<td><select id='status' name='status' class='enable'></select></td>";
                tdstr+="<td nowrap><input type='text' id='std' name='start_date' style='width:75px'; class='PE_tb_edatedatepicker  enable datemandtry ' value='"+$(tds[4]).html()+"'></td>";
                tdstr+="<td nowrap><input type='text' name='end_date' id='PE_tb_enddate' style='width:75px'; class='PE_tb_edatedatepicker enable datemandtry' value='"+$(tds[5]).html()+"' ></td>";
                tdstr+="<td>"+$(tds[6]).html()+"</td>";
                tdstr+="<td nowrap>"+$(tds[7]).html()+"</td>";
                tdstr+="<td>"+updatebutton +" " + cancel+"</td>";
                $('#'+combineid).html(tdstr);
                $('#projectdes').val($(tds[1]).html());
                var status='';
                if($(tds[3]).html()==project_status[0][1]||$(tds[3]).html()==project_status[1][1]){
                    for (var i=0;i<project_status.length;i++) {
                        if(project_status[i][0]==1||project_status[i][0]==2)continue;
                        status += '<option value="' + project_status[i][1] + '">' + project_status[i][1] + '</option>';
                    }
                }
                else if($(tds[3]).html()==project_status[2][1]){
                    for (var i=0;i<project_status.length;i++) {
                        if(project_status[i][0]==2|| project_status[i][0]==3)continue;
                        status += '<option value="' + project_status[i][1] + '">' + project_status[i][1] + '</option>';
                    }
                }
                status+='<option value="' + $(tds[3]).html() + '">' +$(tds[3]).html()+ '</option>'
                $('#status').html(status);
                $('#status').val($(tds[3]).html());
                $('.PE_tb_edatedatepicker').datepicker({
                    dateFormat:"dd-mm-yy",
                    changeYear: true,
                    changeMonth: true
                });
                var PE_startdate=($('#std').val()).split('-');
                var day=PE_startdate[0];
                var month=PE_startdate[1];
                var year=PE_startdate[2];
                PE_startdate=new Date(year,month-1,day);
                var date = new Date( Date.parse( PE_startdate ));
                date.setDate( date.getDate()  );
                var PE_enddate = date.toDateString();
                PE_enddate = new Date( Date.parse( PE_enddate ));
                $('#PE_tb_enddate').datepicker("option","minDate",PE_enddate);
                var max_date=new Date();
                var month=max_date.getMonth();
                var year=max_date.getFullYear()+2;
                var date=max_date.getDate();
                var max_date = new Date(year,month,date);
                $('#PE_tb_enddate').datepicker("option","maxDate",max_date);
                $(".autosize").doValidation({rule:'general',prop:{autosize:true}});
                var PE_sdate=(comp_start_date).split('-');
                var day=PE_sdate[0];
                var month=PE_sdate[1];
                var year=PE_sdate[2];
                PE_sdate=new Date(year,month-1,day);
                $('#std').datepicker("option","minDate",PE_sdate);
                var max_date=new Date();
                var month=max_date.getMonth();
                var year=max_date.getFullYear()+2;
                var date=max_date.getDate();
                var max_date = new Date(year,month,date);
                $('#std').datepicker("option","maxDate",max_date);
                $('#std').change(function(){
                    var PE_startdate=($('#std').val()).split('-');
                    var day=PE_startdate[0];
                    var month=PE_startdate[1];
                    var year=PE_startdate[2];
                    PE_startdate=new Date(year,month-1,day);
                    var date = new Date( Date.parse( PE_startdate ));
                    date.setDate( date.getDate()  );
                    var PE_enddate = date.toDateString();
                    PE_enddate = new Date( Date.parse( PE_enddate ));
                    $('#PE_tb_enddate').datepicker("option","minDate",PE_enddate);
                    var max_date=new Date(PE_startdate);
                    var month=max_date.getMonth();
                    var year=max_date.getFullYear()+2;
                    var date=max_date.getDate();
                    var max_date = new Date(year,month,date);
                    $('#PE_tb_enddate').datepicker("option","maxDate",max_date);
                });
            });
// UPDATE BUTTON VALIDATION
            $(document).on('change blur','.enable',function(){
                var projectname= $('#projectname').val();
                var projectsdate= $("#std").val();
                var projectstatus=$("#status").val();
                var projectdes=$("#projectdes").val().trim();
                var projectedate=$("#PE_tb_enddate").val();
                if((projectname!="") && (projectstatus!='') && (projectsdate!="") && (projectdes !="") && (projectedate!=""))
                {
                    $("#PE_btn_update").removeAttr("disabled");
                }
                else
                {
                    $("#PE_btn_update").attr("disabled", "disabled");
                }
            });
            //CLICK EVENT FOR CANCEL BUTTON
            $(document).on("click",'.ajaxcancel', function (){
                $('.ajaxedit').removeAttr("disabled");
            });
//CLICK EVENT FOR UPDATE BUTTON
            $(document).on("click",'.ajaxedit', function (){
                var checkproject_name=$('#projectname').val();
                var rec_ver=$('#recver').val();
                if(checkproject_name!=''){
//            $('.preloader', window.parent.document).show();
                    $(".preloader").show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
                            var check_array=(xmlhttp.responseText);
                            if(check_array==1){
                                $('#std').prop('disabled','disabled');
                            }
                            else
                            {
                                $('#std').removeAttr('disabled');
                            }
                        }
                    }
                    var option='RANDOM';
                    xmlhttp.open("GET","SETTINGS/DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?checkproject_name="+checkproject_name+"&option="+option+"&recver="+rec_ver,true);
                    xmlhttp.send();
                }
            });
            $(document).on('click','#PE_btn_pdf',function(){
                var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=17&title='+error_message[6];
            });

            function first()
            {
                $.ajax({
                    type: 'POST',
                    url: 'SETTINGS/DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do',
                    data:{option:'edit'},
                    success: function(data){
//                    alert(data);
                        $('section').html(data);
                        $('#tablecontainer').show();
                        $('#PE_btn_pdf').show();
                        $('#reg').DataTable( {
                            "aaSorting": [],
                            "pageLength": 10,
                            "responsive": true,
                            "sPaginationType":"full_numbers"
                        });
                    },
                    error:function(data){
                        alert('error in getting'+JSON.stringify(data));
                    }
                })
            }
            var previous_id;
            var combineid;
            var pdid;
            var psid;
            var tdvalue;
            var ifcondition;
            $(document).on('click','.edit', function (){
                if(previous_id!=undefined){
                    $('#'+previous_id).replaceWith("<td class='edit' id='"+previous_id+"' >"+tdvalue+"</td>");
                }
                var cid = $(this).attr('id');
//        alert(cid)
                var id=cid.split('_');
                ifcondition=id[0];
                previous_id=cid;
//        alert(psid);
                pdid=id[1];
                psid=id[2];
                tdvalue=$(this).text();

                if(ifcondition=='pname')
                {
                    $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><input type='text' id='project_name' name='project_name'  class='update' maxlength='50'  value='"+tdvalue+"'></td>");
                }
                if(ifcondition=='pdesc')
                {
                    $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><textarea id='project_des' name='project_des'  class='update' maxlength='50'  value='"+tdvalue+"'>"+tdvalue+"</textarea></td>");
                }
                if(ifcondition=='status')
                {
//                alert(project_status)
                    $('#'+cid).replaceWith("<td  class='new' id='"+previous_id+"'><select class='update' id='project_status' style='width: 250px;'></select></td>");
                    var status='<option value="SELECT">SELECT</option>';

                    for(var k=0;k<project_status.length;k++){


                        status += '<option value="'+project_status[k][0]+'">'+ project_status[k][1]+'</option>';
                    }

                    $('#project_status').html(status);
                    $('#project_status').val(project_status[0]);
                }
//

                if(ifcondition=='sdate')
                {
                    $('#'+cid).replaceWith("<td  class='new' id='"+previous_id+"'><input type='text' id='start_date' name='start_date'  class='update date-picker' style='width: 110px'  value='"+tdvalue+"'></td>");
                    $(".date-picker").datepicker({dateFormat:'dd-mm-yy',
                        changeYear: true,
                        changeMonth: true
                    });
                    $('.date-picker').datepicker("option","maxDate",new Date());
                }
                if(ifcondition=='edate')
                {
                    $('#'+cid).replaceWith("<td  class='new' id='"+previous_id+"'><input type='text' id='end_date' name='end_date'  class='update date-picker' style='width: 110px'  value='"+tdvalue+"'></td>");
                    $(".date-picker").datepicker({dateFormat:'dd-mm-yy',
                        changeYear: true,
                        changeMonth: true
                    });
                    $('.date-picker').datepicker("option","maxDate",new Date());
                }


            } );

            $(document).on('change','.update',function(){
                $('.preloader').show();

                if($('#pname_'+pdid+'_'+psid).hasClass("edit")==true){
                    var babypname=$('#pname_'+pdid+'_'+psid).text();
                }
                else{
                    var babypname=$('#project_name').val().toUpperCase();
                }

                if($('#pdesc_'+pdid+'_'+psid).hasClass("edit")==true){

                    var babypdesc=$('#pdesc_'+pdid+'_'+psid).text();
                }
                else{
                    var babypdesc=$('#project_des').val().toUpperCase();
                }

                if($('#status_'+pdid+'_'+psid).hasClass("edit")==true){

                    var babystatus=$('#status_'+pdid+'_'+psid).text();
                }
                else{
                    var babystatus=$('#project_status').find('option:selected').text();

                }
                if($('#sdate_'+pdid+'_'+psid).hasClass("edit")==true){

                    var babysdate=$('#sdate_'+pdid+'_'+psid).text();
                }
                else{
                    var babysdate=$('#start_date').val();
                }
                if($('#edate_'+pdid+'_'+psid).hasClass("edit")==true){

                    var babyedate=$('#edate_'+pdid+'_'+psid).text();
                }
                else{
                    var babyedate=$('#end_date').val();
                }
//        alert('&option=update&pdid='+pdid+'&psid='+psid+'&babypname='+babypname+'&babypdesc='+babypdesc+'&babystatus='+babystatus+'&babysdate='+babysdate+'&babyedate='+babyedate)
                $.ajax({
                    type: 'POST',
                    url: 'SETTINGS/DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do',
//            data:'&rowid='+combineid+'&babypname='+babypname+'&babypdesc='+babypdesc+'&babystatus='+babystatus+'&babysdate='+babysdate+'&babyedate='+babyedate,
                    data:'&option=update&pdid='+pdid+'&psid='+psid+'&babypname='+babypname+'&babypdesc='+babypdesc+'&babystatus='+babystatus+'&babysdate='+babysdate+'&babyedate='+babyedate,
                    success: function(data) {
//                alert(data)
                        $('.preloader').hide();
                        var resultflag=data;
                        if(resultflag==1){
//                    alert('1');
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UPDATE",msgcontent:error_message[3],position:{top:150,left:520}}});
                            show_msgbox("PROJECT ENTRY /ACCESS /SEARCH /UPDATE",error_message[3],"success",false);
                            previous_id=undefined;
                            first()
//                        get_Values();
                        }
                        else if(resultflag==0)
                        {
//                    alert('2');
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UPDATE",msgcontent:error_message[4],position:{top:150,left:520}}});
                            show_msgbox("PROJECT ENTRY /ACCESS /SEARCH /UPDATE",error_message[4],"success",false);
                            previous_id=undefined;
                            first()
//                        get_Values()/;
                        }
                        else
                        {
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UPDATE",msgcontent:response,position:{top:150,left:520}}});
                            show_msgbox("PROJECT ENTRY /ACCESS /SEARCH /UPDATE",response,"success",false);
                            previous_id=undefined;
                            first()
//                        get_Values();
                        }
                    }
                });
            }) ;

            $(document).on('click','.prjclick',function(){
                var radiooption=$(this).val();
                if(radiooption=='access')
                {
                    $('#EMP_lbl_report_access').show();
                    $(".preloader").show();
                    $('#EMP_lbl_report_access').html('PROJECT ACCESS');
                    $('#EMP_lbl_report_search').hide();
//            $('#option').val('SELECT');
                    $('#access').show();
                    $('#search').hide();
                    $('#EMP_ENTRY_lb_loginid').hide();
                    $('#EMPSRC_UPD_lb_loginid').val('select');
                    $('#EMPSRC_UPD_lbl_nologinid').hide();
                    $('#EMPSRC_UPD_tble_projectlistbx').hide();
                    $('#EMPSRC_UPD_lbl_txtselectproj').hide();
                    $('#EMPSRC_UPD_tble_frstsel_projectlistbx').hide();
//        $('.preloader', window.parent.document).show();
                    $('#EMP_ENTRY_btn_save').hide();
                    $('#EMP_ENTRY_btn_reset').hide();
                    EMP_ENTRY_empname;
                    initialload();
                    //FUNCTION FOR GETTING PROJECT LIST,ERR MSG,LOGIN ID
                    function initialload(){
                        var xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                                $(".preloader").hide();
                                var values_array=JSON.parse(xmlhttp.responseText);
                                EMP_ENTRY_loginid=values_array[0];
                                project_array=values_array[1];
                                err_msg_array=values_array[2];
                                if(EMP_ENTRY_loginid.length!=0)
                                {
                                    var active_employee='<option>SELECT</option>';
                                    for (var i=0;i<EMP_ENTRY_loginid.length;i++) {
                                        active_employee += '<option value="' + EMP_ENTRY_loginid[i][1]+ '">' + EMP_ENTRY_loginid[i][0]+ '</option>';
                                    }
                                    $('#EMP_ENTRY_lb_loginid').html(active_employee);
                                    $('#EMP_ENTRY_lbl_loginid').show();
                                    $('#EMP_ENTRY_lb_loginid').show();
                                }
                                else
                                {
                                    $('#EMP_ENTRY_lbl_nologinid').text(err_msg_array[1]).show();
                                    $('#EMP_ENTRY_lbl_loginid').hide();
                                    $('#EMP_ENTRY_lb_loginid').hide();
                                }
                            }
                        }
                        var option="common";
                        xmlhttp.open("GET","SETTINGS/DB_EMPLOYEE_PROJECT_ACCESS.do?option="+option);
                        xmlhttp.send();
                    }
                }
                else if(radiooption=='search')
                {
                    $('#EMP_lbl_report_search').show();
                    $(".preloader").show();
                    $('#EMP_lbl_report_search').html('PROJECT SEARCH UPDATE');
                    $('#EMP_lbl_report_access').hide();
//            $('#option').val('SELECT');
                    $('#search').show();
                    $('#access').hide();
                    $('#EMP_ENTRY_lbl_loginid').hide();
                    $('#EMP_ENTRY_lb_loginid').val('select');
                    $('#EMP_ENTRY_lbl_nologinid').hide();
                    $('#EMP_ENTRY_tble_projectlistbx').hide();
                    $('#EMP_ENTRY_lbl_txtselectproj').hide();
                    $('#EMP_ENTRY_tble_frstsel_projectlistbx').hide();

//        $('.preloader', window.parent.document).show()
                    $('#EMPSRC_UPD_btn_update').hide();
                    $('#EMPSRC_UPD_btn_reset').hide();
                    EMPSRC_UPD_empname;
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader', window.parent.document).hide()
                            $(".preloader").hide();
                            var EMPSRC_UPD_loginid=$('#EMPSRC_UPD_lb_loginid').val();
//                        alert(xmlhttp.responseText);
                            var values_array=JSON.parse(xmlhttp.responseText);
                            EMPSRC_UPD_loginid=values_array[0];
                            err_msg_array=values_array[1];
                            if(EMPSRC_UPD_loginid.length!=0)
                            {
                                var active_employee='<option>SELECT</option>';
                                for (var i=0;i<EMPSRC_UPD_loginid.length;i++) {
                                    active_employee += '<option value="' + EMPSRC_UPD_loginid[i][1] + '">' + EMPSRC_UPD_loginid[i][0] + '</option>';
                                }
                                $('#EMPSRC_UPD_lb_loginid').html(active_employee);
                                $('#EMPSRC_UPD_lbl_loginid').show();
                                $('#EMPSRC_UPD_lb_loginid').show();
                            }
                            else
                            {
                                $('#EMPSRC_UPD_lbl_nologinid').text(err_msg_array[1]).show();
                            }
                        }
                    }
                    var option="common";
                    xmlhttp.open("GET","SETTINGS/DB_EMPLOYEE_PROJECT_SEARCH_UPDATE.do?option="+option);
                    xmlhttp.send();
                }
                else if(radiooption=='entry')
                {

                }
            });

//        $('.preloader', window.parent.document).show();
            $(".preloader").hide();

            //FUNCTION FOR PROJECT LIST
            function projectlist(){
                $('#EMP_ENTRY_tble_frstsel_projectlistbx').html('');
                var project_list;
                for (var i=0;i<project_array.length;i++) {
                    project_list += '<tr><td><input type="checkbox" id ="checkbox" name="checkbox[]" value="' + project_array[i][1] + '">' + project_array[i][0] + '-' +project_array[i][2] +'</td></tr>';
                }
                $('#EMP_ENTRY_tble_frstsel_projectlistbx').append(project_list).show();
            }
            //CHANGE EVENT FOR ACTIVE LOGIN ID
            $('#EMP_ENTRY_lb_loginid').change(function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                //PRELOADER ADJUST FUNCTION
//            $('.preloader', window.parent.document).show();
                $(".preloader").show();
                $('input:checkbox[id=checkbox]').attr('checked',false);
                $('#checkbox').attr('checked',false);
                EMP_ENTRY_empname=$("#EMP_ENTRY_lb_loginid option:selected").text();
                if(EMP_ENTRY_empname=="SELECT")
                {
//                $('.preloader', window.parent.document).hide();
                    $(".preloader").hide();
                    $('#EMP_ENTRY_btn_save').hide();
                    $('#EMP_ENTRY_btn_reset').hide();
                    $('#EMP_ENTRY_tble_frstsel_projectlistbx').hide();
                    $('#EMP_ENTRY_tble_projectlistbx').hide();
                    $('#EMP_ENTRY_lbl_txtselectproj').hide();
                }
                else
                {
                    projectlist();
//                $('.preloader', window.parent.document).hide();
                    $(".preloader").hide();
                    $('#EMP_ENTRY_lb_loginid').show();
                    $('#EMP_ENTRY_lbl_loginid').show();
                    $('#EMP_ENTRY_btn_save').attr("disabled","disabled").show();
                    $('#EMP_ENTRY_btn_reset').show();
                    $('#checkbox').attr('checked',false).show();
                    $('#EMP_ENTRY_tble_projectlistbx').show();
                    $('#EMP_ENTRY_lbl_txtselectproj').show();
                }
            });
            //CLICK EVENT FUCNTION FOR RESET
            $('#EMP_ENTRY_btn_reset').click(function()
            {
                EMP_ENTRY_rset()
            });
            //CLEAR ALL FIELDS
            function EMP_ENTRY_rset()
            {
                $('#EMP_ENTRY_lb_loginid').val('SELECT');
                $('#EMP_ENTRY_btn_save').hide();
                $('#EMP_ENTRY_btn_reset').hide();
                $('#EMP_ENTRY_tble_frstsel_projectlistbx').hide();
                $('#EMP_ENTRY_tble_projectlistbx').hide();
                $('#EMP_ENTRY_lbl_txtselectproj').hide();
            }
            //FORM VALIDATION
            $(document).on('change blur','#EMP_ENTRY_form_employeename',function(){
                var EMP_ENTRY_loginid = $("#EMP_ENTRY_lb_loginid").val();
                var EMP_ENTRY_projectselectlistbx = $("input[id=checkbox]").is(":checked");
                if((EMP_ENTRY_loginid!='SELECT')&&( EMP_ENTRY_projectselectlistbx==true))
                {
                    $("#EMP_ENTRY_btn_save").removeAttr("disabled");
                }
                else
                {
                    $("#EMP_ENTRY_btn_save").attr("disabled", "disabled");
                }
            });
            //CLICK EVENT FOR SAVE BUTTON
            $(document).on('click','#EMP_ENTRY_btn_save',function(){
                //PRELOADER ADJUST FUNCTION
//            $('.preloader', window.parent.document).show();
                $(".preloader").show();
                var loginid=$("#EMP_ENTRY_lb_loginid option:selected").text();
                var formElement = document.getElementById("EMP_ENTRY_form_employeename");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var msg_alert=xmlhttp.responseText;
                        if(msg_alert==1)
                        {
//                        $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
                            var msg=err_msg_array[2].replace("[LOGINID]",loginid);
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE PROJECT ACCESS",msgcontent:msg,position:{top:100,left:100}}});
                            show_msgbox("PROJECT ENTRY /ACCESS /SEARCH /UPDATE",msg,"success",false);
                            EMP_ENTRY_rset()
                            initialload();
                        }
                        else
                        {
//                        $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE PROJECT ACCESS",msgcontent:err_msg_array[0],position:{top:100,left:100}}});
                            show_msgbox("PROJECT ENTRY /ACCESS /SEARCH /UPDATE",err_msg_array[0],"success",false);
                        }
                    }
                }
                var choice="PROJECT_PROPETIES_SAVE"
                xmlhttp.open("POST","SETTINGS/DB_EMPLOYEE_PROJECT_ACCESS.do?option="+choice,true);
                xmlhttp.send(new FormData(formElement));
            });
//    $(document).on('click','#project_search',function(){
//        $('.preloader', window.parent.document).show()

            //FUNCTION FOR PROJECT LIST
            //CHANGE EVENT FOR ACTIVE LOGIN ID
            $('#EMPSRC_UPD_lb_loginid').change(function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                $('#EMPSRC_UPD_btn_update').hide();
                $('#EMPSRC_UPD_btn_reset').hide();
                $('#EMPSRC_UPD_lbl_txtselectproj').hide();
                $('#EMPSRC_UPD_tble_frstsel_projectlistbx').html('');
                //PRELOADER ADJUST FUNCTION
                $(".preloader").show();
                EMPSRC_UPD_empname=$("#EMPSRC_UPD_lb_loginid option:selected").text();
                if(EMPSRC_UPD_empname=='SELECT')
                {
                    $(".preloader").hide();
                    $('#EMPSRC_UPD_btn_update').hide();
                    $('#EMPSRC_UPD_btn_reset').hide();
                    $('#EMPSRC_UPD_tble_frstsel_projectlistbx').hide();
                    $('#EMPSRC_UPD_tble_projectlistbx').hide();
                    $('#EMPSRC_UPD_lbl_txtselectproj').hide();
                }
                else{
                    var formElement = document.getElementById("EMP_ENTRY_form_employeename");
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
//                    alert(xmlhttp.responseText);
                            var values_array=JSON.parse(xmlhttp.responseText);
                            var EMPSRC_UPD_proj_array=values_array[0];
                            var EMPSRC_UPD_proj_id=values_array[1];
                            var projects_list;
                            for (var i=0;i<EMPSRC_UPD_proj_array.length;i++) {
                                projects_list += '<tr><td><input type="checkbox" id="' + EMPSRC_UPD_proj_array[i][1] +'p'+ '" class="update_validate" name="checkbox[]" value="' + EMPSRC_UPD_proj_array[i][1] + '" >' + EMPSRC_UPD_proj_array[i][0] + '-'+EMPSRC_UPD_proj_array[i][2] + '</td></tr>';
                            }
                            $('#EMPSRC_UPD_tble_frstsel_projectlistbx').append(projects_list).show();
                            for(var i=0;i<EMPSRC_UPD_proj_array.length;i++){
                                for(var j=0;j<EMPSRC_UPD_proj_id.length;j++){
                                    if(EMPSRC_UPD_proj_id[j][1]==EMPSRC_UPD_proj_array[i][1]){
                                        $("#" + EMPSRC_UPD_proj_array[i][1]+'p').prop( "checked", true );
                                    }
                                }
                            }
                            $(".preloader").hide();
                            $('#EMPSRC_UPD_lb_loginid').show();
                            $('#EMPSRC_UPD_lbl_loginid').show();
                            $('#EMPSRC_UPD_btn_update').attr("disabled","disabled").show();
                            $('#EMPSRC_UPD_btn_reset').show();
                            $('#checkbox').attr('checked',false).show();
                            $('#EMPSRC_UPD_tble_projectlistbx').show();
                            $('#EMPSRC_UPD_lbl_txtselectproj').show();
                            $('#EMPSRC_UPD_tble_frstsel_projectlistbx').show();
                        }
                    }
                    var choice="PROJECT_NAME"
                    xmlhttp.open("POST","SETTINGS/DB_EMPLOYEE_PROJECT_SEARCH_UPDATE.do?option="+choice,true);
                    xmlhttp.send(new FormData(formElement));
                }
            });
            //CLICK EVENT FUCNTION FOR RESET
            $('#EMPSRC_UPD_btn_reset').click(function()
            {
                EMPSRC_UPD_rset()
            });
            //CLEAR ALL FIELDS
            function EMPSRC_UPD_rset()
            {
                $('#EMPSRC_UPD_lb_loginid').val('SELECT');
                $('#EMPSRC_UPD_btn_update').hide();
                $('#EMPSRC_UPD_btn_reset').hide();
                $('#EMPSRC_UPD_tble_frstsel_projectlistbx').hide();
                $('#EMPSRC_UPD_tble_projectlistbx').hide();
                $('#EMPSRC_UPD_lbl_txtselectproj').hide();
            }
            //FORM VALIDATION
//        $(document).on('change blur','#EMPSRC_UPD_form_employeename',function(){
            $(document).on('change blur','#EMP_ENTRY_form_employeename',function(){
                var EMPSRC_UPD_loginid = $("#EMPSRC_UPD_lb_loginid").val();
                var EMPSRC_UPD_projectselectlistbx=$('input[name="checkbox[]"]:checked').length;
                if((EMPSRC_UPD_loginid!='SELECT')&&(EMPSRC_UPD_projectselectlistbx>0))
                {
                    $("#EMPSRC_UPD_btn_update").removeAttr("disabled");
                }
                else
                {
                    $("#EMPSRC_UPD_btn_update").attr("disabled", "disabled");
                }
            });
            //CLICK EVENT FOR UPDATE BUTTON
            $(document).on('click','#EMPSRC_UPD_btn_update',function(){
//            $('.preloader', window.parent.document).show()
                $(".preloader").show();

                var formElement = document.getElementById("EMP_ENTRY_form_employeename");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var msg_alert=xmlhttp.responseText;
                        if(msg_alert==1)
                        {
//                        $('.preloader', window.parent.document).hide()
                            $(".preloader").hide();
                            var msg=err_msg_array[2].replace("[LOGIN ID]",EMPSRC_UPD_empname);

//                       $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE PROJECT ACCESS SEARCH/UPDATE",msgcontent:msg,position:{top:100,left:100}}});
                            show_msgbox("PROJECT ENTRY /ACCESS /SEARCH /UPDATE",msg,"success",false);
                            EMPSRC_UPD_rset()
                        }
                        else
                        {
//                        $('.preloader', window.parent.document).hide()
                            $(".preloader").hide();
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE PROJECT ACCESS SEARCH/UPDATE",msgcontent:err_msg_array[0],position:{top:100,left:100}}});
                            show_msgbox("PROJECT ENTRY /ACCESS /SEARCH /UPDATE",err_msg_array[0],"success",false);
                        }
                    }
                }
                var choice="PROJECT_PROPERTIES_UPDATE"
                xmlhttp.open("POST","SETTINGS/DB_EMPLOYEE_PROJECT_SEARCH_UPDATE.do?option="+choice,true);
                xmlhttp.send(new FormData(formElement));
            });
        });
        //END DOCUMENT READY FUNCTION
    </script>
</head>
<!--SCRIPT TAG END-->
<!--BODY TAG START-->
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/></div>
    <div class="title text-center"><h4><b>PROJECT ENTRY /ACCESS /SEARCH /UPDATE</b></h4></div>
    <form  name="EMP_ENTRY_form_employeename" id="EMP_ENTRY_form_employeename" class="content form-horizontal" role="form" >
        <div class="panel-body">
            <fieldset>
                <div style="padding-left: 15px">
                    <label>
                        <div class="radio">
                            <input type="radio" name="project" class="project" id="project">PROJECT ACCESS</label>
                </div>

                <div id="prj" hidden>
                    <div class="row-fluid form-group" style="padding-left: 15px">
                        <label name="reports_entry" class="col-sm-2"  id="reports_entry">
                            <div class="radio">
                                <input type="radio" name="entry" class="prjclick"  id="project_access" value="access">ACCESS</label>
                    </div>
                </div>
                <div class="row-fluid form-group" style="padding-left: 15px">
                    <label id="reports_search" class="col-sm-2"   name="reports_search">
                        <div class="radio">
                            <input type="radio" name="entry" class="prjclick"  id="project_search" value="search">SEARCH/UPDATE</label>
                </div></div>
</div>
<label>
    <div class="radio">
        <input type="radio" name="project" class="project_entry" id="project_entry" value="entry">PROJECT ENTRY</label>

</div>
</div>

<div class="row-fluid form-group">
    <label name="EMP_report_access" id="EMP_lbl_report_access" class="srctitle col-sm-12"></label>
</div>
<div class="row-fluid form-group">
    <label name="EMP_report_search" id="EMP_lbl_report_search" class="srctitle col-sm-12"></label>
</div>
<div class="form-group" style="padding-left: 15px">
    <label name="EMP_report_entry" id="EMP_lbl_report_entrys" class="srctitle"></label>
</div>
<div id="access" hidden>
    <div class="row-fluid form-group" >
        <label name="EMP_ENTRY_lbl_loginid" class="col-sm-2" id="EMP_ENTRY_lbl_loginid" hidden>EMPLOYEE NAME<em>*</em></label>
        <div class="col-sm-4">
            <select name="EMP_ENTRY_lb_loginid" id="EMP_ENTRY_lb_loginid" class="form-control" hidden>
            </select>
        </div></div>
    <div><label id="EMP_ENTRY_lbl_nologinid" name="EMP_ENTRY_lbl_nologinid" class="errormsg"></label></div>
    <div id="EMP_ENTRY_tble_projectlistbx" class="row-fluid form-group" hidden>
        <label name="EMP_ENTRY_lbl_txtselectproj" class="col-sm-2" id="EMP_ENTRY_lbl_txtselectproj">PROJECT NAME<em>*</em></label>
        <div id="EMP_ENTRY_tble_frstsel_projectlistbx"  class="col-sm-10"  ></div>

    </div>

    <div>
        <input type="button" class="btn" name="EMP_ENTRY_btn_save" id="EMP_ENTRY_btn_save"   value="SAVE" disabled="" hidden>
        <input type="button" class="btn" name="EMP_ENTRY_btn_reset" id="EMP_ENTRY_btn_reset"  value="RESET" hidden>

    </div>
</div>
<div id="search" hidden>
    <div class="row-fluid form-group">
        <label name="EMPSRC_UPD_lbl_loginid" class="col-sm-2" id="EMPSRC_UPD_lbl_loginid" hidden>EMPLOYEE NAME<em>*</em></label>
        <div class="col-sm-4">
            <select name="EMPSRC_UPD_lb_loginid" id="EMPSRC_UPD_lb_loginid" class="form-control" style="display: inline" hidden>
            </select>
        </div></div>
    <div><label id="EMPSRC_UPD_lbl_nologinid" name="EMPSRC_UPD_lbl_nologinid" class="errormsg"></label></div>

    <div id="EMPSRC_UPD_tble_projectlistbx" class="row-fluid form-group" hidden>
        <label name="EMPSRC_UPD_lbl_txtselectproj" class="col-sm-2" id="EMPSRC_UPD_lbl_txtselectproj">PROJECT NAME<em>*</em></label>
        <div id="EMPSRC_UPD_tble_frstsel_projectlistbx" class="col-sm-10" ></div>
    </div>


    <div>
        <input type="button" class="btn" name="EMPSRC_UPD_btn_update" id="EMPSRC_UPD_btn_update"   value="UPDATE" disabled hidden>
        <input type="button" class="btn" name="EMPSRC_UPD_btn_reset" id="EMPSRC_UPD_btn_reset"  value="RESET" hidden>
    </div>
</div>
<div id="entry" hidden>

    <div class="form-group">
        <label class="col-lg-2" name="PE_lbl_prjectname" id="PE_lbl_prjectname">PROJECT NAME<em>*</em></label>
        <div class="col-lg-3">
            <input type="text" name="PE_tb_prjectname" id="PE_tb_prjectname" class="valid autosize form-control" maxlength='50'>  <label id="PE_lbl_erromsg" class="errormsg"></label>
        </div></div>

    <div class="form-group">
        <label class="col-lg-2" name="PE_lbl_prjdescrptn" id="PE_lbl_prjdescrptn">PROJECT DESCRIPTION<em>*</em></label>
        <div class="col-lg-4">
            <textarea  name="PE_ta_prjdescrptn" id="PE_ta_prjdescrptn" class="maxlength tarea valid form-control"></textarea>
        </div> </div>

    <div class="form-group">
        <label class="col-lg-2" name="PE_lbl_status" id="PE_lbl_status" >STATUS<em>*</em></label>
        <div class="col-lg-3">
            <input type="text" id="PE_tb_status" name="PE_tb_status" class="valid form-control" readonly>
        </div></div>

    <div class="form-group">
        <label class="col-lg-2" name="PE_lbl_sdate" id="PE_lbl_sdate" >START DATE<em>*</em></label>
        <div class="col-lg-4">
            <input type="text" name="PE_tb_sdate" id="PE_tb_sdate"  class="PE_tb_sdatedatepicker form-control valid datemandtry" style="width: 100px">
        </div> </div>

    <div class="form-group">
        <label class="col-lg-2" name="PE_lbl_edate" id="PE_lbl_edate" >END DATE<em>*</em></label>
        <div class="col-lg-4">
            <input type="text" name="PE_tb_edate" id="PE_tb_edate" class="PE_tb_edatedatepicker form-control valid datemandtry" style="width: 100px">
        </div></div>

    <div>
        <input type="button" class="btn" name="PE_btn_save" id="PE_btn_save"  value="SAVE" disabled>
    </div>

    <div>
        <label class="errormsg" id="PE_nodataerrormsg" hidden></label>
    </div>
    <div>
        <label class="srctitle" id="PE_lbl_title" hidden></label>
    </div>
    <div><input type="button" id="PE_btn_pdf" class="btnpdf" value="PDF"></div>
    <div class="table-responsive">
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