
<?php
include "../../TSLIB/TSLIB_HEADER.php";
//include  "NEW_MENU.php";
?>
<html>
<head>
    <style>
        a.classAddButton ,a.classAddButton1,a.classMinusButton1 ,a.classMinusButton:hover,a.classAddButtonNWItems:hover
        {
            cursor: hand; cursor: pointer;
        }
        div.details-control{
            background: url('images/details_open.png')no-repeat center center;
            cursor: pointer;
        }
        .row
        {
            background: rgba(239, 239, 239, 0.47);
            line-height:28pt;
            border-top: 1px solid lightgray;
        }
        .row:nth-of-type(odd)
        {
            background: rgba(221, 221, 221, 0.8);;
        }
        .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;!important;
            padding-top:8px;
        }
        .form-group
        {
            margin-bottom: 0px;
        }
        .row1
        {
            margin-right: -15px;
            margin-left: -15px;
        }
        .numbersonly
        {
            width:80px;
        }
    </style>
    <script>
        $(document).ready(function()
        {
            var  errmsg;
            $(".preloader").hide();
//            alert("ready fun");
            var tablerowCount;
            var rowcount;
            var ifcondition;

            $("#temptextbox").hide;

            $(document).on("keyup",'.earningamt', function (){
                if (this.value != this.value.replace(/[^0-9\.]/g, ''))
                {
                    this.value = this.value.replace(/[^0-9\.]/g, '');
                }
            });
            $(document).on("keyup",'.deductionamt', function (){
                if (this.value != this.value.replace(/[^0-9\.]/g, ''))
                {
                    this.value = this.value.replace(/[^0-9\.]/g, '');
                }
            });

            $(".earninglbl").keypress(function (e)
            {
                var regex = new RegExp("^[A-Z]+$");
                var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
                if (regex.test(str))
                {
                    return true;
                }
                e.preventDefault();
                return false;
            });
            $('#PS_tb_FROM_DATE').datepicker(
                {
                    dateFormat:"dd-mm-yy",
                    changeYear: true,
                    changeMonth: true
                });
            $('#PS_tb_TO_DATE').datepicker(
                {
                    dateFormat:"dd-mm-yy",
                    changeYear: true,
                    changeMonth: true
                });
            $('#PS_tb_PAYMENT_DATE').datepicker(
                {
                    dateFormat:"dd-mm-yy",
                    changeYear: true,
                    changeMonth: true
                });
            var option="initial_data";
            $.ajax({
                type: "POST",
                url: "ADMIN/EMPLOYEE/DB_PAYROLL.do",
                data:'&Option='+option,
                success: function (data)
                {
                    var arrayvalues=JSON.parse(data);
                    errmsg=arrayvalues[1];
                    var activeemployee=arrayvalues[0];
                    var paymentmodearr=arrayvalues[2];
                    var employeename='<option>SELECT</option>';
                    var paymentmode='<option>SELECT</option>';

                    for (var i=0;i<activeemployee.length;i++)
                    {
                        employeename +='<option value="' + activeemployee[i] + '">' + activeemployee[i] + '</option>';
                    }
                    $('#ps_emp_name').html(employeename);

                    for (var i=0;i<paymentmodearr.length;i++)
                    {
                        paymentmode +='<option value="' + paymentmodearr[i] + '">' + paymentmodearr[i] + '</option>';
                    }
                    $('#payment_mode_lb').html(paymentmode);

                    var earninglabelsarr =arrayvalues[3];
                    var deductionlabelsarr=arrayvalues[4];

                    var other_recoverlabelarr=arrayvalues[5];
//              alert(other_recoverlabelarr);
                    var arrcount;
                    var earningcount=earninglabelsarr.length;
                    var deductioncount=deductionlabelsarr.length;

                    var otherrecovercount=other_recoverlabelarr.length;

//                 alert(otherrecovercount)

                    if (earningcount>deductioncount)
                    {
                        arrcount=earningcount;
                    }
                    else
                    {
                        arrcount=deductioncount;
                    }
//                if (earningcount>otherrecovercount)
//                {
//                    arrcount=otherrecovercount;
//                }
//                var tab='<div><table border="0"><tr><div class="col-sm-2"><label></label></div><td> '
                    var earninglabels='<div><table border="0" >';

                    for (var i=0;i<arrcount;i++)
                    {
                        earninglabels+='<tr>';
                        earninglabels+='<td nowrap><div class="col-sm-2"><label name="earninglbl[]" class="earninglbl">'+earninglabelsarr[i]+'</label></div></td>';
                        earninglabels+='<td><div class="col-sm-2"><input type="text" name="earningamt[]" class="earningamt" id="'+earninglabelsarr[i]+'"style="width:100px"/></div></td>';
                        if(deductionlabelsarr[i]!=undefined && deductionlabelsarr[i]!='')
                        {
                            earninglabels+='<td nowrap><div class="col-sm-2"><label name="deduction[]" class="deduction">'+deductionlabelsarr[i]+'</label></div></td>';
                            earninglabels+='<td><div class="col-sm-6"><input type="text" name="deductionamt[]" class="deductionamt" id="'+deductionlabelsarr[i]+'"style="width:100px"/></div></td>';
                        }
                        if(other_recoverlabelarr[i]!=undefined && other_recoverlabelarr[i]!='')
                        {
                            earninglabels+='<td nowrap<div class="col-sm-2"><label name="other_recover[]" class="other_recover">'+other_recoverlabelarr[i]+'</label></div></td>';
                            earninglabels+='<td><div class="col-lg-2"><input type="text" name="other_recoveramt[]" class="other_recoveramt" id="'+other_recoverlabelarr[i]+'"style="width:100px"/></div></td>';
                        }

                        else
                        {
                            earninglabels+='<td><div class="col-sm-2"><label hidden></label></div></td>';
                            earninglabels+='<td><div class="col-sm-6"><label hidden></label></div></td>';
                        }
                        earninglabels+='</tr>';
                    }

                    earninglabels+='</table></div>';
                    $('#earninglables').append(earninglabels).show;
                    $('#earninglables').html(earninglabels);


                },
                error: function (data)
                {
                    alert('error in getting' + JSON.stringify(data));
                }
            });

// FUNTION FOR SUBMIT BUTTON CLICK FOR INSERT AND CALUCULATE AMT
            $(document).on('click','#PS_bnt_payslip_Submit',function()
            {
//                    alert('in');
                var Option="earningdedution";
                var Earrleng=[];
                $('.earningamt').each(function()
                {
                    var vals=$(this).attr('id');
                    if ($('#'+vals).val()!='')
                    {
                        var textvals=$(this).attr('id');
//                            textvals=textvals+"^";
                        Earrleng.push(textvals);
                    }
                });
//                alert(Earrleng);

                var Darrleng=[];
                $('.deductionamt').each(function()
                {
                    var vals=$(this).attr('id');
                    if ($('#'+vals).val()!='')
                    {
                        var textvals=$(this).attr('id');
                        Darrleng.push(textvals);
                    }
                });

                var otharrleng=[];
                $('.other_recoveramt').each(function()
                {
                    var vals=$(this).attr('id');
                    if ($('#'+vals).val()!='')
                    {
                        var textvals=$(this).attr('id');
                        otharrleng.push(textvals);
                    }
                });
                $.ajax({
                    type:"POST",
                    url: "ADMIN/EMPLOYEE/DB_PAYROLL.do",
                    data:$("#PAY_SLIP").serialize()+'&Option='+ Option+'&Earrleng='+Earrleng+'&Darrleng='+Darrleng+'&otharrleng='+otharrleng,
                    success: function (data)
                    {
                        var values=JSON.parse(data);
                        if (values==1)
                        {
//                              show_msgbox("EMPLOYEE PAYROLL",errmsg[2],"success",false);
                        }
                        else
                        {
//                              show_msgbox("EMPLOYEE PAYROLL",errmsg[4],"success",false);
                        }
                    }
                });
            });
            $(document).on('click','#PS_pdf',function()
            {
                var fromdate=$('#PS_tb_FROM_DATE').val();
                var todate=$('#PS_tb_TO_DATE').val();
                var EMPNAME=$('#ps_emp_name').val();
                var formElement = document.getElementById("#PAY_SLIP");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                        $('.preloader').hide();
                        var values_array=JSON.parse(xmlhttp.responseText);
                    }
                }
                var option="pdf";
                var url=document.location.href="TSLIB/TSLIB_EMP_PAYROLL_PDF.do?option="+option;
//                    +"&fromdate="+fromdate+"&todate="+todate+"&empname="+EMPNAME);
                xmlhttp.send();
            });
        });
    </script>
