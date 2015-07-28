<!--//*******************************************FILE DESCRIPTION*********************************************//
//*******************************************MENU*********************************************//
//DONE BY:SAFI
//VER 0.05,SD:06/01/2015 ED:06/01/2015,TRACKER NO 74,DESC:VALIDATION DONE FOR CLOCK OUT FUNCTION
//DONE BY:SASIKALA
//VER 0.04,SD:26/12/2014 ED:27/12/2014,TRACKER NO 74,DESC:DISPLAYED ANALOG CLOCK AND SAVEPART FOR CLOCK IN TIME
//DONE BY:SAFI
//VER 0.03,SD:10/11/2014 ED:11/11/2014,TRACKER NO 74,DESC:PRELOADER UPDATED WHEN MENU CLICK;
//VER 0.02, SD:29/10/2014 ED:29/10/2014,TRACKER NO:74,DESC:alignment changed.
//VER 0.01-INITIAL VERSION, SD:18/08/2014 ED:27/09/2014,TRACKER NO:79
//*********************************************************************************************************//-->
<?php
include "TSLIB/TSLIB_GET_USERSTAMP.php";
include "TSLIB/TSLIB_HEADER.php";
$Userstamp=json_encode($UserStamp);
?>
<html>
<head>
    <link rel="stylesheet" href="menu/CSS/one.css">
    <link rel="stylesheet" href="menu/CSS/two.css">
    <link rel="stylesheet" href="menu/CSS/thr.css">
    <link rel="stylesheet" href="menu/CSS/four.css'">
    <link rel="stylesheet" href="menu/CSS/five.css">
    <link rel="stylesheet" href="menu/CSS/six.css">


    <script src="menu/JS/one.js" ></script>
    <script src="menu/JS/two.js" ></script>
    <script src="menu/JS/thr.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
    <script src="menu/JS/four.js" ></script>
    <style>
        .navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .active > a {
            background-image: linear-gradient(to bottom, #498af3 0%, #498af3 100%);
            background-repeat: repeat-x;
            box-shadow: 0px 3px 9px #498af3 inset;
        }
    </style>
<script>
    function callFuncAfterMenu(){
        'use strict';
        $(function() {
            $('#scroll_top').on('click', function() {
                this.disabled = true;
                $('body, html').animate({
                    scrollTop: 0
                }, 800, function() {
                    this.disabled = false;
                }.bind(this));
                this.blur();
            });

            // Dropdown fix
            $('.dropdown > a[tabindex]').on('keydown', function(event) {
                // 13: Return
                if (event.keyCode == 13) {
                    $(this).dropdown('toggle');
                }
            });
            // Предотвращаем закрытие при клике на неактивный элемент списка
            $('.dropdown-menu > .disabled, .dropdown-header').on('click.bs.dropdown.data-api', function(event) {
                event.stopPropagation();
            });
            $('.dropdown-submenu > a').submenupicker();
            hljs.initHighlighting();
        });
    }
    var ErrorControl ={MsgBox:'false'}
    var MenuPage=1;
    var SubPage=2;
    var address='';
    var ipcheckflag= 1,geoflag=0;
    function CheckPageStatus(){
        if(MenuPage!=1 && SubPage!=1)
            $(".preloader").hide();
    }
    function updateClock ( )
    {
        var currentTime = new Date ( );

        var currentHours = currentTime.getHours ( );
        var currentMinutes = currentTime.getMinutes ( );
        var currentSeconds = currentTime.getSeconds ( );

        // Pad the minutes and seconds with leading zeros, if required
        currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
        currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

        // Choose either "AM" or "PM" as appropriate
        var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

        // Convert the hours component to 12-hour format if needed
        currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

        // Convert an hours component of "0" to "12"
        currentHours = ( currentHours == 0 ) ? 12 : currentHours;

        // Compose the string for display
        var currentTimeString = currentTime+":"+currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;


        $("#clock").html(currentTime);

    }

    $(document).ready(function(){
        $(".preloader").show();
        $('#checkin').attr("disabled","disabled");
        function displayLocation(latitude,longitude){
            var request = new XMLHttpRequest();
            var method = 'GET';
            var url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='+latitude+','+longitude+'&sensor=true';
            var async = true;

            request.open(method, url, async);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var data = JSON.parse(request.responseText);
                    address = data.results[0];
                    $('#location').text(address.formatted_address);
                    geoflag=1;
                    if(ipcheckflag==1)
                    {
                        $('#checkin').removeAttr("disabled");
                    }
                    $('#errmsg').hide();

                }
            };
            request.send();
        };
        var successCallback = function(position){
            var x = position.coords.latitude;
            var y = position.coords.longitude;
            displayLocation(x,y);
        };

        var errorCallback = function(error){
            var errorMessage = 'Unknown error';
            switch(error.code) {
                case 1:
                    errorMessage = 'Permission denied';
                    break;
                case 2:
                    errorMessage = 'Position unavailable';
                    break;
                case 3:
                    errorMessage = 'Timeout';
                    break;
            }
            document.write(errorMessage);
        };

        var options = {
            enableHighAccuracy: true,
            timeout: 40000,
            maximumAge: 0
        };

        navigator.geolocation.getCurrentPosition(successCallback,errorCallback,options);
        <?php echo  "var Userstamp = ". $Userstamp.PHP_EOL;?>
        setInterval('updateClock()', 1000);
        var Page_url;
        $(document).on("click",'.btnclass', function (){
            Page_url =$(this).attr('page');
//            Page_url =$(this).data('pageurl');
            var attr_id=$(this).attr("id");
            if(attr_id==undefined)
            {
                attr_id='';
            }
//        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"MENU CONFIRMATION",msgcontent:"Do You Want to Open "+$(this).attr("id")+" "+$(this).text()+" ?",confirmation:true,position:{top:150,left:480}}});
            show_msgbox("MENU CONFIRMATION","Do You Want to Open "+attr_id+" "+$(this).text()+" ?","success",true);
            return false;
        });
        function init () {
            document.getElementById('menu_frame').onload = function () {
                $(".preloader").hide();
            }
        }
        // FUNCTION FOR ANALOG CLOCK DISPLAYING
        var $hands = $('#liveclock div.hand')
        window.requestAnimationFrame = window.requestAnimationFrame
        || window.mozRequestAnimationFrame
        || window.webkitRequestAnimationFrame
        || window.msRequestAnimationFrame
        || function(f){setTimeout(f, 60)}
        function analogupdateclock(){
            var curdate = new Date()
            var hour_as_degree = ( curdate.getHours() + curdate.getMinutes()/60 ) / 12 * 360
            var minute_as_degree = curdate.getMinutes() / 60 * 360
            var second_as_degree = ( curdate.getSeconds() + curdate.getMilliseconds()/1000 ) /60 * 360
            $hands.filter('.hour').css({transform: 'rotate(' + hour_as_degree + 'deg)' })
            $hands.filter('.minute').css({transform: 'rotate(' + minute_as_degree + 'deg)' })
            $hands.filter('.second').css({transform: 'rotate(' + second_as_degree + 'deg)' })
            requestAnimationFrame(analogupdateclock)
        }
        requestAnimationFrame(analogupdateclock)
        var all_menu_array=[];
        var checkintime;
        var checkouttime;
        var checkinerrormsg=[];


        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {

            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var value_array=JSON.parse(xmlhttp.responseText);
                all_menu_array= value_array[0];
                checkintime=value_array[1];
                checkinerrormsg=value_array[2];
                checkouttime=value_array[4];
                ipcheckflag=value_array[6];
                if($('#location').text()==""){
                    $('#errmsg').text(checkinerrormsg[2]).show();
                }
                else{

                    $('#errmsg').hide();

                }
                if(all_menu_array[0]!=''){
                    ACRMENU_getallmenu_result(all_menu_array)
                    var login_id_role=value_array[5];
                    if(login_id_role!='SUPER ADMIN'){
                        if(checkintime==null && checkouttime==null )
                        {
                            $('#liveclock').show();
                            $('#checkin').val('CLOCK IN').show();
                            $('#clockmsg').hide();
                            $('#buttondiv').removeAttr('style');

                        }
                        else if(checkouttime==null){
                            $('#buttondiv').removeAttr('style');
                            $('#liveclock').show();
                            $('#checkin').val("CLOCK OUT").show();
                            var msg=checkinerrormsg[1].toString().replace("[TIME]",checkintime);
                            $('#clockmsg').text(msg).show();
                        }
                        else {

                            $('#buttondiv').css('display','none');
                            $('#liveclock').hide();
                            $('#checkin').hide();
                            var msg=checkinerrormsg[1].toString().replace("[TIME]",checkintime);
                            $('#clockmsg').text(msg).show();
                        }
                    }
                    else{
                        $('#buttondiv').css('display','none');
                        $('#liveclock').hide();
                        $('#checkin').hide();
                        $('#clockmsg').hide();
                    }
                }
                else{
                    var error_msg= checkinerrormsg[0];
                    error_msg=(error_msg).toString().replace('[LOGIN ID]',Userstamp);
                    $('#ACRMENU_lbl_errormsg').text(error_msg);
                    $('#ACRMENU_lbl_errormsg').show();
                    $(".preloader").hide();
                    $('#buttondiv').css('display','none');
                    $('#liveclock').hide();
                    $('#checkin').hide();
                    $('#clockmsg').hide();
                }
                if(ipcheckflag==0||geoflag==0)
                {
                    $('#checkin').attr("disabled","disabled");
                }
                else
                {
                    $('#checkin').removeAttr("disabled");
                }
            }

        }
        var option="MENU";
        xmlhttp.open("POST","DB_MENU.do?option="+option,true);
        xmlhttp.send();
        $(document).on('click','.menuconfirm',function(){
            $(".preloader").show();
            $('#buttondiv').css('display','none');
            $('#liveclock').hide();
            $('#checkin').hide();
            if(Page_url){
                $('#menu_frame').load(Page_url);
//            window.location.href=Page_url;
                init();
            }
        });
        $("#cssmenu").hide()
