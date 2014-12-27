<!--//*******************************************FILE DESCRIPTION*********************************************//
//**********************************************REVENUE*******************************************************//
//DONE BY:LALITHA
//VER 0.06-SD:23/12/2014 ED:27/12/2014,TRACKER NO:74,Changed paramtrs nd Sp checked via form,Updated recver nd prjct nme,Unique functn,Changed date nf frm validation
//VER 0.05-SD:01/12/2014 ED:01/12/2014,TRACKER NO:74,Changed Preloder funct
//VER 0.04-SD:13/11/2014 ED:13/11/2014,TRACKER NO:97,Tested Newly added outflg for project by emp,Updated showned err msg nd hide,Renamed column name of prjct by revenue
//VER 0.03-SD:29/10/2014 ED:31/10/2014,TRACKER NO:97,Fixed the column nd div width,Changed the data table header is meaningful nd put space also,Updated Sorting fr dte,Hide the section id nd old dt datas while listbx changing fn,Added Preloader in list bx fn ,Column values alignd in centre
//VER 0.02-SD:14/10/2014 ED:21/10/2014,TRACKER NO:97,Did others two parts of projects,Changed data tble for prjct rvn by actv nonactv emp option,Removed hard code of list bx option(tkn data nd id also frm db),Updated data tble,validation,Loaded all err msg frm db,hiding err msg,lbls nd others fields in unwanted places,Changed queries,update dte frmt,Update comments,Set min nd max dte
//DONE BY:SASIKALA
//VER 0.01-INITIAL VERSION, SD:08/10/2014 ED:15/10/2014,TRACKER NO:97
//************************************************************************************************************-->
<?php
include "HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
//GLOBAL DECLARATION
var err_msg_array=[];
//READY FUNCTION START
$(document).ready(function(){
    $('#REV_btn_prjctsrch').hide();
    $('#REV_btn_empsrch').hide();
    $('#REV_btn_search').hide();
    $('#REV_btn_searchdaterange').hide();
    var REV_active_emp=[];
    var  REV_nonactive_emp=[];
    var REV_project_name=[];
    var REV_project_listbx=[];
    var err_msg_array=[];
    var REV_project_recver=[];
    $('.preloader').show();
    var formElement = document.getElementById("REV_form_revenue");
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            $(".preloader").hide();
            var values_array=JSON.parse(xmlhttp.responseText);
            REV_project_listbx=values_array[0];
            REV_project_name=values_array[1];
            REV_active_emp=values_array[2];
            REV_nonactive_emp=values_array[3];
            err_msg_array=values_array[4];
            REV_project_recver=values_array[5];
            if(REV_project_listbx.length!=0){
                var project_list='<option>SELECT</option>';
                for (var i=0;i<REV_project_listbx.length;i++) {
                    project_list += '<option value="' + REV_project_listbx[i][1] + '">' + REV_project_listbx[i][0] + '</option>';
                }
                $('#REV_lb_project').html(project_list);
                $('#REV_lb_project').show();
                $('#REV_lbl_prjct').show();
            }
            else
            {
                $('#REV_nodata_rc').text(err_msg_array[0]).show();
            }
            var project_name='<option>SELECT</option>';
            for (var i=0;i<REV_project_name.length;i++) {
                project_name += '<option value="' + REV_project_name[i] + '">' + REV_project_name[i] + '</option>';
            }
            $('#REV_lb_projectname').html(project_name);
            $('#REV_lb_projectnamedaterange').html(project_name);
        }
    }
    var option="common";
    xmlhttp.open("GET","DB_REPORT_REVENUE.do?option="+option);
    xmlhttp.send(new FormData(formElement));
//FUNCTION FOR FORMTABLEDATEFORMAT
    function FormTableDateFormat(inputdate){
        var string = inputdate.split("-");
        return string[2]+'-'+ string[1]+'-'+string[0];
    }
//DATE PICKER FUNCTION
    $('.REV_datepicker ').datepicker(
        {
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth: true
        });
    //END DATE PICKER FUNCTION
    //DATE PICKER FUNCTION
    $('.REV_datepickerrnge ').datepicker(
        {
            dateFormat: 'dd-mm-yy',
            changeYear: true,
            changeMonth: true
        });
    //END DATE PICKER FUNCTION
    //CHANGE FUNCTION FOR PERMANENT RADIO BTN
    $(document).on('change','.valid',function(){
        $('sectionrnge').html('');
        var REV_start_date= $('#REV_tb_strtdte').val()
        var REV_end_date=$('#REV_tb_enddte').val()
        $('#REV_tble_nonactive_bydaterange').hide();
        $('#REV_lbl_emptitle').hide();
        $('#REV_lbl_empday').hide();
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_lbl_eachproject_empday').hide();
        $('#REV_nodata_startenddate').hide();
        $('#sectionrnge').hide();
        if((REV_start_date!='') && (REV_end_date!='' ))
        {
            $("#REV_btn_search").removeAttr("disabled");
        }
        else
        {
            $("#REV_btn_search").attr("disabled","disabled");
        }
    });
    //VALIDATION SEARCH BTN FOR PROJECT NAME BY DATE RANGE
    $(document).on('change','.validsrchbtn',function(){
        $('sectionprbydtrange').html('');
        var REV_start_date= $('#REV_tb_strtdtebyrange').val()
        var REV_end_date=$('#REV_tb_enddtebyrange').val();
        $('#REV_tble_projctrevenue_bydaterange').html('');
        $('#REV_lbl_totaldays').hide();
        $('#REV_lbl_totalhrs').hide();
        $('#REV_div_projecttotal_dtebyrange').hide();
        $('#REV_nodata_staenddate').hide();
        $('#REV_nodata_startenddate').hide();
        $('#REV_lbl_dterangetitle').hide();
        $('#REV_lbl_totaldays_dterange').hide();
        $('#REV_lbl_totalhrs_dterange').hide();
        if((REV_start_date!='') && (REV_end_date!='' ))
        {
            $("#REV_btn_searchdaterange").removeAttr("disabled");
        }
        else
        {
            $("#REV_btn_searchdaterange").attr("disabled","disabled");
        }
    });
    function STDLY_INPUT_unique(a) {
        var result = [];
        $.each(a, function(i, e) {
            if ($.inArray(e, result) == -1) result.push(e);
        });
        return result;
    }
    // CHANGE EVENT FOR PROJECT LISTBOX
    $('#REV_lb_loginid').change(function(){
        var login_id=$('#REV_lb_loginid').val()
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                var values_array=JSON.parse(xmlhttp.responseText);
                var project=[];
                var REV_project_names=values_array[0];
                for (var i=0;i<REV_project_names.length;i++) {
                    project.push(REV_project_names[i][0]);
                }
                var unique_prj_name=STDLY_INPUT_unique(project)
                var project_names='<option>SELECT</option>';
                for (var j=0;j<unique_prj_name.length;j++) {
                    project_names += '<option value="' + unique_prj_name[j] + '">' + unique_prj_name[j] + '</option>';
                }
                $('#REV_lb_empproject').html(project_names);
            }
        }
        var option="SPECICIFIED_PROJECT_NAME";
        xmlhttp.open("GET","DB_REPORT_REVENUE.do?option="+option+"&login_id="+login_id);
        xmlhttp.send();
    });
// CHANGE EVENT FOR PROJECT LISTBOX
    $('#REV_lb_project').change(function(){
        REV_hide();
        $('#REV_lb_projectname').hide();
        $('#REV_lbl_prjctnme').hide();
        $('#REV_nodata_uld').hide();
        $('#REV_nodata_loginid').hide();
        $('#REV_tble_searchbtn').html('');
        $('#REV_tble_nonactive_bydaterange').hide();
        $('#REV_nodata_pdflextble').hide();
        $('#REV_nodata_startenddate').hide();
        $('#REV_nodata_staenddate').hide();
        $('#REV_lbl_title').hide();
        $('#REV_lbl_dterangetitle').hide();
        $('#REV_lbl_totaldays_dterange').hide();
        $('#REV_lbl_totalhrs_dterange').hide();
        $('#REV_rd_withproject').hide();
        $('#REV_lbl_withproject').hide();
        $('#REV_rd_withoutproject').hide();
        $('#REV_lbl_withoutproject').hide();
        $('#REV_lbl_emptitle').hide();
        var option=$("#REV_lb_project").val();
        if(option=="SELECT")
        {
            REV_hide(option);
            $('#REV_lbl_prjctnme').hide();
            $('#REV_lb_projectname').hide();
        }
        else if(option=='7')
        {
            if(REV_project_name.length!=0)
            {
                $('#REV_tble_prjctrevenue').show();
                $('#REV_lbl_prjctnme').show();
                $('#REV_lb_projectname').val("SELECT").show();
            }
            else
            {
                $('#REV_nodata_pd').text(err_msg_array[0]).show();
            }
            REV_hide(option);
        }
        else if(option=='8')
        {
            $('#REV_tble_prjctrevactnonact').show();
            $('#REV_rd_actveemp').show();
            $('#REV_rd_nonactveemp').show();
            $('#REV_lbl_actveemp').show();
            $('#REV_lbl_nonactveemp').show();
        }
        else if(option=='9')
        {
            $('#REV_tble_prjctrevactnonact').show();
            $('#REV_rd_actveemp').show();
            $('#REV_rd_nonactveemp').show();
            $('#REV_lbl_actveemp').show();
            $('#REV_lbl_nonactveemp').show();
        }
        else if(option=='10')
        {
            if(REV_project_name.length!=0)
            {
                $('#REV_tble_prjctrevenue').show();
                $('#REV_lbl_prjctnme').show();
                $('#REV_lb_projectname').val("SELECT").show();
            }
            else
            {
                $('#REV_nodata_pd').text(err_msg_array[0]).show();
            }
            REV_hide(option);

        }
    });
