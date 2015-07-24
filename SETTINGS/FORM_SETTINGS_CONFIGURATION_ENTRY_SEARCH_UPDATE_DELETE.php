<!--*********************************************GLOBAL DECLARATION******************************************-->
<!--*********************************************************************************************************//-->
<!--//*******************************************FILE DESCRIPTION*********************************************//
//****************************************CONFIGURATION ENTRY*************************************************//
/DONE BY:ARTHI
//VER 0.05-SD:25/06/2015 ED:27/06/2015,corrected the trigger fires multiple times,did some responsive
/DONE BY:ARTHI
//VER 0.04-SD:11/06/2015 ED:11/06/2015,UPDATE PART IS NOT WORKING FIXED THAT PART
//DONE BY:LALITHA
//VER 0.03-SD:07/02/2015 ED:07/02/2015,TRACKER NO:74,Corrected Issues:Updated alphabets fr project details nd Changed validation,Updated Preloader position also
/DONE BY:SARADAMBAL
//VER 0.01-SD:06/01/2015 ED:06/01/2015,TRACKER NO:74,IMPLEMENTED PRELOADER POSITION,CHANGED LOGIN ID INTO EMPLOYEE NAME
//*********************************************************************************************************//
<?php
include "../TSLIB/TSLIB_HEADER.php";
?>
<!--SCRIPT TAG START-->
<html>
<head>
    <script>

        //DOCUMENT READY FUNCTION START

        $(document).ready(function(){
            var flag=0;
            $(".preloader").hide();
            var CONFIG_ENTRY_errmsg;
            var CONFIG_ENTRY_mod_opt;
            var pre_tds;
            var CONFIG_SRCH_UPD_errmsg=[];
            var CONFIG_SRCH_UPD_mod_opt;
            $(document).on('change','.crclick',function(evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                var radiooption=$(this).val();

                if(radiooption=='entry'){

                    $('#entry').show();
                    $('#search_update').hide();
                    $('#CONFIG_SRCH_UPD_lb_module').val('SELECT');
                    $('#CONFIG_SRCH_UPD_tr_type').hide();
                    $('#CONFIG_SRCH_UPD_err_flex').hide();
                    $('#CONFIG_SRCH_UPD_div_errMod').hide();
                    $('#CONFIG_SRCH_UPD_tble_config').hide();
                    $('#CONFIG_ENTRY_div_errMod').hide();
                    $(".preloader").show();
                    CONFIG_ENTRY_errmsg=[];
                    CONFIG_ENTRY_mod_opt='<option value="SELECT">SELECT</option>';
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            var CONFIG_ENTRY_values=JSON.parse(xmlhttp.responseText);
                            CONFIG_ENTRY_errmsg=CONFIG_ENTRY_values[0];
                            var CONFIG_ENTRY_typ_opt='<option value="SELECT">SELECT</option>';
                            for (var i=0;i<CONFIG_ENTRY_values[1].length;i++) {
                                CONFIG_ENTRY_mod_opt += '<option value="' + CONFIG_ENTRY_values[1][i][0] + '">' + CONFIG_ENTRY_values[1][i][1] + '</option>';
                            }
                            $('#CONFIG_ENTRY_lb_module').html(CONFIG_ENTRY_mod_opt);
                        }}
                    var OPTION="CONFIG_ENTRY_load_mod";
                    xmlhttp.open("GET","SETTINGS/DB_SETTINGS_CONFIGURATION_ENTRY_SEARCH_UPDATE_DELETE.do?option="+OPTION,true);
                    xmlhttp.send(new FormData());
                }
                else if(radiooption=='search_update'){
                    $('#search_update').show();
                    $('#entry').hide();
                    $('#CONFIG_ENTRY_lb_module').val('SELECT');
                    $('#CONFIG_ENTRY_tr_type').hide();
                    $('#CONFIG_ENTRY_tr_data').hide();
                    $('#CONFIG_ENTRY_tr_btn').hide();
                    $('#CONFIG_SRCH_UPD_div_errmsg').hide();
                    $('#STDTL_SEARCH_div_flexdata_result').hide();
                    $('#CONFIG_SRCH_UPD_tble_config_wrapper').hide();
                    $(".preloader").show();
                    CONFIG_SRCH_UPD_mod_opt='<option>SELECT</option>';
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();

                            var CONFIG_SRCH_UPD_values=JSON.parse(xmlhttp.responseText);
                            CONFIG_SRCH_UPD_errmsg=CONFIG_SRCH_UPD_values[0];
                            var CONFIG_SRCH_UPD_typ_opt='<option value="SELECT">SELECT</option>';
                            for (var i=0;i<CONFIG_SRCH_UPD_values[1].length;i++) {
                                CONFIG_SRCH_UPD_mod_opt += '<option value="' + CONFIG_SRCH_UPD_values[1][i][0] + '">' + CONFIG_SRCH_UPD_values[1][i][1] + '</option>';
                            }
                            $('#CONFIG_SRCH_UPD_lb_module').html(CONFIG_SRCH_UPD_mod_opt);
                        }}
                    var OPTION="CONFIG_SRCH_UPD_load_mod";
                    xmlhttp.open("GET","SETTINGS/DB_SETTINGS_CONFIGURATION_ENTRY_SEARCH_UPDATE_DELETE.do?option="+OPTION);
                    xmlhttp.send(new FormData());
                }
            });
            //CHANGE EVENT FOR MODULE CONFIG
            $(document).on('change','#CONFIG_ENTRY_lb_module',function(evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                $('#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn,#CONFIG_ENTRY_tr_type').html('');
                $('#CONFIG_ENTRY_div_errMod').hide();
                var CONFIG_ENTRY_typ_opt='<option value="SELECT">SELECT</option>';
                var formElement = document.getElementById("CONFIG_ENTRY_form");
                if($(this).val()!='SELECT'){
                    $(".preloader").show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            var CONFIG_ENTRY_values=JSON.parse(xmlhttp.responseText);
                            $(".preloader").hide();
                            if(CONFIG_ENTRY_values.length==0){
                                $('#CONFIG_ENTRY_div_errMod').show();
                                $('#CONFIG_ENTRY_div_errMod').text(CONFIG_ENTRY_errmsg[1].replace('[TYPE]',$("#CONFIG_ENTRY_lb_module option:selected").text()));
                            }else{
                                $('#CONFIG_ENTRY_div_errMod').hide();
                                for (var i=0;i<CONFIG_ENTRY_values.length;i++) {
                                    CONFIG_ENTRY_typ_opt += '<option value="' + CONFIG_ENTRY_values[i][0] + '">' + CONFIG_ENTRY_values[i][1] + '</option>';
                                }
//                                $("#BDLY_SRC_div_searchresult").html('')
                                $('#CONFIG_ENTRY_tr_type').html('').append('<div class="row-fluid form-group"><label class="col-sm-2">TYPE<em>*</em></label><div class="col-sm-4"><select id="CONFIG_ENTRY_lb_type" name="CONFIG_ENTRY_lb_type" class="form-control"></select></div></div>')
                                $('#CONFIG_ENTRY_lb_type').html(CONFIG_ENTRY_typ_opt);
                                $('#CONFIG_ENTRY_tr_type').show();
//                              $('#CONFIG_ENTRY_lb_type').show();
                            }
                        }}
                    var OPTION="CONFIG_ENTRY_load_type";
                    var CONFIG_ENTRY_data=$(this).val();
                    xmlhttp.open("GET","SETTINGS/DB_SETTINGS_CONFIGURATION_ENTRY_SEARCH_UPDATE_DELETE.do?option="+OPTION+"&module="+CONFIG_ENTRY_data,true);
                    xmlhttp.send(new FormData());
                }
                else
                {
                    $('#CONFIG_ENTRY_div_errMod').hide();
                }
            });
            //CHANGE EVENT FOR TYPE CONFIG
            $(document).on('change','#CONFIG_ENTRY_lb_type',function(evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                $('#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').html('');
                if($('#CONFIG_ENTRY_lb_type').val()!='SELECT')
                {
                    $("#CONFIG_ENTRY_tr_data").empty('');
                    if(($('#CONFIG_ENTRY_lb_type').val()=='7') || ($('#CONFIG_ENTRY_lb_type').val()=='10'))
                    {
                        $('#CONFIG_ENTRY_tr_data').html('').append('<div class="row-fluid form-group"><label class="col-sm-2">DATA<em>*</em></label><div class="col-sm-4"><input type="text" id="CONFIG_ENTRY_tb_data" class="alphabets" name="CONFIG_ENTRY_tb_data"><div id="CONFIG_ENTRY_div_errmsg" hidden class="errormsg"></div></div></div>');
                    }
                    else if($('#CONFIG_ENTRY_lb_type').val()=='23')
                    {
                        $('#CONFIG_ENTRY_tr_data').html('').append('<div class="row-fluid form-group"><label class="col-sm-2">LAPTOP NUMBER<em>*</em></label><div class="col-sm-4"><input type="text" id="LN_CONFIG_ENTRY_tb_data" class="form-control "name="LN_CONFIG_ENTRY_tb_data"></div></div><div class="row-fluid from-group"><label class="col-sm-2">CHARGER NUMBER<em>*</em></label><div class="col-sm-4"><input type="text" id="CN_CONFIG_ENTRY_tb_data" class="form-control" name="CN_CONFIG_ENTRY_tb_data"></div></div><div class="row-fluid from-group"><label class="col-sm-2">BATTERY NUMBER<em>*</em></label><div class="col-sm-4"><input type="text" id="BN_CONFIG_ENTRY_tb_data" class="form-control" name="BN_CONFIG_ENTRY_tb_data"></div></div><div class="row-fluid from-group"><label class="col-sm-2">LAPBAG NUMBER<em>*</em></label><div class="col-sm-4"><input type="text" id="LBN_CONFIG_ENTRY_tb_data" class="form-control" name="LBN_CONFIG_ENTRY_tb_data"></div></div><div class="row-fluid from-group"><label class="col-sm-2">MOUSE NUMBER</label><div class="col-sm-4"><input type="text" id="MN_CONFIG_ENTRY_tb_data" class="form-control" name="MN_CONFIG_ENTRY_tb_data"><div id="CONFIG_ENTRY_div_errmsg" hidden class="errormsg"></div></div></div>');
                    }
                    else if($('#CONFIG_ENTRY_lb_type').val()=='22')
                    {
                        $('#CONFIG_ENTRY_tr_data').html('').append('<div class="row-fluid form-group"><label class="col-sm-2">RELATIONHOOD TYPE<em>*</em></label><div class="col-sm-4"><input type="text" id="CONFIG_ENTRY_tb_data" class="alphabets" name="CONFIG_ENTRY_tb_data"><div id="CONFIG_ENTRY_div_errmsg" hidden class="errormsg"></div></div></div>');
                    }
                    else if($('#CONFIG_ENTRY_lb_type').val()=='24')
                    {
                        $('#CONFIG_ENTRY_tr_data').html('').append('<div class="row-fluid form-group"><label class="col-sm-2">EMPLOYEE DESIGNATION<em>*</em></label><div class="col-sm-4"><input type="text" id="CONFIG_ENTRY_tb_data" class="alphabets" name="CONFIG_ENTRY_tb_data"><div id="CONFIG_ENTRY_div_errmsg" hidden class="errormsg"></div></div></div>');
                    }
                    else
                    {
                        $('#CONFIG_ENTRY_tr_data').html('').append('<div class="row-fluid form-group"><label class="col-sm-2">DATA<em>*</em></label><div class="col-sm-4"><input type="text" id="CONFIG_ENTRY_tb_data" name="CONFIG_ENTRY_tb_data"><div id="CONFIG_ENTRY_div_errmsg" hidden class="errormsg"></div></div></div>');
                        $('#CONFIG_ENTRY_tr_data').show();
                    }
                    $('#CONFIG_ENTRY_tr_btn').append('<div class="row-fluid" style="padding-left: 15px"><input  type="button" id="CONFIG_ENTRY_btn_save" class="btn" value="SAVE" disabled>&nbsp;&nbsp;<input type="button" id="CONFIG_ENTRY_btn_reset" class="btn" value="RESET"></div>');
                    $("#CONFIG_ENTRY_tb_data").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
                    $("#CN_CONFIG_ENTRY_tb_data").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
                    $("#LN_CONFIG_ENTRY_tb_data").doValidation({rule:'',prop:{whitespace:true,uppercase:true,autosize:true}});
                    $("#BN_CONFIG_ENTRY_tb_data").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
                    $("#LBN_CONFIG_ENTRY_tb_data").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
                    $("#MN_CONFIG_ENTRY_tb_data").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
                    $(".alphabets").doValidation({rule:'alphabets',prop:{whitespace:true,uppercase:true,autosize:true}});
                    $('#CONFIG_ENTRY_tr_data').show();
                    $('#CONFIG_ENTRY_tr_btn').show();
                }
                else
                {
                    $('#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').html('');
                }
            });
            //CLICK EVENT FOR BUTTON
            $(document).on('click','#CONFIG_ENTRY_btn_save',function(evt){

                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                $(".preloader").show();
                var formElement = document.getElementById("CONFIG_ENTRY_form");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();

                        var CONFIG_ENTRY_msg_alert=xmlhttp.responseText;
                        var errormessage=CONFIG_ENTRY_msg_alert;

                        if(errormessage==1)
                        {

                            show_msgbox("CONFIGURATION ENTRY",CONFIG_ENTRY_errmsg[2].replace('[MODULE NAME]',$("#CONFIG_ENTRY_lb_module option:selected").text()),"success",false);

                        }
                        else if(errormessage==0)
                        {


                            show_msgbox("CONFIGURATION ENTRY",CONFIG_ENTRY_errmsg[0],"success",false);
                        }
                        if(errormessage==2){
                            $("#CONFIG_ENTRY_btn_save").attr("disabled","disabled");
                            $("#CONFIG_ENTRY_div_errmsg").text(CONFIG_ENTRY_errmsg[3].replace('[TYPE]',$("#CONFIG_ENTRY_lb_type option:selected").text())).show();
                        }

                        else{

                            $('#CONFIG_ENTRY_tr_type,#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').html('');
                        }

                        $('#CONFIG_ENTRY_lb_module').val('SELECT');
                    }}

                var OPTION="CONFIG_ENTRY_save";
                xmlhttp.open("POST","SETTINGS/DB_SETTINGS_CONFIGURATION_ENTRY_SEARCH_UPDATE_DELETE.do?option="+OPTION,true);
                xmlhttp.send(new FormData(formElement));

            });
