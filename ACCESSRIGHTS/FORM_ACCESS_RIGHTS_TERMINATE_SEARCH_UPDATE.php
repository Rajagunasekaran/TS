<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************ACCESS_RIGHTS_TERMINATE_SEARCH_UPDATE*********************************************//
//DONE BY:PUNI
//ver 0.08 DESC:CHANGED SCRIPT TO UPLOAD FILE IN DRIVE -REJOIN FORM-CHANGED SUBMIT BUTTON VALIDATION,SHOW ERR WITH DUPLICATE FILE NAME BY USER,MAX 4 FILES,FILE SIZE 10 MB,CREATED FOLDER IN DRIVE IF NOT EXISTS ELSE SHOW ERROR MSG
//DONE BY:LALITHA
//ver 0.06 SD:08/01/2015 ED:10/01/2015 tracker no :74,Merged Employee details in design part nd mail part also,Added Attach File Html Code nd file Upload Script,Changed validation,Replaced Login id as Employee name,Updated auto focus
//ver 0.05 SD:30/12/2014 ED:31/12/2014 tracker no :74,Updated preloader nd message box position
done by :safi
//ver 0.04 SD:29/12/2014 ED:29/12/2014 tracker no :74,desc:updated to terminate login if selected day report entered also
//VER 0.03 SD:28/11/14 ED:1/12/2014 TRACKER NO:74 DESC:MERGED LOGIN CREATION/UPDATION AND EMPLOYEE CREATION FORM
//DONE BY:shalini
//VER 0.01-INITIAL VERSION, SD:20/08/2014 ED:11/09/2014,TRACKER NO:81
//*********************************************************************************************************//-->
<?php
include "../TSLIB/TSLIB_COMMON.php";
include "../TSLIB/TSLIB_HEADER.php";
//include  "NEW_MENU.php";
?>
<!--SCRIPT TAG START-->
<script>
    var  button_vflag=1;
    //START READY FUNCTION
    $(document).ready(function(){
//    $('.preloader', window.parent.document).show();
        $(".preloader").show();

        $('textarea').autogrow({onInitialize: true});
        //reomve file upload row
        $(document).on('click', 'button.removebutton', function () {
            $(this).closest('tr').remove();
            ValidateSubmitbutton()
            return false;
        });
//    $('.preloader', window.parent.document).hide();
        $(".preloader").hide();
        //file extension validation
        $(document).on("change",'.fileextensionchk', function (){
            ValidateSubmitbutton()
        });
        //file extension validation
        function ValidateSubmitbutton()
        {
            button_vflag=1;
            $("input[type=file].fileextensionchk").each(function(){
                var currentid=$(this).attr("id");
                if($(this).val()!="")
                {
                    var datasplit=$(this).val().split('.');
                    var ext=datasplit[1].toUpperCase();//(this.files[0].type).toString().toUpperCase();
                    if(ext!='PDF'&& ext!='JPG'&& ext!='PNG' && ext!='JPEG')
                    {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:js_errormsg_array[12],position:{top:100,left:100}}});
                        show_msgbox("REJOIN",js_errormsg_array[12],"success",false);
                        button_vflag=0;
                        $('#attachprompt').hide();
                    }
                    var filesize=$("#"+$(this).attr("id"))[0].files[0].size;//(this.files[0].size);
                    var maxfilesize=1048576*10;
                    if(filesize>maxfilesize)
                    {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:js_errormsg_array[15],position:{top:100,left:100}}});
                        show_msgbox("REJOIN",js_errormsg_array[15],"success",false);
                        button_vflag=0;
                        reset_field($('#'+$(this).attr("id")));
                        $('#attachprompt').hide();
                    }
                    var filename=[];
                    $("div.fileextensionchk").each(function(){
                        filename.push($(this).text());
                    });

                    $("input[type=file].fileextensionchk").each(function(){

                        if(currentid!=$(this).attr("id"))
                            if($(this).val()!="")
                            {
                                filename.push($("#"+$(this).attr("id"))[0].files[0].name);
                            }
                    });

                    if($.inArray( $("#"+$(this).attr("id"))[0].files[0].name, filename )!=-1)
                    {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:js_errormsg_array[14],position:{top:100,left:100}}});
                        show_msgbox("REJOIN",js_errormsg_array[14],"success",false);
                        button_vflag=0;
                        reset_field($('#'+$(this).attr("id")));
                        $('#attachprompt').hide();

                    }
                }
                else
                {
                    button_vflag=0;
                    $('#attachprompt').hide();
                }

            });
            URT_SRC_validation();
            var rowCount = $('#filetableuploads tr').length;
            if(rowCount!=0)
            {
                $('#attachafile').text('Attach another file');
            }
            else
            {
                $('#attachafile').text('Attach a file');
            }
            if(rowCount==4)
            {
                $('#attachprompt').hide();
            }
            else
            {
                if(button_vflag==1)
                    $('#attachprompt').show();
            }
        }
        //file upload reset
        function reset_field(e) {
            e.wrap('<form>').parent('form').trigger('reset');
            e.unwrap();
        }
        //add file upload row
        $(document).on("click",'#attachprompt', function (){
            button_vflag=0;
            var tablerowCount = $('#filetableuploads tr').length;
            var uploadfileid="upload_filename"+tablerowCount;
            var appendfile='<input type="file" name="UTERM_uploaded_files[]" class="fileextensionchk" id='+uploadfileid+'></td><td><button type="button" class="removebutton" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;">Remove</button></br>';
            $('#filetableuploads').append(appendfile);
            ValidateSubmitbutton();
        });

        //GLOBAL DECLARATION
        var URT_SRC_terminate_array=[];
        var js_errormsg_array=[];
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//            $('.preloader', window.parent.document).hide();
                $(".preloader").hide();
                var value_array=JSON.parse(xmlhttp.responseText);
                js_errormsg_array=value_array[0];
                URT_SRC_terminate_array=value_array[1];
                var URSRC_emptype_array=value_array[2];
                var URSRC_desg_array=value_array[3];
                var URSRC_rela_array=value_array[4];
                var URSRC_accty_array=value_array[5];
                var URSRC_lapty_array=value_array[6];
                var URT_SRC_radio_role='';
                for (var i=0;i<URT_SRC_terminate_array.length;i++) {
                    var id="URT_SRC_tble_table"+i;
                    var id1="URT_SRC_terminate_array"+i;
                    var value=URT_SRC_terminate_array[i][1].replace(" ","_")
                    if(i==0)
                        var temp='<lable>SELECT ROLE ACCESS<em>*</em></lable>';
                    else
                        var temp='';
                    URT_SRC_radio_role+='<div class="row-fluid form-group"><label id="URT_lbl_dbroles" class="col-sm-2">'+temp+'</label><div class="col-sm-2"><input type="radio" name="URT_SRC_radio_nrole" id='+id1+' value='+value+' class="URT_SRC_radio_clsrole"  />' + URT_SRC_terminate_array[i][1] + '</div></div>';
                }

                $('#URT_SRC_tble_roles').html(URT_SRC_radio_role);
                var emp_type='<option value="SELECT">SELECT</option>';

                for(var k=0;k<URSRC_emptype_array.length;k++){
                    emp_type += '<option value="' + URSRC_emptype_array[k] + '">' + URSRC_emptype_array[k] + '</option>';
                }
                $('#URSRC_lb_selectemptype').html(emp_type);
                var rdesgn_result='<option value="SELECT">SELECT</option>';
                for(var k=0;k<URSRC_desg_array.length;k++){
                    rdesgn_result += '<option value="' + URSRC_desg_array[k] + '">' + URSRC_desg_array[k] + '</option>';

                }
                $('#URSRC_tb_designation').html(rdesgn_result);
                var rname_result='<option value="SELECT">SELECT</option>';
                for(var k=0;k<URSRC_rela_array.length;k++){
                    rname_result += '<option value="' + URSRC_rela_array[k] + '">' + URSRC_rela_array[k] + '</option>';
                }
                $('#URSRC_tb_relationhd').html(rname_result);
                var acctype_result='<option value="SELECT">SELECT</option>';
                for(var k=0;k<URSRC_accty_array.length;k++){
                    acctype_result += '<option value="' + URSRC_accty_array[k] + '">' + URSRC_accty_array[k] + '</option>';
                }
                $('#URSRC_tb_accntyp').html(acctype_result);

                var laptype_result='<option value="SELECT">SELECT</option>';
                for(var k=0;k<URSRC_lapty_array.length;k++){
                    laptype_result += '<option value="' + URSRC_lapty_array[k] + '">' + URSRC_lapty_array[k] + '</option>';
                }
                $('#URSRC_tb_laptopno').html(laptype_result);


            }
        }
        var choice="USER_RIGHTS_TERMINATE";
        xmlhttp.open("GET","TSLIB/TSLIB_COMMON.do?option="+choice);
//    xmlhttp.open("POST","DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?option="+choice);
        xmlhttp.send();

        //DO VALIDATION PART
        //emp
        $(".mobileno").doValidation({rule:'numbersonly',prop:{realpart:10,leadzero:true}});
        $(".accntno").doValidation({rule:'numbersonly',prop:{leadzero:true}});
        $(".alphanumeric").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:false,autosize:true}});
        $(".alphanumericuppercse").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
        $(".autosizealph").doValidation({rule:'alphabets',prop:{whitespace:true,autosize:true}});
        //END VALIDATION PART
        //CLICK FUNCTION FOR TERMINATION BTN
        $(document).on('click','#URT_SRC_btn_termination',function(){

//        $('.preloader',window.parent.document).show();
            $(".preloader").show();
            var URT_SRC_empname=$("#URT_SRC_lb_loginterminate option:selected").text();
            var URT_loginid=$('#URT_SRC_lb_loginrejoin').val();
            var loggin=$("#URT_SRC_lb_loginterminate").val();
            var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader',window.parent.document).hide();
                    $(".preloader").hide();
                    var msg_alert=JSON.parse(xmlhttp.responseText);
                    var success_flag=msg_alert[0];
                    var ss_flag=msg_alert[1];
                    var cal_flag=msg_alert[2];
                    if((success_flag==1)&&(ss_flag==1)&&(cal_flag==1)){
                        var msg=js_errormsg_array[1].toString().replace("[LOGIN ID]",URT_SRC_empname);
//                    alert(msg);
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg,position:{top:150,left:550}}});
                        show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msg,"success",false);
                        $("#URT_SRC_lbl_datepickertermination").hide();
                        $("#URT_SRC_lb_loginterminate").hide();
                        $("#URT_SRC_lbl_loginterminate").hide();
                        $("#URT_SRC_tb_datepickertermination").hide();
                        $("#URT_SRC_lbl_reasontermination").hide();
                        $("#URT_SRC_ta_reasontermination").hide();
                        $("#URT_SRC_btn_termination").hide();
                        $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                        $("#filetableuploads tr").remove();
                        $('#attachafile').text('Attach a file');
                    }
                    if(success_flag==0){
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[6],position:{top:150,left:550}}});
//                    show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",js_errormsg_array[6],"success",false);
                        $("#URT_SRC_lbl_datepickertermination").hide();
                        $("#URT_SRC_lb_loginterminate").hide();
                        $("#URT_SRC_lbl_loginterminate").hide();
                        $("#URT_SRC_tb_datepickertermination").hide();
                        $("#URT_SRC_lbl_reasontermination").hide();
                        $("#URT_SRC_ta_reasontermination").hide();
                        $("#URT_SRC_btn_termination").hide();
                        $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                    }
                    if((success_flag==1)&&(ss_flag==0)){
                        var fileid=msg_alert[3];
                        var msg= js_errormsg_array[8].replace("[SSID]",fileid)
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg,position:{top:150,left:550}}});
                        show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msg,"success",false);
                    }
                    if((success_flag==1)&&(ss_flag==1)&&(cal_flag==0)){
                        var msg= js_errormsg_array[9];
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg,position:{top:150,left:550}}});
                        show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msg,"success",false);
                    }
                }
            }
            var choice='TERMINATE';
            xmlhttp.open("POST","ACCESSRIGHTS/DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?option="+choice+"&loggin="+loggin);
            xmlhttp.send(new FormData(formElement));
        });
        //SET DOB DATEPICKER
        var EMP_ENTRY_d = new Date();
        var EMP_ENTRY_year = EMP_ENTRY_d.getFullYear() - 18;
        EMP_ENTRY_d.setFullYear(EMP_ENTRY_year);
        //DATE PICKER
        $('#URSRC_tb_dob').datepicker(
            {
                dateFormat: 'dd-mm-yy',
                changeYear: true,
                changeMonth: true,
                yearRange: '1920:' + EMP_ENTRY_year + '',
                defaultDate: EMP_ENTRY_d
            });
        var pass_changedmonth=new Date(EMP_ENTRY_d.setFullYear(EMP_ENTRY_year));
        $('#URSRC_tb_dob').datepicker("option","maxDate",pass_changedmonth);
