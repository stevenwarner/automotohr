<div class="hr-innerpadding text-center">
    <div class="row">
        <div class="col-xs-12">
            <span class="text-center">Are you sure you want to <?php echo $attendance_status; ?>?</span>

        </div>
        <hr />
        <div class="col-xs-12">
            <span class="text-center">
                <label class="control control--checkbox">
                    Yes <?php echo $attendance_status_message; ?>
                    <input class="" name="confirm_checkbox" id="confirm_checkbox" type="checkbox" />
                    <div class="control__indicator"></div>
                </label>
            </span>
        </div>
        <hr />
        <br />
        <br />
        <div class="col-xs-12">
            <span class="text-center">
                <button onclick="func_form_confirm('<?php echo $form_id; ?>');" class="btn btn-success"><?php echo $attendance_status_message; ?></button>
            </span>
        </div>
    </div>
</div>