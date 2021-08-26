<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br />
        <!-- Main Content Area -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="csF18 csB9">
                    Employees Listing 
                </h1>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <span class="pull-right">
                    <button class="btn btn-success jsAddBankAccount">
                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                        Add A New Bank Account
                    </button>
                    <button class="btn btn-danger jsDeleteBankAccounts">
                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                        Delete Bank Accounts
                    </button>
                </span>
            </div>
        </div>
        <!--  -->
        <div class="cardContainer" style="position: relative;">
            <div class="csIPLoader jsIPLoader" data-page="main"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i></div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th class="text-center" scope="col">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="" id="jsSelectMultiple">
                                        <div class="control__indicator" style="top: -10px;"></div>
                                    </label>
                                </th>
                                <th class="text-center" scope="col">Account Type</th>
                                <th class="text-center" scope="col">Account Number</th>
                                <th class="text-center" scope="col">Routing Number</th>
                                <th class="text-center" scope="col">Used For Payroll</th>
                                <th class="text-center" scope="col">Account Payroll Id</th>
                                <th class="text-center" scope="col">Last Updated By</th>
                                <th class="text-center" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($BankAccounts)) : ?>
                            <?php foreach($BankAccounts as $account):?>
                                <!--  -->
                                <tr data-id="<?=$account['sid'];?>">
                                    <td class="vam text-center">
                                        <?php if(empty($account['account_uid'])): ?>
                                        <label class="control control--checkbox">
                                            <input type="checkbox" name="" class="jsSelectSingle">
                                            <div class="control__indicator" style="top: -10px;"></div>
                                        </label>
                                        <?php endif;?>
                                    </td>
                                    <td class="vam text-center">
                                        <p><?=ucwords($account['account_type']);?></p>
                                    </td>
                                    <td class="vam text-center">
                                        <p><?=$account['account_number'];?></p>
                                    </td>
                                    <td class="vam text-center">
                                        <p><?=$account['routing_number'];?></p>
                                    </td>
                                    <td class="vam text-center">
                                        <p><?=$account['use_for_payroll'] == 1 ? 'Yes' : 'No';?></p>
                                    </td>
                                    <td class="vam text-center">
                                        <p><?=!empty($account['account_uid']) ? $account['account_uid'] : 'N/A';?></p>
                                    </td>
                                    <td class="vam">
                                        <p><?=remakeEmployeeName($account);?></p>
                                        <p><?=formatDateToDB($account['updated_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);?></p>
                                    </td>
                                    <td class="vam text-center">
                                        <?php if(empty($account['account_uid'])): ?>
                                            <button class="btn btn-success jsEditAccount">
                                                <i class="fa fa-edit" aria-hidden="true"></i> Edit Account
                                            </button>
                                            <button class="btn btn-success jsAddAccountToPayroll">
                                                <i class="fa fa-plus-square" aria-hidden="true"></i> Use For Payroll
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8">
                                        <p class="alert alert-info text-center csF16 csB7">
                                            No bank accounts found. <br><br>
                                            <button class="btn btn-success jsAddBankAccount"><i class="fa fa-plus-square" aria-hidden="true"></i> Add A New Bank Account</button>
                                        </p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add System Model -->
<link rel="stylesheet" href="<?=base_url("assets/css/SystemModel.css");?>">
<script src="<?=base_url("assets/js/SystemModal.js");?>"></script>

<script>
    $(function(){
        //
        $('.jsAddBankAccount').click(function(event){
            //
            event.preventDefault();
            //
            Modal({
                Id: "jsEBAModal",
                Title: "Add A New Bank Account",
                Body:  "<div id=\"jsEBAModalBody\"></div>",
                Loader: "jsEBAModalLoader",
            }, function(){
                $.get("<?=base_url("payroll/get_add_bank_account");?>/"+<?=$companyId;?>)
                .done(function(resp){
                    $('#jsEBAModalBody').html(resp);
                    ml(false, 'jsEBAModalLoader');
                });
            });
        });

        //
        $('#jsSelectMultiple').click(function(){
           //
           $('.jsSelectSingle').prop('checked', $(this).prop('checked'));
        });
        
        //
        $('.jsDeleteBankAccounts').click(function(event){
            //
            event.preventDefault();
            //
            var ids = [];
            //
            $('.jsSelectSingle:checked').map(function(){
                ids.push($(this).closest('tr').data('id'));
            });
            //
            if(ids.length === 0){
                alertify.alert(
                    'Error!',
                    'Please select at least one account.'
                );
                return;
            }
            //
            alertify.confirm(
                'This action will remove the selected accounts. Do you want to continue?',
                function(){
                    RemoveAccounts(ids);
                }
            );
        });
        
        //
        $('.jsEditAccount').click(function(event){
            //
            event.preventDefault();
            //
            var id = $(this).closest('tr').data('id');
            //
            Modal({
                Id: "jsEBEModal",
                Title: "Add A New Bank Account",
                Body:  "<div id=\"jsEBEModalBody\"></div>",
                Loader: "jsEBEModalLoader",
            }, function(){
                $.get("<?=base_url("payroll/get_edit_bank_account");?>/"+(id)+"/"+<?=$companyId;?>)
                .done(function(resp){
                    $('#jsEBEModalBody').html(resp);
                    ml(false, 'jsEBEModalLoader');
                });
            });
            
        });
        //
        $('.jsAddAccountToPayroll').click(function(event){
            //
            event.preventDefault();
            //
            var id = $(this).closest('tr').data('id');
            //
            alertify.confirm(
                'This action will remove the previous bank account from payroll. Do you want to continue?',
                function(){
                    UpdateBankPayroll(id);
                }
            );
        });
        
        //
        function RemoveAccounts(ids){
            //
            ml(true, 'main');
            //
            $.post(
                "<?=base_url("payroll/remove_company_bank_account");?>", {
                    accountIds: ids,
                    companyId: <?=$companyId;?>,
                    employeeId: <?=$employerId;?>
                }
            ).done(function(resp){
                //
                ml(false, 'main');
                //
                if(resp.Status === false){
                    //
                    alertify.alert(
                        'Error!',
                        resp.Message
                    );
                    //
                    return;
                }
                //
                alertify.alert(
                    'Success!',
                    resp.Message,
                    function(){
                        window.location.reload();
                    }
                );
            });
        }   

        //
        function UpdateBankPayroll(id){
            //
            ml(true, 'main');
            //
            $.post(
                "<?=base_url("payroll/update_bank_account_to_payroll");?>", {
                    accountId: id,
                    companyId: <?=$companyId;?>,
                    employeeId: <?=$employerId;?>
                }
            ).done(function(resp){
                //
                ml(false, 'main');
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
        }   
        //
        ml(false, 'main');
    });
</script>