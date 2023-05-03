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
                <div class="">
                    <span class="pull-left">
                        <h3 class="">Payroll Document</h3>
                    </span>
                </div>
                <div>
                    <div class="row">
                        <div class="col-xs-12">
                            <iframe src="<?php echo $formData['Form']['document_url']; ?>" class="uploaded-file-preview js-hybrid-iframe" style="width:100%; height:80em;" frameborder="0"></iframe>
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
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <button <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?> onclick="func_save_e_signature();" type="button" class="btn blue-button break-word-text disabled_consent_btn" <?php echo $consent == 1 ? 'disabled="disabled"' : ''; ?>><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>