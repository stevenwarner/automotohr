<!--  -->
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <label class="csF16 csB7">Account Title <span class="csRequired"></span></label>
            <input type="text" id="jsACAccountTitle" class="form-control" />
        </div>
        <div class="col-sm-4">
            <label class="csF16 csB7">Account Number <span class="csRequired"></span></label>
            <input type="text" id="jsACAccountNumber" class="form-control" />
        </div>
        <div class="col-sm-4">
            <label class="csF16 csB7">Routing Number <span class="csRequired"></span></label>
            <input type="text" id="jsACRoutingNumber" class="form-control" />
        </div>
    </div>
    <br>
    <!--  -->
    <div class="row">
    <div class="col-sm-4">
            <label class="csF16 csB7">Bank Name <span class="csRequired"></span></label>
            <input type="text" id="jsACBankName" class="form-control" />
        </div>
        <div class="col-sm-4">
            <label class="csF16 csB7">Account Type <span class="csRequired"></span></label>
            <select id="jsACAccountType" class="form-control">
                <option value="0"></option>
                <option value="checking">Checking</option>
                <option value="savings">Saving</option>
            </select>
        </div>
        <div class="col-sm-4">
            <br>
            <label class="control control--checkbox">
                <input type="checkbox" id="jsACUseForParoll"> Use For Payroll 
                <div class="control__indicator"></div>
            </label>
            <p class="csF14 csB1 csInfo"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;<em>This action will remove previous accounts from payroll and use this account for payroll.</em></p>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <span class="pull-right">
                <button class="btn btn-success jsACAddAccount">Add Bank Account</button>
            </span>
        </div>
    </div>
</div>


<script>
    $(function(){
        //
        $('.jsACAddAccount').click(function(event){
            //
            event.preventDefault();
            //
            var o = {};
            //
            o.account_title = $('#jsACAccountTitle').val().trim();
            o.account_number = $('#jsACAccountNumber').val().trim();
            o.routing_number = $('#jsACRoutingNumber').val().trim();
            o.bank_name = $('#jsACBankName').val().trim();
            o.account_type = $('#jsACAccountType').val();
            o.use_for_payroll = $('#jsACUseForParoll').prop('checked');
            //
            if(o.account_title === ''){
                alertify.alert(
                    'Warning!', 
                    'Account title is required.'
                );
                return;
            }
            //
            if(o.account_number === ''){
                alertify.alert(
                    'Warning!', 
                    'Account number is required.'
                );
                return;
            }
            //
            if(o.routing_number === ''){
                alertify.alert(
                    'Warning!', 
                    'Routing number is required.'
                );
                return;
            }
            //
            if(o.routing_number.replace(/[^0-9]/g, '').length != 9){
                alertify.alert(
                    'Warning!', 
                    'Routing number should only contain digits and must be of 9 digits.'
                );
                return;
            }
            //
            if(o.bank_name === ''){
                alertify.alert(
                    'Warning!', 
                    'Bank name is required.'
                );
                return;
            }
            //
            if(o.account_type == 0){
                alertify.alert(
                    'Warning!', 
                    'Account type is required.'
                );
                return;
            }
            //
            o.companyId = <?=$companyId;?>;
            o.employerId = <?=$employerId;?>;
            //
            ml(true, 'jsEBAModalLoader');
            //
            $.post(
                "<?=base_url("payroll/add_company_payroll_bank_account")?>", 
                o
            ).done(function(resp){
                //
                ml(false, 'jsEBAModalLoader');
                //
                if(resp.Status === false){
                    //
                    var errors = '';
                    //
                    resp.Errors.map(function(err){
                        //
                        errors += '<p>'+(err.message === undefined ? err : err.message)+'</p>';
                    });
                    //
                    alertify.alert(
                        'Error!',
                        errors
                    );
                    //
                    return;
                }
                //
                alertify.alert(
                    'Success!',
                    resp.Response,
                    function(){
                        window.location.reload();
                    }
                );
            });
        });
    });
</script>