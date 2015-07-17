<!--//*******************************************FILE DESCRIPTION*********************************************//
//*********************************ADMIN WEEKLY REPORT ENTRY******************************************//
//DONE BY:LALITHA
//VER 0.06-SD:11/07/2015 ED:11/07/2015,github ver:,updated real escape string funct,changed txt bx as textarea fr report inline editing,in search option dnt hve record na showned err msg
//DONE BY:ARTHI
//VER 0.05-SD:09/06/2015 ED:10/06/2015,DID RESPONSIVE,INITIALLY DATE PICKER IS NOT LOADING FIXED THAT ISSUE.
//DONE BY:LALITHA
//VER 0.04-SD:29/12/2014 ED:30/12/2014,TRACKER NO:74,Changed date picker function nd validation,Updated err msg(rep nt saved)
//VER 0.03-SD:02/12/2014 ED:02/12/2014,TRACKER NO:74,Changed Preloder funct,Removed confirmation err msg,Removed hardcode fr mindate
//VER 0.02,SD:14/11/2014 ED:14/11/2014,TRACKER NO:74,Fixed max date nd min dte
//DONE BY:SHALINI
//VER 0.01-INITIAL VERSION, SD:16/10/2014 ED:19/10/2014,TRACKER NO:86
<!--//*********************************************************************************************************-->
<?php
include "../TSLIB/TSLIB_HEADER.php";
//include  "NEW_MENU.php";
?>
<html>
<head>
    <script xmlns="http://www.w3.org/1999/html">
        // READY FUNCTION STARTS
        $(document).ready(function(){
            $(".preloader").hide();
            $("#AWRE_SRC_ta_enterreport").hide();
            var maxdate;
            var js_errormsg_array=[];
            var js_min_date=[];
            var day;
            var month;
            var year;
            var date;
            var max_date;
            var datepicker_maxdate;
            var PE_startdate;
            var PE_enddate;
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
            var  PE_maxdate;
            var  PE_max_enddate;
            var report;
            $(document).on('change','.wdclick',function(){
                var radiooption=$(this).val();
                if(radiooption=='report_entry')
                {
                    $('#AWRE_lbl_report_entry').html('ADMIN WEEKLY REPORT ENTRY');
                    $('#report_entry').show();
                    $('#report_search_update').hide();
                    $('#AWSU_tb_strtdte').val('');
                    $('#AWSU_tb_strtdtes').hide();
                    $('#AWSU_tb_enddte').val('');
                    $('#AWSU_tb_enddtes').hide();
                    $('#AWSU_nodata_startenddate').hide();
                    $('#AWSU_lbl_title').hide();
                    $('#AWSU_btn_pdf').hide();
                    $('#tablecontainer').hide();
                    $(".preloader").show();

                    //ERROR MESSAGE
                    js_errormsg_array=[];
                    js_min_date=[];
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            var value_array=JSON.parse(xmlhttp.responseText);
                            js_errormsg_array=value_array[0];
                            js_min_date=value_array[1];
                            //SET MIN ND MAX DATE
                            maxdate=new Date();
                            day=maxdate.getUTCDay();
                            if(day==0)
                            {
                                month=maxdate.getMonth();
                                year=maxdate.getFullYear();
                                date=maxdate.getDate()+6;
                            }
                            if(day==1)
                            {
                                month=maxdate.getMonth();
                                year=maxdate.getFullYear();
                                date=maxdate.getDate()+5;
                            }
                            if(day==2)
                            {
                                month=maxdate.getMonth();
                                year=maxdate.getFullYear();
                                date=maxdate.getDate()+4;
                            }
                            if(day==3)
                            {
                                month=maxdate.getMonth();
                                year=maxdate.getFullYear();
                                date=maxdate.getDate()+3;
                            }
                            if(day==4)
                            {
                                month=maxdate.getMonth();
                                year=maxdate.getFullYear();
                                date=maxdate.getDate()+2;
                            }
                            if(day==5)
                            {
                                month=maxdate.getMonth();
                                year=maxdate.getFullYear();
                                date=maxdate.getDate()+1;
                            }
                            if(day==6)
                            {
                                month=maxdate.getMonth();
                                year=maxdate.getFullYear();
                                date=maxdate.getDate()-7;
                            }
                            max_date = new Date(year,month,date);
                            datepicker_maxdate=new Date(Date.parse(max_date));
                            PE_startdate=js_min_date.split('-');
                            day=PE_startdate[0];
                            month=PE_startdate[1];
                            year=PE_startdate[2];
                            PE_startdate=new Date(year,month-1,day);
                            date = new Date( Date.parse( PE_startdate ));
                            date.setDate( date.getDate()  );
                            PE_enddate = date.toDateString();
                            PE_enddate = new Date( Date.parse( PE_enddate ));
                            $('.AWRE_SRC_tb_datepicker').datepicker("option","minDate",PE_enddate);
                            $('.AWRE_SRC_tb_datepicker').datepicker("option","maxDate",datepicker_maxdate);

                        }
                    }
                    var choice='ADMIN WEEKLY REPORT ENTRY';
                    xmlhttp.open("POST","TSLIB/TSLIB_COMMON.do?option="+choice,true);
                    xmlhttp.send();
                    $('#AWRE_SRC_btn_reset').hide();
                }
                else if(radiooption=='report_search_update')
                {
                    $('#AWRE_lbl_report_entry').html('ADMIN WEEKLY REPORT SEARCH UPDATE');
                    $('#report_entry').hide();
                    $('#report_search_update').show();
                    $('#AWRE_SRC_tb_date').val('null');
                    $('#AWRE_SRC_lbl_enterreport').hide();
                    $('#AWRE_SRC_ta_enterreport').hide();
                    $('#AWRE_SRC_btn_submit').hide();
                    $('#AWRE_errmsg').hide();

                    $('#AWSU_btn_pdf').hide();
                    $(".preloader").show();
//ERROR_MESSAGE

                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            var value_array=JSON.parse(xmlhttp.responseText);
                            js_errormsg_array=value_array[0];
                            AWSU_weekly_mindate=value_array[1];
                            AWSU_weekly_maxdate=value_array[2];
                            AWSU_weekly_month_enddate=value_array[3];
//                    alert(AWSU_weekly_mindate)
                            if(AWSU_weekly_mindate=='1970-01-01' || AWSU_weekly_mindate==null)
                            {
                                $('#ARE_lbl_nodata_srcherrmsg').text(js_errormsg_array[3]).show();
//                                $('#AWRE_SRC_form_reportentry').replaceWith('<p><label class="errormsg">'+ js_errormsg_array[3] +'</label></p>');
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
                                PE_startdate=AWSU_weekly_mindate.split('-');
                                day=min_date;
                                month=PE_startdate[1];
                                year=PE_startdate[0];
                                PE_startdate=new Date(year,month-1,day);
                                date = new Date( Date.parse( PE_startdate ));
                                date.setDate( date.getDate()  );
                                PE_enddate = date.toDateString();
                                PE_enddate = new Date( Date.parse( PE_enddate ));
                                $('.mindate').datepicker("option","minDate",PE_enddate);

                                PE_maxdate=AWSU_weekly_maxdate.split('-');
                                day=max_date;
                                month=PE_maxdate[1];
                                year=PE_maxdate[0];
                                PE_maxdate=new Date(year,month-1,day);
                                date = new Date( Date.parse( PE_maxdate ));
                                date.setDate( date.getDate()  );
                                PE_max_enddate = date.toDateString();
                                PE_max_enddate = new Date( Date.parse( PE_max_enddate ));
                                $('.maxdate').datepicker("option","maxDate",PE_max_enddate);
                            }
                        }
                    }
                    var choice='ADMIN WEEKLY REPORT SEARCH UPDATE';
                    xmlhttp.open("POST","TSLIB/TSLIB_COMMON.do?option="+choice,true);
                    xmlhttp.send();
                }
            });