//END DATE PICKER FUNCTION
        //CLICK FUNCTION FOR REJOIN BTN
        $('form').submit(function (e){
            e.preventDefault();
            var URSRC_submitbtnvalue= $(this).find("input[type=submit]:focus").val();
//        $('.preloader',window.parent.document).show();
            $(".preloader").show();
            if(URSRC_submitbtnvalue=="REJOIN")
            {
                var login_id=$("#URT_SRC_lb_loginrejoin").val();
                var URT_loginid_val=$("#URT_SRC_lb_loginrejoin option:selected").text();
                var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
                $.ajax({
                    type: 'POST',
                    url: "ACCESSRIGHTS/DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do",
                    data: new FormData(this),
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    processData:false,
                    success: function(data)
                    {
//                    $('.preloader',window.parent.document).hide();
                        $(".preloader").hide();
                        if(data.match("Error:Folder id Not present"))
                        {
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:js_errormsg_array[13],position:{top:100,left:100}}});
                            show_msgbox("REJOIN",js_errormsg_array[13],"success",false);
                        }
                        else if(data.toLowerCase().match("error")){
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:data ,position:{top:100,left:100}}});
                            show_msgbox("REJOIN",data,"success",false);
                        }
                        var msg_alert=JSON.parse(data);
                        var success_flag=msg_alert[0];
                        var ss_flag=msg_alert[1];
                        var cal_flag=msg_alert[2];
                        var file_flag=msg_alert[4];
                        var folder_id=msg_alert[5];
                        if((success_flag==1)&&(ss_flag==1)&&(cal_flag==1)){
                            var loggin=$("#URT_SRC_lb_loginrejoin").val();
                            var msg=js_errormsg_array[2].toString().replace("[LOGIN ID]",URT_loginid_val);
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:msg,position:{top:100,left:100}}});
                            show_msgbox("REJOIN",msg,"success",false);
                            $("#URT_SRC_lbl_datepickerrejoin").hide();
                            $("#URT_SRC_lbl_loginrejoin").show();
                            $("#URT_SRC_tble_roles").hide();
                            $('#URSRC_table_employeetbl').hide();
                            $("#URT_SRC_lb_loginupdate").hide();
                            $("#URT_SRC_tb_datepickerrejoin").hide();
                            $("#URT_SRC_btn_rejoin").hide();
                            $('#URT_SRC_lbl_loginupdate').hide();
                            $('#URT_SRC_lbl_datepickerupdate').hide();
                            $('#URT_SRC_tb_datepickerupdate').hide();
                            $('#URT_SRC_lbl_reasonupdate').hide();
                            $('#URT_SRC_ta_reasonupdate').hide();
                            $('#URT_SRC_btn_update').hide();
                            $('#URT_SRC_radio_selectrejoin').hide();
                            $('#URT_SRC_radio_selectsearchupdate').hide()
                            $('#URT_SRC_lb_loginrejoin').hide();
                            $('#URT_SRC_lbl_loginrejoin').hide();
                            $('#URT_SRC_lbl_selectsearchupdate').hide();
                            $('#URT_SRC_lbl_selectrejoin').hide();
                            $('#URT_SRC_lbl_selectoption').hide();
                            $("#URSRC_lbl_emptype").hide();
                            $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
                            $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                            $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
                            $("#filetableuploads tr").remove();
                            $('#attachafile').text('Attach a file');
                        }
                        else if(success_flag==0){
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:js_errormsg_array[6],position:{top:100,left:100}}});
                            show_msgbox("REJOIN",js_errormsg_array[6],"success",false);
                            $("#URT_SRC_lbl_datepickerrejoin").hide();
                            $("#URT_SRC_lbl_loginrejoin").show();
                            $("#URT_SRC_tble_roles").hide();
                            $('#URSRC_table_employeetbl').hide();
                            $("#URT_SRC_lb_loginupdate").hide();
                            $("#URT_SRC_tb_datepickerrejoin").hide();
                            $("#URT_SRC_btn_rejoin").hide();
                            $('#URT_SRC_lbl_loginupdate').hide();
                            $('#URT_SRC_lbl_datepickerupdate').hide();
                            $('#URT_SRC_tb_datepickerupdate').hide();
                            $('#URT_SRC_lbl_reasonupdate').hide();
                            $('#URT_SRC_ta_reasonupdate').hide();
                            $('#URT_SRC_btn_update').hide();
                            $('#URT_SRC_radio_selectrejoin').hide();
                            $('#URT_SRC_radio_selectsearchupdate').hide()
                            $('#URT_SRC_lb_loginrejoin').hide();
                            $('#URT_SRC_lbl_loginrejoin').hide();
                            $('#URT_SRC_lbl_selectsearchupdate').hide();
                            $('#URT_SRC_lbl_selectrejoin').hide();
                            $('#URT_SRC_lbl_selectoption').hide();
                            $("#URSRC_lbl_emptype").hide();
                            $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
                            $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                            $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
                        }
                        else if((success_flag==1)&&(ss_flag==0)){
                            var fileid=msg_alert[3];
                            var msg= js_errormsg_array[8].replace("[SSID]",fileid)
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:msg,position:{top:100,left:100}}});
                            show_msgbox("REJOIN",msg,"success",false);
                        }
                        else if((success_flag==1)&&(ss_flag==1)&&(file_flag==0)){

                            var msg=js_errormsg_array[11].replace("[FID]",folder_id);
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:msg ,position:{top:100,left:100}}});
                            show_msgbox("REJOIN",msg,"success",false);
                        }
                        else if((success_flag==1)&&(ss_flag==1)&&(cal_flag==0)){
                            var msg= js_errormsg_array[9];
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:msg,position:{top:100,left:100}}});
                            show_msgbox("REJOIN",msg,"success",false);
                        }
                    },
                    error: function(data){
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:JSON.stringify(data),position:{top:150,left:550}}});
                        show_msgbox("REJOIN",JSON.stringify(data),"success",false);
                    }

                });
            }
        });
        $(document).on('click','#URT_SRC_btn_update',function(){
//        $('.preloader', window.parent.document).show();
            $(".preloader").show();
            var URT_SRC_loggin=$("#URT_SRC_lb_loginupdate").val();
            var URT_SRC_empname_upd=$("#URT_SRC_lb_loginupdate option:selected").text();
            var formElement = document.getElementById("URT_SRC_form_terminatesearchupdate");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader', window.parent.document).hide();
                    $(".preloader").hide();
//                    alert(xmlhttp.responseText)''
                    var msg_alert=xmlhttp.responseText;
                    if(msg_alert==1){
                        var loggin=$("#URT_SRC_lb_loginupdate").val();
                        var msg=js_errormsg_array[0].toString().replace("[LOGIN ID]",URT_SRC_empname_upd);
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:msg,position:{top:100,left:100}}});
                        show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msg,"success",false);
                    }
                    $('#URT_SRC_lbl_loginrejoin').hide();
                    $('#URT_SRC_lb_loginrejoin').hide();
                    $('#URT_SRC_lbl_loginterminate').hide();
                    $('#URT_SRC_lb_loginterminate').hide();
                    $("#URT_SRC_tble_roles").hide();
                    $('#URSRC_table_employeetbl').hide();
                    $("#URT_SRC_tb_datepickerrejoin").hide();
                    $("#URT_SRC_lbl_datepickerrejoin").hide();
                    $("#URT_SRC_btn_rejoin").hide();
                    $("#URT_SRC_lbl_loginupdate").show();
                    $('#URT_SRC_lbl_datepickerupdate').hide();
                    $('#URT_SRC_tb_datepickerupdate').hide();
                    $('#URT_SRC_lbl_reasonupdate').hide();
                    $('#URT_SRC_ta_reasonupdate').hide();
                    $('#URT_SRC_btn_update').hide();
                    $('#URT_SRC_lbl_loginupdate').hide();
                    $('#URT_SRC_lb_loginupdate').hide();
                    $('#URT_SRC_lbl_selectsearchupdate').hide();
                    $('#URT_SRC_lbl_selectrejoin').hide();
                    $('#URT_SRC_radio_selectsearchupdate').hide();
                    $('#URT_SRC_radio_selectrejoin').hide();
                    $('#URT_SRC_lbl_selectoption').hide();
                    $('#URT_SRC_lb_recordversion').hide();
                    $('#URT_SRC_lbl_recordversion').hide();
                    $("input[name=URT_SRC_radio_nterminndupdatesearch]:checked").attr('checked',false);
                }
            }
            var choice='UPDATE';
            xmlhttp.open("POST","ACCESSRIGHTS/DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?option="+choice+"&URT_SRC_loggin="+URT_SRC_loggin,true);
            xmlhttp.send(new FormData(formElement));
        });
        $('#URT_SRC_lb_loginupdate').change(function(){
            $('#URT_SRC_lb_recordversion').hide();
            $('#URT_SRC_lbl_recordversion').hide();
//        $('.preloader', window.parent.document).show();
            $(".preloader").show();
            var URT_SRC_loggin=$(this).val();
            var URT_SRC_empname_upd=$("#URT_SRC_lb_loginupdate option:selected").text();
            var recver_array=[];
            if(URT_SRC_empname_upd !="SELECT"){
                $('#URT_SRC_lb_recordversion').hide();
                $('#URT_SRC_lbl_recordversion').hide();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        var values_array=JSON.parse(xmlhttp.responseText);
                        var rec_ver='<option value="SELECT">SELECT</option>';
                        recver_array=(values_array.recver);
                        for(var k=0;k<recver_array.length;k++){
                            rec_ver += '<option value="' + recver_array[k] + '">' + recver_array[k] + '</option>';
                        }
                        $('#URT_SRC_lb_recordversion').html(rec_ver);
                        var recver=(values_array.recver).length;
                        if(recver==1){
                            var min_date=values_array.enddate;
                            var mindate=min_date.toString().split('-');
                            var month=mindate[1]-1;
                            var year=mindate[2];
                            var date=parseInt(mindate[0]);
                            var minimumdate = new Date(year,month,date);
                            $('#URT_SRC_tb_datepickerupdate').val(values_array.enddate);
                            $('#URT_SRC_ta_reasonupdate').val(values_array.reasonn);
                            $('#URT_SRC_lb_recordversion').val(values_array.recver);
                            $('#URT_SRC_tb_datepickerupdate').datepicker("option","minDate",minimumdate);
                            var mindate=min_date.toString().split('-');
                            var month=parseInt(mindate[1]-1)+1;//mindate[1]-1;
                            var year=mindate[2];
                            var date=parseInt(mindate[0])+1;
                            var minimumdate = new Date(year,month,date);
                            $('#URT_SRC_tb_datepickerupdate').datepicker("option","maxDate",new Date());
                        }
                        else{
                            $('#URT_SRC_tb_datepickerupdate').hide();
                            $('#URT_SRC_ta_reasonupdate').hide();
                            $('#URT_SRC_lbl_datepickerupdate').hide();
                            $('#URT_SRC_lbl_reasonupdate').hide();
                            $('#URT_SRC_btn_update').hide();
                            $('#URT_SRC_lb_recordversion').show();
                            $('#URT_SRC_lbl_recordversion').show();
                        }
                    }
                }
//            $('.preloader', window.parent.document).hide();
                $(".preloader").hide();
            }
            else
            {
                $('#URT_SRC_tb_datepickerupdate').hide();
                $('#URT_SRC_ta_reasonupdate').hide();
                $('#URT_SRC_lbl_datepickerupdate').hide();
                $('#URT_SRC_lbl_reasonupdate').hide();
                $('#URT_SRC_btn_update').hide();
                $('#URT_SRC_lb_recordversion').hide();
                $('#URT_SRC_lbl_recordversion').hide();
//            $('.preloader', window.parent.document).hide();
                $(".preloader").hide();
            }
            var option='FETCH';
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
            xmlhttp.send();
        });
        //CHANGE FUNCTION FOR RECORD VERSION
        $('#URT_SRC_lb_recordversion').change(function(){
            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
            var recver= $('#URT_SRC_lb_recordversion').val();
            var URT_SRC_loggin=$('#URT_SRC_lb_loginupdate').val();
            if(recver!='SELECT'){
//            $('.preloader', window.parent.document).show();
                $(".preloader").show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        var values_array=JSON.parse(xmlhttp.responseText);
                        var min_date=values_array.enddate;
                        var mindate=min_date.toString().split('-');
                        var month=mindate[1]-1;
                        var year=mindate[2];
                        var date=parseInt(mindate[0]);
                        var minimumdate = new Date(year,month,date);
                        $('#URT_SRC_tb_datepickerupdate').val(values_array.enddate);
                        $('#URT_SRC_ta_reasonupdate').val(values_array.reasonn);
                        $('#URT_SRC_tb_datepickerupdate').datepicker("option","minDate",minimumdate);
                        $('#URT_SRC_tb_datepickerupdate').datepicker("option","maxDate",new Date());
                        $('#URT_SRC_tb_datepickerupdate').show();
                        $('#URT_SRC_ta_reasonupdate').show();
                        $('#URT_SRC_lbl_datepickerupdate').show();
                        $('#URT_SRC_lbl_reasonupdate').show();
                        $('#URT_SRC_btn_update').show();
                        $('#URT_SRC_lb_recordversion').show();
                        $('#URT_SRC_lbl_recordversion').show();
                    }
                }
                var option='FETCH DATA';
                xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option+"&recver="+recver,true);
                xmlhttp.send();
            }
            else{
                $('#URT_SRC_tb_datepickerupdate').hide();
                $('#URT_SRC_ta_reasonupdate').hide();
                $('#URT_SRC_lbl_datepickerupdate').hide();
                $('#URT_SRC_lbl_reasonupdate').hide();
                $('#URT_SRC_btn_update').hide();
            }
        });
        //CHANGE FUNCTION FOR LOGIN TERMINATE FORM
        $('#URT_SRC_lb_loginterminate').change(function(){
            $('#URT_SRC_errdate').hide();
//        $('.preloader', window.parent.document).show();
            $(".preloader").show();
            var URT_SRC_loggin=$(this).val();
            if(URT_SRC_loggin !=""){
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        var min_date=(xmlhttp.responseText);
                        var mindate=min_date.toString().split('-');
                        var month=mindate[1]-1;
                        var year=mindate[2];
                        var date=parseInt(mindate[0])+1;
                        var minimumdate = new Date(year,month,date);
                        $('#URT_SRC_tb_datepickertermination').datepicker("option","minDate",minimumdate);
                        $(".URT_SRC_tb_termindatepickerclass").datepicker("option","maxDate",new Date())
                    }
                }
            }
            var option='GETDATE';
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
            xmlhttp.send();
        });
        var err_flag=0;
        $('#URT_SRC_tb_datepickertermination').change(function(){
//        $('.preloader', window.parent.document).show();
            $(".preloader").show();
            var URT_SRC_loggin=$('#URT_SRC_lb_loginterminate').val();
            var date_value=$('#URT_SRC_tb_datepickertermination').val();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader', window.parent.document).hide();
                    $(".preloader").hide();
                    var final_value=xmlhttp.responseText;
                    if(final_value!=''){
                        err_flag=1;
                        var URT_loginid_val=$("#URT_SRC_lb_loginterminate option:selected").text();

                        var msg=js_errormsg_array[10].replace('[LOGIN ID]',URT_loginid_val);
                        msg=msg.replace('[DATE]',final_value);
                        $('#URT_SRC_errdate').text(msg).show();
                        $('#URT_SRC_btn_termination').attr('disabled','disabled');
                    }
                    else{
                        err_flag=0;
                        $('#URT_SRC_errdate').hide();
                        URT_SRC_validation();
                    }
                }
            }
            var option='GET_VALUE';
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option+"&date_value="+date_value,true);
            xmlhttp.send();
        });

        function list()
        {
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//            $('.preloader', window.parent.document).hide();
                    $(".preloader").hide();
//                alert(xmlhttp.responseText);
                    var listbox_value=JSON.parse(xmlhttp.responseText);

                    var desgn=listbox_value[0];
                    var relation=listbox_value[1];
                    var laptop = listbox_value[2];
                    var acc_type=listbox_value[3];
//                if(desgn.length!=0)
//                {
//                    var des='<option value="SELECT">SELECT</option>'
//                    for(var l=0;l<desgn.length;l++){
//                        des+= '<option value="' + desgn[l] + '">' + desgn[l]+ '</option>';
//                    }
//                    $('#URSRC_tb_designation').html(des)
//                }
//                if(relation.length!=0)
//                {
//                    var rel='<option value="SELECT">SELECT</option>'
//                    for(var l=0;l<relation.length;l++){
//                        rel+= '<option value="' + relation[l] + '">' + relation[l]+ '</option>';
//                    }
//                    $('#URSRC_tb_relationhd').html(rel)
//                }
//                if(laptop.length!=0)
//                {
//                    var ltp='<option value="SELECT">SELECT</option>'
//                    for(var l=0;l<laptop.length;l++){
//                        ltp+= '<option value="' + laptop[l]+ '">' + laptop[l]+ '</option>';
//                    }
//                    $('#URSRC_tb_laptopno').html(ltp)
//                }
//                if(acc_type.length!=0)
//                {
//                    var acc='<option value="SELECT">SELECT</option>'
//                    for(var l=0;l<acc_type.length;l++){
//                        acc+= '<option value="' + acc_type[l] + '">' + acc_type[l]+ '</option>';
//                    }
//                    $('#URSRC_tb_accntyp').html(acc)
//                }
                }
            }
            var option='LISTBOX';
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?&option="+option,true);
            xmlhttp.send();
        }

        //CHANGE FUNCTION FOR REJOIN LOGIN ID LISTBOX
        $('#URT_SRC_lb_loginrejoin').change(function(){

            $("#filetableuploads tr").remove();
            $('#attachprompt').show();
            var URT_loginid_val=$("#URT_SRC_lb_loginrejoin option:selected").text();
            ///
            $('#URSRC_lbl_login_role').hide();
            $('#URT_SRC_lbl_datepickerrejoin').hide();
            $('#URT_SRC_tb_datepickerrejoin').hide();
            $('#URSRC_lbl_emptype').hide();
            $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
            $('#URSRC_table_employeetbl').hide();
            $('#URSRC_table_others').hide();
            $('#URT_lbl_dbroles').hide();
            $('#URT_SRC_tble_roles').hide();
            $('#URT_SRC_btn_rejoin').hide();
            ///
            if(URT_loginid_val=='SELECT')
            {
                $("#URT_SRC_tble_roles").hide();
                $('#URSRC_table_employeetbl').hide();
                $("#URT_SRC_tb_datepickerrejoin").hide();
                $("#URT_SRC_btn_rejoin").hide();
                $("#URT_SRC_lbl_datepickerrejoin").hide();
                $("#URSRC_lbl_emptype").hide();
                $('#URSRC_lb_selectemptype').hide();
                $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
            }
            else
            {
//            $('.preloader', window.parent.document).show();
                $(".preloader").show();
                list();
                var URT_SRC_loggin=$(this).val();
                if(URT_loginid_val !=""){
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
//                        $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
                            if(xmlhttp.responseText.match("Error:Folder id Not present")){
//                            $(document).doValidation({rule:'messagebox',prop:{msgtitle:"REJOIN",msgcontent:js_errormsg_array[13] ,position:{top:100,left:100}}});
                                show_msgbox("REJOIN",js_errormsg_array[13],"success",false);
                            }
                            else{
                                $('#URSRC_lbl_login_role').show();
                                $('#URT_SRC_lbl_datepickerrejoin').show();
                                $('#URT_SRC_tb_datepickerrejoin').show();
                                $('#URSRC_lbl_emptype').show();
                                $('#URSRC_lb_selectemptype').show();
                                $('#URSRC_table_employeetbl').show();
                                $('#URSRC_table_others').show();
                                $('#URT_lbl_dbroles').show();
                                $('#URT_SRC_tble_roles').show();
                                $("#URT_SRC_tble_roles").show();
                                $('#URSRC_table_employeetbl').show();
                                $("#URT_SRC_tb_datepickerrejoin").val('').show();
                                $("#URT_SRC_lbl_datepickerrejoin").show();
                                $("#URT_SRC_btn_rejoin").show();
                                $("#URSRC_lbl_emptype").show();
                                $('#URSRC_lb_selectemptype').show();
                                $('#URSRC_tb_firstname').val('');
                                $('#URSRC_tb_lastname').val('');
                                $('#URSRC_tb_dob').val('');
                                $('#URSRC_tb_designation').val('');
                                $('#URSRC_tb_permobile').val('');
                                $('#URSRC_tb_kinname').val('');
                                $('#URSRC_tb_relationhd').val('');
                                $('#URSRC_tb_mobile').val('');
                                $('#URSRC_tb_bnkname').val('');
                                $('#URSRC_tb_brnchname').val('');
                                $('#URSRC_tb_accntname').val('');
                                $('#URSRC_tb_accntno').val('');
                                $('#URSRC_tb_ifsccode').val('');
                                $('#URSRC_tb_accntyp').val('');
                                $('#URSRC_ta_brnchaddr').val('');
                                $('#URSRC_tb_laptopno').val('');
                                $('#URSRC_tb_chargerno').val('');
                                $('#URSRC_ta_comments').val('');
                                $('#URSRC_tb_aadharno').val('').hide();
                                $('#URSRC_tb_passportno').val('').hide();
                                $('#URSRC_tb_votersid').val('').hide();
                                $("input[name=URSRC_chk_bag]:checked").attr('checked',false);
                                $("input[name=URSRC_chk_mouse]:checked").attr('checked',false);
                                $("input[name=URSRC_chk_dracess]:checked").attr('checked',false);
                                $("input[name=URSRC_chk_idcrd]:checked").attr('checked',false);
                                $("input[name=URSRC_chk_headset]:checked").attr('checked',false);
                                $("input[name=URSRC_chk_aadharno]:checked").attr('checked',false);
                                $("input[name=URSRC_chk_passportno]:checked").attr('checked',false);
                                $("input[name=URSRC_chk_votersid]:checked").attr('checked',false);
                                $('#URSRC_lbl_validnumber').hide();
                                $('#URSRC_lbl_validnumber1').hide();
                                $("input[name=URT_SRC_radio_nrole]:checked").attr('checked',false);
                                var values_array=JSON.parse(xmlhttp.responseText);
                                var min_date=values_array[0][1];
                                var firstname=values_array[0][0].firstname;
                                var lastname=values_array[0][0].lastname;
                                var dob=values_array[0][0].dob;
                                var designation=values_array[0][0].designation;
                                var mobile=values_array[0][0].mobile;
                                var kinname=values_array[0][0].kinname;
                                var relationhood=values_array[0][0].relationhood;
                                var altmobile=values_array[0][0].altmobile;
                                var laptop=values_array[0][0].laptop;
                                var chargerno=values_array[0][0].chargerno;
                                var bag=values_array[0][0].bag;
                                var mouse=values_array[0][0].mouse;
                                var dooraccess=values_array[0][0].dooraccess;
                                var idcard=values_array[0][0].idcard;
                                var headset=values_array[0][0].headset;
                                var bankname=values_array[0][0].bankname;
                                var branchname=values_array[0][0].branchname;
                                var accountname=values_array[0][0].accountname;
                                var accountno=values_array[0][0].accountno;
                                var ifsccode=values_array[0][0].ifsccode;
                                var accountype=values_array[0][0].accountype;
                                var branchaddr=values_array[0][0].branchaddress;
                                var aadharno=values_array[0][0].URSRC_aadhar;
                                var passportno=values_array[0][0].URSRC_passport;
                                var votersid=values_array[0][0].URSRC_voterid;
                                var comments=values_array[0][0].URSRC_comments;
                                var emp_role=values_array[0][0].URSRC_role;
                                var house=values_array[0][0].URSRC_house;
                                var street=values_array[0][0].URSRC_street;
                                var area=values_array[0][0].URSRC_area;
                                var postal_code=values_array[0][0].URSRC_postal;
                                var mindate=min_date.toString().split('-');
                                var month=mindate[1]-1;
                                var year=mindate[2];
                                var date=parseInt(mindate[0])+1;
                                var minimumdate = new Date(year,month,date);
                                //load old files start
                                if(emp_role!='')
                                {
                                    $('#URSRC_lb_selectemptype').val(emp_role);
                                }
                                if(designation!='')
                                {
                                    $('#URSRC_tb_designation').val(designation);
                                }
                                if( relationhood!='')
                                {
                                    $('#URSRC_tb_relationhd').val(relationhood);
                                }
                                if(accountype!='')
                                {
                                    $('#URSRC_tb_accntyp').val(accountype);
                                }
//                            alert(values_array[0][3])
//                            alert(values_array[0][2])
//                            for(var f=0;f<values_array[0][3].length;f++)
//                            {
//                                var tablerowCount = $('#filetableuploads tr').length;
//                                var uploadfileid="upload_filename"+tablerowCount;
//                                var uploadfilename="filename"+tablerowCount;
//                                var appendfile='<tr><td ><input type=hidden name="uploadfilelist[]" value='+values_array[0][2][f]+'><div class="fileextensionchk"><a href='+values_array[0][4][f]+' target="_blank" class="uploadtag">'+values_array[0][3][f]+'</a></div></td><td><button type="button" class="removebutton" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;">Remove</button><label id="attach_error" hidden></label></td></tr></br>';
//                                $('#filetableuploads').append(appendfile);
//                            }
//                            if(values_array[0][2].length>0)
//                            {
//                                $('#attachafile').text('Attach another file');
//                            }
//                            else
//                            {
//                                $('#attachafile').text('Attach a file');
//                            }
//                            if(values_array[0][2].length==4)
//                            {
//                                $('#attachprompt').hide();
//                            }
                                //load old files end
                                $('#URT_SRC_tb_datepickerrejoin').datepicker("option","minDate",minimumdate);
                                $('#URT_SRC_tb_datepickerrejoin').datepicker("option","maxDate",new Date());
                                $('#URSRC_table_employeetbl').show();
                                $('#URSRC_table_others').show();
                                var emp_firstname=firstname.length;
                                $('#URSRC_tb_firstname').val(firstname).attr("size",emp_firstname+3);
                                var emp_lastname=lastname.length;
                                $('#URSRC_tb_lastname').val(lastname).attr("size",emp_lastname+3);
                                $('#URSRC_tb_dob').val(dob);
                                var emp_designation=designation.length;
//                            $('#URSRC_tb_designation').val(designation).attr("size",emp_designation+4);
//                            $('#URSRC_tb_designation').val(designation);
//                          $('#URSRC_tb_designation option[value="' + designation + '"]').prop('selected', true);
                                $('#URSRC_tb_permobile').val(mobile);
                                var emp_kinname=kinname.length;
                                $('#URSRC_tb_kinname').val(kinname).attr("size",emp_kinname+1);
                                var emp_relationhood=relationhood.length;
//                            $('#URSRC_tb_relationhd').val(relationhood).attr("size",emp_relationhood+2);
//                            $('#URSRC_tb_relationhd').val(relationhood);
                                $('#URSRC_tb_mobile').val(altmobile);
                                var emp_bankname=bankname.length;
                                $('#URSRC_tb_bnkname').val(bankname).attr("size",emp_bankname+2);
                                var emp_branchname=branchname.length;
                                $('#URSRC_tb_brnchname').val(branchname).attr("size",emp_branchname+3);
                                var emp_accountname=accountname.length;
                                $('#URSRC_tb_accntname').val(accountname).attr("size",emp_accountname+2);
                                var emp_accountno=accountno.length;
                                $('#URSRC_tb_accntno').val(accountno).attr("size",emp_accountno+2);
                                var emp_ifsccode=ifsccode.length;
                                $('#URSRC_tb_ifsccode').val(ifsccode).attr("size",emp_ifsccode+2);
                                var emp_accountype=accountype.length;
//                            $('#URSRC_tb_accntyp').val(accountype).attr("size",emp_accountype+2);
//                            $('#URSRC_tb_accntyp').val(accountype);
                                $('#URSRC_ta_brnchaddr').val(branchaddr);
                                $('#URSRC_tb_houseno').val(house);
                                $('#URSRC_tb_strtname').val(street);
                                $('#URSRC_tb_area').val(area);
                                $('#URSRC_tb_pstlcode').val(postal_code);
                                if(laptop!=null){
                                    var emp_laptop=laptop.length;
//                                $('#URSRC_tb_laptopno').val(laptop).attr("size",emp_laptop+2);
                                    $('#URSRC_tb_laptopno').val(laptop);
                                }
                                else{
                                    $('#URSRC_tb_laptopno').val('SELECT');
                                }
                                if(chargerno!=null){
                                    var emp_cahrgerno=chargerno.length;
                                    $('#URSRC_tb_chargerno').val(chargerno).attr("size",emp_cahrgerno+2);
                                }
                                $('#URSRC_ta_comments').val(comments);
                                if(bag=='X')
                                {
                                    $('#URSRC_chk_bag').attr('checked',true);
                                }
                                else
                                {
                                    $('#URSRC_chk_bag').attr('checked',false);
                                }
                                if(mouse=='X')
                                {
                                    $('#URSRC_chk_mouse').attr('checked',true);
                                }
                                else
                                {
                                    $('#URSRC_chk_mouse').attr('checked',false);
                                }
                                if(dooraccess=='X')
                                {
                                    $('#URSRC_chk_dracess').attr('checked',true);
                                }
                                else
                                {
                                    $('#URSRC_chk_dracess').attr('checked',false);
                                }
                                if(idcard=='X')
                                {
                                    $('#URSRC_chk_idcrd').attr('checked',true);
                                }
                                else
                                {
                                    $('#URSRC_chk_idcrd').attr('checked',false);
                                }
                                if(headset=='X')
                                {
                                    $('#URSRC_chk_headset').attr('checked',true);
                                }
                                else
                                {
                                    $('#URSRC_chk_headset').attr('checked',false);
                                }
                                if(aadharno!=null)
                                {
                                    $('#URSRC_chk_aadharno').attr('checked',true);
                                    var emp_aadharno=aadharno.length;
                                    $('#URSRC_tb_aadharno').val(aadharno).show().attr("size",emp_aadharno);
                                }
                                else
                                {
                                    $('#URSRC_chk_aadharno').attr('checked',false);
                                    $('#URSRC_tb_aadharno').val('').hide();
                                }
                                if(passportno!=null)
                                {
                                    $('#URSRC_chk_passportno').attr('checked',true);
                                    var emp_passportno=passportno.length;
                                    $('#URSRC_tb_passportno').val(passportno).show().attr("size",emp_passportno);
                                }
                                else
                                {
                                    $('#URSRC_chk_passportno').attr('checked',false);
                                    $('#URSRC_tb_passportno').val('').hide();
                                }
                                if(votersid!=null)
                                {
                                    $('#URSRC_chk_votersid').attr('checked',true);
                                    var emp_votersid=votersid.length;
                                    $('#URSRC_tb_votersid').val(votersid).show().attr("size",emp_votersid);
                                }
                                else
                                {
                                    $('#URSRC_chk_votersid').attr('checked',false);
                                    $('#URSRC_tb_votersid').val('').hide();
                                }
                            }
                        }
                    }
                }
            }
            var option='GETENDDATE';
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?URT_SRC_loggin="+URT_SRC_loggin+"&option="+option,true);
            xmlhttp.send();
        });

