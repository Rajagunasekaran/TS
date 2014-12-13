<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************FORM_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE**************************************//
//DONE BY:SASIKALA
//VER 0.02 SD:14/10/2014 ED:16/10/2014,TRACKER NO:86,DESC:VALIDATION'S DONE
//VER 0.01-INITIAL VERSION, SD:20/09/2014 ED:13/10/2014,TRACKER NO:74 DONE BY:SHALINI
//*********************************************************************************************************//-->
<?php
include "HEADER.php";
?>
<script>
// READY FUNCTION STARTS
$(document).ready(function(){
    showTable();
    var  CACS_VIEW_customername;
    $('textarea').autogrow({onInitialize: true});
    $(".autosize").doValidation({rule:'alphabets',prop:{whitespace:true,autosize:true}});
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
    });
    //AUTOCOMPLETE TEXT
    var error_message=[];
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var values=JSON.parse(xmlhttp.responseText);
            var proj_auto=values[0];
            error_message=values[1];
            CACS_VIEW_customername=proj_auto;
        }
    }
    var option='AUTO';
    xmlhttp.open("GET","DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?&option="+option,true);
    xmlhttp.send();
    $(document).on("change blur",'#projectname',function(){
        var checkproject_name=$(this).val();
        if(checkproject_name!=''){
            $(".preloader").show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var check_array=(xmlhttp.responseText);
                    if(check_array==1){
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

//        }
        }

    });
    //CHANGE EVENT FOR PROJECT TEXT BOX
    $(document).on("change blur",'#PE_tb_prjectname', function (){
        var checkproject_name=$(this).val();
        if(checkproject_name!=''){
            $(".preloader").show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var check_array=(xmlhttp.responseText);
                    if(CACS_VIEW_customername==checkproject_name){
                        $('#PE_lbl_erromsg').hide();
                        $('#PE_tb_status').val('REOPEN'); //reopen
                    }
                    else if(check_array==1){
                        $('#PE_lbl_erromsg').text(error_message[0]).show();
                        $('#PE_tb_status').val('');
//                        $("#PE_tb_status").val('REOPEN').show();//error messag
                    }
                    else
                    {
//                            $('#PE_tb_status').text('PROJECT NAME ALREADY EXISTS');
                        $('#PE_lbl_erromsg').hide();
                        $('#PE_tb_status').val('STARTED').show();
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
        $(".preloader").show();
        var formElement = document.getElementById("PE_form_projectentry");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var msg_alert=xmlhttp.responseText;
                if(msg_alert==1)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UDATE",msgcontent:error_message[1],confirmation:true}});
                    $("#PE_tb_prjectname").val('').show();
                    $("#PE_ta_prjdescrptn").val('').show();
                    $("#PE_tb_sdate").val('').show();
                    $("#PE_tb_edate").val('').show();
                    $("#PE_tb_status").val('').show();
                    $("#PE_btn_save").attr("disabled", "disabled");
                    showTable();
                }
                else if(msg_alert==0)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UDATE",msgcontent:error_message[2],confirmation:true}});
                    $("#PE_tb_prjectname").val('').show();
                    $("#PE_ta_prjdescrptn").val('').show();
                    $("#PE_tb_sdate").val('').show();
                    $("#PE_tb_edate").val('').show();
                    $("#PE_tb_status").val('').show();
                    $("#PE_btn_save").attr("disabled", "disabled");
                    showTable();
                }
                else
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UDATE",msgcontent:msg_alert,confirmation:true}});
                    $("#PE_tb_prjectname").val('').show();
                    $("#PE_ta_prjdescrptn").val('').show();
                    $("#PE_tb_sdate").val('').show();
                    $("#PE_tb_edate").val('').show();
                    $("#PE_tb_status").val('').show();
                    $("#PE_btn_save").attr("disabled", "disabled");
                    showTable();
                }
            }
        }
        var option='SAVE';
        xmlhttp.open("POST","DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?option="+option,true);
        xmlhttp.send(new FormData(formElement));
    });
// SAVE BUTTON VALIDATION
    $(document).on('change blur','.valid',function(){
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
                var header='<table id="demoajax" border="1" cellspacing="0" width="1200">'
                header+=response;
                $('section').html(header);
                $('#demoajax').DataTable({
                    dom: 'T<"clear">lfrtip',
                    tableTools: {"aButtons": [
                        "pdf"],
                        "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                    }
                });
                $('#tablecontainer').show();
            }
        });
    }
// CLICK EVENT FOR EDIT BUTTON
    $('section').on('click','.ajaxedit',function(){
        $('.ajaxedit').attr("disabled","disabled");
        var edittrid = $(this).parent().parent().attr('id');
        var tds = $('#'+edittrid).children('td');
        var tdstr = '';
        var td = '';
        pre_tds = tds;
//        for(var j=0;j<field_arr.length;j++){
//
//            tdstr += "<td><input type='"+field_arr[j]+"' name='"+field_name[j]+"' value='"+$(tds[j]).html()+"'></td>";
//        }
        tdstr += "<td><input type='text' id='projectname' name='projectname'  class='autosize enable'value='"+$(tds[0]).html()+"'></td>";
        tdstr += "<td><input type='text' id='projectdes' name='projectdes'  class='autosize enable' value='"+$(tds[1]).html()+"'></td>";
        tdstr+="<td><select id='status' name='status' class='enable'><option value="+$(tds[2]).html()+">"+$(tds[2]).html()+"</option><option value='CLOSED'>CLOSED</option></select></td>";

        tdstr+="<td><input type='text' id='std' name='start_date' style='width:75px'; class='PE_tb_edatedatepicker enable' value='"+$(tds[3]).html()+"'></td>";
        tdstr+="<td><input type='text' name='end_date' id='PE_tb_enddate' style='width:75px'; class='PE_tb_edatedatepicker enable' value='"+$(tds[4]).html()+"' ></td>";
        tdstr+="<td>"+$(tds[5]).html()+"</td>";
        tdstr+="<td>"+$(tds[6]).html()+"</td>";
        tdstr+="<td>"+updatebutton +" " + cancel+"</td>";

        $('#'+edittrid).html(tdstr);

        $('.PE_tb_edatedatepicker').datepicker({
            dateFormat:"dd-mm-yy",
            maxDate: Date(),
            changeYear: true,
            changeMonth: true
        });
        $(".autosize").doValidation({rule:'alphabets',prop:{whitespace:true,autosize:true}});
    });