//DATEPICKER FUNCTION
            $('#AWRE_SRC_tb_selectdate').datepicker({
                onSelect: function(dateText, inst) {
                    $('#AWRE_SRC_tb_date').val(dateText);
                    d1=new Date(dateText),
                        date= GetWeekInMonth(d1);
                    dateFormat:$(this).val(date);
                    validation();
                },
                changeYear: true,
                changeMonth: true
            });
            function validation(){

                $('#AWRE_errmsg').text(msg).hide();
                var checkreportdate=$('#AWRE_SRC_tb_date').val();//$(this).val();
                var y=0;
                var array1=[];
                for(var z=0;z<date_array.length;z++)
                {
                    var d8=date_array[z];
                    var d7=W_GetWeekInmonth(d8);
                    var d9=W_GetWeekInmonth(checkreportdate);
                    if((d7)==(d9))
                    {
                        y++;
                    }
                }
                for(var i=0;i<date_array.length;i++)
                {
                    var d3=date_array[i];
                    var d2=W_GetWeekInMonth(d3);
                    array1.push(d2);
                }
                var p=0;
                for(var k=0;k<array1.length;k++)
                {
                    var d4=array1[k];
                    var d1=W_GetWeekInMonth(checkreportdate);
                    if(d4==d1)
                    {
                        p++;
                    }
                }
                if(p==0)
                {
//            alert('1');
                    $("#AWRE_SRC_lbl_enterreport").show();
//            alert('2');
                    $("#AWRE_SRC_ta_enterreport").val('').show();
                    $("#AWRE_SRC_btn_submit").show();
                    $("#AWRE_SRC_btn_reset").show();
                    $('#AWRE_errmsg').text(msg).hide();
                }
                else if(y==0)
                {
//            alert('3');
                    $("#AWRE_SRC_lbl_enterreport").show();
//            alert('4');
                    $("#AWRE_SRC_ta_enterreport").val('').show();
                    $("#AWRE_SRC_btn_submit").show();
                    $("#AWRE_SRC_btn_reset").show();
                    $('#AWRE_errmsg').text(msg).hide();
                }
                else
                {
//            alert('4');
                    var date=$("#AWRE_SRC_tb_selectdate").val();
//            alert(date);
                    var msg=js_errormsg_array[2].toString().replace("[DATE]",date);
//            alert(msg);
                    $('#AWRE_errmsg').text(msg).show();
                    $("#AWRE_SRC_lbl_enterreport").hide();
                    $("#AWRE_SRC_ta_enterreport").val('').hide();
                    $("#AWRE_SRC_btn_submit").hide();
                    $("#AWRE_SRC_btn_reset").hide();
                }
            }
            //CHECKING REPORT ALREADY ENTERED IN THIS WEEK OR NOT
            var d2;
            var date_array=[];
            function datesarray(){
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        date_array=JSON.parse(xmlhttp.responseText);
//                alert('checking');
                    }
                }
                var option='CHECK';
                xmlhttp.open("GET","ADMIN/DB_WEEKLY_REPORT_ADMIN_WEEKLY_REPORT_ENTRY.do?&option="+option,true);
                xmlhttp.send();
            }