//CHANGE FUNCTION FOR LAPTOP
        $(document).on('change','#URSRC_tb_laptopno',function(){
//        $('.preloader', window.parent.document).show();
            $(".preloader").show();
            var URSRC_lb_laptopno=$('#URSRC_tb_laptopno').find('option:selected').text();
            if(URSRC_lb_laptopno!='SELECT')
            {
                var xmlhttp=new XMLHttpRequest();
//            alert('xmlhttp')
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                        $(".preloader").hide();
                        var value_array=(xmlhttp.responseText);
                        $('#URSRC_tb_chargerno').val(value_array);

                    }
                }
                var option="COMPANY_PROPERTY";
                xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?option="+option+"&URSRC_lb_laptopno="+URSRC_lb_laptopno );
                xmlhttp.send();
            }
        });


//CLICK FUNCTION FOR AADHAR RDO BTN
        $('#URSRC_chk_aadharno').click(function(){
            if($("input[name=URSRC_chk_aadharno]").is(":checked")==true){
                $('#URSRC_tb_aadharno').show();
            }
            else{
                $('#URSRC_tb_aadharno').hide().val("");
            }
        });
        //CLICK FUNCTION FOR RD PASSPORT BTN
        $('#URSRC_chk_passportno').click(function(){
            if($("input[name=URSRC_chk_passportno]").is(":checked")==true){
                $('#URSRC_tb_passportno').show();
            }
            else{
                $('#URSRC_tb_passportno').hide().val("");
            }
        });