//FUNCTION FOR HIDING
    function REV_hide(option){
        $('#REV_lbl_totaldays').hide();
        $('#REV_lbl_totalhrs').hide();
        $('#REV_tble_totaldays').hide();
        $('#REV_lbl_loginid').hide();
        $('#REV_lb_loginid').hide();
        $('#REV_btn_empsrch').hide();
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_lbl_empday').hide();
        $('#REV_lbl_eachproject_empday').hide();
        $('#REV_tble_empday').hide();
        $('#REV_rd_actveemp').hide();
        $('#REV_rd_nonactveemp').hide();
        $('#REV_lbl_actveemp').hide();
        $('#REV_lbl_nonactveemp').hide();
        $('#REV_lbl_actveemps').hide();
        $('#REV_lbl_nonactveemps').hide();
        $('#REV_div_loginid').hide();
        $('#REV_btn_prjctsrch').hide();
        $('#REV_div_projecttotal').hide();
        $('#REV_lbl_strtdte').hide();
        $('#REV_tb_strtdte').hide();
        $('#REV_lbl_enddte').hide();
        $('#REV_tb_enddte').hide();
        $('#REV_btn_search').hide();
        $('#REV_tb_strtdtedaterange').val('').hide();
        $('#REV_lbl_enddtedaterange').hide();
        $('#REV_tb_enddtedaterange').val('').hide();
        $('#REV_btn_searchdaterange').hide();
        $('#REV_lbl_empproject').hide();
        $('#REV_lb_empproject').hide();
        $('#REV_lbl_recver').hide();
        $('#REV_lb_recver').hide();
        $('#REV_lbl_precver').hide();
        $('#REV_lb_precver').hide();
        $('#REV_rd_nonactveemp').attr("checked",false);
        $('#REV_lb_projectnamedaterange').hide();
        $('#REV_lbl_prjctnmedaterange').hide();
        $('#REV_tble_prjctrevenuedaterange').hide();
        $('#REV_tble_startdate').hide();
        $('#REV_tble_searchbtn').hide();
        $('#REV_rd_actveemp').attr("checked",false);
        $('#REV_tb_strtdtebyrange').hide();
        $('#REV_tb_enddtebyrange').hide();
        $('#REV_lbl_strtdtebyrange').hide();
        $('#REV_lbl_enddtebyrange').hide();
        $('#REV_tble_prjctrevactnonact').hide();
        $('#REV_nodata_pd').hide();
        $('#nonactiveempdatatble').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_div_projecttotal_dtebyrange').hide();
    }
// CHANGE EVENT FOR PROJECT NAME
    var daterange_val=[];
    $('#REV_lb_projectname').change(function(){
        $('.preloader', window.parent.document).show();
        $('#REV_btn_prjctsrch').show();
        $('#REV_tble_searchbtn').html('');
        $('#REV_nodata_loginid').hide();
        $('#REV_div_projecttotal').hide();
        $('#REV_div_projecttotal_dtebyrange').hide();
        $('#REV_nodata_pdflextble').hide();
        $('#REV_nodata_startenddate').hide();
        $('#REV_nodata_staenddate').hide();
        $('#REV_lbl_title').hide();
        $('#REV_lbl_dterangetitle').hide();
        if($('#REV_lb_projectname').val()=="SELECT")
        {
            $('.preloader', window.parent.document).hide();
            $('#REV_lb_precver').hide();
            $('#REV_lbl_precver').hide();
            $('#REV_btn_prjctsrch').attr("disabled","disabled");
            $('#REV_tble_totaldays').hide();
            $('#REV_lbl_totaldays').hide();
            $('#REV_lbl_totalhrs').hide();
            $('#REV_btn_searchdaterange').hide();
            $('#REV_tble_searchbtn').html('');
        }
        else
        {
            $('.preloader', window.parent.document).hide();
            $('#REV_tble_searchbtn').show();
            $('#REV_tble_startdate').show();
            $('#REV_btn_prjctsrch').removeAttr("disabled");
            $('#REV_tble_totaldays').hide();
            $('#REV_lbl_totaldays').hide();
            $('#REV_lbl_totalhrs').hide();
        }
    });
// CLICK EVENT FOR ACTIVE RADIO BUTTON
    $('#REV_rd_actveemp').click(function(){
        $('#REV_div_loginid').hide();
        $('#REV_lbl_emptitle').hide();
        $('#REV_nodata_uld').hide();
        $('#REV_nodata_loginid').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_nodata_startenddate').hide();
        $('#REV_lbl_actveemps').show();
        if(REV_active_emp.length!=0)
        {
            var active_employee='<option>SELECT</option>';
            for (var i=0;i<REV_active_emp.length;i++) {
                active_employee += '<option value="' + REV_active_emp[i] + '">' + REV_active_emp[i] + '</option>';
            }
            $('#REV_lb_loginid').html(active_employee);

            $('#REV_lbl_loginid').show();
            $('#REV_lb_loginid').show();
        }
        else
        {
            $('#REV_nodata_uld').text(err_msg_array[0]).show();
        }
        $('#REV_btn_empsrch').hide();
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_lbl_empday').hide();
        $('#REV_lbl_eachproject_empday').hide();
        $('#REV_tble_empday').hide();
        $('#REV_lbl_nonactveemps').hide();
        $('#REV_lbl_strtdte').hide();
        $('#REV_tb_strtdte').hide();
        $('#REV_lbl_enddte').hide();
        $('#REV_tb_enddte').hide();
        $('#REV_btn_search').hide();
        $('#REV_lbl_empproject').hide();
        $('#REV_lb_empproject').hide();
        $('#REV_lbl_recver').hide();
        $('#REV_lb_recver').hide();
        $('#REV_rd_withproject').hide();
        $('#REV_lbl_withproject').hide();
        $('#REV_rd_withoutproject').hide();
        $('#REV_lbl_withoutproject').hide();
    });
// CLICK EVENT FOR NONACTIVE RADIO BUTTON
    $('#REV_rd_nonactveemp').click(function(){
        $('#REV_div_loginid').hide();
        $('#REV_lbl_emptitle').hide();
        $('#REV_nodata_uld').hide();
        $('#REV_nodata_loginid').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_nodata_startenddate').hide();
        if(REV_nonactive_emp.length!=0)
        {
            var nonactive_employee='<option>SELECT</option>';
            for (var i=0;i<REV_nonactive_emp.length;i++) {
                nonactive_employee += '<option value="' + REV_nonactive_emp[i] + '">' + REV_nonactive_emp[i] + '</option>';
            }
            $('#REV_lb_loginid').html(nonactive_employee);
            $('#REV_lbl_nonactveemps').show();
            $('#REV_lbl_loginid').show();
            $('#REV_lb_loginid').show();
        }
        else
        {
            $('#REV_nodata_uld').text(err_msg_array[0]).show();
        }
        $('#REV_btn_empsrch').hide();
        $('#REV_lbl_actveemps').hide();
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_lbl_empday').hide();
        $('#REV_lbl_eachproject_empday').hide();
        $('#REV_tble_empday').hide();
        $('#REV_lbl_strtdte').hide();
        $('#REV_tb_strtdte').hide();
        $('#REV_lbl_enddte').hide();
        $('#REV_tb_enddte').hide();
        $('#REV_btn_search').hide();
        $('#REV_lbl_empproject').hide();
        $('#REV_lb_empproject').hide();
        $('#REV_lbl_recver').hide();
        $('#REV_lb_recver').hide();
        $('#REV_rd_withproject').hide();
        $('#REV_lbl_withproject').hide();
        $('#REV_rd_withoutproject').hide();
        $('#REV_lbl_withoutproject').hide();
    });