//FUNCTION FOR DATE BOX VALIDATION
            $(document).on('change blur','.valid',function(){
                date= $('#AWRE_SRC_tb_selectdate').val();
                report=$("#AWRE_SRC_ta_enterreport").val().trim();
                if((date!="") &&(report !=""))
                {
                    $("#AWRE_SRC_btn_submit").removeAttr("disabled");
                }
                else
                {
                    $("#AWRE_SRC_btn_submit").attr("disabled", "disabled");
                }
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
                var a=(WeekNumber[weekNum] + ' week ,'+d1 +' '+date1.getFullYear());
                return a;
            }
            //DATEPICKER FUNCTION
            $('#AWSU_tb_strtdte').datepicker({
                dateFormat:"dd MM yy",
                onSelect: function(dateText, inst) {
                    $('#AWSU_tb_strtdtes').val(dateText);
                    d1=new Date(dateText),
                        date= GetWeekInMonths(d1);
                    dateFormat:$(this).val(date);
                    validations();
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
                    validations();
                },
                changeYear: true,
                changeMonth: true
            });
            //VALIDATION FOR SEARCH BUTTON
            function validations(){
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
            //CLICK EVENT FOR SEARCH BUTTON
            $(document).on("click",'#AWSU_btn_search', function (){
                $("#AWSU_btn_search").attr("disabled", "disabled");
                showTable();
            });
            var previous_id;
            var combineid;
            var tdvalue;
            $(document).on('click','.report', function (){
                if(previous_id!=undefined){
                    $('#'+previous_id).replaceWith("<td class='data' id='"+previous_id+"' >"+tdvalue+"</td>");
                }
                var cid = $(this).attr('id');
                previous_id=cid;
                var id=cid.split('_');
                combineid=id[1];
                tdvalue=$(this).text();

                if(tdvalue!=''){
                    $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><textarea type='text' id='name' name='data'  class='reportupdate' maxlength='50'  value='"+tdvalue+"'>"+tdvalue+"</textarea></td>");
                }

            } );

            $(document).on('change','.reportupdate',function(){
                var reportvalue=$(this).val().trim();
                if((reportvalue!='')){
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    alert('sss.'+xmlhttp.responseText)
                            var value=xmlhttp.responseText;
                            if(value==1)
                            {
                                var msg=js_errormsg_array[0];
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN WEEKLY SEARCH/UPDATE",msgcontent:msg,position:{top:100,left:100}}});
                                show_msgbox("ADMIN WEEKLY SEARCH/UPDATE",js_errormsg_array[0],"success",false);
                                showTable();
                                previous_id=undefined;
                            }
                            else
                            {
                                var msg=js_errormsg_array[2];
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN WEEKLY SEARCH/UPDATE",msgcontent:msg,position:{top:100,left:100}}});
                                show_msgbox("ADMIN WEEKLY SEARCH/UPDATE",js_errormsg_array[2],"success",false);
                                showTable();
                            }
//                        $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
                        }
                    }
                    var OPTION="update";
                    xmlhttp.open("POST","ADMIN/DB_WEEKLY_REPORT_ADMIN_WEEKLY_SEARCH_UPDATE.do?option="+OPTION+"&reportvalue="+reportvalue+"&id="+combineid,true);
                    xmlhttp.send();
                }
            });
            //CLICK FUNCTION FOR BTN PDF
            $(document).on('click','#AWSU_btn_pdf',function(){
                var inputValOne=$('#AWSU_tb_strtdtes').val();
                inputValOne = inputValOne.split("-").reverse().join("-");
                var inputValTwo=$('#AWSU_tb_enddtes').val();
                inputValTwo = inputValTwo.split("-").reverse().join("-");
                var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=19&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&title='+pdfmsg;
            });

