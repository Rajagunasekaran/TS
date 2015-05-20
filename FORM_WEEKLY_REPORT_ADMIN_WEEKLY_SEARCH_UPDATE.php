<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************ADMIN WEEKLY SEARCH/UPDATE******************************************//
//DONE BY:LALITHA
//0.06-SD:30/01/2014 ED:31/01/2014,TRACKER NO:74,Changed date picker validation
//0.05-SD:29/12/2014 ED:30/12/2014,TRACKER NO:74,Changed date picker nd validation,Preloader position
//0.04-SD:19/12/2014 ED:19/12/2014,TRACKER NO:74,Updated sorting function for date nd timestamp,Showned flex tble order by data()
//DONE BY:SASIKALA
//0.03-SD:03/12/2014 ED:04/12/2014,TRACKER NO:74,DONE REPORT SHOWING POINT BY POINT,DATATABLE HEADER FIXED AND PDF EXPORT FILENAME FIXED.
//DONE BY:LALITHA
//0.02-SD:02/12/2014 ED:02/12/2014,TRACKER NO:74,Fixed max date nd min dte,Changed Preloder funct,Removed confirmation err msg,Fixed flex tble width
//DONE BY:SHALINI
//VER 0.01-INITIAL VERSION, SD:20/10/2014 ED:28/10/2014,TRACKER NO:86
//*********************************************************************************************************//-->
<?php
include "HEADER.php";
?>
<!--<script>-->
<script type="text/javascript">
function pageSetup() {
    $('#dialog-form').dialog("close");
}
function rejectMemberreq(userNumber) {
    $('#subscriberNumber').val(userNumber);
    $('#reasonText').val("");
    $("#dialog-form").dialog("open");
    $("#button-ok").button("disable");
}
function removeMemberreq() {
    if($('#reasonText').val().length == 0){
        $("#button-ok").button("disable");
        $('#reasonText').focus();
    }else{
        var userNumber = $('#subscriberNumber').val();
        var reqReason = $('#reasonText').val();
        $.ajax({
            data : {
                datatype : 'json',
                method : 'rejectUser',
                subscriberNumber : userNumber,
                reasonText : reqReason
            },
            beforeSend : function() {
                $('#loadingscreen').show();
            },
            success : function(confmMsg) {
                $('#buttonRef-' + userNumber).html("REJECTED");
            },
            error : function(xhr, ajaxOptions, thrownError) {
                error = true;
            }
        });
        setTimeout($.unblockUI, 1000);
    }
}
/* Reject User req Dialog Form */
function enableOk(){
    var len = $('#reasonText').val().length;
    if(len > 1) {
        $("#button-ok").button("enable");
    }else{
        $("#button-ok").button("disable");
    }
}
// READY FUNCTION STARTS
$(document).ready(function(){
    $('#AWSU_btn_pdf').hide();
    $('.preloader', window.parent.document).show();
//ERROR_MESSAGE
    var pdfmsg;
    var dateText="";
    var js_errormsg_array=[];
    var AWSU_weekly_mindate=[];
    var AWSU_weekly_maxdate=[];
    var AWSU_weekly_month_enddate=[];
    var AWSU_weeklyS_month_enddate;
    var max_date;
    var min_date;
    var date_day;
    var date_min_day;
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $('.preloader', window.parent.document).hide();
//            alert(xmlhttp.responseText);
            var value_array=JSON.parse(xmlhttp.responseText);
            js_errormsg_array=value_array[0];
            AWSU_weekly_mindate=value_array[1];
            AWSU_weekly_maxdate=value_array[2];
            AWSU_weekly_month_enddate=value_array[3];
            if(AWSU_weekly_mindate=='1970-01-01' || AWSU_weekly_mindate==null)
            {
                $('#PE_form_projectentry').replaceWith('<p><label class="errormsg">'+ js_errormsg_array[3] +'</label></p>');
            }
            else
            {
                //MIN DATE CALCULATION
                var full_min_date=new Date(AWSU_weekly_mindate);
                date_min_day=full_min_date.getDate();
                if(date_min_day>7)
                {
                    var mod_min=Math.floor(date_min_day%7);
                    if(mod_min==0)
                    {
                        var final_min=date_min_day-7;
                    }
                    else
                    {
                        var final_min=date_min_day - mod_min

                    }
                    min_date=final_min+1;
                }
                else
                {
                    min_date=1;
                }
                //MAX DATE CALCULATION
                var full_date=new Date(AWSU_weekly_maxdate);
                date_day=full_date.getDate();
                if(date_day>7)
                {
                    var mod=Math.floor(date_day%7);
                    if(mod==0)
                    {
                        var final=0;
                    }
                    else
                    {
                        var final=7 - mod;
                    }
                    max_date=date_day + final
                }
                else
                {
                    var date_max=7-date_day
                    max_date=date_day+date_max
                }
                if(max_date>28)
                {
                    max_date=AWSU_weekly_month_enddate
                }
                //SET MIN ND MAX DATE FUNCTION FRO START ND END DATE
                var PE_startdate=AWSU_weekly_mindate.split('-');
                var day=min_date;
                var month=PE_startdate[1];
                var year=PE_startdate[0];
                PE_startdate=new Date(year,month-1,day);
                var date = new Date( Date.parse( PE_startdate ));
                date.setDate( date.getDate()  );
                var PE_enddate = date.toDateString();
                PE_enddate = new Date( Date.parse( PE_enddate ));
                $('.mindate').datepicker("option","minDate",PE_enddate);

                var PE_maxdate=AWSU_weekly_maxdate.split('-');
                var day=max_date;
                var month=PE_maxdate[1];
                var year=PE_maxdate[0];
                PE_maxdate=new Date(year,month-1,day);
                var date = new Date( Date.parse( PE_maxdate ));
                date.setDate( date.getDate()  );
                var PE_max_enddate = date.toDateString();
                PE_max_enddate = new Date( Date.parse( PE_max_enddate ));
                $('.maxdate').datepicker("option","maxDate",PE_max_enddate);
            }
        }
    }
    var choice='ADMIN WEEKLY REPORT SEARCH UPDATE';
    xmlhttp.open("POST","COMMON.do?option="+choice,true);
    xmlhttp.send();
    //FUNCTION FOR FORMTABLEDATEFORMAT
    function FormTableDateFormat(inputdate){
        var string = inputdate.split("-");
        return string[2]+'-'+ string[1]+'-'+string[0];
    }
    //TEXT AREA AUTO GROW
    $('textarea').autogrow({onInitialize: true});
    // CREATING UPDATE AND CANCEL BUTTON
    var data='';
    var action = '';
    var updatebutton = "<input type='button' id='AWSU_btn_update' class='AWSU_btn_update btn' disabled value='Update'>";
    var cancel = "<input type='button' class='AWSU_btn_cancel btn' value='Cancel'>";
    var pre_tds;
    //FUNCTION FOR DATATABLE
    function monthstartdate(date)
    {
        var curr = new Date(date);
        var first = curr.getDate() - curr.getDay();
        var last = first + 6;
        var firstday = new Date(curr.setDate(first));
        return firstday
    }
    function monthenddate(date)
    {
        var curr = new Date(date);
        var first = curr.getDate() - curr.getDay();
        var last = first + 6;
        var lastday = new Date(curr.setDate(last));
        return lastday
    }
    function datesplit(date)
    {
        var newdate=date.split(' ');
        var day=newdate[0];
        var month=newdate[1];
        var year=newdate[2];
        var res = month.substr(0, 3);
        var convert_date=day+'-'+res+'-'+year;
        return convert_date;
    }
    function dateformatchange(ipdate)
    {
        var date=new Date(ipdate);
        var day=date.getDate();
        var month=date.getMonth()+1;
        var year=date.getFullYear();
        var returndate=year+'-'+month+'-'+day;
        return returndate;
    }
    //FUNCTION FOR FLEX TABLE
    function showTable(){
        $('.preloader', window.parent.document).show();
        $("#AWSU_btn_search").attr("disabled", "disabled");
        $('#AWSU_nodata_startenddate').hide();
        var values_array=[];
        var startdate=$('#AWSU_tb_strtdtes').val();
        var enddate =$('#AWSU_tb_enddtes').val();

        var startdates=$('#AWSU_tb_strtdte').val();
        var newsddate=datesplit(startdate);
        var finalsddate= monthstartdate(newsddate);
        finalsddate=dateformatchange(finalsddate);
        var enddates =$('#AWSU_tb_enddte').val();
        var neweddate=datesplit(enddate);
        var finaleddate= monthenddate(neweddate);
        finaleddate=dateformatchange(finaleddate)
        var title=js_errormsg_array[4].toString().replace("[STARTDATE]",startdates);
        var titlemsg=title.toString().replace("[ENDDATE]",enddates);
        pdfmsg=titlemsg;
        data ="&startdate="+finalsddate+"&enddate="+finaleddate+"&option=showData";
        $.ajax({
            url:"DB_WEEKLY_REPORT_ADMIN_WEEKLY_SEARCH_UPDATE.do",
            type:"POST",
            data:data,
            cache: false,
            success: function(response){
                $('#AWSU_lbl_title').text(titlemsg).show();
                $('#AWSU_btn_pdf').show();
                values_array=JSON.parse(response);
                if(values_array)
                {
                    $('.preloader', window.parent.document).hide();
                    var AWSU_tableheader='<table id="AWSU_tble_adminweeklysearchupdate" border="1" class="display"  cellspacing="0" width="1300" ><thead bgcolor="#6495ed" style="color:white"><tr class="head"><th style="min-width:180px;"  class="uk-week-column" nowrap >WEEK</th><th style="width:1500px">WEEKLY REPORT</th><th>USERSTAMP</th><th sstyle="min-width:150px;" class="uk-timestp-column" nowrap>TIMESTAMP</th><th style="width:50px;">EDIT</th></tr></thead><tbody>';
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
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers",

                        "aoColumnDefs" : [
                            { "aTargets" : ["uk-week-column"] , "sType" : "uk_week"},    { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                    });
                }
                else
                {
                    $('.preloader', window.parent.document).hide();
                    var sd=js_errormsg_array[1].toString().replace("[SDATE]",startdates);
                    var msg=sd.toString().replace("[EDATE]",enddates);
                    $('#AWSU_nodata_startenddate').text(msg).show();
                    $('#tablecontainer').hide();
                    $('#AWSU_lbl_title').hide();
                    $('#AWSU_btn_pdf').hide();
                }
            }
        });
        $('#tablecontainer').show();
        sorting();
    }
    //FUNCTION FOR SORTING
    function sorting(){
        jQuery.fn.dataTableExt.oSort['uk_week-asc']  = function(a,b) {
            var x = new Date( Date.parse(weekDateFormat(a)));
            var y = new Date( Date.parse(weekDateFormat(b)) );
            return ((x < y) ? -1 : ((x > y) ?  1 : 0));
        };
        jQuery.fn.dataTableExt.oSort['uk_week-desc'] = function(a,b) {
            var x = new Date( Date.parse(weekDateFormat(a)));
            var y = new Date( Date.parse(weekDateFormat(b)) );
            return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
        }
        jQuery.fn.dataTableExt.oSort['uk_date-asc']  = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a)));
            var y = new Date( Date.parse(FormTableDateFormat(b)) );
            return ((x < y) ? -1 : ((x > y) ?  1 : 0));
        };
        jQuery.fn.dataTableExt.oSort['uk_date-desc'] = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a)));
            var y = new Date( Date.parse(FormTableDateFormat(b)) );
            return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
        }
        jQuery.fn.dataTableExt.oSort['uk_timestp-asc']  = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a.split(' ')[0]))).setHours(a.split(' ')[1].split(':')[0],a.split(' ')[1].split(':')[1],a.split(' ')[1].split(':')[2]);
            var y = new Date( Date.parse(FormTableDateFormat(b.split(' ')[0]))).setHours(b.split(' ')[1].split(':')[0],b.split(' ')[1].split(':')[1],b.split(' ')[1].split(':')[2]);
            return ((x < y) ? -1 : ((x > y) ?  1 : 0));
        };
        jQuery.fn.dataTableExt.oSort['uk_timestp-desc'] = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a.split(' ')[0]))).setHours(a.split(' ')[1].split(':')[0],a.split(' ')[1].split(':')[1],a.split(' ')[1].split(':')[2]);
            var y = new Date( Date.parse(FormTableDateFormat(b.split(' ')[0]))).setHours(b.split(' ')[1].split(':')[0],b.split(' ')[1].split(':')[1],b.split(' ')[1].split(':')[2]);
            return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
        };
    }
    //CLICK EVENT FOR SEARCH BUTTON
    $(document).on("click",'#AWSU_btn_search', function (){
        $("#AWSU_btn_search").attr("disabled", "disabled");
        showTable();
    });