// CHANGE EVENT FOR LOGINID
    $('#REV_lb_loginid').change(function(){
        $('.preloader', window.parent.document).hide();
        $('#REV_lbl_emptitle').hide();
        $('#REV_tb_strtdte').hide();
        $('#REV_lbl_enddte').hide();
        $('#REV_tb_enddte').hide();
        $('#REV_lbl_strtdte').hide();
        $('#REV_btn_search').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_lbl_empday').hide();
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_lbl_eachproject_empday').hide();
        $('#REV_lb_empproject').hide();
        $('#REV_lbl_recver').hide();
        $('#REV_lb_recver').hide();
        $('#REV_lbl_empproject').hide();
        $('#REV_nodata_loginid').hide();
        $('#REV_tb_strtdte').val('');
        $('#REV_tb_enddte').val('');
        $('#REV_nodata_startenddate').hide();
        $('#REV_div_loginid').hide();
        $('sectionprbydtrange').html('');
        $('sections').html('');
        $('sectionrnge').html('');
        $('#REV_btn_empsrch').hide();
        var formElement = document.getElementById("REV_form_revenue");
        var date_val=[];
        var REV_loginids=$("#REV_lb_loginid").val();
        var option=$("#REV_lb_project").val();
        $('#REV_div_loginid').hide();
        if($('#REV_lb_loginid').val()=="SELECT")
        {
            $('#REV_btn_empsrch').hide();
            $('#REV_btn_empsrch').attr("disabled","disabled");
            $('#REV_lbl_ttlprjct').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_lbl_eachproject_empday').hide();
            $('#REV_tble_empday').hide();
            $('#REV_div_loginid').hide();
            $('#REV_div_nonactve_dterange').hide();
            $('#REV_tb_strtdte').hide();
            $('#REV_tb_enddte').hide();
            $('#REV_lbl_strtdte').hide();
            $('#REV_lbl_enddte').hide();
            $('#REV_btn_search').hide();
            $('#REV_nodata_loginid').hide();
            $('#REV_nodata_startenddate').hide();
            $('#REV_lbl_empproject').hide();
            $('#REV_lb_empproject').hide();
            $('#REV_lbl_recver').hide();
            $('#REV_lb_recver').hide();
            $('#REV_rd_withproject').hide();
            $('#REV_lbl_withproject').hide();
            $('#REV_rd_withoutproject').hide();
            $('#REV_lbl_withoutproject').hide();
        }
        else
        {
            if(option=='8')
            {
                $('#REV_lbl_ttlprjct').hide();
                $('#REV_lbl_empday').hide();
                $('#REV_lbl_eachproject_empday').hide();
                $('#REV_tble_empday').hide();
                $('#REV_lbl_strtdte').hide();
                $('#REV_tb_strtdte').hide();
                $('#REV_lbl_enddte').hide();
                $('#REV_tb_enddte').hide();
                $('#REV_btn_search').hide();
                $('#REV_lbl_strtdte').hide();
                $('#REV_tb_strtdte').hide();
                $('#REV_lbl_enddte').hide();
                $('#REV_tb_enddte').hide();
                $('#REV_btn_search').hide();
                $('#REV_rd_withproject').show();
                $('#REV_rd_withproject').attr("checked",false);
                $('#REV_lbl_withproject').show();
                $('#REV_rd_withoutproject').show();
                $('#REV_rd_withoutproject').attr("checked",false);
                $('#REV_lbl_withoutproject').show();
            }
            else if(option=='9')
            {
                $('#REV_btn_empsrch').hide();
                $('#REV_div_loginid').hide();
                $('#REV_rd_withproject').show();
                $('#REV_rd_withproject').attr("checked",false);
                $('#REV_lbl_withproject').show();
                $('#REV_rd_withoutproject').show();
                $('#REV_rd_withoutproject').attr("checked",false);
                $('#REV_lbl_withoutproject').show();

            }
        }
    });
    //CLICK EVENT FOR WITHPROJECT
    $('#REV_rd_withproject').click(function(){
        $('#REV_lbl_empproject').show();
        $('#REV_lb_empproject').val("SELECT").show();
        $('#REV_lbl_recver').hide();
        $('#REV_lb_recver').hide();
        $('#REV_btn_empsrch').hide();
        $('#REV_tb_strtdte').hide();
        $('#REV_lbl_strtdte').hide();
        $('#REV_lbl_enddte').hide();
        $('#REV_tb_enddte').hide();
        $('#REV_nodata_loginid').hide();
        $('#REV_nodata_startenddate').hide();
        $('#REV_lbl_emptitle').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_tble_nonactive_bydaterange').html('');
        $('#REV_div_loginid').hide();
        $('#REV_btn_search').hide();
        $('#REV_tble_empday_nonactveemp1').html('');
        $('#REV_tb_strtdte').val('');
        $('#REV_tb_enddte').val('');
        $('#REV_btn_empsrch').attr("disabled","disabled");
    });
    //CLICK EVENT FOR WITHOUTPROJECT
    $('#REV_rd_withoutproject').click(function(){
        $('#REV_tb_strtdte').val('');
        $('#REV_tb_enddte').val('');
        $('#REV_lbl_emptitle').hide();
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_btn_search').hide();
        $('#REV_nodata_startenddate').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_lbl_empday').hide();
        $('#REV_nodata_loginid').hide();
        $('#REV_div_loginid').hide();
        $('#REV_tble_empday_nonactveemp1').html('');
        $('#REV_tble_nonactive_bydaterange').html('');
        $('sections').html('');
        var option=$("#REV_lb_project").val();
        if(option==8)
        {
            $('#REV_lbl_empproject').hide();
            $('#REV_lb_empproject').val("SELECT").hide();
            $('#REV_lbl_recver').hide();
            $('#REV_lb_recver').hide();
            $('#REV_btn_empsrch').show();
            $('#REV_btn_empsrch').removeAttr("disabled");
            $('#REV_tb_strtdte').hide();
            $('#REV_lbl_strtdte').hide();
            $('#REV_lbl_enddte').hide();
            $('#REV_tb_enddte').hide();
        }
        else if(option==9)
        {
            $('#REV_lbl_empproject').hide();
            $('#REV_lb_empproject').val("SELECT").hide();
            $('#REV_lbl_recver').hide();
            $('#REV_lb_recver').hide();
            $('#REV_btn_empsrch').hide();
            $('#REV_tb_strtdte').show();
            $('#REV_lbl_strtdte').show();
            $('#REV_lbl_enddte').show();
            $('#REV_tb_enddte').show();
            $('#REV_btn_search').show();
            $("#REV_btn_search").attr("disabled","disabled");
            //DATE PICKER FUNCTION
            $('.REV_datepickerrnge ').datepicker(
                {
                    dateFormat: 'dd-mm-yy',
                    changeYear: true,
                    changeMonth: true
                });
            //END DATE PICKER FUNCTION
        }
    });
//CHANGE FUNCTION FOR PROJECT NAME
    $('#REV_lb_projectname').change(function(){
        $('.preloader', window.parent.document).show();
        $('section').html('');
        $('sectionprbydtrange').html('');
        $('#REV_btn_prjctsrch').hide();
        $('#REV_tble_startdate').html('');
        $('#REV_btn_searchdaterange').hide();
        $('#REV_lb_precver').hide();
        $('#REV_lbl_precver').hide();
        $('#REV_lbl_totaldays_dterange').hide();
        $('#REV_lbl_totalhrs_dterange').hide();
        var REV_projectname=$("#REV_lb_projectname").val();
        var project_name=$("#REV_lb_project").val();
        var formElement = document.getElementById("REV_form_revenue");
        var option=$("#REV_lb_project").val();
        $('#REV_div_loginid').hide();
        if($('#REV_lb_projectname').val()=="SELECT")
        {
            $('.preloader', window.parent.document).hide();
            $('#REV_btn_empsrch').hide();
            $('#REV_btn_empsrch').attr("disabled","disabled");
            $('#REV_lbl_ttlprjct').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_lbl_eachproject_empday').hide();
            $('#REV_tble_empday').hide();
            $('#REV_div_loginid').hide();
            $('#REV_btn_searchdaterange').hide();
            $('#REV_nodata_pdflextble').hide();
            $('#REV_div_projecttotal_dtebyrange').hide();
        }
        else{
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader', window.parent.document).hide();
                    var values_array=JSON.parse(xmlhttp.responseText);
                    REV_project_recver=values_array;
                    var recver_list='<option>SELECT</option>';
                    for (var i=0;i<REV_project_recver.length;i++) {
                        recver_list += '<option value="' + REV_project_recver[i] + '">' + REV_project_recver[i] + '</option>';
                    }
                    $('#REV_lb_precver').html(recver_list);
                    if(REV_project_recver.length>1)
                    {
                        $('#REV_lb_precver').show();
                        $('#REV_lbl_precver').show();
                    }
                    else
                    {
                        if(project_name==7){
                            $('#REV_btn_prjctsrch').show();
                            $('#REV_lb_precver').prop('selectedIndex',1);
                            $('#REV_lb_precver').hide();
                            $('#REV_lbl_precver').hide();
                            var title=err_msg_array[7].toString().replace("[PROJECTNAME]",REV_projectname);
                        }
                        else if(project_name==10)
                        {
                            $('#REV_lb_precver').hide();
                            $('#REV_lbl_precver').hide();
                            $('#REV_lb_precver').prop('selectedIndex',1);
                            REV_showform();
                        }
                    }
                }
            }
            var choice="PROJECTRECVERSION";
            xmlhttp.open("POST","DB_REPORT_REVENUE.do?option="+choice+"&selectoption="+option);
            xmlhttp.send(new FormData(formElement));
        }
    });
