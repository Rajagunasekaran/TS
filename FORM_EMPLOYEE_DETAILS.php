
<!--//*******************************************FILE DESCRIPTION*********************************************//
//**********************************************EMPLOYEE DETAILS*******************************************************//
//DONE BY:RENUKADEVI
//INITIAL VERSION
//************************************************************************************************************-->
<?php
include "HEADER.php";
//include  "NEW_MENU.php";
?>
<!--HTML TAG START-->

<!--HEAD TAG START-->
<script>
    //DOCUMENT READY FUNCTION START
    $(document).ready(function(){
        $('#ED_btn_pdf').hide();
//        $('.preloader', window.parent.document).show();
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
                    var ED_errorAarray=values_arraystotal[1];
                    if(values_array.length!=0)
                    {
                        title=ED_errorAarray[1].toString().replace("PROJECT","EMPLOYEE");
                        $('#ED_lbl_title').text(title).show();
                        $('#ED_btn_pdf').show();

                        var ED_table_header='<table id="ED_tble_htmltable" border="1"  cellspacing="0" class="srcresult" style="width:3200px" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>EMPLOYEE NAME</th><th style="width:80px;"  class="uk-date-column" nowrap>DATE OF BIRTH</th><th>DESIGNATION</th><th>MOBILE NO</th><th>NEXT KIN NAME</th><th>RELATION HOOD</th><th>ALT MOBILE NO</th><th>BANK NAME</th><th>BRANCH NAME</th><th>ACCOUNT NAME</th><th>ACCOUNT NO</th><th>IFSC CODE</th><th>ACCOUNT TYPE</th><th>BRANCH ADDRESS</th><th>AADHAAR NO</th><th>PASSPORT NO</th><th>VOTER ID</th><th>COMMENTS</th><th>LAPTOP NO</th><th>CHARGER NO</th><th>LAPTOP BAG</th><th>MOUSE</th><th>DOOR ACCESS</th><th>ID CARD</th><th>HEAD SET</th><th>USERSTAMP</th><th style="width:150px;" class="uk-timestp-column">TIMESTAMP</th></tr></thead><tbody>'
                        for(var j=0;j<values_array.length;j++){
                            var AE_empname=values_array[j].empname;
                            var ED_empdob=values_array[j].empdob;
                            var ED_desgn=values_array[j].desgn;
                            var ED_empmblno=values_array[j].empmblno;
                            var ED_empnkn=values_array[j].empnkn;
                            var ED_relation=values_array[j].relation;
                            var ED_empaltmbl=values_array[j].empaltmbl;

                            var ED_empbank=values_array[j].empbank;
                            var ED_empbranch=values_array[j].empbranch;
                            var ED_empaccntname=values_array[j].empaccntname;
                            var ED_empaccntno=values_array[j].ampaccntno;
                            var ED_empifsc=values_array[j].ampifsc;
                            var ED_empaccnttype=values_array[j].empaccnttype;
                            var ED_empbranchaddrs=values_array[j].empbranchaddrs;
                            var ED_empaadhaar=values_array[j].empaadhaar;
                            if((ED_empaadhaar=='null')||(ED_empaadhaar==undefined))
                            {
                                ED_empaadhaar='';
                            }
                            var ED_emppassport=values_array[j].emppassport;
                            if((ED_emppassport=='null')||(ED_emppassport==undefined))
                            {
                                ED_emppassport='';
                            }
                            var ED_empvoterid=values_array[j].empvoterid;
                            if((ED_empvoterid=='null')||(ED_empvoterid==undefined))
                            {
                                ED_empvoterid='';
                            }
                            var ED_empcomments=values_array[j].empcomments;
                            if((ED_empcomments=='null')||(ED_empcomments==undefined))
                            {
                                ED_empcomments='';
                            }
                            var CPD_laptopno=values_array[j].laptopno;
                            if((CPD_laptopno=='null')||(CPD_laptopno==undefined))
                            {
                                CPD_laptopno='';
                            }
                            var CPD_chargerno=values_array[j].chargerno;
                            if((CPD_chargerno=='null')||(CPD_chargerno==undefined))
                            {
                                CPD_chargerno='';
                            }
                            var CPD_laptopbag=values_array[j].laptopbag;
                            if((CPD_laptopbag=='null')||(CPD_laptopbag==undefined))
                            {
                                CPD_laptopbag='';
                            }
                            var CPD_mouse=values_array[j].mouse;
                            if((CPD_mouse=='null')||(CPD_mouse==undefined))
                            {
                                CPD_mouse='';
                            }
                            var CPD_dooraccess=values_array[j].dooraccess;
                            if((CPD_dooraccess=='null')||(CPD_dooraccess==undefined))
                            {
                                CPD_dooraccess='';
                            }
                            var CPD_idcard=values_array[j].idcard;
                            if((CPD_idcard=='null')||(CPD_idcard==undefined))
                            {
                                CPD_idcard='';
                            }
                            var CPD_headset=values_array[j].headset;
                            if((CPD_headset=='null')||(CPD_headset==undefined))
                            {
                                CPD_headset='';
                            }

                            var ED_empuserstamp=values_array[j].empuserstamp;
                            var ED_emptimestamp=values_array[j].emptimestamp;

                            ED_table_header+='<tr><td nowrap align="center">'+AE_empname+'</td>' + '<td align="center" style="width:80px; !important; nowrap">'+ED_empdob+'</td><td align="center">'+ ED_desgn+'</td>' + '<td align="center">'+ED_empmblno+'</td><td align="center">'+ED_empnkn+'</td>' + '<td align="center">'+ED_relation+'</td><td align="center">'+ED_empaltmbl+'</td>' + '<td align="center">'+ED_empbank+'</td><td align="center">'+ED_empbranch+'</td>' + '<td align="center">'+ED_empaccntname+'</td><td align="center">'+ED_empaccntno+'</td>' + '<td align="center">'+ED_empifsc+'</td><td align="center">'+ED_empaccnttype+'</td>' + '<td align="center">'+ED_empbranchaddrs+'</td><td align="center">'+ED_empaadhaar+'</td>' + '<td align="center">'+ED_emppassport+'</td><td align="center">'+ED_empvoterid+'</td>' + '<td align="center">'+ED_empcomments+'</td><td align="center">'+CPD_laptopno+'</td>' + '<td STYLE="width: 10PX" align="center">'+CPD_chargerno+'</td><td align="center">'+CPD_laptopbag+'</td>' +  '<td align="center">'+CPD_mouse+'</td><td align="center">'+CPD_dooraccess+'</td>' + '<td align="center">'+CPD_idcard+'</td><td align="center">'+CPD_headset+'</td>'+'<td align="center">'+ED_empuserstamp+'</td><td  nowrap align="center">'+ED_emptimestamp+'</td></tr>';
                        }
                        ED_table_header+='</tbody></table>';

                        $('section').html(ED_table_header);
                        $('#ED_tble_htmltable').DataTable( {
                            "aaSorting": [],
                            "pageLength": 10,
                            "container":"box",
                            "width":100,
                            "sPaginationType":"full_numbers",
                            "aoColumnDefs" : [
                                { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"}, { "aTargets" : ["uk-timestp-column"] , "sType" : "uk_timestp"} ]
                        });
                    }
                    else
                    {
                        ('#ED_lbl_norole_err').text(ED_errorAarray[0]).show();
                        $('#ED_lbl_title').hide();
                        $('#ED_btn_pdf').hide();
                    }
                }
            }
            $('#tablecontainer').show();
            xmlhttp.open("POST","DB_EMPLOYEE_DETAILS.do",true);
            xmlhttp.send();
        }

//    //FUNCTION FOR SORTING

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

        $(document).on('click','#ED_btn_pdf',function(){
            var url=document.location.href='COMMON_PDF.do?flag=25&title='+title;
        });
    });
    //DOCUMENT READY FUNCTION END
</script>
<!--SCRIPT TAG END-->
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body class="dt-example">
<div class="container">
    <div class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"/></div></div></div>
    <div class="newtitle1" id="fhead"><center><p><b><h3>EMPLOYEE DETAILS</h3></b><p></center></div>

    <form class="newcontent1" name="ED_form_user" id="ED_form_user" autocomplete="off" >
        <div class="panel-body">
            <fieldset>
        <div><label id="ED_lbl_title" name="ED_lbl_title" class="srctitle"></label></div>

        <div><input type="button" id='ED_btn_pdf' class="btnpdf" value="PDF"></div>


            <div class="table-responsive" id="tablecontainer" hidden>
                <section>
                </section>
            </div>

        <div><label id="ED_lbl_norole_err" name="ED_lbl_norole_err" class="errormsg"></label></div>
                </fieldset>
            </div>
    </form>

</div>

</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->
