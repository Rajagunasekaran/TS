<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************ACCESS_RIGHTS_SEARCH_UPDATE*********************************************//
//DONE BY:PUNI
//ver 0.08 DESC:CHANGED SCRIPT TO UPLOAD FILE IN DRIVE -LOGIN CREATION N UPDATTION-CHANGED SUBMIT BUTTON VALIDATION,SHOW ERR WITH DUPLICATE FILE NAME BY USER,MAX 4 FILES,FILE SIZE 10 MB,CREATED FOLDER IN DRIVE IF NOT EXISTS ELSE SHOW ERROR MSG
//DONE BY:SASIKALA
//ver 0.07 DESC:updated preloader position and attach file
//ver 0.06 DESC:changed text box id while change login id and added real escapt for branchadress-safi
//VER 0.05  DESC:updated login creation/updation  validation part
//VER 0.04 SD:2/12/14 ED:2/12/2014 TRACKER NO:74 DESC:ISSUE CORRECTED IN COMMENT NO:123
//VER 0.03 SD:28/11/14 ED:1/12/2014 TRACKER NO:74 DESC:MERGED LOGIN CREATION/UPDATION AND EMPLOYEE CREATION FORM
//DONE BY:LALITHA
//VER 0.02-SD:09/10/2014 ED:13/10/2014 TRACKER NO:79 :DESC:updated to update login id in search/update and sending email while create login id
//VER 0.01-INITIAL VERSION, SD:18/08/2014 ED:27/09/2014,TRACKER NO:79
//*********************************************************************************************************//-->
<?php
include  "../TSLIB/TSLIB_HEADER.php";
//include  "NEW_MENU.php";
?>
<!--SCRIPT TAG START-->
<script>
    var button_vflag=1;
    var err_msg_array=[];
    var EMP_ENTRY_loginid=[];
    var project_array=[];
    var laptopno;
    var mouse;var btry;var mouseupd;var bag;
    //START DOCUMENT READY FUNCTION
    $(document).ready(function(){
        $(".preloader").show();
        $('textarea').autogrow({onInitialize: true});
        //reomve file upload row
//    FUNCTION TO GET DIFFERENCE ARRAY
        function getDifferenceArray(outfilearray,infilearray)
        {
            var j=0;
            var URSRC_new_diff_array=[];
            for(var i=0; i<=infilearray.length-1;i++)
            {
                if(outfilearray.indexOf(infilearray[i])==-1)
                {
                    URSRC_new_diff_array[j]=infilearray[i];
                    j++;
                }
            }
            return URSRC_new_diff_array;
        }
        $(document).on('click', 'button.removebutton', function () {
            $(this).closest('div').remove();
            ValidateSubmitbutton()
            return false;
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
                        show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[31],"success",false);
                        button_vflag=0;
                        $('#attachprompt').hide();
                    }
                    var filesize=$("#"+$(this).attr("id"))[0].files[0].size;//(this.files[0].size);
                    var maxfilesize=1048576*10;

                    if(filesize>maxfilesize)
                    {
                        show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[34],"success",false);
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
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:URSRC_errorAarray[33],position:{top:100,left:100}}});
                        show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[33],"success",false);
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
            loginbuttonvalidation();
            var rowCount = $('#filetableuploads div').length;
            if(rowCount!=0)
            {
                $('#attachprompt').show();
                $('#attachafile').text('Attach another file').show();
            }
            else
            {
                $('#attachafile').text('Attach a file').show();
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
        $(document).on("change",'.fileextensionchk', function (){
            ValidateSubmitbutton()
        });
        //file upload reset
        function reset_field(e) {
            e.wrap('<form>').parent('form').trigger('reset');
            e.unwrap();
        }
        var filerowcount=0;
        //add file upload row
        $(document).on("click",'#attachprompt', function (){
            button_vflag=0;
            var tablerowCount = $('#filetableuploads div').length;
            filerowcount=filerowcount+1;
            var uploadfileid="upload_filename"+filerowcount;
            var appendfile='<div class="col-sm-offset-6 col-sm-10" style="padding-left:23px"><input type="file" name="UPD_uploaded_files[]" class="fileextensionchk" id='+uploadfileid+'></td><td><button type="button" class="removebutton" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;">Remove</button><label id="attach_error" hidden></label></div>';
            $('#filetableuploads').append(appendfile);
            ValidateSubmitbutton()
        });
        var URSRC_menuname=[];
        var URSRC_submenu=[];
        var URSRC_subsubmenu=[];
        var URSRC_checked_mpid=[];
        var sub_menu1_id=[];
        var URSRC_basicradio_value;
        $('#URSRC_btn_login_submitbutton').hide();
        $('#URSRC_btn_submitbutton').hide();
        var URSRC_multi_array=[];
        var URSRC_rolecreation_array=[];
        var URSRC_basicrole_profile_array=[];
        var URSRC_userrigths_array=[];
        var URSRC_errorAarray=[];
        var URSRC_emptype_array=[];
        var URSRC_comp_sdate;
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var value_array=JSON.parse(xmlhttp.responseText);
                URSRC_rolecreation_array=value_array[0];
                URSRC_userrigths_array=value_array[1];
                URSRC_basicrole_profile_array=value_array[3];
                URSRC_errorAarray=value_array[4];
                URSRC_emptype_array=value_array[5];
                URSRC_comp_sdate=value_array[6];
                var emp_type='<option value="SELECT">SELECT</option>';
                for(var k=0;k<URSRC_emptype_array.length;k++){
                    emp_type += '<option value="' + URSRC_emptype_array[k] + '">' + URSRC_emptype_array[k] + '</option>';
                }
                $('#URSRC_lb_selectemptype').html(emp_type);
                var URSRC_basicrole_radio='<div class="col-lg-2 control-label"><label>SELECT BASIC ROLE</label></div>'
                var URSRC_basicroleprofile_radio='<div class="col-lg-2 control-label"><label">SELECT BASIC ROLE <em>*</em></label></div>'
                for(var j=0;j<URSRC_basicrole_profile_array.length;j++){
                    var basic_roleprofile_value=URSRC_basicrole_profile_array[j].replace(" ","_")
                    URSRC_basicroleprofile_radio+='<div class="col-sm-offset-2 col-lg-10">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="URSRC_cb_basicroles1[]" id='+basic_roleprofile_value+' value='+basic_roleprofile_value+' class="URSRC_class_basicroles_chk tree"/>'+URSRC_basicrole_profile_array[j]+'</div>';
                }
                $('#URSRC_tble_basicroles_chk').html(URSRC_basicroleprofile_radio);
                //BASIC ROLE ENTRY
                if(URSRC_userrigths_array.length!=0){
                    var URSRC_role_radio='<div class="col-lg-2 control-label"><label>SELECT ROLE ACCESS</label></div>'
                    var URSRC_basicrole_radio='<div class="col-lg-2 control-label"><label>SELECT BASIC ROLE</label></div>'
                    for (var i = 0; i < URSRC_userrigths_array.length; i++) {
                        var id="URSRC_tble_table"+i
                        var id1="URSRC_userrigths_array"+i;
                        var value=URSRC_userrigths_array[i].replace(" ","_")
                        URSRC_role_radio+='<div class="col-sm-offset-2 col-lg-10"><div class="radio"><label><input type="radio" name="basicroles" id='+id1+' value='+value+' class="URSRC_class_basicroles "  />' + URSRC_userrigths_array[i] + '</label></div></div>';
                        URSRC_basicrole_radio+='<div class=" col-sm-offset-2 col-lg-10"><div class="radio"><label><input type="radio" name="URSRC_radio_basicroles1" id='+value+i+' value='+value+' class="URSRC_class_basic"/>'+URSRC_userrigths_array[i]+'</label></div></div>';
                    }
                    $('#URSRC_tble_roles').html(URSRC_role_radio);
                    $('#URSRC_tble_basicroles').html(URSRC_basicrole_radio);
                }
                else
                {
                    var msg=URSRC_errorAarray[12].replace("[USERID]",UserStamp);
                    $('#URSRC_form_user_rights').replaceWith('<p><label class="errormsg">'+msg+'</label></p>');
                }
                $(".title_alpha").prop("title",URSRC_errorAarray[0]);
                $(".title_nos").prop("title",URSRC_errorAarray[1]);
            }
        }
        var option="ACCESS_RIGHTS_SEARCH_UPDATE";
        xmlhttp.open("GET","TSLIB/TSLIB_COMMON.do?option="+option);
        xmlhttp.send();
        //END BASIC ROLECREATION
        //postalcode