//CHANGE FUNCTION FOR RECORD VERSION
    $('#REV_lb_precver').change(function(){
        $('#REV_btn_searchdaterange').html('');
        $('#REV_lbl_dterangetitle').hide();
        $('#REV_nodata_staenddate').hide();
        $('#REV_lbl_totaldays').hide();
        $('#REV_lbl_totalhrs').hide();
        $('#REV_lbl_title').hide();
        $('#REV_lbl_totaldays_dterange').hide();
        $('#REV_lbl_totalhrs_dterange').hide();
        var project=$('#REV_lb_projectname').val();
        var projectname=$('#REV_lb_project').val();
        var project_recver=$('#REV_lb_precver').val();
        if( project_recver!="SELECT" && projectname==7)
        {
            $('#REV_btn_prjctsrch').removeAttr("disabled");
            $('#REV_lbl_title').hide();
        }
        else{
            $('#REV_btn_prjctsrch').attr("disabled","disabled").hide();
        }
    });
    // CHANGE EVENT FOR PROJECT NAME
    $('#REV_lb_precver').change(function(){
        REV_showform();

    });
    function REV_showform(){
        $('.preloader', window.parent.document).show();
        $('section').html('');
        $('sectionprbydtrange').html('');
        $('#REV_btn_prjctsrch').hide();
        $('#REV_tble_startdate').html('');
        $('#REV_tble_searchbtn').html('');
        $('#REV_btn_searchdaterange').hide();
        var project_recver=$('#REV_lb_precver').val();
        var REV_loginids=$("#REV_lb_projectname").val();
        var option=$("#REV_lb_project").val();
        $('#REV_div_loginid').hide();
        if($('#REV_lb_precver').val()=="SELECT")
        {
            $('.preloader', window.parent.document).hide();
            $('#REV_btn_empsrch').hide();
            $('#REV_btn_empsrch').attr("disabled","disabled");
            $('#REV_lbl_ttlprjct').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_lbl_eachproject_empday').hide();
            $('#REV_tble_empday').hide();
            $('#REV_div_loginid').hide();
            $('#REV_btn_searchdaterange').hide();
            $('#REV_nodata_pdflextble').hide();
            $('#REV_div_projecttotal_dtebyrange').hide();
        }
        else
        {
            if(option=='7')
            {
                $('#REV_btn_prjctsrch').show();
                $('#REV_btn_prjctsrch').removeAttr("disabled");
                $('#REV_lbl_ttlprjct').hide();
                $('#REV_lbl_empday').hide();
                $('#REV_lbl_eachproject_empday').hide();
                $('#REV_tble_empday').hide();
                $('#REV_lbl_strtdte').hide();
                $('#REV_tb_strtdte').hide();
                $('#REV_lbl_enddte').hide();
                $('#REV_tb_enddte').hide();
                $('#REV_btn_search').hide();
                $('#REV_lbl_strtdte').hide();
                $('#REV_tb_strtdte').hide();
                $('#REV_lbl_enddte').hide();
                $('#REV_tb_enddte').hide();
                $('#REV_btn_search').hide();
                $('.preloader', window.parent.document).hide();
            }
            else if((option=='10'))
            {
                $('<tr><td width="150"><label name="REV_lbl_strtdte" id="REV_lbl_strtdtebyrange" >START DATE<em>*</em></label></td><td><input type="text" name="REV_tb_strtdtebyrange" id="REV_tb_strtdtebyrange" class=" validsrchbtn clear REV_datepicker datemandtry" style="width:75px;" ></td></tr>').appendTo('#REV_tble_startdate')
                $('<tr><td width="150"><label name="REV_lbl_enddte" id="REV_lbl_enddtebyrange" >END DATE<em>*</em></label></td><td><input type="text" name="REV_tb_enddtebyrange" id="REV_tb_enddtebyrange" class=" validsrchbtn clear REV_datepicker datemandtry" style="width:75px;" ></td></tr>').appendTo('#REV_tble_startdate')
                $('<tr><td><input type="button" class="btn" name="REV_btn_searchdaterange" id="REV_btn_searchdaterange"  value="SEARCH" disabled></td></tr>').appendTo('#REV_tble_searchbtn')
                //DATE PICKER FUNCTION
                $('.REV_datepicker').datepicker(
                    {
                        dateFormat: 'dd-mm-yy',
                        changeYear: true,
                        changeMonth: true
                    });
                //END DATE PICKER FUNCTION
                var daterange_val=[];
                var formElement = document.getElementById("REV_form_revenue");
                var REV_project_name=$("#REV_lb_projectname").val();
                //FUNCTION FOR SETTINF MIN ND MAX DATE
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $('.preloader', window.parent.document).hide();
                        daterange_val=JSON.parse(xmlhttp.responseText);
                        var REV_start_dates=daterange_val[0];
                        var REV_end_dates=daterange_val[1];
                        $('#REV_tb_strtdtebyrange').datepicker("option","minDate",new Date(REV_start_dates));
                        $('#REV_tb_strtdtebyrange').datepicker("option","maxDate",new Date(REV_end_dates));
                        $('#REV_tb_enddtebyrange').datepicker("option","maxDate",new Date(REV_end_dates));
                    }
                }
                var choice="set_datemin_max";
                xmlhttp.open("GET","DB_REPORT_REVENUE.do?REV_project_name="+REV_project_name+"&option="+choice+"&project_recver="+project_recver,true);
                xmlhttp.send(new FormData(formElement));
                //SET END DATE
                $(document).on('change','#REV_tb_strtdtebyrange',function(){
                    $('#REV_lbl_emptitle').hide();
                    var USRC_UPD_startdate = $('#REV_tb_strtdtebyrange').datepicker('getDate');
                    var date = new Date( Date.parse( USRC_UPD_startdate ));
                    date.setDate( date.getDate()  );
                    var USRC_UPD_todate = date.toDateString();
                    USRC_UPD_todate = new Date( Date.parse( USRC_UPD_todate ));
                    $('#REV_tb_enddtebyrange').datepicker("option","minDate",USRC_UPD_todate);
                });
                $('#REV_lbl_selectdterange').show();
                $('#REV_btn_prjctsrch').hide();
                $('#REV_lbl_strtdtedaterange').show();
                $('#REV_tb_strtdtedaterange').val('').show();
                $('#REV_lbl_enddtedaterange').show();
                $('#REV_tb_enddtedaterange').val('').show();
                $("#REV_btn_search").attr("disabled","disabled");
                $('#REV_div_loginid').hide();
            }
        }
    }
