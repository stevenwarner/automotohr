<!--  -->
<div class="container">
    <div class="row">
        <div class="col-sm-12">
        <?php if(!empty($bank_accounts)): ?>
            <table class="table table-striped table-bordered">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col">Account Title</th>
                        <th scope="col">Account Type</th>
                        <th scope="col">Bank Name</th>
                        <th scope="col">Account Number</th>
                        <th scope="col">Routing Number</th>
                        <th scope="col">Payroll Bank Id</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bank_accounts as $account){ ?>
                        <tr 
                            data-id="<?=$account['account_id'];?>"
                            data-name="<?=$account['account_title'];?>"
                            data-account_type="<?=$account['account_type'];?>"
                            data-account_number="<?=$account['account_number'];?>"
                            data-routing_number="<?=$account['routing_number'];?>"
                        >
                            <td class="vam">
                                <p><?=$account['account_title'];?></p>
                            </td>
                            <td class="vam">
                                <p><?=$account['account_type'];?></p>
                            </td>
                            <td class="vam">
                                <p><?=$account['bank_name'];?></p>
                            </td>
                            <td class="vam">
                                <p><?=$account['account_number'];?></p>
                            </td>
                            <td class="vam">
                                <p><?=$account['routing_number'];?></p>
                            </td>
                            <td class="vam">
                                <p><?=!empty($selected['bank_uid']) && $selected['bank_id'] == $account['account_id'] ? $selected['bank_uid'] : 'N/A';?></p>
                            </td>
                            <td class="vam">
                                <?php if(empty($selected) || $selected['bank_id'] != $account['account_id']): ?>
                                    <button class="btn btn-success jsAddAccountToPayroll">Use Account For Payroll</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="alert alert-info csF16 csB7 text-center">
                No bank account found. <br><br>
                <a href="<?=base_url("direct_deposit/employee/".($employeeId)."");?>" target="_blank" class="btn btn-success">Add A New Bank Account</a>
            </p>
        <?php endif;?>
        </div>
    </div>
</div>


<script>
    $(function(){
        //
        $('.jsAddAccountToPayroll').click(function(event){
            //
            event.preventDefault();
            //
            var o = {};
            o = Object.assign(o, $(this).closest('tr').data());
            o.employeeId = <?=$employeeId;?>;
            alertify.confirm(
                'This action will remove the previous bank account from payroll and add this one. <br> Do you want to continue with this action?',
                function(){
                    ml(true, 'jsEBAModalLoader');
                    //
                    $.post(
                        "<?=base_url("payroll/add_bc_to_payroll");?>",
                        o
                    ).done(function(resp){
                        //
                        ml(false, 'jsEBAModalLoader');
                        //
                        if(resp.Status === false){
                            //
                            var errors = '';
                            //
                            resp.Errors.map(function(er){
                                errors += '<p>'+(er.message)+'</p>';
                            });
                            //
                            alertify.alert('Error!', errors);
                            return;
                        }
                        //
                        alertify.alert(
                            'Success!',
                            'You have successfully updated the bank account for payroll.',
                            function(){
                                $('.jsModalCancel').click();
                            }
                        );
                    });
                }
            );
        });
    });
</script>