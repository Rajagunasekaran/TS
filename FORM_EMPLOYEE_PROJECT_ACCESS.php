<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE PROJECT ACCESS*********************************************//
//DONE BY:LALITHA
//VER 0.03 SD:09/01/2014 ED:09/01/2014,TRACKER NO:74,Changed preloader position,Updated auto focus
//VER 0.02 SD:06/01/2014 ED:08/01/2014,TRACKER NO:74,Updated preloader position nd message box position,Changed loginid to emp name
//VER 0.01-INITIAL VERSION, SD:24/09/2014 ED:29/09/2014,TRACKER NO:79
//*********************************************************************************************************//
<?php
include "HEADER.php";
//include  "NEW_MENU.php";
?>
<!--SCRIPT TAG START-->
<script xmlns="http://www.w3.org/1999/html">
//GLOBAL DECLARATION
var err_msg_array=[];
var EMP_ENTRY_loginid=[];
var project_array=[];
var EMPSRC_UPD_loginid=[]
var EMPSRC_UPD_proj_array=[];
var EMPSRC_UPD_proj_id=[];
//START DOCUMENT READY FUNCTION
$(document).ready(function(){
    $(".preloader").hide()
    $(document).on('click','#project_access',function(){

//        $('.preloader', window.parent.document).show();
        $(".preloader").show()
        $('#EMP_lbl_report_entry').html('PROJECT ACCESS');
//            $('#option').val('SELECT');
        $('#access').show();
        $('#search').hide();

        $('#EMPSRC_UPD_lb_loginid').val('select');
        $('#EMPSRC_UPD_lbl_nologinid').hide();
        $('#EMPSRC_UPD_tble_projectlistbx').hide();
        $('#EMPSRC_UPD_lbl_txtselectproj').hide();
        $('#EMPSRC_UPD_tble_frstsel_projectlistbx').hide();
//        $('.preloader', window.parent.document).show();
        $('#EMP_ENTRY_btn_save').hide();
        $('#EMP_ENTRY_btn_reset').hide();
        var EMP_ENTRY_empname;
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
            xmlhttp.open("GET","DB_EMPLOYEE_PROJECT_ACCESS.do?option="+option);
            xmlhttp.send();
        }
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
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE PROJECT ACCESS",msgcontent:msg,position:{top:100,left:100}}});
                        EMP_ENTRY_rset()
                        initialload();
                    }
                    else
                    {
//                        $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE PROJECT ACCESS",msgcontent:err_msg_array[0],position:{top:100,left:100}}});
                    }
                }
            }
            var choice="PROJECT_PROPETIES_SAVE"
            xmlhttp.open("POST","DB_EMPLOYEE_PROJECT_ACCESS.do?option="+choice,true);
            xmlhttp.send(new FormData(formElement));
        });
    });
    $(document).on('click','#project_search',function(){
//        $('.preloader', window.parent.document).show()
        $(".preloader").show();
        $('#EMP_lbl_report_entry').html('PROJECT SEARCH UPDATE');
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
        var EMPSRC_UPD_empname;
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader', window.parent.document).hide()
                $(".preloader").hide();
                var EMPSRC_UPD_loginid=$('#EMPSRC_UPD_lb_loginid').val();
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
        xmlhttp.open("GET","DB_EMPLOYEE_PROJECT_SEARCH_UPDATE.do?option="+option);
        xmlhttp.send();
        //FUNCTION FOR PROJECT LIST
        //CHANGE EVENT FOR ACTIVE LOGIN ID
        $('#EMPSRC_UPD_lb_loginid').change(function(){
            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
            $('#EMPSRC_UPD_btn_update').hide();
            $('#EMPSRC_UPD_btn_reset').hide();
            $('#EMPSRC_UPD_lbl_txtselectproj').hide();
            $('#EMPSRC_UPD_tble_frstsel_projectlistbx').html('');
            //PRELOADER ADJUST FUNCTION
//            $('.preloader', window.parent.document).show()
            $(".preloader").show();
            EMPSRC_UPD_empname=$("#EMPSRC_UPD_lb_loginid option:selected").text();
            if(EMPSRC_UPD_empname=='SELECT')
            {
//                $('.preloader', window.parent.document).hide()
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
//                        $('.preloader', window.parent.document).hide()
                        $(".preloader").hide();
                        var values_array=JSON.parse(xmlhttp.responseText);
                        EMPSRC_UPD_proj_array=values_array[0];
                        EMPSRC_UPD_proj_id=values_array[1];
                        var project_list;
                        for (var i=0;i<EMPSRC_UPD_proj_array.length;i++) {
                            project_list += '<tr><td><input type="checkbox" id="' + EMPSRC_UPD_proj_array[i][1] +'p'+ '" class="update_validate" name="checkbox[]" value="' + EMPSRC_UPD_proj_array[i][1] + '" >' + EMPSRC_UPD_proj_array[i][0] + '-'+EMPSRC_UPD_proj_array[i][2] + '</td></tr>';
                        }
                        $('#EMPSRC_UPD_tble_frstsel_projectlistbx').append(project_list);
                        for(var i=0;i<EMPSRC_UPD_proj_array.length;i++){
                            for(var j=0;j<EMPSRC_UPD_proj_id.length;j++){
                                if(EMPSRC_UPD_proj_id[j][1]==EMPSRC_UPD_proj_array[i][1]){
                                    $("#" + EMPSRC_UPD_proj_array[i][1]+'p').prop( "checked", true );
                                }
                            }
                        }
//                        $('.preloader', window.parent.document).hide()
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
                xmlhttp.open("POST","DB_EMPLOYEE_PROJECT_SEARCH_UPDATE.do?option="+choice,true);
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
                   // alert(msg_alert)
                    if(msg_alert==1)
                    {
//                        $('.preloader', window.parent.document).hide()
                        $(".preloader").hide();
                       // alert(err_msg_array[2])
                        var msg=err_msg_array[2].replace("[LOGIN ID]",EMPSRC_UPD_empname);
                      //  alert(msg)
                      //  $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE PROJECT ACCESS SEARCH/UPDATE",msgcontent:msg,position:{top:100,left:100}}});
                        show_msgbox("EMPLOYEE PROJECT ACCESS SEARCH/UPDATE",msg,"success",false);
                        EMPSRC_UPD_rset()
                    }
                    else
                    {
//                        $('.preloader', window.parent.document).hide()
                        $(".preloader").hide();
                       // $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE PROJECT ACCESS SEARCH/UPDATE",msgcontent:err_msg_array[0],position:{top:100,left:100}}});
                        show_msgbox("EMPLOYEE PROJECT ACCESS SEARCH/UPDATE",err_msg_array[0],"success",false);
                    }
                }
            }
            var choice="PROJECT_PROPERTIES_UPDATE"
            xmlhttp.open("POST","DB_EMPLOYEE_PROJECT_SEARCH_UPDATE.do?option="+choice,true);
            xmlhttp.send(new FormData(formElement));
        });
    });
});
//END DOCUMENT READY FUNCTION
</script>
<!--SCRIPT TAG END-->
<!--BODY TAG START-->
<body>
<div class="container">
        <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
        <div class="title" id="fhead" ><center><p><b><h3 id="entry">PROJECT ACCESS/SEARCH/UPDATE</h3></b><p></center></div>
        <form  name="EMP_ENTRY_form_employeename" id="EMP_ENTRY_form_employeename" class="content" >
            <div class="panel-body">
                <fieldset>
                    <div class="row-fluid form-group ">
                        <label name="reports_entry" class="col-sm-2"  id="reports_entry">
                            <div class="radio">
                                <input type="radio" name="entry"  id="project_access" value="access">ACCESS</label>
                    </div>
            </div>
            <div class="row-fluid  form-group">
                <label id="reports_search" class="col-sm-2"   name="reports_search">
                    <div class="radio">
                        <input type="radio" name="entry"  id="project_search" value="search">SEARCH/UPDATE</label>
            </div></div>
    <div class="row-fluid form-group">
        <label name="EMP_report_entry" id="EMP_lbl_report_entry" class="srctitle col-sm-12"></label>
    </div>
    <div id="access" hidden>
        <div class="row-fluid form-group">
            <label name="EMP_ENTRY_lbl_loginid" class="col-sm-3" id="EMP_ENTRY_lbl_loginid" hidden>EMPLOYEE NAME<em>*</em></label>
            <div class="col-sm-6">
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
            <label name="EMPSRC_UPD_lbl_loginid" class="col-sm-3" id="EMPSRC_UPD_lbl_loginid" hidden>EMPLOYEE NAME<em>*</em></label>
            <div class="col-sm-6">
                <select name="EMPSRC_UPD_lb_loginid" id="EMPSRC_UPD_lb_loginid" class="form-control" hidden>
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
    </fieldset>
</div>
</form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->