//CHANGE FUNCTION FOR DATA
            $(document).on('change blur','#CONFIG_ENTRY_tb_data',function(evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                var formElement = document.getElementById("CONFIG_ENTRY_form");
                if($(this).val()!=''){
                    $(".preloader").show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            if(xmlhttp.responseText==1){
                                $("#CONFIG_ENTRY_div_errmsg").show();
                                $("#CONFIG_ENTRY_div_errmsg").text(CONFIG_ENTRY_errmsg[3].replace('[TYPE]',$("#CONFIG_ENTRY_lb_type option:selected").text()));}
                            else
                                $("#CONFIG_ENTRY_div_errmsg").text('');
                            if(xmlhttp.responseText==0)
                                $("#CONFIG_ENTRY_btn_save").removeAttr("disabled","disabled");
                            else
//                        $("#CONFIG_ENTRY_btn_save").attr("disabled","disabled");
                                $("#CONFIG_ENTRY_btn_save").removeAttr("disabled","disabled");

                        }}
                    var OPTION="CONFIG_ENTRY_check_data";
                    var CONFIG_ENTRY_data=$(this).val();
                    xmlhttp.open("POST","SETTINGS/DB_SETTINGS_CONFIGURATION_ENTRY_SEARCH_UPDATE_DELETE.do?option="+OPTION,true);
                    xmlhttp.send(new FormData(formElement));}
                else
                {
                    $("#CONFIG_ENTRY_btn_save").attr("disabled","disabled");
                }
            });
            //CHANGE EVENT FOR MODULE CONFIG
            $(document).on('change','#CONFIG_SRCH_UPD_lb_module',function(evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                $('#CONFIG_SRCH_UPD_err_flex').hide();
                $('#STDTL_SEARCH_div_flexdata_result').hide();
                $('#CONFIG_SRCH_UPD_tr_data,#CONFIG_SRCH_UPD_tr_btn,#CONFIG_SRCH_UPD_tr_type,section').html('');
                $('#CONFIG_SRCH_UPD_div_errMod').hide();
                var CONFIG_SRCH_UPD_typ_opt='<option value="SELECT">SELECT</option>';
                var formElement = document.getElementById("CONFIG_ENTRY_form");
                if($(this).val()!='SELECT'){
                    $(".preloader").show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            var CONFIG_SRCH_UPD_values=JSON.parse(xmlhttp.responseText);
                            if(CONFIG_SRCH_UPD_values.length==0){
                                $('#CONFIG_SRCH_UPD_div_errMod').show();
                                $('#CONFIG_SRCH_UPD_div_errMod').text(CONFIG_SRCH_UPD_errmsg[5].replace('[TYPE]',$("#CONFIG_SRCH_UPD_lb_module option:selected").text()));
                            }else{
                                $('#CONFIG_SRCH_UPD_div_errMod').hide();
                                for (var i=0;i<CONFIG_SRCH_UPD_values.length;i++) {
                                    CONFIG_SRCH_UPD_typ_opt += '<option value="' + CONFIG_SRCH_UPD_values[i][0] + '">' + CONFIG_SRCH_UPD_values[i][1] + '</option>';
                                }
                                $('#CONFIG_SRCH_UPD_tr_type').html('').append('<div class="row-fluid form-group"><label id="search_type" class="col-sm-2">TYPE<em>*</em></label><div class="col-sm-4"><select id="CONFIG_SRCH_UPD_lb_type" name="CONFIG_SRCH_UPD_lb_type" class="form-control"></select></div></div>')
                                $('#CONFIG_SRCH_UPD_lb_type').html(CONFIG_SRCH_UPD_typ_opt);
                                $('#CONFIG_SRCH_UPD_tr_type').show();
                            }
                        }
                    }
                    var OPTION="CONFIG_SRCH_UPD_load_type";
                    var CONFIG_SRCH_UPD_data=$(this).val();
                    xmlhttp.open("GET","SETTINGS/DB_SETTINGS_CONFIGURATION_ENTRY_SEARCH_UPDATE_DELETE.do?option="+OPTION+"&module="+CONFIG_SRCH_UPD_data,true);
                    xmlhttp.send(new FormData());
                }
            });