//FUNCTION TO SET ALL MENUS
        function ACRMENU_getallmenu_result(all_menu_array)
        {
            var ACRMENU_mainmenu=all_menu_array[0];//['ACCESS RIGHTS','DAILY REPORTS','PROJECT','REPORT']//main menu
            var ARCMENU_first_submenu=all_menu_array[1];
            //[['ACCESS RIGHTS-SEARCH/UPDATE','TERMINATE-SEARCH/UPDATE','USER SEARCH DETAILS'],['ADMIN ','USER '],['PROJECT ENTRY','PROJECT SEARCH/UPDATE'],['ATTENDANCE','REVENUE']]//submenu
            var ARCMENU_second_submenu=[];
            ARCMENU_second_submenu=all_menu_array[2]//[[], [], [], ['REPORT ENTRY', 'SEARCH/UPDATE/DELETE','WEEKLY REPORT ENTRY','WEEKLY SEARCH/UPDATE'], ['REPORT ENTRY', 'SEARCH/UPDATE'],[],[],[],[]];
            var count=0;
            var mainmenuItem="";
            var submenuItem="";
            var filelist=all_menu_array[4];
            var sub_submenuItem="";
            var script_flag=all_menu_array[3];
            for(var i=0;i<ACRMENU_mainmenu.length;i++)//add main menu
            {
                var main='mainmenu'+i
                var submen='submenu'+i;
                var filename=filelist[count]+'.do';

                if(ARCMENU_first_submenu.length==0)
                {
                    mainmenuItem='<li><a class="btnclass" tabindex="0"  page="'+filename+'" href="#"  id="'+ACRMENU_mainmenu[i]+'">'+ACRMENU_mainmenu[i]+'</a></li>'

//                    mainmenuItem='<li class="active"><a data-pageurl="'+filename+'" href="#"  id="'+ACRMENU_mainmenu[i]+'" >'+ACRMENU_mainmenu[i]+'</a></li>'
                }
                else

                {
                    mainmenuItem='<li class="dropdown"><a tabindex="0" data-toggle="dropdown">'+ACRMENU_mainmenu[i]+'<span class="caret"></span></a><ul class="dropdown-menu '+submen+'" role="menu">'

//                    mainmenuItem='<li class="has-sub dropdown"><a tabindex="0" href="#" data-toggle="dropdown">'+ACRMENU_mainmenu[i]+'<b class="caret"></b></a><ul class="dropdown-menu fa-ul '+submen+'">'
                }
                $("#ACRMENU_ulclass_mainmenu").append(mainmenuItem);

                for(var j=0;j<ARCMENU_first_submenu.length;j++)
                {
                    if(i==j)
                    {
                        for(var k=0;k<ARCMENU_first_submenu[j].length;k++)//add submenu1
                        {
                            var sub_submenu='sub_submenu'+j+k;
                            if(ARCMENU_second_submenu[count].length==0)
                            {
                                if(script_flag[count]!='X'){
                                    var file_name=filelist[count]+'.do';
                                }
                                else{
                                    var file_name='ACCESSRIGHTS/ERROR_PAGE.do';
                                }
                                submenuItem='<li><a tabindex="0" page="'+file_name+'" href="#" class="btnclass"  id="'+ACRMENU_mainmenu[i]+'" >'+ARCMENU_first_submenu[j][k]+'</a></li>'

//                                submenuItem='<li class="active"><a class="btnclass" data-pageurl="'+file_name+'" href="#"   id="'+ACRMENU_mainmenu[i]+'" >'+ARCMENU_first_submenu[j][k]+'</a></li></ul>'
                            }
                            else
                            {
                                submenuItem='<li class="dropdown-submenu">  <a tabindex="0" data-toggle="dropdown">'+ARCMENU_first_submenu[j][k]+'</a><ul class="dropdown-menu '+sub_submenu+'" >'

//                                submenuItem='<li class="has-sub dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">'+ARCMENU_first_submenu[j][k]+'</a><ul class="dropdown-menu '+sub_submenu+'" role="menu">'
                            }
                            $("."+submen).append(submenuItem);
                            for(var m=0;m<ARCMENU_second_submenu[count].length;m++)//add submenu2
                            {
                                if(script_flag[count][m]!='X'){
                                    var file_name=filelist[count][m]+'.do';
                                }
                                else{
                                    var file_name='ACCESSRIGHTS/ERROR_PAGE.do';
                                }
                                sub_submenuItem='<li ><a tabindex="0" page="'+file_name+'" href="#" class="btnclass"  id="'+ARCMENU_first_submenu[j][k]+'" >'+ARCMENU_second_submenu[count][m]+'</a></li>'

//                                sub_submenuItem='<li class="active"><a class="btnclass"  data-pageurl="'+file_name+'" href="#"   id="'+ARCMENU_first_submenu[j][k]+'" >'+ARCMENU_second_submenu[count][m]+'</a></li>'

                                $("."+sub_submenu).append(sub_submenuItem);
                            }
                            count++;
                            $("#ACRMENU_ulclass_mainmenu").append('</ul></li>');
                        }
                    }
                }
                $("#ACRMENU_ulclass_mainmenu").append('</li>');
            }
            callFuncAfterMenu();
            $("#cssmenu").show()
            $(".preloader").hide();
            MenuPage=0;
            CheckPageStatus();
        }
        $('#checkin').click(function(){
        $('.preloader').show();
            var locationaddress=address.formatted_address;
            var button_value=$('#checkin').val();
            var currentTime = new Date ();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $(".preloader").hide();
                    var response=JSON.parse(xmlhttp.responseText);
                    if(button_value=="CLOCK IN"){
                        if(response[0]==1)
                        {
                            var msg=checkinerrormsg[1].toString().replace("[TIME]",response[1]);
                            $('#clockmsg').text(msg).show();
                            $('#liveclock').show();
                            $('#checkin').val("CLOCK OUT").show();
                            $('#buttondiv').removeAttr('style');
                        }
//                    else{
//
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"CLOCK IN/OUT",msgcontent:response[0]}});
//                    }
                    }
                    else{
                        if(response[0]==1){
                            $('#buttondiv').css('display','none');
                            $('#liveclock').hide();
                            $('#checkin').val("CLOCK IN").hide();
                        }
//                    else{
//                        $(document).doValidation({rule:'messagebox',prop:{msgtitle:"CLOCK IN/OUT",msgcontent:response[0]}});
//                    }
                    }
                    $(".preloader").hide();
                }

            }
            var option="CLOCK";
            xmlhttp.open("POST","DB_MENU.do" +"?option="+option+"&location="+locationaddress+"&btn_value="+button_value);
            xmlhttp.send();
        });
    });
