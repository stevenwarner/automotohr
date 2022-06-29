<hr>
<!--  -->
<div class="row">
    <div class="col-xs-12 col-sm-4">
        <h1 class="csF18 csB7">Select a date to pay contractors</h1>
        <input type="text" class="form-control jsContractorDate" readonly />
    </div>
</div>
<hr>

<!--  -->
<div class="row">
    <div class="col-xs-12 col-sm-4">
        <h1 class="csF18 csB7">Enter hours and payments</h1>
    </div>
</div>
<!--  -->
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <table class="table table-striped">
            <caption></caption>
            <thead>
                <tr>
                    <th class="vam">Contractor</th>
                    <th class="vam">Hours(H)<br />Bonus(B)</th>
                    <th class="vam">Wage(W)<br />Reimbursement(R)</th>
                    <th class="vam">Payment Method</th>
                    <th class="vam">Total Amount</th>
                </tr>
            </thead>
            <tbody id="jsPayrollContractorStep1TBL"></tbody>
        </table>
    </div>
</div>

<hr>
<!--  -->
<div class="row">
    <div class="col-sm-12 text-center">
        <button class="btn btn-black btn-lg jsContractorClear">Clear</button>
        <button class="btn btn-orange btn-lg jsContractorSubmit">Submit</button>
    </div>
</div>
<br>