//FUNCTION FOR FETCHING DATA FOR FLEX TABLE

            function CONFIG_SRCH_UPD_fetch_configdata(){
                flag=0;

                var formElement = document.getElementById("CONFIG_ENTRY_form");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $(".preloader").hide();
//                alert(xmlhttp.responseText);
                        var CONFIG_SRCH_UPD_values=JSON.parse(xmlhttp.responseText);
                        $('section').html(CONFIG_SRCH_UPD_values);
                        $('#CONFIG_SRCH_UPD_tble_config').show();
                        $('#CONFIG_SRCH_UPD_tr_type').show();
                        var oTable=$('#CONFIG_SRCH_UPD_tble_config').DataTable( {
                            "aaSorting": [],
                            "pageLength": 10,
                            "responsive": true,
                            "sPaginationType":"full_numbers"
                        });
                        if(oTable.rows().data().length==0){
                            $('#CONFIG_SRCH_UPD_err_flex').text(CONFIG_SRCH_UPD_errmsg[6].replace('[TYPE]',$("#CONFIG_SRCH_UPD_lb_type option:selected").text())).show();
                            $('#CONFIG_SRCH_UPD_err_flex').show();
                            $('section').html('');
                            $('#CONFIG_SRCH_UPD_tble_config').hide();
                        }
                        else{
                            $('#CONFIG_SRCH_UPD_div_errmsg').removeClass('errormsg').addClass('srctitle');
                            $('#CONFIG_SRCH_UPD_div_errmsg').text(CONFIG_SRCH_UPD_errmsg[3].replace('[TYPE]',$("#CONFIG_SRCH_UPD_lb_type option:selected").text()));
                            $('#CONFIG_SRCH_UPD_div_errmsg').show();
                        }
                        if(CONFIG_flag_upd==1 && flag==0){
                            var errmsg=CONFIG_SRCH_UPD_errmsg[4].replace('[MODULE NAME]',$("#CONFIG_SRCH_UPD_lb_module option:selected").text());
                            show_msgbox("CONFIGURATION ENTRY",errmsg,"success",false);}
                    }}
                var OPTION="CONFIG_SRCH_UPD_load_data";
                xmlhttp.open("POST","SETTINGS/DB_SETTINGS_CONFIGURATION_ENTRY_SEARCH_UPDATE_DELETE.do?option="+OPTION,true);
                xmlhttp.send(new FormData(formElement));
            }
            //CHANGE EVENT FOR TYPE CONFIG
            $(document).on('change','#CONFIG_SRCH_UPD_lb_type',function(evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                CONFIG_flag_upd=0;
                $('section').html('');
                $('#CONFIG_SRCH_UPD_tble_config').hide();
                $('#CONFIG_SRCH_UPD_err_flex').hide();
                $('#STDTL_SEARCH_div_flexdata_result').hide();
                if($(this).val()!='SELECT'){
                    $(".preloader").show();
                    $('#STDTL_SEARCH_div_flexdata_result').show();
                    CONFIG_SRCH_UPD_fetch_configdata();
                }
            });