//CLICK FUNCTION FOR VOTERID BTN
        $('#URSRC_chk_votersid').click(function(){
            if($("input[name=URSRC_chk_votersid]").is(":checked")==true){
                $('#URSRC_tb_votersid').show();
            }
            else{
                $('#URSRC_tb_votersid').hide().val("");
            }
        });
        //CLICK FUNCTION FOR LOGIN TERMINATION RADIO BTN
        $('#URT_SRC_radio_logintermination').click(function(){
            err_flag=0;
//        $('.preloader', window.parent.document).show();
            $(".preloader").show();
            $("#URT_SRC_lbl_datepickertermination").hide();
            $("#URT_SRC_tb_datepickertermination").hide();
            $("#URT_SRC_lbl_reasontermination").hide();
            $("#URT_SRC_ta_reasontermination").hide();
            $("#URT_SRC_btn_termination").hide();
            $('#URT_SRC_errdate').hide();
            var radio_value_loginidsearch=$(this).val();
            $('#URT_SRC_lb_recordversion').hide();
            $('#URT_SRC_lbl_recordversion').hide();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader', window.parent.document).hide();
                    $(".preloader").hide();
                    var loginid_array=JSON.parse(xmlhttp.responseText);
                    if(loginid_array.length!=0){
                        var URT_SRC_loginid_options='<option>SELECT</option>'
                        for(var l=0;l<loginid_array.length;l++){
                            URT_SRC_loginid_options+= '<option value="' + loginid_array[l][1] + '">' + loginid_array[l][0]+ '</option>';
                        }
                        $('#URT_SRC_lb_loginterminate').html(URT_SRC_loginid_options);
                        $('#URT_SRC_lb_loginterminate').show().prop('selectedIndex',0);
                    }
                    else
                    {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[3],position:{top:100,left:100}}});
                        show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",js_errormsg_array[3],"success",false);
                    }
                }
            }
            var option='TERMINATIONLB';
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
            xmlhttp.send();
        });
