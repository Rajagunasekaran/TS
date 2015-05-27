<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************PROJECT ENTRY/SEARCH/UPDATE**************************************//
//DONE BY:RAJA
//VER 0.05-SD:03/01/2015 ED:06/01/2015, TRACKER NO:166,179,DESC:IMPLEMENTED PDF BUTTON AND VALIDATED AND GAVE INPUT TO DB, SETTING PRELOADER POSITON, MSGBOX POSITION
//DONE BY:LALTHA
//VER 0.04 SD:03/12/2014 ED:03/12/2014,TRACKER NO:74,DESC:Updated preloader funct,Removed confirmation err msg,Added no data err msg,Fixed Width
//DONE BY:safi
//ver 0.03 SD:06/011/2014 ED:07/11/2014,tracker no:74,updated autocomplte function,set date for datepicker,changed validation part
//DONE BY:SASIKALA
//VER 0.02 SD:14/10/2014 ED:16/10/2014,TRACKER NO:86,DESC:VALIDATION'S DONE
//VER 0.01-INITIAL VERSION, SD:20/09/2014 ED:13/10/2014,TRACKER NO:74 DONE BY:SHALINI
//*********************************************************************************************************//-->
<?php
//include "HEADER.php";
include "NEW_MENU.php";
?>
<script>
//CHECK PRELOADER STATUS N HIDE START
var SubPage=1;
// READY FUNCTION STARTS
$(document).ready(function(){
//    alert('dddddddddddddd')
    first()
//    $('.preloader', window.parent.document).show();
    $(".preloader").show();
    get_Values();
    $('#PE_btn_pdf').hide();
    var  CACS_VIEW_customername;
    $('textarea').autogrow({onInitialize: true});
    $(".autosize").doValidation({rule:'general',prop:{autosize:true}});
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
    //AUTOCOMPLETE TEXT
    var error_message=[];
    var comp_start_date;
    var project_status=[];
    function get_Values(){
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader', window.parent.document).hide();
                $(".preloader").hide();
                var values=JSON.parse(xmlhttp.responseText);
                var proj_auto=values[0];
                error_message=values[1];
                comp_start_date=values[2];
                project_status=values[3];
                CACS_VIEW_customername=proj_auto;
            }
        }
        var option='AUTO';
        xmlhttp.open("GET","DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?&option="+option,true);
        xmlhttp.send();

    }
    showTable();
    //BLUR FUNCTION FOR PROJECT NAME
    $(document).on("change blur",'#projectname',function(){
        var checkproject_name=$(this).val();
        if(checkproject_name!=''){
//            $('.preloader', window.parent.document).show();
            $(".preloader").show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
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
            xmlhttp.open("GET","DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?checkproject_name="+checkproject_name+"&option="+option,true);
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
//            $('.preloader', window.parent.document).show();
            $(".preloader").show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                    $(".preloader").hide();
                    var check_array=JSON.parse(xmlhttp.responseText);
//                    var desc=check_array[1];
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
            xmlhttp.open("GET","DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?checkproject_name="+checkproject_name+"&option="+option,true);
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
//        $('.preloader', window.parent.document).show();
        $(".preloader").show();
        var formElement = document.getElementById("PE_form_projectentry");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader', window.parent.document).hide();
                $(".preloader").hide();
                var msg_alert=xmlhttp.responseText;

                if(msg_alert==1)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UPDATE",msgcontent:error_message[1],position:{top:150,left:500}}});
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
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UPDATE",msgcontent:error_message[2],position:{top:150,left:500}}});
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
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UPDATE",msgcontent:msg_alert,position:{top:150,left:500}}});
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
        xmlhttp.open("POST","DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?option="+option,true);
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
            url:"DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do",
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
                    $('#PE_btn_pdf').hide();
                    $('#tablecontainer').hide();
                }

            }

        });

    }

