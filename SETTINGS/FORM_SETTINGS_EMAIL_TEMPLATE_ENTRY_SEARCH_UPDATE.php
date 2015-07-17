<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMAIL TEMPLATE ENTRY*********************************************//
//DONE BY:RAJA
//VER 0.02-SD:03/01/2015 ED:06/01/2015, TRACKER NO:179,DESC: SETTING PRELOADER POSITON AND MSGBOX POSITION
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:27/10/2014 ED:28/10/2014,TRACKER NO:99
//*********************************************************************************************************//
<?php
include "../TSLIB/TSLIB_HEADER.php";
//include  "NEW_MENU.php";
?>
<!--SCRIPT TAG START-->
<html>
<head>
    <script>
        //READY FUNCTION START
        $(document).ready(function(){
            first()
            $(".preloader").show();
            var ET_ENTRY_chknull_input="";
            var ET_ENTRY_errormsg=[];
            //START FUNCTION FOR EMAIL TEMPLATE ERROR MESSAGE
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var value_array=JSON.parse(xmlhttp.responseText);
                    ET_ENTRY_errormsg=value_array[0];
                }
            }
            var option="EMAIL_TEMPLATE_ENTRY";
            xmlhttp.open("GET","TSLIB/TSLIB_COMMON.do?option="+option);
            xmlhttp.send();
            //END FUNCTION FOR EMAIL TEMPLATE ERROR MESSAGE
            //JQUERY LIB VALIDATION START
            $("#ET_ENTRY_tb_scriptname").doValidation({rule:'general',prop:{autosize:true}});
            $('textarea').autogrow({onInitialize: true});
            //JQUERY LIB VALIDATION END
            //KEY PRESS FUNCTION START
            var ET_ENTRY_max=3000;
            $('.maxlength').keypress(function(e)
            {
                if(e.which < 0x20)
                {
                    return;
                }
                if(this.value.length==ET_ENTRY_max)
                {
                    e.preventDefault();
                }
                else if(this.value.length > ET_ENTRY_max)
                {
                    this.value=this.value.substring(0,ET_ENTRY_max);
                }
            });
//KEY PRESS FUNCTION END
            //CHANGE FUNCTION FOR VALIDATION
            $("#ET_ENTRY_form_template").change(function(){
                $("#ET_ENTRY_hidden_chkvalid").val("")//SET VALIDATION FUNCTION VALUE
                ET_ENTRY_checkscriptname()
            });
            //CHANGE FUNCTION FOR VALIDATION
            $("#ET_ENTRY_tb_scriptname").blur(function(){
                $("#ET_ENTRY_hidden_chkvalid").val("")//SET VALIDATION FUNCTION VALUE
                ET_ENTRY_checkscriptname()
            });
            //BLUR FUNCTION FOR TRIM SUBJECT
            $("#ET_ENTRY_ta_subject").blur(function(){
                $(".preloader").hide();
                $('#ET_ENTRY_ta_subject').val($('#ET_ENTRY_ta_subject').val().toUpperCase())
                var trimfunc=($('#ET_ENTRY_ta_subject').val()).trim()
                $('#ET_ENTRY_ta_subject').val(trimfunc)
            });
