<!--//******************************************FILE DESCRIPTION********************************************//
//********************************PETTY CASH**********************************//
//DONE BY:KAVIN KARNAN
//VER 0.1-SD:30/06/2015 ED:11/07/2015,DESC: CREATE PETTY CASH FORM-->

<?php
include "../../TSLIB/TSLIB_HEADER.php";
//include  "NEW_MENU.php";
?>
<html>
<head>

    <link rel="stylesheet" href="Data_table/media/css/colreorder.css" />
    <link rel="stylesheet" href="Data_table/media/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="Data_table/media/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="Data_table/media/css/jquery.dataTables_themeroller.css" />
    <script type="text/javascript" src="Data_table/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="Data_table/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="Data_table/media/js/colreorder.js"></script>

    <script>
        $(document).ready(function()
        {
//alert('Ready fun');
            $(".preloader").hide();
            first()

            var option="load";
            $.ajax({
                type: "POST",
                url: "ADMIN/EMPLOYEE/DB_PETTY_CASH.do",
                data:'&Option='+ option,
                success: function (data)
                {
//           alert(data);
                    $('#PC_tb_BALANCE').val(data)

                },
                error: function (data)
                {
                    alert('error in getting' + JSON.stringify(data));
                }
            });

            $('#PC_tb_DATE').datepicker(
                {
                    dateFormat:"dd-mm-yy",
                    changeYear: true,
                    changeMonth: true

                });
            $('#PC_tb_DATE').datepicker("option","maxDate",new Date());

            $("#PC_tb_atm").keypress(function (e)
            {
                //if the letter is not digit then display error and don't type anything
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))
                {
                    //display error message
                    // $("#studentform").html("Digits Only").show().fadeOut("slow");
                    return false;
                }
            });

            $(document).on('click','#PC_bnt_back',function()//FOR HIDEING FORM
            {
//            alert('a');
                $('#select').show();
                $('#petty_cash').hide();
                $('#datatables').hide();
            });
            $(document).on('click','.entry',function()//FOR HIDEING FORM
            {
//            alert('a');
                $('#petty_cash').show();
                $('#select').hide();
                $('#datatables').hide();
            });
            $(document).on('click','.search',function()//FOR HIDEING FORM
            {
//            alert('a');
                $('#petty_cash').hide();
                $('#datatables').show();
                $('#select').hide();
            });

            $(document).on('click','#PC_bnt_Submit',function()//FRO INSERT THE DATA
            {
                var formelement = $('#pettycashform').serialize();
                var option='Insert';
                $.ajax({
                    type: "POST",
                    url: "ADMIN/EMPLOYEE/DB_PETTY_CASH.do",
                    data:formelement+'&Option='+ option,

                    success: function (data)
                    {
//                alert(data)
                        var values=JSON.parse(data);
                        $('#PC_tb_BALANCE').val(values[0])
                        if (values[1] == 1)
                        {
                            alert('value inserted');
                            first()
                            $('#PC_tb_DATE').val('');
                            $('#PC_tb_INVOICE_ITEMS').val('');
                            $('#PC_tb_atm').val('');
                            $('#PC_tb_COMMENTS').val('');

//                $('#pettycashform')[0].reset();
                        }
                        else
                        {
                            alert('Record not inserted');
                        }
                    },
                    error: function (data)
                    {
                        alert('error in getting' + JSON.stringify(data));
                    }
                });
            });

