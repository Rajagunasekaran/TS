<!--*********************************************GLOBAL DECLARATION******************************************-->
<!--*********************************************************************************************************//-->
<!--//*******************************************FILE DESCRIPTION*********************************************//
//****************************************DOOR ACCESS DETAILS*************************************************//
//DONE BY:SARADAMBAL
//VER 0.04-SD:06/01/2015 ED:06/01/2015,TRACKER NO:74,IMPLEMENTED PRELOADER POSITION,CHANGED LOGIN ID INTO EMPLOYEE NAME
//DONE BY: RAJA
//VER 0.03-SD:31/12/2014 ED:31/12/2014, TRACKER NO:166, DESC:IMPLEMENTED PDF BUTTON AND VALIDATED AND GAVE INPUT TO DB
//VER 0.02-SD:05/12/2014 ED:05/12/2014,TRACKER NO:74,IMPLEMENTED HEADER NAME FOR PDF AND DATA TABLE
//DONE BY:LALITHA
//VER 0.01-INITIAL VERSION,SD:04/11/2014 ED:04/11/2014,TRACKER NO:97
//*********************************************************************************************************//
<?PHP
include "../../TSLIB/TSLIB_HEADER.php";
//include  "NEW_MENU.php";
?>
<!--HTML TAG START-->
<html>
<head>
<!--SCRIPT TAG START-->
<script>
    //DOCUMENT READY FUNCTION START
    $(document).ready(function(){
        var errmsg;
        $('#DR_ACC_btn_pdf').hide();
//        $('.preloader', window.parent.document).show()
        $(".preloader").show();
        var values_arraystotal=[];
        var values_array=[];
        table();
        //FUNCTION FOR SHOWING DATA TABLE OF DOOR ACCESS RECORDS
        function table(){
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                    $(".preloader").hide();
                    values_arraystotal=JSON.parse(xmlhttp.responseText);
                    values_array=values_arraystotal[0];
                    var DR_ACC_errorAarray=values_arraystotal[1];
                    if(values_array.length!=0)
                    {
                        errmsg=values_arraystotal[1][1];
                        $('#src_lbl_error').text(errmsg).show();
                        $('#DR_ACC_btn_pdf').show();
                        var DR_ACC_table_header='<table id="DR_ACC_tble_htmltable" border="1"  cellspacing="0" class="srcresult"><thead  bgcolor="#6495ed" style="color:white"><tr><th>EMPLOYEE NAME</th><th>DOOR ACCESS</th></tr></thead><tbody>'
                        for(var j=0;j<values_array.length;j++){
                            var loginid=values_array[j].loginid;
                            var DR_ACC_draccess=values_array[j].DR_ACC_draccess;
                            if((DR_ACC_draccess=='null')||(DR_ACC_draccess==undefined))
                            {
                                DR_ACC_draccess='';
                            }
                            DR_ACC_table_header+='<tr><td>'+loginid+'</td><td>'+DR_ACC_draccess+'</td></tr>';
                        }
                        DR_ACC_table_header+='</tbody></table>';
                        $('section').html(DR_ACC_table_header);
                        $('#DR_ACC_tble_htmltable').DataTable( {
                            "aaSorting": [],
                            "pageLength": 10,
                            "sPaginationType":"full_numbers"
                        });
                    }
                    else
                    {
                        $('#DR_ACC_lbl_norole_err').text(DR_ACC_errorAarray).show();
                        $('#DR_ACC_btn_pdf').hide();
                    }
                }
            }
            $('#tablecontainer').show();
            xmlhttp.open("POST","ADMIN/EMPLOYEE/DB_REPORT_DOOR_ACCESS.do",true);
            xmlhttp.send();
        }
        //CLICK EVENT FOR PDF BUTTON
        $(document).on('click','#DR_ACC_btn_pdf',function(){
            var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=4&title='+errmsg;

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
    <div class="title text-center"><b><h4>DOOR ACCESS DETAILS</h4></b></div>
    <form class="content" name="DR_ACC_form_user" id="DR_ACC_form_user" autocomplete="off" >
        <div class="panel-body">
            <fieldset>
        <div class="table-responsive">
            <div>
                <label id="src_lbl_error" class="srctitle"></label><br><br>
                <input type="button" id="DR_ACC_btn_pdf" class="btnpdf" value="PDF" hidden>
            </div>
            <div  id="tablecontainer"  hidden>
                <section style="max-width:500px;">
                </section>
            </div>
        </div>
        <div>
            <label id="DR_ACC_lbl_norole_err" name="DR_ACC_lbl_norole_err" class="errormsg"></label>
        </div>
       </fieldset>
            </div>
</form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->