</script>
<title>SSOMENS TIME SHEET</title>
</head>
<body>
<div class="container-fluid">
    <div class="wrapper" >
        <div class="preloader" hidden><span class="Centerer"></span><img class="preloaderimg"/> </div>
        <img src="images/companyLogo.jpg"  class="img-responsive" alt="Responsive image" align="middle"/>
        <div>
            <div>
                <div style="width:1000px"><b><h4><span style="font-family:Helvetica Neue" id="clock"></span></h4></b></div>
                <div align="right"><b><?php echo $UserStamp ?></b></div>
            </div>

            <div>
                <div><b><label  id="clockmsg" name="clockmsg" class="locbold" ></label></b> </div><div align="right"><b><label id="location" class="locbold"></label></b></div>
            </div>
        </div>
        <nav class="navbar navbar-default" id="menu_nav">
            <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="menu" >
                <ul class="nav navbar-nav" id="ACRMENU_ulclass_mainmenu">
                </ul>
            </div>
        </nav>

        <div class="space" id="buttondiv" style="display: none" >
            <lable id="errmsg" class="errormsg" width="40px" hidden ></lable>
        </div>
        <center><input  style="align-content: center" type="button" id="checkin" class="maxbtn" name="checkin" value="CLOCK IN" disabled /></center>
        <div style="padding-top: 20px">
            <div id="liveclock" class="outer_face">
                <div class="marker oneseven"></div>
                <div class="marker twoeight"></div>
                <div class="marker fourten"></div>
                <div class="marker fiveeleven"></div>

                <div class="inner_face">
                    <div class="hand hour"></div>
                    <div class="hand minute"></div>
                    <div class="hand second"></div>
                </div>
            </div>
        </div>
        <br><label id="ACRMENU_lbl_errormsg" class="errormsg" hidden ></label>
        <div id="menu_frame" name="iframe_a" width="100%" height="100%" border="0"></div>
        <!--                <iframe id="menu_frame" name="iframe_a" width="100%" height="100%"  frameborder="0"></iframe>-->
    </div>
</div>
</body>
</html>