//    jQuery('.pstlcode').keyup(function () {
//        this.value = this.value.replace(/[^0-9\.]/g,'');
//    });
        //DATE PICKER FUNCTION
        $('.datepicker').datepicker({
            dateFormat:"dd-mm-yy",changeYear:true,changeMonth:true
        });
        //MAX DATE SETTING
        $( '.datepicker' ).datepicker( "option", "maxDate", new Date() );
        //SET DOB DATEPICKER
        var EMP_ENTRY_d = new Date();
        var EMP_ENTRY_year = EMP_ENTRY_d.getFullYear() - 18;
        EMP_ENTRY_d.setFullYear(EMP_ENTRY_year);
        $('#URSRC_tb_dob').datepicker(
            {
                dateFormat: 'dd-mm-yy',
                changeYear: true,
                changeMonth: true,
                yearRange: '1920:' + EMP_ENTRY_year + '',
                defaultDate: EMP_ENTRY_d
            });
        $('#URSC_tb_strtname').keypress(function (e) {
            var regex = new RegExp("^[a-z A-Z]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            else
            {
                return false;
            }
        });
        $('#URSC_tb_area').keypress(function (e) {
            var regex = new RegExp("^[a-z A-Z]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            else
            {
                return false;
            }
        });
        var pass_changedmonth=new Date(EMP_ENTRY_d.setFullYear(EMP_ENTRY_year));
        $('#URSRC_tb_dob').datepicker("option","maxDate",pass_changedmonth);
//END DATE PICKER FUNCTION
        //DO VALIDATION START
        $('.autosize').doValidation({rule:'general',prop:{autosize:true}});
        $('#URSRC_tb_customrole').doValidation({rule:'alphanumeric',prop:{whitespace:true,autosize:true,uppercase:true}});
        $('#URSRC_tb_loginid').doValidation({rule:'general',prop:{uppercase:false,autosize:true}});
        $('#URSRC_tb_loginidupd').doValidation({rule:'general',prop:{uppercase:false,autosize:true}});
        $(".autosizealph").doValidation({rule:'alphabets',prop:{whitespace:true,autosize:true}});
        $("#URSRC_ENTRY_tb_mobile").doValidation({rule:'numbersonly',prop:{realpart:10,leadzero:true}});
        $("#URSRC_ENTRY_tb_permobile").doValidation({rule:'numbersonly',prop:{realpart:10,leadzero:true}});
        //emp
        $(".pstlcode").doValidation({rule:'numbersonly',prop:{realpart:6,leadzero:true}});
        $(".mobileno").doValidation({rule:'numbersonly',prop:{realpart:10,leadzero:true}});
        $(".accntno").doValidation({rule:'numbersonly',prop:{leadzero:true}});
        $(".alphanumeric").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:false,autosize:true}});
        $(".alphanumericuppercse").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
        //DO VALIDATION END
        //BASIC ROLE MENU CREATION CLICK FUNCTION
        $(document).on('click','#URSRC_radio_basicrolemenucreation',function(){
            flag=0;
//        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
            $('#URSRC_lbl_header').text("BASIC ROLE MENU CREATION").show();
            $('#URSRC_tble_basicroles').show();
            $('#URSRC_workfromhome').hide();
            $('#URSRC_lbl_nodetails_err').hide();
            $('#URSRC_tble_basicrolemenucreation').show();
            $('#URSRC_lbl_login_role').hide();
            $('#URSRC_lbl_joindate').hide().val("");
            $('#URSRC_tb_joindate').hide().val("");
            $('#URSRC_tb_loginid').hide();
            $('#URSRC_lbl_loginid').hide();
            $('#URSRC_btn_submitbutton').val("CREATE").hide()
            $('#URSRC_tble_rolesearch').hide();
            $('#URSRC_btn_login_submitbutton').attr("disabled","disabled").hide();
            $('#URSRC_tb_customrole').val("");
            $('#URSRC_tble_rolecreation tr').remove().hide();
            $('#URSRC_tble_role').hide();
            $('#URSRC_tble_login').hide();
            $('#URSRC_lbl_basicrole_err').hide()
            $('#URSRC_tble_menu').hide();
            $('#URSRC_tble_folder').hide();
            $('#URSRC_tble_roles').hide()
            $('#URSRC_tble_basicroles_chk ').hide()
            $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
            $('input[name=URSRC_cb_basicroles1]').attr('checked',false);
            $('#URSRC_table_employeetbl').hide();
            $('#URSRC_table_others').hide();
            $('#URSRC_submitupdate').hide();
            $('#URSRC_lbl_role_err').hide();
            $('#URSRC_lbl_validnumber').hide();
            $('#URSRC_lbl_validnumber1').hide();
            $('#URSRC_lbl_validnumberhouseno').hide();
            $("#filetableuploads div").remove();
            $('#attachafile').text('Attach a file');
        });
        //BASIC ROLE MENU SEARCH/UPDATE CLICK FUNCTION
        $('#URSRC_radio_basicrolemenusearchupdate').click(function(){
            flag=0;
//        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
            $('#URSRC_lbl_header').text("BASIC ROLE MENU SEARCH UPDATE").show()
            $('#URSRC_lbl_login_role').hide();
            $('#URSRC_workfromhome').hide();
            $('#URSRC_rolesearch_roles > div').remove()
            $('#URSRC_lbl_joindate').hide().val("");
            $('#URSRC_lbl_nodetails_err').hide()
            $('#URSRC_tb_joindate').hide().val("");
            $('#URSRC_lbl_basicrole_err').hide()
            $('#URSRC_tb_loginid').hide();
            $('#URSRC_lbl_loginid').hide();
            $('#URSRC_lbl_basicrole_err').hide()
            $('#URSRC_btn_submitbutton').val("UPDATE").hide()
            $('#URSRC_tble_rolesearch').hide();
            $('#URSRC_btn_login_submitbutton').attr("disabled","disabled").hide();
            $('#URSRC_tb_customrole').val("");
            $('#URSRC_tble_rolecreation tr').remove().hide();
            $('#URSRC_tble_role').hide();
            $('#URSRC_tble_roles').hide()
            $('#URSRC_tble_login').hide();
            $('#URSRC_tble_menu').hide();
            $('#URSRC_tble_folder').hide();
            $('#URSRC_tble_basicroles').show();
            $('#URSRC_tble_basicrolemenucreation').show();
            $('#URSRC_tble_basicroles_chk ').hide()
            $('input[name=URSRC_cb_basicroles1]').prop('checked',false);
            $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
            $('#URSRC_table_employeetbl').hide();
            $('#URSRC_table_others').hide();
            $('#URSRC_submitupdate').hide();
            $('#URSRC_lbl_role_err').hide();
            $('#URSRC_lbl_validnumber').hide();
            $('#URSRC_lbl_validnumber1').hide();
            $('#URSRC_lbl_validnumberhouseno').hide();
            $("#filetableuploads div").remove();
            $('#attachafile').text('Attach a file');
        });
        //ROLE CREATION CLICK FUNCTION
        $(document).on('click','#URSRC_radio_rolecreation',function(){
            flag=0;
//        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
            // $('#joindate').hide();
            $('#URSRC_workfromhome').hide();
            $('#URSRC_lbl_joindate').hide();
            $('#URSRC_lb_rolename').hide();
            $('#URSRC_lbl_selectrole').hide();
            $('#URSRC_tb_joindate').hide();
            $('#URSRC_tble_roles').hide();
            $('#URSRC_rolesearch_roles').hide();
            $('#URSRC_tble_basicroles').hide();
            $('#URSRC_lbl_header').text("ROLE CREATION").show()
            $('#URSRC_submitupdate').hide();
            $('#URSRC_rolesearch_roles > div').remove()
            $('#URSRC_tb_customrole').val('');
            $('#URSRC_tble_search').hide();
            $('#URSRC_tble_login').hide();
            $('#URSRC_lbl_nodetails_err').hide()
            $('#URSRC_tb_loginid').val("");
            $('#URSRC_tble_role').show();
            $('#URSRC_table_employeetbl').hide();
            $('#URSRC_tble_rolesearch').hide();
            $('#URSRC_tble_menu').hide();
            $('#URSRC_tble_folder').hide();
            $('#URSRC_tb_joindate').val("");
            $('#URSRC_lbl_basicrole_err').hide()
            $('#URSRC_btn_submitbutton').val("CREATE").hide();
            $('#URSRC_btn_login_submitbutton').hide();
            $('#URSRC_tble_basicroles').hide();
            $('#URSRC_tble_rolecreation tr').remove().hide();
            $('#URSRC_tble_basicrolemenucreation').hide();
            $('#URSRC_tble_basicroles_chk ').hide()
            $('input:[name=URSRC_cb_basicroles1]').prop('checked',false);
            $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
            $('#URSRC_table_employeetbl').hide();
            $('#URSRC_table_others').hide();
            $('#URSRC_lbl_validnumber').hide();
            $('#URSRC_lbl_validnumber1').hide();
            $('#URSRC_lbl_validnumberhouseno').hide();
            $("#filetableuploads div").remove();
            $('#attachafile').text('Attach a file');
        });
        var basicmenurolesresult=[];
        //WEHN BASIC ROLE CLICK IN BASIC MENU CREATION AND SEARCH/UPDATE FORM
        $(document).on("click",'.URSRC_class_basic', function (){
//        $('.preloader',window.parent.document).show();
            $(".preloader").show();
            $('#URSRC_btn_submitbutton').hide();
            $('#URSRC_lbl_selectrole').hide();
            $('#URSRC_tble_rolesearch').hide();
            $('#URSRC_lb_selectrole').hide();
//        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
            $('input[type=checkbox]').attr('checked', false);
            URSRC_basicradio_value=$(this).val();
            var role=$(this).val()
            role=role.replace("_"," ")
            //GOOGLE URSRC_check_basicrole
            var formElement = document.getElementById("URE_attendanceentry");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var msg_alert=xmlhttp.responseText;
                    if(msg_alert==1)
                    {
//                    $('.preloader',window.parent.document).hide();
                        $(".preloader").hide();
                        if($("input[name=URSRC_mainradiobutton]:checked").val()=="BASIC ROLE MENU CREATION"){
                            $('#URSRC_lbl_basicrole_err').hide();
                            $('#URSRC_tble_basicroles_chk').show();
                            //GOOGLE URSRC_loadmenu_basicrole(basic_role_menus)
                            URSRC_loadmenu_basicrole()
                        }
                        else{
//                        $('.preloader',window.parent.document).hide();
                            $(".preloader").hide();
                            var msg=URSRC_errorAarray[16].toString().replace("[NAME]",$("input[name=URSRC_radio_basicroles1]:checked").val())
                            $('#URSRC_lbl_basicrole_err').text(msg).show();
                            $('#URSRC_tble_basicroles_chk').hide()
                            $('#URSRC_tble_menu').hide();
                            $('#URSRC_tble_folder').hide();
                            $('#URSRC_btn_submitbutton').attr("disabled","disabled").hide()
                        }
                    }
                    else{
                        if($("input[name=URSRC_mainradiobutton]:checked").val()=="BASIC ROLE MENU CREATION")
                        {
                            $('#URSRC_lbl_basicrole_err').text(URSRC_errorAarray[13]).show()
//                        $('.preloader',window.parent.document).hide();
                            $(".preloader").hide();
                            $('#URSRC_tble_basicroles_chk').hide()
                            $('#URSRC_tble_menu').hide();
                            $('#URSRC_tble_folder').hide();
                        }
                        else{
                            $('input[name=URSRC_cb_basicroles1]').attr('checked',false);
                            var role=URSRC_basicradio_value
                            role=role.replace("_"," ")
                            $('#URSRC_lbl_basicrole_err').hide()
                            URSRC_loadbasicrole_menus(role)
                        }
                    }
                }
            }
            var choice='URSRC_check_basicrolemenu';
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?URSRC_basicradio_value="+URSRC_basicradio_value+"&option="+choice,true);
            xmlhttp.send(new FormData(formElement));
        });
        //BASIC ROLE CREATION FOR TRUE/FALSE
        function URSRC_loadmenu_basicrole()
        {
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    $('#URSRC_tble_basicroles_chk').show();
                    URSRC_tree_view(values_array,'')
                }
            }
            var choice="URSRC_tree_view"
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?radio_value="+URSRC_basicradio_value+"&option="+choice,true);
            xmlhttp.send();
        }
        var basicmenurolesresult=[];
        //SUCCESS FUNCTION FOR BASIC ROLE MENUS
        function URSRC_loadbasicrole_menus(role)
        {
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    basicmenurolesresult=JSON.parse(xmlhttp.responseText);
                    var URSRC_full_array=basicmenurolesresult[2];
                    var URSRC_checked_mpid=basicmenurolesresult[0];
                    var URSRC_basicrole_profile=basicmenurolesresult[1];
                    //Funcion to load selected basic menu and roles for basic menu
                    for(var j=0;j<URSRC_basicrole_profile_array.length;j++){
                        for(var i=0;i<URSRC_basicrole_profile.length;i++){
                            if(URSRC_basicrole_profile[i]==URSRC_basicrole_profile_array[j]){
                                var checkbox=URSRC_basicrole_profile[i].replace(" ","_")
                                $("#" + checkbox).prop( "checked", true );
                            }
                        }
                    }
                    $('#URSRC_tble_basicroles_chk').show()
                    $('#URSRC_tble_basicrolemenusearch').show()
                    URSRC_tree_view(URSRC_full_array,URSRC_checked_mpid);
                }
            }
            var choice="URSRC_loadbasicrole_menu"
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?URSRC_basicradio_value="+URSRC_basicradio_value+"&option="+choice,true);
            xmlhttp.send();
        }
        //CUSTOM ROLE CHANGE FUNCTION
        //FUNCTION TO CHECK CUSTOM ROLE ALREADY EXISTS
        $(document).on('blur','#URSRC_tb_customrole',function(){
            var URSRC_roleidval=$(this).val();
            if(URSRC_roleidval!=''){
//            $('.preloader',window.parent.document).show();
                $(".preloader").show();
                $('#URSRC_tble_roles').hide()
                $('#URSRC_tble_menu').hide();
                $('#URSRC_tble_folder').hide();
                $('input:radio[name=basicroles]').attr('checked',false);
                $('#URSRC_btn_submitbutton').hide()
                $('#URSRC_lbl_role_err').hide();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        var msgalert=JSON.parse(xmlhttp.responseText);//
                        if(msgalert==0)
                        {
                            $('#URSRC_tble_roles').show();
                            $('#URSRC_lbl_role_err').hide()
                        }
                        else{
                            var msg=URSRC_errorAarray[5].replace('[NAME]',$('#URSRC_tb_customrole').val())
                            $('#URSRC_btn_submitbutton').attr("disabled","disabled")
                            $('#URSRC_lbl_role_err').text(msg).show()
                        }
                    }
                }
                var choice='URSRC_check_role_id';
                xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?URSRC_roleidval="+URSRC_roleidval+"&option="+choice,true);
                xmlhttp.send();
            }
        });
        //LOGIN CREATION CLICK FUNCTION
        $('#URSRC_radio_logincreation').click(function(){
            $('#URSRC_workfromhome').hide();
            $('#attachprompt').show();
            $('#URSRC_tble_rolecreation').empty();
            $('#URSRC_rolesearch_roles > div').remove()
//        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
            flag=0;
            exist_flag=1;
            button_vflag=1;
            error_valid='valid';
            error_ext='valid';
            $('#URSRC_lbl_header').text("LOGIN CREATION").show()
            $('#URSRC_tb_customrole').val("");
            $('#URSRC_lbl_nodetails_err').hide()
            $('#URSRC_btn_submitbutton').val("UPDATE").hide()
            $("#URSRC_btn_update").html('')
            $('#URSRC_tble_rolesearch').hide();
            $('#URSRC_lbl_emptype').hide();
            $('#URSRC_lbl_selectloginid').hide();
            $('#URSRC_lbl_loginidupd').hide();
            $('#URSRC_tb_loginidupd').hide();
            $('#URSRC_lb_selectemptype').prop('selectedIndex',0).hide();
            $('#URSRC_lbl_login_role').hide();
            $('#URSRC_lbl_joindate').hide();
            $('#URSRC_lbl_loginid').show();
            $('#URSRC_tb_joindate').hide();
            $('#URSRC_lbl_basicrole_err').hide()
            $('#URSRC_lb_selectloginid').hide();
            $('input:radio[name=basicroles]').attr('checked',false);
            $('#URSRC_tble_role').hide()
            $('#URSRC_tble_roles').hide()
            $('#URSRC_tble_menu').hide();
            $('#URSRC_tble_folder').hide();
            $('#URSRC_tble_login').show();
            $('#URSRC_tb_loginid').val('').show();
            $("#URSRC_lbl_email_err").hide()
            $("#URSRC_lbl_email_errupd").hide()
            $('#URSRC_tble_rolesearch').hide();
            $('#URSRC_tb_loginidupd').removeClass("invalid")
            $('#URSRC_tble_rolecreation div').remove().hide();
            $('#URSRC_tb_loginid').removeClass("invalid")
            $('#URSRC_lbl_nologin_err').hide()
            $('#URSRC_btn_login_submitbutton').attr("disabled","disabled").hide();
            $('#URSRC_tble_basicroles').hide();
            $('#URSRC_tble_basicrolemenucreation').hide();
            $('#URSRC_tble_basicroles_chk ').hide()
            $('input[name=URSRC_cb_basicroles1]').attr('checked',false);
            $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
            var PE_startdate=(URSRC_comp_sdate).split('-');
            var day=PE_startdate[0];
            var month=PE_startdate[1];
            var year=PE_startdate[2];
            PE_startdate=new Date(year,month-1,day);
            $('#URSRC_tb_joindate').datepicker("option","minDate",PE_startdate);
            $('#URSRC_tb_joindate').val('');
            $('#URSRC_table_employeetbl').hide();
            $('#URSRC_table_others').hide();
            $('#URSRC_tb_firstname').val('');
            $('#URSRC_tb_lastname').val('');
            $('#URSRC_tb_dob').val('');
            $('#URSRC_tb_designation').val('');
            $('#URSRC_tb_permobile').val('');
            $('#URSRC_tb_kinname').val('');
            $('#URSRC_tb_relationhd').val('');
            $('#URSRC_tb_mobile').val('');
            $('#URSRC_tb_houseno').val('');
            $('#URSRC_tb_strtname').val('');
            $('#URSRC_tb_area').val('');
            $('#URSRC_tb_pstlcode').val('');
            $('#URSRC_tb_loginid').val('');
            $('#URSRC_tb_bnkname').val('');
            $('#URSRC_tb_brnchname').val('');
            $('#URSRC_tb_accntname').val('');
            $('#URSRC_tb_accntno').val('');
            $('#URSRC_tb_ifsccode').val('');
            $('#URSRC_tb_accntyp').val('');
            $('#URSRC_ta_brnchaddr').val('');
            $('#URSRC_tb_laptopno').val('');
            $('#URSRC_tb_chargerno').val('');
            $('#URSRC_tb_btry').val('');
            $('#URSRC_chk_bag').attr('checked',false);
            $('#URSRC_chk_mouse').attr('checked',false);
            $('#URSRC_chk_btry').attr('checked',false);
            $('#URSRC_chk_dracess').attr('checked',false);
            $('#URSRC_chk_idcrd').attr('checked',false);
            $('#URSRC_chk_headset').attr('checked',false);
            $('#URSRC_chk_aadharno').attr('checked',false);
            $('#URSRC_chk_passportno').attr('checked',false);
            $('#URSRC_chk_votersid').attr('checked',false);
            $('#URSRC_tb_aadharno').val('').hide();
            $('#URSRC_tb_laptopno').val('').hide();
            $('#URSRC_tb_btry').val('').hide();
            $('#URSRC_tb_mouse').val('').hide();
            $('#URSRC_tb_passportno').val('').hide();
            $('#URSRC_tb_votersid').val('').hide();
            $('#URSRC_ta_comments').val('');
            $('#URSRC_lbl_role_err').hide();
            $('#URSRC_lbl_validnumber').hide();
            $('#URSRC_lbl_validnumber1').hide();
            $('#URSRC_lbl_validnumberhouseno').hide();
            $("#filetableuploads div").remove();
            $('#attachafile').text('Attach a file');
        });
        var error_valid='valid';
        var error_ext='valid';
        var flag=0;
        var exist_flag=1;
        $('.URSRC_email_validate').blur(function(){
            var URSRC_radio_button_select_value=$("input[name=URSRC_mainradiobutton]:checked").val();
            var URSRC_login_id=$(this).val().toLowerCase();
            var old_loginid=$('#URSRC_lb_selectloginid').val();
            if(URSRC_radio_button_select_value=='LOGIN SEARCH UPDATE'){

                if(old_loginid!=URSRC_login_id){

                    URSRC_login_validate(URSRC_login_id);
                }
                else{
                    error_valid='valid';
                    exist_flag=1;
                    $("#URSRC_lbl_email_errupd").hide();
                    $('#URSRC_tb_loginidupd').removeClass("invalid")
                    $('#URSRC_lbl_email_errupd').hide();
                    error_ext='valid';
                    var newloginid=($('#URSRC_tb_loginidupd').val().toLowerCase());
                    $('#URSRC_tb_loginidupd').val(newloginid)
                    loginbuttonvalidation();
                }
            }
            else{
                URSRC_login_validate(URSRC_login_id);
            }
        });
        function URSRC_login_validate(URSRC_login_id){
            $('#URSRC_tble_rolecreation').hide();
            var URSRC_radio_button_select_value=$("input[name=URSRC_mainradiobutton]:checked").val();
            $('.URSRC_resizefunction').prop("size","20");
            var URSRC_login_id=URSRC_login_id;//$(this).val();
            var atpos=URSRC_login_id.indexOf("@");
            var dotpos=URSRC_login_id.lastIndexOf(".");
            if(URSRC_login_id.length>0)
            {
                if ((atpos<1 || dotpos<atpos+2 || dotpos+2>=URSRC_login_id.length)||(/^[@a-zA-Z0-9-\\.]*$/.test(URSRC_login_id) == false))
                {

                    error_valid='invalid';
                    $(".preloader").hide();
                    if(URSRC_radio_button_select_value=="LOGIN CREATION"){
                        $("#URSRC_lbl_email_err").text(URSRC_errorAarray[2]).show();
                        $('#URSRC_tb_loginid').addClass("invalid")
                    }
                    else{
                        error_valid='invalid';
                        $('#URSRC_submitupdate').show().attr("disabled","disabled");
                        $("#URSRC_lbl_email_errupd").text(URSRC_errorAarray[2]).show();
                        $('#URSRC_tb_loginidupd').addClass("invalid")
                    }
                }
                else
                {
                    error_valid='valid';
//                $('.preloader',window.parent.document).show();
                    $(".preloader").show();
                    if(URSRC_radio_button_select_value=="LOGIN CREATION"){
                        $("#URSRC_lbl_email_err").hide();
                        $('#URSRC_tb_loginid').removeClass("invalid")
                        $('#URSRC_tb_loginid').val($('#URSRC_tb_loginid').val().toLowerCase())
                        URSRC_login_id=$('#URSRC_tb_loginid').val();
                    }
                    else{
                        error_valid='valid';
                        $("#URSRC_lbl_email_errupd").hide();
                        $('#URSRC_tb_loginidupd').removeClass("invalid")
                        $('#URSRC_tb_loginidupd').val($('#URSRC_tb_loginidupd').val().toLowerCase())
                        URSRC_login_id=$('#URSRC_tb_loginidupd').val();
                    }
                    URSRC_login_id=URSRC_login_id;//$(this).val();
                    if((URSRC_login_id.substring(URSRC_login_id.indexOf("@") + 1) == "ssomens.com")||(URSRC_login_id.substring(URSRC_login_id.indexOf("@") + 1) == "gmail.com"))
                    {
                        error_ext='valid';
                        var xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                           alert(xmlhttp.responseText);
                                $(".preloader").hide();
                                var msgalert=JSON.parse(xmlhttp.responseText);
                                var LoginId_exist=msgalert[0];
                                var URSRC_role_array=msgalert[1];
                                var URSRC=msgalert[2];
                                var URSRC_LAP=msgalert[3];
//                            var URSRC_CHR=msgalert[4];
                                var URSRC_des=msgalert[4];
                                var URSRC_ACC=msgalert[5];
                                if(LoginId_exist==0)
                                {
                                    exist_flag=1;
                                    if(URSRC_radio_button_select_value=="LOGIN CREATION")
                                    {
                                        if(flag==0){
                                            $('#URSRC_tble_rolecreation tr').remove().hide();
                                            var URSRC_roles=''
                                            for (var i = 0; i < URSRC_role_array.length; i++){
                                                var value=URSRC_role_array[i].replace(" ","_")
                                                var id1="URSRC_role_array"+i;
                                                if(i==0){
                                                    var URSRC_roles='<div class="col-lg-2  control-label"><label >SELECT ROLE ACCESS<em>*</em></label></div>'
                                                    URSRC_roles+= '<div class="col-lg-10" ><div class="radio"><label><input type="radio"  name="roles1" id='+id1+' value='+value+' class="URSRC_class_role1 tree login_submitvalidate"   />' + URSRC_role_array[i] + '</label></div></div>';
                                                    $('#URSRC_tble_rolecreation').append(URSRC_roles);
                                                }
                                                else{
                                                    URSRC_roles= '<div class="col-sm-offset-2 col-lg-10"><div class="radio"><label><input type="radio" name="roles1" id='+id1+' value='+value+' class="URSRC_class_role1 tree login_submitvalidate"   />' + URSRC_role_array[i] + '</label></div></div>';
                                                    $('#URSRC_tble_rolecreation').append(URSRC_roles);
                                                }
                                            }
                                        }
                                        $('#URSRC_lbl_login_role').show();
                                        $('#URSRC_tble_rolecreation').show();
                                        $('#URSRC_lbl_joindate').show();
                                        $('#URSRC_tb_joindate').show();
                                        $('#URSRC_lbl_emptype').show();
                                        $('#URSRC_lb_selectemptype').show();
                                        $('#URSRC_table_employeetbl').show();
                                        $('#URSRC_table_others').show();
                                        $('#URSRC_tb_btry').show();
                                        $('#URSRC_btn_login_submitbutton').val("CREATE").show();
                                        if(URSRC.length!=0)
                                        {
                                            var URSRC_array='<option value="SELECT">SELECT</option>';
                                            for(var k=0;k<URSRC.length;k++){
                                                URSRC_array += '<option value="'+URSRC[k]+'">'+ URSRC[k]+'</option>';
                                            }
                                            $('#URSRC_lb_selectrelationhd').html(URSRC_array);
                                        }
                                        else
                                        {
//                                alert('no data aviable in relationhood ')
                                        }
                                        if(URSRC_LAP!='')
                                        {
                                            var  URSRC_LAP_array='<option value="SELECT">SELECT</option>';
                                            for(var k=0;k< URSRC_LAP.length;k++){
                                                URSRC_LAP_array += '<option value="'+ URSRC_LAP[k]+'">'+  URSRC_LAP[k]+'</option>';
                                            }
                                            $('#URSRC_lb_selectlaptopno').html( URSRC_LAP_array);
                                        }
                                        else
                                        {
                                            $('#URSRC_lb_selectlaptopno').hide();
                                            $('#URSRC_lbl_nolaptop').text(URSRC_errorAarray[35]).show();
//                                alert('no data aviable in laptopno')
                                        }
                                        if(URSRC_des.length!=0)
                                        {
                                            var URSRC_des_array='<option value="SELECT">SELECT</option>';
                                            for(var k=0;k<URSRC_des.length;k++){
                                                URSRC_des_array += '<option value="'+URSRC_des[k]+'">'+ URSRC_des[k]+'</option>';
                                            }
                                            $('#URSRC_tb_designation').html(URSRC_des_array);
                                        }
                                        else
                                        {
//                                alert('no data avilable in desgn ')
                                        }

                                        if(URSRC_ACC.length!=0)
                                        {
                                            var URSRC_ACC_array='<option value="SELECT">SELECT</option>';
                                            for(var k=0;k<URSRC_ACC.length;k++){
                                                URSRC_ACC_array += '<option value="'+URSRC_ACC[k]+'">'+ URSRC_ACC[k]+'</option>';
                                            }
                                            $('#URSRC_tb_accntyp').html(URSRC_ACC_array);
                                        }
                                        else
                                        {
//                                alert('no data aviable in account ')
                                        }
                                    }
                                    else{
                                        $('#URSRC_lbl_login_role').show();
                                        $('#URSRC_tble_rolecreation').show();
                                        $('#URSRC_lbl_emptype').show();
                                        $('#URSRC_lbl_joindate').show();
                                        $('#URSRC_tb_joindate').show();
                                        $('#URSRC_lb_selectemptype').show();
                                        $('#URSRC_submitupdate').show()
                                    }
                                    flag++;
                                }
                                else{
                                    exist_flag=0;
                                    var msg=URSRC_errorAarray[10].toString().replace("[NAME]",$('#URSRC_tb_loginid').val())
                                    if(URSRC_radio_button_select_value=="LOGIN CREATION"){
                                        $('#URSRC_lbl_email_err').text(msg).show();
                                    }
                                    else{
                                        var msg=URSRC_errorAarray[10].toString().replace("[NAME]",$('#URSRC_tb_loginidupd').val())
                                        $('#URSRC_lbl_email_errupd').text(msg).show();
                                        $('#URSRC_submitupdate').attr("disabled","disabled").show();
                                    }

                                }
                                loginbuttonvalidation();
                            }
                        }
                        var choice='check_login_id';
                        xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?URSRC_login_id="+URSRC_login_id+"&option="+choice,true);
                        xmlhttp.send();
                    }
                    else{
                        error_ext='invalid';
//                    $('.preloader',window.parent.document).hide();
                        $(".preloader").hide();
                        if(URSRC_radio_button_select_value=="LOGIN CREATION"){
                            $('#URSRC_tble_rolecreation').show()
                            $('#URSRC_lbl_email_err').text(URSRC_errorAarray[17]).show()
                            $('#URSRC_tb_loginid').addClass("invalid");
//                        loginbuttonvalidation();
                        }
                        else{
//                        $('#URSRC_submitupdate').hide();
                            $('#URSRC_lbl_email_errupd').text(URSRC_errorAarray[17]).show()
                            $('#URSRC_tb_loginidupd').addClass("invalid");
                        }
                    }
                }
                loginbuttonvalidation();
            }
            else{
                $(".preloader").hide();
                if(URSRC_radio_button_select_value=="LOGIN CREATION"){
                    alert('sad')
                    $('#URSRC_tble_rolecreation tr').remove().hide();
                    $('#URSRC_tble_rolecreation').hide();
                    $('#URSRC_lbl_joindate').hide();
                    $('#URSRC_tb_joindate').val("").hide();
                    $("#URSRC_lbl_email_err").hide();
                    $('#URSRC_tb_loginid').removeClass("invalid")
                    $('#URSRC_lbl_emptype').hide();
                    $('#URSRC_table_employeetbl').hide();
                    $('#URSRC_table_others').hide();
                    $('#URSRC_lb_selectemptype').hide();
                    $('#URSRC_btn_login_submitbutton').hide();
//                    $('#URSRC_rolesearch_roles div').remove();
                    $('#URSRC_rolesearch_roles > div').remove();
                    flag=0;
                }
                else{
                    $('#URSRC_submitupdate').attr("disabled","disabled");

                }
            }
        }

        function loginbuttonvalidation(){
            var URSRC_radio_button_select_value=$("input[name=URSRC_mainradiobutton]:checked").val();
            if(URSRC_radio_button_select_value=="LOGIN CREATION")
            {
                var login_id=$('#URSRC_tb_loginid').val();
                var role_id=$("input[name=roles1]").is(":checked")
                var join_date=$('#URSRC_tb_joindate').val();
                var emp_type=$('#URSRC_lb_selectemptype').val();
                var URSRC_Firstname= $("#URSRC_tb_firstname").val();
                var URSRC_Lastname =$("#URSRC_tb_lastname").val();
                var URSRC_tb_dob=$('#URSRC_tb_dob').val();
                var URSRC_empdesig =$("#URSRC_tb_designation").val();
                var URSRC_Mobileno = $("#URSRC_tb_permobile").val();
                var URSRC_kinname = $("#URSRC_tb_kinname").val();
                var URSRC_relationhd = $("#URSRC_tb_relationhd").val();
                var URSRC_mobile= $("#URSRC_tb_mobile").val();
                var URSRC_Houseno=$("#URSC_tb_houseno").val();
                var  URSRC_Streetname=$("#URSC_tb_strtname").val();
                var URSRC_Area=$("#URSC_tb_area").val();
                var URSRC_Postalcode=$("#URSRC_tb_pstlcode").val();
                var URSRC_bnkname =$("#URSRC_tb_bnkname").val();
                var URSRC_tb_brnname=$('#URSRC_tb_brnchname').val();
                var URSRC_accname =$("#URSRC_tb_accntname").val();
                var URSRC_acctno = $("#URSRC_tb_accntno").val();
                var URSRC_ifsc = $("#URSRC_tb_ifsccode").val();
                var URSRC_accttyp = $("#URSRC_tb_accntyp").val();
                var URSRC_brnchaddr= $("#URSRC_ta_brnchaddr").val();
                var URSRC_aadharno=$('#URSRC_tb_aadharno').val();
                var URSRC_passportno=$('#URSRC_tb_passportno').val();
                var URSRC_voterid=$('#URSRC_tb_votersid').val();
                var URSRC_designation=$('#URSRC_tb_designation').val();
                var URSRC_relationhood=$('#URSRC_lb_selectrelationhd').val();
                var URSRC_accttype=$('#URSRC_tb_accntyp').val();
                var URSRC_postalcode=$('#URSRC_tb_pstlcode').val();
                var URSRC_street=$('#URSRC_tb_strtname').val();
                var URSRC_area=$('#URSRC_tb_area').val();
                var URSRC_houseno=$('#URSRC_tb_houseno').val();
                var URSRC_mouseno=$('#URSRC_tb_mouse').val();
                var URSRC_laptopno=$('#URSRC_tb_laptopno').val();
                if(button_vflag==1&&(login_id!="")&&(exist_flag==1)&&(error_ext=='valid')&&(error_valid=='valid')&&(role_id!=false)&&(join_date!="")&& (emp_type!="SELECT")&& (URSRC_Firstname!='') && (URSRC_Lastname!='' ) && (URSRC_tb_dob!='' ) && (URSRC_empdesig!='' )&&( URSRC_Mobileno!='' && (parseInt($('#URSRC_tb_permobile').val())!=0)) && (URSRC_kinname!='')&& (URSRC_relationhd!='' )&& (URSRC_Mobileno.length>=10)&&(URSRC_mobile.length>=10 )&&(URSRC_brnchaddr!="")&&(URSRC_accttyp!="")&&(URSRC_ifsc!="")&&(URSRC_acctno!="")&&(URSRC_accname!="")&&(URSRC_tb_brnname!="")&&(URSRC_bnkname!="") &&(URSRC_designation!='SELECT')&&(URSRC_relationhood!='SELECT')&&(URSRC_accttype!='SELECT')&&(URSRC_postalcode!='')&&(URSRC_street!='')&&(URSRC_area!='')&&(URSRC_houseno!=''))
                {
                    $("#URSRC_btn_login_submitbutton").removeAttr("disabled")
//                $('#attachprompt').show();

                    if(($("input[name=URSRC_chk_aadharno]").is(":checked")==true)||($("input[name=URSRC_chk_passportno]").is(":checked")==true)||($("input[name=URSRC_chk_votersid]").is(":checked")==true) ||($("input[name=URSRC_chk_mouse]").is(":checked")==true)||($("input[name=URSRC_chk_bag]").is(":checked")==true))
                    {
                        if((URSRC_aadharno=='' && $("input[name=URSRC_chk_aadharno]").is(":checked")==true) ||(URSRC_passportno=='' && $("input[name=URSRC_chk_passportno]").is(":checked")==true)||(URSRC_voterid=='' && $("input[name=URSRC_chk_votersid]").is(":checked")==true) ||(URSRC_mouseno=='' && $("input[name=URSRC_chk_mouse]").is(":checked")==true)||(URSRC_laptopno=='' && $("input[name=URSRC_chk_bag]").is(":checked")==true))
                            $("#URSRC_btn_login_submitbutton").attr("disabled", "disabled");
                        else
                            $("#URSRC_btn_login_submitbutton").removeAttr("disabled");
                    }
                }
                else{
                    $('#URSRC_btn_login_submitbutton').attr("disabled","disabled");
                }
            }
            else
            {
                var login_id=$('#URSRC_lb_selectloginid').val();
                var role_id=$("input[name=roles1]").is(":checked")
                var updatedloginid=$('#URSRC_tb_loginidupd').val();
                var join_date=$('#URSRC_tb_joindate').val();
                var emp_type=$('#URSRC_lb_selectemptype').val();
                var URSRC_Firstname= $("#URSRC_tb_firstname").val();
                var URSRC_Lastname =$("#URSRC_tb_lastname").val();
                var URSRC_tb_dob=$('#URSRC_tb_dob').val();
                var URSRC_empdesig =$("#URSRC_tb_designation").val();
                var URSRC_Mobileno = $("#URSRC_tb_permobile").val();
                var URSRC_kinname = $("#URSRC_tb_kinname").val();
                var URSRC_relationhd = $("#URSRC_tb_relationhd").val();
                var URSRC_mobile= $("#URSRC_tb_mobile").val();
                var URSRC_Houseno=$("#URSC_tb_houseno").val();
                var  URSRC_Streetname=$("#URSC_tb_strtname").val();
                var URSRC_Area=$("#URSC_tb_area").val();
                var URSRC_Postalcode=$("#URSRC_tb_pstlcode").val();
                var URSRC_bnkname =$("#URSRC_tb_bnkname").val();
                var URSRC_tb_brnname=$('#URSRC_tb_brnchname').val();
                var URSRC_accname =$("#URSRC_tb_accntname").val();
                var URSRC_acctno = $("#URSRC_tb_accntno").val();
                var URSRC_ifsc = $("#URSRC_tb_ifsccode").val();
                var URSRC_accttyp = $("#URSRC_tb_accntyp").val();
                var URSRC_brnchaddr= $("#URSRC_ta_brnchaddr").val();
                var URSRC_aadharno=$('#URSRC_tb_aadharno').val();
                var URSRC_passportno=$('#URSRC_tb_passportno').val();
                var URSRC_voterid=$('#URSRC_tb_votersid').val();
                var URSRC_designation=$('#URSRC_tb_designation').val();
                var URSRC_relationhood=$('#URSRC_lb_selectrelationhd').val();
                var URSRC_accttype=$('#URSRC_tb_accntyp').val();
                var URSRC_postalcode=$('#URSRC_tb_pstlcode').val();
                var URSRC_street=$('#URSRC_tb_strtname').val();
                var URSRC_area=$('#URSRC_tb_area').val();
                var URSRC_houseno=$('#URSRC_tb_houseno').val();
                var URSRC_laptopno=$('#URSRC_tb_laptopno').val();
                var URSRC_mouseno=$('#URSRC_tb_mouse').val();
                if(button_vflag==1&&(login_id!="SELECT")&&(exist_flag==1)&&(error_ext=='valid')&&(error_valid=='valid')&&(updatedloginid!='')&&(role_id!=false)&&(join_date!="")&& (emp_type!="SELECT")&& (URSRC_Firstname!='') && (URSRC_Lastname!='' ) && (URSRC_tb_dob!='' ) && (URSRC_empdesig!='' )&&( URSRC_Mobileno!='' && (parseInt($('#URSRC_tb_permobile').val())!=0)) && (URSRC_kinname!='')&& (URSRC_relationhd!='' )&& (URSRC_Mobileno.length>=10)&&(URSRC_mobile.length>=10 )&&(URSRC_brnchaddr!="")&&(URSRC_accttyp!="")&&(URSRC_ifsc!="")&&(URSRC_acctno!="")&&(URSRC_accname!="")&&(URSRC_tb_brnname!="")&&(URSRC_bnkname!="") &&($('#URSRC_tb_houseno').val()!='')&&($('#URSRC_tb_strtname').val()!='')&&($('#URSRC_tb_area').val()!='')&&($('#URSRC_tb_pstlcode').val()!='')&&(URSRC_designation!='SELECT')&&(URSRC_relationhood!='SELECT')&&(URSRC_accttype!='SELECT')){
                    $("#URSRC_submitupdate").removeAttr("disabled")
//                $('#attachprompt').show();
                    if(($("input[name=URSRC_chk_aadharno]").is(":checked")==true)||($("input[name=URSRC_chk_passportno]").is(":checked")==true)||($("input[name=URSRC_chk_votersid]").is(":checked")==true)||($("input[name=URSRC_chk_bag]").is(":checked")==true)||($("input[name=URSRC_chk_mouse]").is(":checked")==true))
                    {
                        if((URSRC_aadharno=='' && $("input[name=URSRC_chk_aadharno]").is(":checked")==true) ||(URSRC_passportno=='' && $("input[name=URSRC_chk_passportno]").is(":checked")==true)||(URSRC_voterid=='' && $("input[name=URSRC_chk_votersid]").is(":checked")==true)||(URSRC_laptopno=='' && $("input[name=URSRC_chk_bag]").is(":checked")==true)||(URSRC_mouseno=='' && $("input[name=URSRC_chk_mouse]").is(":checked")==true))
                            $("#URSRC_submitupdate").attr("disabled", "disabled");
                        else
                            $("#URSRC_submitupdate").removeAttr("disabled");
                    }
                }
                else{
                    $('#URSRC_submitupdate').attr("disabled","disabled");
                }
            }
        }

        //Login Submit button validation
        $(document).on("blur change",'.login_submitvalidate', function (){
            loginbuttonvalidation();
        });
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
                    $('#URSRC_lbl_validnumber').text(URSRC_errorAarray[24]).show();
                else
                    $('#URSRC_lbl_validnumber1').text(URSRC_errorAarray[24]).show();
            }
        });
        //BLUR FUNCTION FOR MOBILE NUMBER VALIDATION
        $(document).on('blur','.validhouseno',function(){
            $('#URSRC_lbl_validnumber').hide();
            $('#URSRC_lbl_validnumber1').hide();
            var URSRC_houseno=$(this).attr("id");
            var URSRC_housenoval=$(this).val();
            if(URSRC_housenoval.length==15)
            {
                if(URSRC_houseno=='URSRC_tb_houseno')
                    $('#URSRC_lbl_validnumberhouseno').hide();
                else
                    $('#URSRC_lbl_validnumber1').hide();
            }
            else
            {
                if(URSRC_houseno=='URSRC_tb_houseno')
                    $('#URSRC_lbl_validnumberhouseno').text("HOUSE NO SHOULD BE 15 DIGITS").show();
                else
                    $('#URSRC_lbl_validnumber1').text(URSRC_errorAarray[24]).show();
            }
        });
        //LOGIN SEARCH/UPDATE CLICK FUNCTION
        var relation;
        var desgn;
        var laptop;
        var acc_type;
        $('#URSRC_radio_loginsearchupdate').click(function(){
            button_vflag=1;
            $('#URSRC_workfromhome').hide();
//            $('#URSRC_tble_main').hide();
//            $('#URSRC_tble_basicrolemenucreation').hide();
//            $('#URSRC_tble_basicroles').hide();
//            $('#URSRC_workfromhome').hide();
            $('#CONFIG_SRCH_UPD_tr_type').empty();
            var loinid_lap_val=$('#URSRC_lb_selectloginid').val();
            $('#URSRC_tble_rolecreation').hide();
            $('#URSRC_lbl_selectrole').hide();
            $('#URSRC_tble_rolesearch').hide();
            $('#URSRC_rolesearch_roles > div').remove();
            $('#URSRC_lb_selectrole').hide();
//        $('.preloader',window.parent.document).show();
            $(".preloader").show();
            flag=0;
            exist_flag=1;
            error_valid='valid';
            error_ext='valid';
            var radio_value_loginidsearch=$(this).val();
            $('#URSRC_lbl_header').text("LOGIN SEARCH/UPDATE").show()
            $('#URSRC_tb_loginid').val("");
            $('#URSRC_btn_login_submitbutton').val("UPDATE").hide();
            $('#URSRC_lbl_nodetails_err').hide()
            $('input:radio[name=basicroles]').attr('checked',false);
            $('#URSRC_tble_role').hide()
            $('#URSRC_tble_roles').hide()
            $('#URSRC_lbl_emptype').hide();
            $('#URSRC_lb_selectemptype').prop('selectedIndex',0).hide();
            $('#URSRC_btn_submitbutton').val("UPDATE").hide();
            $('#URSRC_lbl_login_role').hide();
            $('#URSRC_lbl_basicrole_err').hide()
            $('#URSRC_lbl_joindate').hide().val("");
            $('#URSRC_tb_joindate').hide().val("");
            $('#URSRC_tb_loginid').hide();
            $('#URSRC_lbl_loginid').hide();
            $('#URSRC_tb_loginidupd').hide();
            $('#URSRC_tble_rolesearch').hide();
            $('#URSRC_tb_loginid').removeClass("invalid")
            $('#URSRC_tb_loginidupd').removeClass("invalid")
            $('#URSRC_tble_menu').hide();
            $("#URSRC_lbl_email_err").hide()
            $("#URSRC_lbl_email_errupd").hide()
            $('#URSRC_lbl_loginidupd').hide();
            $('#URSRC_tble_folder').hide();
            $('#URSRC_submitupdate').hide();
            $('#URSRC_btn_login_submitbutton').attr("disabled","disabled").hide();
            $('#URSRC_tb_customrole').val("");
            $('#URSRC_tble_rolecreation div').remove().hide();
            $('#URSRC_tble_basicroles').hide();
            $('#URSRC_table_employeetbl').hide();
            $('#URSRC_table_others').hide();
            $('#URSRC_tble_basicrolemenucreation').hide();
            $('#URSRC_tble_basicroles_chk ').hide()
            $('input[name=URSRC_cb_basicroles1]').attr('checked',false);
            $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
            $('#URSRC_lbl_role_err').hide();
            $('#URSRC_lbl_validnumber').hide();
            $('#URSRC_lbl_validnumber1').hide();
            $('#URSRC_lbl_validnumberhouseno').hide();
            $("#filetableuploads div").remove();
            $('#attachafile').text('Attach a file');
            var PE_startdate=(URSRC_comp_sdate).split('-');
            var day=PE_startdate[0];
            var month=PE_startdate[1];
            var year=PE_startdate[2];
            PE_startdate=new Date(year,month-1,day);
            $('#URSRC_tb_joindate').datepicker("option","minDate",PE_startdate);
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
//              alert(JSON.stringify(xmlhttp.responseText+'stringfy'));
                    var loginid_array=JSON.parse(xmlhttp.responseText);
                    relation=loginid_array[1];
                    laptop=loginid_array[2];
                    desgn=loginid_array[3];
                    acc_type=loginid_array[4];
                    if(loginid_array[0].length!=0){
                        var URSRC_loginid_options='<option>SELECT</option>'
                        for(var l=0;l<loginid_array[0].length;l++){
                            URSRC_loginid_options+= '<option value="' + loginid_array[0][l][2] + '">' + loginid_array[0][l][0]+ '</option>';
                        }
                        $('#URSRC_lb_selectloginid').html(URSRC_loginid_options);

                        $('#URSRC_lb_selectloginid').show().prop('selectedIndex',0);
                        $('#URSRC_tble_login').show();
                        $('#URSRC_lbl_selectloginid').show();
                    }
                    else{
                        $('#URSRC_tble_login').show();
                        $('#URSRC_lbl_selectloginid').hide();
                        $('#URSRC_lb_selectloginid').hide()
                        $('#URSRC_lbl_nologin_err').text(URSRC_errorAarray[3]).show();
                    }
                    if(desgn.length!=0)
                    {
                        var des='<option>SELECT</option>'
                        for(var l=0;l<desgn.length;l++){
                            des+= '<option value="' + desgn[l] + '">' + desgn[l]+ '</option>';
                        }
                        $('#URSRC_tb_designation').html(des)
                    }
                    if(relation.length!=0)
                    {
                        var rel='<option>SELECT</option>'
                        for(var l=0;l<relation.length;l++){
                            rel+= '<option value="' + relation[l] + '">' + relation[l]+ '</option>';
                        }
                        $('#URSRC_lb_selectrelationhd').html(rel)
                    }
                    if(laptop.length!=0)
                    {
                        var ltp='<option>SELECT</option>'
                        for(var l=0;l<laptop.length;l++){
                            ltp+= '<option value="' + laptop[l]+ '">' + laptop[l]+ '</option>';
                        }
                        $('#URSRC_lb_selectlaptopno').html(ltp)
                    }
                    if(acc_type.length!=0)
                    {
                        var acc='<option>SELECT</option>'
                        for(var l=0;l<acc_type.length;l++){
                            acc+= '<option value="' + acc_type[l] + '">' + acc_type[l]+ '</option>';
                        }
                        $('#URSRC_tb_accntyp').html(acc)
                    }
                }
            }
            var choice="login_db";
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?loinid_lap_val="+loinid_lap_val+"&option="+choice,true);
            xmlhttp.send();
        });
        //LOGIN SEARCH ND UPDATE FOR LOGIN ID CHANGE FUNCTION
        $('#URSRC_lb_selectloginid').change(function(){
            $('#URSRC_lbl_nolaptop').hide();
//            $('#URSRC_tb_chargerno').val('');
            var loinid_lap_val=$('#URSRC_lb_selectloginid').val();
            $('#attachprompt').show();
            $("#filetableuploads div").remove();
            $("html, body").animate({ scrollTop: $(document).height() }, 1000);//worked
//        $('.preloader',window.parent.document).show();
            $(".preloader").show();
            exist_flag=1;
            error_valid='valid';
            error_ext='valid';
            $("#URSRC_btn_update").html('')
            var URSRC_login_id=$(this).val();
            var len=URSRC_login_id.length;
            $('#URSRC_lbl_email_errupd').hide();
            $('#URSRC_lbl_loginidupd').hide();
            $('#URSRC_tb_loginidupd').hide();
            $('#URSRC_tb_chargerno').val('');
            $('#URSRC_tb_btry').val('');
            $('#URSRC_tb_aadharno').val('');
            $('#URSRC_tb_passportno').val('');
            $('#URSRC_tb_votersid').val('');
            $('#URSRC_chk_passportno').attr('checked',false);
            $('#URSRC_tb_passportno').val('').hide();
            $('#URSRC_chk_votersid').attr('checked',false);
            $('#URSRC_chk_btry').attr('checked',false);
            $('#URSRC_chk_mouse').attr('checked',false);
            $('#URSRC_chk_bag').attr('checked',false);
            $('#URSRC_tb_votersid').val('').hide();
            $('#URSRC_tb_btry').val('');
            $('#URSRC_tb_laptopno').val('').hide();
            $('#URSRC_tb_mouse').val('').hide();
            $('#URSRC_ta_comments').val('').hide();
            if(URSRC_login_id!="SELECT"){
                $('#URSRC_tble_rolecreation').show();
                $('#URSRC_lbl_loginidupd').show();
                $('#URSRC_tb_loginidupd').val(URSRC_login_id).show().prop("size",len);
                $('#URSRC_tble_rolecreation tr').remove();
                $('#URSRC_btn_login_submitbutton').attr("disabled","disabled");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader',window.parent.document).hide();
                        $(".preloader").hide();
//                        alert(xmlhttp.responseText)
                        if(xmlhttp.responseText.match("Error:Folder id Not present")){
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:URSRC_errorAarray[32] ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[32],"success",false);
                            $('#URSRC_lbl_login_role').hide();
                            $('#URSRC_lbl_joindate').hide();
                            $('#URSRC_tb_joindate').hide();
                            $('#URSRC_lbl_emptype').hide();
                            $('#URSRC_lb_selectemptype').hide().prop('selectedIndex',0);
                            $('#URSRC_table_employeetbl').hide();
                            $('#URSRC_table_others').hide();
                            $('#URSRC_tb_loginidupd').hide();
                            $('#URSRC_tble_rolecreation').hide();
                            $('#URSRC_lbl_loginidupd').hide();
                        }
                        $('#URSRC_tble_rolecreation').empty();
                        var values_array=JSON.parse(xmlhttp.responseText);
                        for(var f=0;f<values_array[0][3].length;f++)
                        {
                            var tablerowCount = $('#filetableuploads div').length;
                            var uploadfileid="upload_filename"+tablerowCount;
                            var uploadfilename="filename"+tablerowCount;
//                            var appendfile='<div class="col-sm-offset-2 col-sm-10"><label class="inline"><input type="file" style="max-width:250px " class="fileextensionchk form-control" name='+uploadfileid+' id='+uploadfileid+'></label><label class="inline" ><button  class="removebutton" value="" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;"></button></label></div>';
                            var appendfile='<div class="col-sm-offset-6 col-sm-10"><input type=hidden name="uploadfilelist[]" value='+values_array[0][2][f]+'><div class="fileextensionchk"><a href='+values_array[0][4][f]+' target="_blank" class="uploadtag">'+values_array[0][3][f]+'</a></div><button type="button" class="removebutton" title="Remove this row" style="background-color:red;color:white;font-size:10;font-weight: bold;">Remove</button><label id="attach_error" hidden></label></div>';
                            $('#filetableuploads').append(appendfile);
                        }
                        if(values_array[0][2].length>0)
                        {
                            $('#attachafile').text('Attach another file');
                        }
                        else
                        {
                            $('#attachafile').text('Attach a file');
                        }
                        if(values_array[0][2].length==4)
                        {
                            $('#attachprompt').hide();
                        }
                        if(values_array[0][5].length!=0)
                        {
                            var ltp='<option>SELECT</option>'
                            for(var l=0;l<values_array[0][5].length;l++){
                                ltp+= '<option value="' + values_array[0][5][l]+ '">' + values_array[0][5][l]+ '</option>';
                            }
                            $('#URSRC_lb_selectlaptopno').html(ltp)
                        }
                        var join_date=values_array[0][0].joindate;
                        var rc_name=values_array[0][0].rcname;
                        var emp_type=values_array[0][0].emp_type;
                        var firstname=values_array[0][0].firstname;
                        var lastname=values_array[0][0].lastname;
                        var dob=values_array[0][0].dob;
                        var designation=values_array[0][0].designation;
                        var mobile=values_array[0][0].mobile;
                        var kinname=values_array[0][0].kinname;
                        var relationhood=values_array[0][0].relationhood;
                        var altmobile=values_array[0][0].altmobile;
                        var Houseno=values_array[0][0].Houseno;
                        var Streetname=values_array[0][0].Streetname;
                        var Area=values_array[0][0].Area;
                        var Postalcode=values_array[0][0].Postalcode;
                        var laptop=values_array[0][0].laptop;
                        var chargerno=values_array[0][0].chargerno;
                        bag=values_array[0][0].bag;
                        var mouseupd=values_array[0][0].mouse;
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
                        var URSRC_role1=values_array[0][1];
                        var aadharno=values_array[0][0].aadharno;
                        var passportno=values_array[0][0].passportno;
                        var voterid=values_array[0][0].voterid;
                        var comment=values_array[0][0].comment;
                        var batryslno=values_array[0][0].batryslno;
//                        alert(batryslno+'aa'+bag)
                        //UPDATE FORM
                        for (var i = 0; i < URSRC_role1.length; i++) {
                            var value=URSRC_role1[i].replace(" ","_");
                            var id1="URSRC_role_arrays"+i;
                            if(URSRC_role1[i]==rc_name){
                                if(i==0)
                                {
                                    var URSRC_roles='<div class="col-lg-2  control-label"><label>SELECT ROLE ACCESS</label></div>';
                                    URSRC_roles+='<div class="col-lg-10" ><div class="radio"><label><input type="radio" name="roles1" id='+id1+' value='+value+' class="login_submitvalidate" checked  />' + URSRC_role1[i] + '</label></div></div>';
                                    $('#URSRC_tble_rolecreation').append(URSRC_roles);
                                }
                                else
                                {
                                    URSRC_roles= '<div class="col-sm-offset-2 col-lg-10"><div class="radio"><label><input type="radio" name="roles1" id='+id1+' value='+value+' class="login_submitvalidate" checked  />' + URSRC_role1[i] + '</label></div></div>';
                                    $('#URSRC_tble_rolecreation').append(URSRC_roles);
                                }
                            }
                            else
                            {
                                if(i==0)
                                {
                                    var URSRC_roles='<div class="col-lg-2  control-label"><label>SELECT ROLE ACCESS<em>*</em></label></div>';
                                    URSRC_roles+= '<div class="col-lg-10" ><div class="radio"><label><input type="radio" name="roles1" id='+id1+' value='+value+' class="login_submitvalidate"   />' + URSRC_role1[i] + '</label></div></div>';
                                    $('#URSRC_tble_rolecreation').append(URSRC_roles);
                                }
                                else
                                {
                                    URSRC_roles = '<div class="col-sm-offset-2 col-lg-10"><div class="radio"><label><input type="radio" name="roles1" id='+id1+' value='+value+' class="login_submitvalidate"   />' + URSRC_role1[i] + '</label></div></div>';
                                    $('#URSRC_tble_rolecreation').append(URSRC_roles);
                                }
                            }

                        }
                        $('#URSRC_lbl_login_role').show();
                        $('#URSRC_lbl_joindate').show();
                        $('#URSRC_tb_joindate').val(join_date).show();
                        $('#URSRC_lbl_emptype').show();
                        $('#URSRC_lb_selectemptype').val(emp_type).show();
                        $('#URSRC_table_employeetbl').show();
                        $('#URSRC_table_others').show();
                        var emp_firstname=firstname.length;
                        $('#URSRC_tb_firstname').val(firstname).attr("size",emp_firstname+3);
                        var emp_lastname=lastname.length;
                        $('#URSRC_tb_lastname').val(lastname).attr("size",emp_lastname+3);
                        $('#URSRC_tb_dob').val(dob);
                        var emp_designation=designation.length;
//                    $('#URSRC_tb_designation').val(designation).attr("size",emp_designation+4);
                        $('#URSRC_tb_designation').val(designation);
                        $('#URSRC_tb_permobile').val(mobile);
                        var emp_kinname=kinname.length;
                        $('#URSRC_tb_kinname').val(kinname).attr("size",emp_kinname+1);
                        var emp_relationhood=relationhood.length;
//                    $('#URSRC_tb_relationhd').val(relationhood).attr("size",emp_relationhood+1);
                        $('#URSRC_lb_selectrelationhd').val(relationhood);
                        $('#URSRC_tb_mobile').val(altmobile);
                        $('#URSRC_tb_houseno').val(Houseno);
                        $('#URSRC_tb_strtname').val(Streetname);
                        $('#URSRC_tb_area').val(Area);
                        $('#URSRC_tb_pstlcode').val(Postalcode);
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
                        $('#URSRC_tb_accntyp').val(accountype);
                        $('#URSRC_ta_brnchaddr').val(branchaddr);
                        if(values_array[0][5]!=null){
                            $('#URSRC_lb_selectlaptopno').val(laptop).show();
//                        $("#URSRC_tb_laptopno option[value='" + laptop + "']").attr("selected", true");
                        }
                        else
                        {
                            $('#URSRC_lb_selectlaptopno').hide();
                            $('#URSRC_lbl_nolaptop').text(URSRC_errorAarray[35]).show();
                        }
                        if(laptop==null)
                        {
                            $('#URSRC_lb_selectlaptopno').val('SELECT').show();
                        }
                        if(chargerno!=null){
                            var emp_cahrgerno=chargerno.length;
                            $('#URSRC_tb_chargerno').val(chargerno).attr("size",emp_cahrgerno+1);
                        }
                        if(batryslno!=null){
                            var emp_btryno=batryslno.length;
                            $('#URSRC_tb_btry').val(batryslno).attr("size",emp_btryno+1);
                        }
//                        if(bag=='X')
//                        {
//                            $('#URSRC_chk_bag').attr('checked',true);
//                        }
//                        else
//                        {
//                            $('#URSRC_chk_bag').attr('checked',false);
//                        }
                        if(bag!=null)
                        {
                            $('#URSRC_chk_bag').attr('checked',true);
                            var emp_bagno=bag.length;
                            $("#URSRC_tb_laptopno").prop("readonly", true);
                            $('#URSRC_tb_laptopno').val(bag).show().attr("size",emp_bagno);
                            $('#URSRC_chk_bag').attr("disabled","disabled")
                        }
                        else
                        {
                            $('#URSRC_chk_bag').attr('checked',false);
                            $('#URSRC_tb_laptopno').val('').hide();
//                            $("#URSRC_tb_laptopno").prop("readonly", false);
                        }
//                        if(mouse=='X')
//                        {
//                            $('#URSRC_chk_mouse').attr('checked',true);
//                        }
//                        else
//                        {
//                            $('#URSRC_chk_mouse').attr('checked',false);
//                        }
                        if(mouseupd!=null)
                        {
                            $('#URSRC_chk_mouse').attr('checked',true);
                            var emp_mouseno=mouseupd.length;
                            $("#URSRC_tb_mouse").prop("readonly", false);
                            $('#URSRC_tb_mouse').val(mouseupd).show().attr("size",emp_mouseno);
                        }
                        else
                        {
                            $('#URSRC_chk_mouse').attr('checked',false);
                            $('#URSRC_tb_mouse').val('').hide();
                        }
//                        if(batryslno!=null)
//                        {
//                            $('#URSRC_chk_btry').attr('checked',true);
//                            var emp_btryno=batryslno.length;
////                            $("#URSRC_tb_btry").prop("readonly", false);
//                            $('#URSRC_tb_btry').val(batryslno).show().attr("size",emp_btryno);
//                        }
//                        else
//                        {
//                            $('#URSRC_chk_btry').attr('checked',false);
//                            $('#URSRC_tb_btry').val('').hide();
//                        }
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
                        if(passportno!=null && passportno!='')
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
                        if(voterid!=null)
                        {
                            $('#URSRC_chk_votersid').attr('checked',true);
                            var emp_votersid=voterid.length;
                            $('#URSRC_tb_votersid').val(voterid).show().attr("size",emp_votersid);
                        }
                        else
                        {
                            $('#URSRC_chk_votersid').attr('checked',false);
                            $('#URSRC_tb_votersid').val('').hide();
                        }
                        if(comment!=null)
                        {
                            $('#URSRC_ta_comments').val(comment).show();
                        }
                        else
                        {
                            $('#URSRC_ta_comments').val('').show();
                        }
//                        $('<tr><td align="left"><input type=submit  class="btn" name="URSRC_submitupdate" id="URSRC_submitupdate"  value="UPDATE" disabled></td></tr>').appendTo($("#URSRC_btn_update"));
                        var btn_recreat='<div style="padding-right:1000px"><input type=submit  class="btn" name="URSRC_submitupdate" id="URSRC_submitupdate"  value="UPDATE" disabled></div>';
                        $('#URSRC_btn_update').html(btn_recreat);
//                        $('<input type="button"  class="btn" name="URSRC_submitupdate" id="URSRC_submitupdate"  value="UPDATE" disabled>').appendTo($("#URSRC_btn_update"));
                    }
                }
                var choice="loginfetch"
                xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?URSRC_login_id="+URSRC_login_id+"&loinid_lap_val="+loinid_lap_val+"&option="+choice,true);
                xmlhttp.send();
            }
            else{
//            $('.preloader',window.parent.document).hide();
                $(".preloader").hide();
                $('#URSRC_tble_rolecreation').hide();
                $('#URSRC_tble_rolecreation tr').remove();
                $('#URSRC_lbl_joindate').hide();
                $('#URSRC_tb_joindate').hide().val("");
                $('#URSRC_lbl_login_role').hide()
                $('#URSRC_lbl_loginidupd').hide();
                $('#URSRC_lbl_emptype').hide();
                $('#URSRC_lb_selectemptype').val('').hide();
                $('#URSRC_tb_loginidupd').hide();
                $('#URSRC_lbl_loginidupd').hide();
                $('#URSRC_btn_login_submitbutton').attr("disabled","disabled");
                $('#URSRC_table_employeetbl').hide();
                $('#URSRC_table_others').hide();
                $('#URSRC_lbl_email_errupd').hide();
            }
        });
        //VALIDATION FOR UPDATE BUTTON LOGIN SEARCH ND UPDATE
        $('form').submit(function (e){
            e.preventDefault();
            var URSRCT_radioid_val=$('input[name=URSRC_mainradiobutton]:checked').val();//$("input:radio[name=URSRC_mainradiobutton]").is(":checked");
            var URSRC_submitbtnvalue= $(this).find("input[type=submit]:focus").val();
            if(URSRC_submitbtnvalue=="CREATE" && URSRCT_radioid_val!='ROLE CREATION')
            {
//            $('.preloader',window.parent.document).show();
                $(".preloader").show();
                var radio_checked=$("input[name=roles1]:checked" ).val()
                $.ajax({
                    type: 'POST',
                    url: "ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do",
                    data: new FormData(this),
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    processData:false,
                    success: function(data)
                    {
//                        alert(data)
                        if(data.toLowerCase().match("error")){
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",data,"success",false);
                        }
                        $(".preloader").hide();
                        var msg_alert=JSON.parse(data);
                        var success_flag=msg_alert[0];
                        var ss_flag=msg_alert[1];
                        var cal_flag=msg_alert[2];
                        var file_flag=msg_alert[4];
                        var name=$('#URSRC_tb_loginid').val();
                        var msg=URSRC_errorAarray[7].replace("[NAME]",name)
                        var finalmsg=msg.replace("[NAME]",name)
                        $('#URSRC_lbl_header').hide();
                        if((success_flag==1)&&(ss_flag==1)&&(cal_flag==1))
                        {
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",finalmsg,"success",false);
                            $('#URSRC_tble_login').hide();
                            $('#URSRC_tble_rolesearch').hide();
                            $('#URSRC_tb_joindate').val("");
                            $('#URSRC_table_employeetbl').hide();
                            $('#URSRC_lbl_joindate').hide();
                            $('#URSRC_tb_joindate').hide();
                            $('#URSRC_tb_loginid').hide();
                            $('#URSRC_tble_rolecreation').hide();
                            $('#URSRC_tb_loginid').hide();
                            $('#URSRC_lbl_loginid').hide();
                            $('#URSRC_lbl_header').hide();
                            $('input:radio[name=URSRC_mainradiobutton]').attr('checked',false);
                            $('#URSRC_btn_login_submitbutton').hide();
                            $('#URSRC_btn_submitbutton').hide();
                            $('#URSRC_tb_loginid').prop("size","20");
                            $('#URSRC_tb_loginid').val('');
                            $("#URSRC_btn_login_submitbutton").attr("disabled", "disabled");
                            $('#URSRC_tb_joindate').val("");
                            $('#URSRC_tb_loginid').val("");
                            $('input[name=URSRC_mainradiobutton]:checked').attr('checked',false);
                            $("#filetableuploads div").remove();
                            $('#attachafile').text('Attach a file');
                        }
                        if(success_flag==0)
                        {
                            var name=$('#URSRC_tb_loginid').val();
                            var msg=URSRC_errorAarray[27].replace("[NAME]",name)
                            var finalmsg=msg.replace("[NAME]",name)
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:finalmsg ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",finalmsg,"success",false);
                        }
                        if((success_flag==1)&&(ss_flag==0))
                        {
                            var fileid=msg_alert[3];
                            var msg= URSRC_errorAarray[28].replace("[SSID]",fileid)
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:msg ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",msg,"success",false);
                        }
                        if((success_flag==1)&&(ss_flag==1)&&(file_flag==0)){
                            var folderid=msg_alert[5];
                            var msg=URSRC_errorAarray[30].replace("[FID]",folderid);
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:msg ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",msg,"success",false);
                        }
                        if((success_flag==1)&&(ss_flag==1)&&(cal_flag==0))
                        {
                            var fileid=msg_alert[3];
                            var msg= URSRC_errorAarray[29];
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:msg ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",msg,"success",false);
                        }

                    },
                    error: function(data){
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:JSON.stringify(data) ,position:{top:100,left:100}}});
                        show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",JSON.stringify(data),"success",false);
                    }

                });
            }
            if(URSRC_submitbtnvalue=="UPDATE")
            {
//            $('.preloader',window.parent.document).show();
                $(".preloader").show();
                var URSRC_radio_button_select_value=$("input[name=URSRC_mainradiobutton]:checked").val();
                $.ajax({
                    type: 'POST',
                    url: "ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do",
                    data: new FormData(this),
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    processData:false,
                    success: function(data)
                    {
                        if(data.match("Error:Folder id Not present")){
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:URSRC_errorAarray[32] ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[32],"success",false);
                        }
                        else if(data.toLowerCase().match("error")){
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:data ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",data,"success",false);
                        }
//                    $('.preloader',window.parent.document).hide();
                        $(".preloader").hide();
                        var msg_alert=JSON.parse(data);
                        var success_flag=msg_alert[0];
                        var ss_flag=msg_alert[1];
                        var cal_flag=msg_alert[2];
                        var file_flag=msg_alert[4];
                        var folderid=msg_alert[5];
                        var name=$('#URSRC_tb_loginid').val();
                        $('#URSRC_tb_joindate').hide();
                        $('#URSRC_lbl_joindate').hide()
                        $('#URSRC_lbl_emptype').hide();
                        $('#URSRC_lb_selectemptype').prop('selectedIndex',0).hide();
                        $('#URSRC_tble_rolecreation').hide();
                        $('#URSRC_tb_loginidupd').hide();
                        $('#URSRC_lbl_loginidupd').hide();
                        $('#URSRC_lb_selectloginid').prop('selectedIndex',0);
                        $('#URSRC_btn_login_submitbutton').hide()
                        var msg=URSRC_errorAarray[8].replace("[NAME]",name)
                        if((success_flag==1)&&(ss_flag==1)&&(cal_flag==1))
                        {
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:msg,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",msg,"success",false);
                            $('#URSRC_submitupdate').hide();
                            $('#URSRC_lb_selectloginid').hide();
                            $('#URSRC_lbl_loginid').hide();
                            $('#URSRC_lbl_joindate').hide();
                            $('#URSRC_tb_joindate').hide();
                            $('#URSRC_tble_rolecreation').hide();
                            $('#URSRC_lbl_header').hide()
                            $('input:radio[name=URSRC_mainradiobutton]').attr('checked',false);
                            $('#URSRC_table_employeetbl').hide();
                            $('#URSRC_lbl_selectloginid').hide();
                            $("#filetableuploads div").remove();
                            $('#attachafile').text('Attach a file');
                        }
                        if(success_flag==0){
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:URSRC_errorAarray[18] ,position:{top:150,left:500}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[18],"success",false);
                            $('#URSRC_submitupdate').hide();
                            $('#URSRC_lb_selectloginid').hide();
                            $('#URSRC_lbl_loginid').hide();
                            $('#URSRC_lbl_joindate').hide();
                            $('#URSRC_tb_joindate').hide();
                            $('#URSRC_tble_rolecreation').hide();
                            $('#URSRC_lbl_header').hide()
                            $('input:radio[name=URSRC_mainradiobutton]').attr('checked',false);
                            $('#URSRC_table_employeetbl').hide();
                            $('#URSRC_lbl_selectloginid').hide();
                        }
                        if((success_flag==1)&&(ss_flag==0)){

                            var fileid=msg_alert[3];
                            var msg= URSRC_errorAarray[28].replace("[SSID]",fileid)
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:msg ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",msg,"success",false);
                            $('#URSRC_submitupdate').hide();
                            $('#URSRC_lb_selectloginid').hide();
                            $('#URSRC_lbl_loginid').hide();
                            $('#URSRC_lbl_joindate').hide();
                            $('#URSRC_tb_joindate').hide();
                            $('#URSRC_tble_rolecreation').hide();
                            $('#URSRC_lbl_header').hide()
                            $('input:radio[name=URSRC_mainradiobutton]').attr('checked',false);
                            $('#URSRC_table_employeetbl').hide();
                            $('#URSRC_lbl_selectloginid').hide();
                        }
                        if((success_flag==1)&&(ss_flag==1)&&(file_flag==0)){
                            var folderid=msg_alert[5];
                            var msg=URSRC_errorAarray[30].replace("[FID]",folderid);
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:msg ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",msg,"success",false);
                        }
                        if((success_flag==1)&&(ss_flag==1)&&(cal_flag==0))
                        {
//                    var fileid=msg_alert[3];
                            var msg= URSRC_errorAarray[29];
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:msg ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",msg,"success",false);
                            $('#URSRC_submitupdate').hide();
                            $('#URSRC_lb_selectloginid').hide();
                            $('#URSRC_lbl_loginid').hide();
                            $('#URSRC_lbl_joindate').hide();
                            $('#URSRC_tb_joindate').hide();
                            $('#URSRC_tble_rolecreation').hide();
                            $('#URSRC_lbl_header').hide()
                            $('input:radio[name=URSRC_mainradiobutton]').attr('checked',false);
                            $('#URSRC_table_employeetbl').hide();
                            $('#URSRC_lbl_selectloginid').hide();
                        }
                    },
                    error: function(data){
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:JSON.stringify(data) ,position:{top:100,left:100}}});
                        show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",JSON.stringify(data),"success",false);
                    }

                });
            }
        });
        //FUNCTION TO CLICK BASIC ROLE
        $(document).on("click",'.URSRC_class_basicroles', function (){
//        $('.preloader',window.parent.document).show();
            $(".preloader").show();
            $('#URSRC_lb_selectrole').hide();
            var radio_value=$(this).val();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader',window.parent.document).hide();
                    $(".preloader").hide();
                    var values_array=JSON.parse(xmlhttp.responseText);
                    URSRC_menuname=values_array[0];
                    URSRC_submenu=values_array[1];
                    URSRC_subsubmenu=values_array[2];
                    URSRC_tree_view(values_array,'')
                }
            }
            var choice="URSRC_tree_view"
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?radio_value="+radio_value+"&option="+choice,true);
            xmlhttp.send();
        });
        //COMMON TREE VIEW FUNCTION
        function URSRC_tree_view(values_array,URSRC_checked_mpid){
//        $('.preloader',window.parent.document).hide();
            $(".preloader").hide();
            $('#URSRC_btn_submitbutton').attr("disabled","disabled");
            $('#URSRC_tble_menu').replaceWith('<table id="URSRC_tble_menu" style="width:450px"  ></table>')
            var count=0;
            var menus=[];
            URSRC_menuname=values_array[0];
            URSRC_submenu=values_array[1];
            URSRC_subsubmenu=values_array[2];
            var URSRC_main_menu=URSRC_menuname
            var URSRC_sub_menu=URSRC_submenu
            var URSRC_sub_menu1=URSRC_subsubmenu
            var URSRC_menu1='<div class="row-fluid form-group"><label class="col-sm-2">MENU<em>*</em></label></div>'
            $('#URSRC_tble_menu').append(URSRC_menu1);
            var URSRC_menu=''
            for(var i=0;i<URSRC_main_menu.length;i++)
            {
                var URSRC_submenu_table_id="URSRC_tble_submenu"+i;
                var URSRC_menu_button_id="menu"+"_"+i;
                var URSRC_submenu_div_id="sub"+i
                var menu_value=URSRC_main_menu[i].replace(/ /g,"&");
                var id_menu=i+'m'
                var mainmenuid=i;
                URSRC_menu= '<div class="col-sm-offset-4 col-sm-10"><ul style="list-style: none;" ><li style="list-style: none;" ><tr ><td>&nbsp;&nbsp;&nbsp;<input value="+" type="button"  id='+URSRC_menu_button_id+' height="1" width="1" class="exp" /><input type="checkbox" name="menu" id='+id_menu+' value='+menu_value+' level="parent" class="tree URSRC_submit_validate Parent"  />' + URSRC_main_menu[i] + '</td></tr>';
                URSRC_menu+='<div id='+URSRC_submenu_div_id+' hidden ><tr><td><table id='+URSRC_submenu_table_id+' class="URSRC_class_submenu"  ></table></tr></div></li></ul></div>';
                $('#URSRC_tble_menu').append(URSRC_menu);
                var URSRC_submenu='';
                for(var j=0;j<URSRC_sub_menu.length;j++)
                {
                    if(i==j)
                    {
                        var submenulength=URSRC_sub_menu[j].length;
                        for(var k=0;k<URSRC_sub_menu[j].length;k++)
                        {
                            var URSRC_submenu1_table_id="URSRC_tble_submenu1"+k+j;
                            var URSRC_submenu_button_id="sub_menu"+"_"+k+j;
                            var URSRC_submenu1_div_id="sub1"+k+j;
                            var sub_menu_value1=URSRC_sub_menu[j][k];
                            var sub_menu_values=sub_menu_value1[1];
                            var sub_menu_id=sub_menu_value1[0];
                            sub_menu_values[1]=sub_menu_values[1];
                            var submenuids="USR_SITE_submenus-"+mainmenuid+'-'+submenulength+'-'+k;//+'-'+sub_menu_id;
                            var idsubmenu=k+j
                            if(URSRC_sub_menu1[count].length>0)
                            {
                                URSRC_submenu = '<div ><ul style="list-style: none;"><li style="list-style: none;" ><tr ><td>&nbsp;&nbsp;&nbsp;<input value="+" type="button"  id='+URSRC_submenu_button_id+' height="1" width="1" class="exp1" /><input type="checkbox" name="Sub_menu[]" id='+submenuids+' value='+sub_menu_id+'&&'+' level="child" class="tree submenucheck URSRC_submit_validate Child"  />' + sub_menu_values + '</td></tr>';
                                URSRC_submenu+='<div id='+URSRC_submenu1_div_id+'  ><tr><td><table id='+URSRC_submenu1_table_id+' hidden ></table></tr></div></li></ul></div>';//CHANGED BEC THS LINE USED FOR ONLY IN SUBSUB MENU VAL
                            }
                            else
                            {
                                URSRC_submenu = '<div ><ul style="list-style: none;"><li style="list-style: none;" ><tr ><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="Sub_menu[]" id='+submenuids+' value='+sub_menu_id+' class="tree submenucheck URSRC_submit_validate" level="child" />' + sub_menu_values + '</td><td><input type="hidden" ></tr>';
                            }
                            $('#'+"URSRC_tble_submenu"+i).append(URSRC_submenu);
                            if(URSRC_checked_mpid.length>0)
                            {
                                for(var m1=0;m1<URSRC_checked_mpid.length;m1++){
                                    if(sub_menu_id==URSRC_checked_mpid[m1]){
                                        $('#'+submenuids).prop("checked", true)
                                        $('#'+id_menu).prop("checked", true)
                                    }
                                }
                            }
                            var URSRC_submenu1='';
                            var subsubmenucount=URSRC_sub_menu1[count].length;
                            for(var m=0;m<URSRC_sub_menu1[count].length;m++)
                            {
                                var sub_menu1_value1=URSRC_sub_menu1[count][m];
                                var sub_menu1_values=sub_menu1_value1[1];
                                sub_menu1_values[1]=sub_menu1_values[1];
                                var sub_menu1_id=sub_menu1_value1[0];
                                var idsubmenu1=count+m+'s1'
                                var subsubmenuid='USR_SITE_submenuchk-'+mainmenuid+'-'+submenulength+'-'+k+'-'+sub_menu_id+'-'+m+'-'+subsubmenucount;//+'-'+sub_menu1_id;//
                                URSRC_submenu1 = '<div ><ul style="list-style: none;"><li style="list-style: none;" ><tr ><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="Sub_menu1[]" id='+subsubmenuid+' value='+sub_menu1_id+' class="tree subsubmenuchk URSRC_submit_validate" level="child1" />' +sub_menu1_values + '</td><td><input type="hidden" ></tr></li></ul></div>';
                                $('#'+"URSRC_tble_submenu1"+k+j).append(URSRC_submenu1);
                                if(URSRC_checked_mpid.length>0)
                                {
                                    for(var m1=0;m1<URSRC_checked_mpid.length;m1++){
                                        if(sub_menu1_id==URSRC_checked_mpid[m1]){
                                            $('#'+subsubmenuid).prop("checked", true)
                                            $('#'+submenuids).prop("checked", true)
                                            $('#'+id_menu).prop("checked", true)
                                        }
                                    }
                                }
                            }
                            count++;
                        }
                    }
                }
            }
            $('#URSRC_btn_submitbutton').show()
            $('#URSRC_tble_rolesearch').show()
        }
        //TREE VIEW EXPANDING
        $(document).on("click",'.exp,.collapse2', function (evt){
            evt.stopPropagation();
            evt.preventDefault();
            evt.stopImmediatePropagation();
            var button_id=$(this).attr("id")
            var btnid=button_id.split("_");
            var menu_btnid=btnid[1]
//        alert($(this).val())
            if($(this).val()=='+'){
//           alert('if');
                $(this).val('-');
//            $(this).replaceWith('<input type="button"   value="-" id='+button_id+'  height="3" width="3" class="collapse" />');
                if(btnid[0]=='folder'){
                    $('#subf'+menu_btnid).toggle("fold",100);
                }
                else{
                    $('#sub'+menu_btnid).toggle("fold",100);
                }
            }
            else
            {
                if(btnid[0]=='folder'){
                    $('#subf'+menu_btnid).toggle("fold",100);
                }
                else{
                    $('#sub'+menu_btnid).toggle("fold",100);
                }
                $(this).replaceWith('<input type="button"   value="+" id='+button_id+'  height="1" width="1" class="exp" />');
            }
        });
        //TREE VIEW EXPANDING
        $(document).on("click",'.exp1,.collapse1', function (evt){
            evt.stopPropagation();
            evt.preventDefault();
            evt.stopImmediatePropagation();
            var sub_buttonid=$(this).attr("id")
            var btnid=sub_buttonid.split("_");
            var menu_btnid=btnid[2]
            if($(this).val()=='+'){
                $(this).replaceWith('<input type="button"   value="-" id='+sub_buttonid+'  height="1" width="1" class="collapse1" />');
                $('#URSRC_tble_submenu1'+menu_btnid).toggle("fold",100);
            }
            else
            {
                $('#URSRC_tble_submenu1'+menu_btnid).toggle("fold",100);
                $(this).replaceWith('<input type="button"   value="+" id='+sub_buttonid+'  height="3" width="3" class="exp1" />');
            }
        });
        //CHECKED ALL MAIN MENU CHECK BOX
        var URSRC_mainmenu_value;
        $(document).on("change blur",'.tree ', function (){
            var val = $(this).attr("checked");
            URSRC_mainmenu_value=$(this).val()
            $(this).parent().find("input:checkbox").each(function() {
                if (val) {
                    $(this).attr("checked", "checked");
                } else {
                    $(this).removeAttr("checked");
                    $(this).parents('ul').each(function(){
                        $(this).prev('input:checkbox').removeAttr("checked");
                    });
                }
            });
            URSRC_submit_validate()
        });
        //VALIDATION FORTREE VIEW CREATE BUTTON
        function URSRC_submit_validate(){
            var basicrole_profile_checked = $('input[name="URSRC_cb_basicroles1[]"]:checked').length;//$("input[id=checkbox]").is(":checked");
            var URSRC_radio_button_select_value=$("input[name=URSRC_mainradiobutton]:checked").val();
            if((URSRC_radio_button_select_value=="ROLE CREATION")||(URSRC_radio_button_select_value=="ROLE SEARCH UPDATE")){
                var Submenu1_checked=$('input[name="Sub_menu1[]"]:checked').length
                var Submenu_checked=$('input[name="Sub_menu[]"]:checked').length
                if(Submenu1_checked>0||Submenu_checked>0){
                    $('#URSRC_btn_submitbutton').removeAttr('disabled')
                }
                else
                {
                    $('#URSRC_btn_submitbutton').attr("disabled","disabled");
                }
            }
            else{
                var Submenu1_checked=$('input[name="Sub_menu1[]"]:checked').length
                var Submenu_checked=$('input[name="Sub_menu[]"]:checked').length
                if((Submenu1_checked>0)&&(basicrole_profile_checked>0)||(Submenu_checked>0)&&(basicrole_profile_checked>0)){
                    $('#URSRC_btn_submitbutton').removeAttr('disabled')
                }
                else
                {
                    $('#URSRC_btn_submitbutton').attr("disabled","disabled");////remove the comments
                }
            }
        }
        //VALIDATION FOR SUB MENU CHECK BOX CLICKING
        $(document).on('click','.submenucheck',function(){
            var URSRC_checkbox_id=$(this).attr("id");
            var URSRC_checkbox_id_split=URSRC_checkbox_id.split('-');
            var count=0;
            for(var g=0;g<URSRC_checkbox_id_split[2];g++)
            {
                var checked1='USR_SITE_submenus-'+URSRC_checkbox_id_split[1]+'-'+URSRC_checkbox_id_split[2]+'-'+g;
                var checked=$('#'+checked1).attr("checked");
                if(checked)
                {
                    count++;
                }
            }
            if(count!=0)
            {
                $('#'+URSRC_checkbox_id_split[1]+'m').prop('checked',true);
            }
            else
            {
                $('#'+URSRC_checkbox_id_split[1]+'m').prop('checked',false);
            }
        });
        //VALIDATION FOR SUB SUB MENU CHECK BOX CLICKING
        $(document).on('click','.subsubmenuchk',function(){
            var URSRC_checkbox_id=$(this).attr("id");
            var URSRC_checkbox_id_idsplit=URSRC_checkbox_id.split('-');
            var count=0;
            for(var i=0;i<URSRC_checkbox_id_idsplit[6];i++)
            {
                var chkboxid=URSRC_checkbox_id_idsplit[0]+'-'+URSRC_checkbox_id_idsplit[1]+'-'+URSRC_checkbox_id_idsplit[2]+'-'+URSRC_checkbox_id_idsplit[3]+'-'+URSRC_checkbox_id_idsplit[4]+'-'+i+'-'+URSRC_checkbox_id_idsplit[6];
                var checked=$('#'+chkboxid).attr("checked");
                if(checked)
                {
                    count++;
                }
            }
            if(count!=0)
            {
                $('#USR_SITE_submenus-'+URSRC_checkbox_id_idsplit[1]+'-'+URSRC_checkbox_id_idsplit[2]+'-'+URSRC_checkbox_id_idsplit[3]).prop('checked',true);
            }
            else
            {
                $('#USR_SITE_submenus-'+URSRC_checkbox_id_idsplit[1]+'-'+URSRC_checkbox_id_idsplit[2]+'-'+URSRC_checkbox_id_idsplit[3]).prop('checked',false);
            }
            var submenucount=0;
            for(var j=0;j<URSRC_checkbox_id_idsplit[2];j++)
            {
                var subchkid=URSRC_checkbox_id_idsplit[1]+'-'+URSRC_checkbox_id_idsplit[2]+'-'+j;
                var submenuchecked=$('#USR_SITE_submenus-'+subchkid).attr("checked");
                if(submenuchecked)
                {
                    submenucount++;
                }
            }
            if(submenucount!=0)
            {
                $('#'+URSRC_checkbox_id_idsplit[1]+'m').prop('checked',true);
            }
            else
            {
                $('#'+URSRC_checkbox_id_idsplit[1]+'m').prop('checked',false);
            }
        });
        //Basic Role/Search&update/Role Creation and Update  button click
        $(document).on('click','#URSRC_btn_submitbutton',function(){
//        $('.preloader',window.parent.document).show();
            $(".preloader").show();
            var URSRC_radio_button_select_value=$("input[name=URSRC_mainradiobutton]:checked").val();
            //ROLE CREATION SAVE PART
            var formElement = document.getElementById("URE_attendanceentry");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                $('.preloader',window.parent.document).hide();
                    $(".preloader").hide();
                    var msg_alert=xmlhttp.responseText;
                    if(URSRC_radio_button_select_value=="ROLE CREATION"){
                        $('#URSRC_tble_menu').hide();
                        $('#URSRC_tble_folder').hide();
                        $('#URSRC_tble_roles').hide();
                        $('#URSRC_btn_submitbutton').hide();
                        $('#URSRC_tble_role').hide();
                        $('#URSRC_tb_customrole').val("");
                        $('input[name=URSRC_mainradiobutton]:checked').attr('checked',false);
                        $('#URSRC_lbl_header').hide();
                        if(msg_alert==1)
                        {
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:URSRC_errorAarray[6] ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[6],"success",false);
                            $('#URSRC_tble_menu').hide();
                            $('#URSRC_tble_folder').hide();
                            $('#URSRC_tble_roles').hide();
                            $('#URSRC_btn_submitbutton').hide();
                            $('#URSRC_tble_role').hide();
                            $('#URSRC_tb_customrole').val("");
                            $('input[name=URSRC_mainradiobutton]:checked').attr('checked',false);
                            $('#URSRC_lbl_header').hide();
                        }
                        else
                        {
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:URSRC_errorAarray[18] ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[18],"success",false);
                        }
                    }
                    if(URSRC_radio_button_select_value=="ROLE SEARCH UPDATE"){
                        $('#URSRC_tble_menu').hide();
                        $('#URSRC_tble_folder').hide();
                        $('#URSRC_rolesearch_roles tr').remove()
                        $('#URSRC_tble_rolecreation').hide()
                        $('#URSRC_btn_submitbutton').hide();
                        $('#URSRC_lb_selectrole').prop('selectedIndex',0);
                        if(msg_alert==1)
                        {
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:URSRC_errorAarray[9] ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[9],"success",false);
                            $('#URSRC_rolesearch_roles > div').remove()
                            $('#URSRC_tble_menu').hide();
                            $('#URSRC_tble_folder').hide();
                            $('#URSRC_tble_roles').hide();
                            $('#URSRC_btn_submitbutton').hide();
                            $('#URSRC_tble_role').hide();
                            $('#URSRC_tb_customrole').val("");
                            $('input[name=URSRC_mainradiobutton]:checked').attr('checked',false);
                            $('#URSRC_lbl_header').hide();
                            $('#URSRC_lb_selectrole').hide();
                            $('#URSRC_lbl_selectrole').hide();
                        }
                        else
                        {
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:URSRC_errorAarray[18] ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[18],"success",false);
                        }
                    }
                    if(URSRC_radio_button_select_value=="BASIC ROLE MENU CREATION"){
                        $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
                        $('input:radio[name=URSRC_cb_basicroles1]').attr('checked',false);
                        $('#URSRC_tble_menu').hide();
                        $('#URSRC_tble_folder').hide();
                        $('#URSRC_btn_submitbutton').hide();
                        $('#URSRC_tble_basicroles').hide();
                        $('#URSRC_tble_basicroles_chk').hide();
                        $('input[name=URSRC_mainradiobutton]:checked').attr('checked',false);
                        $('#URSRC_lbl_header').hide();
                        if(msg_alert==1)
                        {
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:URSRC_errorAarray[14] ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[14],"success",false);
                        }
                        else
                        {
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS:SEARCH/UPDATE",msgcontent:URSRC_errorAarray[18] ,position:{top:100,left:100}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[18],"success",false);
                        }
                    }
                    if(URSRC_radio_button_select_value=="BASIC ROLE MENU SEARCH UPDATE"){
                        $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
                        $('input:radio[name=URSRC_cb_basicroles1]').attr('checked',false);
                        $('#URSRC_tble_basicroles_chk').hide();
                        $('#URSRC_tble_menu').hide();
                        $('#URSRC_tble_folder').hide();
                        $('#URSRC_btn_submitbutton').hide();
                        $('#URSRC_lbl_header').text("BASIC ROLE MENU SEARCH UPDATE").hide();
                        $('#URSRC_tble_basicroles').hide();
                        $('input[name=URSRC_mainradiobutton]:checked').attr('checked',false);
                        if(msg_alert==1){
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS-SEARCH/UPDATE",msgcontent:URSRC_errorAarray[15] ,position:{top:100,left:500}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[15],"success",false);
                        }
                        else{
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"ACCESS RIGHTS-SEARCH/UPDATE",msgcontent:URSRC_errorAarray[18] ,position:{top:100,left:500}}});
                            show_msgbox("ACCESS RIGHTS:SEARCH/UPDATE",URSRC_errorAarray[18],"success",false);
                        }
                    }
                }
            }
            var choice="rolecreationsave"
            xmlhttp.open("POST","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?URSRC_radio_button_select_value="+URSRC_radio_button_select_value+"&option="+choice,true);
            xmlhttp.send(new FormData(formElement));
        });
        //ROLE SEARCH/UPDATE CLICK
        $(document).on('click','#URSRC_radio_rolesearchupdate',function(){
            flag=0;
            var radio_value_rolesearch=$(this).val();
            $('#URSRC_lbl_header').text("ROLE SEARCH/UPDATE").show()
//        $('.preloader',window.parent.document).show();
            $(".preloader").show();
            $('#URSRC_workfromhome').hide();
            $('#URSRC_btn_submitbutton').val('UPDATE').hide();
            $('#URSRC_tble_role').hide();
            $('#URSRC_tble_menu').hide();
            $('#URSRC_tble_folder').hide();
            $('#URSRC_lbl_nodetails_err').hide()
            $('#URSRC_tble_login').hide()
            $('#URSRC_tble_roles').hide();
            $('#URSRC_tb_customrole').val("");
            $('#URSRC_tble_search').hide();
            $('#URSRC_lbl_basicrole_err').hide()
            $('#URSRC_table_employeetbl').hide();
            $('#URSRC_btn_login_submitbutton').hide();
            $('#URSRC_tble_login').hide();
            $('#URSRC_tb_loginid').val("");
            $('#URSRC_tb_joindate').val("");
            $('#URSRC_tble_rolecreation tr').remove().hide();
            $('input:radio[name=basicroles]').attr('checked',false);
            $('#URSRC_tble_basicroles').hide();
            $('#URSRC_tble_basicrolemenucreation').hide();
            $('#URSRC_tble_basicroles_chk ').hide()
            $('input[name=URSRC_cb_basicroles1]').attr('checked',false);
            $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
            $('#URSRC_table_employeetbl').hide();
            $('#URSRC_table_others').hide();
            $('#URSRC_lbl_joindate').hide().val("");
            $('#URSRC_tb_joindate').hide().val("");
            $('#URSRC_lbl_role_err').hide();
            $('#URSRC_lbl_validnumber').hide();
            $('#URSRC_lbl_validnumber1').hide();
            $('#URSRC_lbl_validnumberhouseno').hide();
            //REQUEST SENDING
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array_rcname=JSON.parse(xmlhttp.responseText);
//                $('.preloader',window.parent.document).hide();
                    $(".preloader").hide();
                    if(values_array_rcname.length!=0){
                        var URSRC_customerole_options='<option>SELECT</option>'
                        for(var l=0;l<values_array_rcname.length;l++){
                            URSRC_customerole_options+= '<option value="' + values_array_rcname[l] + '">' + values_array_rcname[l]+ '</option>';
                        }
                        $('#URSRC_lb_selectrole').html(URSRC_customerole_options);
                        $('#URSRC_tble_rolesearch').show();
                        $('#URSRC_lbl_selectrole').show()
                        $('#URSRC_lb_selectrole').show()
                        $('#URSRC_rolesearch_roles').hide()
                        $('#URSRC_lbl_norole_err').hide();
                        $('#URSRC_submitupdate').hide();
                    }
                    else{
                        $('#URSRC_lbl_norole_err').text(URSRC_errorAarray[11]).show();
                        $('#URSRC_tble_rolesearch').show();
                        $('#URSRC_lb_selectrole').hide();
                        $('#URSRC_rolesearch_roles').hide()
                        $('#URSRC_lbl_selectrole').hide()
                    }
                }
            }
            var choice="ACCESS_RIGHTS_SEARCH_UPDATE_BASICROLE"
            xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?option="+choice,true);
            xmlhttp.send();
        });
        var values_array_rcname=[];
