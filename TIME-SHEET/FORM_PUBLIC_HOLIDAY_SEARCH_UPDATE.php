<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************PUBLIC HOLIDAY SEARCH/UPDATE*********************************************//
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION, SD:17/12/2014 ED:17/12/2014,TRACKER NO:74
//*********************************************************************************************************//
<?php
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
var PH_SRC_UPD_yr_listbx=[];
var err_msg_array=[];
$('#PH_SRC_UPD_btn_search').hide();
//START DOCUMENT READY FUNCTION
$(document).ready(function(){
    $(".preloader").show();
    $('#PH_SRC_UPD_btn_search').hide();
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $(".preloader").hide();
            var values_array=JSON.parse(xmlhttp.responseText);
            PH_SRC_UPD_yr_listbx=values_array[0];
            err_msg_array=values_array[1];
            if(PH_SRC_UPD_yr_listbx.length!=0){
                var project_list='<option>SELECT</option>';
                for (var i=0;i<PH_SRC_UPD_yr_listbx.length;i++) {
                    project_list += '<option value="' + PH_SRC_UPD_yr_listbx[i] + '">' + PH_SRC_UPD_yr_listbx[i] + '</option>';
                }
                $('#PH_SRC_UPD_lb_yr').html(project_list);
                $('#PH_SRC_UPD_lb_yr').show();
                $('#PH_SRC_UPD_lbl_yr').show();
            }
            else
            {
                $('#PH_SRC_UPD_nodaterr').text(err_msg_array[3]).show();
            }
        }
    }
    var option="common";
    xmlhttp.open("GET","DB_PUBLIC_HOLIDAY_SEARCH_UPDATE.do?option="+option);
    xmlhttp.send();
    //DATE PICKER FUNCTION
    $('.PH_SRC_UPD_tb_dates').datepicker({
        dateFormat:"dd-mm-yy",
        changeYear: true,
        changeMonth: true
    });
    //CHANGE FUNCTION FOR YEAR
    var values_arraystotal=[];
    var values_array=[];
    var id;
    $(document).on('change','#PH_SRC_UPD_lb_yr',function(){
        $('.preloader', window.parent.document).show();
        $('#tablecontainer').hide();
        $('#PH_SRC_UPD_tble_htmltable').html('');
        $('section').html('');
        $('#PH_SRC_UPD_nodate').hide();
        var yr=$('#PH_SRC_UPD_lb_yr').val()
        var msg=err_msg_array[0].replace("[DATE]",yr);
        $('#PH_SRC_UPD_lbl_norole_err').text(msg).hide();
        flex_table();
    });
    //FUNCTION FOR FLEX TABLE
    function flex_table(){
        if($('#PH_SRC_UPD_lb_yr').val()!="SELECT")
        {
            var yr=$('#PH_SRC_UPD_lb_yr').val()
            var formElement = document.getElementById("PH_SRC_UPD_form");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
                    $(".preloader").hide();
                    values_arraystotal=JSON.parse(xmlhttp.responseText);
                    values_array=values_arraystotal[0];
                    if(values_array.length!=0)
                    {
                        var msg=err_msg_array[4].replace("[SCRIPT]",'PUBLIC HOLIDAY FOR '+yr);
                        $('#PH_SRC_UPD_nodate').text(msg).show();
                        var PH_SRC_UPD_table_header='<table id="PH_SRC_UPD_tble_htmltable" border="1"  cellspacing="0" class="srcresult" style="width:900px" ><thead  bgcolor="#6495ed" style="color:white"><tr><th style="width:10px"></th><th class="uk-date-column" style="width:100px">DATE</th><th style="width:400px">DESCRIPTION</th><th style="width:100px">USERSTAMP</th><th class="uk-timestp-column" style="width:180px">TIMESTAMP</th></tr></thead><tbody>'
                        for(var j=0;j<values_array.length;j++){
                            var PH_SRC_UPD_date=values_array[j].PH_SRC_UPD_date;
                            var PH_SRC_UPD_desc=values_array[j].PH_SRC_UPD_descr;
                            var PH_SRC_UPD_userstamp=values_array[j].PH_SRC_UPD_userstamp;
                            var PH_SRC_UPD_timestamp=values_array[j].PH_SRC_UPD_timestamp;
                            id=values_array[j].id;
                            PH_SRC_UPD_table_header+='<tr><td><input type="radio" name="EMPSRC_UPD_DEL_rd_flxtbl" class="EMPSRC_UPD_DEL_radio" id='+id+'  value='+id+' ></td><td  style="width:100px" align="center">'+PH_SRC_UPD_date+'</td><td style="width:400px">'+PH_SRC_UPD_desc+'</td><td style="width:100px">'+PH_SRC_UPD_userstamp+'</td><td style="width:180px">'+PH_SRC_UPD_timestamp+'</td></tr>';
                        }
                        PH_SRC_UPD_table_header+='</tbody></table>';
                        $('section').html(PH_SRC_UPD_table_header);
                        $('#PH_SRC_UPD_tble_htmltable').DataTable( {
                            "aaSorting": [],
                            "pageLength": 10,
                            "sPaginationType":"full_numbers",
                            "aoColumnDefs" : [
                                { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ],
                            dom: 'T<"clear">lfrtip',
                            tableTools: {"aButtons": [
                                {
                                    "sExtends": "pdf",
                                    "mColumns": [1, 2, 3 ,4],
                                    "sTitle": msg,
                                    "sPdfOrientation": "landscape",
                                    "sPdfSize": "A3"
                                }],
                                "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                            }
                        });
                    }
                    else
                    {
                        var msg=err_msg_array[1].replace("[DATE]",yr);
                        $('#PH_SRC_UPD_lbl_norole_err').text(msg).show();
                        $('#PH_SRC_UPD_updateform').hide();
                        $('#PH_SRC_UPD_btn_search').hide();
                        $('#tablecontainer').hide();
                        $('#PH_SRC_UPD_tble_htmltable').html('');

                    }
                    $('.preloader', window.parent.document).hide();
                }
            }
            $('#tablecontainer').show();
            var choice="PUBLIC_HOLIDAY_DETAILS"
            xmlhttp.open("POST","DB_PUBLIC_HOLIDAY_SEARCH_UPDATE.do?&option="+choice,true);
            xmlhttp.send(new FormData(formElement));
        }
        else
        {
            $('.preloader', window.parent.document).hide();
            $('#PH_SRC_UPD_updateform').hide();
            $('#PH_SRC_UPD_btn_search').hide();
            $('#tablecontainer').hide();
            $('#PH_SRC_UPD_tble_htmltable').html('');
        }
    }
    //RADIO CLICK FUNCTION
    $(document).on('click','.EMPSRC_UPD_DEL_radio',function(){
        $('#PH_SRC_UPD_btn_search').show();
        $("#PH_SRC_UPD_btn_search").removeAttr("disabled","disabled");
        $('#PH_SRC_UPD_updateform').hide();
    });
    var values_array=[];
    //CLICK FUNCTION FOR SEARCH BTN
    $(document).on('click','#PH_SRC_UPD_btn_search',function(){
        $('#PH_SRC_UPD_updateform').show();
        $('#EMP_ENTRY_table_others').show();
        $("#PH_SRC_UPD_btn_search").attr("disabled","disabled");
        $("#EMPSRC_UPD_DEL_btn_update").attr("disabled","disabled");
        $('#EMPSRC_UPD_DEL_lbl_validnumber1').hide();
        $('#EMPSRC_UPD_DEL_lbl_validnumber').hide();
        var SRC_UPD_idradiovalue=$('input:radio[name=EMPSRC_UPD_DEL_rd_flxtbl]:checked').attr('id');
        for(var j=0;j<values_array.length;j++){
            var id=values_array[j].id;
            var PH_SRC_UPD_dateval=values_array[j].PH_SRC_UPD_date;
            var PH_SRC_UPD_description=values_array[j].PH_SRC_UPD_descr;
            if(id==SRC_UPD_idradiovalue)
            {

                $('#PH_SRC_UPD_tb_date').val(PH_SRC_UPD_dateval).show();
                $('#PH_SRC_UPD_tb_des').val(PH_SRC_UPD_description).show();
            }
        }
        //MIN ND MAX DATE
        var year_val=$('#PH_SRC_UPD_lb_yr').val();
        var year=parseInt(year_val);
        var month=0;
        var date=parseInt('01');
        var minimumdate =new Date(year,month,date);
        $(".minmax").datepicker("option","minDate",minimumdate);
        var month=11;
        var date=parseInt('31');
        var maxdate =new Date(year,month,date);
        $(".minmax").datepicker("option","maxDate",maxdate);
    });
    //EMPLOYEE UPDATE BUTTON VALIDATION
    $(document).on('change','#PH_SRC_UPD_form',function(){
        var PH_SRC_UPD_date= $("#PH_SRC_UPD_tb_date").val();
        var PH_SRC_UPD_des =$("#PH_SRC_UPD_tb_des").val();
        if((PH_SRC_UPD_date!='') && (PH_SRC_UPD_des!='' ))
        {
            $("#PH_SRC_UPD_btn_update").removeAttr("disabled");
        }
        else
        {
            $("#PH_SRC_UPD_btn_update").attr("disabled","disabled");
        }
    });

    $(document).on('click','.paginate_button',function(){
//    alert('inside');

        $("#PH_SRC_UPD_updateform").hide();
        $('#PH_SRC_UPD_btn_search').hide();
        $('input:radio[name=EMPSRC_UPD_DEL_rd_flxtbl]').attr('checked',false);


    });
    //CLICK EVENT FUCNTION FOR UPDATE
    $('#PH_SRC_UPD_btn_update').click(function()
    {
        $('.preloader', window.parent.document).show();
        var PH_SRC_UPD_date=$('#PH_SRC_UPD_tb_date').val();
        var PH_SRC_UPD_des=$('#PH_SRC_UPD_tb_des').val();
        var formElement = document.getElementById("PH_SRC_UPD_form");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var ET_SRC_UPD_DEL_update_result=xmlhttp.responseText;
                if(ET_SRC_UPD_DEL_update_result==1){
                    $("#PH_SRC_UPD_updateform").hide();
                    $('#PH_SRC_UPD_btn_search').hide();
                    var msg=err_msg_array[0].replace("REPORT",'RECORD');
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PUBLIC HOLIDAY SEARCH/UPDATE",msgcontent:msg}});
                    PH_SRC_UPD_detailrset()
                    flex_table();
                }
                else
                {
                    //MESSAGE BOX FOR NOT UPDATED
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PUBLIC HOLIDAY SEARCH/UPDATE",msgcontent:err_msg_array[2]}});
                }
                $('.preloader', window.parent.document).hide();
            }
        }
        var choice="PROJECT_DETAILS_UPDATE"
        xmlhttp.open("POST","DB_PUBLIC_HOLIDAY_SEARCH_UPDATE.do?option="+choice,true);
        xmlhttp.send(new FormData(formElement));
    });
    //CLICK EVENT FUCNTION FOR RESET
    $('#PH_SRC_UPD_btn_reset').click(function()
    {
        $(".preloader").show();
        PH_SRC_UPD_detailrset()
    });
