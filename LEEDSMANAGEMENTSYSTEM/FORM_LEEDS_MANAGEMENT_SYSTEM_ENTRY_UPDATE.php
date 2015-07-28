<?php
include '../TSLIB/TSLIB_HEADER.php';
?>
<html>
<head>
    <style>
        .form-group {
            margin-bottom: 0 !important;
        }
    </style>
    <script>
        //ready function
        $(document).ready(function(){
            $('.preloader').hide();
            var errmsg;
            var msg;
            var formElement = document.getElementById("CCD_form_entry");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var value_array=JSON.parse(xmlhttp.responseText);
                    var errmsg=value_array[0];
                    var msg=value_array[1];
                }
            }
            var option="error"
            xmlhttp.open("POST","LEEDSMANAGEMENTSYSTEM/DB_LEEDS_MANAGEMENT_SYSTEM_ENTRY_UPDATE.do?option="+option);
            xmlhttp.send(new FormData(formElement));
            $(document).on('click','#CC_details',function(){
                $('#entrysearch').show();
                $('#CC_entry').attr('checked',false);
                $('#CC_search').attr('checked',false);
            });
            $(document).on('click','#CC_entry',function(){
                $('#entry').show();
            });
            $(document).on('click','#MD_details',function(){
                $('#entrysearch').hide();
            });

            $('.datepickers').datepicker ({
                dateFormat:"dd-mm-yy",
                changeYear: true,
                changeMonth: true

            });

//    validation
            $("#CC_tb_client_contact").doValidation({rule:'numbersonly',prop:{realpart:10,leadzero:true}});
            $('#CC_tb_client_mail').doValidation({rule:'general',prop:{uppercase:false,autosize:true}});
            $("#CC_tb_client_name").doValidation({rule:'alphabets',prop:{whitespace:true,autosize:true}});
            $("#CC_tb_project_name").doValidation({rule:'alphabets',prop:{whitespace:true,autosize:true}});
            $(document).on('click','#CC_entry_save',function(){

                var formElement = document.getElementById("CCD_form_entry");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();

                        var value_array=JSON.parse(xmlhttp.responseText);
                        if(value_array==1)
                        {

                            show_msgbox("FORM LEEDS MANAGEMENT SYSTEM ENTRY UPDATE",errmsg,"success",true);
                        }
                        else if(value_array==0)
                        {

                            show_msgbox("FORM LEEDS MANAGEMENT SYSTEM ENTRY UPDATE",msg,"success",true);
                        }
                    }
                }
                var option="SAVE"
                xmlhttp.open("POST","LEEDSMANAGEMENTSYSTEM/DB_LEEDS_MANAGEMENT_SYSTEM_ENTRY_UPDATE.do?option="+option);
                xmlhttp.send(new FormData(formElement));

            });


        });

    </script>
