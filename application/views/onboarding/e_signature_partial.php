<form id="form_e_signature" enctype="multipart/form-data" method="post" action="<?php echo $save_post_url; ?>">

    <input type="hidden" id="perform_action" name="perform_action" value="sign_document" />

    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />

    <input type="hidden" id="user_type" name="user_type" value="<?php echo $users_type; ?>" />

    <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $users_sid; ?>" />

    <input type="hidden" id="ip_address" name="ip_address" value="<?php echo getUserIP(); ?>" />

    <input type="hidden" id="user_agent" name="user_agent" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />

    <input type="hidden" id="first_name" name="first_name" value="<?php echo $first_name; ?>" />

    <input type="hidden" id="last_name" name="last_name" value="<?php echo $last_name; ?>" />

    <input type="hidden" id="email_address" name="email_address" value="<?php echo $email; ?>" />

    <input type="hidden" id="signature_timestamp" name="signature_timestamp" value="<?php echo date('d/m/Y H:i:s'); ?>" />

    <input type="hidden" id="documents_assignment_sid" name="documents_assignment_sid" value="<?php echo $documents_assignment_sid; ?>" />



    <?php $active_signature = isset($e_signature_data['active_signature']) ? $e_signature_data['active_signature'] : 'typed'; ?>

    <?php $signature = isset($e_signature_data['signature']) ? $e_signature_data['signature'] : '';?>



    <?php if(isset($e_signature_data['active_signature']) && $e_signature_data['active_signature'] == 'typed') { ?>

        <div id="type_signature" style="min-height: 250px;" class="field-row autoheight">

            <input readonly="readonly" data-rule-required="true" type="text" class="signature-field" name="signature" id="signature" value="<?php echo set_value('signature', $signature); ?>"  placeholder="John Doe" />

        </div>

    <?php } else if(isset($e_signature_data['active_signature']) && $e_signature_data['active_signature'] == 'drawn') { ?>

        <div class="img-full">

            <img src="<?php echo isset($e_signature_data['signature_bas64_image']) && !empty($e_signature_data['signature_bas64_image']) ? $e_signature_data['signature_bas64_image'] : ''; ?>"  />

        </div>

    <?php } ?>



    <input type="hidden" id="active_signature" name="active_signature" value="<?php echo $active_signature; ?>" />

    <input type="hidden" id="signature" name="signature" value="<?php echo $signature; ?>" />

    <input type="hidden" id="signature_bas64_image" name="signature_bas64_image" value="<?php echo isset($e_signature_data['signature_bas64_image']) && !empty($e_signature_data['signature_bas64_image']) ? $e_signature_data['signature_bas64_image'] : ''; ?>" />

    <hr />

    <div class="row">

        <div class="col-xs-12 text-justify">

            <p>

                <strong>CONSENT AND NOTICE REGARDING ELECTRONIC COMMUNICATIONS</strong> FOR <strong>AutomotoSocial LLC / <?php echo STORE_NAME; ?></strong><br />

            </p>

            <p>1. Electronic Signature Agreement.</p>

            <p>

                By selecting the "I Accept" button, you are signing this Agreement electronically. You agree your electronic signature is the legal equivalent of your manual signature on this Agreement. By selecting "I Accept" you consent to be legally bound by this Agreement's terms and conditions.

                You further agree that your use of a key pad, mouse or other device to select an item, button, icon or similar act/action, or to otherwise

                provide AutomotoSocial LLC / <?php echo STORE_NAME; ?>, or in accessing or making any transaction regarding any agreement, acknowledgement, consent terms, disclosures or conditions constitutes your signature (hereafter referred to as "E-Signature"), acceptance and agreement as if actually signed by

                you in writing.

                You also agree that no certification authority or other third party verification

                is necessary to validate your E-Signature and that the lack of such certification or third party verification will not in any way affect the enforceability of your E-Signature or any resulting contract between you and AutomotoSocial LLC / <?php echo STORE_NAME; ?>. You also represent that

                you are authorized to enter into this Agreement for all persons who own or are authorized to access any of your accounts and that such persons will be bound by the terms of this Agreement.

                You further agree that each use of your E-Signature in obtaining a AutomotoSocial LLC / <?php echo STORE_NAME; ?> service constitutes your agreement to be bound by the terms and conditions of the AutomotoSocial LLC / <?php echo STORE_NAME; ?> Disclosures and Agreements as they exist on the date of your

                E-Signature

            </p>

        </div>

    </div>



    <div class="row">

        <div class="col-xs-12">

            <?php $consent = isset($e_signature_data['user_consent']) ? $e_signature_data['user_consent'] : 0; ?>

            <label class="control control--checkbox">

                I Consent and Accept Electronic Signature Agreement

                <input <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?>  <?php echo set_checkbox('user_consent', 1, $consent == 1); ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" <?php echo $user_consent == 1 ? 'checked="checked"' : ''; ?> />

                <div class="control__indicator"></div>

            </label>

        </div>

    </div>

    <hr />

    <?php if($signed_flag == false) { ?>

    <div class="row">

        <div class="col-lg-12 text-center">

            <button <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?> onclick="func_save_e_signature();" type="button" class="btn btn-success break-word-text" <?php echo $user_consent == 1 ? 'disabled="disabled"' : ''; ?>>I Consent and Accept Electronic Signature Agreement</button>

        </div>

    </div>

    <?php } ?>

</form>



<script>

    $(document).ready(function () {

        $('.active_signature').on('click', function () {

            var selected = $(this).val();



            if (selected == 'drawn') {

                $('#type_signature').hide();

                $('#draw_signature').show();

            } else if (selected == 'typed') {

                $('#type_signature').show();

                $('#draw_signature').hide();

            }

        });



        $('.active_signature:checked').trigger('click');



        $('#form_e_signature').validate({

            errorClass: 'text-danger',

            errorElement: 'p',

            errorElementClass: 'text-danger'

        });

    });



    function func_save_e_signature() {

        if ($('#form_e_signature').valid()) {

            alertify.confirm(

                'Are you Sure?',

                'Are you sure you want to Consent And Accept Electronic Signature Agreement?',

                function () {

                    $('#form_e_signature').submit();

                },

                function () {

                    alertify.error('Cancelled!');

                }).set('labels', {ok: 'I Consent and Accept!', cancel: 'Cancel'});

        }

    }

</script>