// CHANGE EVENT FOR EMPLOYEE PROJECT SEARCH BUTTON
    $('#REV_lb_empproject').change(function(){
        $('#REV_lbl_emptitle').hide();
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_tb_strtdte').hide();
        $('#REV_lbl_strtdte').hide();
        $('#REV_lbl_enddte').hide();
        $('#REV_tb_enddte').hide();
        $('#REV_btn_search').hide();
        $('#REV_btn_empsrch').hide();
        $('#REV_nodata_startenddate').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_lbl_empday').hide();
        $('#REV_lb_recver').hide();
        $('#REV_lbl_recver').hide();
        $('#REV_btn_empsrch').attr("disabled","disabled");
        $('#REV_tble_empday_nonactveemp1').html();
        $('#REV_tble_nonactive_bydaterange').html('');
        $('#REV_tble_empday_nonactveemp1').html('');
        $('#REV_div_loginid').hide();
        $('#REV_nodata_loginid').hide();
        var option=$("#REV_lb_project").val();
        var formElement = document.getElementById("REV_form_revenue");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $(".preloader").hide();
                var values_array=JSON.parse(xmlhttp.responseText);
                REV_project_recver=values_array;
                if($('#REV_lb_empproject').val()=="SELECT")
                {
                    $('#REV_nodata_loginid').hide();
                    $('#REV_btn_empsrch').hide();
                    $('#REV_tb_strtdte').hide();
                    $('#REV_tb_enddte').hide();
                    $('#REV_lbl_emptitle').hide();
                    $('#REV_lbl_ttlprjct').hide();
                    $('#REV_nodata_startenddate').hide();
                    $('#REV_div_nonactve_dterange').hide();
                    $('#REV_btn_search').hide();
                    $('#REV_lbl_enddte').hide();
                    $('#REV_lbl_strtdte').hide();
                    $('#REV_tble_nonactive_bydaterange').html('');
                    $('#REV_lbl_recver').hide();
                    $('#REV_lb_recver').hide();
                }
                else
                {
                    var recver_list='<option>SELECT</option>';
                    for (var i=0;i<REV_project_recver.length;i++) {
                        recver_list += '<option value="' + REV_project_recver[i] + '">' + REV_project_recver[i] + '</option>';
                    }
                    $('#REV_lb_recver').html(recver_list);
                    if(option==8)
                    {
                        if(REV_project_recver.length>1)
                        {
                            $('#REV_lb_recver').val("SELECT").show();
                            $('#REV_lbl_recver').show();
                            $('#REV_btn_empsrch').show();
                            daterange();
                        }
                        else
                        {
                            $('#REV_btn_empsrch').removeAttr("disabled","disabled").show();
                            $('#REV_lb_recver').prop('selectedIndex',1);
                            $('#REV_lb_recver').hide();
                            $('#REV_lbl_recver').hide();
                            daterange();
                        }
                    }
                    if(option==9)
                    {
                        if(REV_project_recver.length>1)
                        {
                            $('#REV_lbl_recver').show();
                            $('#REV_lb_recver').val("SELECT").show();
                            daterange();
                        }
                        else
                        {
                            $('#REV_tb_strtdte').val('').show();
                            $('#REV_lbl_strtdte').show();
                            $('#REV_lb_recver').prop('selectedIndex',1);
                            $('#REV_lbl_enddte').show();
                            $('#REV_tb_enddte').val('').show();
                            $('#REV_btn_search').show();
                            daterange();
                        }
                        $("#REV_btn_search").attr("disabled","disabled");
                        //DATE PICKER FUNCTION
                        $('.REV_datepickerrnge ').datepicker(
                            {
                                dateFormat: 'dd-mm-yy',
                                changeYear: true,
                                changeMonth: true
                            });
                        //END DATE PICKER FUNCTION
                    }
                }
            }
        }
        var choice="PROJECTRECVERSION";
        xmlhttp.open("POST","DB_REPORT_REVENUE.do?option="+choice);
        xmlhttp.send(new FormData(formElement));
    });
    //FUNCTION FOR SET MIN ND MAX DATE FOR NON ACTIVE BY DATE RANGE
    function daterange(){
        var formElement = document.getElementById("REV_form_revenue");
        var REV_loginids=$("#REV_lb_loginid").val();
        var project_recverss=$('#REV_lb_recver').val();
        var project_namess=$('#REV_lb_empproject').val();
        var date_val=[];
        //FUNCTION FOR SETTINF MIN ND MAX DATE
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader', window.parent.document).hide();
                date_val=JSON.parse(xmlhttp.responseText);
                var REV_start_dates=date_val[0];
                var REV_end_dates=date_val[1];
                $('#REV_tb_strtdte').datepicker("option","minDate",new Date(REV_start_dates));
                $('#REV_tb_strtdte').datepicker("option","maxDate",new Date(REV_end_dates));
                $('#REV_tb_enddte').datepicker("option","maxDate",new Date(REV_end_dates));
            }
        }
        var choice="login_id";
        xmlhttp.open("GET","DB_REPORT_REVENUE.do?REV_loginids="+REV_loginids+"&option="+choice+"&project_recverss="+project_recverss+"&project_namess="+project_namess,true);
        xmlhttp.send(new FormData(formElement));
        //SET END DATE
        $(document).on('change','#REV_tb_strtdte',function(){
            $('#REV_lbl_emptitle').hide();
            var USRC_UPD_startdate = $('#REV_tb_strtdte').datepicker('getDate');
            var date = new Date( Date.parse( USRC_UPD_startdate ));
            date.setDate( date.getDate()  );
            var USRC_UPD_todate = date.toDateString();
            USRC_UPD_todate = new Date( Date.parse( USRC_UPD_todate ));
            $('#REV_tb_enddte').datepicker("option","minDate",USRC_UPD_todate);
        });
    }
    //click event for project rec ver
    $('#REV_lb_recver').change(function(){
        $('#REV_tb_strtdte').val('');
        $('#REV_tb_enddte').val('');
        var formElement = document.getElementById("REV_form_revenue");
        var REV_loginids=$("#REV_lb_loginid").val();
        var project_recverss=$('#REV_lb_recver').val();
        var project_namess=$('#REV_lb_empproject').val();
        var date_val=[];
        $('#REV_lbl_title').hide();
        $('#REV_nodata_pdflextble').hide();
        $('#REV_tb_strtdte').hide();
        $('#REV_lbl_strtdte').hide();
        $('#REV_lbl_enddte').hide();
        $('#REV_tb_enddte').hide();
        $('#REV_btn_search').hide();
        $('#REV_lbl_emptitle').hide();
        $('#REV_lbl_empday').hide();
        $('#REV_tble_empday_nonactveemp1').html('');
        $('sections').html('');
        $('#REV_nodata_loginid').hide();
        $('#REV_btn_search').attr("disabled","disabled");
        var emp_project=$('#REV_lb_empproject').val();
        var project_recver=$('#REV_lb_recver').val();
        var projectname=$('#REV_lb_project').val();
        if(emp_project!='SELECT'&& project_recver!="SELECT" && projectname==9){
            daterange()
            $('#REV_tb_strtdte').val('').show();
            $('#REV_lbl_strtdte').show();
            $('#REV_lbl_enddte').show();
            $('#REV_tb_enddte').val('').show();
            $('#REV_btn_search').show();
            $('#REV_btn_empsrch').removeAttr("disabled");
        }
        else if(emp_project!='SELECT'&& project_recver!="SELECT" && projectname==8){
            $('#REV_tb_strtdte').hide();
            $('#REV_lbl_strtdte').hide();
            $('#REV_lbl_enddte').hide();
            $('#REV_tb_enddte').hide();
            $('#REV_btn_search').hide();
            $('#REV_btn_empsrch').removeAttr("disabled");
        }
        else{
            $('#REV_btn_empsrch').attr("disabled","disabled");
            $('#REV_tb_strtdte').hide();
            $('#REV_lbl_strtdte').hide();
            $('#REV_lbl_enddte').hide();
            $('#REV_tb_enddte').hide();
            $('#REV_btn_search').hide();
            $('#REV_lbl_emptitle').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_tble_empday_nonactveemp1').html('');
            $('sections').html('');
            $('#REV_nodata_loginid').hide();
        }
        daterange();
    });
// CLICK EVENT FOR PROJECT SEARCH BUTTON
    var projectvalues=[];
    $(document).on('click','#REV_btn_prjctsrch',function(){
        $('#REV_nodata_pdflextble').hide();
        $('#REV_div_projecttotal').hide();
        $('#REV_lbl_totaldays').hide();
        $('#REV_lbl_totalhrs').hide();
        $('#REV_tble_totaldays').html('');
        var project_recver=$('#REV_lb_precver').val();
        $('#REV_btn_prjctsrch').attr("disabled","disabled");
        var REV_projectname=$('#REV_lb_projectname').val();
        $('.preloader', window.parent.document).show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var projectvalues=JSON.parse(xmlhttp.responseText);
                if(projectvalues)
                {
                    if(REV_project_recver.length>1)
                    {
                        var title=err_msg_array[7].toString().replace("[PROJECTNAME]",REV_projectname+' '+'VER'+ - +project_recver);
                    }
                    else
                    {
                        var title=err_msg_array[7].toString().replace("[PROJECTNAME]",REV_projectname);
                    }
                    $('#REV_lbl_title').text(title).show();
                    var total_days= projectvalues[0].working_day;
                    var total_hrs= projectvalues[0].REV_total_hrs;
                    $('#REV_lbl_totaldays').text("TOTAL NO OF  DAYS: "  +   total_days).show();
                    $('#REV_lbl_totalhrs').text("TOTAL NO OF HRS: "  +   total_hrs).show();
                    var REV_table_header='<table id="REV_tble_totaldays" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>USERNAME</th><th>NUMBER OF DAYS</th><th>NUMBER OF HRS</th><th>NUMBER OF MINUTES</th></tr></thead><tbody>'
                    for(var i=0;i<projectvalues.length;i++){
                        var username=projectvalues[i].username;
                        var noofdays=projectvalues[i].noofdays;
                        var total_hrs=projectvalues[i].total_hrs;
                        var total_minutes=projectvalues[i].total_minutes;
                        REV_table_header+='<tr><td>'+username+'</td><td align="center">'+noofdays+'</td><td align="center">'+total_hrs+'</td><td align="center">'+total_minutes+'</td></tr>';
                    }
                    REV_table_header+='</tbody></table>';
                    $('section').html(REV_table_header);
                    $('#REV_tble_totaldays').DataTable({
                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            {
                                "sExtends": "pdf",
                                "sTitle": title,
                                "sPdfOrientation": "landscape",
                                "sPdfSize": "A3"
                            }],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                }
                else
                {
                    var sd=err_msg_array[3].toString().replace("[NAME]",REV_projectname);
                    $('#REV_nodata_pdflextble').text(sd).show();
                    $('#REV_div_projecttotal').hide();
                }
                $('.preloader', window.parent.document).hide();
            }
        }
        $('#REV_div_projecttotal').show();
        var option="projectname";
        xmlhttp.open("GET","DB_REPORT_REVENUE.do?option="+option+"&REV_projectname="+REV_projectname+"&project_recver="+project_recver);
        xmlhttp.send();
    });
