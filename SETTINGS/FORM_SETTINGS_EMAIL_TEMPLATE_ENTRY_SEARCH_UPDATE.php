<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMAIL TEMPLATE ENTRY*********************************************//
//DONE BY:RAJA
//VER 0.02-SD:03/01/2015 ED:06/01/2015, TRACKER NO:179,DESC: SETTING PRELOADER POSITON AND MSGBOX POSITION
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:27/10/2014 ED:28/10/2014,TRACKER NO:99
//*********************************************************************************************************//
<?php
//include "../TSLIB_HEADER.php";
include "../TSLIB/TSLIB_HEADER.php";
//include  "NEW_MENU.php";
?>
<!--SCRIPT TAG START-->
<script>
    //READY FUNCTION START
    $(document).ready(function(){
        first()
//        $('.preloader', window.parent.document).show();
        $(".preloader").show();
        var ET_ENTRY_chknull_input="";
        var ET_ENTRY_errormsg=[];
        //START FUNCTION FOR EMAIL TEMPLATE ERROR MESSAGE
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader', window.parent.document).hide();
                $(".preloader").hide();
                var value_array=JSON.parse(xmlhttp.responseText);
                ET_ENTRY_errormsg=value_array[0];
            }
        }
        var option="EMAIL_TEMPLATE_ENTRY";
        xmlhttp.open("GET","TSLIB/COMMON.do?option="+option);
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
//            $('.preloader', window.parent.document).hide();
            $(".preloader").hide();
            $('#ET_ENTRY_ta_subject').val($('#ET_ENTRY_ta_subject').val().toUpperCase())
            var trimfunc=($('#ET_ENTRY_ta_subject').val()).trim()
            $('#ET_ENTRY_ta_subject').val(trimfunc)
        });
