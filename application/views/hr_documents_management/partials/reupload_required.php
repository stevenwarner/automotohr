<div class="row">
    <div class="col-xs-12">
        <label>Re-Upload Required</label>
        <div class="hr-select-dropdown">
            <select class="invoice-fields" name="signature_required">
                <option <?php if (isset($document_info['signature_required']) && $document_info['signature_required'] == '0') echo 'selected'; ?> value="0"> No </option>
                <option <?php if (isset($document_info['signature_required']) && $document_info['signature_required'] == '1') echo 'selected'; ?> value="1"> Yes </option>
            </select>
        </div>
        <div class="help-text">
            Enable the Re-Upload Required option, if you need the Employee or Onboarding Candidate to complete and Sign the document with a pen, and then upload the completed document into the system.
        </div>
    </div>
</div>