//ROLE CHANGE FUNCTION FOR ROLE SEARCH AND UPDATE
        $('#URSRC_lb_selectrole').change(function(){
            var URSRC_lbrole_srchndupdate=$('#URSRC_lb_selectrole').val();
            if($(this).val()!='SELECT'){
//            $('.preloader',window.parent.document).show();
                $(".preloader").show();
                //FUNCTION TO LOAD SELECTED ROLE DETAILS
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader',window.parent.document).hide();
                        $(".preloader").hide();
                        values_array_rcname=JSON.parse(xmlhttp.responseText);
                        var URSRC_lb_radiovalrolesearch=values_array_rcname[0];
                        URSRC_checked_mpid=values_array_rcname[1];
                        var URSRC_menu_fullarray=values_array_rcname[2];
                        var URSRC_role_radio='<div class="row-fluid form-group"><label class="col-sm-2">SELECT A ROLE ACCESS</label></div>'
                        $('#URSRC_rolesearch_roles').html(URSRC_role_radio);
                        for (var i = 0; i < URSRC_userrigths_array.length; i++) {
                            var id1="URSRC_userrigths_array"+i;
                            var value=URSRC_userrigths_array[i].replace(" ","_")
                            if(URSRC_userrigths_array[i]==URSRC_lb_radiovalrolesearch){
                                URSRC_role_radio+='<div class="row-fluid form-group col-sm-offset-2"><input type="radio" name="basicroles" id='+id1+' value='+value+' class=" URSRC_class_basicroles"  checked  />' + URSRC_userrigths_array[i] + '</div>';
                            }
                            else{
                                URSRC_role_radio+='<div class="row-fluid form-group col-sm-offset-2"><input type="radio" name="basicroles" id='+id1+' value='+value+' class=" URSRC_class_basicroles"   />' + URSRC_userrigths_array[i] + '</div>';
                            }
                        }
                        $('#URSRC_rolesearch_roles').html(URSRC_role_radio);
                        $('#URSRC_rolesearch_roles').show();
                        if(URSRC_menu_fullarray[2].length!=0){
                            $('#URSRC_lbl_nodetails_err').hide()
                            URSRC_tree_view(URSRC_menu_fullarray,URSRC_checked_mpid)
                        }
                        else{
                        }
                    }
                }
                var choice="URSRC_tree_views"
                xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?URSRC_lbrole_srchndupdate="+URSRC_lbrole_srchndupdate+"&option="+choice,true);
                xmlhttp.send();
            }
            $('#URSRC_tble_menu').hide();
            $('#URSRC_tble_folder').hide();
            $('#URSRC_rolesearch_roles div').remove();
            $('#URSRC_btn_submitbutton').hide();
        });
        $('#URSRC_chk_aadharno').click(function(){
            if($("input[name=URSRC_chk_aadharno]").is(":checked")==true){
                $('#URSRC_tb_aadharno').show();
            }
            else{
                $('#URSRC_tb_aadharno').hide().val("");
            }
        });
        $('#URSRC_chk_passportno').click(function(){
            if($("input[name=URSRC_chk_passportno]").is(":checked")==true){
                $('#URSRC_tb_passportno').show();
            }
            else{

                $('#URSRC_tb_passportno').hide().val("");
            }
        });
        $('#URSRC_chk_votersid').click(function(){
            if($("input[name=URSRC_chk_votersid]").is(":checked")==true){
                $('#URSRC_tb_votersid').show();
            }
            else{
                $('#URSRC_tb_votersid').hide().val("");
            }
        });
        $('#URSRC_chk_bag').click(function(){
            if(($("input[name=URSRC_chk_bag]").is(":checked")==true) && (bag!=null)){
                $('#URSRC_tb_laptopno').val(laptopno).show();
            }
            if(($("input[name=URSRC_chk_bag]").is(":checked")==true) && (laptopno==undefined)){
                $("#URSRC_tb_laptopno").prop("readonly", false);
                $('#URSRC_tb_laptopno').val('').show();
            }
            else{
                $("#URSRC_tb_laptopno").prop("readonly", false);
                $('#URSRC_tb_laptopno').val("").hide();
            }
        });
        $('#URSRC_chk_mouse').click(function(){
            if($("input[name=URSRC_chk_mouse]").is(":checked")==true){
                $('#URSRC_tb_mouse').val(mouse).show();
            }
            else{
                $('#URSRC_tb_mouse').hide().val("");
            }
        });
        $('#URSRC_btn_submitbutton').hide();
        $(document).on('change','#URSRC_lb_selectlaptopno',function(){
//            $('#URSRC_tb_mouse').val('');
//            $('#URSRC_tb_laptopno').val('');
//            $("#URSRC_tb_laptopno").prop("readonly", false);
//            $("#URSRC_tb_mouse").prop("readonly", false);
            $("#URSRC_chk_bag").removeAttr("disabled","disabled")
            $(".preloader").show();
            var URSRC_lb_laptopno=$('#URSRC_lb_selectlaptopno').find('option:selected').text();
            if(URSRC_lb_laptopno!='SELECT')
            {
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        var value_array=JSON.parse(xmlhttp.responseText);
                        for(var j=0;j<value_array.length;j++) {
                            var chargerno = value_array[j].chargerno;
                            laptopno = value_array[j].laptopno;
                            mouse = value_array[j].mouse;
                            btry = value_array[j].btry;
                        }
//                        alert(laptopno+'a'+mouse+'aa'+btry)
                        $('#URSRC_tb_chargerno').val(chargerno);
                        $('#URSRC_tb_btry').val(btry).show();
                        if(laptopno!=null)
                        {
                            $('#URSRC_tb_laptopno').val(laptopno).show();
                            $('input:checkbox[id=URSRC_chk_bag]').attr('checked',true)
                            $("#URSRC_tb_laptopno").prop("readonly", true);
//                            $("#URSRC_chk_bag").prop("readonly", true);
                            $("#URSRC_chk_bag").attr("disabled","disabled")
                        }
                        else
                        {
                            $('input:checkbox[id=URSRC_chk_bag]').attr('checked',false)
                            $("#URSRC_tb_laptopno").prop("readonly", false);
                            $('#URSRC_tb_laptopno').val('').hide();
                        }
                        if(mouse!=null)
                        {
                            $('#URSRC_tb_mouse').val(mouse).show();
                            $('input:checkbox[id=URSRC_chk_mouse]').attr('checked',true)
                        }
                        else
                        {
                            $('input:checkbox[id=URSRC_chk_mouse]').attr('checked',false)
                            $("#URSRC_tb_mouse").prop("readonly", false);
                            $('#URSRC_tb_mouse').val('').hide();
                        }
//                        if(btry!='')
//                        {
//                            $('#URSRC_tb_btry').val(btry).show();
//                            $('input:checkbox[id=URSRC_chk_btry]').attr('checked',true)
//                        }
//                        else
//                        {
//                            $('#URSRC_tb_btry').val('').hide();
//                        }
                        loginbuttonvalidation()
                    }
                }
                var option="COMPANY_PROPERTY";
                xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?option="+option+"&URSRC_lb_laptopno="+URSRC_lb_laptopno );
                xmlhttp.send();
            }
            else{
                $(".preloader").hide();
                $('#URSRC_tb_chargerno').val('');
                $('#URSRC_tb_btry').val('').show();
                $('#URSRC_tb_laptopno').val('').hide();
                $('#URSRC_tb_mouse').val('').hide();
                $('input:checkbox[id=URSRC_chk_btry]').attr('checked',false)
                $('input:checkbox[id=URSRC_chk_mouse]').attr('checked',false)
                $('input:checkbox[id=URSRC_chk_bag]').attr('checked',false)
                loginbuttonvalidation()
            }
        });
        $(document).on('click','#URSRC_radio_workfromhome',function(){
            $(".preloader").show();
            $('#URSRC_lbl_header').text("WORK FROM HOME ACCESS").show();
            $('#EMP_ENTRY_lbl_txtselectproj').hide();
            $('#EMP_ENTRY_tble_frstsel_projectlistbx').hide();
            $('#CONFIG_SRCH_UPD_tr_type').empty();
            $('#EMP_ENTRY_btn_save').hide();
            $('#URSRC_lbl_nodetails_err').hide();
            $('#URSRC_lbl_login_role').hide();
            $('#URSRC_lbl_joindate').hide().val("");
            $('#URSRC_tb_joindate').hide().val("");
            $('#URSRC_tb_loginid').hide();
            $('#URSRC_lbl_loginid').hide();
            $('#URSRC_btn_submitbutton').val("CREATE").hide()
            $('#URSRC_tble_rolesearch').hide();
            $('#URSRC_btn_login_submitbutton').attr("disabled","disabled").hide();
            $('#URSRC_tb_customrole').val("");
            $('#URSRC_tble_rolecreation div').remove().hide();
            $('#URSRC_tble_basicroles').hide();
            $('#URSRC_tble_role').hide();
            $('#URSRC_tble_login').hide();
            $('#URSRC_lbl_basicrole_err').hide()
            $('#URSRC_tble_menu').hide();
            $('#URSRC_tble_folder').hide();
            $('#URSRC_tble_roles').hide()
            $('#URSRC_tble_basicroles_chk ').hide()
            $('input:radio[name=URSRC_radio_basicroles1]').attr('checked',false);
            $('input[name=URSRC_cb_basicroles1]').attr('checked',false);
            $('#URSRC_table_employeetbl').hide();
            $('#URSRC_table_others').hide();
            $('#URSRC_submitupdate').hide();
            $('#URSRC_lbl_role_err').hide();
            $('#URSRC_lbl_validnumber').hide();
            $('#URSRC_lbl_validnumber1').hide();
            $('#URSRC_lbl_validnumberhouseno').hide();
            $("#filetableuploads div").remove();
            $('#attachafile').text('Attach a file');
            $('#URSRC_lbl_basicrole_err').hide();
            $('#URSRC_workfromhome').show();
            $('#EMP_ENTRY_btn_reset').hide();
            var EMP_ENTRY_empname;
            initialload();
            //FUNCTION FOR GETTING ERR MSG,LOGIN ID
            function initialload(){
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
                        var values_array=JSON.parse(xmlhttp.responseText);
                        EMP_ENTRY_loginid=values_array[0];
                        project_array=values_array[1];
                        err_msg_array=values_array[2];
                        if(EMP_ENTRY_loginid.length!=0)
                        {
                            var active_employee='<option>SELECT</option>';
                            for (var i=0;i<EMP_ENTRY_loginid.length;i++) {
                                active_employee += '<option value="' + EMP_ENTRY_loginid[i][1]+ '">' + EMP_ENTRY_loginid[i][0]+ '</option>';
                            }
                            $('#EMP_ENTRY_lb_loginid').html(active_employee);
                            $('#EMP_ENTRY_lbl_loginid').show();
                            $('#EMP_ENTRY_lb_loginid').show();
                        }
                        else
                        {
                            $('#EMP_ENTRY_lbl_nologinid').text(err_msg_array[1]).show();
                            $('#EMP_ENTRY_lbl_loginid').hide();
                            $('#EMP_ENTRY_lb_loginid').hide();
                        }
                    }
                }
                var option="common";
                xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?option="+option);
                xmlhttp.send();
            }
            //FUNCTION FOR PROJECT LIST
            function projectlist(){
                $('#EMP_ENTRY_tble_frstsel_projectlistbx').html('');
                var project_list;
                for (var i=0;i<project_array.length;i++) {
                    project_list += '<tr><td><input type="checkbox" id ="checkbox" name="checkbox[]" value="' + project_array[i][1] + '">' + project_array[i][0] +'</td></tr>';
                }
                $('#EMP_ENTRY_tble_frstsel_projectlistbx').append(project_list).show();
            }
            //CHANGE EVENT FOR ACTIVE LOGIN ID
            $('#EMP_ENTRY_lb_loginid').change(function(){
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                //PRELOADER ADJUST FUNCTION
                $(".preloader").show();
                $('input:checkbox[id=checkbox]').attr('checked',false);
                $('#checkbox').attr('checked',false);
                EMP_ENTRY_empname=$("#EMP_ENTRY_lb_loginid option:selected").text();
                if(EMP_ENTRY_empname=="SELECT")
                {
                    $(".preloader").hide();
                    $('#EMP_ENTRY_btn_reset').hide();
                    $('#EMP_ENTRY_tble_frstsel_projectlistbx').hide();
                    $('#EMP_ENTRY_tble_projectlistbx').hide();
                    $('#EMP_ENTRY_lbl_txtselectproj').hide();
                }
                else
                {
                    projectlist();
                    $(".preloader").hide();
                    $('#EMP_ENTRY_lb_loginid').show();
                    $('#EMP_ENTRY_lbl_loginid').show();
                    $('#CONFIG_SRCH_UPD_tr_type').append('<div class="row-fluid form-group"><input type="button" class="btn" name="EMP_ENTRY_btn_save" id="EMP_ENTRY_btn_save" value="SAVE" disabled></div>');
                    $('#EMP_ENTRY_btn_reset').show();
                    $('#checkbox').attr('checked',false).show();
                    $('#EMP_ENTRY_tble_projectlistbx').show();
                    $('#EMP_ENTRY_lbl_txtselectproj').show();
                }
            });
            //CLICK EVENT FUCNTION FOR RESET
            $('#EMP_ENTRY_btn_reset').click(function()
            {
                EMP_ENTRY_rset()
            });
            //CLEAR ALL FIELDS
            function EMP_ENTRY_rset()
            {
                $('#EMP_ENTRY_lb_loginid').val('SELECT');
                $('#EMP_ENTRY_btn_reset').hide();
                $('#EMP_ENTRY_btn_save').hide();
                $('#CONFIG_SRCH_UPD_tr_type').empty();
                $('#EMP_ENTRY_tble_frstsel_projectlistbx').hide();
                $('#EMP_ENTRY_tble_projectlistbx').hide();
                $('#EMP_ENTRY_lbl_txtselectproj').hide();
            }
            //FORM VALIDATION
            $(document).on('change','#URE_attendanceentry',function(){
                $("#EMP_ENTRY_btn_save").attr("disabled", "disabled");
                var EMP_ENTRY_loginid = $("#EMP_ENTRY_lb_loginid").val();
                var EMP_ENTRY_projectselectlistbx = $("input[id=checkbox]").is(":checked");
                var button_val=$('#EMP_ENTRY_btn_save').val();
                if(button_val == 'SAVE')
                {
                    if((EMP_ENTRY_loginid!='SELECT')&&( EMP_ENTRY_projectselectlistbx==true))
                    {
                        $("#EMP_ENTRY_btn_save").removeAttr("disabled");
                    }
                    else
                    {
                        $("#EMP_ENTRY_btn_save").attr("disabled", "disabled");
                    }
                }
                else if(button_val == 'UPDATE')
                {
                    if((EMP_ENTRY_loginid!='SELECT')&& ( EMP_ENTRY_projectselectlistbx==false))
                    {
                        $("#EMP_ENTRY_btn_save").removeAttr("disabled");
                    }
                    else
                    {
                        $("#EMP_ENTRY_btn_save").attr("disabled", "disabled");
                    }
                }
            });
            $(document).on('change','#EMP_ENTRY_lb_loginid',function(evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                $('#CONFIG_SRCH_UPD_tr_type').empty();
                if($(this).val() == 'SELECT')
                {
                    $('#CONFIG_SRCH_UPD_tr_type').empty();
                    $("#EMP_ENTRY_btn_save").hide();
                    $('#EMP_ENTRY_tble_projectlistbx').hide();
                }
                else{
                    $('#EMP_ENTRY_tble_projectlistbx').show();
                    var loginid=$('#EMP_ENTRY_lb_loginid').val();
                    var formElement = document.getElementById("URE_attendanceentry");
                    var flag;
//            $('.preloader', window.parent.document).show();
                    $(".preloader").show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                    $('.preloader', window.parent.document).hide();
                            $(".preloader").hide();
                            var values_array=JSON.parse(xmlhttp.responseText);
                            flag=values_array[0];
                            if(flag == "X")
                            {
                                $('input:checkbox[id=checkbox]').attr('checked',true)
                                $('#CONFIG_SRCH_UPD_tr_type').append('<div class="row-fluid form-group"><input type="button" class="btn" name="EMP_ENTRY_btn_save" id="EMP_ENTRY_btn_save"   value="UPDATE" disabled></div>')
                            }
                            else{
                                $('input:checkbox[id=checkbox]').attr('checked',false)
                                $('#CONFIG_SRCH_UPD_tr_type').append('<div class="row-fluid form-group"><input type="button" class="btn" name="EMP_ENTRY_btn_save" id="EMP_ENTRY_btn_save"   value="SAVE" disabled></div>')
                            }
                        }
                    }
                    var option="check_flag";
                    xmlhttp.open("GET","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?option="+option+"&loginid="+loginid);
                    xmlhttp.send(new FormData(formElement));
                }
            })

            //CLICK EVENT FOR SAVE BUTTON
            $(document).on('click','#EMP_ENTRY_btn_save',function(){
                //PRELOADER ADJUST FUNCTION
                $('#CONFIG_SRCH_UPD_tr_type').empty();
                $(".preloader").show();
                var loginid=$("#EMP_ENTRY_lb_loginid option:selected").text();
                var formElement = document.getElementById("URE_attendanceentry");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var msg_alert=JSON.parse(xmlhttp.responseText);
                        if(msg_alert[1]=='SAVE')
                        {
                            if(msg_alert[0]==1)
                            {
                                $(".preloader").hide();
                                var msg=err_msg_array[2].replace("[UNAME]",loginid);
                                show_msgbox("EMPLOYEE WORK FROM ACCESS",msg,"success",false);
                                EMP_ENTRY_rset()
                                initialload();
                                $('#EMP_ENTRY_lb_loginid').hide();
                            }
                            else
                            {
                                $(".preloader").hide();
                                show_msgbox("EMPLOYEE WORK FROM ACCESS",err_msg_array[0],"success",false);
                            }
                        }
                        else
                        {
                            if(msg_alert[0]==1)
                            {
                                $(".preloader").hide();
                                var msg=err_msg_array[3].replace("[UNAME]",loginid);
                                show_msgbox("EMPLOYEE WORK FROM ACCESS",msg,"success",false);
                                EMP_ENTRY_rset()
//                                initialload();
                                $('#URSRC_radio_workfromhome').attr("checked",false)
                                $('#EMP_ENTRY_lb_loginid').hide();
                                $('#EMP_ENTRY_lbl_loginid').hide();
                                $('#URSRC_lbl_header').hide();
                            }
                            else
                            {
                                $(".preloader").hide();
                                show_msgbox("EMPLOYEE WORK FROM ACCESS",err_msg_array[0],"success",false);

                            }

                        }
                    }
                }
                var choice="PROJECT_PROPETIES_SAVE"
                xmlhttp.open("POST","ACCESSRIGHTS/DB_ACCESS_RIGHTS_ACCESS_RIGHTS-SEARCH_UPDATE.do?option="+choice,true);
                xmlhttp.send(new FormData(formElement));
            });
        });
    });
    //END DOCUMENT READY FUNCTION
