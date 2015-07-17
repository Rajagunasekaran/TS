<!--//*******************************************FILE DESCRIPTION*********************************************//
//**********************************************REVENUE*******************************************************//
//DONE BY:JAYAPRIYA
//VER 0.09-SD:29/06/2015 ED:04/07/2015, RECREATE ALL OPTIONS IN ORDER TO REMOVE THE EMPTY SPACE AND FIX THE DT HIDING ISSUES FOR THE  OPTION ACTIVE,NONACTIVE
//DONE BY:SARADAMBAL
//VER 0.08-SD:06/01/2015 ED:06/01/2015,TRACKER NO:74,IMPLEMENTED PRELOADER POSITION,INCLUDE PRELOADER WHILE SETTING MIN AND MAX DATE,CHANGED LOGIN ID INTO EMPLOYEE NAME,REMOVED DP VALIDATION IF DATE IS NULL
//DONE BY:RAJA
//VER 0.07-SD:02/01/2015 ED:02/01/2015, TRACKER NO:166, DESC:IMPLEMENTED PDF BUTTON AND VALIDATED AND GAVE INPUT TO DB
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
include "../../TSLIB/TSLIB_HEADER.php";
?>
<!--SCRIPT TAG START-->
<script>
    //GLOBAL DECLARATION
    //GLOBAL DECLARATION
    var err_msg_array=[];
    //READY FUNCTION START
    $(document).ready(function(){
        $('.preloader').hide();
        var title;
        var employeetitles;
        var heading;
        var REV_lbl_dterangetitle;
        $('#REV_div_nonactve_dterange').hide();
        $('#REV_div_loginid').hide();
        $('#REV_btn_totalhrs_pdf').hide();
        $('#REV_btn_emplist_pdf').hide();
        $('#REV_btn_emp_pdf').hide();
        $('#REV_btn_pdf').hide();
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
        var unique_prj_name=[];
        var REV_project_names=[];
        $('.preloader').show();
        var formElement = document.getElementById("REV_form_revenue");
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                $('.preloader').hide();
                var values_array=JSON.parse(xmlhttp.responseText);
//            alert(xmlhttp.responseText)
//            alert(values_array)
                REV_project_listbx=values_array[0];
//            alert(values_array[0])
                REV_project_name=values_array[1];
//            alert(values_array[1])
                REV_active_emp=values_array[2];
//            alert(values_array[2])
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
        xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_REVENUE.do?option="+option);
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
            $('#REV_div_nonactve_dterange').hide();
            var REV_start_date= $('#REV_tb_strtdte').val()
            var REV_end_date=$('#REV_tb_enddte').val()
            $('#REV_tble_nonactive_bydaterange').hide();
            $('#REV_lbl_emptitle').hide();
            $('#REV_btn_emplist_pdf').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_btn_emp_pdf').hide();
            $('#REV_lbl_ttlprjct').hide();
            $('#REV_lbl_eachproject_empday').hide();
            $('#REV_nodata_startenddate').hide();
            $('#sectionrnge').hide();
            if((REV_start_date!='') && (REV_end_date!='' ))
            {
                $("#REV_btn_search").removeAttr("disabled");
                $('#REV_btn_searchdivid').removeAttr("disabled");
            }
            else
            {
                $("#REV_btn_search").attr("disabled","disabled");
                $("#REV_btn_searchdivid").attr("disabled","disabled");
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
            $('#REV_btn_totalhrs_pdf').hide();
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
        $(document).on('change','#REV_lb_loginid',function(){
            $('.preloader').show();
            $('#REV_lbl_revenulist').hide();
            $('#REV_lbl_projectlist').hide();
            $('#REV_lbl_revenulist').hide();
            $('#REV_lbl_projectlist').hide();
            var login_id=$('#REV_lb_loginid').val();
            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    $('.preloader').hide();
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
            xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_REVENUE.do?option="+option+"&login_id="+login_id);
            xmlhttp.send();
        });
// CHANGE EVENT FOR PROJECT LISTBOX
        $(document).on('change','#REV_lb_project',function(){
            REV_hide();
            $('#REV_lbl_revenulistlbl').html('');
            $('#REV_lbl_projectlistlbl').html('');
            $('#REV_lbl_revenulist').html('');
            $('#REV_lbl_projectlist').html('');
            $('#project_act_lbl').html('');
            $('#project_nonact_lbl').html('');
            $('#project_act').html('');
            $('#project_nonact').html('');
            $('#REV_lbl_loginid').html('');
            $('#REV_lb_projectname').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
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
            $('#REV_btn_totalhrs_pdf').hide();
            $('#REV_rd_withproject').hide();
            $('#REV_lbl_withproject').hide();
            $('#REV_rd_withoutproject').hide();
            $('#REV_lbl_withoutproject').hide();
            $('#REV_lbl_emptitle').hide();
            $('#REV_btn_emplist_pdf').hide();
            $('#REV_btn_pdf').hide();
            var option=$("#REV_lb_project").val();
            if(option=="SELECT")
            {
                REV_hide(option);
                $('REV_lbl_loginid').html('');
                $('#REV_lbl_prjctnme').hide();
                $('#REV_lb_projectname').hide();
            }
            else if(option=='7')
            {
                if(REV_project_name.length!=0)
                {
                    $('#REV_lbl_actveempsdivid').html('');
                    $('#REV_rd_veempactdivid').html('');
                    $('#REV_lbl_nonactveempsdivid').html('');
                    $('#REV_rd_veempnonactdivid').html('');
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
                $('#REV_lbl_loginid').html('');
                $('#REV_tble_prjctrevactnonact').show();
                $('#project_act_lbl').html('').append('<div class="row-fluid"><label name="REV_lbl_actveemp" class="form-inline col-sm-3" id="REV_lbl_actveemp" hidden></label></div>');
                $('#project_nonact_lbl').html('').append('<div class="row-fluid"><label name="REV_lbl_nonactveemp" class="form-inline col-sm-3" id="REV_lbl_nonactveemp" hidden></label></div>');
                $('#project_act').html('').append('<div class=" form-group" style="padding-left: 35px"><div class="radio"><input type="radio" name="REV_rd_veemp"  id="REV_rd_actveemp" value="EMPLOYEE" hidden>ACTIVE EMPLOYEE</div></div>');
                $('#project_nonact').html('').append('<div class="form-group" style="padding-left: 35px"><div class="radio"><input type="radio" name="REV_rd_veemp" id="REV_rd_nonactveemp" value="EMPLOYEE" hidden>NON ACTIVE EMPLOYEE</div></div>');
                $('#project_act_lbl').show();
                $('#project_nonact_lbl').show();
                $('#project_act').show();
                $('#project_nonact').show();
                $('#REV_rd_actveemp').show();
                $('#REV_rd_nonactveemp').show();
                $('#REV_lbl_actveemp').show();
                $('#REV_lbl_nonactveemp').show();
                $('#REV_lbl_loginid').html('');
            }
            else if(option=='9')
            {
//            alert(option)
                $('#REV_lbl_loginid').html('');
                $('#project_act_lbl').html('').append('<div class="row-fluid"><label name="REV_lbl_actveemp" class="form-inline col-sm-3" id="REV_lbl_actveemp" hidden></label></div>');
                $('#project_nonact_lbl').html('').append('<div class="row-fluid"><label name="REV_lbl_nonactveemp" class="form-inline col-sm-3" id="REV_lbl_nonactveemp" hidden></label></div>');
                $('#project_act').html('').append('<div class="form-group" style="padding-left:35px "><div class="radio"><input type="radio" name="REV_rd_veemp"  id="REV_rd_actveemp" value="EMPLOYEE" hidden>ACTIVE EMPLOYEE</label></div></div>');
                $('#project_nonact').html('').append(' <div class="form-group" style="padding-left:35px "><div class="radio"> <input type="radio" name="REV_rd_veemp" id="REV_rd_nonactveemp" value="EMPLOYEE" hidden>NON ACTIVE EMPLOYEE </label></div></div>');
                $('#project_act_lbl').show();
                $('#project_nonact_lbl').show();
                $('#project_act').show();
                $('#project_nonact').show();
                $('#REV_rd_veempnonactdivid').html('');
                $('#REV_rd_veempactdivid').html('');
                $('#REV_tble_prjctrevactnonact').show();
                $('#REV_rd_actveemp').show();
                $('#REV_rd_nonactveemp').show();
                $('#REV_lbl_actveemp').show();
                $('#REV_lbl_nonactveemp').show();
            }
            else if(option=='10')
            {
                $('#REV_lbl_loginid').html('');
                if(REV_project_name.length!=0)
                {
                    $('#REV_lbl_loginid').html('');
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
            $('#REV_lbl_totaldays').hide();$('#REV_err_msg_date').hide()
            $('#REV_lbl_totalhrs').hide();
            $('#REV_tble_totaldays').hide();
            $('#REV_actlbl_loginid').hide();
            $('#REV_nonactlbl_loginid').hide();
            $('#REV_lb_loginid').hide();
            $('#REV_btn_empsrch').hide();
            $('#REV_lbl_ttlprjct').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_btn_emp_pdf').hide();
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
            $('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
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
        $(document).on('change','#REV_lb_projectname',function(){
//        alert('bbbb')
            $('.preloader').show();
            $('#REV_btn_prjctsrch').show();
            $('#REV_btn_prjctsrchdivid').show();
            $('#REV_err_msg_date').hide();
            $('#REV_err_msg_date_project').hide();
            $('#REV_tble_searchbtn').html('');
            $('#REV_nodata_loginid').hide();
            $('#REV_div_projecttotal').hide();
            $('#REV_div_projecttotal_dtebyrange').hide();
            $('#REV_nodata_pdflextble').hide();
            $('#REV_nodata_startenddate').hide();
            $('#REV_nodata_staenddate').hide();
            $('#REV_lbl_title').hide();
            $('#REV_lbl_dterangetitle').hide();
            $('#REV_btn_pdf').hide();
            if($('#REV_lb_projectname').val()=="SELECT")
            {
                $('.preloader').hide();
                $('#REV_lb_precver').hide();
                $('#REV_lbl_precver').hide();
                $('#REV_btn_prjctsrch').attr("disabled","disabled");
                $('#REV_btn_prjctsrchdivid').attr("disabled","disabled");
                $('#REV_tble_totaldays').hide();
                $('#REV_lbl_totaldays').hide();
                $('#REV_lbl_totalhrs').hide();
                $('#REV_btn_searchdaterange').hide();
                $('#REV_tble_searchbtn').html('');
            }
            else
            {
                $('.preloader').hide();
                $('#REV_tble_searchbtn').show();
                $('#REV_tble_startdate').show();
                $('#REV_btn_prjctsrch').removeAttr("disabled");
                $('#REV_btn_prjctsrchdivid').removeAttr("disabled");
                $('#REV_tble_totaldays').hide();
                $('#REV_lbl_totaldays').hide();
                $('#REV_lbl_totalhrs').hide();
            }
        });
// CLICK EVENT FOR ACTIVE RADIO BUTTON
        $(document).on('click','#REV_rd_actveemp',function(){
//        alert('active')
            $('#REV_nodata_rcdivid').hide();
            $('#REV_lbl_nonactveempsdivid').html('');
            $('#REV_lbl_revenulistlbl').html('');
            $('#REV_lbl_projectlistlbl').html('');
            $('#REV_lbl_revenulist').html('');
            $('#REV_lbl_projectlist').html('');
            $('#REV_div_projecttotal_dtebyrangedivid').html('');
            $('#REV_rd_veempnonactdivid').html('');
            $('#REV_tble_prjctrevenue').hide();
            $('#REV_lbl_totaldays_dterangedivid').html('');
            $('#REV_lbl_totalhrs_dterangedivid').html('');
            $('#REV_btn_totalhrs_pdfdivid').html('');
            $('#REV_lbl_dterangetitledivid').html('');
            $('#REV_tble_startdate').html('');
            $('#REV_tble_searchbtn').html('');
            $('#REV_tb_strtdtebyrange').html('');
            $('#REV_tb_enddtebyrange').html('');
            $('#REV_tble_searchbtn').html('');
            $('#REV_btn_prjctsrchdivid').html('');
            $('#REV_lbl_titledivid').html('');
            $('#REV_lbl_totaldaysdivid').html('');
            $('#REV_lbl_totalhrsdivid').html('');
            $('#REV_btn_pdfdivid').html('')
            $('#REV_div_projecttotaldivid').html('');
            $('#REV_lbl_dterangetitle').hide();
            $('#REV_lbl_totaldays_dterange').hide();
            $('#REV_lbl_totalhrs_dterange').hide();
            $('#REV_btn_totalhrs_pdf').hide();
            $('#REV_lbl_actveempsdivid').html('').append('<div class="form-group" style="padding-bottom: 10px"><label name="REV_lbl_actveemps" id="REV_lbl_actveemps" class="col-sm-12 srctitle" hidden>ACTIVE EMPLOYEE</label></div>');
            $('#REV_rd_veempactdivid').html('').append('<div class="row-fluid form-group"> <label class="col-sm-2" name="REV_lbl_loginid" id="REV_lbl_loginid">LOGIN ID<em>*</em></label><div class="col-sm-4"> <select name="REV_lb_loginid" id="REV_lb_loginid" class="form-control" style="display: none"> </select></div></div>');
            $('#REV_rd_veempactdivid').show();
            $('#REV_lbl_actveemps').show();
            $('#REV_lbl_actveempsdivid').show();
            $('#REV_rd_veempnonactdivid').html('');
            $('#REV_div_loginid').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
            $('#REV_lbl_emptitle').hide();
            $('#REV_btn_emplist_pdf').hide();
            $('#REV_nodata_uld').hide();
            $('#REV_nodata_loginid').hide();
            $('#REV_div_nonactve_dterange').hide();
            $('#REV_nodata_startenddate').hide();
            if(REV_active_emp.length!=0)
            {
                var active_employee='<option>SELECT</option>';
                for (var i=0;i<REV_active_emp.length;i++) {
                    active_employee += '<option value="' + REV_active_emp[i][1] + '">' + REV_active_emp[i][0] + '</option>';
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
            $('#REV_btn_emp_pdf').hide();
            $('#REV_lbl_eachproject_empday').hide();
            $('#REV_tble_empday').hide();
            $('#REV_lbl_nonactveemps').hide();
            $('#REV_lbl_strtdte').hide();
            $('#REV_tb_strtdte').hide();$('#REV_err_msg_date').hide();
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
        $(document).on('click','#REV_rd_nonactveemp',function(){
            $('#REV_nodata_rcdivid').hide();
//       alert('nonactive')
            $('#REV_div_projecttotal_dtebyrangedivid').html('');
            $('#REV_lbl_revenulistlbl').html('');
            $('#REV_lbl_projectlistlbl').html('');
            $('#REV_lbl_revenulist').html('');
            $('#REV_lbl_projectlist').html('');
            $('#REV_tble_prjctrevenue').hide();
            $('#REV_lbl_totaldays_dterangedivid').html('');
            $('#REV_lbl_totalhrs_dterangedivid').html('');
            $('#REV_btn_totalhrs_pdfdivid').html('');
            $('#REV_lbl_dterangetitledivid').html('');
            $('#REV_tble_startdate').html('');
            $('#REV_tble_searchbtn').html('');
            $('#REV_tb_strtdtebyrange').html('');
            $('#REV_tb_enddtebyrange').html('');
            $('#REV_btn_prjctsrchdivid').html('');
            $('#REV_lbl_titledivid').html('');
            $('#REV_lbl_totaldaysdivid').html('');
            $('#REV_lbl_totalhrsdivid').html('');
            $('#REV_btn_pdfdivid').html('')
            $('#REV_div_projecttotaldivid').html('');
            $('#REV_lbl_dterangetitle').hide();
            $('#REV_lbl_totaldays_dterange').hide();
            $('#REV_lbl_totalhrs_dterange').hide();
            $('#REV_btn_totalhrs_pdf').hide();
            $('#REV_lbl_actveempsdivid').html('');
            $('#REV_lbl_nonactveempsdivid').html('').append('<div class="form-group" style="padding-bottom: 10px"> <label name="REV_lbl_nonactveemps" id="REV_lbl_nonactveemps" class="srctitle col-sm-12" hidden>NON ACTIVE EMPLOYEE </label></div>');
            $('#REV_rd_veempnonactdivid').html('').append('<div class="row-fluid form-group"> <label class="col-sm-2" name="REV_lbl_loginid" id="REV_lbl_loginid">LOGIN ID<em>*</em></label><div class="col-sm-4"> <select name="REV_lb_loginid" id="REV_lb_loginid" class="form-control" style="display: none"> </select></div></div>');
            $('#REV_rd_veempnonactdivid').show();
            $('#REV_lbl_nonactveemps').show();
            $('#REV_lbl_nonactveempsdivid').show();
            $('#REV_rd_veempactdivid').html('');
            $('#REV_div_loginid').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
            $('#REV_lbl_emptitle').hide();
            $('#REV_btn_emplist_pdf').hide();
            $('#REV_nodata_uld').hide();
            $('#REV_nodata_loginid').hide();
            $('#REV_div_nonactve_dterange').hide();
            $('#REV_nodata_startenddate').hide();
            if(REV_nonactive_emp.length!=0)
            {
                var nonactive_employee='<option>SELECT</option>';
                for (var i=0;i<REV_nonactive_emp.length;i++) {
                    nonactive_employee += '<option value="' + REV_nonactive_emp[i][1] + '">' + REV_nonactive_emp[i][0] + '</option>';
                }
                $('#REV_lb_loginid').html(nonactive_employee);
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
            $('#REV_btn_emp_pdf').hide();
            $('#REV_lbl_eachproject_empday').hide();
            $('#REV_tble_empday').hide();
            $('#REV_lbl_strtdte').hide();
            $('#REV_tb_strtdte').hide();$('#REV_err_msg_date').hide();
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
        $(document).on('change','#REV_lb_loginid',function(){
            $('.preloader').hide();
            $('#REV_lbl_emptitle').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
            $('#REV_btn_emplist_pdf').hide();
            $('#REV_tb_strtdte').hide();
            $('#REV_lbl_enddte').hide();
            $('#REV_tb_enddte').hide();
            $('#REV_lbl_strtdte').hide();
            $('#REV_btn_search').hide();
            $('#REV_div_nonactve_dterange').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_btn_emp_pdf').hide();
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
                $('#REV_btn_emp_pdf').hide();
                $('#REV_lbl_eachproject_empday').hide();
                $('#REV_tble_empday').hide();
                $('#REV_div_loginid').hide();
                $('#REV_div_nonactve_dterange').hide();
                $('#REV_tb_strtdte').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
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
                    $('#REV_lbl_revenulistlbl').html('').append('<div class="form-group"><label name="REV_lbl_withproject"class="form-inline col-sm-3"  id="REV_lbl_withproject" hidden></label></div>');
                    $('#REV_lbl_projectlistlbl').html('').append('<div class="form-group"><label name="REV_lbl_withoutproject"class="form-inline col-sm-3"  id="REV_lbl_withoutproject"  hidden></label></div>');
                    $('#REV_lbl_revenulist').html('').append('<div class="form-group" style="padding-left: 35px"><div class="radio"><input type="radio" name="REV_rd_project" id="REV_rd_withproject"  value="project" hidden>LIST REVENUE BY PROJECT</div></div>');
                    $('#REV_lbl_projectlist').html('').append('<div class="form-group" style="padding-left: 35px"><div class="radio"><input type="radio" name="REV_rd_project" id="REV_rd_withoutproject"   value="withoutproject" hidden>LIST OF PROJECT REVENUE</div></div>');
                    $('#REV_lbl_ttlprjct').hide();
                    $('#REV_lbl_empday').hide();
                    $('#REV_btn_emp_pdf').hide();
                    $('#REV_lbl_eachproject_empday').hide();
                    $('#REV_tble_empday').hide();
                    $('#REV_lbl_strtdte').hide();
                    $('#REV_tb_strtdte').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
                    $('#REV_lbl_enddte').hide();
                    $('#REV_tb_enddte').hide();
                    $('#REV_btn_search').hide();
                    $('#REV_lbl_strtdte').hide();
                    $('#REV_tb_strtdte').hide();
                    $('#REV_lbl_enddte').hide();
                    $('#REV_tb_enddte').hide();
                    $('#REV_btn_search').hide();
                    $('#REV_lbl_revenulist').show();
                    $('#REV_lbl_projectlist').show();
                    $('#REV_lbl_revenulistlbl').show();
                    $('#REV_lbl_projectlistlbl').show();
                    $('#REV_rd_withproject').show();
                    $('#REV_rd_withproject').attr("checked",false);
                    $('#REV_lbl_withproject').show();
                    $('#REV_rd_withoutproject').show();
                    $('#REV_rd_withoutproject').attr("checked",false);
                    $('#REV_lbl_withoutproject').show();
                }
                else if(option=='9')
                {
//                alert(option);
                    $('#REV_lbl_revenulistlbl').html('').append('<div class="form-group" ><label name="REV_lbl_withproject"class="form-inline col-sm-3"  id="REV_lbl_withproject" hidden></label></div>');
                    $('#REV_lbl_projectlistlbl').html('').append('<div class="form-group"><label name="REV_lbl_withoutproject"class="form-inline col-sm-3"  id="REV_lbl_withoutproject"  hidden></label></div>');
                    $('#REV_lbl_revenulist').html('').append('<div class="form-group" style="padding-left: 35px"><div class="radio"><input type="radio" name="REV_rd_project" id="REV_rd_withproject"  value="project" hidden>LIST REVENUE BY PROJECT</div></div>');
                    $('#REV_lbl_projectlist').html('').append('<div class="form-group" style="padding-left: 35px"><div class="radio"><input type="radio" name="REV_rd_project" id="REV_rd_withoutproject"   value="withoutproject" hidden>LIST OF PROJECT REVENUE</div></div>');
                    $('#REV_lbl_revenulistlbl').show();
                    $('#REV_lbl_projectlistlbl').show();
                    $('#REV_lbl_revenulist').show();
                    $('#REV_lbl_projectlist').show();
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
        $(document).on('click','#REV_rd_withproject',function(){
            $('.preloader').show();
            $('#REV_btn_empsrchdivid').html('');
            $('#REV_lbl_empprojectdivid').html('').append('<div class="form-group" style="padding-right: 15px"><label class="col-sm-2" name="REV_lbl_empproject" id="REV_lbl_empproject">PROJECT<em>*</em></label> <div class="col-sm-4"> <select id="REV_lb_empproject" name="REV_lb_empproject" class="form-control"> </select></div></div>');
            $('#REV_lbl_empprojectdivid').show();
            $('#REV_lb_empproject').val("SELECT").show();
            $('#REV_lbl_empproject').show();
            $('#REV_lbl_recver').hide();
            $('#REV_lb_recver').hide();
            $('#REV_btn_empsrch').hide();
            $('#REV_tb_strtdte').hide();
            $('#REV_lbl_strtdte').hide();
            $('#REV_lbl_enddte').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
            $('#REV_tb_enddte').hide();
            $('#REV_nodata_loginid').hide();
            $('#REV_nodata_startenddate').hide();
            $('#REV_lbl_emptitle').hide();
            $('#REV_btn_emplist_pdf').hide();
            $('#REV_div_nonactve_dterange').hide();
            $('#REV_tble_nonactive_bydaterange').html('');
            $('#REV_div_loginid').hide();
            $('#REV_btn_search').hide();
            $('#REV_tble_empday_nonactveemp1').html('');
            $('#REV_tb_strtdte').val('');
            $('#REV_tb_enddte').val('');
            $('#REV_btn_empsrch').attr("disabled","disabled");
            var login_id=$('#REV_lb_loginid').val()
//         alert(login_id);
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    $('.preloader').hide();
                    var project=[];
                    var REV_project_names=values_array[0];
                    for (var i=0;i<REV_project_names.length;i++) {
                        project.push(REV_project_names[i][0]);
                    }
                    var unique_prj_name=STDLY_INPUT_unique(project)
//                alert(unique_prj_name)
                    var project_names='<option>SELECT</option>';
                    for (var j=0;j<unique_prj_name.length;j++) {
                        project_names += '<option value="' + unique_prj_name[j] + '">' + unique_prj_name[j] + '</option>';
                    }
                    $('#REV_lb_empproject').html(project_names);
                }
            }
            var option="SPECICIFIED_PROJECT_NAME";
            xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_REVENUE.do?option="+option+"&login_id="+login_id);
            xmlhttp.send();
        });
        //CLICK EVENT FOR WITHOUTPROJECT
        $(document).on('change','#REV_rd_withoutproject',function(){
            $('#REV_tb_strtdte').val('');
            $('#REV_tb_enddte').val('');
            $('#REV_lbl_emptitle').hide();
            $('#REV_btn_emplist_pdf').hide();
            $('#REV_lbl_ttlprjct').hide();
            $('#REV_btn_search').hide();
            $('#REV_nodata_startenddate').hide();
            $('#REV_div_nonactve_dterange').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_btn_emp_pdf').hide();
            $('#REV_nodata_loginid').hide();
            $('#REV_div_loginid').hide();
            $('#REV_tble_empday_nonactveemp1').html('');
            $('#REV_tble_nonactive_bydaterange').html('');
            $('sections').html('');
            var option=$("#REV_lb_project").val();
            if(option==8)
            {
                $('#REV_lbl_empprojectdivid').html('');
                $('#REV_btn_empsrchdivid').html('').append('<div class="form-group col-sm-12" style="padding-right: 35px"><input type="button" id="REV_btn_empsrch" name="REV_btn_empsrch" value="SEARCH" class="btn" disabled/></div>');
                $('#REV_btn_empsrchdivid').show();
                $('#REV_lbl_empproject').hide();
                $('#REV_lb_empproject').val("SELECT").hide();
                $('#REV_lbl_recver').hide();
                $('#REV_lb_recver').hide();
                $('#REV_btn_empsrch').show();
                $('#REV_btn_empsrch').removeAttr("disabled");
                $('#REV_tb_strtdte').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
                $('#REV_lbl_strtdte').hide();
                $('#REV_lbl_enddte').hide();
                $('#REV_tb_enddte').hide();
            }
            else if(option==9)
            {
                $('#REV_lbl_empprojectdivid').html('');
                $('.preloader').show();
                $('#REV_lbl_empproject').hide();
                $('#REV_lb_empproject').val("SELECT").hide();
                $('#REV_lbl_recver').hide();
                $('#REV_lb_recver').hide();
                $('#REV_btn_empsrch').hide();
                $('#REV_tb_strtdte').show();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
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
                $("#REV_tb_strtdte,#REV_tb_enddte").datepicker(
                    {
                        dateFormat: 'dd-mm-yy',
                        changeYear: true,
                        changeMonth: true
                    });
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var values_array=JSON.parse(xmlhttp.responseText);
                        $('.preloader').hide();
                        if(values_array[0]!=null && values_array[0]!=''){
                            $('#REV_tb_strtdte,#REV_tb_enddte,#REV_lbl_strtdte,#REV_lbl_enddte,#REV_btn_search').show();$('#REV_err_msg_date').hide();
                            $('#REV_tb_strtdte,#REV_tb_enddte').datepicker("option","minDate",new Date(values_array[0]));
                            $('#REV_tb_strtdte,#REV_tb_enddte').datepicker("option","maxDate",new Date(values_array[1]));}
                        else{
                            $('#REV_tb_strtdte,#REV_tb_enddte,#REV_lbl_strtdte,#REV_lbl_enddte,#REV_btn_search').hide();$('#REV_err_msg_date').show().text(err_msg_array[4]);
                        }
                    }}
                var choice="EMPLOYEEPERIOD";
                xmlhttp.open("POST","ADMIN/EMPLOYEE/DB_REPORT_REVENUE.do?option="+choice+"&selectoption="+$('#REV_lb_loginid').val());
                xmlhttp.send(new FormData(formElement));
                //END DATE PICKER FUNCTION
            }
        });
//CHANGE FUNCTION FOR PROJECT NAME
        $(document).on('change','#REV_lb_projectname',function(){
//        alert('aaaa')
            $('.preloader').show();
            $('#REV_lbl_actveempsdivid').html('');
            $('#REV_rd_veempactdivid').html('');
            $('section').html('');
            $('sectionprbydtrange').html('');
            $('#REV_btn_prjctsrch').hide();
            $('#REV_btn_prjctsrchdivid').hide();
            $('#REV_err_msg_date').hide();
            $('#REV_err_msg_date_project').hide();
            $('#REV_tble_startdate').html('');
            $('#REV_btn_searchdaterange').hide();
            $('#REV_lb_precver').hide();
            $('#REV_lbl_precver').hide();
            $('#REV_lbl_totaldays_dterange').hide();
            $('#REV_lbl_totalhrs_dterange').hide();
            $('#REV_btn_totalhrs_pdf').hide();
            var REV_projectname=$("#REV_lb_projectname").val();
            var project_name=$("#REV_lb_project").val();
            var formElement = document.getElementById("REV_form_revenue");
            var option=$("#REV_lb_project").val();
            $('#REV_div_loginid').hide();
            if($('#REV_lb_projectname').val()=="SELECT")
            {
                $('.preloader').hide();
                $('#REV_btn_empsrch').hide();
                $('#REV_btn_empsrch').attr("disabled","disabled");
                $('#REV_lbl_ttlprjct').hide();
                $('#REV_lbl_empday').hide();
                $('#REV_btn_emp_pdf').hide();
                $('#REV_lbl_eachproject_empday').hide();
                $('#REV_tble_empday').hide();
                $('#REV_div_loginid').hide();
                $('#REV_btn_searchdaterange').hide();
                $('#REV_nodata_pdflextble').hide();
                $('#REV_div_projecttotal_dtebyrange').hide();
            }
            else{
                $('#REV_lbl_precverdivid').html('').append('<div class="row-fluid form-group"> <label class="col-sm-2"name="REV_lbl_precver" id="REV_lbl_precver" hidden>RECORD VERSION<em>*</em></label><div class="col-sm-4"> <select id="REV_lb_precver" class="form-control" style="display: none" name="REV_lb_precver"> </select></div></div>')
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        $('.preloader').hide();
                        var values_array=JSON.parse(xmlhttp.responseText);
                        REV_project_recver=values_array;
                        var recver_list='<option>SELECT</option>';
                        for (var i=0;i<REV_project_recver.length;i++) {
                            recver_list += '<option value="' + REV_project_recver[i] + '">' + REV_project_recver[i] + '</option>';
                        }
                        $('#REV_lb_precver').html(recver_list);
                        if(REV_project_recver.length>1)
                        {
                            $('#REV_lbl_precverdivid').show();
                            $('#REV_lb_precver').show();
                            $('#REV_lbl_precver').show();
                            $('#REV_btn_prjctsrchdivid').show();
                        }
                        else
                        {
                            if(project_name==7){
//                            alert('77777')
                                $('#REV_btn_prjctsrchdivid').html('').append('<div class="row-fluid form-group col-sm-2"><input type="button" id="REV_btn_prjctsrch"  name="REV_btn_prjctsrch" value="SEARCH" class="btn" disabled/></div>')
                                $('#REV_btn_prjctsrch').show();
                                $('#REV_btn_prjctsrchdivid').show();
                                $('#REV_btn_prjctsrch').removeAttr("disabled");
                                $('#REV_btn_prjctsrchdivid').removeAttr("disabled");
                                $('#REV_lb_precver').prop('selectedIndex',1);
                                $('#REV_lb_precver').hide();
                                $('#REV_lbl_precver').hide();
                                var title=err_msg_array[8].toString().replace("[PROJECTNAME]",REV_projectname);
                            }
                            else if(project_name==10)
                            {
//                            alert('projectlboption2-10')
                                $('<div class="row-fluid form-group"><label class="col-sm-2" name="REV_lbl_strtdte" id="REV_lbl_strtdtebyrange" >START DATE<em>*</em></label><div class="col-sm-4"><input type="text" name="REV_tb_strtdtebyrange" id="REV_tb_strtdtebyrange" class="validsrchbtn clear REV_datepicker datemandtry" style="width:75px;" ></div></div>').appendTo('#REV_tble_startdate');
                                $('<div class="row-fluid form-group"><label class="col-sm-2" name="REV_lbl_enddte" id="REV_lbl_enddtebyrange" >END DATE<em>*</em></label><div class="col-sm-4"><input type="text" name="REV_tb_enddtebyrange" id="REV_tb_enddtebyrange" class="validsrchbtn clear REV_datepicker datemandtry" style="width:75px;" ></div></div>').appendTo('#REV_tble_startdate');
                                $('<div class="row-fluid form-group col-sm-2"><input type="button" class="btn" name="REV_btn_searchdaterange" id="REV_btn_searchdaterange"  value="SEARCH" disabled></div>').appendTo('#REV_tble_searchbtn')
                                $('#REV_tble_startdate').show();
                                $('#REV_tble_searchbtn').show();
                                $('#REV_lb_precver').hide();
                                $('#REV_lbl_precver').hide();
                                $('#REV_lb_precver').prop('selectedIndex',1);
                                REV_showform();
                            }
                        }
                    }
                }
                var choice="PROJECTRECVERSION";
                xmlhttp.open("POST","ADMIN/EMPLOYEE/DB_REPORT_REVENUE.do?option="+choice+"&selectoption="+option);
                xmlhttp.send(new FormData(formElement));
            }
        });
//CHANGE FUNCTION FOR RECORD VERSION
        $(document).on('change','#REV_lb_precver',function(){
            $('#REV_btn_searchdaterange').html('');
            $('#REV_lbl_dterangetitle').hide();
            $('#REV_nodata_staenddate').hide();
            $('#REV_lbl_totaldays').hide();
            $('#REV_lbl_totalhrs').hide();
            $('#REV_lbl_title').hide();
            $('#REV_btn_pdf').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
            $('#REV_lbl_totaldays_dterange').hide();
            $('#REV_lbl_totalhrs_dterange').hide();
            $('#REV_btn_totalhrs_pdf').hide();
            var project=$('#REV_lb_projectname').val();
            var projectname=$('#REV_lb_project').val();
            var project_recver=$('#REV_lb_precver').val();
            if( project_recver!="SELECT" && projectname==7)
            {
//            alert(projectname)
                $('#REV_btn_prjctsrchdivid').html('').append('<div class="row-fluid form-group col-sm-2"><input type="button" id="REV_btn_prjctsrch"  name="REV_btn_prjctsrch" value="SEARCH" class="btn" disabled/></div>');
                $('#REV_btn_prjctsrch').removeAttr("disabled");
                $('#REV_btn_prjctsrchdivid').removeAttr("disabled").show();
                $('#REV_lbl_title').hide();
                $('#REV_btn_pdf').hide();
            }
            else{
                $('#REV_btn_prjctsrch').attr("disabled","disabled").hide();
            }
        });
        // CHANGE EVENT FOR PROJECT NAME
        $(document).on('change','#REV_lb_precver',function(){
            REV_showform();
//        var newPos= adjustPosition($(this).position(),100,270);
//        resetPreloader(newPos);
//        $('.maskpanel',window.parent.document).css("height","297px").show();
//        $('.preloader').show();
        });
        function REV_showform(){
//        alert('optionrev10')
            $('section').html('');
            $('sectionprbydtrange').html('');
            $('#REV_btn_prjctsrch').hide();
            $('#REV_tble_startdate').html('');
            $('#REV_tble_searchbtn').html('');
            $('#REV_btn_searchdaterange').hide();
            var project_recver=$('#REV_lb_precver').val();
            var REV_loginids=$("#REV_lb_projectname").val();
            var option=$("#REV_lb_project").val();
//        alert(option)
//        alert($("#REV_lb_project").val());
            $('#REV_div_loginid').hide();
//        alert($('#REV_lb_precver').val());
            if($('#REV_lb_precver').val()=="SELECT")
            {
//            alert('revoptionselect')
//            $('.maskpanel',window.parent.document).removeAttr('style').hide();
//            $('.preloader').hide();
                $('#REV_btn_empsrch').hide();
                $('#REV_btn_empsrch').attr("disabled","disabled");
                $('#REV_lbl_ttlprjct').hide();
                $('#REV_lbl_empday').hide();
                $('#REV_btn_emp_pdf').hide();
                $('#REV_lbl_eachproject_empday').hide();
                $('#REV_tble_empday').hide();
                $('#REV_div_loginid').hide();
                $('#REV_btn_searchdaterange').hide();
                $('#REV_nodata_pdflextble').hide();
                $('#REV_div_projecttotal_dtebyrange').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
            }
            else
            {
                if(option=='7')
                {
//                alert('optionrev7')
                    $('#REV_btn_prjctsrchdivid').show();
                    $('#REV_btn_prjctsrch').show();
                    $('#REV_btn_prjctsrch').removeAttr("disabled");
                    $('#REV_btn_prjctsrchdivid').removeAttr("disabled").show();
                    $('#REV_lbl_ttlprjct').hide();
                    $('#REV_lbl_empday').hide();
                    $('#REV_btn_emp_pdf').hide();
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
                    $('#REV_btn_search').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();

                }
                else if((option=='10'))
                {
//                alert('revoption10append')
                    $('.preloader').show();
                    $('<div class="row-fluid form-group"><label class="col-sm-2" name="REV_lbl_strtdte" id="REV_lbl_strtdtebyrange" >START DATE<em>*</em></label><div class="col-sm-4"><input type="text" name="REV_tb_strtdtebyrange" id="REV_tb_strtdtebyrange" class="validsrchbtn clear REV_datepicker datemandtry" style="width:75px;" ></div></div>').appendTo('#REV_tble_startdate');
                    $('<div class="row-fluid form-group"><label class="col-sm-2" name="REV_lbl_enddte" id="REV_lbl_enddtebyrange" >END DATE<em>*</em></label><div class="col-sm-4"><input type="text" name="REV_tb_enddtebyrange" id="REV_tb_enddtebyrange" class="validsrchbtn clear REV_datepicker datemandtry" style="width:75px;" ></div></div>').appendTo('#REV_tble_startdate');
                    $('<div class="row-fluid form-group col-sm-2"><input type="button" class="btn" name="REV_btn_searchdaterange" id="REV_btn_searchdaterange"  value="SEARCH" disabled></div>').appendTo('#REV_tble_searchbtn')
                    $('#REV_tble_startdate').show();
                    $('#REV_tble_searchbtn').show();
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
                            $('.preloader').hide();
                            daterange_val=JSON.parse(xmlhttp.responseText);
                            var REV_start_dates=daterange_val[0];
                            var REV_end_dates=daterange_val[1];
//                        if(REV_end_dates!=null && REV_end_dates !=''){
                            if(REV_end_dates!=null){
                                $('#REV_lbl_selectdterange').show();
                                $('#REV_btn_prjctsrch').hide();
                                $('#REV_lbl_strtdtedaterange').show();
                                $('#REV_tb_strtdtedaterange').val('').show();
                                $('#REV_lbl_enddtedaterange').show();
                                $('#REV_tb_enddtedaterange').val('').show();
                                $('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
                                $('#REV_tb_strtdtebyrange').datepicker("option","minDate",new Date(REV_start_dates));
                                $('#REV_tb_strtdtebyrange').datepicker("option","maxDate",new Date(REV_end_dates));
                                $('#REV_tb_enddtebyrange').datepicker("option","minDate",new Date(REV_start_dates));
                                $('#REV_tb_enddtebyrange').datepicker("option","maxDate",new Date(REV_end_dates));}
                            else if(REV_end_dates == null || REV_end_dates== ''){
                                $('#REV_err_msg_date_project').show().text(err_msg_array[4]);
                                $('#REV_tble_startdate,#REV_tble_searchbtn').hide();
                                $('#REV_lbl_selectdterange').hide();
                                $('#REV_btn_prjctsrch').hide();
                                $('#REV_lbl_strtdtedaterange').hide();
                                $('#REV_tb_strtdtedaterange').val('').hide();
                                $('#REV_lbl_enddtedaterange').hide();
                                $('#REV_tb_enddtedaterange').val('').hide();
                            }
                        }
                    }
                    var choice="set_datemin_max";
                    xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_REVENUE.do?REV_project_name="+REV_project_name+"&option="+choice+"&project_recver="+project_recver,true);
                    xmlhttp.send(new FormData(formElement));
                    //SET END DATE
                    $(document).on('change','#REV_tb_strtdtebyrange',function(){
                        $('#REV_lbl_emptitle').hide();
                        $('#REV_btn_emplist_pdf').hide();
                        $('#REV_div_nonactve_dterange').hide();
                        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                        var USRC_UPD_startdate = $('#REV_tb_strtdtebyrange').datepicker('getDate');
                        var date = new Date( Date.parse( USRC_UPD_startdate ));
                        date.setDate( date.getDate()  );
                        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                        var USRC_UPD_todate = date.toDateString();
                        USRC_UPD_todate = new Date( Date.parse( USRC_UPD_todate ));
                        $('#REV_tb_enddtebyrange').datepicker("option","minDate",USRC_UPD_todate);
                    });

                    $("#REV_btn_search").attr("disabled","disabled");
                    $('#REV_div_loginid').hide();
                }
            }
        }
// CHANGE EVENT FOR EMPLOYEE PROJECT SEARCH BUTTON
        $(document).on('change','#REV_lb_empproject',function(){
//        alert('employeeproject')
            $('#REV_lbl_emptitle').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
            $('#REV_btn_emplist_pdf').hide();
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
            $('#REV_btn_emp_pdf').hide();
            $('#REV_lb_recver').hide();
            $('#REV_lbl_recver').hide();
            $('#REV_btn_empsrch').attr("disabled","disabled");
            $('#REV_tble_empday_nonactveemp1').html();
            $('#REV_tble_nonactive_bydaterange').html('');
            $('#REV_tble_empday_nonactveemp1').html('');
            $('#REV_div_loginid').hide();
            $('#REV_nodata_loginid').hide();
            var option=$("#REV_lb_project").val();
            $('.preloader').show();
            var formElement = document.getElementById("REV_form_revenue");
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var values_array=JSON.parse(xmlhttp.responseText);
                    REV_project_recver=values_array;
                    if($('#REV_lb_empproject').val()=="SELECT")
                    {
                        $('#REV_nodata_loginid').hide();
                        $('#REV_btn_empsrch').hide();
                        $('#REV_tb_strtdte').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
                        $('#REV_tb_enddte').hide();
                        $('#REV_lbl_emptitle').hide();
                        $('#REV_btn_emplist_pdf').hide();
                        $('#REV_lbl_ttlprjct').hide();
                        $('#REV_nodata_startenddate').hide();
                        $('#REV_div_nonactve_dterange').hide();
                        $('#REV_btn_search').hide();
                        $('#REV_lbl_enddte').hide();
                        $('#REV_lbl_strtdte').hide();
                        $('#REV_tble_nonactive_bydaterange').html('');
                        $('#REV_lbl_recver').hide();
                        $('#REV_lb_recver').hide();
                        $('.preloader').hide();
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
//                        alert(option)
                            if(REV_project_recver.length>1)
                            {
                                $('#REV_lb_recverdivid').html('').append('<div class="row-fluid form-group"><label class="col-sm-2" name="REV_lbl_recver" id="REV_lbl_recver" hidden>RECORD VERSION<em>*</em></label><div class="col-sm-4"><select id="REV_lb_recver" class="form-control" name="REV_lb_recver" ></select></div></div>');
                                $('#REV_btn_empsrchdivid').html('').append('<div class="form-group" style="padding-left: 9px"><input type="button" id="REV_btn_empsrch" name="REV_btn_empsrch" value="SEARCH" class="btn col-sm-12" disabled/></div>');
                                //  $('#REV_lb_recverdivid').show();
                                // $('#REV_btn_empsrchdivid').show();
                                var recver_list='<option>SELECT</option>';
                                for (var i=0;i<REV_project_recver.length;i++) {
                                    recver_list += '<option value="' + REV_project_recver[i] + '">' + REV_project_recver[i] + '</option>';
                                }
                                $('#REV_lb_recver').html(recver_list);
                                $('#REV_lb_recver').val("SELECT").show();
                                $('#REV_lbl_recver').show();
                                $('#REV_btn_empsrch').show();
                                $('REV_btn_empsrch').removeAttr("disabled");
                                $('#REV_btn_empsrchdivid').show();
                                $('REV_btn_empsrchdivid').removeAttr("disabled");
                                $('#REV_lb_recverdivid').show();
                                daterange();
                            }
                            else {
                                $('#REV_lb_recverdivid').html('').append('<div class="row-fluid form-group"><label class="col-sm-2" name="REV_lbl_recver" id="REV_lbl_recver" hidden>RECORD VERSION<em>*</em></label><div class="col-sm-4"><select id="REV_lb_recver" class="form-control" name="REV_lb_recver" ></select></div></div>');
                                $('#REV_btn_empsrchdivid').html('').append('<div class="form-group"  style="padding-left: 9px"><input type="button" id="REV_btn_empsrch" name="REV_btn_empsrch" value="SEARCH" class="btn col-sm-12" disabled/></div>');
                                $('#REV_btn_empsrchdivid').show();
                                var recver_list='<option>SELECT</option>';
                                for (var i=0;i<REV_project_recver.length;i++) {
                                    recver_list += '<option value="' + REV_project_recver[i] + '">' + REV_project_recver[i] + '</option>';
                                }
                                $('#REV_lb_recver').html(recver_list);
//                            $('#REV_lb_recver').val("SELECT").show();
                                $('#REV_lbl_recver').hide();
                                $('#REV_btn_empsrch').removeAttr("disabled","disabled").show()
                                $('#REV_btn_empsrchdivid').removeAttr("disabled").show();
                                $('#REV_lb_recver').prop('selectedIndex',1);
                                $('#REV_lb_recver').hide();
                                $('#REV_lbl_recver').hide();
                                daterange();
                            }
                        }
                        if(option==9)
                        {
//                        alert(option)
                            if(REV_project_recver.length>1)
                            {
//                            alert('recvcond')
                                $('#REV_lb_recverdivid').html('').append(' <div class="row-fluid form-group"><label class="col-sm-2" name="REV_lbl_recver" id="REV_lbl_recver" hidden>RECORD VERSION<em>*</em></label><div class="col-sm-4"><select id="REV_lb_recver" class="form-control" name="REV_lb_recver"></select></div></div>');
                                $('#REV_tb_strtdtedivid').html('').append('<div class="row-fluid form-group"><label class="col-sm-2" name="REV_lbl_strtdte" id="REV_lbl_strtdte" hidden>START DATE<em>*</em></label> <div class="col-sm-4"><input type="text" name="REV_tb_strtdte" id="REV_tb_strtdte" class=" valid clear REV_datepicker datemandtry" style="width:75px;" hidden></div></div>');
                                $('#REV_tb_enddtedivid').html('').append('<div class="row-fluid form-group"><label class="col-sm-2"  name="REV_lbl_enddte" id="REV_lbl_enddte" hidden >END DATE<em>*</em></label><div class="col-sm-4"><input type="text" name="REV_tb_enddte" id="REV_tb_enddte" class=" valid clear REV_datepicker datemandtry" style="width:75px;" hidden></div></div>')
                                $('#REV_btn_searchdivid').html('').append(' <div  class="row-fluid form-group col-sm-2" ><input type="button" class="btn" name="REV_btn_search" id="REV_btn_search"  value="SEARCH" disabled></div>');
                                $('#REV_lb_recverdivid').show();
                                $('#REV_btn_searchdivid').show();
                                $('#REV_tb_strtdtedivid').show();
                                $('#REV_tb_enddtedivid').show();
                                $("#REV_tb_strtdte,#REV_tb_enddte").datepicker(
                                    {
                                        dateFormat: 'dd-mm-yy',
                                        changeYear: true,
                                        changeMonth: true
                                    });
                                var recver_list='<option>SELECT</option>';
                                for (var i=0;i<REV_project_recver.length;i++) {
                                    recver_list += '<option value="' + REV_project_recver[i] + '">' + REV_project_recver[i] + '</option>';
                                }
                                $('#REV_lb_recver').html(recver_list);
                                $('#REV_lbl_recver').show();
                                $('#REV_lb_recver').val("SELECT").show();
                                daterange();
                            }
                            else
                            {
//                            alert('elsecon')
                                $('#REV_tb_strtdtedivid').html('').append(' <div class="row-fluid form-group"><label class="col-sm-2" name="REV_lbl_strtdte" id="REV_lbl_strtdte" hidden>START DATE<em>*</em></label> <div class="col-sm-4"><input type="text" name="REV_tb_strtdte" id="REV_tb_strtdte" class=" valid clear REV_datepicker datemandtry" style="width:75px;" hidden></div></div>');
                                $('#REV_tb_enddtedivid').html('').append('<div class="row-fluid form-group"><label class="col-sm-2"  name="REV_lbl_enddte" id="REV_lbl_enddte" hidden >END DATE<em>*</em></label><div class="col-sm-4"><input type="text" name="REV_tb_enddte" id="REV_tb_enddte" class=" valid clear REV_datepicker datemandtry" style="width:75px;" hidden></div></div>')
                                $('#REV_btn_searchdivid').html('').append('<div  class="row-fluid form-group col-sm-2" ><input type="button" class="btn" name="REV_btn_search" id="REV_btn_search"  value="SEARCH" disabled></div>');
                                var recver_list='<option>SELECT</option>';
                                for (var i=0;i<REV_project_recver.length;i++) {
                                    recver_list += '<option value="' + REV_project_recver[i] + '">' + REV_project_recver[i] + '</option>';
                                }
                                $('#REV_lb_recver').html(recver_list);
//                            $('#REV_lb_recver').val("SELECT").show();
                                $('#REV_lbl_recver').hide();
                                $('#REV_tb_strtdtedivid').show();
                                $('#REV_tb_enddtedivid').show();
                                $('#REV_btn_searchdivid').show();
                                $('#REV_tb_strtdte').val('').show();
                                $('#REV_lbl_strtdte').show();
                                $('#REV_lb_recver').prop('selectedIndex',1);
                                $('#REV_lbl_enddte').show();
                                $('#REV_tb_enddte').val('').show();
                                $('#REV_btn_search').show();
                                daterange();
                                $("#REV_tb_strtdte,#REV_tb_enddte").datepicker(
                                    {
                                        dateFormat: 'dd-mm-yy',
                                        changeYear: true,
                                        changeMonth: true
                                    });
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
            xmlhttp.open("POST","ADMIN/EMPLOYEE/DB_REPORT_REVENUE.do?option="+choice);
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
                    $('.preloader').hide();
                    date_val=JSON.parse(xmlhttp.responseText);
                    var REV_start_dates=date_val[0];
                    var REV_end_dates=date_val[1];
                    if(REV_start_dates!=null && REV_start_dates !='' && $('#REV_lb_project').val()!=8){
                        $('#REV_tb_strtdte,#REV_tb_enddte,#REV_btn_search,#REV_lbl_enddte,#REV_lbl_strtdte').show();$('#REV_err_msg_date').hide();
                        $('#REV_tb_strtdte').datepicker("option","minDate",new Date(REV_start_dates));
                        $('#REV_tb_strtdte').datepicker("option","maxDate",new Date(REV_end_dates));
                        $('#REV_tb_enddte').datepicker("option","minDate",new Date(REV_start_dates));
                        $('#REV_tb_enddte').datepicker("option","maxDate",new Date(REV_end_dates));
                    }
                    else if($('#REV_lb_project').val()!=8){
                        $('#REV_tb_strtdte,#REV_tb_enddte,#REV_btn_search,#REV_lbl_enddte,#REV_lbl_strtdte').hide();
                        $('#REV_err_msg_date').text(err_msg_array[4]).show();
                    }
                }
            }
            var choice="login_id";
            xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_REVENUE.do?REV_loginids="+REV_loginids+"&option="+choice+"&project_recverss="+project_recverss+"&project_namess="+project_namess,true);
            xmlhttp.send(new FormData(formElement));
            //SET END DATE
            $(document).on('change','#REV_tb_strtdte',function(){
                $('#REV_lbl_emptitle').hide();
                $('#REV_btn_emplist_pdf').hide();
                $('#REV_div_nonactve_dterange').hide();
                var USRC_UPD_startdate = $('#REV_tb_strtdte').datepicker('getDate');
                var date = new Date( Date.parse( USRC_UPD_startdate ));
                date.setDate( date.getDate()  );
                var USRC_UPD_todate = date.toDateString();
                USRC_UPD_todate = new Date( Date.parse( USRC_UPD_todate ));
                $('#REV_tb_enddte').datepicker("option","minDate",USRC_UPD_todate);
            });
        }
        //click event for project rec ver
        $(document).on('change','#REV_lb_recver',function(){
//        alert('recvproj')
            $('#REV_div_loginid').hide();
            $('#REV_tb_strtdte').val('');
            $('#REV_tb_enddte').val('');
            var formElement = document.getElementById("REV_form_revenue");
//        alert(formElement)
            var REV_loginids=$("#REV_lb_loginid").val();
            var project_recverss=$('#REV_lb_recver').val();
//        alert(project_recverss)
            var project_namess=$('#REV_lb_empproject').val();
            var date_val=[];
            $('#REV_lbl_title').hide();
            $('#REV_btn_pdf').hide();
            $('#REV_nodata_pdflextble').hide();
            $('#REV_tb_strtdte').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
            $('#REV_lbl_strtdte').hide();
            $('#REV_lbl_enddte').hide();
            $('#REV_tb_enddte').hide();
            $('#REV_btn_search').hide();
            $('#REV_lbl_emptitle').hide();
            $('#REV_btn_emplist_pdf').hide();
            $('#REV_lbl_empday').hide();
            $('#REV_btn_emp_pdf').hide();
            $('#REV_tble_empday_nonactveemp1').html('');
            $('sections').html('');
            $('#REV_nodata_loginid').hide();
            $('#REV_btn_search').attr("disabled","disabled");
            var emp_project=$('#REV_lb_empproject').val();
            var project_recver=$('#REV_lb_recver').val();
            var projectname=$('#REV_lb_project').val();
            if(emp_project!='SELECT'&& project_recver!="SELECT" && projectname==9){
//            alert('recvnotequaltoselect')
                $('#REV_div_loginid').hide();
                $('#REV_div_nonactve_dterange').hide();
                daterange()
                $('#REV_tb_strtdte').val('').show();
                $('#REV_lbl_strtdte').show();
                $('#REV_lbl_enddte').show();
                $('#REV_tb_enddte').val('').show();
                $('#REV_btn_search').show();
                $('#REV_btn_empsrch').removeAttr("disabled");
                $('#REV_btn_empsrchdivid').removeAttr("disabled");
            }
            else if(emp_project!='SELECT'&& project_recver!="SELECT" && projectname==8){
//            alert('elseifemprecr');
                $('#REV_div_loginid').hide();
                $('#REV_tb_strtdte').hide();
                $('#REV_err_msg_date').hide();
                $('#REV_err_msg_date_project').hide();
                $('#REV_lbl_strtdte').hide();
                $('#REV_lbl_enddte').hide();
                $('#REV_tb_enddte').hide();
                $('#REV_btn_search').hide();
                $('#REV_btn_empsrch').removeAttr("disabled");
                $('#REV_btn_empsrchdivid').removeAttr("disabled");
            }
            else{
                $('#REV_btn_empsrch').attr("disabled","disabled");
                $('#REV_tb_strtdte').hide();$('#REV_err_msg_date').hide();$('#REV_err_msg_date_project').hide();
                $('#REV_lbl_strtdte').hide();
                $('#REV_lbl_enddte').hide();
                $('#REV_tb_enddte').hide();
                $('#REV_btn_search').hide();
                $('#REV_lbl_emptitle').hide();
                $('#REV_btn_emplist_pdf').hide();
                $('#REV_lbl_empday').hide();
                $('#REV_btn_emp_pdf').hide();
                $('#REV_tble_empday_nonactveemp1').html('');
                $('sections').html('');
                $('#REV_nodata_loginid').hide();
                $('#REV_nodata_startenddate').hide();
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
//        alert(project_recver)
            $('#REV_btn_prjctsrch').attr("disabled","disabled");
            var REV_projectname=$('#REV_lb_projectname').val();
//        alert(REV_projectname)
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var projectvalues=JSON.parse(xmlhttp.responseText);
                    if(projectvalues)
                    {
                        $('.preloader').hide();
                        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                        if(REV_project_recver.length>1)
                        {
                            title=err_msg_array[8].toString().replace("[PROJECTNAME]",REV_projectname+' '+'VER'+ - +project_recver);
                        }
                        else
                        {
                            title=err_msg_array[8].toString().replace("[PROJECTNAME]",REV_projectname);
                        }
                        $('#REV_lbl_titledivid').html('').append('<div class="row-fluid form-group col-sm-2" ><label id="REV_lbl_title" name="REV_lbl_title"  class="srctitle" hidden></label></div>')
                        $('#REV_lbl_titledivid').show();
                        $('#REV_lbl_title').text(title).show();
                        $('#REV_btn_pdfdivid').html('').append('<div class="row-fluid form-group col-sm-2" ><input type="button" id="REV_btn_pdf" class="btnpdf" value="PDF"></div>');
                        $('#REV_btn_pdf').show();
                        $('#REV_btn_pdfdivid').show();
                        var total_days= projectvalues[0].working_day;
                        var total_hrs= projectvalues[0].REV_total_hrs;
                        $('#REV_lbl_totaldaysdivid').html('').append('<div class="row-fluid form-group col-sm-2" ><label id="REV_lbl_totaldays" name="REV_lbl_totaldays"  class="srctitle" hidden></label></div>')
                        $('#REV_lbl_totaldays').text("TOTAL NO OF  DAYS: "  +   total_days).show();
                        $('#REV_lbl_totaldaysdivid').show();
                        $('#REV_lbl_totalhrsdivid').html('').append('<div class="row-fluid form-group col-sm-2" ><label id="REV_lbl_totalhrs" name="REV_lbl_totalhrs"  class="srctitle" hidden></label></div>')
                        $('#REV_lbl_totalhrs').text("TOTAL NO OF HRS: "  +   total_hrs).show();
                        $('#REV_lbl_totalhrsdivid').show();
                        var REV_table_header='<table id="REV_tble_totaldays" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th style="width:150px">EMPLOYEE NAME</th><th>NUMBER OF DAYS</th><th>NUMBER OF HRS</th><th>NUMBER OF MINUTES</th></tr></thead><tbody>'
                        for(var i=0;i<projectvalues.length;i++){
                            var username=projectvalues[i].username;
                            var noofdays=projectvalues[i].noofdays;
                            var total_hrs=projectvalues[i].total_hrs;
                            var total_minutes=projectvalues[i].total_minutes;
                            REV_table_header+='<tr><td>'+username+'</td><td align="center">'+noofdays+'</td><td align="center">'+total_hrs+'</td><td align="center">'+total_minutes+'</td></tr>';
                        }
                        REV_table_header+='</tbody></table>';
//                    alert(REV_table_header)
                        $('section').html(REV_table_header);
                        $('#REV_tble_totaldays').DataTable({
                        });
                    }
                    else
                    {
                        $('.preloader').hide();
                        var sd=err_msg_array[3].toString().replace("[NAME]",REV_projectname);
                        $('#REV_nodata_pdflextble').text(sd).show();
                        $('#REV_div_projecttotal').hide();
                    }

                }
            }
            $('#REV_div_projecttotaldivid').html('').append('<div id ="REV_div_projecttotal" class="table-responsive row-fluid form-group col-sm-2" style="max-width:550px"><section></section></div>');
            $('#REV_div_projecttotal').show();
            $('#REV_div_projecttotaldivid').show();
            var option="projectname";
            xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_REVENUE.do?option="+option+"&REV_projectname="+REV_projectname+"&project_recver="+project_recver);
            xmlhttp.send();
        });
// CLICK EVENT FOR LOGINID SEARCH BUTTON
        var loginidvalues=[];
        $(document).on('click','#REV_btn_empsrch',function(){
//        alert('search')
            $('.preloader').show();
            $('sections').html('');

            var REV_withproject=$('#REV_rd_withproject').val();
            $('#REV_nodata_loginid').hide();
            $('#REV_btn_empsrch').attr("disabled","disabled");
            $('#REV_div_loginid').hide();
            $('#REV_nodata_loginid').hide();
            $('#REV_tb_enddtedivid').html('');
            $('#REV_tb_strtdtedivid').html('');
            $('#REV_tble_empday_nonactveemp1').html('');
            var REV_loginid=$('#REV_lb_loginid').val();
//        alert('login')
//        alert($('#REV_lb_loginid').val());
            var REV_withproject=$('#REV_rd_withproject').val();
//        alert('revwithproject')
//        alert(REV_withproject)
            var rec_ver=$('#REV_lb_recver').val();
//        alert('recver')
//        alert(rec_ver)
            var formElement = document.getElementById("REV_form_revenue");
            var REV_prjctname=$('#REV_lb_empproject').val();
//        alert('projectname')
//        alert(REV_prjctname)
            var seacrhby_prjct=$("input[name=REV_rd_project]:checked").val();
            var project_recver=$('#REV_lb_recver').val();
//        alert(project_recver)
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
//                alert(xmlhttp.responseText)
//                alert(loginidvalues)
                    loginidvalues=JSON.parse(xmlhttp.responseText);
                    $('.preloader').hide();
                    if(loginidvalues)
                    {
                        $('.preloader').hide();
                        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                        if(seacrhby_prjct=='project')
                        {
                            var loginname;
                            var loginpos=REV_loginid.search("@");
                            if(loginpos>0){
                                loginname=REV_loginid.substring(0,loginpos);
                            }
                            var emptitle=err_msg_array[7].toString().replace("[LOGINID]",$("#REV_lb_loginid option:selected").text());
                            var employeetitle=emptitle.replace("[PROJECTNAME]",REV_prjctname);

                            if(REV_project_recver.length>1)
                            {
//                            alert('ifrecv')
//                            $('#REV_lbl_emptitledivid').html('').append('<div class="row-fluid form-group col-sm-2"><label id="REV_lbl_emptitle" name="REV_lbl_emptitle"  class="srctitle" hidden></label></div>');
                                $('#REV_lbl_emptitle').text(employeetitle+' '+'VER'+ - +rec_ver).show();
//                            $('#REV_lbl_emptitledivid').show();
                            }
                            else
                            {
//                            alert('elserecv')
//                            $('#REV_lbl_emptitledivid').html('').append('<div class="row-fluid form-group col-sm-2"><label id="REV_lbl_emptitle" name="REV_lbl_emptitle"  class="srctitle" hidden></label></div>');
                                $('#REV_lbl_emptitle').text(employeetitle).show();
//                            $('#REV_lbl_emptitledivid').show();
                            }
                            var emptitle=err_msg_array[7].toString().replace("[LOGINID]",$("#REV_lb_loginid option:selected").text());
                            if(REV_project_recver.length>1)
                            {
//                            alert('ifprojver')
                                employeetitles=emptitle.replace("[PROJECTNAME]",REV_prjctname+' '+'VER'+ - +rec_ver);
                            }
                            else
                            {
//                            alert('elserecv')
                                employeetitles=emptitle.replace("[PROJECTNAME]",REV_prjctname);
                            }
                            var total_days= loginidvalues[0].working_day;
                            $('#REV_lbl_empday').text("TOTAL NO OF DAYS: "  +   total_days).show();
                            $('#REV_btn_emp_pdf').show();
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
                            var employeetitle=err_msg_array[7].toString().replace("[LOGINID] FOR [PROJECTNAME]",$("#REV_lb_loginid option:selected").text());
                            employeetitles=err_msg_array[7].toString().replace("[LOGINID] FOR [PROJECTNAME]",$("#REV_lb_loginid option:selected").text());
//                        $('#REV_lbl_emptitledivid').show();
                            $('#REV_lbl_emptitle').text(employeetitle).show();
                            $('#REV_btn_emplist_pdf').show();
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
                                { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"} ]
                        });
                    }
                    else
                    {
                        $('.preloader').hide();
                        var sd=err_msg_array[2].toString().replace("[LOGINID]",$("#REV_lb_loginid option:selected").text());
                        $('#REV_nodata_loginid').text(sd).show();
                        $('#REV_div_loginid').hide();
                    }

                }
            }
            $('#REV_div_loginid').show();
            var option="nonactiveempdatatble";
            xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_REVENUE.do?option="+option+"&REV_loginid="+REV_loginid+"&REV_prjctname="+REV_prjctname+"&REV_withproject="+seacrhby_prjct+"&project_recver="+project_recver);
            xmlhttp.send(formElement);
            sorting();
        });
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
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    loginidvalues=JSON.parse(xmlhttp.responseText);
                    if(loginidvalues)
                    {
                        $('.preloader').hide();
                        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                        if(seacrhby_prjct=='project')
                        {
                            var loginname;
                            var loginpos=REV_loginid.search("@");
                            if(loginpos>0){
                                loginname=REV_loginid.substring(0,loginpos);
                            }
                            var emptitle=err_msg_array[9].toString().replace("[LOGINID]",$("#REV_lb_loginid option:selected").text());
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
                            var emptitle=err_msg_array[9].toString().replace("[LOGINID]",$("#REV_lb_loginid option:selected").text());
                            var startdate=emptitle.replace("[STARTDATE]",REV_start_datevalue);
                            var enddate=startdate.replace("[ENDDATE]",REV_end_datevalue);
                            if(REV_project_recver.length>1)
                            {
                                heading=enddate.replace("[PROJECTNAME]",REV_prjctname+' '+'VER'+ - +rec_ver);
                            }
                            else
                            {
                                heading=enddate.replace("[PROJECTNAME]",REV_prjctname);
                            }
                            var total_days= loginidvalues[0].total_no_project;
                            $('#REV_lbl_empday').text("TOTAL NO OF DAYS: "  +   total_days).show();
                            $('#REV_btn_emp_pdf').show();
                            //TABLE HEADER
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
                            var emptitle=err_msg_array[6].toString().replace("[MONTH]",$("#REV_lb_loginid option:selected").text());
                            var startdate=emptitle.replace("[STARTDATE]",REV_start_datevalue);
                            var header=startdate.replace("[ENDDATE]",REV_end_datevalue);
                            $('#REV_lbl_emptitle').text(header).show();
                            $('#REV_btn_emplist_pdf').show();
                            var emptitle=err_msg_array[6].toString().replace("[MONTH]",$("#REV_lb_loginid option:selected").text());
                            var startdate=emptitle.replace("[STARTDATE]",REV_start_datevalue);
                            heading=startdate.replace("[ENDDATE]",REV_end_datevalue);
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
                                { "aTargets" : ["uk-date-column"] , "sType" : "uk_date"} ]
                        });
                    }
                    else
                    {
                        $('.preloader').hide();
                        var sd=err_msg_array[1].toString().replace("[SDATE]",REV_start_datevalue);
                        var msg=sd.toString().replace("[EDATE]",REV_end_datevalue);
                        $('#REV_nodata_startenddate').text(msg).show();
                        $('#REV_div_nonactve_dterange').hide();
                        $('#REV_lbl_ttlprjct').hide();
                        $('#REV_lbl_eachproject_empday').hide();
                        $('#REV_lbl_empday').hide();
                        $('#REV_btn_emp_pdf').hide();
                    }

                }
            }
            $('#REV_div_nonactve_dterange').show();
            var option="non_activeemp_dterange";
            xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_REVENUE.do?option="+option+"&REV_loginid="+REV_loginid+"&REV_start_datevalue="+REV_start_datevalue+"&REV_end_datevalue="+REV_end_datevalue+"&REV_prjctname="+REV_prjctname+"&REV_withproject="+seacrhby_prjct+"&rec_ver="+rec_ver);
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
//        alert('startdate')
//        alert(REV_start_datevalue)
            var REV_end_datevalue=$('#REV_tb_enddtebyrange').val()
//        alert('endate')
//        alert(REV_end_datevalue)
            var REV_projectname=$('#REV_lb_projectname').val();
//        alert('projectname')
//        alert(REV_projectname)
            var REV_project_recversion=$('#REV_lb_precver').val();
//        alert('recv')
//        alert(REV_project_recversion)
            $('.preloader').show();
            var xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var projectvalues=JSON.parse(xmlhttp.responseText);
//                alert(xmlhttp.responseText)
                    if(projectvalues)
                    {
                        $('.preloader').hide();
                        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                        if(REV_project_recver.length>1)
                        {
                            var replaceprjct=err_msg_array[6].toString().replace("[MONTH]",REV_projectname+' '+'VER'+ - +REV_project_recversion);
                        }
                        else
                        {
                            var replaceprjct=err_msg_array[6].toString().replace("[MONTH]",REV_projectname);
                        }
                        var sd=replaceprjct.replace("[STARTDATE]",REV_start_datevalue);
                        REV_lbl_dterangetitle=sd.replace("[ENDDATE]",REV_end_datevalue);
                        $('#REV_lbl_dterangetitledivid').html('').append('<div class="row-fluid form-group col-sm-2"><label id="REV_lbl_dterangetitle" name="REV_lbl_dterangetitle" class="srctitle"></label></div>')
                        $('#REV_lbl_dterangetitle').text(REV_lbl_dterangetitle).show();
                        $('#REV_lbl_dterangetitledivid').show();
                        var working_day= projectvalues[0].working_day;
                        var REV_total_hrs= projectvalues[0].REV_total_hrs;
                        $('#REV_lbl_totaldays_dterangedivid').html('').append('<div class="row-fluid form-group col-sm-2"><label id="REV_lbl_totaldays_dterange" name="REV_lbl_totaldays_dterange"  class="srctitle" hidden></label></div>')
                        $('#REV_lbl_totalhrs_dterangedivid').html('').append('<div class="row-fluid form-group col-sm-2"><label id="REV_lbl_totalhrs_dterange" name="REV_lbl_totalhrs_dterange"  class="srctitle" hidden></label></div>')
                        $('#REV_btn_totalhrs_pdfdivid').html('').append('<div class="row-fluid form-group col-sm-2"><input type="button" id="REV_btn_totalhrs_pdf" class="btnpdf" value="PDF"></div>')
                        $('#REV_div_projecttotal_dtebyrangedivid').html('').append('<div class="row-fluid form-group col-sm-2"><div id ="REV_div_projecttotal_dtebyrange" class="table-responsive row-fluid form-group col-sm-2" style="max-width:700px"><sectionprbydtrange></sectionprbydtrange></div></div>')
                        $('#REV_lbl_totaldays_dterange').text("TOTAL NO OF  DAYS: "  +   working_day).show();
                        $('#REV_lbl_totalhrs_dterange').text("TOTAL NO OF HRS: "  +   REV_total_hrs).show();
                        $('#REV_btn_totalhrs_pdf').show();
                        $('#REV_lbl_totaldays_dterangedivid').show();
                        $('#REV_lbl_totalhrs_dterangedivid').show();
                        $('#REV_btn_totalhrs_pdfdivid').show();
                        var REV_table_header='<table width="700px" id="REV_tble_projctrevenue_bydaterange" border="1"  cellspacing="0" class="srcresult" ><thead  bgcolor="#6495ed" style="color:white"><tr><th width="200px">EMPLOYEE NAME</th><th>NUMBER OF DAYS</th><th>NUMBER OF HRS</th><th>NUMBER OF MINUTES</th></tr></thead><tbody>'
                        for(var i=0;i<projectvalues.length;i++){
                            var username=projectvalues[i].username;
                            var noofdays=projectvalues[i].noofdays;
                            var total_hrs=projectvalues[i].total_hrs;
                            var total_minutes=projectvalues[i].total_minutes;
                            REV_table_header+='<tr><td>'+username+'</td><td align="center">'+noofdays+'</td><td align="center">'+total_hrs+'</td><td align="center">'+total_minutes+'</td></tr>';
                        }
                        REV_table_header+='</tbody></table>';
//                    alert(REV_table_header)
                        $('sectionprbydtrange').html(REV_table_header);
                        $('#REV_tble_projctrevenue_bydaterange').DataTable({
                        });
                    }
                    else
                    {
                        $('.preloader').hide();
                        var sd=err_msg_array[1].toString().replace("[SDATE]",REV_start_datevalue);
                        var msg=sd.toString().replace("[EDATE]",REV_end_datevalue);
                        $('#REV_nodata_staenddate').text(msg).show();
                        $('#REV_div_projecttotal_dtebyrange').hide();
                        $('#REV_lbl_ttlprjct').hide();
                        $('#REV_lbl_empday').hide();
                        $('#REV_btn_emp_pdf').hide();
                        $('#REV_lbl_eachproject_empday').hide();
                    }

                }
            }
            $('#REV_div_projecttotal_dtebyrange').show();
            $('#REV_div_projecttotal_dtebyrangedivid').show();
            var option="projectname_dtebyrange";
            xmlhttp.open("GET","ADMIN/EMPLOYEE/DB_REPORT_REVENUE.do?option="+option+"&REV_projectname="+REV_projectname+"&REV_start_datevalue="+REV_start_datevalue+"&REV_end_datevalue="+REV_end_datevalue+"&REV_project_recver="+REV_project_recversion);
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

        //CLICK EVENT FOR PDF BUTTON
        $(document).on('click','#REV_btn_pdf',function(){
            var inputValOne=$("#REV_lb_projectname").val();
            var inputValTwo=$('#REV_lb_precver').val();
            var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=5&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&title='+title;
        });
        $(document).on('click','#REV_btn_emp_pdf',function(){
            if($("input[id=REV_rd_withproject]:checked").val()=="project"){
                var inputValOne=$('#REV_lb_loginid').val();
                var inputValTwo=$('#REV_lb_empproject').val();
                var inputValThree=$('#REV_lb_recver').val();
                var pdfoption=$('#REV_lb_project').val();
                if(pdfoption==9)
                {
                    var inputValFour=$('#REV_tb_strtdte').val();
                    inputValFour = inputValFour.split("-").reverse().join("-");
                    var inputValFive=$('#REV_tb_enddte').val();
                    inputValFive = inputValFive.split("-").reverse().join("-");
                }
                else
                {
                    var inputValFour='';
                    var inputValFive='';
                }
                if($('#REV_lb_project').val()==8){
                    var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=6&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&inputValThree='+inputValThree+'&title='+employeetitles;
                }
                else if($('#REV_lb_project').val()==9){
                    var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=8&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&inputValThree='+inputValThree+'&inputValFour='+inputValFour+'&inputValFive='+inputValFive+'&title='+heading;
                }
            }
        });
        $(document).on('click','#REV_btn_emplist_pdf',function(){
            if($("input[id=REV_rd_withoutproject]:checked").val()=="withoutproject"){
                var inputValOne=$('#REV_lb_loginid').val();
                var inputValFour=$('#REV_tb_strtdte').val();
                inputValFour = inputValFour.split("-").reverse().join("-");
                var inputValFive=$('#REV_tb_enddte').val();
                inputValFive = inputValFive.split("-").reverse().join("-");
                if($('#REV_lb_project').val()==8){
                    var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=7&inputValOne='+inputValOne+'&title='+employeetitles;
                }
                else if($('#REV_lb_project').val()==9){
                    var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=9&inputValOne='+inputValOne+'&inputValFour='+inputValFour+'&inputValFive='+inputValFive+'&title='+heading;
                }
            }
        });
        $(document).on('click','#REV_btn_totalhrs_pdf',function(){
            var inputValOne=$("#REV_lb_projectname").val();
            var inputValTwo=$('#REV_lb_precver').val();
            var inputValThree=$('#REV_tb_strtdtebyrange').val();
            inputValThree = inputValThree.split("-").reverse().join("-");
            var inputValFour=$('#REV_tb_enddtebyrange').val();
            inputValFour = inputValFour.split("-").reverse().join("-");
            var url=document.location.href='TSLIB/TSLIB_COMMON_PDF.do?flag=10&inputValOne='+inputValOne+'&inputValTwo='+inputValTwo+'&inputValThree='+inputValThree+'&inputValFour='+inputValFour+'&title='+REV_lbl_dterangetitle;
        });
    });
    //DOCUMENT READY FUNCTION END
</script>
<!--SCRIPT TAG END-->
<!--BODY TAG START-->
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/> </div>
    <div class="title text-center"><h4><b>REVENUE</b></h4></div>
    <form   id="REV_form_revenue" class="content form-horizontal" role="form" >
        <div class="panel-body">
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2" name="REV_lbl_prjct" id="REV_lbl_prjct">PROJECT REVENUE<em>*</em></label>
                    <div class="col-sm-4">
                        <select id="REV_lb_project" class="form-control" style="display: inline;" name="REV_lb_project" hidden>
                        </select>
                    </div>
                </div>
                <div id="project_act_lbl"></div>
                <div id="project_nonact_lbl"></div>
                <div id="project_act"></div>
                <div id="project_nonact"></div>

                <div id="REV_nodata_rcdivid"><label id="REV_nodata_rc" name="REV_nodata_rc" class="errormsg"></label></div>

                <div  id="REV_tble_prjctrevenue"  hidden>
                    <div class="form-group">
                        <label class="col-sm-2" name="REV_lbl_prjctnme" id="REV_lbl_prjctnme">PROJECT NAME<em>*</em></label>
                        <div class="col-sm-4">
                            <select id="REV_lb_projectname" class="form-control" style="display: inline" name="REV_lb_projectname" hidden>
                            </select>
                        </div>
                    </div></div>

                <!--      <div class="row-fluid form-group"> <label class="col-sm-2"name="REV_lbl_precver" id="REV_lbl_precver" hidden>RECORD VERSION<em>*</em></label><div class="col-sm-4"> <select id="REV_lb_precver" name="REV_lb_precver" hidden> </select></div></div>-->
                <div id="REV_lbl_precverdivid"></div>

                <!--                <div><label id="REV_nodata_pd" class="col-sm-2" name="REV_nodata_pd" class="errormsg"></label></div>-->
                <div id="REV_tble_startdate"></div>
                <div id="REV_tble_searchbtn"></div>
                <!--                <div class="row-fluid form-group" id="startenddatebydterangeoption" hidden>-->
                <!--                    <label class="col-sm-2" id="REV_lbl_strtdtebyrange" >START DATE<em>*</em></label>-->
                <div class="col-sm-4"><input type="text"  id="REV_tb_strtdtebyrange1" class="validsrchbtn clear REV_datepickeroption datemandtry" style="width:75px;" hidden></div>
                <!--        </div>-->
                <!---->
                <!--                <div class="row-fluid form-group" id="endstartdatebydterangeoption" hidden>-->
                <!--                    <label class="col-sm-2"  id="REV_lbl_enddtebyrange1" hidden >END DATE<em>*</em></label>-->
                <!--                    <div class="col-sm-4">-->
                <!--                        <input type="text" id="REV_tb_enddtebyrange1" class="validsrchbtn clear REV_datepickeroption datemandtry" style="width:75px;" hidden>-->
                <!--                    </div></div>-->
                <!---->
                <input type="button" class="btn" id="REV_btn_searchdaterange"  value="SEARCH" disabled hidden>


                <div id="REV_err_msg_date_project" class="col-sm-2" class="errormsg"></div>
                <!---->
                <!--                <div class="row-fluid form-group col-sm-2"><input type="button" id="REV_btn_prjctsrch"  name="REV_btn_prjctsrch" value="SEARCH" class="btn" disabled/></div>-->
                <div id="REV_btn_prjctsrchdivid"></div>
                <!---->

                <!--                <div class="row-fluid form-group col-sm-2"><label id="REV_nodata_pdflextble" name="REV_nodata_pdflextble" class="errormsg"></label></div>-->
                <!---->
                <!--              <div class="row-fluid form-group col-sm-2" ><label id="REV_lbl_title" name="REV_lbl_title"  class="srctitle" hidden></label></div>-->
                <div id="REV_lbl_titledivid"></div>
                <!---->
                <!--              <div class="row-fluid form-group col-sm-2" ><label id="REV_lbl_totaldays" name="REV_lbl_totaldays"  class="srctitle" hidden></label></div>-->
                <div id="REV_lbl_totaldaysdivid"></div>
                <!---->
                <!--               <div class="row-fluid form-group col-sm-2" ><label id="REV_lbl_totalhrs" name="REV_lbl_totalhrs"  class="srctitle" hidden></label></div>-->
                <div id="REV_lbl_totalhrsdivid"></div>
                <!---->
                <!--               <div class="row-fluid form-group col-sm-2" ><input type="button" id="REV_btn_pdf" class="btnpdf" value="PDF"></div>-->
                <div id="REV_btn_pdfdivid"></div>
                <!--             <div id ="REV_div_projecttotal" style="max-width:500px"><section></section></div>-->
                <div id="REV_div_projecttotaldivid"></div>
                <!--                    <section></section>-->
                <!--                </div>-->
                <!--                    </div></div>-->
                <!---->
                <div class="form-group col-sm-2"><label id="REV_nodata_staenddate" name="REV_nodata_staenddate" class="errormsg"></label></div>
                <!---->
                <!--             <div class="row-fluid form-group col-sm-2"><label id="REV_lbl_dterangetitle" name="REV_lbl_dterangetitle" class="srctitle"></label></div>-->
                <div id="REV_lbl_dterangetitledivid"></div>

                <!--             <div class="row-fluid form-group col-sm-2"><label id="REV_lbl_totaldays_dterange" name="REV_lbl_totaldays_dterange"  class="srctitle" hidden></label></div>-->
                <div id="REV_lbl_totaldays_dterangedivid"></div>
                <!---->
                <!--             <div class="row-fluid form-group col-sm-2"><label id="REV_lbl_totalhrs_dterange" name="REV_lbl_totalhrs_dterange"  class="srctitle" hidden></label></div>-->
                <div id="REV_lbl_totalhrs_dterangedivid"></div>
                <!---->
                <!--                <div class="row-fluid form-group col-sm-2"><input type="button" id="REV_btn_totalhrs_pdf" class="btnpdf" value="PDF"></div>-->
                <div id="REV_btn_totalhrs_pdfdivid"></div>

                <!--                <div class="row-fluid form-group col-sm-2"><div id ="REV_div_projecttotal_dtebyrange" class="table-responsive row-fluid form-group col-sm-2" style="max-width:700px"hidden><sectionprbydtrange></sectionprbydtrange></div></div>-->
                <div id="REV_div_projecttotal_dtebyrangedivid"></div>

                <div id="REV_tble_prjctrevactnonact" hidden></div>

                <!--                <div class="row-fluid form-group">-->
                <!--                    <label name="REV_lbl_actveemp" class="form-inline col-sm-3" id="REV_lbl_actveemp" hidden>-->
                <!--                        <div class="radio">-->
                <!--                            <input type="radio" name="REV_rd_veemp"  id="REV_rd_actveemp" value="EMPLOYEE" hidden>&nbsp;ACTIVE EMPLOYEE</label>-->
                <!--                </div>-->
                <!--        </div>-->
                <div id="REV_lbl_actveempsdivid"></div>
                <div id="REV_rd_veempactdivid"></div>
                <div id="REV_lbl_nonactveempsdivid"></div>
                <div id="REV_rd_veempnonactdivid"></div>
                <!--        <div class="row-fluid form-group">-->
                <!--         <label name="REV_lbl_nonactveemp" class="form-inline col-sm-3" id="REV_lbl_nonactveemp"  hidden>-->
                <!--         <div class="radio"> <input type="radio" name="REV_rd_veemp" id="REV_rd_nonactveemp"  value="EMPLOYEE" class='attnd' hidden>&nbsp;NON ACTIVE EMPLOYEE </label> </div></div>-->


                <!--<div class="row-fluid form-group"><label name="REV_lbl_actveemps" id="REV_lbl_actveemps" class="col-sm-3 srctitle" hidden>ACTIVE EMPLOYEE</label></div>-->

                <!---->
                <!--<div class="row-fluid form-group"> <label name="REV_lbl_nonactveemps" id="REV_lbl_nonactveemps" class="srctitle col-sm-3" hidden>NON ACTIVE EMPLOYEE </label></div>-->
                <!---->
                <!--<div><label id="REV_nodata_uld" name="REV_nodata_uld" class="errormsg"></label></div>-->

                <!--<div class="row-fluid form-group"> <label class="col-sm-2" name="REV_lbl_loginid" id="REV_lbl_loginid"  hidden>LOGIN ID<em>*</em></label><div class="col-sm-4"> <select name="REV_lb_loginid" id="REV_lb_loginid" class="form-control" style="display: none"> </select></div></div>-->
                <div id="REV_lbl_revenulistlbl"></div>
                <div id="REV_lbl_projectlistlbl"></div>
                <div id="REV_lbl_revenulist"></div>
                <div id="REV_lbl_projectlist"></div>

                <!--<div class="row-fluid form-group"><label name="REV_lbl_withproject"class="form-inline col-sm-3"  id="REV_lbl_withproject"  hidden><div class="radio"><input type="radio" name="REV_rd_project" id="REV_rd_withproject"  value="project" hidden>&nbsp;LIST REVENUE BY PROJECT</label></div></div>-->
                <!---->
                <!--<div class="row-fluid form-group"><label name="REV_lbl_withoutproject"class="form-inline col-sm-3"  id="REV_lbl_withoutproject"  hidden> <div class="radio"> <input type="radio" name="REV_rd_project" id="REV_rd_withoutproject"   value="withoutproject" hidden>&nbsp;LIST OF PROJECT REVENUE</label></div></div>-->
                <!---->
                <!--<div class="row-fluid form-group"><label class="col-sm-2" name="REV_lbl_empproject" id="REV_lbl_empproject" hidden>PROJECT<em>*</em></label> <div class="col-sm-4"> <select id="REV_lb_empproject" name="REV_lb_empproject" class="form-control" style="display: none"> </select></div></div>-->
                <!---->
                <div id="REV_lbl_empprojectdivid"></div>
                <!--                <div class="row-fluid form-group"><label class="col-sm-3" name="REV_lbl_recver" id="REV_lbl_recver" hidden>RECORD VERSION<em>*</em></label><div class="col-sm-4"><select id="REV_lb_recver" name="REV_lb_recver" hidden></select></div></div>-->
                <div id="REV_lb_recverdivid"></div>
                <!---->
                <!--                <div class="row-fluid form-group col-sm-2"><input type="button" id="REV_btn_empsrch" name="REV_btn_empsrch" value="SEARCH" class="btn" disabled/></div>-->
                <div id="REV_btn_empsrchdivid"></div>
                <!---->
                <div  class="row-fluid form-group col-sm-2"><label id="REV_nodata_loginid" name="REV_nodata_loginid" class="errormsg"></label></div>
                <!---->
                <!--                <div class="row-fluid form-group"><label class="col-sm-2" name="REV_lbl_strtdte" id="REV_lbl_strtdte" hidden>START DATE<em>*</em></label> <div class="col-sm-4"><input type="text" name="REV_tb_strtdte" id="REV_tb_strtdte" class=" valid clear REV_datepicker datemandtry" style="width:75px;" hidden></div></div>-->
                <div id="REV_tb_strtdtedivid"></div>

                <!---->
                <!--                <div class="row-fluid form-group"><label class="col-sm-2"  name="REV_lbl_enddte" id="REV_lbl_enddte" hidden >END DATE<em>*</em></label><div class="col-sm-4"><input type="text" name="REV_tb_enddte" id="REV_tb_enddte" class=" valid clear REV_datepicker datemandtry" style="width:75px;" hidden></div></div>-->
                <div id="REV_tb_enddtedivid"></div>
                <!---->
                <!--                <div  class="row-fluid form-group col-sm-2" ><input type="button" class="btn" name="REV_btn_search" id="REV_btn_search"  value="SEARCH" disabled></div>-->
                <div id="REV_btn_searchdivid"></div>
                <!---->
                <div id="REV_err_msg_date" class="errormsg row-fluid form-group col-sm-12"></div>
                <!---->
                <div class="form-group col-sm-12"><label id="REV_nodata_startenddate" name="REV_nodata_startenddate" class="errormsg"></label></div>
                <!---->
                <div class="form-group col-sm-12"><label id="REV_lbl_emptitle" name="REV_lbl_emptitle"  class="srctitle" hidden></label></div>
                <!--                <div id="REV_lbl_emptitledivid"></div>-->
                <!---->
                <div>
                    <label id="REV_lbl_ttlprjct" name="REV_lbl_ttlprjct"  class="srctitle" hidden></label>
                </div>
                <!---->
                <div class="form-group col-sm-12">
                    <label id="REV_lbl_empday" name="REV_lbl_empday"  class="srctitle" hidden></label>
                </div>
                <!---->
                <div class="form-group col-sm-12"><input type="button" id="REV_btn_emp_pdf" class="btnpdf" value="PDF">
                    <!--    <div class="row-fluid form-group col-sm-2"><input type="button" id="REV_btn_emplist_pdf" class="btnpdf" value="PDF"></div>-->
                    <!--    <div></div>-->
                    <div class="form-group"><label id="REV_lbl_eachproject_empday" name="REV_lbl_eachproject_empday"  class="srctitle" hidden></label>
                        <!--    </div></div>-->

                        <div  id ="REV_div_loginid" class="table-responsive col-sm-12" style="max-width:550px;padding-right: 8px">
                            <sections>
                            </sections>
                        </div>

                        <div id ="REV_div_nonactve_dterange" class="table-responsive col-sm-12" style="max-width:550px;padding-right: 8px">
                            <sectionrnge>
                            </sectionrnge>
                        </div>

            </fieldset>
        </div>
    </form>
</div>
</body>
<!--BODY TAG END-->
</html>
<!--HTML TAG END-->