// UPDATE BUTTON VALIDATION
    $(document).on('change blur','.enable',function(){
        var projectname= $('#projectname').val();
        var projectsdate= $("#std").val();
        var projectstatus=$("#status").val();
        var projectdes=$("#projectdes").val().trim();
        var projectedate=$("#PE_tb_enddate").val();
//        alert(projectname+' '+projectdes+' '+projectstatus+' '+projectsdate+' '+projectedate)
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
    $('section').on("click",'.ajaxedit', function (){
//        $('.ajaxedit').attr("disabled","disabled");
        var checkproject_name=$('#projectname').val();
        if(checkproject_name!=''){
            $(".preloader").show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
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
            xmlhttp.open("GET","DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do?checkproject_name="+checkproject_name+"&option="+option,true);
            xmlhttp.send();
        }
    });
// CLICK EVENT FOR UPDATE BUTTON
    $('section').on('click','.ajaxupdate',function(){
        var edittrid = $(this).parent().parent().attr('id');
        var projectname =  $("input[name='"+field_name[0]+"']");
        var projectdes = $("input[name='"+field_name[1]+"']");
        var prostatus =  $('#status').val();
//           alert(prostatus);
        var projectsdate = $("input[name='start_date']");
        var projectedate =  $("input[name='end_date']");
//           alert(prostatus);
//           if(validate(projectname,projectdes,prostatus,projectsdate,projectedate)){
        data = "&name="+projectname.val()+"&des="+projectdes.val()+"&sta="+prostatus+"&ssd="+projectsdate.val()+"&eed="+projectedate.val()+"&editid="+edittrid+"&option=updateData";
//alert(data);
        $.ajax({
            url:"DB_PROJECT_PROJECT_ENTRY_SEARCH_UPDATE.do",
            type:"POST",
            data:data,
            cache: false,
            success: function(response){
                if(response==1){
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UDATE",msgcontent:error_message[3],confirmation:true}});
                    showTable()
//                    $('#demoajax').html(response);
                }
                else if(response==0)
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UDATE",msgcontent:error_message[4],confirmation:true}});
                    showTable()
                }
                else
                {
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"PROJECT ENTRY/SEARCH/UDATE",msgcontent:response,confirmation:true}});
                    showTable()
                }
            }
        });
    });
// CLICK EVENT FOR CANCEL BUTTON
    $('section').on('click','.ajaxcancel',function(){
        var edittrid = $(this).parent().parent().attr('id');
        $('#'+edittrid).html(pre_tds);
    });
});
// READY FUNCTION ENDS
</script>
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>PROJECT ENTRY/SEARCH/UPDATE</h3><p></div></div>
    <div class="container">
        <form  name="PE_form_projectentry" id="PE_form_projectentry" method="post" class="content">
            <table id="PE_tble_projectentry">
                <tr>
                    <td><label name="PE_lbl_prjectname" id="PE_lbl_prjectname">PROJECT NAME<em>*</em></label></td>
                    <td><input type="text" name="PE_tb_prjectname" id="PE_tb_prjectname" class="valid autosize"></td><td><label id="PE_lbl_erromsg" class="errormsg"></label></label></td>
                    <!--                <td><label id="PE_errmsg" name="PE_errmsg" class="errormsg" hidden></label></td>-->
                </tr>
                <tr>
                    <td><label name="PE_lbl_prjdescrptn" id="PE_lbl_prjdescrptn">PROJECT DESCRIPTION<em>*</em></label></td>
                    <td><textarea  name="PE_ta_prjdescrptn" id="PE_ta_prjdescrptn" class="maxlength autosize valid" ></textarea></td>
                </tr>
                <tr>
                    <td><label name="PE_lbl_status" id="PE_lbl_status" >STATUS<em>*</em></label></td>
                    <td><input type="text" id="PE_tb_status" name="PE_tb_status" style="width:100px;" class="valid" readonly></td>
                </tr>
                <tr>
                    <td><label name="PE_lbl_sdate" id="PE_lbl_sdate" >START DATE<em>*</em></label></td>
                    <td><input type="text" name="PE_tb_sdate" id="PE_tb_sdate" style="width:75px;" class="PE_tb_sdatedatepicker valid datemandtry"></td>
                </tr>
                <tr>
                    <td><label name="PE_lbl_edate" id="PE_lbl_edate" >END DATE<em>*</em></label></td>
                    <td><input type="text" name="PE_tb_edate" id="PE_tb_edate" style="width:75px;" class="PE_tb_edatedatepicker valid datemandtry"></td>
                </tr>
                <tr>
                    <td align="left"><input type="button" class="btn" name="PE_btn_save" id="PE_btn_save"  value="SAVE" disabled></td>
                </tr>
            </table>
            <div class="container" id="tablecontainer" hidden>
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