// CLICK EVENT FOR LOGINID SEARCH BUTTON
    var loginidvalues=[];
    $(document).on('click','#REV_btn_empsrch',function(){
        $('sections').html('');
        var REV_withproject=$('#REV_rd_withproject').val();
        $('#REV_nodata_loginid').hide();
        $('#REV_btn_empsrch').attr("disabled","disabled");
        $('#REV_div_loginid').hide();
        $('#REV_tble_empday_nonactveemp1').html('');
        var REV_loginid=$('#REV_lb_loginid').val();
        var REV_withproject=$('#REV_rd_withproject').val();
        var rec_ver=$('#REV_lb_recver').val();
        var formElement = document.getElementById("REV_form_revenue");
        var REV_prjctname=$('#REV_lb_empproject').val();
        var seacrhby_prjct=$("input[name=REV_rd_project]:checked").val();
        var project_recver=$('#REV_lb_recver').val();
        $('.preloader', window.parent.document).show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                loginidvalues=JSON.parse(xmlhttp.responseText);
                if(loginidvalues)
                {
                    if(seacrhby_prjct=='project')
                    {
                        var loginname;
                        var loginpos=REV_loginid.search("@");
                        if(loginpos>0){
                            loginname=REV_loginid.substring(0,loginpos);
                        }
                        var emptitle=err_msg_array[6].toString().replace("[LOGINID]",REV_loginid);
                        var employeetitle=emptitle.replace("[PROJECTNAME]",REV_prjctname);
                        if(REV_project_recver.length>1)
                        {
                            $('#REV_lbl_emptitle').text(employeetitle+' '+'VER'+ - +rec_ver).show();
                        }
                        else
                        {
                            $('#REV_lbl_emptitle').text(employeetitle).show();
                        }
                        var emptitle=err_msg_array[6].toString().replace("[LOGINID]",loginname);
                        if(REV_project_recver.length>1)
                        {
                            var employeetitles=emptitle.replace("[PROJECTNAME]",REV_prjctname+' '+'VER'+ - +rec_ver);
                        }
                        else
                        {
                            var employeetitles=emptitle.replace("[PROJECTNAME]",REV_prjctname);
                        }
                        var total_days= loginidvalues[0].working_day;
                        $('#REV_lbl_empday').text("TOTAL NO OF DAYS: "  +   total_days).show();
                        var REV_table_header1='<table id="REV_tble_empday_nonactveemp1" border="1"  cellspacing="0" class="srcresult" style="width:500px";><thead  bgcolor="#6495ed" style="color:white"><tr><th class="uk-date-column" style="width:150px" nowrap>PROJECT DATE</th><th style="max-width:10px; !important;">DAYS</th><th style="max-width:10px; !important;">HOURS</th><th style="max-width:10px; !important;">MINUTES</th></tr></thead><tbody>'
                        for(var i=0;i<loginidvalues.length;i++){
                            var projectdate=loginidvalues[i].projectdate;
                            var project_days=loginidvalues[i].project_days;
                            var project_hrs=loginidvalues[i].project_hrs;
                            var project_mints=loginidvalues[i].project_mints;
                            REV_table_header1+='<tr><td style="width:150px" nowrap align="center">'+projectdate+'</td><td align="center" style="max-width:10px; !important;">'+project_days+'</td><td align="center" style="max-width:10px; !important;">'+project_hrs+'</td><td align="center" style="max-width:10px; !important;">'+project_mints+'</td></tr>';
                        }
                    }
                    else
                    {
                        var loginname;
                        var loginpos=REV_loginid.search("@");
                        if(loginpos>0){
                            loginname=REV_loginid.substring(0,loginpos);
                        }
                        var employeetitle=err_msg_array[6].toString().replace("[LOGINID] FOR [PROJECTNAME]",REV_loginid);
                        var employeetitles=err_msg_array[6].toString().replace("[LOGINID] FOR [PROJECTNAME]",loginname);
                        $('#REV_lbl_emptitle').text(employeetitle).show();
                        var REV_table_header1='<table id="REV_tble_empday_nonactveemp1" border="1"  cellspacing="0" class="srcresult" style="width:500px";><thead  bgcolor="#6495ed" style="color:white"><tr><th class="uk-date-column" style="width:200px" nowrap>PROJECT NAME</th><th>DAYS</th><th>HOURS</th><th>MINUTES</th></tr></thead><tbody>'
                        for(var i=0;i<loginidvalues.length;i++){
                            var projectname=loginidvalues[i].projectname;
                            var project_days=loginidvalues[i].project_days;
                            var project_hrs=loginidvalues[i].project_hrs;
                            var project_mints=loginidvalues[i].project_mints;
                            REV_table_header1+='<tr><td style="width:200px" nowrap>'+projectname+'</td><td align="center">'+project_days+'</td><td align="center">'+project_hrs+'</td><td align="center">'+project_mints+'</td></tr>';
                        }
                    }
                    REV_table_header1+='</tbody></table>';
                    $('sections').html(REV_table_header1);
                    $('#REV_tble_empday_nonactveemp1').DataTable({
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers",
                        "aoColumnDefs" : [
                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"} ],
                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            {
                                "sExtends": "pdf",
                                "sTitle": employeetitles,
                                "sPdfOrientation": "landscape",
                                "sPdfSize": "A3"
                            }],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                }
                else
                {
                    var sd=err_msg_array[2].toString().replace("[LOGINID]",REV_loginid);
                    $('#REV_nodata_loginid').text(sd).show();
                    $('#REV_div_loginid').hide();
                }
                $('.preloader', window.parent.document).hide();
            }
        }
        $('#REV_div_loginid').show();
        var option="nonactiveempdatatble";
        xmlhttp.open("GET","DB_REPORT_REVENUE.do?option="+option+"&REV_loginid="+REV_loginid+"&REV_prjctname="+REV_prjctname+"&REV_withproject="+seacrhby_prjct+"&project_recver="+project_recver);
        xmlhttp.send(formElement);
        sorting();
    });