//LOGIN SECOND SEARCH ND UPDATE
        $('#URT_SRC_radio_selectrejoin').click(function(){
            $("#URT_SRC_tble_roles").hide();
            $('#URSRC_table_employeetbl').hide();
            $("#URT_SRC_tb_datepickerrejoin").hide();
            $("#URT_SRC_btn_rejoin").hide();
            $("#URT_SRC_lbl_datepickerrejoin").hide();
            $("#URSRC_lbl_emptype").hide();
            $('#URSRC_lb_selectemptype').hide();
            $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
//        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
//        $('.preloader', window.parent.document).show();
            $(".preloader").show();
            var radio_value_loginidsearch=$(this).val();
            $('#URT_SRC_lb_recordversion').hide();
            $('#URT_SRC_lbl_recordversion').hide();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader', window.parent.document).hide();
                    $(".preloader").hide();
                    var loginid_array=JSON.parse(xmlhttp.responseText);
                    if(loginid_array.length!=0){
                        var URT_SRC_loginid_options='<option>SELECT</option>'
                        for(var l=0;l<loginid_array.length;l++){
                            URT_SRC_loginid_options+= '<option value="' + loginid_array[l][1] + '">' + loginid_array[l][0]+ '</option>';
                        }
                        $('#URT_SRC_lb_loginrejoin').html(URT_SRC_loginid_options);
                        $('#URT_SRC_lb_loginrejoin').show().prop('selectedIndex',0);
                    }
                    else
                    {
                        $('#URT_SRC_lbl_loginrejoin').hide();
                        $('#URT_SRC_lb_loginrejoin').hide();
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[4],position:{top:100,left:  100}}});
                        show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",js_errormsg_array[4],"success",false);
                    }
                }
            }
            var option='REJOINLB';
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
            xmlhttp.send();
        });
        //CLICK FUNCTION FOR RADIO SEARCH ND UPDATE BTN
        $('#URT_SRC_radio_selectsearchupdate').click(function(){
//        $("html, body").animate({ scrollTop: $(document).height() }, "fast");
//        $('.preloader', window.parent.document).show();
            $(".preloader").show();
            $('#URT_SRC_lbl_datepickerupdate').hide();
            $('#URT_SRC_tb_datepickerupdate').hide();
            $('#URT_SRC_lbl_reasonupdate').hide();
            $('#URT_SRC_ta_reasonupdate').hide();
            $('#URT_SRC_btn_update').hide();
            var radio_value_loginidsearch=$(this).val();
            $('#URT_SRC_lb_recordversion').hide();
            $('#URT_SRC_lbl_recordversion').hide();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader', window.parent.document).hide();
                    $(".preloader").hide();
                    var loginid_array=JSON.parse(xmlhttp.responseText);
                    if(loginid_array.length!=0){
                        var URT_SRC_loginid_options='<option>SELECT</option>'
                        for(var l=0;l<loginid_array.length;l++){
                            URT_SRC_loginid_options+= '<option value="' + loginid_array[l][1] + '">' + loginid_array[l][0]+ '</option>';
                        }
                        $('#URT_SRC_lb_loginupdate').html(URT_SRC_loginid_options);
                        $('#URT_SRC_lb_loginupdate').show().prop('selectedIndex',0);

                    }
                    else
                    {
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",msgcontent:js_errormsg_array[5],position:{top:100,left:100}}});
                        show_msgbox("ACCESS RIGHTS:TERMINATE SEARCH/UPDATE",js_errormsg_array[5],"success",false);
                        $('#URT_SRC_lbl_loginupdate').hide();
                        $('#URT_SRC_lb_loginupdate').hide();
                    }
                }
            }
            var option='SEARCHLB';
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_TERMINATE-SEARCH_UPDATE.do?radio_value_loginidsearch="+radio_value_loginidsearch+"&option="+option,true);
            xmlhttp.send();
        });
        $("#URT_SRC_btn_rejoin").hide();
        $("#URT_SRC_btn_termination").hide();
        $("#URT_SRC_btn_update").hide();
        $("#URT_SRC_tble_roles").hide();
        $('#URSRC_table_employeetbl').hide();
        $("#URT_SRC_lbl_logintermination").show();
        $("#URT_SRC_lbl_loginsearchupdate").show();
        $('#URT_SRC_radio_logintermination').change(function(){
            $("#URT_SRC_lb_loginupdate").hide();
            $("#URT_SRC_lbl_loginterminate").show();
            $("#URT_SRC_tble_roles").hide();
            $('#URSRC_table_employeetbl').hide();
            $("#URT_SRC_tb_datepickerrejoin").hide();
            $("#URT_SRC_lbl_datepickerrejoin").hide();
            $("#URT_SRC_btn_rejoin").hide();
            $("#URT_SRC_lbl_loginterminate").val("SELECT");
            $("#URT_SRC_lb_loginterminate").show();
            $("#URT_SRC_lbl_selectoption").hide();
            $("#URSRC_lbl_emptype").hide();
            $('#URSRC_lb_selectemptype').hide();
            $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
            $("#URT_SRC_radio_selectrejoin").hide();
            $("#URT_SRC_lbl_selectrejoin").hide();
            $("#URT_SRC_radio_selectsearchupdate").hide();
            $("#URT_SRC_lbl_selectsearchupdate").hide();
            $("#URT_SRC_lbl_loginrejoin").hide();
            $("#URT_SRC_lb_loginrejoin").hide();
            $('#URT_SRC_lbl_loginupdate').hide();
            $('#URT_SRC_lbl_datepickerupdate').hide();
            $('#URT_SRC_tb_datepickerupdate').hide();
            $('#URT_SRC_lbl_reasonupdate').hide();
            $('#URT_SRC_ta_reasonupdate').hide();
            $('#URT_SRC_btn_update').hide();
        });
        $('#URT_SRC_lb_loginterminate').change(function(){
            var loginid=$('#URT_SRC_lb_loginterminate').val();
            if(loginid=='SELECT')
            {
                $("#URT_SRC_lbl_datepickertermination").hide();
                $("#URT_SRC_tb_datepickertermination").hide();
                $("#URT_SRC_lbl_reasontermination").hide();
                $("#URT_SRC_ta_reasontermination").hide();
                $("#URT_SRC_btn_termination").hide();
            }
            else
            {
                $("#URT_SRC_lbl_datepickertermination").show();
                $("#URT_SRC_tb_datepickertermination").val('').show();
                $("#URT_SRC_lbl_reasontermination").show();
                $("#URT_SRC_ta_reasontermination").val('').show();
                $("#URT_SRC_btn_termination").show();
            }
        });
