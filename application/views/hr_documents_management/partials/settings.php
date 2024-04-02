<?php
$id_checkbox = isset($id_checkbox) ? $id_checkbox : 'setting_is_confidential';
?>
<!-- Document Settings -->
<div class="panel <?= isset($cl) ? $cl : 'panel-default'; ?>">
    <div class="panel-heading"><strong>Document Settings</strong></div>
    <div class="panel-body">
        <!-- Confidential -->
        <div class="row">
            <div class="col-sm-12">
                <label class="control control--checkbox">
                    Confidential Document <span class="text-danger">(The document will not be visible to the employee/applicant)</span>
                    <input type="checkbox" name="setting_is_confidential" <?= $is_confidential == 1 ? 'checked' : ''; ?> id="<?= $id_checkbox ?>" />
                    <div class="control__indicator"></div>
                </label>
            </div>

            <div class="col-sm-12 confidentialSelectedEmployeesdiv" style="margin-top: 20px;" id="confidentialSelectedEmployeesdiv">
                <hr>
                <p class="text-danger"><strong><em>Selected employees will have visibility to this document.</em></strong></p>
                <label>Employee(s)
                </label>

                <?php
                $confidentialSelectedEmployees = array();
                if ($document_info['confidential_employees'] != -1 && $document_info['confidential_employees'] != null) {
                    $confidentialSelectedEmployees = explode(',', $document_info['confidential_employees']);
                }
                ?>

                <select multiple="true" name="confidentialSelectedEmployees[]" class="assignSelectedEmployees" id="confidentialSelectedEmployees">
                    <option value="-1" <?php if ($document_info['confidential_employees'] == -1) {
                                            echo  "selected";
                                        } ?>>All</option>
                    <?php foreach ($employeesList as $key => $employee) { ?>
                        <option <?= in_array($employee['sid'], $confidentialSelectedEmployees) ? "selected" : "" ?> value="<?= $employee['sid']; ?>"><?= remakeEmployeeName($employee); ?></option>

                    <?php } ?>
                </select>
            </div>

        </div>
        <?php if (isset($btn)) : ?>
            <div class="">
                <div class="col-m-12 text-right">
                    <button class="btn btn-success jsUpdateDocumentSetting">
                        <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update Setting
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($btn)) : ?>
    <script>
        $(function DocumentSetting() {
            //
            var xhr = null;
            //
            $('.jsUpdateDocumentSetting').click(function(event) {
                //
                event.preventDefault();
                var confidentialEmployees = $('#confidentialSelectedEmployees').val() || '';
                //
                if (xhr !== null) {
                    return;
                }
                //
                var _this = $(this);
                //
                _this.text('Updating...');
                //
                var obj = {
                    document_aid: <?= $documentAssignedId; ?>,
                    is_confidential: $('[name="setting_is_confidential"]').prop('checked') ? 'on' : 'off',
                    confidentialSelectedEmployees: confidentialEmployees.toString()
                };
                //
                xhr = $.ajax({
                        method: "POST",
                        url: "<?= rtrim(base_url(), '/'); ?>/document_setting",
                        data: obj
                    })
                    .success(function(response) {
                        xhr = null;
                        return alertify.alert(
                            'Success!',
                            'Hurray! You have successfully updated the confidential status.',
                            function() {
                                window.location.reload();
                            }
                        );
                    })
                    .fail(function() {
                        xhr = null;
                        _this.html('<i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update Setting');
                        return alertify.alert(
                            'Error!',
                            'Oops! Something went wrong. Please try again in a few moments.',
                            function() {}
                        );
                    });

            });
        });
    </script>
<?php endif; ?>