//EDIT CLICK FUNCTION FOR UPDATE FORM
            $(document).on('click','.edit',function(evt){
//        alert('edit');
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                if($(this).hasClass( "deletion" )==true)
                {

                    $(this).val('UPDATE').addClass('update').removeClass('edit');$(this).next().val('CANCEL').addClass('cancel').removeClass('delete');
                }
                else
                {
                    $(this).val('UPDATE').addClass('update').removeClass('edit');$(this).next().val('CANCEL').addClass('cancl');
                }
                $(this).attr("disabled","disabled");
                $('.edit').attr("disabled","disabled");
                $('.cancel').attr("disabled","disabled");
                $('.cancl').attr("disabled","disabled");
                $('.delete').attr("disabled","disabled");
                $(this).next().removeAttr("disabled","disabled");
                var edittrid=$(this).parent().parent().attr('id');
//        alert(edittrid);
                var tds = $('#'+edittrid).children('td');
                var td=$(tds[0]).attr('id');
                pre_tds = $(tds[0]).html();
                var tdstr = '';
                var final_data_length=($(tds[0]).html()).length;
                if(($('#CONFIG_SRCH_UPD_lb_type').val()=='7') || ($('#CONFIG_SRCH_UPD_lb_type').val()=='10'))
                {
                    tdstr += "<input type='text' id='CONFIG_SRCH_UPD_tb_data' name='CONFIG_SRCH_data' class='alphabets'    value='"+$(tds[0]).html()+"'>";
                }
                else if($('#CONFIG_SRCH_UPD_lb_module').val()=='2')
                {

                    tdstr += "<input type='text' id='CONFIG_SRCH_UPD_tb_data' name='CONFIG_SRCH_data' class='reports'    value='"+$(tds[0]).html()+"'>";
                }
                else
                {

                    tdstr += "<input type='text' id='CONFIG_SRCH_UPD_tb_data' name='CONFIG_SRCH_data' class='autosize'    value='"+$(tds[0]).html()+"'>";
                }
                $('#'+td).html(tdstr);
                $('#CONFIG_SRCH_UPD_tb_data').attr("size",final_data_length+3);
                $('#CONFIG_SRCH_UPD_tb_data').show();
                $(".alphabets").doValidation({rule:'alphabets',prop:{whitespace:true,uppercase:true,autosize:true}});
                $(".autosize").doValidation({rule:'general',prop:{autosize:true,uppercase:false}});
                $(".reports").doValidation({rule:'general',prop:{autosize:true,whitespace:true,uppercase:true}});
            });
            //CLICK EVENT FOR CANCEL BUTTON
            $(document).on("click",'.cancel', function (evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                if(pre_tds!='')
                {
                    $(this).val('DELETE').addClass('delete');
                    $('.edit').removeAttr("disabled");//after
                    $('.cancel').removeAttr("disabled","disabled");
                    $('.cancl').removeAttr("disabled","disabled");
                    $('.delete').removeAttr("disabled","disabled");//after
                    var edittrid = $(this).parent().parent().attr('id');
                    var tds = $('#'+edittrid).children('td');
                    var td=$(tds[0]).attr('id');
                    $('#'+td).html(pre_tds);
                }
                pre_tds='';
            });
            //CLICK EVENT FOR DB CANCEL BUTTON
            $(document).on("click",'.cancl', function (evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                if(pre_tds!='')
                {
                    $(this).prev().val('EDIT').addClass('edit').removeClass('update');
                    $('.edit').removeAttr("disabled");//after
                    $('.cancel').removeAttr("disabled","disabled");
                    $('.cancl').removeAttr("disabled","disabled");
                    $('.delete').removeAttr("disabled","disabled");//after
                    var edittrid = $(this).parent().parent().attr('id');
                    var tds = $('#'+edittrid).children('td');
                    var td=$(tds[0]).attr('id');
                    $('#'+td).html(pre_tds);
                }
                pre_tds='';
            });
            var CONFIG_flag_upd;
            //CLICK EVENT FOR BUTTON UPDATE
            $(document).on('click','.update',function(evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                CONFIG_flag_upd=0;
                var config_type=$('#CONFIG_SRCH_UPD_lb_type').val();
                var CONFIG_id=$(this).parent().parent().attr('id');
                $(".preloader").show();
                var formElement = document.getElementById("CONFIG_ENTRY_form");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var msg_alert=JSON.parse(xmlhttp.responseText)
                        var success_flag=msg_alert[0];
                        var  CONFIG_SRCH_UPD_msg_alert=success_flag;
                        var fileid=msg_alert[2];
                        var failure_flag=msg_alert[1];
                        if(CONFIG_SRCH_UPD_msg_alert==1 )
                        {
                            CONFIG_flag_upd=1;
                        }
                        else if(CONFIG_SRCH_UPD_msg_alert==0 && failure_flag==1)
                        {

                            $(".preloader").hide();
                            show_msgbox("CONFIGURATION ENTRY",CONFIG_SRCH_UPD_errmsg[0],"success",false);
                        }
                        if(CONFIG_SRCH_UPD_msg_alert==2){

                            $(".preloader").hide();
                            $(".update").attr("disabled","disabled");
                            show_msgbox("CONFIGURATION ENTRY",errmsg,"success",false);
                        }
                        else if(CONFIG_SRCH_UPD_msg_alert!=0){
                            CONFIG_SRCH_UPD_fetch_configdata();
//                    first();
                        }
                        else if(failure_flag==0){
                            $(".preloader").hide();
                            if((config_type==9)||(config_type==12)){
                                var msg= CONFIG_SRCH_UPD_errmsg[2].replace("[SSID]",fileid)
                            }
                            else if(config_type==17){

                                var msg=CONFIG_SRCH_UPD_errmsg[9].replace("[FID]",fileid)
                            }
                            else if(config_type==13){
                                var msg=CONFIG_SRCH_UPD_errmsg[1];

                            }
                            show_msgbox("CONFIGURATION ENTRY",msg,"success",false);
                        }
                    }}
                var OPTION="CONFIG_SRCH_UPD_save";
                xmlhttp.open("POST","SETTINGS/DB_SETTINGS_CONFIGURATION_ENTRY_SEARCH_UPDATE_DELETE.do?option="+OPTION+"&CONFIG_SRCH_UPD_id="+CONFIG_id+"&CONFIG_SRCH_UPD_tb_data="+$('#CONFIG_SRCH_UPD_tb_data').val(),true);
                xmlhttp.send(new FormData(formElement));
            });
            var CONFIG_flag_del;
            //CLICK EVENT FOR BUTTON UPDATE
            $(document).on('click','.delete',function(evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                CONFIG_flag_del=0;
                var CONFIG_id=$(this).parent().parent().attr('id');
                $(".preloader").show();
                var formElement = document.getElementById("CONFIG_ENTRY_form");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var CONFIG_SRCH_UPD_msg_alert=xmlhttp.responseText;
                        if(CONFIG_SRCH_UPD_msg_alert==1)
                        {
                            $(".delete").attr("disabled","disabled");
                            show_msgbox("CONFIGURATION SEARCH/UPDATE/DELETE",CONFIG_SRCH_UPD_errmsg[6].replace('[MODULE NAME]',$("#CONFIG_SRCH_UPD_lb_type option:selected").text()),"success",false);
                            CONFIG_SRCH_UPD_fetch_configdata();
//                    first();
                            flag=1;

                        }
                        else
                        {
                            show_msgbox("CONFIGURATION SEARCH/UPDATE/DELETE",CONFIG_SRCH_UPD_errmsg[7],"success",false);

                        }
                        $(".preloader").hide();

                    }
                }
                var OPTION="CONFIG_SRCH_UPD_delete";
                xmlhttp.open("POST","SETTINGS/DB_SETTINGS_CONFIGURATION_ENTRY_SEARCH_UPDATE_DELETE.do?option="+OPTION+"&CONFIG_SRCH_UPD_id="+CONFIG_id+"&CONFIG_SRCH_UPD_tb_data="+$('#CONFIG_SRCH_UPD_tb_data').val(),true);
                xmlhttp.send(new FormData(formElement));
            });
            //CHANGE FUNCTION FOR DATA
            $(document).on('blur','#CONFIG_SRCH_UPD_tb_data',function(evt){
                evt.stopPropagation();
                evt.preventDefault();
                evt.stopImmediatePropagation();
                var txt_area=$(this).val().trim();
                var formElement = document.getElementById("CONFIG_ENTRY_form");
                if((txt_area!='') && txt_area!=pre_tds){
                    $(".preloader").show();
                    var xmlhttp=new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            $(".preloader").hide();
                            if(xmlhttp.responseText==1){
                                $(".update").attr("disabled","disabled");
                                show_msgbox("CONFIGURATION SEARCH/UPDATE/DELETE",CONFIG_SRCH_UPD_errmsg[8].replace('[TYPE]',$("#CONFIG_SRCH_UPD_lb_type option:selected").text()),"success",false);
                                $(this).addClass('invalid');}
                            else{
                                $(".update").removeAttr("disabled","disabled");
                                $(this).removeClass('invalid');
                            }
                        }
                    }
                    var OPTION="CONFIG_SRCH_UPD_check_data";
                    var CONFIG_SRCH_UPD_data=$(this).val();
                    xmlhttp.open("POST","SETTINGS/DB_SETTINGS_CONFIGURATION_ENTRY_SEARCH_UPDATE_DELETE.do?option="+OPTION+"&CONFIG_SRCH_UPD_tb_data="+txt_area,true);
                    xmlhttp.send(new FormData(formElement));
                }
                else{

                    $(".update").attr("disabled","disabled");
                }
            });