</head>
<body>
<div class="container">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/></div>
    <div class="title text-center"><h4><b>EMPLOYEE PAY SLIP</b></h4></div>
    <form id="PAY_SLIP" class="content form-horizontal" role="form">
        <div class="panel-body">
            <div class="row-fluid form-group">
                <label class="col-sm-2" name="emp_active_lbl" id="emp_active_lbl">EMPLOYEE NAME<em>*</em></label>
                <div class="col-sm-4">
                    <select id="ps_emp_name" class="form-control" style="display: inline" name="ps_emp_name">
                    </select>
                </div>
            </div>
            <div class="row-fluid form-group">
                <label class="col-sm-2" name="PS_lbl_FROM_DATE" id="PS_lbl_FROM_DATE">FROM DATE:<em>*</em></label>
                <div class="col-sm-4">
                    <input type='text' class="form-control" id="PS_tb_FROM_DATE" name="PS_tb_FROM_DATE" style="width: 100px"/>
                </div>
            </div>
            <div id="temp" hidden>
                <input type='text' class="form-control" id="temptextbox" name="temptextbox" style="width: 100px">
                <input type='text' class="form-control" id="temptextbox1" name="temptextbox1" style="width: 100px">
            </div>
            <div class="row-fluid form-group">
                <label class="col-sm-2" name="PS_lbl_TO_DATE" id="PS_lbl_TO_DATE"> TO DATE:<em>*</em></label>
                <div class="col-sm-4">
                    <input type='text' class="form-control" id="PS_tb_TO_DATE" name="PS_tb_TO_DATE" style="width: 100px"/>
                </div>
            </div>
            <div class="row-fluid form-group">
                <label class="col-sm-2" name="PS_lbl_PAYMENT_DATE" id="PS_lbl_PAYMENT_DATE">PAYMENT DATE:<em>*</em></label>
                <div class="col-sm-4">
                    <input type='text' class="form-control" id="PS_tb_PAYMENT_DATE" name="PS_tb_PAYMENT_DATE" style="width: 100px"/>
                </div>
            </div>
            <div class="row-fluid form-group">
                <label class="col-sm-2" name="ps_payment_lbl" id="ps_payment_lbl">PAYMENT MODE<em>*</em></label>
                <div class="col-sm-4">
                    <select id="payment_mode_lb" class="form-control" style="display: inline" name="payment_mode_lb">
                    </select>
                </div>
            </div>

            <div class="row-fluid form-group">
                <label class="col-sm-2" name="PS_lbl_COMMENTS" id="PS_lbl_COMMENTS">COMMENTS:<em>*</em></label>
                <div class="col-sm-4">
                    <textarea cols="30" class="form-control" id="PS_tb_COMMENTS" name="PS_tb_COMMENTS" rows="2"></textarea>
                </div>
            </div>
            <div class="row-fluid form-group">
                <!--        <label class="col-sm-2">EARNING</label><label id="PS_DEDUTION_lbl_amt" class="col-sm-6">AMOUNT</label>-->
                <div id="earninglables">
                </div>
            </div>
            <div class="row-fluid form-group">
                <div id="deductionlables">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-offset-2 col-lg-3">
                    <input type="button" id="PS_bnt_payslip_Submit" class="btn" value="SUBMIT">
                </div>
            </div>
        </div>
    </form>
</div>
</body>
</html>
