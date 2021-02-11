<?php if($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) { ?>
<!-- Modal -->
<div class="modal fade" id="jsCategoryManagerModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #81b431; color: #fff;">
                <h5 class="modal-title"><strong>Manage Category</strong></h5>
            </div>
            <div class="modal-body" style="min-height: 400px;">
                <div class="cs-loader jsCategoryManagerLoader">
                    <i class="fa fa-circle-o-notch fa-spin fa-5x"></i>
                </div>
                <!--  -->
                <form action="javascript:void(0)">
                    <div class="form-group">
                        <div class="alert alert-info">
                            <p style="font-size: 14px;">
                                <strong>Note: </strong> You can assign this document to a single category or to multiple categories.
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Categories</label>
                        <div>
                            <select id="jsCategoryManagerSelect" multiple></select>
                        </div>
                        <!--  -->
                        <p><strong id="jsCategoryManagerSelected">0</strong> Categories selected.</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success jsAddNewCategoryBTN">Add New Category</button>
                <button type="button" class="btn btn-success jsCategoryManagerSaveBTN">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="jsAddNewCategoryModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #81b431; color: #fff;">
                <h5 class="modal-title"><strong>Add Category</strong></h5>
            </div>
            <div class="modal-body" style="min-height: 400px;">
                <div class="cs-loader jsCategoryNewManagerLoader">
                    <i class="fa fa-circle-o-notch fa-spin fa-5x"></i>
                </div>
                <input type="hidden" name="perform_action" id="category_perform_action" value="add_document_category" />
                <input type="hidden" name="ip_address" id="ip_address" value="<?php echo getUserIP(); ?>" />

                <div class="form-group autoheight">
                    <label for="name">Name<span class="staric">*</span></label>
                    <input type="text" name="name" id="category_name" class="form-control" value="">
                </div>
                <div class="form-group autoheight">
                    <label for="description">Description</label>
                    <textarea name="description" cols="40" rows="10" class="form-control ckeditor" id="category_description" style="visibility: hidden; display: none;"></textarea>
                </div>  
                <div class="form-group autoheight">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" id="category_sort_order" value="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success jsAddCategorySaveBTN">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .cs-loader{
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        width: 100%;
        text-align: center;
        z-index: 1;
        background-color: #fff;
    }
    .cs-loader i{
        position: relative;
        top: 50%;
        transform: translateY(-50%);
        color: #81b431;
    }
    .jsCategoryManagerBTN{ display: block; }
    .jsAddNewCategoryBTN{ float: left; }
</style>