//CHANGE FUNCTION FOR LOGIN LIST BX OF SEARCH ND UPDATE OPTION
        $('#URT_SRC_lb_loginupdate').change(function(){
            $('#URT_SRC_lbl_recordversion').hide();
            $('#URT_SRC_lb_recordversion').hide();
            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
            var loginvalue=$('#URT_SRC_lb_loginupdate').val();
            var URT_SRC_empname_upd=$("#URT_SRC_lb_loginupdate option:selected").text();
            if(URT_SRC_empname_upd=='SELECT')
            {
                $('#URT_SRC_lbl_datepickerupdate').hide();
                $('#URT_SRC_tb_datepickerupdate').hide();
                $('#URT_SRC_lbl_reasonupdate').hide();
                $('#URT_SRC_ta_reasonupdate').hide();
                $('#URT_SRC_btn_update').hide();
                $('#URT_SRC_lb_recordversion').hide();
                $('#URT_SRC_lbl_recordversion').hide();
            }
            else
            {
                $('#URT_SRC_lbl_datepickerupdate').show();
                $('#URT_SRC_tb_datepickerupdate').val('').show();
                $('#URT_SRC_lbl_reasonupdate').show();
                $('#URT_SRC_ta_reasonupdate').val('').show();
                $('#URT_SRC_btn_update').show();
            }
        });
//CHNAGE FUNCTION FOR RADIO OF LOGIN SEARCH ND UPDATE BTN
        $('#URT_SRC_radio_loginsearchupdate').change(function(){
            err_flag=0;
            $("#URT_SRC_lbl_selectoption").show();
            $('#URT_SRC_errdate').hide();
            $("#URT_SRC_radio_selectrejoin").show();
            $("#URT_SRC_lbl_selectrejoin").show();
            $("#URT_SRC_lbl_selectsearchupdate").show();
            $("#URT_SRC_radio_selectsearchupdate").show();
            $("#URT_SRC_lbl_loginterminate").hide();
            $("#URSRC_lbl_emptype").hide();
            $('#URT_SRC_lb_recordversion').hide();
            $('#URT_SRC_lbl_recordversion').hide();
            $('#URSRC_lb_selectemptype').hide();
            $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
            $("#URT_SRC_lb_loginterminate").hide();
            $("#URT_SRC_lbl_datepickertermination").hide();
            $("#URT_SRC_tb_datepickertermination").hide();
            $("#URT_SRC_lbl_reasontermination").hide();
            $("#URT_SRC_ta_reasontermination").hide();
            $("#URT_SRC_btn_termination").hide();
            $("input[name=URT_SRC_radio_nselectoption]:checked").attr('checked',false);
        });

        $('#URT_SRC_radio_selectsearchupdate').change(function(){
            $('#URT_SRC_errdate').hide();
            $('#URT_SRC_lbl_loginrejoin').hide();
            $('#URT_SRC_lb_loginrejoin').hide();
            $('#URT_SRC_lbl_loginterminate').hide();
            $('#URT_SRC_lb_loginterminate').hide();
            $("#URT_SRC_tble_roles").hide();
            $('#URSRC_table_employeetbl').hide();
            $("#URSRC_lbl_emptype").hide();
            $('#URSRC_lb_selectemptype').hide();
            $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
            $("#URT_SRC_tb_datepickerrejoin").hide();
            $("#URT_SRC_lbl_datepickerrejoin").hide();
            $("#URT_SRC_btn_rejoin").hide();
            $("#URT_SRC_lbl_loginupdate").show();
            $('#URT_SRC_lbl_datepickerupdate').hide();
            $('#URT_SRC_tb_datepickerupdate').hide();
            $('#URT_SRC_lbl_reasonupdate').hide();
            $('#URT_SRC_ta_reasonupdate').hide();
            $('#URT_SRC_btn_update').hide();
            $('#URT_SRC_lb_loginupdate').show();
        });
        $('#URT_SRC_radio_selectrejoin').change(function(){
            button_vflag=1;
            $("#URT_SRC_lbl_datepickerrejoin").hide();
            $("#URT_SRC_lbl_loginrejoin").show();
            $("#URT_SRC_tble_roles").hide();
            $('#URSRC_table_employeetbl').hide();
            $("#URT_SRC_lb_loginupdate").hide();
            $("#URT_SRC_tb_datepickerrejoin").hide();
            $("#URT_SRC_btn_rejoin").hide();
            $('#URT_SRC_lbl_loginupdate').hide();
            $('#URT_SRC_lbl_datepickerupdate').hide();
            $('#URT_SRC_tb_datepickerupdate').hide();
            $('#URT_SRC_lbl_reasonupdate').hide();
            $('#URT_SRC_ta_reasonupdate').hide();
            $('#URT_SRC_btn_update').hide();
            $("input[name=URT_SRC_radio_nrole]:checked").attr('checked',false);

        });
//DATE PICKER FUNCTION
        $('.URT_SRC_tb_termindatepickerclass').datepicker({
            dateFormat:"dd-mm-yy",
            changeYear: true,
            changeMonth: true

        });
        //TO SET REJOIN DATE PICKER
        $('.URT_SRC_tb_rejoinndsearchdatepicker').datepicker({
            dateFormat:"dd-mm-yy",
            changeYear: true,
            changeMonth: true
        });
        //FORM VALIDATION
        $(document).on('change','#URT_SRC_form_terminatesearchupdate',function(){
            URT_SRC_validation();
        });
        //FORM VALIDATION FUNCTION CALLIN
        function URT_SRC_validation(){
            var Selectedradiooption = $("input[name='URT_SRC_radio_nterminndupdatesearch']:checked").val();
            var Selectedradiooption = $("input[name='URT_SRC_radio_nterminndupdatesearch']:checked").val();
            if(Selectedradiooption=='URT_SRC_radio_valuelogintermination')
            {
                if(($('#URT_SRC_lb_loginterminate').val()!='SELECT') && ($("#URT_SRC_tb_datepickertermination").val()!="") && (($("#URT_SRC_ta_reasontermination").val()).trim()!="")&& (err_flag==0))
                {
                    $("#URT_SRC_btn_termination").removeAttr("disabled");
                }
                else
                {
                    $("#URT_SRC_btn_termination").removeAttr("disabled");
//                    $("#URT_SRC_btn_termination").attr("disabled", "disabled");
                }
            }
            if(Selectedradiooption=='URT_SRC_radio_valueloginsearchupdate')
            {
                var Selectedsearchradiooption = $("input[name='URT_SRC_radio_nselectoption']:checked").val();
                var URSRC_Firstname= $("#URSRC_tb_firstname").val();
                var URSRC_Lastname =$("#URSRC_tb_lastname").val();
                var URSRC_tb_dob=$('#URSRC_tb_dob').val();
//            var URSRC_empdesig =$("#URSRC_tb_designation").val();
                var URSRC_Mobileno = $("#URSRC_tb_permobile").val();
                var URSRC_kinname = $("#URSRC_tb_kinname").val();
                var URSRC_relationhd = $("#URSRC_tb_relationhd").val();
                var URSRC_mobile= $("#URSRC_tb_mobile").val();
                var URSRC_bnkname =$("#URSRC_tb_bnkname").val();
                var URSRC_tb_brnname=$('#URSRC_tb_brnchname').val();
                var URSRC_accname =$("#URSRC_tb_accntname").val();
                var URSRC_acctno = $("#URSRC_tb_accntno").val();
                var URSRC_ifsc = $("#URSRC_tb_ifsccode").val();
//            var URSRC_accttyp = $("#URSRC_tb_accntyp").val();
                var URSRC_brnchaddr= $("#URSRC_ta_brnchaddr").val();
                var URT_SRC_aadharno=$('#URSRC_tb_aadharno').val();
                var URT_SRC_passportnono=$('#URSRC_tb_passportno').val();
                var URT_SRC_votersidno=$('#URSRC_tb_votersid').val();
                if(Selectedsearchradiooption=='URT_SRC_radio_valuerejoin')
                {
                    if(button_vflag==1&&($("#URT_SRC_lbl_loginupdate").val()!='SELECT') &&($('#URSRC_lb_selectemptype').val()!='SELECT') && ($("#URT_SRC_tb_datepickerrejoin").val()!="")&& ($("input[name=URT_SRC_radio_nrole]").is(":checked")==true)&&(URSRC_Firstname!='') && (URSRC_Lastname!='' ) && (URSRC_tb_dob!='' ) &&($('#URSRC_tb_designation').val()!='SELECT')&&( URSRC_Mobileno!='' && (parseInt($('#URSRC_tb_permobile').val())!=0)) && (URSRC_kinname!='')&& ($('#URSRC_tb_relationhd').val()!='SELECT') && (URSRC_Mobileno.length>=10)&&(URSRC_mobile.length>=10 )&&(URSRC_brnchaddr!="")&&($('#URSRC_tb_accntyp').val()!='SELECT') &&(URSRC_ifsc!="")&&(URSRC_acctno!="")&&(URSRC_accname!="")&&(URSRC_tb_brnname!="")&&(URSRC_bnkname!=""))
                    {
                        $("#URT_SRC_btn_rejoin").removeAttr("disabled");
                        if(($("input[name=URSRC_chk_aadharno]").is(":checked")==true)||($("input[name=URSRC_chk_votersid]").is(":checked")==true)||($("input[name=URSRC_chk_passportno]").is(":checked")==true)){
                            if((URT_SRC_aadharno=='' && $("input[name=URSRC_chk_aadharno]").is(":checked")==true) ||(URT_SRC_passportnono=='' && $("input[name=URSRC_chk_passportno]").is(":checked")==true)||(URT_SRC_votersidno=='' && $("input[name=URSRC_chk_votersid]").is(":checked")==true))
                                $("#URT_SRC_btn_rejoin").attr("disabled", "disabled");
                            else
                                $("#URT_SRC_btn_rejoin").removeAttr("disabled");
                        }

                    }
                    else
                    {
                        $("#URT_SRC_btn_rejoin").attr("disabled", "disabled");
                    }
                }
                else
                {
                    if(($('#URT_SRC_lb_loginupdate').val()!='SELECT') && ($("#URT_SRC_tb_datepickerupdate").val()!='') && (($("#URT_SRC_ta_reasonupdate").val()).trim()) && ($('#URT_SRC_lb_recordversion').val()!='SELECT'))
                    {
                        $("#URT_SRC_btn_update").removeAttr("disabled");
                    }
                    else
                    {
                        $("#URT_SRC_btn_update").attr("disabled", "disabled");
                    }
                }
            }
        }
        //BLUR FUNCTION FOR MOBILE NUMBER VALIDATION
        $(document).on('blur','.valid',function(){
            var URSRC_Mobileno=$(this).attr("id");
            var URSRC_Mobilenoval=$(this).val();
            if(URSRC_Mobilenoval.length==10)
            {
                if(URSRC_Mobileno=='URSRC_tb_permobile')
                    $('#URSRC_lbl_validnumber').hide();
                else
                    $('#URSRC_lbl_validnumber1').hide();
            }
            else
            {
                if(URSRC_Mobileno=='URSRC_tb_permobile')
                    $('#URSRC_lbl_validnumber').text(js_errormsg_array[7]).show();
                else
                    $('#URSRC_lbl_validnumber1').text(js_errormsg_array[7]).show();
            }
        });
    });
