<style>
    .red {
        float: none !important;
    }
</style>
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-tags"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin') ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Dashboard</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="heading-title">
                                                    <?php if (check_access_permissions_for_view($security_details, 'add_job_category')) { ?>
                                                        <button type="button" onclick="open_uploaded_model();" class="btn btn-success">Add Category</button>
                                                    <?php } ?>
                                                    &nbsp;
                                                    <a href="<?php echo base_url('manage_admin/document_categories_manager/document_category_industries'); ?>" class="btn btn-success">Industries</a>
                                                    <?php if (isset($appendix)) { ?>
                                                        <a href="<?php echo base_url('manage_admin/document_categories_manager'); ?>" class="black-btn text-right">All Categories</a>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-xs-12 text-center">
                                                    <nav class="hr-pagination">
                                                        <ul class="text-center">
                                                            <?php for ($i = 65; $i < 91; $i++) {
                                                                $class = '';

                                                                if (strtolower(chr($i)) == $letter) {
                                                                    $class = 'class = "active"';
                                                                }

                                                                echo '<li ' . $class . '><a href="' . base_url('manage_admin/document_categories_manager/appendix/' . strtolower(chr($i))) . '">' . chr($i) . '</a></li>';
                                                            } ?>
                                                        </ul>
                                                    </nav>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <!--                                                        <h2 class="page-title text-center"><?php echo isset($appendix) ? 'Categories List' : 'All Job Listing Categories'; ?></h2>-->
                                                        <?php echo $page_links; ?>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="clearfix"></div>
                                                <div class="table-responsive">
                                                    <div class="hr-displayResultsTable">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-5">Category Name</th>
                                                                    <th class="col-xs-7">Description</th>

                                                                    <?php if (check_access_permissions_for_view($security_details, 'add_job_category')) { ?>
                                                                        <th class="text-center col-xs-1" colspan="2">Actions</th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($categories)) { ?>
                                                                    <?php foreach ($categories as $key => $category) { ?>
                                                                        <tr>
                                                                            <td><?php echo $category['category_name'] ?></td>
                                                                            <td><?php echo $category['description'] ?></td>
                                                                            <td class="text-center">
                                                                                <button title="Edit" data-toggle="tooltip" data-categorysid="<?php echo $category['sid']; ?>" data-categoryname="<?php echo $category['category_name'] ?>" data-categorydesc="<?php echo $category['description'] ?>" data-placement="top" class="btn btn-success btn-sm editdocumentcategory"><i class="fa fa-pencil"></i></button>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <form id="form_delete_category_<?php echo $category['sid']; ?>" method="post" enctype="multipart/form-data" action="document_categories_manager/delete_document_category">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="delete_category" />
                                                                                    <input type="hidden" id="category_sid" name="category_sid" value="<?php echo $category['sid']; ?>" />
                                                                                    <button onclick="func_delete_category(<?php echo $category['sid']; ?>, '<?php echo $category['category_name']; ?>');" type="button" title="Delete" data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                                                </form>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td class="text-center col-xs-1" colspan="4">No Document Categories Found!</td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <?php echo $page_links; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="document_listing_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add a new document category</h4>
            </div>
            <div class="modal-body">
                <div class="compose-message">
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100 autoheight">
                                <div class="row">
                                    <div class="col-md-4"><b>Name </b><span class="hr-required red"> * </span></div>
                                    <div class="col-md-8">
                                        <input type='text' class="hr-form-fileds invoice-fields" name='category_name' id="category_name" />
                                        <input type="hidden" value="" id="categoryaction" />
                                        <input type="hidden" value="" id="categorysid" />
                                    </div>
                                </div>

                                <br>
                                <div class="row">
                                    <div class="col-md-4"><b>Description </b></div>
                                    <div class="col-md-8">
                                        <textarea id="short_description" name="short_description" class="hr-form-fileds field-row-autoheight" rows="7"></textarea>

                                    </div>
                                </div>

                            </li>

                            <li class="form-col-100 autoheight">
                                <div class="row">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-8">
                                        <div class="message-action-btn">
                                            <input type="submit" value="Save" class="btn btn-success" onclick="document_category_form_validate()" id="btnsave">
                                        </div>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
    </div>
</div>


<?php if (check_access_permissions_for_view($security_details, 'add_job_category')) { ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle=tooltip]').tooltip();
        });

        function func_delete_category(category_sid, category_name) {
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want to delete "' + category_name + '" ?',
                function() {
                    $('#form_delete_category_' + category_sid).submit();
                },
                function() {});
        }
    </script>
<?php } ?>

<script>
    $(".editdocumentcategory").click(function() {

        var category_sid = $(this).attr('data-categorysid');
        var category_name = $(this).attr('data-categoryname');
        var category_description = $(this).attr('data-categorydesc');
        $('#categoryaction').val('edit_document_category');
        $('#category_name').val(category_name);
        $('#categorysid').val(category_sid);
        $('#short_description').val(category_description);
        $('.modal-title').text('Update Document Category');
        $('#btnsave').val('Update');
        $('#document_listing_modal').modal("show");
        $('.modal-backdrop').hide();

    });


    function open_uploaded_model() {
        $('#category_name').val('');
        $('#short_description').val('');
        $('#document_listing_modal').modal("show");
        $('.modal-backdrop').hide();
        $('#categoryaction').val('add_document_category');
        $('.modal-title').text('Add New Document Category');
        $('#btnsave').val('Save');
    }

    function document_category_form_validate() {
        var catagoryName = $('#category_name').val().trim();
        if (catagoryName == "" || catagoryName == undefined) {
            alertify.alert("Notice", "Please enter category name.");
            return;
        } else {
            //
            var obj = {};
            //
            var category_action = $('#categoryaction').val();
            var sid = $('#categorysid').val();
            var category_description = $('#short_description').val();
            obj.documentCategoryName = catagoryName;
            obj.action = category_action;
            obj.categorydescription = category_description;
            obj.sid = sid;
            $.ajax({
                url: "<?= base_url('manage_admin/document_categories_manager/handler'); ?>",
                type: 'POST',
                data: obj,
                beforeSend: function() {
                    $('#document_loader').show();
                }
            }).done(function(resp) {
                //
                $('#document_loader').hide();
                alertify.alert('SUCCESS!', resp.Message, function() {
                    if (resp.Status == true) {
                        window.location.reload();
                    }
                });
            });

        }

    }
</script>