//CLICK EVENT FOR BUTTON RESET
            $(document).on('click','#CONFIG_ENTRY_btn_reset',function(){
                $('#CONFIG_ENTRY_tr_type,#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').html('');
                $('#CONFIG_ENTRY_lb_module').val('SELECT');
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
    <div class="title text-center"><h4><b>CONFIGURATION ENTRY</b></h4></div>
    <form class="content" name="CONFIG_ENTRY_form" id="CONFIG_ENTRY_form" autocomplete="off" >
        <div class="panel-body">
            <fieldset>
                <div style="padding-left: 15px">
                    <div class="radio">
                        <label name="UR_lbl_entry" id="UR_lbl_entry">
                            <input type="radio" name="UR_ESU" id="UR_ENTRY" class="crclick" value="entry">ENTRY</label>
                    </div>

                    <div class="radio">
                        <label name="UR_lbl_search_update" id="UR_lbl_search_update">
                            <input type="radio" name="UR_ESU" id="UR_SEARCH_UPDATE" class="crclick" value="search_update">SEARCH / UPDATE</label>
                    </div></div>
                <div id="entry" hidden>
                    <div class="row-fluid form-group">
                        <label class="col-sm-2" id="CONFIG_ENTRY_lbl_module">MODULE NAME<em>*</em></label>
                        <div class="col-sm-4">
                            <select id="CONFIG_ENTRY_lb_module" name="CONFIG_ENTRY_lb_module" class="form-control"></select>
                        </div></div>
                    <div id="CONFIG_ENTRY_div_errMod" hidden class="errormsg"></div>
                    <div id="CONFIG_ENTRY_tr_type"></div>
                    <div id="CONFIG_ENTRY_tr_data"></div>
                    <div id="CONFIG_ENTRY_tr_btn"></div>
                </div>
                <div id="search_update" hidden>
                    <div class=" row-fluid form-group">
                        <label class="col-sm-2" id="CONFIG_SRCH_UPD_lbl_module">MODULE NAME<em>*</em></label>
                        <div class="col-sm-4">
                            <select id="CONFIG_SRCH_UPD_lb_module" name="CONFIG_SRCH_UPD_lb_module" class="form-control"></select>
                        </div></div>
                    <div id="CONFIG_SRCH_UPD_div_errMod" hidden class="errormsg"></div>
                    <br>
                    <div id="CONFIG_SRCH_UPD_tr_type"></div>
                    <!--                    <div class="table-responsive">-->
                    <div id="STDTL_SEARCH_div_flexdata_result" style="padding-left: 15px" class="table-responsive" hidden>
                        <section style="max-width:500px;">
                        </section>
                    </div>
                    <div><label id="CONFIG_SRCH_UPD_err_flex" name="CONFIG_SRCH_UPD_err_flex" class="errormsg" hidden></label></div>
                </div>
            </fieldset>
        </div>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->