<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************ADMIN WEEKLY SEARCH/UPDATE******************************************//
//DONE BY:LALITHA
//0.02-SD:02/12/2014 ED:02/12/2014,TRACKER NO:74,Fixed max date nd min dte,Changed Preloder funct,Removed confirmation err msg,Fixed flex tble width
//DONE BY:SHALINI
//VER 0.01-INITIAL VERSION, SD:20/10/2014 ED:28/10/2014,TRACKER NO:86
//*********************************************************************************************************//-->
<?php
include "HEADER.php";
?>
<script>
// READY FUNCTION STARTS
$(document).ready(function(){
    $('.preloader').show();
//ERROR_MESSAGE
    var js_errormsg_array=[];
    var AWSU_weekly_mindate=[];
    var AWSU_weekly_maxdate=[];
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader').hide();
            var value_array=JSON.parse(xmlhttp.responseText);
            js_errormsg_array=value_array[0];
            AWSU_weekly_mindate=value_array[1];
            AWSU_weekly_maxdate=value_array[2];
            //SET MIN ND MAX DATE FUNCTION FRO START ND END DATE
            var PE_startdate=AWSU_weekly_mindate.split('-');
            var day=PE_startdate[0];
            var month=PE_startdate[1];
            var year=PE_startdate[2];
            PE_startdate=new Date(year,month-1,day);
            var date = new Date( Date.parse( PE_startdate ));
            date.setDate( date.getDate()  );
            var PE_enddate = date.toDateString();
            PE_enddate = new Date( Date.parse( PE_enddate ));
            $('.mindate').datepicker("option","minDate",PE_enddate);
            var PE_maxdate=AWSU_weekly_maxdate.split('-');
            var day=PE_maxdate[0];
            var month=PE_maxdate[1];
            var year=PE_maxdate[2];
            PE_maxdate=new Date(year,month-1,day);
            var date = new Date( Date.parse( PE_maxdate ));
            date.setDate( date.getDate()  );
            var PE_max_enddate = date.toDateString();
            PE_max_enddate = new Date( Date.parse( PE_max_enddate ));
            $('.maxdate').datepicker("option","maxDate",PE_max_enddate);
        }
    }
    var choice='ADMIN WEEKLY REPORT SEARCH UPDATE';
    xmlhttp.open("POST","COMMON.do?option="+choice,true);
    xmlhttp.send();
    //TEXT AREA AUTO GROW
    $('textarea').autogrow({onInitialize: true});
    // CREATING UPDATE AND CANCEL BUTTON
    var data='';
    var action = '';
    var updatebutton = "<input type='button' id='AWSU_btn_update' class='AWSU_btn_update btn' disabled value='Update'>";
    var cancel = "<input type='button' class='AWSU_btn_cancel btn' value='Cancel'>";
    var pre_tds;
    //FUNCTION FOR DATATABLE
    function showTable(){
        $("#AWSU_btn_search").attr("disabled", "disabled");
        $('#AWSU_nodata_startenddate').hide();
        $('.preloader', window.parent.document).show();
        var values_array=[];
        var startdate=$('#AWSU_tb_strtdte').val();
        var enddate = $('#AWSU_tb_enddte').val();
        var title=js_errormsg_array[3].toString().replace("[STARTDATE]",startdate);
        var titlemsg=title.toString().replace("[ENDDATE]",enddate);
        $('#AWSU_lbl_title').text(titlemsg).show();
        data ="&startdate="+startdate+"&enddate="+enddate+"&option=showData";
        $.ajax({
            url:"DB_WEEKLY_REPORT_ADMIN_WEEKLY_SEARCH_UPDATE.do",
            type:"POST",
            data:data,
            cache: false,
            success: function(response){
                $('.preloader', window.parent.document).hide();
                values_array=JSON.parse(response);
                if(values_array)
                {
                    var AWSU_tableheader='<table id="AWSU_tble_adminweeklysearchupdate" border="1" class="display"  cellspacing="0" width="1300" ><thead bgcolor="#6495ed" style="color:white"><tr class="head"><th style="min-width:180px;" nowrap>WEEK</th><th style="width:1500px">WEEKLY REPORT</th><th>USERSTAMP</th><th sstyle="min-width:150px;" nowrap>TIMESTAMP</th><th style="width:50px;">EDIT</th></tr></thead><tbody>';
                    for(var j=0;j<values_array.length;j++)
                    {
                        var id=values_array[j].id;
                        var week=values_array[j].date;
                        var d1=new Date(week);
                        var weeks= GetWeekInMonth(d1);
                        var weekreport=values_array[j].report;
                        var userstamp=values_array[j].userstamp;
                        var timestamp=values_array[j].timestamp;
                        var editbutton = "<input type='button' id='editbtn' class='AWSU_btn_edit btn' value='Edit'>";
                        AWSU_tableheader +='<tr id='+id+'><td style="min-width:180px;" nowrap>'+weeks+'</td><td style="width:1500px;">'+weekreport+'</td><td style="width:90px;">'+userstamp+'</td><td style="min-width:150px;" nowrap>'+timestamp+'</td><td style="width:50px;">'+editbutton+'</td></tr>';
                    }
                    AWSU_tableheader +='</tbody></table>';
                    $('section').html(AWSU_tableheader);
                    $('#AWSU_tble_adminweeklysearchupdate').DataTable({
                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            {
                                "sExtends": "pdf",
                                "sTitle": titlemsg,
                                "mColumns": [0, 1, 2, 3],
                                "sPdfOrientation": "landscape",
                                "sPdfSize": "A3"
                            }],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                }
                else
                {
                    var sd=js_errormsg_array[1].toString().replace("[SDATE]",startdate);
                    var msg=sd.toString().replace("[EDATE]",enddate);
                    $('#AWSU_nodata_startenddate').text(msg).show();
                    $('#tablecontainer').hide();
                    $('#AWSU_lbl_title').hide();
                }
            }
        });
        $('#tablecontainer').show();
    }
    //CLICK EVENT FOR SEARCH BUTTON
    $(document).on("click",'#AWSU_btn_search', function (){
        $("#AWSU_btn_search").attr("disabled", "disabled");
        showTable();
    });

// CLICK EVENT FOR EDIT BUTTON
    $('section').on('click','.AWSU_btn_edit',function(){
        $('.AWSU_btn_edit').attr("disabled","disabled");
//        $('textarea').height(50).width(60);
        var edittrid = $(this).parent().parent().attr('id');
        var tds = $('#'+edittrid).children('td');
        var tdstr = '';
        var td = '';
        pre_tds = tds;
        tdstr+="<td>"+$(tds[0]).html()+"</td>";
        tdstr +="<td><textarea id='AWSU_tb_report' name='AWSU_tb_report' value='"+$(tds[1]).html()+"'></textarea></td>";
        tdstr+="<td>"+$(tds[2]).html()+"</td>";
        tdstr+="<td>"+$(tds[3]).html()+"</td>";
        tdstr+="<td>"+updatebutton +" " + cancel+"</td>";
        $('#'+edittrid).html(tdstr);
        var str = $(tds[1]).html();
        var regex = /<br\s*[\/]?>/gi;
        $("#AWSU_tb_report").html(str.replace(regex, ""));
    });
    //FUNCTION FOR DATE TO WEEK CONVERSION
    function GetWeekInMonth(date)
    {
        var date1=new Date(date);
        var  WeekNumber = ['1st', '2nd', '3rd', '4th', '5th'];
        var weekNum = 0 | date1.getDate() / 7;
        weekNum = ( date1.getDate() % 7 == 0 ) ? weekNum - 1 : weekNum;
        var month=new Array();
        month[0]="January";
        month[1]="February";
        month[2]="March";
        month[3]="April";
        month[4]="May";
        month[5]="June";
        month[6]="July";
        month[7]="August";
        month[8]="September";
        month[9]="October";
        month[10]="November";
        month[11]="December";
        var d1=month[date1.getMonth()];
        var a=(WeekNumber[weekNum] + ' week ,   '+d1+"    "+date1.getFullYear());
        return a;
    }
// UPDATE BUTTON VALIDATION
    $(document).on('change blur','#AWSU_tb_report',function(){
        var AWSU_tb_report=$("#AWSU_tb_report").val();
        if(AWSU_tb_report !="")// && ($("#AWSU_tb_report").val().trim()!=aa))
        {
            $("#AWSU_btn_update").removeAttr("disabled");
        }
        else
        {
            $("#AWSU_btn_update").attr("disabled", "disabled");
        }
    });
//CLICK EVENT FOR CANCEL BUTTON
    $(document).on("click",'.AWSU_btn_cancel', function (){
        $('.AWSU_btn_edit').removeAttr("disabled");
    });
// CLICK EVENT FOR UPDATE BUTTON
    $('section').on('click','.AWSU_btn_update',function(){
        $('.preloader', window.parent.document).show();
        $('textarea').height(50).width(60);
        var edittrid = $(this).parent().parent().attr('id');
        var AWSU_tb_report = $('#AWSU_tb_report').val();
        data ="&report="+AWSU_tb_report+"&editid="+edittrid+"&option=updateData";
        $.ajax({
            url:"DB_WEEKLY_REPORT_ADMIN_WEEKLY_SEARCH_UPDATE.do",
            type:"POST",
            data:data,
            cache: false,
            success: function(response){
                if(response==1){
                    var msg=js_errormsg_array[0];
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN WEEKLY SEARCH/UPDATE",msgcontent:msg}});
                    showTable();
                }
                else
                {
                    var msg=js_errormsg_array[2];
                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN WEEKLY SEARCH/UPDATE",msgcontent:msg}});
                    showTable();
                }
                $('.preloader', window.parent.document).hide();
            }
        });
    });
// CLICK EVENT FOR CANCEL BUTTON
    $('section').on('click','.AWSU_btn_cancel',function(){
        var edittrid = $(this).parent().parent().attr('id');
        $('#'+edittrid).html(pre_tds);
    });
    //DATE PICKER FUNCTION
    $('.AWSU_tb_datepicker').datepicker({
        dateFormat:"dd MM yy",
        changeYear: true,
        changeMonth: true
    });
    //VALIDATION FOR SEARCH BUTTON
    $(document).on('change blur','.valid',function(){
        $('#tablecontainer').hide();
        $('#AWSU_nodata_startenddate').hide();
        $('#AWSU_lbl_title').hide();
        var startdate= $('#AWSU_tb_strtdte').val();
        var enddate=$("#AWSU_tb_enddte").val();
        if((startdate!="") &&(enddate !=""))
        {
            $("#AWSU_btn_search").removeAttr("disabled");
        }
        else
        {
            $("#AWSU_btn_search").attr("disabled", "disabled");
        }
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
    <div class="title" id="fhead" ><div style="padding-left:500px; text-align:left;"><p><h3>ADMIN WEEKLY SEARCH/UPDATE</h3><p></div></div>
    <div class="container">
        <form  name="PE_form_projectentry" id="PE_form_projectentry" method="post" class="content">
            <table>
                <tr>
                    <td width="150"><label name="AWSU_lbl_strtdte" id="AWSU_lbl_strtdte" >START DATE<em>*</em></label></td>
                    <td><input type="text" name="AWSU_tb_strtdte" id="AWSU_tb_strtdte" class="AWSU_tb_datepicker mindate maxdate valid datemandtry" style="width:120px;"></td><br>
                </tr>
                <tr>
                    <td width="150"><label name="AWSU_lbl_enddte" id="AWSU_lbl_enddte" >END DATE<em>*</em></label></td>
                    <td><input type="text" name="AWSU_tb_enddte" id="AWSU_tb_enddte" class="AWSU_tb_datepicker mindate maxdate valid datemandtry" style="width:120px;"></td><br>
                </tr>
                <td><input type="button" class="btn"  id="AWSU_btn_search" value="SEARCH" disabled></td><br>
            </table>
            <tr><td><label id="AWSU_nodata_startenddate" name="AWSU_nodata_startenddate" class="errormsg"></label></td></tr>
            <tr><td><label id="AWSU_lbl_title" name="AWSU_lbl_title" class="srctitle"></label></td></tr>
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