<div class="row">
    <div class="col-xs-12">
        <div class="hr-innerpadding">
            <form id="func_insert_new_useful_link" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                <input type="hidden" id="perform_action" name="perform_action" value="onboarding_disclosure" />
                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                <div class="universal-form-style-v2">
                    <ul>
                        <li class="form-col-100 autoheight">
                            <?php $field_id = 'disclosure'; ?>
                            <?php echo form_label('', $field_id); ?>
                            <?php echo form_textarea($field_id, $onboarding_disclosure, 'class="invoice-fields autoheight ckeditor" id="' . $field_id . '"'); ?>
                            <?php echo form_error($field_id); ?>
                        </li>
                    </ul>
                    <button type="submit" class="btn btn-success">Update Disclosure</button>
                </div>
            </form>
        </div>
    </div>
</div>