<script>
    $(function(){
        let 
        companySid = <?=$company_sid;?>,
        userSid = <?=$user_sid;?>,
        userType = "<?=$user_type;?>",
        obj = {},
        xhr = null,
        catgeories = [];

        //
        getAllCategories();

        //
        $('.jsCategoryManagerBTN').click(function(e){
            //
            e.preventDefault();
            // When no categories are found
            // TODO:
            // Show create category popup
            if(catgeories.length === 0){
                //
                obj = $(this).data();
                //
                alertify.confirm(
                    'Note!',
                    'The system is unable to find any categories. Please, add at least one category before this action.<br><br><b>Do you want to add a new Category?</b>',
                    function () {
                        $('#category_name').val('');
                        CKEDITOR.instances.category_description.setData('');
                        $('#category_sort_order').val('');
                        $('#jsAddNewCategoryModal').modal('show');
                        $('.jsCategoryNewManagerLoader').hide();
                    },
                    function () {
                        
                    }
                ).set('labels', {ok: 'yes', cancel: 'No'});
                //
                return;
            }
            //
            obj = $(this).data();
            //
            $('#jsCategoryManagerModal').modal('show');
            //
            $.get(
                `<?=base_url('category_manager');?>/single/${obj.sid}/${obj.asid}`,
                (resp) => {
                    $('#jsCategoryManagerSelect').select2('val', resp);
                    $('.jsCategoryManagerLoader').fadeOut();
                }
            )
        });

        //
        $('.jsAddNewCategoryBTN').click(function(e){
            //
            $('#jsCategoryManagerModal').modal('hide');
            //
            setTimeout(() => {
                $('#category_name').val('');
                CKEDITOR.instances.category_description.setData('');
                $('#category_sort_order').val('');
                $('#jsAddNewCategoryModal').modal('show');
                $('.jsCategoryNewManagerLoader').hide();
            }, 400);
        });
        
        //
        $(document).on('hide.bs.modal', '#jsAddNewCategoryModal', () => {
            if(catgeories.length !== 0) $(`.jsCategoryManagerBTN[data-sid="${obj.sid}"][data-asid="${obj.asid}"]`).click();
        });

        //
        $('.jsAddCategorySaveBTN').click(function(e){
            var category_perform_action = $('#category_perform_action').val();
            var ip_address              = $('#ip_address').val();
            var category_name           = $('#category_name').val();
            var category_description    = CKEDITOR.instances.category_description.getData();
            var category_sort_order     = $('#category_sort_order').val();

            // if (category_name != '' && category_description != '' && category_sort_order != '') {
            if (category_name != '') {
                var add_category_url      = '<?php echo base_url('hr_documents_management/add_new_category_from_cm'); ?>';
                var form_data           = new FormData();
                form_data.append('perform_action', category_perform_action);
                form_data.append('ip_address', ip_address);
                form_data.append('name', category_name);
                form_data.append('description', category_description);
                form_data.append('status', 1);
                form_data.append('sort_order', category_sort_order);

                $('.jsCategoryNewManagerLoader').show();

                $.ajax({
                    url: add_category_url,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    data: form_data,
                    success: function(response){
                        $('.jsCategoryNewManagerLoader').hide();
                        if(response == 'error'){
                            alertify.alert(
                                'WARNING!',
                                'Category already exists.',
                                () => {}
                            );                            
                        } else{
                            //
                            getAllCategories();
                            //
                            alertify.alert('SUCCESS', 'New category is successfully added.', () => {
                                //
                                $('#jsAddNewCategoryModal').modal('hide');
                                $('#jsCategoryManagerModal').modal('show');
                                //
                                $.get(
                                    `<?=base_url('category_manager');?>/single/${obj.sid}/${obj.asid}`,
                                    (resp) => {
                                        $('#jsCategoryManagerSelect').select2('val', resp);
                                        $('.jsCategoryManagerLoader').fadeOut();
                                    }
                                )
                            });
                        }
                    },
                    error: function(){
                    }
                });

            } else {
                if (category_name == '') {
                    alertify.alert('Notice', 'Category name is required');
                    return false;
                }
            }
        });
        
        //
        $('.jsCategoryManagerSaveBTN').click(function(e){
            //
            e.preventDefault();
            //
            $('.jsCategoryManagerLoader').fadeIn();
            //
            $.post(
                `<?=base_url('category_manager');?>/update/${obj.sid}/${obj.asid}`, {
                    cats:  $('#jsCategoryManagerSelect').val()
                }, (resp) => {
                    //
                    $('#jsCategoryManagerModal').modal('hide');
                    //
                    alertify.alert(
                        'SUCCESS!',
                        'You have successfully updated the document categories.',
                        () => {
                            window.location.reload();
                        }
                    );
                }
            )
        });

        //
        $('#jsCategoryManagerSelect').change(function(){
            $('#jsCategoryManagerSelected').text(
                $(this).val() === null ? 0 : $(this).val().length
            );
        });


        //
        function getAllCategories(){
            //
            xhr = $.get(
                `<?=base_url('category_manager');?>/get`,
                (resp) => {
                    //
                    xhr = null;
                    //
                    catgeories = resp;
                    //
                    if(catgeories.length > 0){
                        //
                        let rows = '';
                        //
                        catgeories.map((v) => { rows += `<option value="${v.sid}">${v.name}</option>`; });
                        //
                        $('#jsCategoryManagerSelect').html(rows);
                        //
                        $('#jsCategoryManagerSelect').select2({
                            closeOnSelect: false
                        });
                    }
                }
            )
        }
    });
</script>
<?php } else { ?>
<script>
    $(document).ready(() => {
        $('.jsCategoryManagerBTN').remove();
    });
</script>
<?php }  ?>