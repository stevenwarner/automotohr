<div class="row">
    <div class="col-xs-12">
        <div class="hr-box">
            <div class="hr-box-header">
                <label class="control control--checkbox" style="margin-bottom: 16px;">
                    <input type="checkbox" name="jsGroupAll" id="jsGroupAll" />
                    <div class="control__indicator"></div>
                </label>
                <script>
                    $('#jsGroupAll').click(function() {
                        $('input[name="document_group_assignment[]"]').prop('checked', $(this).prop('checked'));
                    });
                </script>
                <strong>
                    Document Group Management:</strong>
            </div>
            <div class="hr-innerpadding">
                <div class="universal-form-style-v2">
                    <?php $assigned_documents = array(); ?>
                    <?php foreach ($document_groups as $key => $document) { ?>
                        <?php $cat_name = 'documents'; ?>
                        <div class="col-xs-6">
                            <label class="control control--checkbox font-normal">
                                <?php echo $document['name']; ?>
                                <input class="disable_doc_checkbox" name="document_group_assignment[]" type="checkbox" value="<?php echo $document['sid']; ?>" <?php echo in_array($document['sid'], $pre_assigned_groups) ? 'checked="checked"' : ''; ?>>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>