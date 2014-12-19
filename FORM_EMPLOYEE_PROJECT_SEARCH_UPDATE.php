<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************EMPLOYEE PROJECT SEARCH/UPDATE*********************************************//
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
    var EMPSRC_UPD_loginid=[];
    var project_array=[];
    var EMPSRC_UPD_proj_array=[];
    var EMPSRC_UPD_proj_id=[];
    //START DOCUMENT READY FUNCTION
    $(document).ready(function(){
        $(".preloader").show();
        $('#EMPSRC_UPD_btn_update').hide();
        $('#EMPSRC_UPD_btn_reset').hide();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var EMPSRC_UPD_loginid=$('#EMPSRC_UPD_lb_loginid').val();
                var values_array=JSON.parse(xmlhttp.responseText);
                EMPSRC_UPD_loginid=values_array[0];
                err_msg_array=values_array[1];
                if(EMPSRC_UPD_loginid.length!=0)
                {
                    var active_employee='<option>SELECT</option>';
                    for (var i=0;i<EMPSRC_UPD_loginid.length;i++) {
                        active_employee += '<option value="' + EMPSRC_UPD_loginid[i] + '">' + EMPSRC_UPD_loginid[i] + '</option>';
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
            $('#EMPSRC_UPD_btn_update').hide();
            $('#EMPSRC_UPD_btn_reset').hide();
            $('#EMPSRC_UPD_lbl_txtselectproj').hide();
            $('#EMPSRC_UPD_tble_frstsel_projectlistbx').html('');
            $('.preloader', window.parent.document).show();
            if($('#EMPSRC_UPD_lb_loginid').val()=="SELECT")
            {
                $('.preloader', window.parent.document).hide();
                $('#EMPSRC_UPD_btn_update').hide();
                $('#EMPSRC_UPD_btn_reset').hide();
                $('#EMPSRC_UPD_tble_frstsel_projectlistbx').hide();
                $('#EMPSRC_UPD_tble_projectlistbx').hide();
                $('#EMPSRC_UPD_lbl_txtselectproj').hide();
            }
            else{
            var formElement = document.getElementById("EMPSRC_UPD_form_employeename");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
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
                $('.preloader', window.parent.document).hide();
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
        $(document).on('change blur','#EMPSRC_UPD_form_employeename',function(){
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
            $('.preloader', window.parent.document).show();
            var loginid=$('#EMPSRC_UPD_lb_loginid').val();
            var formElement = document.getElementById("EMPSRC_UPD_form_employeename");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var msg_alert=xmlhttp.responseText;
                    if(msg_alert==1)
                    {
                        $('.preloader', window.parent.document).hide();
                        var msg=err_msg_array[2].replace("[LOGIN ID]",loginid);
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE PROJECT SEARCH/UPDATE",msgcontent:msg}});
                        EMPSRC_UPD_rset()
                    }
                    else
                    {
                        $('.preloader', window.parent.document).hide();
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMPLOYEE PROJECT SEARCH/UPDATE",msgcontent:err_msg_array[0]}});
                    }
                }
            }
            var choice="PROJECT_PROPERTIES_UPDATE"
            xmlhttp.open("POST","DB_EMPLOYEE_PROJECT_SEARCH_UPDATE.do?option="+choice,true);
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
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>EMPLOYEE PROJECT SEARCH/UPDATE</h3><p></div></div>
    <form  name="EMPSRC_UPD_form_employeename" id="EMPSRC_UPD_form_employeename" class="content" >
        <table>
            <tr>
                <td style="width:160px"><label name="EMPSRC_UPD_lbl_loginid" id="EMPSRC_UPD_lbl_loginid" hidden>LOGIN ID<em>*</em></label></td>
                <td><select name="EMPSRC_UPD_lb_loginid" id="EMPSRC_UPD_lb_loginid" hidden>
                    </select></td>
                <div><label id="EMPSRC_UPD_lbl_nologinid" name="EMPSRC_UPD_lbl_nologinid" class="errormsg"></label></div>
            </tr>
            <table id="EMPSRC_UPD_tble_projectlistbx" hidden>
                <tr><td width="150"><label name="EMPSRC_UPD_lbl_txtselectproj" id="EMPSRC_UPD_lbl_txtselectproj">PROJECT NAME<em>*</em></label></td>
                    <td> <table id="EMPSRC_UPD_tble_frstsel_projectlistbx" ></table></td>
                </tr>
            </table>
            <tr>
                <td  align="right"><input type="button" class="btn" name="EMPSRC_UPD_btn_update" id="EMPSRC_UPD_btn_update"   value="UPDATE" disabled hidden></td>
                <td align="left"><input type="button" class="btn" name="EMPSRC_UPD_btn_reset" id="EMPSRC_UPD_btn_reset"  value="RESET" hidden></td>
            </tr>
        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->