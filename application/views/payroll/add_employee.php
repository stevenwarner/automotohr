<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); $Employee['middle_name'] = 'T'; ?>
        <br />
        <div style="position: relative;">
            <div class="csIPLoader jsIPLoader dn" data-page="employee">
                <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
            </div>
            <!-- Main Content Area -->
            <div class="row">
                <div class="col-sm-12">
                    <h1 class="csF18 csB9">
                        <?php 
                            //
                            $text = 'Not On Payroll';
                            $class = 'label-danger csW';
                            //
                            if($Employee['on_payroll'] == 1) {
                                $text = 'On Payroll';
                                $class = 'label-success csW';
                            }
                        ?>
                        Add Employee To Payroll
                        <span class="pull-right">
                            <span class="label <?=$class;?>"><?=$text;?></span>
                        </span> 
                    </h1>
                    <hr>
                </div>
            </div>
            <?php if($Employee['on_payroll'] == 1): ?>
            <!--  -->
            <div class="row">
                <div class="col-sm-12">
                    <label class="csF16 csB7">Payroll Details</label>
                    <p class="csF16"><strong>Id</strong>: <?=$Payroll['gusto_employee_uid'];?></p>
                    <p class="csF16"><strong>Created On</strong>: <?=formatDateToDB($Payroll['created_at'], 'Y-m-d H:i:s', DATE_WITH_TIME);?></p>
                    <p class="csF16"><strong>Last Updated On</strong>: <?=formatDateToDB($Payroll['updated_at'], 'Y-m-d H:i:s', DATE_WITH_TIME);?></p>
                </div>
            </div>
            <br />
            <?php endif; ?>
            <!--  -->
            <div class="row">
                <div class="col-sm-4">
                    <label class="csF16 csB7">Firstname <span class="csRequired"></span></label>
                    <input type="text" class="form-control jsFN" <?=!empty($Employee['first_name']) ? 'disabled' : '';?> value="<?=!empty($Employee['first_name']) ? $Employee['first_name'] : '';?>" />
                </div>
                <div class="col-sm-4">
                    <label class="csF16 csB7">Middlename</label>
                    <input type="text" class="form-control jsMN" <?=!empty($Employee['middle_name']) ? 'disabled' : '';?> value="<?=!empty($Employee['middle_name']) ? $Employee['middle_name'] : '';?>" />
                </div>
                <div class="col-sm-4">
                    <label class="csF16 csB7">Lastname <span class="csRequired"></span></label>
                    <input type="text" class="form-control jsLN" <?=!empty($Employee['last_name']) ? 'disabled' : '';?> value="<?=!empty($Employee['last_name']) ? $Employee['last_name'] : '';?>" />
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-sm-4">
                    <label class="csF16 csB7">Email <span class="csRequired"></span></label>
                    <input type="text" class="form-control jsEmail" <?=!empty($Employee['email']) ? 'disabled' : '';?> value="<?=!empty($Employee['email']) ? $Employee['email'] : '';?>" />
                </div>
                <div class="col-sm-4">
                    <label class="csF16 csB7">SSN <span class="csRequired"></span></label>
                    <input type="text" class="form-control jsSSN" <?=!empty($Employee['ssn']) ? 'disabled' : '';?> value="<?=!empty($Employee['ssn']) ? $Employee['ssn'] : '';?>" />
                </div>
                <div class="col-sm-4">
                    <label class="csF16 csB7">Date Of Birth <span class="csRequired"></span></label>
                    <input type="text" class="form-control jsDOB" <?=!empty($Employee['dob']) ? 'disabled' : '';?> value="<?=!empty($Employee['dob']) ? $Employee['dob'] : '';?>" />
                </div>
            </div>
            <?php if($Employee['on_payroll'] == 0): ?>
            <br />
            <div class="row">
                <div class="col-sm-12">
                    <span class="pull-right">
                        <button class="btn btn-success jsSubmitBtn">Add Employee To Payroll</button>
                    </span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if($Employee['on_payroll'] == 0): ?>
<script>
    $(function(){

        <?php if(empty($Employee['dob'])) :?>
            $('.jsDOB').datepicker({
                changeYear: true,
                changeMonth: true
            });
        <?php endif; ?>
        //
        $('.jsSubmitBtn').click(function(event){
            //
            event.preventDefault();
            //
            var obj = {};
            //
            obj.first_name = $('.jsFN').val().trim();
            obj.middle_name = $('.jsMN').val().trim();
            obj.last_name = $('.jsLN').val().trim();
            obj.email = $('.jsEmail').val().trim();
            obj.ssn = $('.jsSSN').val().trim();
            obj.dob = $('.jsDOB').val().trim();
            //
            if(obj.first_name == ''){
                //
                alertify.alert(
                    'Warning!',
                    'First name is required.'
                );
                return;
            }
            //
            if(obj.middle_name != '' && obj.middle_name.length != 1){
                //
                alertify.alert(
                    'Warning!',
                    'Middle name must be a single alphabet character.'
                );
                return;
            }
            //
            if(obj.last_name == ''){
                //
                alertify.alert(
                    'Warning!',
                    'Last name is required.'
                );
                return;
            }
            //
            if(obj.email == ''){
                //
                alertify.alert(
                    'Warning!',
                    'Email is required.'
                );
                return;
            }
            //
            if(obj.ssn == ''){
                //
                alertify.alert(
                    'Warning!',
                    'SSN is required.'
                );
                return;
            }
            //
            if(obj.dob == ''){
                //
                alertify.alert(
                    'Warning!',
                    'Date Of Birth is required.'
                );
                return;
            }
            // Validate the incoming object
            if(obj.ssn.replace(/[^0-9]/g, '').length != 9){
                alertify.alert(
                    "Warning!",
                    "SSN should contain only numeric values and must be of 9 digits long."
                );
                return;
            }
            //
            obj.id = <?=$Employee['sid'];?>;
            obj.companyId = <?=$Employee['parent_sid'];?>;
            //
            $('.jsIPLoader[data-page="employee"]').removeClass('dn');
            //
            $.post(
                "<?=base_url("payroll/add_employee_to_payroll")?>",
                obj,
            ).done(function(resp){
                //
                $('.jsIPLoader[data-page="employee"]').addClass('dn');
                //
                if(resp.Status === false){
                    //
                    var errors = '';
                    //
                    resp.Errors.map(function(error){
                        errors += '<p>'+(error.message)+'</p>';
                    });
                    //
                    alertify.alert(
                        'Error!',
                        errors
                    );
                    return;
                }
                //
                alertify.alert('Success!', 'You have successfully added this employee to payroll.', function(){
                    window.location.reload();
                });
            });
        });
    });
</script>
<?php endif; ?>