//RESET ALL THE ELEMENT//
    function PH_SRC_UPD_detailrset()
    {
        $(".preloader").hide();
        $('#PH_SRC_UPD_tb_date').val('');
        $('#PH_SRC_UPD_tb_des').val('');
        $("#PH_SRC_UPD_btn_update").attr("disabled","disabled");
    }
    //END DOCUMENT READY FUNCTION
});
<!--SCRIPT TAG END-->
</script>
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>PUBLIC HOLIDAY SEARCH/UPDATE</h3><p></div></div>
    <form  name="PH_SRC_UPD_form" id="PH_SRC_UPD_form" class="content" >
        <table id="PH_ENTRY_table" >
            <td width="150"><label name="PH_SRC_UPD_lbl_yr" id="PH_SRC_UPD_lbl_yr" hidden>SELECT A YEAR<em>*</em></label></td>
            <td width="150">
                <select id="PH_SRC_UPD_lb_yr" name="PH_SRC_UPD_lb_yr" hidden>
                </select>
            </td>
            <div><label id="PH_SRC_UPD_nodaterr" name="PH_SRC_UPD_nodaterr" class="errormsg"></label></div>
        </table>
        <table>
            <div class="srctitle" name="PH_SRC_UPD_nodate" id="PH_SRC_UPD_nodate" hidden></div>
            <div><label id="UPH_SRC_UPD_lbl_header" name="UPH_SRC_UPD_lbl_header" class="errormsg"></label></div>
            <div class="container">
                <div class="container" id="tablecontainer" style="width:900px;" hidden>
                    <section style="width:900px;">
                    </section>
                </div>
            </div>
            <div><label id="PH_SRC_UPD_lbl_norole_err" name="PH_SRC_UPD_lbl_norole_err" class="errormsg"></label></div>
        </table>
        <tr>
            <td><input class="btn" type="button"  id="PH_SRC_UPD_btn_search" name="PH_SRC_UPD_btn_search" value="SEARCH" hidden /></td>
        </tr>
        <table id="PH_SRC_UPD_updateform" hidden>
            <tr>
                <td width="150"><label name="PH_SRC_UPD_lbl_dte" id="PH_SRC_UPD_lbl_dte">DATE</label></td>
                <td><input type ="text" id="PH_SRC_UPD_tb_date" class='PH_SRC_UPD_tb_dates minmax proj datemandtry formshown update_validate' name="PH_SRC_UPD_tb_date" style="width:75px;"/></td>
            </tr>
            <tr>
                <td width="150"><label name="PH_SRC_UPD_lbl_des" id="PH_SRC_UPD_lbl_des">DESCRIPTION</label></td>
                <td><textarea rows="5" cols="100" name="PH_SRC_UPD_tb_des" id="PH_SRC_UPD_tb_des" class="validation uppercase maxlength"></textarea></td>
            </tr>
            <tr>
                <td  align="right"><input class="btn" type="button"  id="PH_SRC_UPD_btn_update" name="SAVE" value="UPDATE" disabled hidden /></td>
                <td align="left"><input type="button" class="btn" name="PH_SRC_UPD_btn_reset" id="PH_SRC_UPD_btn_reset" value="RESET"></td>
            </tr>
        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->