// CLICK EVENT FOR LOGINID SEARCH BUTTON
    var loginidvalues=[];
    $(document).on('click','#REV_btn_search',function(){
        $('#REV_nodata_startenddate').hide();
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_tble_nonactive_bydaterange').html('');
        $('#REV_lbl_ttlprjct').hide();
        $('#REV_lbl_eachproject_empday').hide();
        var REV_start_datevalue=$('#REV_tb_strtdte').val()
        var REV_end_datevalue=$('#REV_tb_enddte').val()
        var rec_ver=$('#REV_lb_recver').val();
        var seacrhby_prjct=$("input[name=REV_rd_project]:checked").val();
        $('#REV_btn_search').attr("disabled","disabled");
        var REV_loginid=$('#REV_lb_loginid').val();
        var REV_prjctname=$('#REV_lb_empproject').val();
        $('.preloader', window.parent.document).show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                loginidvalues=JSON.parse(xmlhttp.responseText);
                if(loginidvalues)
                {
                    if(seacrhby_prjct=='project')
                    {
                        var loginname;
                        var loginpos=REV_loginid.search("@");
                        if(loginpos>0){
                            loginname=REV_loginid.substring(0,loginpos);
                        }
                        var emptitle=err_msg_array[8].toString().replace("[LOGINID]",REV_loginid);
                        var startdate=emptitle.replace("[STARTDATE]",REV_start_datevalue);
                        var enddate=startdate.replace("[ENDDATE]",REV_end_datevalue);
                        var header=enddate.replace("[PROJECTNAME]",REV_prjctname);
                        if(REV_project_recver.length>1)
                        {
                            $('#REV_lbl_emptitle').text(header+' '+'VER'+ - +rec_ver).show();
                        }
                        else
                        {
                            $('#REV_lbl_emptitle').text(header).show();
                        }
                        //PDF FILE NAME ERR MSG
                        var emptitle=err_msg_array[8].toString().replace("[LOGINID]",loginname);
                        var startdate=emptitle.replace("[STARTDATE]",REV_start_datevalue);
                        var enddate=startdate.replace("[ENDDATE]",REV_end_datevalue);
                        if(REV_project_recver.length>1)
                        {
                            var headers=enddate.replace("[PROJECTNAME]",REV_prjctname+' '+'VER'+ - +rec_ver);
                        }
                        else
                        {
                            var headers=enddate.replace("[PROJECTNAME]",REV_prjctname);
                        }
                        var total_days= loginidvalues[0].total_no_project;
                        $('#REV_lbl_empday').text("TOTAL NO OF DAYS: "  +   total_days).show();
                        //TABLE HEADERS
                        var REV_tble_header='<table id="REV_tble_nonactive_bydaterange" border="1"  cellspacing="0" class="srcresult" style="width:500px";><thead  bgcolor="#6495ed" style="color:white"><tr><th class="uk-date-column" style="width:150px" nowrap align="center">PROJECT DATE</th><th >DAYS</th><th >HRS</th><th >MINUTES</th></tr></thead><tbody>'
                        for(var i=0;i<loginidvalues.length;i++){
                            var projectdate=loginidvalues[i].projectdate;
                            var project_days=loginidvalues[i].project_days;
                            var project_hrs=loginidvalues[i].project_hrs;
                            var project_mints=loginidvalues[i].project_mints;
                            REV_tble_header+='<tr><td style="width:150px" nowrap align="center">'+projectdate+'</td><td align="center">'+project_days+'</td><td align="center">'+project_hrs+'</td><td align="center">'+project_mints+'</td></tr>';
                        }
                    }
                    else
                    {
                        var loginname;
                        var loginpos=REV_loginid.search("@");
                        if(loginpos>0){
                            loginname=REV_loginid.substring(0,loginpos);
                        }
                        var emptitle=err_msg_array[5].toString().replace("[MONTH]",REV_loginid);
                        var startdate=emptitle.replace("[STARTDATE]",REV_start_datevalue);
                        var header=startdate.replace("[ENDDATE]",REV_end_datevalue);
                        $('#REV_lbl_emptitle').text(header).show();
                        var emptitle=err_msg_array[5].toString().replace("[MONTH]",loginname);
                        var startdate=emptitle.replace("[STARTDATE]",REV_start_datevalue);
                        var headers=startdate.replace("[ENDDATE]",REV_end_datevalue);
                        var REV_tble_header='<table id="REV_tble_nonactive_bydaterange" border="1"  cellspacing="0" class="srcresult" style="width:500px";><thead  bgcolor="#6495ed" style="color:white"><tr><th class="uk-date-column" style="width:250px" align="center">PROJECT NAME</th><th >DAYS</th><th >HRS</th><th >MINUTES</th></tr></thead><tbody>'
                        for(var i=0;i<loginidvalues.length;i++){
                            var projectname=loginidvalues[i].projectname;
                            var project_days=loginidvalues[i].project_days;
                            var project_hrs=loginidvalues[i].project_hrs;
                            var project_mints=loginidvalues[i].project_mints;
                            REV_tble_header+='<tr><td style="width:250px">'+projectname+'</td><td align="center">'+project_days+'</td><td align="center">'+project_hrs+'</td><td align="center">'+project_mints+'</td></tr>';
                        }
                    }
                    REV_tble_header+='</tbody></table>';
                    $('sectionrnge').html(REV_tble_header);
                    $('#REV_tble_nonactive_bydaterange').DataTable({
                        "aaSorting": [],
                        "pageLength": 10,
                        "sPaginationType":"full_numbers",
                        "aoColumnDefs" : [
                            { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"} ],

                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            {
                                "sExtends": "pdf",
                                "sTitle": headers,
                                "sPdfOrientation": "landscape",
                                "sPdfSize": "A3"
                            }],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                }
                else
                {
                    var sd=err_msg_array[1].toString().replace("[SDATE]",REV_start_datevalue);
                    var msg=sd.toString().replace("[EDATE]",REV_end_datevalue);
                    $('#REV_nodata_startenddate').text(msg).show();
                    $('#REV_div_nonactve_dterange').hide();
                    $('#REV_lbl_ttlprjct').hide();
                    $('#REV_lbl_eachproject_empday').hide();
                    $('#REV_lbl_empday').hide();
                }
                $('.preloader', window.parent.document).hide();
            }
        }
        $('#REV_div_nonactve_dterange').show();
        var option="non_activeemp_dterange";
        xmlhttp.open("GET","DB_REPORT_REVENUE.do?option="+option+"&REV_loginid="+REV_loginid+"&REV_start_datevalue="+REV_start_datevalue+"&REV_end_datevalue="+REV_end_datevalue+"&REV_prjctname="+REV_prjctname+"&REV_withproject="+seacrhby_prjct+"&rec_ver="+rec_ver);
        xmlhttp.send();
        sorting();
    });
// CLICK EVENT FOR PROJECT SEARCH BUTTON
    var projectvalues=[];
    $(document).on('click','#REV_btn_searchdaterange',function(){
        $('#REV_div_projecttotal_dtebyrange').hide();
        $('#REV_tble_projctrevenue_bydaterange').html('');
        $('#REV_btn_searchdaterange').attr("disabled","disabled");
        var REV_start_datevalue=$('#REV_tb_strtdtebyrange').val()
        var REV_end_datevalue=$('#REV_tb_enddtebyrange').val()
        var REV_projectname=$('#REV_lb_projectname').val();
        var REV_project_recversion=$('#REV_lb_precver').val();
        $('.preloader', window.parent.document).show();
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                var projectvalues=JSON.parse(xmlhttp.responseText);
                if(projectvalues)
                {
                    if(REV_project_recver.length>1)
                    {
                        var replaceprjct=err_msg_array[5].toString().replace("[MONTH]",REV_projectname+' '+'VER'+ - +REV_project_recversion);
                    }
                    else
                    {
                        var replaceprjct=err_msg_array[5].toString().replace("[MONTH]",REV_projectname);
                    }
                    var sd=replaceprjct.replace("[STARTDATE]",REV_start_datevalue);
                    var REV_lbl_dterangetitle=sd.replace("[ENDDATE]",REV_end_datevalue);
                    $('#REV_lbl_dterangetitle').text(REV_lbl_dterangetitle).show();
                    var working_day= projectvalues[0].working_day;
                    var REV_total_hrs= projectvalues[0].REV_total_hrs;
                    $('#REV_lbl_totaldays_dterange').text("TOTAL NO OF  DAYS: "  +   working_day).show();
                    $('#REV_lbl_totalhrs_dterange').text("TOTAL NO OF HRS: "  +   REV_total_hrs).show();
                    var REV_table_header='<table id="REV_tble_projctrevenue_bydaterange" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th>USERNAME</th><th>NUMBER OF DAYS</th><th>NUMBER OF HRS</th><th>NUMBER OF MINUTES</th></tr></thead><tbody>'
                    for(var i=0;i<projectvalues.length;i++){
                        var username=projectvalues[i].username;
                        var noofdays=projectvalues[i].noofdays;
                        var total_hrs=projectvalues[i].total_hrs;
                        var total_minutes=projectvalues[i].total_minutes;
                        REV_table_header+='<tr><td>'+username+'</td><td align="center">'+noofdays+'</td><td align="center">'+total_hrs+'</td><td align="center">'+total_minutes+'</td></tr>';
                    }
                    REV_table_header+='</tbody></table>';
                    $('sectionprbydtrange').html(REV_table_header);
                    $('#REV_tble_projctrevenue_bydaterange').DataTable({
                        dom: 'T<"clear">lfrtip',
                        tableTools: {"aButtons": [
                            {
                                "sExtends": "pdf",
                                "sTitle": REV_lbl_dterangetitle,
                                "sPdfOrientation": "landscape",
                                "sPdfSize": "A3"
                            }],
                            "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.2/swf/copy_csv_xls_pdf.swf"
                        }
                    });
                }
                else
                {
                    var sd=err_msg_array[1].toString().replace("[SDATE]",REV_start_datevalue);
                    var msg=sd.toString().replace("[EDATE]",REV_end_datevalue);
                    $('#REV_nodata_staenddate').text(msg).show();
                    $('#REV_div_projecttotal_dtebyrange').hide();
                    $('#REV_lbl_ttlprjct').hide();
                    $('#REV_lbl_empday').hide();
                    $('#REV_lbl_eachproject_empday').hide();
                }
                $('.preloader', window.parent.document).hide();
            }
        }
        $('#REV_div_projecttotal_dtebyrange').show();
        var option="projectname_dtebyrange";
        xmlhttp.open("GET","DB_REPORT_REVENUE.do?option="+option+"&REV_projectname="+REV_projectname+"&REV_start_datevalue="+REV_start_datevalue+"&REV_end_datevalue="+REV_end_datevalue+"&REV_project_recver="+REV_project_recversion);
        xmlhttp.send();
    });
    //FUNCTION FOR SORTING
    function sorting(){
        jQuery.fn.dataTableExt.oSort['uk_date-asc']  = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a)));
            var y = new Date( Date.parse(FormTableDateFormat(b)) );
            return ((x < y) ? -1 : ((x > y) ?  1 : 0));
        };
        jQuery.fn.dataTableExt.oSort['uk_date-desc'] = function(a,b) {
            var x = new Date( Date.parse(FormTableDateFormat(a)));
            var y = new Date( Date.parse(FormTableDateFormat(b)) );
            return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
        };
    }
});
//DOCUMENT READY FUNCTION END
</script>
<!--SCRIPT TAG END-->
<!--BODY TAG START-->
<body>
<div class="wrapper">
    <div  class="preloader MaskPanel"><div class="preloader statusarea" ><div style="padding-top:90px; text-align:center"><img src="image/Loading.gif"  /></div></div></div>
    <div class="title"><div style="padding-left:500px; text-align:left;" ><p><h3>REVENUE</h3><p></div></div>
    <form   id="REV_form_revenue" class="content" >
        <table>
            <table>
                <tr>
                    <td width="150"><label name="REV_lbl_prjct" id="REV_lbl_prjct" hidden>PROJECT<em>*</em></label></td>
                    <td width="150">
                        <select id="REV_lb_project" name="REV_lb_project" hidden>
                        </select>
                    </td>
                </tr>
            </table>
            <tr><td><label id="REV_nodata_rc" name="REV_nodata_rc" class="errormsg"></label></td></tr>
            <table  id="REV_tble_prjctrevenue"  hidden>
                <tr>
                    <td width="150"><label name="REV_lbl_prjctnme" id="REV_lbl_prjctnme" hidden >PROJECT NAME<em>*</em></label></td>
                    <td>
                        <select id="REV_lb_projectname" name="REV_lb_projectname" hidden>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="150"><label name="REV_lbl_precver" id="REV_lbl_precver" hidden>RECORD VERSION<em>*</em></label></td>
                    <td> <select id="REV_lb_precver" name="REV_lb_precver" hidden>
                        </select>
                    </td>
                </tr>
            </table>
            <tr><td><label id="REV_nodata_pd" name="REV_nodata_pd" class="errormsg"></label></td></tr>
            <table id="REV_tble_startdate"></table>
            <table id="REV_tble_searchbtn"></table>
            <table>
                <tr>
                    <td><input type="button" id="REV_btn_prjctsrch" name="REV_btn_prjctsrch" value="SEARCH" class="btn" disabled/></td>
                </tr>
                <tr><td><label id="REV_nodata_pdflextble" name="REV_nodata_pdflextble" class="errormsg"></label></td></tr>
                <tr>
                    <td><label id="REV_lbl_title" name="REV_lbl_title"  class="srctitle" hidden></label></td>
                </tr>
                <tr>
                    <td><label id="REV_lbl_totaldays" name="REV_lbl_totaldays"  class="srctitle" hidden></label></td>
                </tr>
                <tr>
                    <td><label id="REV_lbl_totalhrs" name="REV_lbl_totalhrs"  class="srctitle" hidden></label></td>
                </tr>
            </table>
            <tr>
                <div id ="REV_div_projecttotal" class="container" style="width:500px" hidden>
                    <section>
                    </section>
                </div>
            </tr>
            <tr><td><label id="REV_nodata_staenddate" name="REV_nodata_staenddate" class="errormsg"></label></td></tr>
            <tr><td><label id="REV_lbl_dterangetitle" name="REV_lbl_dterangetitle" class="srctitle"></label></td></tr><br>
            <tr>
                <td><label id="REV_lbl_totaldays_dterange" name="REV_lbl_totaldays_dterange"  class="srctitle" hidden></label></td><br>
            </tr>
            <tr>
                <td><label id="REV_lbl_totalhrs_dterange" name="REV_lbl_totalhrs_dterange"  class="srctitle" hidden></label></td>
            </tr>
            <tr>
                <div id ="REV_div_projecttotal_dtebyrange" class="container" style="width:500px" hidden>
                    <sectionprbydtrange>
                    </sectionprbydtrange>
                </div>
            </tr>
        </table>
        <table id="REV_tble_prjctrevactnonact" hidden>
            <tr>
                <td><input type="radio" name="REV_rd_veemp" id="REV_rd_actveemp" value="EMPLOYEE" hidden >
                    <label name="REV_lbl_actveemp" id="REV_lbl_actveemp"  hidden>ACTIVE EMPLOYEE</label></td>
            </tr>
            <tr>
                <td><input type="radio" name="REV_rd_veemp" id="REV_rd_nonactveemp"   value="EMPLOYEE" class='attnd' hidden>
                    <label name="REV_lbl_nonactveemp" id="REV_lbl_nonactveemp"  hidden>NON ACTIVE EMPLOYEE </label></td>
            </tr>
            <tr>
                <td><label name="REV_lbl_actveemps" id="REV_lbl_actveemps" class="srctitle" hidden>ACTIVE EMPLOYEE</label></td>
            </tr>
            <tr>
                <td><label name="REV_lbl_nonactveemps" id="REV_lbl_nonactveemps" class="srctitle" hidden>NON ACTIVE EMPLOYEE </label></td>
            </tr>
            <tr><td><label id="REV_nodata_uld" name="REV_nodata_uld" class="errormsg"></label></td></tr>
            <tr>
                <td><table>
                        <tr><td width="150">
                                <label name="REV_lbl_loginid" id="REV_lbl_loginid"  hidden>LOGIN ID<em>*</em></label></td>
                            <td>
                                <select name="REV_lb_loginid" id="REV_lb_loginid" hidden>
                                </select>
                            </td>
                        </tr></table></td>
            </tr>
            <tr>
                <td><input type="radio" name="REV_rd_project" id="REV_rd_withproject" value="project" hidden >
                    <label name="REV_lbl_withproject" id="REV_lbl_withproject"  hidden>LIST REVENUE BY PROJECT</label></td>
            </tr>
            <tr>
                <td><input type="radio" name="REV_rd_project" id="REV_rd_withoutproject"   value="withoutproject" hidden>
                    <label name="REV_lbl_withoutproject" id="REV_lbl_withoutproject"  hidden>LIST OF PROJECT REVENUE</label></td>
            </tr>
            <tr>
                <td><table>
                        <td width="150"><label name="REV_lbl_empproject" id="REV_lbl_empproject" hidden>PROJECT<em>*</em></label></td>
                        <td>
                            <select id="REV_lb_empproject" name="REV_lb_empproject" hidden>
                            </select>
                        </td>
                    </table></td>
            </tr>
            <tr>
                <td><table>
                        <td width="150"><label name="REV_lbl_recver" id="REV_lbl_recver" hidden>RECORD VERSION<em>*</em></label></td>
                        <td>
                            <select id="REV_lb_recver" name="REV_lb_recver" hidden>
                            </select>
                        </td>
                    </table></td>
            </tr>
            <tr>
                <td><input type="button" id="REV_btn_empsrch" name="REV_btn_empsrch" value="SEARCH" class="btn" disabled  /></td>
                <td>
            </tr>
            <tr><td><label id="REV_nodata_loginid" name="REV_nodata_loginid" class="errormsg"></label></td></tr>
            <table>
                <tr>
                    <td width="150"><label name="REV_lbl_strtdte" id="REV_lbl_strtdte" hidden>START DATE<em>*</em></label></td>
                    <td><input type="text" name="REV_tb_strtdte" id="REV_tb_strtdte" class=" valid clear REV_datepicker datemandtry" style="width:75px;" hidden></td><br>
                </tr>
                <tr>
                    <td width="150"><label name="REV_lbl_enddte" id="REV_lbl_enddte" hidden >END DATE<em>*</em></label></td>
                    <td><input type="text" name="REV_tb_enddte" id="REV_tb_enddte" class=" valid clear REV_datepicker datemandtry" style="width:75px;" hidden></td><br>
                </tr>
                <tr>
                    <td><input type="button" class="btn" name="REV_btn_search" id="REV_btn_search"  value="SEARCH" disabled></td>
                </tr>
            </table>
            <tr><td><label id="REV_nodata_startenddate" name="REV_nodata_startenddate" class="errormsg"></label></td></tr>
        </table>
        <tr>
            <td><label id="REV_lbl_emptitle" name="REV_lbl_emptitle"  class="srctitle" hidden></label></td>
        </tr>
        <tr>
            <td><label id="REV_lbl_ttlprjct" name="REV_lbl_ttlprjct"  class="srctitle" hidden></label></td><br>
        </tr>
        <tr>
            <td><label id="REV_lbl_empday" name="REV_lbl_empday"  class="srctitle" hidden></label></td>
        </tr><BR>
        <tr>
            <td><label id="REV_lbl_eachproject_empday" name="REV_lbl_eachproject_empday"  class="srctitle" hidden></label></td>
        </tr>
        <div  id ="REV_div_loginid" class="container" style="width:500px" hidden>
            <sections style="width:500px">
            </sections>
        </div>
        <div id ="REV_div_nonactve_dterange" class="container" style="width:500px" hidden>
            <sectionrnge style="width:500px">
            </sectionrnge>
        </div>

        </table>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->
