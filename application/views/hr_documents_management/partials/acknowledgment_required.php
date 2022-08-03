<div class="row">
    <div class="col-xs-12">
        <label>Acknowledgment Required</label>
        <div class="hr-select-dropdown">
            <select class="invoice-fields" name="acknowledgment_required">
                <option <?php
                        if (isset($document_info['acknowledgment_required']) && $document_info['acknowledgment_required'] == '0') echo 'selected'; ?> value="0"> No </option>
                <option <?php
                        if (isset($document_info['acknowledgment_required']) && $document_info['acknowledgment_required'] == '1') echo 'selected'; ?> value="1"> Yes </option>
            </select>
        </div>
        <div class="help-text">
            Enable the Acknowledgment Requirement, if you need a confirmation that a Document has been received by the Employee or Onboarding Candidate.
        </div>
    </div>
</div>