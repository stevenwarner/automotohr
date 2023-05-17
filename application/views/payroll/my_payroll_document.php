<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br />
        <!-- Main Content Area -->
        <div class="row">
            <!-- Sidebar -->
            <!--  -->
            <div class="col-sm-12 col-xs-12">
                <div>
                    <?php if ($formInfo['signed_by_ip_address']) { ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-success">
                                    <p class="csF18">
                                        <i class="fa fa-check-circle-o" aria-hidden="true"></i> <?= $formInfo['title'] ?> <strong class="text-success">COMPLETED</strong>
                                    </p>
                                    <hr>
                                    <p class="csF14">
                                        <strong>IP Address:</strong> <?= $formInfo['signed_by_ip_address']; ?>
                                    </p>
                                    <p class="csF14">
                                        <strong>Signature Text:</strong> <?= $formInfo['signature_text']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-blue">
                                <div class="panel-heading incident-panal-heading">
                                    <b><?php echo $formInfo['title']; ?></b>
                                </div>
                                <div class="panel-body">
                                    <iframe src="<?php echo $formData['Form']['document_url']; ?>" class="uploaded-file-preview js-hybrid-iframe" style="width:100%; height:80em;" frameborder="0"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($formInfo['requires_signing'] == 1 && $formInfo['is_signed'] == 0) { ?>
                        <form id="user_consent_form" enctype="multipart/form-data" method="post" action="">
                            <input type="hidden" name="perform_action" value="sign_document" />
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel panel-blue">
                                        <div class="panel-heading incident-panal-heading">
                                            <b>Type Signature</b>
                                        </div>
                                        <div class="panel-body">
                                            <p class="domain_message">Hint: Please type your First and Last Name (<small>Max characters limit is 30</small>)</p>
                                            <input data-rule-required="true" type="text" class="form-control" name="signature" id="jsemployeeSignature" maxlength="30" value="<?php echo set_value('signature', $signature); ?>" placeholder="John Doe" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 text-justify">
                                    <?php
                                    echo '<p>' . str_replace("{{company_name}}", $company_name, SIGNATURE_CONSENT_HEADING) . '</p>';
                                    echo '<p>' . SIGNATURE_CONSENT_TITLE . '</p>';
                                    echo '<p>' . str_replace("{{company_name}}", $company_name, SIGNATURE_CONSENT_DESCRIPTION) . '</p>';
                                    ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <?php $consent = isset($document['user_consent']) ? $document['user_consent'] : 0; ?>
                                    <label class="control control--checkbox">
                                        <?php echo SIGNATURE_CONSENT_CHECKBOX; ?>
                                        <input <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?> <?php echo set_checkbox('user_consent', 1, $consent == 1); ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" <?php echo $consent == 1 ? 'checked="checked"' : ''; ?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <button onclick="func_save_e_signature();" type="button" class="btn blue-button break-word-text disabled_consent_btn"><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#user_consent_form').validate({
        errorClass: 'text-danger',
        errorElement: 'p',
        errorElementClass: 'text-danger'
    });

    function func_save_e_signature() {
        if ($('#user_consent_form').valid()) {
            var employeeSignature = $('#jsemployeeSignature').val();

            if (!employeeSignature.length) {
                alertify.alert("Warning", "Please add you name.");
                return
            }


            alertify.confirm(
                'Are you Sure?',
                'Are you sure you want to Consent And Accept Electronic Signature Agreement?',
                function() {
                    $('#user_consent_form').submit();
                },
                function() {
                    alertify.error('Cancelled!');
                }).set('labels', {
                ok: 'I Consent and Accept!',
                cancel: 'Cancel'
            });

        }
    }
</script>