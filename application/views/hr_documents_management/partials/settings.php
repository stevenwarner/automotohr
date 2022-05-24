<!-- Document Settings -->
<div class="panel <?=isset($cl) ? $cl : 'panel-default';?>">
    <div class="panel-heading"><strong>Document Settings</strong></div>
    <div class="panel-body">
        <!-- Confidential -->
        <div class="row">
            <div class="col-sm-12">
                <label class="control control--checkbox">
                    Confidential Document <span class="text-danger">(The document will not be visible to the employee/applicant)</span>
                    <input type="checkbox" name="setting_is_confidential" <?=$is_confidential == 1 ? 'checked' : '';?>  />
                    <div class="control__indicator"></div>
                </label>
            </div>
        </div>
        <?php if(isset($btn)): ?>
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

<?php if(isset($btn)): ?>
    <script>
        $(function DocumentSetting(){
            //
            var xhr = null;
            //
            $('.jsUpdateDocumentSetting').click(function(event){
                //
                event.preventDefault();
                //
                if(xhr !== null){
                    return;
                }
                //
                var _this = $(this);
                //
                _this.text('Updating...');
                //
                var obj = {
                    document_aid: <?=$documentAssignedId;?>,
                    is_confidential: $('[name="setting_is_confidential"]').prop('checked') ? 'on' : 'off'
                };
                //
                xhr = $.ajax({
                    method: "POST",
                    url: "<?=rtrim(base_url(), '/');?>/document_setting",
                    data: obj
                })
                .success(function(response){
                    xhr = null;
                    return alertify.alert(
                        'Success!',
                        'Hurray! You have successfully updated the confidential status.',
                        function(){
                            window.location.reload();
                        }
                    );
                })
                .fail(function(){
                    xhr = null;
                    _this.html('<i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update Setting');
                    return alertify.alert(
                        'Error!',
                        'Oops! Something went wrong. Please try again in a few moments.',
                        function(){}
                    );
                });

            });
        });
    </script>
<?php endif; ?>