//BLUR FUNCTION FOR TRIM BODY
            $("#ET_ENTRY_ta_body").blur(function(){
                $(".preloader").hide();
                $('#ET_ENTRY_ta_body').val($('#ET_ENTRY_ta_body').val().toUpperCase())
                var trimfunc=($('#ET_ENTRY_ta_body').val()).trim()
                $('#ET_ENTRY_ta_body').val(trimfunc)
            });
            //EMAIL TEMPLATE  SUBIT BUTTON VALIDATION
            function ET_ENTRY_checkscriptname()
            {
                var ET_ENTRY_scriptnametxt=$('#ET_ENTRY_tb_scriptname').val();
                var ET_ENTRY_subjecttxt=$('#ET_ENTRY_ta_subject').val();
                var ET_ENTRY_bodytxt=$('#ET_ENTRY_ta_body').val();
                if((ET_ENTRY_scriptnametxt.trim()=="") ||(ET_ENTRY_subjecttxt.trim()=="") || (ET_ENTRY_bodytxt.trim()==""))
                {
                    $("#ET_ENTRY_btn_save").attr("disabled", "disabled");
                    ET_ENTRY_chknull_input=false;
                }
                else
                {
                    ET_ENTRY_chknull_input=true;
                }
                var ET_ENTRY_scriptname=$('#ET_ENTRY_tb_scriptname').val();
                if(ET_ENTRY_scriptname!="")
                {
                    ET_ENTRY_already_result()
                }
//SUCCESS FUNCTION FOR ALREADY EXIST FOR SCRIPT NAME
                function ET_ENTRY_already_result()
                {
                    var ET_ENTRY_scriptname=$('#ET_ENTRY_tb_scriptname').val();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                        $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
                            var ET_ENTRY_response=JSON.parse(xmlhttp.responseText);
                            var ET_ENTRY_chkinput=ET_ENTRY_response;
                            if(ET_ENTRY_chkinput==0)
                            {
                                $('#ET_ENTRY_lbl_validid').hide();
                                $("#ET_ENTRY_tb_scriptname").removeClass('invalid');
                            }
                            if(ET_ENTRY_chkinput==0&&ET_ENTRY_chknull_input==true)
                            {
                                if($("#ET_ENTRY_hidden_chkvalid").val()=="")
                                {
                                    $('#ET_ENTRY_lbl_validid').hide();
                                    $("#ET_ENTRY_btn_save").removeAttr("disabled");
                                }
                                else
                                {
                                    ET_ENTRY_save_resultsuccess()
                                    $("#ET_ENTRY_hidden_chkvalid").val("");
                                }
                            }
                            else if(ET_ENTRY_chkinput==1)
                            {
                                $(".preloader").hide();
                                $('#ET_ENTRY_lbl_validid').show();
                                $('#ET_ENTRY_lbl_validid').text(ET_ENTRY_errormsg[2]);
                                $("#ET_ENTRY_tb_scriptname").addClass('invalid');
                                $("#ET_ENTRY_btn_save").attr("disabled", "disabled");
                            }
                        }
                    }
                    var choice='ET_ENTRY_already_result';
                    xmlhttp.open("GET","SETTINGS/DB_EMAIL_EMAIL_TEMPLATE_ENTRY.do?ET_ENTRY_scriptname="+ET_ENTRY_scriptname+"&option="+choice,true);
                    xmlhttp.send();
                }
            }
            //CLICK EVENT FOR SAVE BUTTON
            $('#ET_ENTRY_btn_save').click(function()
            {

                $(".preloader").show();
                $("#ET_ENTRY_hidden_chkvalid").val("SAVE")//SET SAVE FUNCTION VALUE
                var ET_ENTRY_scriptname=$('#ET_ENTRY_tb_scriptname').val();
                if($('#ET_ENTRY_form_template')!="")
                {
                    ET_ENTRY_checkscriptname()
                    first();
                }
            });
            //SUCCESS FUNCTIOIN FOR SAVE
            function ET_ENTRY_save_resultsuccess()
            {

//        $(".preloader").show();
                var formElement = document.getElementById("ET_ENTRY_form_template");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var ET_ENTRY_response=xmlhttp.responseText;
                        if(ET_ENTRY_response==1)
                        {
                            $("#ET_ENTRY_btn_save").attr("disabled","disabled");
                            //MESSAGE BOX FOR SAVED SUCCESS
                            show_msgbox("EMAIL TEMPLATE ENTRY/SEARCH/UPDATE",ET_ENTRY_errormsg[1],"success",false);
                            $("#ET_ENTRY_hidden_chkvalid").val("");
                            ET_ENTRY_email_template_rset();
                            first()
                            $("#ET_ENTRY_btn_save").attr("disabled", "disabled");
                        }
                        else
                        {
                            //MESSAGE BOX FOR NOT SAVED
                            show_msgbox("EMAIL TEMPLATE ENTRY/SEARCH/UPDATE",ET_ENTRY_errormsg[0],"success",false);
                            $("#ET_ENTRY_btn_save").attr("disabled", "disabled");
                        }
                        $(".preloader").hide();
                    }
                }
                var choice="ET_ENTRY_insert"
                xmlhttp.open("POST","SETTINGS/DB_EMAIL_EMAIL_TEMPLATE_ENTRY.do?option="+choice,true);
                xmlhttp.send(new FormData(formElement));
            }
            //CLICK EVENT FUCNTION FOR RESET
            $('#ET_ENTRY_btn_reset').click(function()
            {

                ET_ENTRY_email_template_rset()
            });
            //CLEAR ALL FIELDS
            function ET_ENTRY_email_template_rset()
            {
                $('#ET_ENTRY_tb_scriptname').val('');
                $('#ET_ENTRY_ta_subject').val('');
                $('#ET_ENTRY_ta_body').val('');
                $("#ET_ENTRY_btn_save").attr("disabled", "disabled");
            }
            <!--   data table     -->
            var $ET_SRC_UPD_DEL_scriptname;
            var ET_SRC_UPD_DEL_emailsubject;
            var ET_SRC_UPD_DEL_emailbody;
            var ET_SRC_UPD_DEL_userstamp;
            var ET_SRC_UPD_DEL_timestmp;
            var id;
            var ET_SRC_UPD_DEL_table_value='';
            var values_array=[];
            //CHANGE FUNCTION FOR SCRIPTNAME

            function first()
            {

                $.ajax({
                    type: 'POST',
                    url: 'SETTINGS/DB_EMAIL_EMAIL_TEMPLATE_ENTRY.do',
                    data:{option:'edit'},
                    success: function(data){
//                        alert(data);
                        $('section').html(data);
                        $('#tablecontainer').show();
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
            <!--inline edit new-->

            var previous_id;
            var combineid;
            var ET_ENTRY_el_id;
            var tdvalue;
            var ifcondition;
            $(document).on('click','.edit', function (){

                if(previous_id!=undefined){
                    $('#'+previous_id).replaceWith("<td class='edit' id='"+previous_id+"' >"+tdvalue+"</td>");
                }
                var cid = $(this).attr('id');
                var id=cid.split('_');
                ifcondition=id[0];
                previous_id=cid;
                ET_ENTRY_el_id=id[1];
                tdvalue=$(this).text();

                if(ifcondition=='emailsubject')
                {

                    $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><input type='text' id='email_subject' name='email_subject'  class='update' maxlength='1000'  value='"+tdvalue+"'></td>");
                }
                if(ifcondition=='emailbody')
                {

                    $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><textarea id='project_des' name='project_des'  class='update' maxlength='1000'  value='"+tdvalue+"'>"+tdvalue+"</textarea></td>");
                }


            } );

            $(document).on('change','.update',function(){

                $(".preloader").show();
                if($('#emailsubject_'+ET_ENTRY_el_id).hasClass("edit")==true){
                    var babypdesc=$('#emailsubject_'+ET_ENTRY_el_id).text();
                }
                else{
                    var babypdesc=$('#email_subject').val();
//            alert(babypdesc);
                }

                if($('#emailbody_'+ET_ENTRY_el_id).hasClass("edit")==true){

                    var babyemail=$('#emailbody_'+ET_ENTRY_el_id).text();
                }
                else{
                    var babyemail=$('#project_des').val();
                }

                $.ajax({
                    type:'POST',
                    url:'SETTINGS/DB_EMAIL_EMAIL_TEMPLATE_ENTRY.do',
                    data:'&option=update&ET_ENTRY_el_id='+ET_ENTRY_el_id+'&babypdesc='+babypdesc+'&babyemail='+babyemail,
                    success: function(data) {
                        $('.preloader').hide();
                        var resultflag=data;
                        if(resultflag==1)
                        {
                            show_msgbox("EMAIL TEMPLATE ENTRY/SEARCH/UPDATE",ET_ENTRY_errormsg[1],"success",false);
                            previous_id=undefined;
                            first()
                        }
                        else
                        {
//                        //MESSAGE BOX FOR NOT UPDATED
                            show_msgbox("EMAIL TEMPLATE ENTRY/SEARCH/UPDATE",ET_ENTRY_errormsg[0],"success",false);
                            previous_id=undefined;
                            first()
                        }
                        $(".preloader").hide();
                    }
                });
            }) ;
            <!-- inline edit end-->
        });
        <!--SCRIPT TAG END-->
    </script>
</head>
<!--BODY TAG START-->
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/></div>
    <div class="title text-center"><h4><b>EMAIL TEMPLATE ENTRY</b></h4></div>
    <form id="ET_ENTRY_form_template" class="content" role="form">
        <div class="panel-body">
            <fieldset>

                <div class="form-group">
                    <label class="col-lg-2" name="ET_ENTRY_lbl_scriptname" id="ET_ENTRY_lbl_scriptname">SCRIPT NAME<em>*</em></label>
                    <div class="col-lg-10">
                        <input type="text" name="ET_ENTRY_tb_scriptname" id="ET_ENTRY_tb_scriptname" maxlength="100"  style="width:150px">
                        <label id="ET_ENTRY_lbl_validid" name="ET_ENTRY_lbl_validid" class="errormsg" disabled=""></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2" name="ET_ENTRY_lbl_subject" id="ET_ENTRY_lbl_subject">SUBJECT<em>*</em></label>
                    <div class="col-lg-10">
                        <textarea rows="4" cols="50" name="ET_ENTRY_ta_subject" id="ET_ENTRY_ta_subject" class="tarea form-control maxlength reset" maxlength="1000"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2" name="ET_ENTRY_lbl_body" id="ET_ENTRY_lbl_body">BODY<em>*</em></label>
                    <div class="col-lg-10">
                        <textarea rows="4" cols="50" name="ET_ENTRY_ta_body" id="ET_ENTRY_ta_body" class="tarea form-control maxlength reset" maxlength="1000"></textarea>
                    </div>
                </div>
                <div class="form-group" style="padding-left: 15px">
                    <button type="button" align="right" class="btn" name="ET_ENTRY_btn_save" id="ET_ENTRY_btn_save" disabled>SAVE</button>
                    <button type="button" align="left" class="btn" name="ET_ENTRY_btn_reset" id="ET_ENTRY_btn_reset">RESET</button>
                </div><br>
                <input type=hidden id="ET_ENTRY_hidden_chkvalid">
                <div class ="table-responsive">
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