//BLUR FUNCTION FOR TRIM BODY
        $("#ET_ENTRY_ta_body").blur(function(){
//            $('.preloader', window.parent.document).hide();
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
//                            $('.preloader', window.parent.document).hide();
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
//            $('.preloader', window.parent.document).show();
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
//            $('.preloader', window.parent.document).show();
            $(".preloader").show();
            var formElement = document.getElementById("ET_ENTRY_form_template");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var ET_ENTRY_response=xmlhttp.responseText;
                    if(ET_ENTRY_response==1)
                    {
                        $("#ET_ENTRY_btn_save").attr("disabled","disabled");
                        //MESSAGE BOX FOR SAVED SUCCESS
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE ENTRY",msgcontent:ET_ENTRY_errormsg[1],position:{top:100,left:100}}});
                        show_msgbox("EMAIL TEMPLATE ENTRY",ET_ENTRY_errormsg[1],"success",false);
                        $("#ET_ENTRY_hidden_chkvalid").val("");
                        ET_ENTRY_email_template_rset();
                        first();
                    }
                    else
                    {
                        //MESSAGE BOX FOR NOT SAVED
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE ENTRY",msgcontent:ET_ENTRY_errormsg[0],position:{top:100,left:100}}});
                        show_msgbox("EMAIL TEMPLATE ENTRY",ET_ENTRY_errormsg[0],"success",false);
                    }

//                    $('.preloader', window.parent.document).hide();
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
            $("#ET_ENTRY_form_template")[0].reset();
            $("#ET_ENTRY_tb_scriptname").removeClass('invalid');
            $('#ET_ENTRY_lbl_validid').hide();
            $("#ET_ENTRY_btn_save").attr("disabled", "disabled");
            $('#ET_ENTRY_tb_scriptname').prop("size","20");
            $('textarea').height(50).width(60);
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

        var previous_id;
        var combineid;
        var tdvalue;
        $(document).on('click','.snameedit', function (){
            if(previous_id!=undefined){
                $('#'+previous_id).replaceWith("<td class='data' id='"+previous_id+"' >"+tdvalue+"</td>");
            }
            var cid = $(this).attr('id');
            previous_id=cid;
            var id=cid.split('_');
            combineid=id[1];
            tdvalue=$(this).text();

            if(tdvalue!=''){
                $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><input type='text' id='name' name='data'  class='snameupdate' maxlength='50'  value='"+tdvalue+"'>");
//                $('.nameupdate').keypress(function (e) {
//                    var regex = new RegExp("^[a-z A-Z]+$");
//                    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
//                    if(regex.test(str)) {
//                        return true;
//                    }
//                    else
//                    {
//
//                        return false;
//                    }
//                });
            }

        } );

        $(document).on('change','.snameupdate',function(){
            var scriptvalue=$(this).val().trim();
//            alert('d');
            if((scriptvalue!='')){
                var xmlhttp=new XMLHttpRequest();
//                alert(xmlhttp);
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                        alert(xmlhttp.responseText);
                        var value=xmlhttp.responseText;

                        if(value==1)
                        {

//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE ENTRY",msgcontent:ET_ENTRY_errormsg[1],position:{top:100,left:100}}});
                            show_msgbox("EMAIL TEMPLATE ENTRY",ET_ENTRY_errormsg[1],"success",false);
                            first()
                        }
                        else
                        {
                            //MESSAGE BOX FOR NOT UPDATED
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE ENTRY",msgcontent:ET_ENTRY_errormsg[0],position:{top:100,left:100}}});
                            show_msgbox("EMAIL TEMPLATE ENTRY",ET_ENTRY_errormsg[0],"success",false);
                        }
//                        $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();

                        }

                    }

                var OPTION="update";
                xmlhttp.open("POST","SETTINGS/DB_EMAIL_EMAIL_TEMPLATE_ENTRY.do?option="+OPTION+"&scriptvalue="+scriptvalue+"&ET_ID="+combineid,true);
                xmlhttp.send();
            }
        });


        var previous_id;
        var combineid;
        var tdvalue;
        $(document).on('click','.emailsubject', function (){
            if(previous_id!=undefined){
                $('#'+previous_id).replaceWith("<td class='data' id='"+previous_id+"' >"+tdvalue+"</td>");
            }
            var cid = $(this).attr('id');
            previous_id=cid;
            var id=cid.split('_');
            combineid=id[1];
            tdvalue=$(this).text();

            if(tdvalue!=''){
                $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><input type='text' id='name' name='data'  class='subjectupdate' maxlength='50'  value='"+tdvalue+"'>");
            }

        } );

        $(document).on('change','.subjectupdate',function(){
            var subjectvalue=$(this).val().trim();
            if((subjectvalue!='')){
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var value=xmlhttp.responseText;
                        if(value==1)
                        {

//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE ENTRY",msgcontent:ET_ENTRY_errormsg[1],position:{top:100,left:100}}});
                            show_msgbox("EMAIL TEMPLATE ENTRY",ET_ENTRY_errormsg[1],"success",false);
                            first()
                        }
                        else
                        {
                            //MESSAGE BOX FOR NOT UPDATED
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE ENTRY",msgcontent:ET_ENTRY_errormsg[0],position:{top:100,left:100}}});
                            show_msgbox("EMAIL TEMPLATE ENTRY",ET_ENTRY_errormsg[0],"success",false);
                        }
//                        $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                    }
                }
                var OPTION="update1";
                xmlhttp.open("POST","SETTINGS/DB_EMAIL_EMAIL_TEMPLATE_ENTRY.do?option="+OPTION+"&subjectvalue="+subjectvalue+"&ETD_ID="+combineid,true);
                xmlhttp.send();
            }
        });

        var previous_id;
        var combineid;
        var tdvalue;
        $(document).on('click','.emailbody', function (){
            if(previous_id!=undefined){
                $('#'+previous_id).replaceWith("<td class='data' id='"+previous_id+"' >"+tdvalue+"</td>");
            }
            var cid = $(this).attr('id');
            previous_id=cid;
            var id=cid.split('_');
            combineid=id[1];
            tdvalue=$(this).text();
            if(tdvalue!=''){
                $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><textarea id='name' name='data'  class='bodyupdate' maxlength='50'  value='"+tdvalue+"'>'"+tdvalue+"'</textarea>");
            }

        } );

        $(document).on('change','.bodyupdate',function(){
            var bodyvalue=$(this).val().trim();
            if((bodyvalue!='')){
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var value=xmlhttp.responseText;
                        if(value==1)
                        {
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE ENTRY",msgcontent:ET_ENTRY_errormsg[1],position:{top:100,left:100}}});

                            show_msgbox("EMAIL TEMPLATE ENTRY",ET_ENTRY_errormsg[1],"success",false);
                            first()
                        }
                        else
                        {
                            //MESSAGE BOX FOR NOT UPDATED
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE ENTRY",msgcontent:ET_ENTRY_errormsg[0],position:{top:100,left:100}}});
                            show_msgbox("EMAIL TEMPLATE ENTRY",ET_ENTRY_errormsg[0],"success",false);
                        }
//                        $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                    }
                }
                var OPTION="update2";
                xmlhttp.open("POST","SETTINGS/DB_EMAIL_EMAIL_TEMPLATE_ENTRY.do?option="+OPTION+"&bodyvalue="+bodyvalue+"&ETD_ID="+combineid,true);
                xmlhttp.send();
            }
        });



    });
    <!--SCRIPT TAG END-->
