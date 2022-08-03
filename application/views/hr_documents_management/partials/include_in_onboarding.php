<div class="row">
    <div class="col-xs-12 margin-top">
        <label>Include in Onboarding<span class="staric">*</span></label>
        <div class="hr-select-dropdown">
            <select class="invoice-fields" name="onboarding">
                <option <?php
                        if (isset($document_info['onboarding']) && $document_info['onboarding'] == '0') echo 'selected'; ?> value="0"> No </option>
                <option <?php
                        if (isset($document_info['onboarding']) && $document_info['onboarding'] == '1') echo 'selected'; ?> value="1"> Yes </option>
            </select>
        </div>
        <div class="help-text">
            This document will be available to select or send to new hires as part of the Onboarding wizard.
        </div>
    </div>
</div>