</head>
<body>
<div class="container-fluid">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/></div>
    <div class="title text-center"><h4><b>LEADS MANAGEMENT SYSTEM ENTRY/ UPDATE</b></h4></div>
    <form id="CCD_form_entry" class="form-horizontal content" method="post" >
        <div class="panel-body">
            <div>
                <div class="radio">
                    <label><input type="radio" name="contact" class="contact" id="CC_details" value="contact">CLIENT CONTACT DETAILS</label>
                </div>
                <div id="entrysearch" hidden>
                    <div class="radio">
                        <label><input type="radio" class="CC_entry" id="CC_entry" value="entry"> ENTRY</label>
                    </div>
                    <div class="radio" >
                        <label><input type="radio" class="CC_entry" id="CC_search" value="search/update">SEARCH/UPDATE</label>
                    </div>
                </div>
                <div class="radio" >
                    <label><input type="radio" name="contact" class="contact" id="MD_details" value="marketing">MARKETING DETAILS</label>
                </div>
            </div>
            <div id="entry" hidden>
                <div class="form-group">
                    <label name="CC_lbl_date" id="CC_lbl_dates" class="col-sm-2">DATE</label>
                    <div class="col-sm-3">
                        <input type="text" id="CC_tb_date" name="CC_tb_dates" class="form-control datepickers" style="width: 100px">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2" id="CC_lbl_client_name" name="CC_lbl_name">CLIENT NAME</label>
                    <div class="col-sm-4">
                        <input type="text" id="CC_tb_client_name" name="CC_tb_name" class="form-control">
                    </div></div>
                <div class="form-group">
                    <label class="col-sm-2" id="CC_lbl_client_mail" name="CC_lbl_mail">MAIL ID</label>
                    <div class="col-sm-4">
                        <input type="text" id="CC_tb_client_mail" name="CC_tb_mail" class="form-control">
                    </div></div>
                <div class="form-group">
                    <label class="col-sm-2" id="CC_lbl_client_contact" name="CC_lbl_contact">CONTACT NO</label>
                    <div class="col-sm-4">
                        <input type="text" id="CC_tb_client_contact" name="CC_tb_contact" class="form-control">
                    </div></div>
                <div class="form-group">
                    <label class="col-sm-2" id="CC_lbl_client_address" name="CC_lbl_address">ADDRESS</label>
                    <div class="col-sm-4">
                        <textarea id="CC_tb_client_address"  rows="4" cols="50" name="CC_tb_address" class="form-control"></textarea>
                    </div></div>
                <div class="form-group">
                    <label class="col-sm-2" id="CC_lbl_project_name" name="CC_lbl_pname">PROJECT NAME</label>
                    <div class="col-sm-4">
                        <input type="text" id="CC_tb_project_name" name="CC_tb_pname" class="form-control">
                    </div></div>
                <div class="form-group">
                    <label class="col-sm-2" id="CC_lbl_contact_time" name="CC_lbl_ctime">CONTACT TIME</label>
                    <div class="col-sm-2">
                        <input type="text" id="CC_tb_contact_time" name="CC_tb_ctime" class="form-control" value="<?php echo gmdate("H:i:s", time()); ?>">
                    </div></div>
                <div class="form-group">
                    <label class="col-sm-2" id="CC_lbl_client_comment" name="CC_lbl_cname">COMMENTS</label>
                    <div class="col-sm-4">
                        <textarea id="CC_tb_client_comment" rows="4" cols="50" name="CC_tb_cname" class="form-control tarea"></textarea>
                    </div></div>
                <div class="form-group">
                    <label class="col-sm-2" id="CC_lbl_checked_by" name="CC_lbl_checked">CHECKED BY</label>
                    <div class="col-sm-4">
                        <input type="text" id="CC_tb_checked_by" name="CC_tb_checked" class="form-control ">
                    </div></div>
                <div>
                    <input type="button" class="btn" name="CC_save" id="CC_entry_save" VALUE="SAVE"><input type="button" class="btn" name="CC_reset" id="CC_entry_reset" VALUE="RESET">
                </div>
            </div>
            <div id="marketing" hidden>
                <div class="form-group">
                    <label class="col-sm-2" id="MM_lbl_client_name" name="MM_lbl_name">CLIENT NAME</label>
                    <div class="col-sm-4">
                        <input type="text" id="MM_tb_client_name" name="MM_tb_name" class="form-control">
                    </div></div>
                <div class="form-group">
                    <label class="col-sm-2" id="MM_lbl_site_used" name="MM_lbl_site">SITE USED</label>
                    <div class="col-sm-4">
                        <input type="text" id="MM_tb_site_used" name="MM_tb_site" class="form-control">
                    </div></div>
                <div class="form-group">
                    <label class="col-sm-2" id="MM_lbl_prj_details" name="MM_lbl_prjname">PROJECT DETAILS</label>
                    <div class="col-sm-4">
                        <input type="text" id="MM_tb_prj_details" name="MM_tb_prjname" class="form-control">
                    </div></div>
            </div>
        </div>
    </form>
</div>
</body>
</html>