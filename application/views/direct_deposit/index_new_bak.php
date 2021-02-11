<?php

//$load_view = 'old';
//
//if ($this->session->userdata('logged_in')) {
//    if (!isset($session)) {
//        $session = $this->session->userdata('logged_in');
//    }
//    $access_level = $session['employer_detail']['access_level'];
//
//    if ($access_level == 'Employee') {
//        $load_view = 'new';
//    }
//}
//

//$uri_segment_01 = strtolower($this->uri->segment(1));
//
//if($uri_segment_01 == 'my_profile' ||
//    $uri_segment_01 == 'login_password' ||
//    $uri_segment_01 == 'incident_reporting_system' ||
//    $uri_segment_01 == 'my_events' ||
//    $uri_segment_01 == 'direct_deposit'
//) {
//    $load_view = 'new';
//}

?>
<?php if (!$load_view) { ?>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <!-- Buttons  -->
                                <div class="row">
                                    <div class="col-sm-12 js-buttons-body"></div>
                                    <br />
                                    <br />
                                    <br />
                                </div>
                                <!-- Table -->
                                <div class="table-responsive table-outer">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Title / Account Type</th>
                                                <th>Routing Number</th>
                                                <th>Account Number</th>
                                                <th>Bank Name</th>
                                                <th>Account List</th>
                                                <th class="col-sm-3">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="js-ddi-body"></tbody>
                                    </table>
                                </div>

                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <br />
                                        <br />
                                        <br />
                                        <p><strong>Disclosures & Authorization:</strong> This authorizes (the “Company”) to send credit entries (and appropriate debit and adjustment entries), electronically or by any other commercially accepted method, to my (our) account(s) indicated below and to other accounts I (we) identify in the future (the “Account”). This authorizes the financial institution holding the Account to post all such entries. I agree that the ACH transactions authorized herein shall comply with all applicable U.S. Law. This authorization will be in effect until the Company receives a written termination notice from myself and has a reasonable opportunity to act on it.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>

<?php } else { ?>
    <?php //$this->load->view('onboarding/bank_details'); ?>
<?php } ?>