</script>
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/></div>
    <div class="title text-center"><h4><b>ACCESS RIGHTS:TERMINATE SEARCH/UPDATE</b></h4></div>
    <form id="URT_SRC_form_terminatesearchupdate" class="content" method="post" enctype="multipart/form-data">
        <div class="panel-body">
            <fieldset>
                <div class="form-group">
                    <label name="URT_SRC_lbl_nlogintermination" class="col-sm-12" id="URT_SRC_lbl_logintermination" hidden>
                        <div class="radio">
                            <input type="radio" name="URT_SRC_radio_nterminndupdatesearch" id="URT_SRC_radio_logintermination" value="URT_SRC_radio_valuelogintermination" >LOGIN TERMINATION</label>
                </div></div>

        <div class="form-group">
            <label  name="URT_SRC_lbl_nloginsearchupdate" class="col-sm-12" id="URT_SRC_lbl_loginsearchupdate"  hidden>
                <div class="radio">
                    <input type="radio" name="URT_SRC_radio_nterminndupdatesearch" id="URT_SRC_radio_loginsearchupdate" value="URT_SRC_radio_valueloginsearchupdate" >SEARCH/UPDATE</label>
        </div></div>

<div class="row-fluid form-group">
    <label name="URT_SRC_lbl_nloginterminate" id="URT_SRC_lbl_loginterminate" class=" col-sm-2" hidden>EMPLOYEE NAME<em>*</em> </label>
    <div class="col-sm-4">
        <select name="URT_SRC_lb_nloginterminate" id="URT_SRC_lb_loginterminate" class="form-control" style="display: none" hidden> <option>SELECT</option></select>
    </div>
</div>

<div class="row-fluid form-group">
    <!-- <label name="URT_SRC_lbl_datepickertermination" id="URT_SRC_lbl_datepickertermination" class="srctitle col-lg-8 control-label col-sm-2" hidden> SELECT A END DATE <em>*</em> </label>-->
    <label name="URT_SRC_lbl_datepickertermination" id="URT_SRC_lbl_datepickertermination" class=" col-sm-2" hidden> SELECT A END DATE <em>*</em> </label>
    <div class="col-sm-4">
        <input type="text" name="URT_SRC_tb_ndatepickertermination" id="URT_SRC_tb_datepickertermination" class="URT_SRC_tb_termindatepickerclass datemandtry" style="width:75px;" hidden>
        <!--     <label id="URT_SRC_errdate" name="URT_SRC_errdate" class="errormsg"></label>-->
    </div>
</div>
<div class="row-fluid form-group">
    <label name="URT_SRC_lbl_nreasontermination" id="URT_SRC_lbl_reasontermination" class=" col-sm-2" hidden> REASON OF TERMINATION<em>*</em></label>
    <div class="col-sm-4">
        <textarea name="URT_SRC_ta_nreasontermination" id="URT_SRC_ta_reasontermination" hidden> </textarea>
    </div>
</div>
<div>
    <input type="button"  value="TERMINATE" id="URT_SRC_btn_termination" class="maxbtn" hidden>
</div>
<!--select an option-->
<div class="row-fluid form-group" >
    <label name="URT_SRC_lbl_nselectoption" id="URT_SRC_lbl_selectoption" class="srctitle" hidden> SELECT A OPTION </label>
</div>
<div class=" form-group form-inline col-sm-12">
    <label name="URT_SRC_lbl_nselectrejoin"  id="URT_SRC_lbl_selectrejoin"  hidden>
        <div class="radio">
            <input type="radio" name="URT_SRC_radio_nselectoption" id="URT_SRC_radio_selectrejoin"  value="URT_SRC_radio_valuerejoin" hidden>&nbsp; REJOIN </label>
</div></div>
<div class=" form-group form-inline col-sm-12">
    <label name="URT_SRC_lbl_nselectsearchupdate" id="URT_SRC_lbl_selectsearchupdate"  hidden>
        <div class="radio">
            <input type="radio" name="URT_SRC_radio_nselectoption" id="URT_SRC_radio_selectsearchupdate" hidden>&nbsp;SEARCH/UPDATE </label>
</div></div>
<!--terminate rejoin-->
<div class="row-fluid form-group">
    <label name="URT_SRC_lbl_nloginrejoin" id="URT_SRC_lbl_loginrejoin" class=" col-sm-2" hidden>EMPLOYEE NAME<em>*</em></label>
    <div class="col-sm-4">
        <select name="URT_SRC_lb_nloginrejoin" id="URT_SRC_lb_loginrejoin" class="form-control"style="display: none" hidden ><option>SELECT</option></select>
    </div></div>
<div class="row-fluid form-group"  width="185">
    <label id="URSRC_lbl_emptype" class=" col-lg-2" hidden>SELECT TYPE OF EMPLOYEE<em>*</em></label>
    <div class="col-sm-3">
        <select id='URSRC_lb_selectemptype' name="URSRC_lb_selectemptype" class="form-control" style="display: none"hidden  >
            <option value='SELECT' selected="selected"> SELECT</option>
        </select>
    </div></div>
<div id="URT_SRC_tble_roles"></div>
<div class="row-fluid form-group">
    <label name="URT_SRC_lbl_ndatepickerrejoin" id="URT_SRC_lbl_datepickerrejoin" class="col-lg-2" hidden> SELECT A REJOIN DATE<em>*</em></label>
    <div class="col-sm-10" width="185">
        <input type="text" name="URT_SRC_tb_ndatepickerrejoin" id="URT_SRC_tb_datepickerrejoin" class="URT_SRC_tb_rejoinndsearchdatepicker datemandtry" style="width:75px;" hidden>
    </div></div>