//    });

            <!--    entry-->
            $('#AWRE_SRC_btn_submit').hide();
            $('#AWRE_SRC_btn_reset').hide();
            $('textarea').autogrow({onInitialize: true});
            datesarray();

//        FUNCTION FOR FINDING DATE ALREADY ENTERED
            function W_GetWeekInmonth(date)
            {
                var date1 = new Date(date);
                WeekNumber = ['1', '2', '3', '4', '5'];
                var weekNum = 0 | date1.getDate() / 7;
                weekNum = ( date1.getDate() % 7 === 0 ) ? weekNum - 1 : weekNum;
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
                var a=(WeekNumber[weekNum] +d1+date1.getFullYear());
                return a;
            }
            function W_GetWeekInMonth(date)
            {
                var date1 = new Date(date);
                WeekNumber = ['1', '2', '3', '4', '5'];
                var weekNum = 0 | date1.getDate() / 7;
                weekNum = ( date1.getDate() % 7 === 0 ) ? weekNum - 1 : weekNum;
                return WeekNumber[weekNum];
            }

//CLICK EVENT FOR SUBMIT BUTTON
            $(document).on('click','#AWRE_SRC_btn_submit',function(){
                $(".preloader").show();
                var formElement = document.getElementById("AWRE_SRC_form_reportentry");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var msg_alert=xmlhttp.responseText;
                        var msg=js_errormsg_array[0];
                        if(msg_alert==1)
                        {
                            show_msgbox("ADMIN WEEKLY REPORT ENTRY",msg,"success",false);
                            $("#AWRE_SRC_lbl_enterreport").hide();
                            $("#AWRE_SRC_ta_enterreport").val('').hide();
                            $("#AWRE_SRC_btn_submit").hide();
                            $("#AWRE_SRC_btn_reset").hide();
                            $("#AWRE_SRC_tb_selectdate").val('').show();
                            datesarray();
                        }
                        else
                        {
                            show_msgbox("ADMIN WEEKLY REPORT ENTRY",js_errormsg_array[1],"success",false);
                        }
                        $(".preloader").hide();
                    }
                }
                var option='SUBMIT';
                xmlhttp.open("POST","ADMIN/DB_WEEKLY_REPORT_ADMIN_WEEKLY_REPORT_ENTRY.do?option="+option,true);
                xmlhttp.send(new FormData(formElement));
            });
            //CLICK EVENT FOR RESET BUTTON
            $('#AWRE_SRC_btn_reset').click(function(){
                $("#AWRE_SRC_tb_selectdate").val('').show();
                $("#AWRE_SRC_lbl_enterreport").hide();
                $("#AWRE_SRC_ta_enterreport").hide();
                $("#AWRE_SRC_btn_submit").hide();
                $("#AWRE_SRC_btn_reset").hide();
            });
            <!--entry end-->

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
//            $('.preloader', window.parent.document).show();
                $(".preloader").show();
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
                    url:"ADMIN/DB_WEEKLY_REPORT_ADMIN_WEEKLY_SEARCH_UPDATE.do",
                    type:"POST",
                    data:data,
                    cache: false,
                    success: function(response){
                        $('#AWSU_lbl_title').text(titlemsg).show();
                        $('#AWSU_btn_pdf').show();
                        values_array=JSON.parse(response);
                        $(".preloader").hide();
                        if(values_array)
                        {
                            var AWSU_tableheader='<table id="AWSU_tble_adminweeklysearchupdate" border="1" class="display"  cellspacing="0" ><thead bgcolor="#6495ed" style="color:white"><tr class="head"><th   class="uk-week-column" nowrap >WEEK</th><th>WEEKLY REPORT</th><th>USERSTAMP</th><th class="uk-timestp-column" nowrap>TIMESTAMP</th></tr></thead><tbody>';
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
                                AWSU_tableheader +='<tr id='+id+'><td  nowrap>'+weeks+'</td><td  id=report_'+id+' class="report">'+weekreport+'</td><td>'+userstamp+'</td><td nowrap>'+timestamp+'</td></tr>';
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
//                        $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
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
//            $('.preloader', window.parent.document).show();
                $(".preloader").show();
                var edittrid = $(this).parent().parent().attr('id');
                var AWSU_tb_report = $('#AWSU_tb_report').val();
                data ="&report="+AWSU_tb_report+"&editid="+edittrid+"&option=updateData";
                $.ajax({
                    url:"ADMIN/DB_WEEKLY_REPORT_ADMIN_WEEKLY_SEARCH_UPDATE.do",
                    type:"POST",
                    data:data,
                    cache: false,
                    success: function(response){
                        if(response==1){
                            var msg=js_errormsg_array[0];
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN WEEKLY SEARCH/UPDATE",msgcontent:msg,position:{top:100,left:100}}});
                            show_msgbox("ADMIN WEEKLY SEARCH/UPDATE",msg,"success",false);
                            showTable();
                        }
                        else
                        {
                            var msg=js_errormsg_array[2];
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ADMIN WEEKLY SEARCH/UPDATE",msgcontent:msg,position:{top:100,left:100}}});
                            show_msgbox("ADMIN WEEKLY SEARCH/UPDATE",msg,"success",false);
                            showTable();
                        }
//                    $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                    }
                });
            });
// CLICK EVENT FOR CANCEL BUTTON
            $('section').on('click','.AWSU_btn_cancel',function(){
                var edittrid = $(this).parent().parent().attr('id');
                $('#'+edittrid).html(pre_tds);
            });

        });
        // READY FUNCTION ENDS
    </script>