<script language="JavaScript" type="text/javascript">
    //
    String.prototype.ucwords = function() {
        str = this.toLowerCase();
        return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
        function(s){ return s.toUpperCase(); });
    };
    //
    const ddiList = <?php echo json_encode($ddi);?>;
    let filo = {};
    let voidCheck = '';
    var dd;
    var ddi;

    //
    $(document).on('click', '.js-add-ddi', (e) => {
        e.preventDefault();
        //
        filo = {};
        voidedCheck = '';
        $('.js-filename').text();
        //
        let rows = `
        <!-- Modal -->
        <div class="modal fade" id="js-dd-add-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #81b431; border-radius: 5px 5px 0 0;">
                        <h4 class="modal-title">Add Direct Deposit Account</h4>
                    </div>
                    <div class="modal-body">
                        ${ getModalRows('-add')}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>`;
        //
        $('#js-dd-add-modal').remove();
        $('body').append(rows);
        $('#js-dd-add-modal').modal('show');
    });
    
    //
    $(document).on('click', '.js-edit', (e) => {
        e.preventDefault();
        //
        $('.js-filename').text();
        //
        const tp = getDD($(e.target).data('sid'));
        dd = tp[0];
        ddi = tp[1];
        voidCheck = dd.voided_cheque;
        filo = {name:dd.voided_cheque, old: true};
        //
        let rows = `
        <!-- Modal -->
        <div class="modal fade" id="js-dd-edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #81b431; border-radius: 5px 5px 0 0;">
                        <h4 class="modal-title">Edit Direct Deposit Account</h4>
                    </div>
                    <div class="modal-body">
                        ${ getModalRows('-edit', dd)}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>`;
        //
        $('#js-dd-edit-modal').remove();
        $('body').append(rows);
        $('#js-dd-edit-modal').modal('show');
    });
    
    //
    $(document).on('click', '.js-view', (e) => {
        e.preventDefault();
        //
        const tp = getDD($(e.target).data('sid'));
        dd = tp[0];
        let vc = dd.voided_cheque.indexOf('base64') !== -1 ? dd.voided_cheque : "<?=AWS_S3_BUCKET_URL;?>"+dd.voided_cheque;
        //
        let rows = `
        <!-- Modal -->
        <div class="modal fade" id="js-dd-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #81b431; border-radius: 5px 5px 0 0;">
                        <h4 class="modal-title">Voided Cheque for <strong>${dd.account_title}</strong></h4>
                    </div>
                    <div class="modal-body">
                        <img width="100%" src="${vc}" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>`;
        //
        $('#js-dd-view-modal').remove();
        $('body').append(rows);
        $('#js-dd-view-modal').modal('show');
    });

    //
    $(document).on('click', '.js-uploader', (e) => {
        //
        e.preventDefault();
        //
        $('.js-image').trigger('click');
    });

    // Trigger when user drop file
    $(document).on('drop', '.js-uploader', function (e) {
        e.stopPropagation();
        e.preventDefault();
        //
        filo = e.originalEvent.dataTransfer.files[0];
        $('.js-filename').text(filo['name']);
        //
        let fr = new FileReader();
        fr.readAsDataURL(e.originalEvent.dataTransfer.files[0]);
        fr.onload = function(e) { 
            voidCheck = this.result;
        };
    });

    //
    $(document).on('change', '.js-image', function (e) {
        e.stopPropagation();
        e.preventDefault();
        filo = e.target.files[0];
        $('.js-filename').text(filo['name']);
        //
        let fr = new FileReader();
        fr.readAsDataURL(e.target.files[0]);
        fr.onload = function(e) { 
            voidCheck = this.result;
        };
    });

    //
    $(document).on('click', '.js-save-dd-add', (e) => {
        e.preventDefault();
        if(ddiList.length == 2){
            alertify.alert('WARNING!', 'You can only add 2 accounts.', () => {});
            return;
        }
        //
        let obj = {};
        obj.title = $('.js-account-title-add').val().trim();
        obj.routingNumber = $('.js-account-routing-add').val().trim();
        obj.accountNumber = $('.js-account-checking-add').val().trim();
        obj.bankName = $('.js-account-bank-add').val().trim();
        obj.accountType = $('.js-account-type-add:checked').val().trim();
        obj.accountPercentage = $('.js-account-percentage').val().trim();
        // obj.accountStatus = $('.js-account-status-add').val().trim();
        obj.accountStatus = '';
        //
        if(obj.title == ''){
            alertify.alert('WARNING!', 'Account title is required.', () => {});
            return;
        } else if(obj.routingNumber == ''){
            alertify.alert('WARNING!', 'Routing number is required.', () => {});
            return;
        } else if(obj.accountNumber == ''){
            alertify.alert('WARNING!', 'Account number is required.', () => {});
            return;
        } else if(obj.bankName == ''){
            alertify.alert('WARNING!', 'Bank name is required.', () => {});
            return;
        } else if(obj.accountType == ''){
            alertify.alert('WARNING!', 'Account type is required.', () => {});
            return;
        } else if(obj.accountPercentage == ''){
            alertify.alert('WARNING!', 'Account percentage / dollar amount is required.', () => {});
            return;
        } else if(filo.name === undefined){
            alertify.alert('WARNING!', 'Voided check is required.', () => {});
            return;
        } else{
            //
            let fp = new FormData();
            fp.append('user_type', "<?=$users_type;?>");
            fp.append('user_sid', "<?=$users_sid;?>");
            $.each(obj, (i, v) => { fp.append(i, v); });
            fp.append('file', filo);
            //
            $.ajax({
                type: 'POST',
                url: "<?=base_url("direct_deposit/add");?>",
                processData: false,
                contentType: false,
                data: fp,
                success: (resp) => {
                    if(resp == 'failed'){
                        alertify.alert("WARNING!", "Something went wrong while adding. Please, try again in a few seconds.", () => {});
                        return;
                    }
                    alertify.alert("SUCCESS!", "Direct deposit account is successfully added.", () => {});
                    ddiList.unshift({
                        sid: resp,
                        users_type : "<?=$users_type;?>",
                        users_sid : "<?=$users_sid;?>",
                        account_title : obj['title'],
                        routing_transaction_number : obj['routingNumber'],
                        account_number : obj['accountNumber'],
                        financial_institution_name : obj['bankName'],
                        account_type : obj['accountType'],
                        account_percentage: obj.accountPercentage,
                        voided_cheque: voidCheck,
                        voided_cheque_name: filo['name'],
                        account_status : ddList.length === 0 ? 'primary' : 'secondary'
                    });
                    makeView();
                    $('#js-dd-add-modal').modal('hide');
                    return;
                } 
            });
        }
    });

    //
    $(document).on('click', '.js-save-dd-edit', (e) => {
        e.preventDefault();
        //
        let obj = {};
        obj.title = $('.js-account-title-edit').val().trim();
        obj.routingNumber = $('.js-account-routing-edit').val().trim();
        obj.accountNumber = $('.js-account-checking-edit').val().trim();
        obj.bankName = $('.js-account-bank-edit').val().trim();
        obj.accountType = $('.js-account-type-edit:checked').val().trim();
        obj.accountPercentage = $('.js-account-percentage').val().trim();
        // obj.accountStatus = $('.js-account-status-edit').val().trim();
        obj.accountStatus = '';
        //
        if(obj.title == ''){
            alertify.alert('WARNING!', 'Account title is required.', () => {});
            return;
        } else if(obj.routingNumber == ''){
            alertify.alert('WARNING!', 'Routing number is required.', () => {});
            return;
        } else if(obj.accountNumber == ''){
            alertify.alert('WARNING!', 'Account number is required.', () => {});
            return;
        } else if(obj.bankName == ''){
            alertify.alert('WARNING!', 'Bank name is required.', () => {});
            return;
        } else if(obj.accountType == ''){
            alertify.alert('WARNING!', 'Account type is required.', () => {});
            return;
        } else if(obj.accountPercentage == ''){
            alertify.alert('WARNING!', 'Account percentage / dollar amount is required.', () => {});
            return;
        } else if(filo.name === undefined){
            alertify.alert('WARNING!', 'Voided check is required.', () => {});
            return;
        } else{
            //
            let fp = new FormData();
            fp.append('user_type', "<?=$users_type;?>");
            fp.append('user_sid', "<?=$users_sid;?>");
            $.each(obj, (i, v) => { fp.append(i, v); });
            fp.append('file', filo);
            fp.append('sid', dd.sid);
            //
            $.ajax({
                type: 'POST',
                url: "<?=base_url("direct_deposit/edit");?>",
                processData: false,
                contentType: false,
                data: fp,
                success: (resp) => {
                    if(resp == 'failed'){
                        alertify.alert("WARNING!", "Something went wrong while updating. Please, try again in a few seconds.", () => {});
                        return;
                    }
                    alertify.alert("SUCCESS!", "Direct deposit account is successfully updated.", () => {});
                    //
                    ddiList[ddi]['account_title'] = obj.title;
                    ddiList[ddi]['routing_transaction_number'] = obj.routingNumber;
                    ddiList[ddi]['account_number'] = obj.accountNumber;
                    ddiList[ddi]['account_type'] = obj.accountType;
                    ddiList[ddi]['financial_institution_name'] = obj.bankName;
                    ddiList[ddi]['account_percentage'] = obj.accountPercentage;
                    ddiList[ddi]['voided_cheque'] = voidCheck;
                    ddiList[ddi]['voided_cheque_name'] = filo['name'];
                    //
                    makeView();
                    $('#js-dd-edit-modal').modal('hide');
                    return;
                } 
            });
        }
    });

    //
    $(document).on('click', '.js-make-primary', (e) => {
        e.preventDefault();
        alertify.confirm('Do you really want to make this account primary?', () => {
            $.get(`<?=base_url('direct_deposit/update_primary');?>/${$(e.target).data('sid')}`, (resp) => {
                alertify.alert('SUCCESS!', 'Successfully set this account to Primary.', () => {
                    window.location.reload();
                });
            })
        }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        }).set('title', 'Confirm!');
    });

    //
    makeView();

    //
    function makeView(){
        //
        let trs = '';
        //
        if(ddiList.length === 0){
            trs = `
            <tr>
                <td colspan="${$('thead th').length}"><p class="text-center alert alert-info">No Direct Deposits accounts found.</p></td>
            </tr>`;
        } else{
            ddiList.map((dd, i) => {
                trs += `
                    <tr>
                        <td>
                            <p>${dd.account_title.ucwords()}</p>
                            <p>${dd.account_type.ucwords()}</p>
                        </td>
                        <td>${dd.routing_transaction_number.ucwords()}</td>
                        <td>${dd.account_number.ucwords()}</td>
                        <td>${dd.financial_institution_name.ucwords()}</td>
                        <td>${dd.account_status.ucwords()}</td>
                        <td>
                            <a href="javascript:void(0)" class="btn btn-success js-edit" data-sid="${dd.sid}" title="Edit account"><i class="fa fa-pencil"data-sid="${dd.sid}"></i></a>
                            ${dd.account_status != 'primary' ? `<a href="javascript:void(0)" class="btn btn-success js-make-primary" data-sid="${dd.sid}" title="Make Primary (Account 1)"><i class="fa fa-shield"data-sid="${dd.sid}"></i></a>` : ''}
                            <a href="javascript:void(0)" class="btn btn-success js-view" data-sid="${dd.sid}" title="View voided cheque"><i class="fa fa-eye"data-sid="${dd.sid}"></i></a>
                        </td>
                    </tr>
                `;
            });
        }

        //
        $('#js-ddi-body').html(trs);
        //
        generateButtons();
    }

    //
    function generateButtons(){
        //
        let btns = '';
        if(ddiList.length < 2){
            btns = `<a class="btn btn-success pull-right js-add-ddi"> Add Direct Deposit Account</a>`;
        }
        if(ddiList.length !== 0){
            btns += `<a href="<?=base_url('direct_deposit/pd/'.($users_type).'/'.($users_sid).'/'.($company_sid).'/print');?>" class="btn btn-success" target="_blank" style="margin-right: 10px;">Print Direct Deposit Form</a>`;
            btns += `<a href="<?=base_url('direct_deposit/pd/'.($users_type).'/'.($users_sid).'/'.($company_sid).'/download');?>" class="btn btn-success" target="_blank" >Download Direct Deposit Form</a>`;
        }
        //
        $('.js-buttons-body').html(btns);
    }

    //
    function getModalRows(typo, data) {
        //
        let html = '';
        //
        html += `
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label>Account Title <span class="cs-required">*</span></label>
                    <div>
                        <input type="text" class="form-control js-account-title${typo}" value="${data !== undefined ? data.account_title : ''}" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>Routing/Transaction Number <span class="cs-required">*</span></label>
                    <div>
                        <input type="text" class="form-control js-account-routing${typo}"  value="${data !== undefined ? data.routing_transaction_number : ''}" />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label>Checking/Saving Account Number <span class="cs-required">*</span></label>
                    <div>
                        <input type="text" class="form-control js-account-checking${typo}"  value="${data !== undefined ? data.account_number : ''}" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>Financial Institution (Bank) Name <span class="cs-required">*</span></label>
                    <div>
                        <input type="text" class="form-control js-account-bank${typo}"  value="${data !== undefined ? data.financial_institution_name : ''}" />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label>Account Type <span class="cs-required">*</span></label> <br />
                    <label class="control control--radio">
                        Checking
                        <input type="radio" class="js-account-type${typo}" value="checking" name="account_type${typo}" ${data !== undefined && data.account_type == 'checking' ? 'checked="true"' : ''} />
                        <div class="control__indicator"></div>
                    </label> &nbsp;&nbsp;&nbsp;&nbsp;
                    <label class="control control--radio">
                        Savings
                        <input type="radio" class="js-account-type${typo}" value="saving" name="account_type${typo}" ${data !== undefined && data.account_type == 'saving' ? 'checked="checked"': ''} />
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="col-sm-6">
                    <label>Percentage or dollar amount to be deposited to this account <span class="cs-required">*</span></label> <br />
                    <input type="text" class="form-control js-account-percentage"  value="${data !== undefined ? data.account_percentage : ''}" />
                </div>
                <!-- <div class="col-sm-6">
                    <label>Account</label> <br />
                    <select class="form-control js-account-status${typo}">
                        <option value="0">[Select Account]</option>
                        <option value="1">Primary</option>
                        <option value="2">Secondary</option>
                    </select>
                </div> -->
            </div>
            <br />
        </div>

        <div class="form-group">
            <label>Voided Cheque <span class="cs-required">*</span></label>
            <div class="cs-uploader js-uploader" data-type="${typo}">
                <div class="cs-uploader-text">
                    <p class="text-center">Click Here / Drop File </p>
                    <p class="js-filename">${data !== undefined ? (data.voided_cheque.indexOf('base64') !== -1 ? data.voided_cheque_name : data.voided_cheque ) : ''}</p>
                </div>
            </div>
            <input type="file" class="js-image" style="display: none;" />
        </div>

        <hr />

        <div class="form-group">
            <p><strong>Disclosures & Authorization:</strong> This authorizes (the “Company”) to send credit entries (and appropriate debit and adjustment entries), electronically or by any other commercially accepted method, to my (our) account(s) indicated below and to other accounts I (we) identify in the future (the “Account”). This authorizes the financial institution holding the Account to post all such entries. I agree that the ACH transactions authorized herein shall comply with all applicable U.S. Law. This authorization will be in effect until the Company receives a written termination notice from myself and has a reasonable opportunity to act on it.</p>
        </div>
        
        <div class="form-group">
            <div class="col-sm-6 col-sm-offset-3">
                <button type="button" class="btn btn-success form-control js-save-dd${typo}">I CONSENT</button>
            </div>
        </div>
        `;

        //
        return html;
    }

    //
    function getDD(sid){
        let 
        i = 0,
        il = ddiList.length;
        //
        for(i; i < il; i++){
            if(ddiList[i]['sid'] == sid) return [ ddiList[i], i ];
        }
        //
        return false;
    }
</script>

<style>
    .cs-required{
        font-weight: bolder;
        font-size: 16px;
        color: #cc1100;
    }

    .cs-uploader{
        position: relative;
        min-height: 200px;
        border: 2px dashed #ccc;
        cursor: pointer;
    }
    .cs-uploader .cs-uploader-text{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
        text-align: center;
    }
    .cs-uploader .cs-uploader-text p{
        font-weight: bolder;
        font-size: 20px;
    }
    .cs-uploader .cs-uploader-text p:nth-child(2){
        font-size: 14px;
        color: #cc1100;
    }
    .cs-uploader img{
        position: absolute;
        left: 0;
        top: 0;
        opacity: .7;
        height: 200px;
        font-weight: bolder;
    }
</style>