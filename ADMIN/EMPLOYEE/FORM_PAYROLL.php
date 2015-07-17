
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
//
//
//  alert('ready fun');

            var tablerowCount;
            var rowcount;

            $(".preloader").hide();
            $("#temptextbox").hide();
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
                url: "ADMIN/EMPLOYEE/DB_PAY_SLIP.do",
                data:'&Option='+ option,
                success: function (data)
                {
//               alert(data);
                    var arrayvalues=JSON.parse(data);

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

                    var earninglabels='';

//               for (var i=0;i<earninglabelsarr.length;i++)
//                {
//                    earninglabels+='<label class="col-sm-2">'+earninglabelsarr[i]+'</label>';
//                    earninglabels+='<div class="col-sm-10"> ';
//                    earninglabels+='<input type="text" class="form-control" id="'+earninglabelsarr[i]+'" style="width:100px"/></div>';
//                }
//                earninglabels+='</div>';
//
//                $('#earninglables').append(earninglabels).show;
//              $('#earninglables').html(earninglabels);
//
//
//                deductionlabel='<div>';
//                for (var i=0;i<deductionlabelsarr.length;i++)
//                {
//
//                    deductionlabel+='<label class="col-sm-2">'+deductionlabelsarr[i]+'</label>';
//                    deductionlabel+='<div class="col-lg-10"> ';
//                    deductionlabel+='<input type="text" class="form-control" id="'+deductionlabelsarr[i]+'" style="width:100px"/></div>';
////                    deductionlabel+='</div>';
//                }
//                deductionlabel+='</div>';
//
//                $('#deductionlables').append(deductionlabel).show;
////                $('#deductionlables').html(deductionlabel);

                },
                error: function (data)
                {
                    alert('error in getting' + JSON.stringify(data));
                }
            });

            $(document).on("click", '.classMinusButton', function()
            {
                var id=$(this).attr('id');

                alert(id);
                var arr=$('#temptextbox').val();
                var min=parseInt(arr)-1;

                $('#lblEARNING'+min).hide();
                $('#EARNING'+min).hide();


//                $("#earning_btn").hide();
                //  $("#EARNING").hide();
//                $("#earninglbl").hide();
//                $("#close").hide();
//                  $(this).find('img').attr('src','images/details_open.png');
//                  $(this).addClass('classAddButton').removeClass('classMinusButton');

            });

            $(document).on("click",'.classMinusButton1',function()
            {
                $("#deduction_btn").hide();
                $("#deductionlbl").hide();
                $("#DEDUCTION").hide();
                $("#close").hide();

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

//                alert(rowcount);
                $('<div class="form-group"><div class="col-sm-offset-2 col-sm-2"><input type="text" style="width: 100px" id="lbl'+uploadfileid+'"></div><div class="col-sm-2"><input type="text" id="'+uploadfileid+'"style="width: 100px"></div><div class="col-sm-1"><a class="classMinusButton classDivMultiRows" id="btn'+uploadfileid+'"><img src="IMAGES/details_close.png" class=" img-responsive"></a></div></div>').appendTo($("#EARNING"));


//                    $(this).find('img').attr('src','images/details_close.png');
//                    $(this).addClass('classMinusButton').removeClass('classAddButton');

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
                    var uploadfileid1="DEDUCTION"+row_count;
                    $('#temptextbox1').val(row_count);
                }
                else
                {
                    var rowvalue=$('#temptextbox1').val();
                    var rowcount=parseInt(rowvalue)+1;
                    uploadfileid1="DEDUCTION_"+rowcount;
                    $('#temptextbox1').val(rowcount);

                }
                $('<div class="form-group"><div class="col-sm-offset-2 col-sm-2"><input type="text" style="width: 100px" id="lbl'+uploadfileid1+'"></div><div class="col-sm-2"><input type="text" id="'+uploadfileid1+'"style="width: 100px"></div><div class="col-sm-1"><a class="classMinusButton1 classDivMultiRows" id="idAMultiRowSpan"><img src="IMAGES/details_close.png" class=" img-responsive"></a></div></div>').appendTo($("#DEDUCTION"));

//                    $(this).find('img').attr('src','images/details_close.png');
//                    $(this).addClass('classMinusButton').removeClass('classAddButton');

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
            <!--<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
            <!--            <div class="form-group">-->
            <!--                <div class="col-sm-offset-2 col-sm-2">-->
            <!--                    <input type="text" id="lbl'+uploadfileid+'" style="width: 100px">-->
            <!--                </div>-->
            <!--                <div class="col-sm-2">-->
            <!--                    <input type="text" id="'+uploadfileid+'" style="width: 100px">-->
            <!--                </div>-->
            <!--                <div class=" col-sm-1">-->
            <!--                    <a class="classAddButton classDivMultiRows'.$i.'" id="idAMultiRowSpan-'.$multiRow.'-'.$row[14].'"><img src="IMAGES/details_open.png" class=" img-responsive"></a></div>-->
            <!--            </div>-->


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

            <!--            <div class="row-fluid form-group">-->
            <!--             <label class="col-sm-2"><B>EARNINGS:</B><em>*</em></label>-->
            <!---->
            <!--            </div>-->
            <!--           <div class="form-group"><label id="DDC_startdate" class="col-sm-offset-2 col-sm-2">EARNING</label><label id="DDC_enddate" class="col-sm-2">AMOUNT</label></div>';-->

            <div class="row-fluid form-group">

                EARNING::<a class="classAddButton classDivMultiRows'.$i.'" id="idAMultiRowSpan-'.$multiRow.'-'.$row[14].'"><img src="IMAGES/details_open.png" class=" img-responsive"></a>
                <!--              <div id="close" hidden>-->
                <!--              <a class="classMinusButton classDivMultiRows'.$i.'" id="idAMultiRowSpan-'.$multiRow.'-'.$row[14].'"><img src="IMAGES/details_close.png" class=" img-responsive"></a>-->
                <!--              </div>-->
                <div class="form-group" id="earninglbl" hidden>
                    <label id="DDC_startdate" class="col-sm-offset-2 col-sm-2">EARNING</label><label id="PS_DEDUTION_lbl_amt" class="col-sm-2">AMOUNT</label>
                </div>
                <div id="EARNING" hidden >

                </div>
                <div class="col-lg-offset-2" id="earning_btn" hidden >
                    <input type="button" id="PS_bnt_Submit" class="btn" value="Submit">
                </div>
            </div>


            <div class="row-fluid form-group">

                DEDUCTION::<a class="classAddButton1 classDivMultiRows'.$i.'" id="idAMultiRowSpan-'.$multiRow.'-'.$row[14].'"><img src="IMAGES/details_open.png" class=" img-responsive"></a>

                <div class="form-group" id="deductionlbl" hidden>
                    <label id="DDC_startdate" class="col-sm-offset-2 col-sm-2">DEDUCTION</label><label id="PS_DEDUTION_lbl_amt" class="col-sm-2">AMOUNT</label>
                </div>
                <div id="DEDUCTION" hidden>
                </div>
                <div class="col-lg-offset-2" id="deduction_btn" hidden >
                    <input type="button" id="PS_bnt_Submit" class="btn" value="Submit">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-offset-2 col-lg-3">
                    <input type="button" id="PS_bnt_Submit" class="btn" value="SAVE" disabled>
                </div>
            </div>
        </div>

    </form>
</div>
</body>
</html>