<!--EMPLOYEE DETAILS-->
<div id="URSRC_table_employeetbl" hidden>
    <div class="row-fluid form-group">
        <label class="srctitle"  name="URSRC_lbl_personnaldtls" id="URSRC_lbl_personnaldtls">PERSONAL DETAILS</label>
    </div>
    <div class="row-fluid form-group" width="175">
        <label name="row URSRC_lbl_firstname" class="col-lg-2" id="URSRC_lbl_firstname">FIRST NAME <em>*</em></label>
        <div class="col-sm-10">
            <input type="text" name="URSRC_tb_firstname" id="URSRC_tb_firstname" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate" >
        </div></div>

    <div class="row-fluid form-group" width="175">
        <label name="URSRC_lbl_lastname" class="col-sm-2" id="URSRC_lbl_lastname">LAST NAME <em>*</em></label>
        <div class="col-sm-10">
            <input type="text" name="URSRC_tb_lastname" id="URSRC_tb_lastname" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate">
        </div></div>

    <div class="row-fluid form-group" width="175">
        <label name="URSRC_lbl_dob" class="col-sm-2" id="URSRC_lbl_dob">DATE OF BIRTH<em>*</em></label>
        <div class="col-sm-10">
            <input type="text" name="URSRC_tb_dob" id="URSRC_tb_dob" class="datepickerdob datemandtry login_submitvalidate" style="width:75px;">
        </div></div>
    <div class="row-fluid form-group" width="175">
        <label name="URSRC_lbl_designation" class="col-sm-2" id="URSRC_lbl_designation">DESIGNATION<em>*</em></label>
        <div class="col-sm-4">
            <!--            <input type="text" name="URSRC_tb_designation" id="URSRC_tb_designation" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate">-->
            <select  name="URSRC_tb_designation" id="URSRC_tb_designation" class="form-control" style="display: inline">
                <option value='SELECT'  selected="selected"> SELECT</option>
            </select>
        </div>
    </div>
    <div class="row-fluid form-group" width="175">

        <label name="URSRC_lbl_permobile" class="col-sm-2" id="URSRC_lbl_permobile">PERSONAL MOBILE<em>*</em></label>
        <div class="col-sm-10">
            <input type="text" name="URSRC_tb_permobile" id="URSRC_tb_permobile"  maxlength='10' class="mobileno title_nos valid login_submitvalidate" style="width:75px" >
            <label id="URSRC_lbl_validnumber" name="URSRC_lbl_validnumber" class="errormsg"></label>
        </div></div>
    <div class="row-fluid  form-group" width="175">
        <label name="URSRC_lbl_kinname" class="col-sm-2" id="URSRC_lbl_kinname">NEXT KIN NAME<em>*</em></label>
        <div class="col-sm-10">
            <input type="text" name="URSRC_tb_kinname" id="URSRC_tb_kinname" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate">
        </div></div>

    <div class="row-fluid form-group">
        <label name="URSRC_lbl_relationhd" class="col-sm-2" id="URSRC_lbl_relationhd">RELATION HOOD<em>*</em></label>
        <div class="col-sm-4">
            <!--            <input type="text" name="URSRC_tb_relationhd" id="URSRC_tb_relationhd" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate" >-->
            <select id='URSRC_tb_relationhd' name="URSRC_tb_relationhd" class="relationhd_submitvalidate form-control" style="display: inline>
            <option value='SELECT' selected="selected"> SELECT</option>
            </select>
        </div></div>
    <div class="row-fluid  form-group">
        <label name="URSRC_lbl_mobile" class="col-sm-2" id="URSRC_lbl_mobile">MOBILE NO<em>*</em></label>
        <div class="col-sm-10">
            <input type="text" name="URSRC_tb_mobile" id="URSRC_tb_mobile" class="mobileno title_nos valid login_submitvalidate" maxlength='10' style="width:75px">
            <label id="URSRC_lbl_validnumber1" name="URSRC_lbl_validnumber1" class="errormsg"></label>
        </div></div>
    <div class="row-fluid form-group">
        <label class="col-sm-2" name="URSRC_lbl_houseno" id="URSRC_lbl_houseno">HOUSE NO<em>*</em></label>
        <div class="col-sm-4">
            <input type="" name="URSRC_tb_houseno" id="URSRC_tb_houseno" class="houseno title_nos valid login_submitvalidate"  maxlength='7' style="width:75px">
        </div></div>
    <div class="row-fluid form-group">
        <label class="col-sm-2" name="URSRC_lbl_strtname" id="URSRC_lbl_strtname">STREET NAME<em>*</em></label>
        <div class="col-sm-4">
            <input type="text" name="URSRC_tb_strtname" id="URSRC_tb_strtname" class="alphanumericuppercse sizefix login_submitvalidate">
        </div></div>
    <div class="row-fluid form-group">
        <label class="col-sm-2" name="URSRC_lbl_area" id="URSRC_lbl_area">AREA<em>*</em></label>
        <div class="col-sm-4">
            <input type="text" name="URSRC_tb_area" id="URSRC_tb_area" class="alphanumericuppercse sizefix login_submitvalidate">
        </div></div>
    <div class="row-fluid form-group">
        <label class="col-sm-2" name="URSRC_lbl_pstlcode" id="URSRC_lbl_pstlccode">POSTAL CODE<em>*</em></label>
        <div class="col-sm-4">
            <input type="text" name="URSRC_tb_pstlcode" id="URSRC_tb_pstlcode" maxlength='6' class="sizefix pstlcode login_submitvalidate" style="width:75px">
        </div></div>
    <div class="row-fluid form-group">
        <label class="srctitle" class="col-sm-2" name="URSRC_lbl_bnkdtls" id="URSRC_lbl_bnkdtls">BANK DETAILS</label>
    </div>
    <div class="row-fluid form-group" width="175">
        <label name="URSRC_lbl_bnkname" class="col-sm-2" id="URSRC_lbl_bnkname">BANK NAME <em>*</em></label>
        <div class="col-sm-10">
            <input type="text" name="URSRC_tb_bnkname" id="URSRC_tb_bnkname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate" >
        </div></div>
    <div class="row-fluid form-group" width="175">
        <label name="URSRC_lbl_brnchname" class="col-sm-2" id="URSRC_lbl_brnchname">BRANCH NAME <em>*</em></label>
        <div class="col-sm-10">
            <input type="text" name="URSRC_tb_brnchname" id="URSRC_tb_brnchname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate" >
        </div></div>
    <div class="row-fluid form-group" width="175">
        <label name="URSRC_lbl_accntname" class="col-sm-2" id="URSRC_lbl_accntname">ACCOUNT NAME <em>*</em></label>
        <div class="col-sm-10">
            <input type="text" name="URSRC_tb_accntname" id="URSRC_tb_accntname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate" >
        </div></div>
    <div class="row-fluid form-group" width="175">
        <label name="URSRC_lbl_accntno" class="col-sm-2" id="URSRC_lbl_accntno">ACCOUNT NUMBER <em>*</em></label>
        <div class="col-sm-10">
            <input type="text" name="URSRC_tb_accntno" id="URSRC_tb_accntno" maxlength='50' class=" sizefix accntno login_submitvalidate" >
        </div></div>
    <div class="row-fluid form-group" width="175">
        <label name="URSRC_lbl_ifsccode" class="col-sm-2" id="URSRC_lbl_ifsccode">IFSC CODE<em>*</em></label>
        <div class="col-sm-10">
            <input type="text" name="URSRC_tb_ifsccode" id="URSRC_tb_ifsccode" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate" >
        </div></div>
    <div class="row-fluid form-group" width="175">
        <label name="URSRC_lbl_accntyp" class="col-sm-2" id="URSRC_lbl_accntyp">ACCOUNT TYPE<em>*</em></label>
        <div class="col-sm-4">
            <!--            <input type="text" name="URSRC_tb_accntyp" id="URSRC_tb_accntyp" maxlength='15' class="alphanumericuppercse sizefix login_submitvalidate" >-->
            <select name="URSRC_tb_accntyp" id="URSRC_tb_accntyp" class="alphanumericuppercse login_submitvalidate form-control" style="display: inline" >
                <option value='SELECT' selected="selected"> SELECT</option>
            </select>
        </div></div>
    <div class="row-fluid form-group" width="175">
        <label name="URSRC_lbl_brnchaddr" class="col-sm-2" id="URSRC_lbl_brnchaddr">BRANCH ADDRESS<em>*</em></label>
        <div class="col-sm-10">
            <textarea rows="4" cols="50" name="URSRC_ta_brnchaddr" id="URSRC_ta_brnchaddr" class="maxlength login_submitvalidate"></textarea>
        </div></div>
    <div class="row-fluid  form-group">

        <label class="srctitle"  name="URSRC_lbl_others" id="URSRC_lbl_others">OTHERS</label>
    </div>
    <div class="row-fluid form-group">
        <label name="URSRC_lbl_laptopno" class="col-sm-2" id="URSRC_lbl_laptopno">LAPTOP NUMBER</label>
        <div class="col-sm-10">
            <!--            <input type="text" name="URSRC_tb_laptopno" id="URSRC_tb_laptopno" maxlength='10' class="alphanumeric sizefix login_submitvalidate">-->
            <select id='URSRC_tb_laptopno' name="URSRC_tb_laptopno"  class="selectlaptopno_submitvalidate"class="form-control" style="display: inline">
                <option value='SELECT' selected="selected"> SELECT</option>
            </select>
        </div></div>
    <div class="row-fluid form-group">
        <label name="URSRC_lbl_laptopno" class="col-sm-2" id="URSRC_lbl_laptopno">CHARGER NO</label>
        <div class="col-sm-10">
            <input type="text" name="URSRC_tb_chargerno" id="URSRC_tb_chargerno" maxlength='50' class="alphanumeric sizefix login_submitvalidate" readonly>
        </div></div>

    <div id="URSRC_table_others" style="width:500px" hidden>
        <div class="row-fluid form-group form-inline col-sm-offset-6 col-lg-3">
            <div class="form-inline col-lg-1"><div class="checkbox">
                    <input type="checkbox" name="URSRC_chk_bag" id="URSRC_chk_bag" class="login_submitvalidate">
                </div></div>
            <label name="URSRC_lbl_laptopbag" id="URSRC_lbl_laptopbag">&nbsp;&nbsp;LAPTOP BAG</label>
        </div>
        <div class="row-fluid form-group form-inline col-sm-offset-6 col-lg-3">
            <div class="form-inline col-lg-1"><div class="checkbox">
                    <input type="checkbox" name="URSRC_chk_mouse" id="URSRC_chk_mouse" class="login_submitvalidate">
                </div></div>
            <label name="URSRC_lbl_laptopno" id="URSRC_lbl_laptopno">&nbsp;&nbsp;MOUSE</label>
        </div>
        <div class="row-fluid form-group form-inline col-sm-offset-6 col-lg-3">
            <div class="form-inline col-lg-1"><div class="checkbox">
                    <input type="checkbox" name="URSRC_chk_dracess" id="URSRC_chk_dracess"  class="login_submitvalidate">
                </div></div>
            <label name="URSRC_lbl_dracess" id="URSRC_lbl_dracess">&nbsp;&nbsp;DOOR ACCESS</label>
        </div>
        <div class="row-fluid form-group form-inline col-sm-offset-6 col-lg-3">
            <div class="form-inline col-lg-1"><div class="checkbox">
                    <input type="checkbox" name="URSRC_chk_idcrd" id="URSRC_chk_idcrd" class="login_submitvalidate">
                </div></div>
            <label name="URSRC_lbl_idcrd" id="URSRC_lbl_idcrd">&nbsp;&nbsp;ID CARD</label>
        </div>
        <div class="row-fluid form-group form-inline col-sm-offset-6 col-lg-3">
            <div class="form-inline col-lg-1"><div class="checkbox">
                    <input type="checkbox" name="URSRC_chk_headset" id="URSRC_chk_headset" class="login_submitvalidate">
                </div></div>
            <label name="URSRC_lbl_headset" id="URSRC_lbl_headset">&nbsp;&nbsp;HEAD SET</label>
        </div>
        <div class="row-fluid form-group form-inline col-sm-offset-6 col-lg-3">
            <div class="form-inline col-lg-1"><div class="checkbox">
                    <input type="checkbox" name="URSRC_chk_aadharno" id="URSRC_chk_aadharno" class="login_submitvalidate">
                </div></div>
            <label name="URSRC_lbl_aadharno" id="URSRC_lbl_aadharno">&nbsp;&nbsp;AADHAAR NO</label><input type="text" name="URSRC_tb_aadharno" id="URSRC_tb_aadharno" maxlength='' class=" sizefix login_submitvalidate" hidden>

        </div>
        <div class="row-fluid form-group form-inline col-sm-offset-6 col-lg-3">
            <div class="form-inline col-lg-1"><div class="checkbox">
                    <input type="checkbox" name="URSRC_chk_passportno" id="URSRC_chk_passportno" class="login_submitvalidate">
                </div></div>
            <label name="URSRC_lbl_passportno" id="URSRC_lbl_passportno">&nbsp;&nbsp;PASSPORT NO</label><input type="text" name="URSRC_tb_passportno" id="URSRC_tb_passportno" maxlength='10' class="alphanumeric sizefix login_submitvalidate" hidden>
        </div>
        <div class="row-fluid form-group form-inline col-sm-offset-6 col-lg-3">
            <div class="form-inline col-lg-1"><div class="checkbox">
                    <input type="checkbox" name="URSRC_chk_votersid" id="URSRC_chk_votersid" class="login_submitvalidate">
                </div></div>
            <label name="URSRC_lbl_votersid" id="URSRC_lbl_votersid">&nbsp;&nbsp;VOTERS ID NO</label><input type="text" name="URSRC_tb_votersid" id="URSRC_tb_votersid" maxlength='10' class="alphanumeric sizefix login_submitvalidate" hidden>
        </div>

        <div class="row-fluid form-group">
            <label  class="col-sm-2" name="URSRC_lbl_comments" id="URSRC_lbl_comments">COMMENTS</label>
            <div class="col-sm-offset-5">
                <textarea rows="4" cols="50" name="URSRC_ta_comments" id="URSRC_ta_comments" class="maxlength login_submitvalidate"></textarea>
            </div></div>

        <div id="filetableuploads"></div>


        <div>
                        <span id="attachprompt"><img width="15" height="15" src="https://ssl.gstatic.com/codesite/ph/images/paperclip.gif" border="0">
                        <a href="javascript:_addAttachmentFields('attachmentarea')" id="attachafile">Attach a file</a>
                        </span>
        </div>
    </div>

    <!--EMPL DETAILS-->
    <div><input align="right" type="submit" value="REJOIN" id="URT_SRC_btn_rejoin" name="URT_SRC_btn_rejoin" class="btn"  hidden></div>
</div>
<!--terminate updation-->
<div class="row-fluid form-group">
    <label name="URT_SRC_lbl_nloginupdate" id="URT_SRC_lbl_loginupdate" class="form-inline col-sm-2" hidden>LOGIN ID<em>*</em></label>
    <div class="col-sm-4">
        <select name="URT_SRC_lb_nloginupdate" id="URT_SRC_lb_loginupdate" class="form-control" style="display: none" hidden> <option>SELECT</option></select>
    </div></div>
<div class="row-fluid form-group">
    <label id="URT_SRC_lbl_recordversion" class=" col-sm-2" hidden >RECORD VERSION<em>*</em></label>
    <div class="col-sm-10">
        <select name="URT_SRC_lb_recordversion" id="URT_SRC_lb_recordversion" hidden ></select>
    </div></div>
<div class="row-fluid form-group">
    <label name="URT_SRC_lbl_ndatepickerupdate" id="URT_SRC_lbl_datepickerupdate" class=" form-inline col-sm-2" hidden> SELECT A END DATE <em>*</em> </label>
    <div class="col-sm-10">
        <input type="text" name="URT_SRC_tb_ndatepickerupdate" id="URT_SRC_tb_datepickerupdate" class="URT_SRC_tb_rejoinndsearchdatepicker datemandtry" hidden>
    </div></div>
<div class="row-fluid form-group">
    <label name="URT_SRC_lbl_nreasonupdate" id="URT_SRC_lbl_reasonupdate" class="form-inline col-sm-2" hidden> REASON OF TERMINATION<em>*</em></label>
    <div class="col-sm-4">
        <textarea name="URT_SRC_ta_nreasonupdate" id="URT_SRC_ta_reasonupdate" hidden> </textarea>
    </div></div>
<div>
    <input align="right" type="button" value="UPDATE" id="URT_SRC_btn_update" class="btn"  hidden style="width:100px">
</div>
</fieldset>
</div>
<!--</div>-->
</form>
</div>
</body>
</html>