</script>
<!--BODY TAG START-->
<body>
<div class="container">
    <div  class="preloader MaskPanel"><div class="statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="row title"><center><p><b><h3>EMAIL TEMPLATE ENTRY</h3></b><p></center></div>
    <form id="ET_ENTRY_form_template" class="content" >
        <div class="panel-body">
            <fieldset>
<div class ="table-responsive">
        <div class="row-fluid form-group">
            <label class="col-lg-2" name="ET_ENTRY_lbl_scriptname" id="ET_ENTRY_lbl_scriptname">SCRIPT NAME<em>*</em></label>
            <div class="col-lg-10">
                <input type="text" name="ET_ENTRY_tb_scriptname" id="ET_ENTRY_tb_scriptname"maxlength="100">
                <label id="ET_ENTRY_lbl_validid" name="ET_ENTRY_lbl_validid" class="errormsg" disabled=""></label>
            </div>

            <!--                    <label id="ET_ENTRY_lbl_validid" name="ET_ENTRY_lbl_validid" class="errormsg" disabled=""></label>-->
        </div>

        <!--                <td><label name="ET_ENTRY_lbl_scriptname" id="ET_ENTRY_lbl_scriptname">SCRIPT NAME<em>*</em></label></td>-->
        <!--                <td><input type="text" name="ET_ENTRY_tb_scriptname" id="ET_ENTRY_tb_scriptname" class="autosize" maxlength=100></td>-->
        <!--                <td><div><label id="ET_ENTRY_lbl_validid" name="ET_ENTRY_lbl_validid" class="errormsg" disabled=""></label></div></td>-->
        <!--        </tr>-->
        <div class="row-fluid form-group">
            <label class="col-lg-2" name="ET_ENTRY_lbl_subject" id="ET_ENTRY_lbl_subject">SUBJECT<em>*</em></label>
            <div class="col-lg-10">
                <textarea rows="4" cols="50" name="ET_ENTRY_ta_subject" id="ET_ENTRY_ta_subject" class="maxlength"maxlength="1000"></textarea>
            </div>
        </div>


        <!--            <tr>-->
        <!--                <td><label name="ET_ENTRY_lbl_subject" id="ET_ENTRY_lbl_subject">SUBJECT<em>*</em></label></td>-->
        <!--                <td><textarea rows="4" cols="50" name="ET_ENTRY_ta_subject" id="ET_ENTRY_ta_subject" class="maxlength">-->
        <!--                    </textarea></td>-->
        <!--            </tr>-->

        <div class="row-fluid form-group">
            <label class="col-lg-2" name="ET_ENTRY_lbl_body" id="ET_ENTRY_lbl_body">BODY<em>*</em></label>
            <div class="col-lg-10">
                <textarea class="form-control" rows="8" name="ET_ENTRY_ta_body" id="ET_ENTRY_ta_body" class="maxlength"></textarea>
            </div>
        </div>
        <!--            <tr>-->
        <!--                <td><label name="ET_ENTRY_lbl_body" id="ET_ENTRY_lbl_body">BODY<em>*</em></label></td>-->
        <!--                <td><textarea rows="4" cols="50" name="ET_ENTRY_ta_body" id="ET_ENTRY_ta_body" class="maxlength">-->
        <!--                    </textarea></td>-->
        <!--            </tr>-->
        <!--            <tr>-->
        <div>
            <button type="button" align="right" class="btn" name="ET_ENTRY_btn_save" id="ET_ENTRY_btn_save" disabled>SAVE</button>
            <button type="button" align="left" class="btn" name="ET_ENTRY_btn_reset" id="ET_ENTRY_btn_reset">RESET</button>

        </div><br>
        <!--                <td align="right"><input type="button" class="btn" name="ET_ENTRY_btn_save" id="ET_ENTRY_btn_save"   value="SAVE" disabled=""></td>-->
        <!--                <td align="left"><input type="button" class="btn" name="ET_ENTRY_btn_reset" id="ET_ENTRY_btn_reset"  value="RESET"></td>-->
        <!--            </tr>-->
        <!--        </table>-->

        <input type=hidden id="ET_ENTRY_hidden_chkvalid">
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