// CLICK EVENT FOR EDIT BUTTON
    $('section').on('click','.AWSU_btn_edit',function(){
        $('.AWSU_btn_edit').attr("disabled","disabled");
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
        $("#AWSU_tb_report").html(str.replace(regex, "\n"));
    });
    //FUNCTION FOR DATE TO WEEK CONVERSION
    function GetWeekInMonths(date)
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
        var a=(WeekNumber[weekNum] + ' week ,'+d1+' '+date1.getFullYear());
        return a;
    }
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
        var a=(WeekNumber[weekNum] + ' week ,   '+d1+"-"+date1.getFullYear());
        return a;
    }
    //FUNCTION FOR WEEK TO DATE CONVERSION
    function weekDateFormat(date){
        var b=date.split('week');
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
        var  WeekNumber = ['1st', '2nd', '3rd', '4th', '5th'];
        b[0]=b[0].trim();
        var date= WeekNumber.indexOf('"'+b[0]+'"');
        date=date+7;
        if(date<10)
            date='0'+date;
        var c=b[1].split("-");
        c[0]=c[0].replace(',','');
        c[0]=c[0].trim()
        var mon= month.indexOf(c[0])+1;
        var year=c[1];
        return year+"-"+mon+"-"+date;
    }
// UPDATE BUTTON VALIDATION
    $(document).on('change blur','#AWSU_tb_report',function(){
        var AWSU_tb_report=$("#AWSU_tb_report").val();
        if(AWSU_tb_report !="")
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
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN WEEKLY SEARCH/UPDATE",msgcontent:msg,position:{top:150,left:500}}});
                    show_msgbox("ADMIN WEEKLY SEARCH/UPDATE",msg,"success",true);
                    showTable();
                }
                else
                {
                    var msg=js_errormsg_array[2];
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN WEEKLY SEARCH/UPDATE",msgcontent:msg,position:{top:150,left:500}}});
                    show_msgbox("ADMIN WEEKLY SEARCH/UPDATE",msg,"success",true);
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
//DATEPICKER FUNCTION
    $('#AWSU_tb_strtdte').datepicker({
        dateFormat:"dd MM yy",
        onSelect: function(dateText, inst) {
            $('#AWSU_tb_strtdtes').val(dateText);
            d1=new Date(dateText),
                date= GetWeekInMonths(d1);
            dateFormat:$(this).val(date);
//            validation();
        },
        changeYear: true,
        changeMonth: true
    });
    //DATEPICKER FUNCTION
    $('#AWSU_tb_enddte').datepicker({
        dateFormat:"dd MM yy",
        onSelect: function(dateText, inst) {
            $('#AWSU_tb_enddtes').val(dateText);
            d1=new Date(dateText),
                date= GetWeekInMonths(d1);
            dateFormat:$(this).val(date);
            validation();
        },
        changeYear: true,
        changeMonth: true
    });
    //VALIDATION FOR SEARCH BUTTON
    function validation(){
        $('#tablecontainer').hide();
        $('#AWSU_nodata_startenddate').hide();
        $('#AWSU_lbl_title').hide();
        $('#AWSU_btn_pdf').hide();
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
    }
    //CLICK FUNCTION FOR BTN PDF
    $(document).on('click','#AWSU_btn_pdf',function(){
        var inputValOne=$('#AWSU_tb_strtdtes').val();
        inputValOne = inputValOne.split("-").reverse().join("-");
        var inputValTwo=$('#AWSU_tb_enddtes').val();
        inputValTwo = inputValTwo.split("-").reverse().join("-");
        var url=document.location.href='COMMON_PDF.do?flag=19&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&title='+pdfmsg;
    });
});
// READY FUNCTION ENDS
</script>
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea"><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title" id="fhead"><center><h3>ADMIN WEEKLY SEARCH/UPDATE</h3></center></div>
    <form  name="PE_form_projectentry" id="PE_form_projectentry" method="post" class="content">
        <div class="form-group">
            <div class="row-fluid form-group">
                <label name="AWSU_lbl_strtdte"  class="col-sm-2" id="AWSU_lbl_strtdte" >START DATE<em>*</em></label>
                <input type="text" name="AWSU_tb_strtdte" id="AWSU_tb_strtdte" class="col-sm-offset-0 mindate maxdate valid datemandtry" style="width:160px;"><br>
                <input type="text" name="AWSU_tb_strtdtes" id="AWSU_tb_strtdtes"  style="width:170px;" class="AWSU_tb_datepicker" hidden><br>
            </div>
            <div class="row-fluid form-group">
                <label name="AWSU_lbl_enddte" class="col-sm-2" id="AWSU_lbl_enddte" >END DATE<em>*</em></label>
                <input type="text" name="AWSU_tb_enddte" id="AWSU_tb_enddte" class="col-sm-offset-0  mindate maxdate valid datemandtry" style="width:160px;"><br>
                <input type="text" name="AWSU_tb_enddtes" id="AWSU_tb_enddtes" class="AWSU_tb_datepicker" style="width:170px;" hidden ><br>
            </div>
            <div class="row-fluid form-group">
                <input type="button" class="btn"  id="AWSU_btn_search" value="SEARCH" disabled>
            </div>
        </div>
        <div class="row-fluid form-group"><label id="AWSU_nodata_startenddate" name="AWSU_nodata_startenddate" class="errormsg"></label></div>
        <div class="row-fluid form-group"><label id="AWSU_lbl_title" name="AWSU_lbl_title" class="srctitle"></label></div>
        <div><input type="button" id='AWSU_btn_pdf' class="btnpdf" value="PDF"></div>
        <div id="tablecontainer" class="table-responsive" hidden>
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