<div class="row">
    <div class="col-xs-12">
        <label>Download Required</label>
        <div class="hr-select-dropdown">
            <select class="invoice-fields" name="download_required">
                <option <?php if (isset($document_info['download_required']) && $document_info['download_required'] == '0') echo 'selected'; ?> value="0"> No </option>
                <option <?php if (isset($document_info['download_required']) && $document_info['download_required'] == '1') echo 'selected'; ?> value="1"> Yes </option>
            </select>
        </div>
        <div class="help-text">
            Enable the Download Required, if you need the Employee or Onboarding Candidate to download this form.
        </div>
    </div>
</div>