</head>
<body>
<!--BODY TAG START-->
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/></div>
    <div class="title text-center"><h4><b>ADMIN WEEKLY REPORT/SEARCH/UPDATE</b></h4></div>
    <form id="AWRE_SRC_form_reportentry"  class="form-horizontal content" role="form">
        <div class="panel-body">
            <fieldset>
                <div style="padding-bottom: 15px">
                    <div class="radio">
                        <label><input type="radio" name="UR_ESU" class="wdclick" id="UR_ENTRY" value="report_entry">ENTRY</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="UR_ESU" class="wdclick" id="UR_SEARCH_UPDATE" value="report_search_update">SEARCH / UPDATE</label>

                    </div>
                </div>
                <div class="row-fluid form-group">
                    <label name="AWRE_report_entry" id="AWRE_lbl_report_entry" class="srctitle col-sm-12"></label>
                </div>
                <div id="report_entry" hidden>
                    <div class="row-fluid form-group">
                        <label name="AWRE_SRC_lbl_selectdate"  class="col-sm-2" id="AWRE_SRC_lbl_selectdate" >SELECT A DATE<em>*</em></label>
                        <div class="col-sm-8">
                            <input type="text" name="AWRE_SRC_tb_selectdate" id="AWRE_SRC_tb_selectdate" style="width:160px;" class="AWRE_SRC_tb_datepicker valid datemandtry">
                            <input type="text" name="AWRE_SRC_tb_date" id="AWRE_SRC_tb_date" style="width:170px;" class="AWRE_SRC_tb_datepicker valid datemandtry" hidden >
                        </div></div>
                    <div class="form-group">
                        <label name="AWRE_SRC_lbl_enterreport" class="col-sm-2" id="AWRE_SRC_lbl_enterreport" hidden>ENTER THE REPORT<em>*</em></label>
                        <div class="col-sm-4">
                            <textarea name="AWRE_SRC_ta_enterreport" id="AWRE_SRC_ta_enterreport" style="height: 154px" rows="5" class="valid  form-control" hidden></textarea>
                        </div></div>
                    <div class="form-group" style="padding-left:15px">
                        <input type="button" value="SUBMIT" id="AWRE_SRC_btn_submit" class="btn" disabled>
                        <input type="button" value="RESET" id="AWRE_SRC_btn_reset" class="btn">

                    </div>
                    <label id="AWRE_errmsg" name="AWRE_errmsg" class="errormsg" hidden></label>
                </div>
                <div id="report_search_update" hidden>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="AWSU_lbl_strtdte" id="AWSU_lbl_strtdte" >START DATE<em>*</em></label>
                        <div class="col-sm-8">
                            <input type="text" name="AWSU_tb_strtdte" id="AWSU_tb_strtdte" class="mindate maxdate valid datemandtry" style="width:160px;">
                            <input type="text" name="AWSU_tb_strtdtes" id="AWSU_tb_strtdtes"  style="width:170px;" class="AWSU_tb_datepicker" hidden>
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="AWSU_lbl_enddte" id="AWSU_lbl_enddte" >END DATE<em>*</em></label>
                        <div class="col-sm-8">
                            <input type="text" name="AWSU_tb_enddte" id="AWSU_tb_enddte" class="mindate maxdate valid datemandtry" style="width:160px;">
                            <input type="text" name="AWSU_tb_enddtes" id="AWSU_tb_enddtes" class="AWSU_tb_datepicker" style="width:170px;" hidden >
                        </div></div>
                    <div><input type="button" class="btn"  id="AWSU_btn_search" value="SEARCH" disabled></div>
                    <div><label id="ARE_lbl_errmsg" name="ARE_lbl_nodata_srcherrmsg" class="errormsg"></label></div>
                    <div><label id="AWSU_nodata_startenddate" name="AWSU_nodata_startenddate" class="errormsg"></label></div>
                    <div><label id="AWSU_lbl_title" name="AWSU_lbl_title" class="srctitle"></label></div>
                    <div><input type="button" id='AWSU_btn_pdf' class="btnpdf" value="PDF"></div>
                    <div  id="tablecontainer" class="table-responsive" hidden>
                        <section>
                        </section>
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