<script>
    $(function PayContractorStep1() {
        //
        var contractorArray = <?= json_encode($contractors); ?>;
        //
        var contractorOBJ = {};

        window.contractorOBJ = contractorOBJ;
        //
        function makeView() {
            //
            $('#jsPayrollContractorStep1TBL').html('');
            //
            contractorOBJ = {};
            //
            var rows = '';
            //
            if (!contractorArray.length) {
                //
                rows += '<tr>';
                rows += '   <td colspan="5">';
                rows += '       <p class="alert alert-info text-center">';
                rows += '       No contractors available';
                rows += '       </p>';
                rows += '   </td>';
                rows += '</tr>';
                //
                return $('#jsPayrollContractorStep1TBL').html(rows);
            }
            //
            contractorArray.map(function(contractor) {
                //
                rows += '<tr class="jsContractorRow" data-id="' + (contractor.sid) + '" data-type="' + (contractor.wage_type) + '" data-name="' + (contractor.first_name + ' ' + contractor.last_name) + '"  data-hr="' + (contractor.hourly_rate) + '">';
                rows += '   <td class="vam">';
                rows += '       <strong class="csF18 csB7">' + (contractor.first_name + ' ' + contractor.last_name) + '</strong> <br><br>';
                rows += '       <span class="csF18">';
                //
                if (contractor.wage_type == 'Fixed') {
                    rows += 'Fixed';
                } else {
                    rows += 'Hourly @ $' + contractor.hourly_rate + '/hr';
                }
                rows += '       </span>';
                rows += '   </td>';
                rows += '   <td class="vam">';
                //
                rows += '<div>';
                if (contractor.wage_type === 'Hourly') {
                    rows += '   <div class="input-group jsContractorRowBox dn">';
                    rows += '       <div class="input-group-addon">H</div>';
                    rows += '       <input type="text" class="form-control jsContractorNumber text-right jsContractorRate" placeholder="0.00" />';
                    rows += '       <div class="input-group-addon">hr</div>';
                    rows += '   </div>';
                    rows += '   <br />';
                    rows += '   <div class="input-group jsContractorRowBox dn">';
                    rows += '       <div class="input-group-addon">B</div>';
                    rows += '       <input type="text" class="form-control jsContractorNumber text-right jsContractorBonus" placeholder="0.00" />';
                    rows += '   </div>';
                }
                rows += '</div>';
                rows += '   </td>';
                rows += '   <td class="vam">';
                //
                rows += '<div>';
                if (contractor.wage_type === 'Fixed') {
                    rows += '   <div class="input-group jsContractorRowBox dn">';
                    rows += '       <div class="input-group-addon">W</div>';
                    rows += '       <input type="text" class="form-control jsContractorNumber text-right jsContractorWage" placeholder="0.00" />';
                    rows += '   </div>';
                } else{
                    rows += '        <div class="jsContractorRowBox dn">';
                    rows += '            <strong class="jsContractorTotalWage">$0.00</strong>';
                    rows += '        </div>';
                }
                rows += '   <br />';
                rows += '   <div class="input-group jsContractorRowBox dn">';
                rows += '       <div class="input-group-addon">R</div>';
                rows += '       <input type="text" class="form-control jsContractorNumber text-right jsContractorReimbursement" placeholder="0.00" />';
                rows += '   </div>';
                rows += '</div>';
                rows += '   </td>';
                rows += '   <td class="vam text-center">';
                rows += '       <strong class="jsContractorRowBox dn">Direct Deposit</strong>';
                rows += '   </td>';
                rows += '   <td class="vam">';
                rows += '        <div class="jsContractorRowBox dn">';
                rows += '            <strong class="jsContractorTotalPayment">$0.00</strong>';
                rows += '        </div>';
                rows += '        <button class="btn btn-orange jsContractorEP">';
                rows += '            <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Enter payment';
                rows += '        </button>';
                rows += '   </td>';
                rows += '</tr>';
            });
            //
            $('#jsPayrollContractorStep1TBL').html(rows);
        }

        //
        $(document).on('click', '.jsContractorEP', function(event) {
            //
            event.preventDefault();
            //
            $(this).hide();
            $(this).closest('.jsContractorRow').find('.jsContractorRowBox').removeClass('dn');
            //
            var data = $(this).closest('.jsContractorRow').data();
            //
            contractorOBJ[data.id] = {
                type: data.type,
                hourly_rate: data.hr,
                name: data.name,
                wage: 0,
                bonus: 0,
                reimbursement: 0,
                hours: 0,
                total: 0
            };
        });

        //
        $(document).on('keyup', '.jsContractorNumber', function() {
            //
            $(this).val(
                $(this).val().replace(/[^0-9.]/, '')
            );
            //
            var classList = $(this).attr('class').split(/\s+/);
            //
            var cId = $(this).closest('.jsContractorRow').data('id')
            //
            if ($.inArray('jsContractorRate', classList) !== -1) {
                contractorOBJ[cId]['hours'] = parseFloat($(this).val().replace(/[^0-9.]/, '') || 0);
            } else if ($.inArray('jsContractorBonus', classList) !== -1) {
                contractorOBJ[cId]['bonus'] = parseFloat($(this).val().replace(/[^0-9.]/, '') || 0);
            } else if ($.inArray('jsContractorWage', classList) !== -1) {
                contractorOBJ[cId]['wage'] = parseFloat($(this).val().replace(/[^0-9.]/, '') || 0);
            } else if ($.inArray('jsContractorReimbursement', classList) !== -1) {
                contractorOBJ[cId]['reimbursement'] = parseFloat($(this).val().replace(/[^0-9.]/, '') || 0);
            }
            //
            makeTotal(cId);
        });

        //
        function makeTotal(contractorId) {
            //
            var contractor = contractorOBJ[contractorId];
            //
            var total = 0;
            //
            if (contractor.type == 'Fixed') {
                total = parseFloat(contractor.wage) + parseFloat(contractor.reimbursement);
            } else{

                total = parseFloat(contractor.hours * parseFloat(contractor.hourly_rate)) + parseFloat(contractor.bonus) + parseFloat(contractor.reimbursement);
                //
                $('.jsContractorRow[data-id="' + (contractorId) + '"]').find('.jsContractorTotalWage').text('$' + (separateComma(parseFloat(contractor.hours * parseFloat(contractor.hourly_rate)).toFixed(2))));
            }
            //
            $('.jsContractorRow[data-id="' + (contractorId) + '"]').find('.jsContractorTotalPayment').text('$' + (separateComma(total.toFixed(2))));
        }

        function separateComma(val) {
            // remove sign if negative
            var sign = 1;
            if (val < 0) {
                sign = -1;
                val = -val;
            }
            // trim the number decimal point if it exists
            let num = val.toString().includes('.') ? val.toString().split('.')[0] : val.toString();
            let len = num.toString().length;
            let result = '';
            let count = 1;

            for (let i = len - 1; i >= 0; i--) {
                result = num.toString()[i] + result;
                if (count % 3 === 0 && count !== 0 && i !== 0) {
                    result = ',' + result;
                }
                count++;
            }

            // add number after decimal point
            if (val.toString().includes('.')) {
                result = result + '.' + val.toString().split('.')[1];
            }
            // return result with - sign if negative
            return sign < 0 ? '-' + result : result;
        }

        //
        $('.jsContractorClear').click(function(event) {
            //
            event.preventDefault();
            //
            makeView();
        });

        //
        $('.jsContractorSubmit').click(function(event) {
            //
            event.preventDefault();
            //
            var obj = {};
            obj.date = $('.jsContractorDate').val();
            //
            if (!obj.date) {
                return alertify.alert(
                    'Error!',
                    'Please select date',
                    function() {}
                );
            }
            //
            if (Object.keys(contractorOBJ).length === 0) {
                return alertify.alert(
                    'Error!',
                    'Please select at least one contractor',
                    function() {}
                );
            }
            //
            var errorFlag = 0;
            //
            for (var index in contractorOBJ) {
                //
                var contractor = contractorOBJ[index];
                //
                if (contractor.type == 'Hourly' && parseInt(contractor.hours) == 0) {
                    //
                    errorFlag = 1;
                    //
                    return alertify.alert(
                        'Error!',
                        'Please add hours for <strong>' + contractor.name + '</strong>',
                        function() {}
                    )
                } else if (contractor.type == 'Fixed' && parseInt(contractor.wage) == 0) {
                    //
                    errorFlag = 1;
                    //
                    return alertify.alert(
                        'Error!',
                        'Please add wage for <strong>' + contractor.name + '</strong>',
                        function() {}
                    )
                }
            }
            //
            if(errorFlag){
                return;
            }
            //
            obj.contractors = contractorOBJ;
            //
            $.post(
                "<?=base_url("payroll/contractor/run")?>", 
                obj
            )
            .done(function(resp){
                
            });
        });

        //
        makeView();
        //
        $('.jsContractorDate').datepicker({
            format: 'm/d/y'
        })
    });
</script>