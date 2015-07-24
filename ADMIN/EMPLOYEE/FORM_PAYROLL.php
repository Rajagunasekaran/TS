
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
                data:'&Option='+ option,
                success: function (data)
                {
//               alert(data);

                    var arrayvalues=JSON.parse(data);
                    errmsg=arrayvalues[1];
//                alert(errmsg);
                    var activeemployee=arrayvalues[0];
                    var paymentmodearr=arrayvalues[2];

                    var employeename='<option>SELECT</option>';
                    var paymentmode='<option>SELECT</option>';

                    for (var i=0;i<activeemployee.length;i++)
                    {
                        employeename += '<option value="' + activeemployee[i] + '">' + activeemployee[i] + '</option>';
                    }
                    $('#ps_emp_name').html(employeename);

                    for (var i=0;i<paymentmodearr.length;i++)
                    {
                        paymentmode += '<option value="' + paymentmodearr[i] + '">' + paymentmodearr[i] + '</option>';
                    }

                    $('#payment_mode_lb').html(paymentmode);

                    var earninglabelsarr =arrayvalues[3];
                    var deductionlabelsarr=arrayvalues[4];

//                var earninglabels='';
//               for (var i=0;i<earninglabelsarr.length;i++)
//                {
//                    earninglabels+='<label name="earninglbl[]" class="col-sm-2 earninglbl">'+earninglabelsarr[i]+'</label>';
//                    earninglabels+='<div class="col-sm-10"> ';
//                    earninglabels+='<input type="text" name="earningamt[]" class="form-control" id="'+earninglabelsarr[i]+'" style="width:100px"/></div>';
//                }
//                earninglabels+='</div>';
//
//                $('#earninglables').append(earninglabels).show;
//                $('#earninglables').html(earninglabels);

//               var deductionlabel='<div>';
//                for (var i=0;i<deductionlabelsarr.length;i++)
//                {
//                    deductionlabel+='<label class="col-sm-2">'+deductionlabelsarr[i]+'</label>';
//                    deductionlabel+='<div class="col-lg-10"> ';
//                    deductionlabel+='<input type="text" class="form-control" id="'+deductionlabelsarr[i]+'" style="width:100px"/></div>';
////                  deductionlabel+='</div>';
//                }
//                deductionlabel+='</div>';
//
//                $('#deductionlables').append(deductionlabel).show;
//                $('#deductionlables').html(deductionlabel);
//            },
//            error: function (data)
//            {
//                alert('error in getting' + JSON.stringify(data));
//            }
                }
            });
            $(document).on("click", '.classMinusButton', function()
            {
                var id= $(this).attr('id');
                var id=id.split('_');
                ifcondition=id[1];
                $('#lblEARNING_'+ifcondition).hide();
                $('#EARNING_'+ifcondition).hide();
                $('#btnEARNING_'+ifcondition).hide();
            });
            $(document).on("click",'.classMinusButton1',function()
            {
                var id= $(this).attr('id');
//                alert(id);
                var id=id.split('_');
                ifcondition=id[1];
//                alert(ifcondition);

                $('#lblDEDUCTION_'+ifcondition).hide();
                $('#DEDUCTION_'+ifcondition).hide();
                $('#btnDEDUCTION_'+ifcondition).hide();
//                $(this).find('img').attr('src','images/details_open.png');
//                $(this).addClass('classAddButton').removeClass('classMinusButton');
            });
            $(document).on("click",'.classAddButton', function ()
            {
                $("#earning_btn").show();
                $("#earninglbl").show();
                $("#EARNING").show();
                $("#close").show();
                var tablerowCount = $('#EARNING > div').length;
                if(tablerowCount==0)
                {
                    row_count=parseInt(tablerowCount)+1;
                    var uploadfileid="EARNING_"+row_count;
                    $('#temptextbox').val(row_count);
                }
                else
                {
                    var rowvalue=$('#temptextbox').val();
                    rowcount=parseInt(rowvalue)+1;
                    uploadfileid="EARNING_"+rowcount;
                    $('#temptextbox').val(rowcount);
                }

                $('<div class="form-group"><div class="col-sm-offset-2 col-sm-2"><input type="text" class="earninglbl" name="earninglbl[]"style="width: 100px" id="lbl'+uploadfileid+'"></div><div class="col-sm-2"><input type="text"  class="earningamt" name= "earningamt[]" id="'+uploadfileid+'"style="width: 100px"></div><div class="col-sm-1"><a class="classMinusButton classDivMultiRows" id="btn'+uploadfileid+'"><img src="IMAGES/details_close.png" class=" img-responsive"></a></div></div>').appendTo($("#EARNING"));
//                $("#classAddButton").attr(disabled);

            });

            $(document).on("click",'.classAddButton1', function ()
            {
                $("#deduction_btn").show();
                $("#deductionlbl").show();
                $("#DEDUCTION").show();
                $("#close").show();
                tablerowCount = $('#DEDUCTION > div').length;

                if(tablerowCount==0)
                {
                    var row_count=parseInt(tablerowCount)+1;
                    var uploadfileid1="DEDUCTION_"+row_count;
                    $('#temptextbox1').val(row_count);
                }
                else
                {
                    var rowvalue=$('#temptextbox1').val();
                    var rowcount=parseInt(rowvalue)+1;
                    uploadfileid1="DEDUCTION_"+rowcount;
                    $('#temptextbox1').val(rowcount);
                }
                $('<div class="form-group"><div class="col-sm-offset-2 col-sm-2"><input type="text" name="deductionlbl[]" style="width: 100px" id="lbl'+uploadfileid1+'"></div><div class="col-sm-2"><input type="text"  class="deductionamt" name="deductionamt[]" id="'+uploadfileid1+'"style="width: 100px"></div><div class="col-sm-1"><a class="classMinusButton1 classDivMultiRows" id="btn'+uploadfileid1+'"><img src="IMAGES/details_close.png" class=" img-responsive"></a></div></div>').appendTo($("#DEDUCTION"));

//                    $(this).find('img').attr('src','images/details_close.png');
//                    $(this).addClass('classMinusButton').removeClass('classAddButton');
            });
            //FUNTION FOR SUBMIT BUTTON CLICK FOR INSERT AND CALUCULATE AMT
            $(document).on('click','#PS_bnt_payslip_Submit',function()
            {
                alert('in');
                var Option="earningdedution";
                $.ajax({
                    type:"POST",
                    url: "ADMIN/EMPLOYEE/DB_PAYROLL.do",
                    data:$("#PAY_SLIP").serialize()+'&Option='+ Option,
                    success: function (data)
                    {
                        alert(data);
//                            alert('value inserted');
                        var values=JSON.parse(data);
                        alert(values);
                        if(values==1)
                        {
//                           show_msgbox("EMPLOYEE PAYROLL",errmsg[2],"success",false);
                            alert('value inserted');
                        }
                        else
                        {
//                            show_msgbox("EMPLOYEE PAYROLL",errmsg[4],"success",false);
                            alert(' value not inserted');
                        }
                    }
                });
            });

            $(document).on('click','#PS_pdf',function()
            {
//                alert('IN');
//                var fromdate=$('#PS_tb_FROM_DATE').val();
//                var todate=$('#PS_tb_TO_DATE').val();
//                var EMPNAME=$('#ps_emp_name').val();
////
//////                alert(EMPNAME);
//////                alert($enddate);
////                alert(fromdate);

                var formElement = document.getElementById("#PAY_SLIP");
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                        $('.preloader').hide();
//                        alert(xmlhttp.responseText);
////                        alert(xmlhttp.responseText);
//                        var values_array=JSON.parse(xmlhttp.responseText);

                    }
                }
                var option="pdf";
                var url=document.location.href="ADMIN/EMPLOYEE/DB_PAYROLL_PDF.do?option="+option;
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
                <!--                <label id="DDC_startdate" class="col-sm-offset-2 col-sm-2">EARNING</label><label id="PS_DEDUTION_lbl_amt" class="col-sm-2">AMOUNT</label>-->

                <div id="earninglables">

                </div>
            </div>

            <label>EARNING::</label><a class="classAddButton classDivMultiRows'.$i.'" id="idAMultiRowSpan-'.$multiRow.'-'.$row[14].'"><img src="IMAGES/details_open.png" class=" img-responsive"></a>
            <div class="form-group" id="earninglbl" hidden>
                <label id="DDC_startdate" class="col-sm-offset-2 col-sm-2">EARNING</label><label id="PS_DEDUTION_lbl_amt" class="col-sm-2">AMOUNT</label>
            </div>
            <div id="EARNING" hidden >

            </div>
            <div class="col-lg-offset-2" id="earning_btn" hidden >
                <!--                    <input type="button" id="PS_bnt_erning_Submit" class="btn" value="Submit">-->
            </div>
        </div>
        <div class="row-fluid form-group">
            <div id="deductionlables">

            </div>
        </div>
        <div class="row-fluid form-group">
            <label>DEDUCTION::</label><a class="classAddButton1 classDivMultiRows'.$i.'" id="idAMultiRowSpan-'.$multiRow.'-'.$row[14].'"><img src="IMAGES/details_open.png" class=" img-responsive"></a>

            <div class="form-group" id="deductionlbl" hidden>
                <label id="DDC_startdate" class="col-sm-offset-2 col-sm-2">DEDUCTION</label><label id="PS_DEDUTION_lbl_amt" class="col-sm-2">AMOUNT</label>
            </div>
            <div id="DEDUCTION" hidden>
            </div>
            <div class="col-lg-offset-2" id="deduction_btn" hidden >
                <!--                    <input type="button" id="PS_bnt_Submit" class="btn" value="Submit">-->
            </div>
        </div>
        <div class="row form-group">
            <div class="col-lg-offset-2 col-lg-3">
                <input type="button" id="PS_bnt_payslip_Submit" class="btn" value="SAVE">

                <input type="button" id="PS_pdf" class="btn" value="PDF">
            </div>
        </div>
</div>
</form>
</div>
</body>
</html>
