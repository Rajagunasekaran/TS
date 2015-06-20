<!--*********************************************GLOBAL DECLARATION******************************************-->
<!--*********************************************************************************************************//-->
<!--//*******************************************FILE DESCRIPTION*********************************************//
//****************************************USER SEARCH DETAILS*************************************************//
//DONE BY:RAJA
//VER 0.03-SD:05/01/2015 ED:06/01/2015, TRACKER NO:166,175,179,DESC:IMPLEMENTED PDF BUTTON AND VALIDATED AND GAVE INPUT TO DB, CHANGED LOGIN ID AS EMPLOYEE NAME, SETTING PRELOADER POSITON
//DONE BY:LALITHA
//VER 0.02 SD:31/10/2014 ED:31/10/2014,TRACKER NO:79,Updated date sorting,Changed resize the width,Changed header name,Changed resize the width
//VER 0.01-INITIAL VERSION,SD:11/10/2014 ED:11/10/2014,TRACKER NO:79
//*********************************************************************************************************//
<?php
include "TSLIB/TSLIB_HEADER.php";
?>
<!--HTML TAG START-->
<html>
<head>
<!--HEAD TAG START-->
<script>
    //DOCUMENT READY FUNCTION START
    $(document).ready(function(){
        $(".preloader").hide();
        $('#URSRC_btn_pdf').hide();
        $('#STDTL_SEARCH_div_flexdata_result').hide();
        $(".preloader").show();
        var title;
        var values_arraystotal=[];
        var values_array=[];
        table();
        //FUNCTION FOR FORM TABLE DATE FORMAT
        function FormTableDateFormat(inputdate){
            var string = inputdate.split("-");
            return string[2]+'-'+ string[1]+'-'+string[0];
        }
        function table(){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                    $(".preloader").hide();
                    values_arraystotal=JSON.parse(xmlhttp.responseText);
                    values_array=values_arraystotal[0];
                    var USD_SRC_errorAarray=values_arraystotal[1];
                    if(values_array.length!=0)
                    {
                        title=USD_SRC_errorAarray[1].toString().replace("PROJECT","EMPLOYEE");
                        $('#URSRC_lbl_title').text(title).show();
                        $('#URSRC_btn_pdf').show();
                        var USU_table_header='<table id="USD_SRC_SRC_tble_htmltable" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th nowrap>EMPLOYEE NAME</th><th>ROLE</th><th>REC VER</th><th class="uk-date-column">JOIN DATE</th><th class="uk-date-column">TERMINATION DATE</th><th>REASON OF TERMINATION</th><th nowrap>EMP TYPE</th><th>USERSTAMP</th><th class="uk-timestp-column">TIMESTAMP</th></tr></thead><tbody>'
                        for(var j=0;j<values_array.length;j++){
                            var USD_SRC_loginid=values_array[j].loginid;
                            var USD_SRC_rcid=values_array[j].rcid;
                            var USD_SRC_recordver=values_array[j].recordver;
                            var USD_SRC_joindate=values_array[j].joindate;
                            var USD_SRC_terminationdate=values_array[j].terminationdate;
                            if((USD_SRC_terminationdate=='null')||(USD_SRC_terminationdate==undefined))
                            {
                                USD_SRC_terminationdate='';
                            }
                            var USD_SRC_reasonoftermination=values_array[j].reasonoftermination;
                            if((USD_SRC_reasonoftermination=='null')||(USD_SRC_reasonoftermination==undefined))
                            {
                                USD_SRC_reasonoftermination='';
                            }
                            var USD_SRC_emptype=values_array[j].emptypes;
                            if((USD_SRC_emptype=='null')||(USD_SRC_emptype==undefined))
                            {
                                USD_SRC_emptype='';
                            }
                            var USD_SRC_userstamp=values_array[j].userstamp;
                            var USD_SRC_timestamp=values_array[j].timestamp;
                            USU_table_header+='<tr><td nowrap>'+USD_SRC_loginid+'</td><td align="center">'+USD_SRC_rcid+'</td><td align="center">'+USD_SRC_recordver+'</td><td nowrap align="center">'+USD_SRC_joindate+'</td><td align="center">'+USD_SRC_terminationdate+'</td><td>'+USD_SRC_reasonoftermination+'</td><td align="center">'+USD_SRC_emptype+'</td><td align="center">'+USD_SRC_userstamp+'</td><td align="center">'+USD_SRC_timestamp+'</td></tr>';
                        }
                        USU_table_header+='</tbody></table>';
                        $('section').html(USU_table_header);
                        $('#STDTL_SEARCH_div_flexdata_result').show();
                        $('#USD_SRC_SRC_tble_htmltable').DataTable( {
                            "aaSorting": [],
                            "pageLength": 10,
                            "sPaginationType":"full_numbers",
                            "aoColumnDefs" : [
                                { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                        });
                    }
                    else
                    {
                        $('#STDTL_SEARCH_div_flexdata_result').hide();
                        $('#URSRC_lbl_norole_err').text(USD_SRC_errorAarray[0]).show();
                        $('#URSRC_lbl_title').hide();
                        $('#URSRC_btn_pdf').hide();
                    }
                }
            }
//            $('#tablecontainer').show();
            xmlhttp.open("POST","ACCESSRIGHTS/DB_ACCESS_RIGHTS_USER_SEARCH_DETAIL.do",true);
            xmlhttp.send();
        }
        //FUNCTION FOR SORTING
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
        $(document).on('click','#URSRC_btn_pdf',function(){
            var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=1&title='+title;
        });
    });
    //DOCUMENT READY FUNCTION END
</script>
<!--SCRIPT TAG END-->
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
<div class="title text-center"><h4><b>USER SEARCH DETAILS</b></h4></div>
    <form class="content" name="USD_SRC_SRC_form_user" id="USD_SRC_SRC_form_user" autocomplete="off" >
        <div class="panel-body">
            <fieldset>
        <div><label id="URSRC_lbl_title" name="URSRC_lbl_title" class="srctitle"></label></div>
        <div><input type="button" id='URSRC_btn_pdf' class="btnpdf" value="PDF"></div>

            <div id="STDTL_SEARCH_div_flexdata_result" class="table-responsive" hidden>
                <section style="width:900px;">
                </section>
            </div>

        <div><label id="URSRC_lbl_norole_err" name="URSRC_lbl_norole_err" class="errormsg"></label></div>
                </fieldset>
            </div>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->