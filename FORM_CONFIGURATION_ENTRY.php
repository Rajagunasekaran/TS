<!--*********************************************GLOBAL DECLARATION******************************************-->
<!--*********************************************************************************************************//-->
<!--//*******************************************FILE DESCRIPTION*********************************************//
//****************************************CONFIGURATION ENTRY*************************************************//
//DONE BY:LALITHA
//VER 0.03-SD:07/02/2015 ED:07/02/2015,TRACKER NO:74,Corrected Issues:Updated alphabets fr project details nd Changed validation,Updated Preloader position also
/DONE BY:SARADAMBAL
//VER 0.01-SD:06/01/2015 ED:06/01/2015,TRACKER NO:74,IMPLEMENTED PRELOADER POSITION,CHANGED LOGIN ID INTO EMPLOYEE NAME
//*********************************************************************************************************//
<?PHP
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
    //DOCUMENT READY FUNCTION START
    $(document).ready(function(){
        $('.preloader', window.parent.document).show();
        var CONFIG_ENTRY_errmsg=[];
        var CONFIG_ENTRY_mod_opt='<option value="SELECT">SELECT</option>';
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                var CONFIG_ENTRY_values=JSON.parse(xmlhttp.responseText);
                CONFIG_ENTRY_errmsg=CONFIG_ENTRY_values[0];
                var CONFIG_ENTRY_typ_opt='<option value="SELECT">SELECT</option>';
                for (var i=0;i<CONFIG_ENTRY_values[1].length;i++) {
                    CONFIG_ENTRY_mod_opt += '<option value="' + CONFIG_ENTRY_values[1][i][0] + '">' + CONFIG_ENTRY_values[1][i][1] + '</option>';
                }
                $('#CONFIG_ENTRY_lb_module').html(CONFIG_ENTRY_mod_opt);
            }}
        var OPTION="CONFIG_ENTRY_load_mod";
        xmlhttp.open("GET","DB_CONFIGURATION_ENTRY.do?option="+OPTION,true);
        xmlhttp.send(new FormData());
        //CHANGE EVENT FOR MODULE CONFIG
        $(document).on('change','#CONFIG_ENTRY_lb_module',function(){
            $('#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn,#CONFIG_ENTRY_tr_type').empty();
            $('#CONFIG_ENTRY_div_errMod').hide();
            var CONFIG_ENTRY_typ_opt='<option value="SELECT">SELECT</option>';
            var formElement = document.getElementById("CONFIG_ENTRY_form");
            if($(this).val()!='SELECT'){
                $('.preloader', window.parent.document).show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $('.preloader', window.parent.document).hide();
                        var CONFIG_ENTRY_values=JSON.parse(xmlhttp.responseText);
                        if(CONFIG_ENTRY_values.length==0){
                            $('#CONFIG_ENTRY_div_errMod').show();
                            $('#CONFIG_ENTRY_div_errMod').text(CONFIG_ENTRY_errmsg[1].replace('[TYPE]',$("#CONFIG_ENTRY_lb_module option:selected").text()));
                        }else{
                            $('#CONFIG_ENTRY_div_errMod').hide();
                            for (var i=0;i<CONFIG_ENTRY_values.length;i++) {
                                CONFIG_ENTRY_typ_opt += '<option value="' + CONFIG_ENTRY_values[i][0] + '">' + CONFIG_ENTRY_values[i][1] + '</option>';
                            }
                            $('#CONFIG_ENTRY_tr_type').append('<td><label>TYPE<em>*</em></label></td><td><select id="CONFIG_ENTRY_lb_type" name="CONFIG_ENTRY_lb_type"></select></td>')
                            $('#CONFIG_ENTRY_lb_type').html(CONFIG_ENTRY_typ_opt);
                        }
                    }}
                var OPTION="CONFIG_ENTRY_load_type";
                var CONFIG_ENTRY_data=$(this).val();
                xmlhttp.open("GET","DB_CONFIGURATION_ENTRY.do?option="+OPTION+"&module="+CONFIG_ENTRY_data,true);
                xmlhttp.send(new FormData());
            }
            else
            {
                $('#CONFIG_ENTRY_div_errMod').hide();
            }
        });
        //CHANGE EVENT FOR TYPE CONFIG
        $(document).on('change','#CONFIG_ENTRY_lb_type',function(){
            $('#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').empty();
            if($('#CONFIG_ENTRY_lb_type').val()!='SELECT')
            {
                if(($('#CONFIG_ENTRY_lb_type').val()=='7') || ($('#CONFIG_ENTRY_lb_type').val()=='10'))
                {
                    $('#CONFIG_ENTRY_tr_data').append('<td><label>DATA<em>*</em></label></td><td><input type="text" id="CONFIG_ENTRY_tb_data" class="alphabets" name="CONFIG_ENTRY_tb_data"></td><td><div id="CONFIG_ENTRY_div_errmsg" hidden class="errormsg"></div></td>');
                }
                else
                {
                    $('#CONFIG_ENTRY_tr_data').append('<td><label>DATA<em>*</em></label></td><td><input type="text" id="CONFIG_ENTRY_tb_data" name="CONFIG_ENTRY_tb_data"></td><td><div id="CONFIG_ENTRY_div_errmsg" hidden class="errormsg"></div></td>');
                }
                $('#CONFIG_ENTRY_tr_btn').append('<td align="right"><input  type="button" id="CONFIG_ENTRY_btn_save" class="btn" value="SAVE" disabled></td><td><input type="button" id="CONFIG_ENTRY_btn_reset" class="btn" value="RESET"></td>');
                $("#CONFIG_ENTRY_tb_data").doValidation({rule:'alphanumeric',prop:{whitespace:true,uppercase:true,autosize:true}});
                $(".alphabets").doValidation({rule:'alphabets',prop:{whitespace:true,uppercase:true,autosize:true}});
            }
            else
            {
                $('#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').empty();
            }
        });
        //CLICK EVENT FOR BUTTON
        $(document).on('click','#CONFIG_ENTRY_btn_save',function(){
            $('.preloader', window.parent.document).show();
            var formElement = document.getElementById("CONFIG_ENTRY_form");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
                    var CONFIG_ENTRY_msg_alert=xmlhttp.responseText;
                    if(CONFIG_ENTRY_msg_alert==1)
                    {
                        var errmsg=CONFIG_ENTRY_errmsg[2].replace('[MODULE NAME]',$("#CONFIG_ENTRY_lb_module option:selected").text());
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"CONFIGURATION ENTRY",msgcontent:errmsg,position:{top:150,left:530}}});
                    }
                    else if(CONFIG_ENTRY_msg_alert==0)
                    {
                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"CONFIGURATION ENTRY",msgcontent:CONFIG_ENTRY_errmsg[0],position:{top:150,left:530}}});
                    }
                    if(CONFIG_ENTRY_msg_alert==2){
                        $("#CONFIG_ENTRY_btn_save").attr("disabled","disabled");
                        $("#CONFIG_ENTRY_div_errmsg").text(CONFIG_ENTRY_errmsg[3].replace('[TYPE]',$("#CONFIG_ENTRY_lb_type option:selected").text())).show();
                    }
                    else{
                        $('#CONFIG_ENTRY_tr_type,#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').empty();
                    }
                    $('#CONFIG_ENTRY_lb_module').val('SELECT');
                }}
            var OPTION="CONFIG_ENTRY_save";
            xmlhttp.open("POST","DB_CONFIGURATION_ENTRY.do?option="+OPTION,true);
            xmlhttp.send(new FormData(formElement));
        });
        //CHANGE FUNCTION FOR DATA
        $(document).on('change blur','#CONFIG_ENTRY_tb_data',function(){
            var formElement = document.getElementById("CONFIG_ENTRY_form");
            if($(this).val()!=''){
                $('.preloader', window.parent.document).show();
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $('.preloader', window.parent.document).hide();
                        if(xmlhttp.responseText==1){
                            $("#CONFIG_ENTRY_div_errmsg").show();
                            $("#CONFIG_ENTRY_div_errmsg").text(CONFIG_ENTRY_errmsg[3].replace('[TYPE]',$("#CONFIG_ENTRY_lb_type option:selected").text()));}
                        else
                            $("#CONFIG_ENTRY_div_errmsg").text('');
                        if(xmlhttp.responseText==0)
                            $("#CONFIG_ENTRY_btn_save").removeAttr("disabled","disabled");
                        else
                            $("#CONFIG_ENTRY_btn_save").attr("disabled","disabled");

                    }}
                var OPTION="CONFIG_ENTRY_check_data";
                var CONFIG_ENTRY_data=$(this).val();
                xmlhttp.open("POST","DB_CONFIGURATION_ENTRY.do?option="+OPTION,true);
                xmlhttp.send(new FormData(formElement));}
            else
            {
                $("#CONFIG_ENTRY_btn_save").attr("disabled","disabled");
            }
        });
        //CLICK EVENT FOR BUTTON RESET
        $(document).on('click','#CONFIG_ENTRY_btn_reset',function(){
            $('#CONFIG_ENTRY_tr_type,#CONFIG_ENTRY_tr_data,#CONFIG_ENTRY_tr_btn').empty();
            $('#CONFIG_ENTRY_lb_module').val('SELECT');
        });
    });
    //DOCUMENT READY FUNCTION END
</script>
<!--SCRIPT TAG END-->
</head>
<!--HEAD TAG END-->
<!--BODY TAG START-->
<body class="dt-example">
<div class="wrapper">
    <div class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"/></div></div></div>
    <div class="title" id="fhead"><div style="padding-left:500px; text-align:left;"><p><h3>CONFIGURATION ENTRY</h3><p></div></div>
    <form class="content" name="CONFIG_ENTRY_form" id="CONFIG_ENTRY_form" autocomplete="off" >
        <table><tr><td width="200px"> <label>MODULE NAME<em>*</em></label></td>
                <td ><select id="CONFIG_ENTRY_lb_module" name="CONFIG_ENTRY_lb_module"></select></td><td><div id="CONFIG_ENTRY_div_errMod" hidden class="errormsg"></div></td></tr>
            <tr id="CONFIG_ENTRY_tr_type"></tr>
            <tr id="CONFIG_ENTRY_tr_data"></tr>
            <tr id="CONFIG_ENTRY_tr_btn"></tr>
        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->