//FOR DATATABLES FUNTIONS
            function first()
            {
                var formelement = $('#pettycashform').serialize();
                $.ajax({
                    type:"POST",
                    url: "ADMIN/EMPLOYEE/DB_PETTY_CASH.do",
                    data:{option:'ShowDetails'},
                    success: function (data)
                    {
                        $('section').html(data);
                        $('#tablecontainer').show();
                        $('#Pettycash').DataTable(
                            {
                                "aaSorting": [],
                                "pageLength": 10,
                                "responsive": true,
                                "sPaginationType":"full_numbers",

                                "sDom":"Rlfrtip",
                                "deferRender":true,
                                "dom":"frtiS",
                                "scrollY": 400,
                                "scrollX": true,
                                "scrollCollapse": true


                            });
                    },
                    error: function (data)
                    {
                        alert('error in getting' + JSON.stringify(data));
                    }
                })
            }

            $(document).on('change blur','#pettycashform',function()
            {
                var date=$('#PC_tb_DATE').val();
                var report=$("#PC_tb_COMMENTS").val();
                var  amt=$("#PC_tb_atm").val();
                var  invoice=$("#PC_tb_INVOICE_ITEMS").val();

                if(date!=''&& report!=''&& amt!='' && invoice!='')
                {
                    $('#PC_bnt_Submit').removeAttr('disabled');
                }
                else
                {
                    $('#PC_bnt_Submit').attr('disabled','disabled');
                }

            });

            var previous_id;
            var combineid;
            var pcid;
            var tdvalue;
            var ifcondition;
            $(document).on('click','.edit', function ()
            {
                if(previous_id!=undefined){
                    $('#'+previous_id).replaceWith("<td class='edit' id='"+previous_id+"' >"+tdvalue+"</td>");
                }
                var cid = $(this).attr('id');
                var id=cid.split('_');
                ifcondition=id[0];
                previous_id=cid;
                pcid=id[1];
                tdvalue=$(this).text();

                if(ifcondition=='PCDATE')
                {
                    $('#'+cid).replaceWith("<td  class='new' id='"+previous_id+"'><input type='text' id='PCDATE' name='PCDATE'  class='update date-picker' style='width: 110px'  value='"+tdvalue+"'></td>");
                    $(".date-picker").datepicker({dateFormat:'dd-mm-yy',
                        changeYear: true,
                        changeMonth: true
                    });
                    $('.date-picker').datepicker("option","maxDate",new Date());
                }
                if(ifcondition=='PCCASHIN')
                {
                    $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><input type='text' id='PCCASHIN' name='PCCASHIN'  class='update' maxlength='50'  value='"+tdvalue+"'></td>");
                    $("#PCCASHIN").keypress(function (e)
                    {

                        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))
                        {

                            return false;
                        }
                    });
                }
                if(ifcondition=='PCCASHOUT')
                {
                    $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><input type='text' id='PCCASHOUT' name='PCCASHOUT'  class='update' maxlength='50'  value='"+tdvalue+"'></td>");
                    $("#PCCASHOUT").keypress(function (e)
                    {

                        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))
                        {

                            return false;
                        }
                    });

                }

                if(ifcondition=='PCBALANCE')
                {
                    $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><input type='text' id='PCBALANCE' name='PCBALANCE'  class='update' maxlength='50'  value='"+tdvalue+"'></td>");
                    $("#PCBALANCE").keypress(function (e)
                    {

                        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))
                        {

                            return false;
                        }
                    });

                }
                if(ifcondition=='PCINVOICEITEMS')
                {
                    $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><input type='text' id='PCINVOICEITEMS' name='PCINVOICEITEMS'  class='update' maxlength='50'  value='"+tdvalue+"'></td>");

                }
                if(ifcondition=='PCCOMMENTS')
                {
                    $('#'+cid).replaceWith("<td class='new' id='"+previous_id+"'><input type='text' id='PCCOMMENTS' name='PCCOMMENTS'  class='update' maxlength='50'  value='"+tdvalue+"'></td>");

                }

            });
            $(document).on('change','.update',function()
            {
//        alert('safdsa');
//        $('.preloader').show();

                if($('#PCDATE_'+pcid).hasClass("edit")==true)
                {
                    var PCDATE=$('#PCDATE_'+pcid).text();

                }
                else
                {

                    var PCDATE=$('#PCDATE').val();

                }

                if($('#PCCASHIN_'+pcid).hasClass("edit")==true)
                {

                    var PCCASHIN=$('#PCCASHIN_'+pcid).text();

                }
                else
                {
                    var PCCASHIN=$('#PCCASHIN').val();

                }
                if($('#PCCASHOUT_'+pcid).hasClass("edit")==true)
                {

                    var PCCASHOUT=$('#PCCASHOUT_'+pcid).text();
                }
                else
                {
                    var PCCASHOUT=$('#PCCASHOUT').val();

                }
                if($('#PCBALANCE_'+pcid).hasClass("edit")==true)
                {

                    var PCBALANCE=$('#PCBALANCE_'+pcid).text();

                }
                else
                {
                    var PCBALANCE=$('#PCBALANCE').val();

                }


                if($('#PCINVOICEITEMS_'+pcid).hasClass("edit")==true)
                {

                    var PCINVOICEITEMS=$('#PCINVOICEITEMS_'+pcid).text();
                    // alert(PCINVOICEITEMS);
                }
                else
                {
                    var PCINVOICEITEMS=$('#PCINVOICEITEMS').val();

                }
                if($('#PCCOMMENTS_'+pcid).hasClass("edit")==true)
                {

                    var PCCOMMENTS=$('#PCCOMMENTS_'+pcid).text();

                }
                else
                {
                    var PCCOMMENTS=$('#PCCOMMENTS').val();

                }

                $.ajax({
                    type: 'POST',
                    url: "ADMIN/EMPLOYEE/DB_PETTY_CASH.do",
                    data:'&option=update&rowid='+pcid+'&PCDATE='+PCDATE+'&PCCASHIN='+PCCASHIN+'&PCCASHOUT='+PCCASHOUT+'&PCBALANCE='+PCBALANCE+'&PCINVOICEITEMS='+PCINVOICEITEMS+'&PCCOMMENTS='+PCCOMMENTS,
                    success: function(data)
                    {
                        alert(data)
//                $('.preloader').hide();
                        var resultflag=data;
//                alert(resultflag);
                        if(resultflag==1)
                        {
                            alert('update Successfully');
                            first()
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE ENTRY",msgcontent:ET_ENTRY_errormsg[1],position:{top:100,left:100}}});
                            previous_id=undefined;
                        }

                        else
                        {
                            first()
                            //alert('Not update Successfully');
//                    $(document).doValidation({rule:'messagebox',prop:{msgtitle:"EMAIL TEMPLATE ENTRY",msgcontent:ET_ENTRY_errormsg[0],position:{top:100,left:100}}});
//
                            previous_id=undefined;
                        }
                    }

                });
            });
        });
    </script>