//    function sorting(){
//        jQuery.fn.dataTableExt.oSort['uk_date-asc']  = function(a,b) {
//            var x = new Date( Date.parse(FormTableDateFormat(a)));
//            var y = new Date( Date.parse(FormTableDateFormat(b)) );
//            return ((x < y) ? -1 : ((x > y) ?  1 : 0));
//        };
//        jQuery.fn.dataTableExt.oSort['uk_date-desc'] = function(a,b) {
//            var x = new Date( Date.parse(FormTableDateFormat(a)));
//            var y = new Date( Date.parse(FormTableDateFormat(b)) );
//            return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
//        }
//        jQuery.fn.dataTableExt.oSort['uk_timestp-asc']  = function(a,b) {
//            var x = new Date( Date.parse(FormTableDateFormat(a.split(' ')[0]))).setHours(a.split(' ')[1].split(':')[0],a.split(' ')[1].split(':')[1],a.split(' ')[1].split(':')[2]);
//            var y = new Date( Date.parse(FormTableDateFormat(b.split(' ')[0]))).setHours(b.split(' ')[1].split(':')[0],b.split(' ')[1].split(':')[1],b.split(' ')[1].split(':')[2]);
//            return ((x < y) ? -1 : ((x > y) ?  1 : 0));
//        };
//        jQuery.fn.dataTableExt.oSort['uk_timestp-desc'] = function(a,b) {
//            var x = new Date( Date.parse(FormTableDateFormat(a.split(' ')[0]))).setHours(a.split(' ')[1].split(':')[0],a.split(' ')[1].split(':')[1],a.split(' ')[1].split(':')[2]);
//            var y = new Date( Date.parse(FormTableDateFormat(b.split(' ')[0]))).setHours(b.split(' ')[1].split(':')[0],b.split(' ')[1].split(':')[1],b.split(' ')[1].split(':')[2]);
//            return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
//        };
//    }

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
            xmlhttp.open("GET","DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?checkproject_name="+checkproject_name+"&option="+option+"&recver="+rec_ver,true);
            xmlhttp.send();
        }
    });
// CLICK EVENT FOR UPDATE BUTTON
//    $('section').on('click','.ajaxupdate',function(){
//        $('.preloader', window.parent.document).show();
//        var edittrid = $(this).parent().parent().attr('id');
//        var combineid = $(this).parent().parent().attr('id');
//        var combineid_split=combineid.split('_');
//        var edittrid=combineid_split[0];
//        var pdid=combineid_split[1];
//        var projectname =  $("input[name='"+field_name[0]+"']");
//        var projectdes = $("textarea[name='"+field_name[1]+"']");
//        var prostatus =  $('#status').val();
//        var projectsdate = $("input[name='start_date']");
//        var projectedate =  $("input[name='end_date']");
//        data = "&name="+projectname.val()+"&des="+projectdes.val()+"&sta="+prostatus+"&ssd="+projectsdate.val()+"&eed="+projectedate.val()+"&editid="+edittrid+"&pdid="+pdid+"&option=updateData";
//        $.ajax({
//            url:"DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do",
//            type:"POST",
//            data:data,
//            cache: false,
//            success: function(response){
//                $('.preloader', window.parent.document).hide();
//                if(response==1){
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UPDATE",msgcontent:error_message[3],position:{top:100,left:100}}});
//                    showTable()
//                    get_Values();
//                }
//                else if(response==0)
//                {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UPDATE",msgcontent:error_message[4],position:{top:100,left:100}}});
//                    showTable()
//                    get_Values();
//                }
//                else
//                {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UPDATE",msgcontent:response,position:{top:100,left:100}}});
//                    showTable()
//                    get_Values();
//                }
//            }
//        });
//    });
// CLICK EVENT FOR CANCEL BUTTON
//    $('section').on('click','.ajaxcancel',function(){
//        var edittrid = $(this).parent().parent().attr('id');
//        $('#'+edittrid).html(pre_tds);
//    });
    $(document).on('click','#PE_btn_pdf',function(){
        var url=document.location.href='COMMON_PDF.do?flag=17&title='+error_message[6];
    });

    function first()
    {
        $.ajax({
            type: 'POST',
            url: 'DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do',
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
            $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><textarea id='project_des' name='project_des'  class='update' maxlength='50'  value='"+tdvalue+"'>'"+tdvalue+"'</textarea></td>");
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
            var babypname=$('#project_name').val();
        }

        if($('#pdesc_'+pdid+'_'+psid).hasClass("edit")==true){

            var babypdesc=$('#pdesc_'+pdid+'_'+psid).text();
        }
        else{
            var babypdesc=$('#project_des').val();
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
            url: 'DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do',
//            data:'&rowid='+combineid+'&babypname='+babypname+'&babypdesc='+babypdesc+'&babystatus='+babystatus+'&babysdate='+babysdate+'&babyedate='+babyedate,
            data:'&option=update&pdid='+pdid+'&psid='+psid+'&babypname='+babypname+'&babypdesc='+babypdesc+'&babystatus='+babystatus+'&babysdate='+babysdate+'&babyedate='+babyedate,
            success: function(data) {
//                alert(data)
                $('.preloader').hide();
                var resultflag=data;
                if(resultflag==1){
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UPDATE",msgcontent:error_message[3],position:{top:150,left:520}}});
                    show_msgbox("PROJECT ENTRY/SEARCH/UPDATE",error_message[3],"success",false);
                    previous_id=undefined;
                    first()
//                        get_Values();
                }
                else if(resultflag==0)
                {
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UPDATE",msgcontent:error_message[4],position:{top:150,left:520}}});
                    show_msgbox("PROJECT ENTRY/SEARCH/UPDATE",error_message[4],"success",false);
                    previous_id=undefined;
                    first()
//                        get_Values()/;
                }
                else
                {
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UPDATE",msgcontent:response,position:{top:150,left:520}}});
                    show_msgbox("PROJECT ENTRY/SEARCH/UPDATE",response,"success",false);
                    previous_id=undefined;
                    first()
//                        get_Values();
                }
            }
        });
    }) ;

