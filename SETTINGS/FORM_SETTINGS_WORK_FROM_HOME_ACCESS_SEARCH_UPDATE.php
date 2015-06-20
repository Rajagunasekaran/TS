<!--//*******************************************FILE DESCRIPTION*********************************************//
<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE PROJECT ACCESS*********************************************//
//DONE BY:JAYAPRIYA
//VER 0.02-INITIAL VERSION, SD:08/06/2015 ED:08/06/2015,CORRECT FORM TO BE RESPONSIVE
//DONE BY:RENUKA
//VER 0.01-INITIAL VERSION, SD:22/04/2015 ED:27/04/2015,TRACKER NO:79
//*********************************************************************************************************//
<?php
include "../TSLIB/TSLIB_HEADER.php";
?>
<!--SCRIPT TAG START-->
<html>
<head>
    <script>
        //GLOBAL DECLARATION
        var err_msg_array=[];
        var EMP_ENTRY_loginid=[];
        var project_array=[];
        //START DOCUMENT READY FUNCTION
        $(document).ready(function(){
            $('#EMP_ENTRY_btn_reset').hide();
            $('#EMP_ENTRY_lb_loginid').hide();
            var EMP_ENTRY_empname;
            initialload();
            //FUNCTION FOR GETTING ERR MSG,LOGIN ID
            function initialload(){
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
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
                xmlhttp.open("GET","SETTINGS/DB_EMPLOYEE_WORK_FROM_HOME_ACCESS.do?option="+option);
                xmlhttp.send();
            }
            //FUNCTION FOR PROJECT LIST
            function projectlist(){
                $('#EMP_ENTRY_tble_frstsel_projectlistbx').html('');
                var project_list;
                for (var i=0;i<project_array.length;i++) {
                    project_list += '<tr><td><input type="checkbox" id ="checkbox" name="checkbox[]" value="' + project_array[i][1] + '">' + project_array[i][0] +'</td></tr>';
                }
                $('#EMP_ENTRY_tble_frstsel_projectlistbx').append(project_list).show();
            }
            //CHANGE EVENT FOR ACTIVE LOGIN ID
            $('#EMP_ENTRY_lb_loginid').change(function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                //PRELOADER ADJUST FUNCTION
                $(".preloader").show();
                $('input:checkbox[id=checkbox]').attr('checked',false);
                $('#checkbox').attr('checked',false);
                EMP_ENTRY_empname=$("#EMP_ENTRY_lb_loginid option:selected").text();
                if(EMP_ENTRY_empname=="SELECT")
                {
                    $(".preloader").hide();
                    $('#EMP_ENTRY_btn_reset').hide();
                    $('#EMP_ENTRY_tble_frstsel_projectlistbx').hide();
                    $('#EMP_ENTRY_tble_projectlistbx').hide();
                    $('#EMP_ENTRY_lbl_txtselectproj').hide();
                }
                else
                {
                    projectlist();
                    $(".preloader").hide();
                    $('#EMP_ENTRY_lb_loginid').show();
                    $('#EMP_ENTRY_lbl_loginid').show();
                    $('#CONFIG_SRCH_UPD_tr_type').append('<div class="row-fluid form-group"><input type="button" class="btn" name="EMP_ENTRY_btn_save" id="EMP_ENTRY_btn_save" value="SAVE" disabled></div>')
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
                $('#EMP_ENTRY_btn_reset').hide();
                $('#EMP_ENTRY_btn_save').hide();
                $('#EMP_ENTRY_tble_frstsel_projectlistbx').hide();
                $('#EMP_ENTRY_tble_projectlistbx').hide();
                $('#EMP_ENTRY_lbl_txtselectproj').hide();
            }
            //FORM VALIDATION
            $(document).on('load change blur','#EMP_ENTRY_form_employeename',function(){
                $("#EMP_ENTRY_btn_save").attr("disabled", "disabled");
                var EMP_ENTRY_loginid = $("#EMP_ENTRY_lb_loginid").val();
                var EMP_ENTRY_projectselectlistbx = $("input[id=checkbox]").is(":checked");
                var button_val=$('#EMP_ENTRY_btn_save').val();
                if(button_val == 'SAVE')
                {
                    if((EMP_ENTRY_loginid!='SELECT')&&( EMP_ENTRY_projectselectlistbx==true))
                    {
                        $("#EMP_ENTRY_btn_save").removeAttr("disabled");
                    }
                    else
                    {
                        $("#EMP_ENTRY_btn_save").attr("disabled", "disabled");
                    }
                }
                else if(button_val == 'UPDATE')
                {
                    if((EMP_ENTRY_loginid!='SELECT')&& ( EMP_ENTRY_projectselectlistbx==false))
                    {
                        $("#EMP_ENTRY_btn_save").removeAttr("disabled");
                    }
                    else
                    {
                        $("#EMP_ENTRY_btn_save").attr("disabled", "disabled");
                    }
                }
            });
            $('#EMP_ENTRY_lb_loginid').change(function(){
                $('#CONFIG_SRCH_UPD_tr_type').empty();
                if($(this).val() == 'SELECT')
                {
                    $("#EMP_ENTRY_btn_save").hide();
                    $('#EMP_ENTRY_tble_projectlistbx').hide();
                }
                else{
                    $('#EMP_ENTRY_tble_projectlistbx').show();
                    var loginid=$('#EMP_ENTRY_lb_loginid').val();
                    var formElement = document.getElementById("EMP_ENTRY_form_employeename");
                    var flag;
//            $('.preloader', window.parent.document).show();
                    $(".preloader").show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
                            var values_array=JSON.parse(xmlhttp.responseText);
                            flag=values_array[0];
                            if(flag == "X")
                            {
                                $('input:checkbox[id=checkbox]').attr('checked',true)
                                $('#CONFIG_SRCH_UPD_tr_type').append('<div class="row-fluid form-group"><input type="button" class="btn" name="EMP_ENTRY_btn_save" id="EMP_ENTRY_btn_save"   value="UPDATE" disabled></div>')
                            }
                            else{
                                $('input:checkbox[id=checkbox]').attr('checked',false)
                                $('#CONFIG_SRCH_UPD_tr_type').append('<div class="row-fluid form-group"><input type="button" class="btn" name="EMP_ENTRY_btn_save" id="EMP_ENTRY_btn_save"   value="SAVE" disabled></div>')
                            }
                        }
                    }
                    var option="check_flag";
                    xmlhttp.open("GET","SETTINGS/DB_EMPLOYEE_WORK_FROM_HOME_ACCESS.do?option="+option+"&loginid="+loginid);
                    xmlhttp.send(new FormData(formElement));
                }
            })

            //CLICK EVENT FOR SAVE BUTTON
            $(document).on('click','#EMP_ENTRY_btn_save',function(){
                //PRELOADER ADJUST FUNCTION
                $('#CONFIG_SRCH_UPD_tr_type').empty();
                $(".preloader").show();
                var loginid=$("#EMP_ENTRY_lb_loginid option:selected").text();
                var formElement = document.getElementById("EMP_ENTRY_form_employeename");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var msg_alert=JSON.parse(xmlhttp.responseText);
                        if(msg_alert[1]=='SAVE')
                        {
                            if(msg_alert[0]==1)
                            {
                                $(".preloader").hide();
                                var msg=err_msg_array[2].replace("[UNAME]",loginid);
                                show_msgbox("EMPLOYEE WORK FROM ACCESS",msg,"success",true);
                                EMP_ENTRY_rset()
                                initialload();
                            }
                            else
                            {
                                $(".preloader").hide();
                                show_msgbox("EMPLOYEE WORK FROM ACCESS",err_msg_array[0],"success",true);
                            }
                        }
                        else
                        {
                            if(msg_alert[0]==1)
                            {
                                $(".preloader").hide();
                                var msg=err_msg_array[3].replace("[UNAME]",loginid);
                                show_msgbox("EMPLOYEE WORK FROM ACCESS",msg,"success",false);
                                EMP_ENTRY_rset()
                                initialload();
                            }
                            else
                            {
                                $(".preloader").hide();
                                show_msgbox("EMPLOYEE WORK FROM ACCESS",err_msg_array[0],"success",false);

                            }

                        }
                    }
                }
                var choice="PROJECT_PROPETIES_SAVE"
                xmlhttp.open("POST","SETTINGS/DB_EMPLOYEE_WORK_FROM_HOME_ACCESS.do?option="+choice,true);
                xmlhttp.send(new FormData(formElement));
            });
        });
        //END DOCUMENT READY FUNCTION
    </script>
    <!--SCRIPT TAG END-->
    <!--BODY TAG START-->
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="title text-center"><h4><b>EMPLOYEE WORK FROM HOME ACCESS</b></h4></div>
    <form  name="EMP_ENTRY_form_employeename" id="EMP_ENTRY_form_employeename" class="form-horizontal content" role="form" >
        <div class="panel-body">
            <fieldset>
                <div class="row-fluid form-group">
                    <label name="EMP_ENTRY_lbl_loginid" class="col-sm-2" id="EMP_ENTRY_lbl_loginid" hidden>EMPLOYEE NAME<em>*</em></label>
                    <div class="col-sm-4">
                        <select name="EMP_ENTRY_lb_loginid" id="EMP_ENTRY_lb_loginid" class="form-control" hidden>
                        </select>
                    </div></div>
                <div><label id="EMP_ENTRY_lbl_nologinid" name="EMP_ENTRY_lbl_nologinid" class="errormsg"></label></div>
                <div class="row-fluid form-group" id="EMP_ENTRY_tble_projectlistbx"  hidden>
                    <label name="EMP_ENTRY_lbl_txtselectproj"  class="col-sm-2" id="EMP_ENTRY_lbl_txtselectproj">PROJECT NAME<em>*</em></label>
                    <div id="EMP_ENTRY_tble_frstsel_projectlistbx" class="col-sm-8" ></div>
                </div>
                <div class="row-fluid form-group form-inline col-sm-offset-0 col-sm-2">
                    <div class="col-sm-2" id="CONFIG_SRCH_UPD_tr_type"></div>
                    <div class="col-sm-4">
                        <input type="button" class="btn" name="EMP_ENTRY_btn_reset" id="EMP_ENTRY_btn_reset"  value="RESET" hidden>
                    </div></div>
            </fieldset>
        </div>
    </form>

</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->