</head>
<div class="container ">
    <div class="preloader"><span class="Centerer"></span><img class="preloaderimg"/></div>
    <div class="title text-center"><h4><b>PETTY CASH</b></h4></div>
    <form id="pettycashform" class="content" role="form" autocomplete="on">
        <div class="panel-body">
            <fieldset>
                <div id="petty_cash" hidden>

                    <div class="row form-group">
                        <div class="col-md-2">
                            <label>CURRENT BALANCE:</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" readonly class="form-control" id="PC_tb_BALANCE" name="PC_tb_BALANCE"   maxlength="30" value=""/>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-2">
                            <label>SELECT DATE:</label>
                        </div>
                        <div class='col-md-3'>
                            <input type='text' class="form-control" id="PC_tb_DATE" name="PC_tb_DATE"/>
                        </div>

                    </div>
                    <div class="row form-group">
                        <div class="col-md-2">
                            <label>CASH TYPE:</label>
                        </div>
                        <div class="col-lg-3" >
                            <input type="radio" id="PC_cash_radio" name="PC_cash_radio"  value="PC_CASH_IN"/>CASH IN
                            <input type="radio" id="PC_cash_radio" name="PC_cash_radio"   value="PC_CASH_OUT" CHECKED/>CASH OUT

                        </div>
                    </div>

                    <div class="row form-group" style="">
                        <div class="col-md-2">
                            <label>AMOUNT:</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="PC_tb_atm" name="PC_tb_atm" maxlength="30"/>
                        </div>
                    </div>
                    <div class="row form-group" style="">
                        <div class="col-md-2">
                            <label>INVICE ITEM:</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="PC_tb_INVOICE_ITEMS" name="PC_tb_INVOICE_ITEMS" maxlength="30"/>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-2">
                            <label>COMMENTS:</label>
                        </div>
                        <div class="col-md-3">
                            <textarea cols="30" class="form-control" id="PC_tb_COMMENTS" name="PC_tb_COMMENTS" rows="2" ></textarea>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-offset-2 col-lg-3">
                            <input type="button" id="PC_bnt_Submit" class="btn" value="Submit" disabled> <input type="button" id="PC_bnt_back" class="btn" value="Back">
                        </div>
                    </div>
                </div>
                <div id="select">
                    <div class="row form-group">
                        <div class="col-md-1">
                            <label>SELECT:</label>
                        </div>
                        <div class="col-md-2" >
                            <input type="radio"id="PC_cash" name="PC_cash" value="IN" class="entry"/>ENTRY
                            <BR><input type="radio"id="PC_cash" name="PC_cash" value="OUT" class="search"/>SEARCH/UPDATE

                        </div>
                    </div>
                </div>
                <div id="datatables" hidden>

                    <div class="row form-group">
                        <div class="col-lg-1">
                            <input type="button" id="PC_bnt_back" class="btn" value="Back">
                        </div>
                    </div>
                    <div id="Datatable" class="table-responsive">
                        <section>

                        </section>
                    </div>
                </div>
        </div>
        </fieldset>
    </form>
</div>
</html>