//


});
// READY FUNCTION ENDS
</script>
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="newtitle" id="fhead" ><p style="padding-top: 20px";><center><h3>PROJECT ENTRY/SEARCH/UPDATE</h3></center><p></div>
    <form  name="PE_form_projectentry" id="PE_form_projectentry" method="post" class="newcontent">
        <div class="table-responsive">
            <div class="form-group">
                <label class="col-lg-4" name="PE_lbl_prjectname" id="PE_lbl_prjectname">PROJECT NAME<em>*</em></label>
                <div class="col-lg-8">
                    <input type="text" name="PE_tb_prjectname" id="PE_tb_prjectname" class="valid autosize" maxlength='50'>  <label id="PE_lbl_erromsg" class="errormsg"></label>
                </div></div>

            <div class="form-group">
                <label class="col-lg-4" name="PE_lbl_prjdescrptn" id="PE_lbl_prjdescrptn">PROJECT DESCRIPTION<em>*</em></label>
                <div class="col-lg-8">
                    <textarea  name="PE_ta_prjdescrptn" id="PE_ta_prjdescrptn" class="maxlength  valid"></textarea>
                </div> </div>

            <div>
                <label class="col-lg-4" name="PE_lbl_status" id="PE_lbl_status" >STATUS<em>*</em></label>
                <div class="col-lg-8">
                    <input type="text" id="PE_tb_status" name="PE_tb_status" class="valid" readonly>
                </div></div>

            <div class="form-group">
                <label class="col-lg-4" name="PE_lbl_sdate" id="PE_lbl_sdate" >START DATE<em>*</em></label>
                <div class="col-lg-8">
                    <input type="text" name="PE_tb_sdate" id="PE_tb_sdate"  class="PE_tb_sdatedatepicker valid datemandtry" style="width: 75px">
                </div> </div>

            <div class="form-group">
                <label class="col-lg-4" name="PE_lbl_edate" id="PE_lbl_edate" >END DATE<em>*</em></label>
                <div class="col-lg-8">
                    <input type="text" name="PE_tb_edate" id="PE_tb_edate" class="PE_tb_edatedatepicker valid datemandtry" style="width: 75px">
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
            <section>
            </section>
        </div>
</div>
</form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->