</script>
<!--SCRIPT TAG END-->
<!--BODY TAG START-->
<body>
<div class="container-fluid">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="title text-center"><h4><b>ACCESS RIGHTS:SEARCH/ UPDATE</b></h4></div>
    <form  name="URE_attendanceentry" id="URE_attendanceentry" class="content" method="post" enctype="multipart/form-data">
        <div class="panel-body">
            <fieldset>
                <div id="URSRC_tble_main ">
                    <div class="row-fluid form-group" style="padding-left: 15px">
                        <label name="USU_lbl_strtdte" id="USU_lbl_strtdte" class="srctitle">SELECT A OPTION</label>
                        <input type="text" name="USU_tb_strtdte" id="USU_tb_strtdte" class="USU_date valid" hidden>
                    </div>
                    <div class="row-fluid form-group form-inline col-sm-5">
                        <div class="radio">
                            <input  type='radio' name='URSRC_mainradiobutton' id='URSRC_radio_basicrolemenucreation' value='BASIC ROLE MENU CREATION'> &nbsp;BASIC ROLE MENU CREATION
                        </div></div>
                    <div class="row-fluid form-group form-inline col-sm-5">
                        <div class="radio">
                            <input  type='radio' name='URSRC_mainradiobutton' id='URSRC_radio_basicrolemenusearchupdate' value='BASIC ROLE MENU SEARCH UPDATE'> &nbsp;BASIC ROLE MENU SEARCH/UPDATE
                        </div></div>
                    <div class="row-fluid form-group form-inline col-sm-5">
                        <div class="radio">
                            <input  type='radio' name='URSRC_mainradiobutton' id='URSRC_radio_rolecreation' value='ROLE CREATION'> &nbsp; ROLE CREATION
                        </div></div>
                    <div class="row-fluid form-group form-inline col-sm-5">
                        <div class="radio">
                            <input   type='radio' name='URSRC_mainradiobutton' id='URSRC_radio_rolesearchupdate' value='ROLE SEARCH UPDATE'>  &nbsp;ROLE SEARCH/UPDATE
                        </div></div>
                    <div class="row-fluid form-group form-inline col-sm-5">
                        <div class="radio">
                            <input   type='radio' name='URSRC_mainradiobutton' id='URSRC_radio_logincreation' value='LOGIN CREATION'>  &nbsp;LOGIN CREATION
                        </div></div>
                    <div class="row-fluid form-group form-inline col-sm-5">
                        <div class="radio">
                            <input   type='radio' name='URSRC_mainradiobutton' id='URSRC_radio_loginsearchupdate' value='LOGIN SEARCH UPDATE'>  &nbsp;LOGIN SEARCH/UPDATE
                        </div></div>
                    <div class="row-fluid form-group form-inline col-sm-5">
                        <div class="radio">
                            <input   type='radio' name='URSRC_mainradiobutton' id='URSRC_radio_workfromhome' value='WORK FROM HOME'>  &nbsp;WORK FROM HOME ACCESS
                        </div></div>
                    <label id="URSRC_lbl_header" class="srctitle" style="padding-left: 15px"></label>
                </div>
                <div id="URSRC_workfromhome" class="form-group" hidden>
                    <div class="row-fluid">
                        <label name="EMP_ENTRY_lbl_loginid" class="col-sm-2" id="EMP_ENTRY_lbl_loginid" hidden>EMPLOYEE NAME<em>*</em></label>
                        <div class="col-sm-4"><select name="EMP_ENTRY_lb_loginid" id="EMP_ENTRY_lb_loginid" class="form-control" style="display: none">
                            </select>
                        </div></div>
                    <div><label id="EMP_ENTRY_lbl_nologinid" name="EMP_ENTRY_lbl_nologinid" class="errormsg"></label></div>
                    <div class="row-fluid" id="EMP_ENTRY_tble_projectlistbx"  hidden>
                        <label name="EMP_ENTRY_lbl_txtselectproj"  class="col-sm-2" id="EMP_ENTRY_lbl_txtselectproj">PROJECT NAME<em>*</em></label>
                        <div id="EMP_ENTRY_tble_frstsel_projectlistbx" class="col-sm-8" ></div>
                    </div>
                    <div class="row-fluid  form-inline col-sm-offset-0 col-sm-2">
                        <div class="col-sm-2" id="CONFIG_SRCH_UPD_tr_type"></div>
                        <div class="col-sm-4">
                            <input type="button" class="btn" name="EMP_ENTRY_btn_reset" id="EMP_ENTRY_btn_reset"  value="RESET" hidden>
                        </div></div>
                </div>
                <div id="URSRC_tble_basicrolemenucreation" hidden>
                    <div id="URSRC_tble_basicroles" hidden ></div>
                </div><br>
                <lable id="URSRC_lbl_basicrole_err" class="errormsg"></lable>
                <div id="URSRC_tble_basicrolemenusearch" hidden>

                    <div id="URSRC_tble_search_basicroles" hidden ></div>

                </div>
                <div id="URSRC_tble_basicroles_chk" hidden ></div>
                <div id="URSRC_tble_role" hidden>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2">ROLE<em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_customrole" id="URSRC_tb_customrole" maxlength="15" class="autosize form-control" /><label id="URSRC_lbl_role_err" class="errormsg"></label>
                        </div></div>

                    <div id="URSRC_tble_roles" hidden ></div>

                </div>
                <div id="URSRC_tble_login" hidden>
                    <label id="URSRC_lbl_nologin_err" class="errormsg"></label>
                    <div class="row-fluid">
                        <label class="col-sm-2" id="URSRC_lbl_loginid">LOGIN ID<em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_loginid" id="URSRC_tb_loginid" class="login_submitvalidate URSRC_email_validate form-control" hidden /><label id="URSRC_lbl_email_err" class="errormsg"></label>
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" id="URSRC_lbl_selectloginid">EMPLOYEE NAME<em>*</em></label>
                        <div class="col-sm-4">
                            <select id='URSRC_lb_selectloginid' name="URSRC_lb_loginid" title="LOGIN ID" maxlength="40" class="form-control" >
                                <option value='SELECT' selected="selected"> SELECT</option>
                            </select>
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" id="URSRC_lbl_loginidupd">LOGIN ID<em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_loginidupd" id="URSRC_tb_loginidupd" class="login_submitvalidate URSRC_email_validate form-control" hidden /><label id="URSRC_lbl_email_errupd" class="errormsg"></label>
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" id="URSRC_lbl_emptype" hidden>SELECT EMPLOYEE TYPE<em>*</em></label>
                        <div class="col-sm-4">
                            <select id='URSRC_lb_selectemptype' name="URSRC_lb_selectemptype"  maxlength="40" class="login_submitvalidate form-control" hidden  >
                                <option value='SELECT' selected="selected"> SELECT</option>
                            </select>
                        </div></div>

                    <div id="URSRC_tble_rolecreation"  hidden></div>


                    <div id="joindate" class="row-fluid form-group" >

                        <label class="col-sm-2" id="URSRC_lbl_joindate" hidden >SELECT A JOIN DATE<em>*</em></label>
                        <div class="col-sm-2">
                            <input type="text" name="URSRC_tb_joindate" id="URSRC_tb_joindate" class="datepicker login_submitvalidate datemandtry form-control" hidden  />
                        </div></div>


                </div>
                <div id="URSRC_table_employeetbl" hidden>
                    <div class="row-fluid form-group">
                        <label class="srctitle"  name="URSRC_lbl_personnaldtls" id="URSRC_lbl_personnaldtls" style="padding-left: 15px">PERSONAL DETAILS</label>
                    </div>
                    <div class="row-fluid form-group">

                        <label class="col-sm-2" name="URSRC_lbl_firstname" id="URSRC_lbl_firstname">FIRST NAME <em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_firstname" id="URSRC_tb_firstname" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate form-control" >
                        </div></div>
                    <div class="row-fluid form-group">

                        <label class="col-sm-2" name="URSRC_lbl_lastname" id="URSRC_lbl_lastname">LAST NAME <em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_lastname" id="URSRC_tb_lastname" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate form-control">
                        </div></div>
                    <div class="row-fluid form-group">

                        <label class="col-sm-2" name="URSRC_lbl_dob" id="URSRC_lbl_dob">DATE OF BIRTH<em>*</em></label>
                        <div class="col-sm-2">
                            <input type="text" name="URSRC_tb_dob" id="URSRC_tb_dob" class="datepickerdob datemandtry login_submitvalidate form-control">
                        </div></div>
                    <div class="row-fluid form-group">

                        <label class="col-sm-2" name="URSRC_lbl_designation" id="URSRC_lbl_designation">DESIGNATION<em>*</em></label>
                        <div class="col-sm-4">
                            <select  name="URSRC_tb_designation" id="URSRC_tb_designation" class="alphanumericuppercse login_submitvalidate form-control">
                                <option value='SELECT' selected="selected"> SELECT</option>
                            </select>
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="URSRC_lbl_permobile" id="URSRC_lbl_permobile">PERSONAL MOBILE<em>*</em></label>
                        <div class="col-sm-2">
                            <input type="text" name="URSRC_tb_permobile" id="URSRC_tb_permobile"  maxlength='10' class="mobileno title_nos valid login_submitvalidate form-control">

                            <label id="URSRC_lbl_validnumber" name="URSRC_lbl_validnumber" class="errormsg"></label>
                        </div></div>
                    <div class="row-fluid form-group">

                        <label  class="col-sm-2" name="URSRC_lbl_kinname" id="URSRC_lbl_kinname">NEXT KIN NAME<em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_kinname" id="URSRC_tb_kinname" maxlength='30' class="autosizealph sizefix title_alpha login_submitvalidate form-control">
                        </div></div>
                    <div class="row-fluid form-group">
                        <label  class="col-sm-2" id="URSRC_lbl_relationhd">RELATION HOOD <em>*</em></label>
                        <div class="col-sm-4">
                            <select id='URSRC_lb_selectrelationhd' name="URSRC_lb_selectrelationhd" class="relationhd_submitvalidate form-control login_submitvalidate">
                                <option value='SELECT' selected="selected"> SELECT</option>
                            </select>
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="URSRC_lbl_mobile" id="URSRC_lbl_mobile">MOBILE NO<em>*</em></label>
                        <div class="col-sm-2">
                            <input type="text" name="URSRC_tb_mobile" id="URSRC_tb_mobile" class="mobileno title_nos valid login_submitvalidate form-control" maxlength='10' >
                            <label id="URSRC_lbl_validnumber1" name="URSRC_lbl_validnumber1" class="errormsg"></label>
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="URSRC_lbl_houseno" id="URSRC_lbl_houseno">HOUSE NO<em>*</em></label>
                        <div class="col-sm-2">
                            <input type="" name="URSRC_tb_houseno" id="URSRC_tb_houseno" class="houseno title_nos   login_submitvalidate form-control"  maxlength='15'>
                            <label id="URSRC_lbl_validnumberhouseno" name="URSRC_lbl_validnumberhouseno" class="errormsg"></label>
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="URSRC_lbl_strtname" id="URSRC_lbl_strtname">STREET NAME<em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_strtname" id="URSRC_tb_strtname" class="alphanumericuppercse sizefix login_submitvalidate form-control">
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="URSRC_lbl_area" id="URSRC_lbl_area">AREA<em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_area" id="URSRC_tb_area" class="alphanumericuppercse sizefix login_submitvalidate form-control">
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="URSRC_lbl_pstlcode" id="URSRC_lbl_pstlccode">POSTAL CODE<em>*</em></label>
                        <div class="col-sm-2">
                            <input type="text" name="URSRC_tb_pstlcode" id="URSRC_tb_pstlcode" maxlength='8' class="sizefix pstlcode login_submitvalidate form-control" >
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="srctitle"  name="URSRC_lbl_bnkdtls" id="URSRC_lbl_bnkdtls" style="padding-left: 15px">BANK DETAILS</label>
                    </div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="URSRC_lbl_bnkname" id="URSRC_lbl_bnkname">BANK NAME <em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_bnkname" id="URSRC_tb_bnkname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate form-control" >
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="URSRC_lbl_brnchname" id="URSRC_lbl_brnchname">BRANCH NAME <em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_brnchname" id="URSRC_tb_brnchname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate form-control" >
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="URSRC_lbl_accntname" id="URSRC_lbl_accntname">ACCOUNT NAME <em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_accntname" id="URSRC_tb_accntname" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate form-control" >
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="URSRC_lbl_accntno" id="URSRC_lbl_accntno">ACCOUNT NUMBER <em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_accntno" id="URSRC_tb_accntno" maxlength='50' class=" sizefix accntno login_submitvalidate form-control" >
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="URSRC_lbl_ifsccode" id="URSRC_lbl_ifsccode">IFSC CODE<em>*</em></label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_ifsccode" id="URSRC_tb_ifsccode" maxlength='50' class="alphanumericuppercse sizefix login_submitvalidate form-control" >
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" name="URSRC_lbl_accntyp" id="URSRC_lbl_accntyp">ACCOUNT TYPE<em>*</em></label>
                        <div class="col-sm-4">
                            <select name="URSRC_tb_accntyp" id="URSRC_tb_accntyp" class="login_submitvalidate form-control" >
                                <option value='SELECT' selected="selected"> SELECT</option>
                            </select>
                        </div></div>
                    <div class="row-fluid form-group" width="175">
                        <label class="col-sm-2" name="URSRC_lbl_brnchaddr" id="URSRC_lbl_brnchaddr">BRANCH ADDRESS<em>*</em></label>
                        <div class="col-sm-4">
                            <textarea rows="4" cols="50" name="URSRC_ta_brnchaddr" id="URSRC_ta_brnchaddr" class="maxlength login_submitvalidate  form-control"></textarea>
                        </div></div>
                    <div class="row-fluid form-group">
                        <label class="srctitle col-sm-2"  name="URSRC_lbl_others" id="URSRC_lbl_others">OTHERS</label>
                    </div>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" id="URSRC_lbl_laptopno">LAPTOP NUMBER</label></td>
                        <div class="col-sm-4">
                            <select id='URSRC_lb_selectlaptopno' name="URSRC_lb_selectlaptopno"  maxlength="40" class="selectlaptopno_submitvalidate form-control">
                                <option value='SELECT' selected="selected"> SELECT</option>
                            </select>
                            <label id="URSRC_lbl_nolaptop" name="URSRC_lbl_nolaptop"  class="errormsg" hidden></label>
                        </div></div>
                    <div class="row-fluid form-group">
                        <label  class="col-sm-2" name="URSRC_lbl_chargerno" id="URSRC_lbl_chargerno">CHARGER NO</label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_chargerno" id="URSRC_tb_chargerno" maxlength='70' class="alphanumeric sizefix login_submitvalidate form-control" readonly>
                        </div></div>
                    <div class="row-fluid form-group">
                        <label  class="col-sm-2" name="URSRC_lbl_btry" id="URSRC_lbl_btry">BATTERY SLNO</label>
                        <div class="col-sm-4">
                            <input type="text" name="URSRC_tb_btry" id="URSRC_tb_btry" maxlength='75' class="alphanumeric sizefix login_submitvalidate form-control" style="width:200px" readonly>
                        </div></div>
                    <div id="URSRC_table_others" class="col-sm-offset-2" hidden>
                        <div class="form-group form-inline col-lg-7">
                            <div class="col-lg-3" style="padding-left:0px"><div class="checkbox">
                                    <label name="URSRC_lbl_laptopbag" id="URSRC_lbl_laptopbag">
                                        <input type="checkbox" name="URSRC_chk_bag" id="URSRC_chk_bag" class="login_submitvalidate">&nbsp;&nbsp;LAPTOP BAG</label>
                                </div></div><div class="">
                                <input type="text" name="URSRC_tb_laptopno" id="URSRC_tb_laptopno" maxlength='75' class="login_submitvalidate form-control " style="display:inline" readonly hidden>
                            </div></div>
                        <div class="form-group form-inline col-lg-7">
                            <div class="col-lg-3" style="padding-left:0px"><div class="checkbox">
                                    <label name="URSRC_lbl_laptopno" id="URSRC_lbl_laptopno">
                                        <input type="checkbox" name="URSRC_chk_mouse" id="URSRC_chk_mouse" class="login_submitvalidate">&nbsp;&nbsp;MOUSE</label>
                                </div></div><div class="">
                                <input type="text" name="URSRC_tb_mouse" id="URSRC_tb_mouse" maxlength='75' class="login_submitvalidate form-control " style="display:inline"  hidden>
                            </div></div>
                        <div class="col-lg-10">
                            <div class="form-group form-inline col-sm-10" style="padding-left:0px"><div class="checkbox">
                                    <label name="URSRC_lbl_dracess" id="URSRC_lbl_dracess">
                                        <input type="checkbox" name="URSRC_chk_dracess" id="URSRC_chk_dracess"  class="login_submitvalidate">&nbsp;&nbsp;DOOR ACCESS</label>
                                </div></div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group form-inline col-sm-10" style="padding-left:0px"><div class="checkbox">
                                    <label name="URSRC_lbl_idcrd" id="URSRC_lbl_idcrd">
                                        <input type="checkbox" name="URSRC_chk_idcrd" id="URSRC_chk_idcrd" class="login_submitvalidate">&nbsp;&nbsp;ID CARD</label>
                                </div></div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group form-inline col-sm-10" style="padding-left:0px"><div class="checkbox">
                                    <label name="URSRC_lbl_headset" id="URSRC_lbl_headset">
                                        <input type="checkbox" name="URSRC_chk_headset" id="URSRC_chk_headset" class="login_submitvalidate">&nbsp;&nbsp;HEAD SET</label>
                                </div></div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group form-inline col-sm-10" style="padding-left:0px">
                                <div class="checkbox">
                                    <label name="URSRC_lbl_aadharno" id="URSRC_lbl_aadharno">
                                        <input type="checkbox" name="URSRC_chk_aadharno" id="URSRC_chk_aadharno" class="login_submitvalidate">&nbsp;&nbsp;AADHAAR NO</label>
                                </div>
                                <input type="text" name="URSRC_tb_aadharno" id="URSRC_tb_aadharno" maxlength='15' class=" alphanumeric sizefix login_submitvalidate form-control " style="display:inline" hidden>
                            </div></div>
                        <div class="col-lg-10">
                            <div class="form-group form-inline col-sm-10" style="padding-left:0px">
                                <div class="checkbox">
                                    <label name="URSRC_lbl_passportno" id="URSRC_lbl_passportno">
                                        <input type="checkbox" name="URSRC_chk_passportno" id="URSRC_chk_passportno" class="login_submitvalidate">&nbsp;&nbsp;PASSPORT NO</label>
                                </div>
                                <input type="text" name="URSRC_tb_passportno" id="URSRC_tb_passportno" maxlength='15' class="alphanumeric sizefix login_submitvalidate form-control" hidden>
                            </div></div>
                        <div class="col-lg-10">
                            <div class="form-group form-inline col-sm-10" style="padding-left:0px">
                                <div class="checkbox">
                                    <label name="URSRC_lbl_votersid" id="URSRC_lbl_votersid">
                                        <input type="checkbox" name="URSRC_chk_votersid" id="URSRC_chk_votersid" class="login_submitvalidate">&nbsp;VOTERS ID</label>
                                </div>
                                <input type="text" name="URSRC_tb_votersid" id="URSRC_tb_votersid" maxlength='25' class="alphanumeric sizefix login_submitvalidate form-control" hidden>
                            </div> </div>
                    </div>
                    <div class="row-fluid form-group col-sm-19">
                        <label class="col-sm-2" name="URSRC_lbl_comments" id="URSRC_lbl_comments">COMMENTS</label>
                        <div class="col-sm-4">
                            <textarea rows="4" cols="50" name="URSRC_ta_comments" id="URSRC_ta_comments" class="maxlength login_submitvalidate  form-control"></textarea>
                        </div></div>
                    <div ID="filetableuploads"> </div>
                    <div class="form-group col-sm-offset-2">
                        <div class="col-sm-9">
                            <div class="form-group">
                                <div class="col-md-20" style="padding-left:0px">
                        <span id="attachprompt"><img width="15" height="15" src="https://ssl.gstatic.com/codesite/ph/images/paperclip.gif" border="0">
                        <a href="javascript:_addAttachmentFields('attachmentarea')" id="attachafile">Attach a file</a>
                        </span></div></div></div>
                    </div>
                    <!--                <br><br> <br><br>-->
                    <div class="col-sm-3">
                        <input class="btn" type="submit"  id="URSRC_btn_login_submitbutton" name="SAVE" value="SUBMIT" disabled hidden />
                    </div>
                    <div id="URSRC_tble_rolesearch" hidden>
                        <div><label id="URSRC_lbl_norole_err" class="errormsg"></label></div>
                        <div class="row-fluid form-group">
                            <lable class="col-sm-2" id="URSRC_lbl_selectrole">SELECT A ROLE<em>*</em></lable>
                            <div class="col-sm-4">
                                <select id='URSRC_lb_selectrole' name="URSRC_lb_rolename" title="ROLE" class='submitvalidate form-control' >

                                    <option value='SELECT' selected="selected"> SELECT</option>
                                </select>
                            </div>
                        </div>
                        <div id="URSRC_rolesearch_roles"></div>

                        <label id="URSRC_lbl_nodetails_err" class="errormsg"></label>
                        <div id="URSRC_tble_menu" hidden ></div>
                        <div id="URSRC_tble_folder" hidden></div>
                        <input class="btn" type="submit"  id="URSRC_btn_submitbutton" name="SAVE" value="SUBMIT"  disabled/>
                    </div><br>
                    <div id="URSRC_btn_update"></div>
            </fieldset>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->

