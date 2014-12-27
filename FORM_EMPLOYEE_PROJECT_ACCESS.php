<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE PROJECT ACCESS*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:24/09/2014 ED:29/09/2014,TRACKER NO:79
//*********************************************************************************************************//
<?php
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
    //GLOBAL DECLARATION
    var err_msg_array=[];
    var EMP_ENTRY_loginid=[];
    var project_array=[];
    //START DOCUMENT READY FUNCTION
    $(document).ready(function(){
        $(".preloader").show();
        $('#EMP_ENTRY_btn_save').hide();
        $('#EMP_ENTRY_btn_reset').hide();
        initialload();
        //FUNCTION FOR GETTING PROJECT LIST,ERR MSG,LOGIN ID
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
                            active_employee += '<option value="' + EMP_ENTRY_loginid[i] + '">' + EMP_ENTRY_loginid[i] + '</option>';
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
            $('.preloader', window.parent.document).show();
            $('input:checkbox[id=checkbox]').attr('checked',false);
            $('#checkbox').attr('checked',false);
            if($('#EMP_ENTRY_lb_loginid').val()=="SELECT")
            {
                $('.preloader', window.parent.document).hide();
                $('#EMP_ENTRY_btn_save').hide();
                $('#EMP_ENTRY_btn_reset').hide();
                $('#EMP_ENTRY_tble_frstsel_projectlistbx').hide();
                $('#EMP_ENTRY_tble_projectlistbx').hide();
                $('#EMP_ENTRY_lbl_txtselectproj').hide();
            }
            else
            {
                projectlist();
                $('.preloader', window.parent.document).hide();
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
            $('.preloader', window.parent.document).show();
            var loginid=$('#EMP_ENTRY_lb_loginid').val();
            var formElement = document.getElementById("EMP_ENTRY_form_employeename");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var msg_alert=xmlhttp.responseText;
                    if(msg_alert==1)
                    {
                        $('.preloader', window.parent.document).hide();
                        var msg=err_msg_array[2].replace("[LOGIN ID]",loginid);
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE PROJECT ACCESS",msgcontent:msg}});
                        EMP_ENTRY_rset()
                        initialload();
                    }
                    else
                    {
                        $('.preloader', window.parent.document).hide();
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE PROJECT ACCESS",msgcontent:err_msg_array[0]}});
                    }
                }
            }
            var choice="PROJECT_PROPETIES_SAVE"
            xmlhttp.open("POST","DB_EMPLOYEE_PROJECT_ACCESS.do?option="+choice,true);
            xmlhttp.send(new FormData(formElement));
        });
    });
    //END DOCUMENT READY FUNCTION
</script>
<!--SCRIPT TAG END-->
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>EMPLOYEE PROJECT ACCESS</h3><p></div></div>
    <form  name="EMP_ENTRY_form_employeename" id="EMP_ENTRY_form_employeename" class="content" >
        <table>
            <tr>
                <td style="width:155px"><label name="EMP_ENTRY_lbl_loginid" id="EMP_ENTRY_lbl_loginid" hidden>LOGIN ID<em>*</em></label></td>
                <td><select name="EMP_ENTRY_lb_loginid" id="EMP_ENTRY_lb_loginid" hidden>
                    </select></td>
                <div><label id="EMP_ENTRY_lbl_nologinid" name="EMP_ENTRY_lbl_nologinid" class="errormsg"></label></div>
            </tr>
            <table id="EMP_ENTRY_tble_projectlistbx" hidden>
                <tr><td width="150"><label name="EMP_ENTRY_lbl_txtselectproj" id="EMP_ENTRY_lbl_txtselectproj">PROJECT NAME</label><em>*</em></td>
                    <td> <table id="EMP_ENTRY_tble_frstsel_projectlistbx" ></table></td>
                </tr>
            </table>
            <tr>
                <td  align="right"><input type="button" class="btn" name="EMP_ENTRY_btn_save" id="EMP_ENTRY_btn_save"   value="SAVE" disabled="" hidden></td>
                <td align="left"><input type="button" class="btn" name="EMP_ENTRY_btn_reset" id="EMP_ENTRY_btn_reset"  value="RESET" hidden></td>
            </tr>
        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->