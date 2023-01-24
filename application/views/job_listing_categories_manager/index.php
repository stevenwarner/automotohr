<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>
                    <?php if(check_access_permissions_for_view($security_details, 'add_edit_group')) { ?>
                        <div class="message-action">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="message-action-btn">
                                        <a class="submit-btn" href="<?php echo base_url('job_listing_categories') . '/add'; ?>" >Create New</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-xs-12 col-sm-12">
                        <?php echo $links;  ?>
                    </div>
                    <?php if ($categories) { ?>
                    <div class="table-responsive table-outer">
                        <div class="table-wrp data-table">
                            <table id="categories_table" class="table">
                                <thead>
                                    <tr>
                                        <th class="col-xs-11">Category Name</th>
                                        <?php $function_names = array(''); ?>
                                        <?php if(check_access_permissions_for_view($security_details, $function_names)) { ?>
                                            <th class="col-xs-1">Actions</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($categories as $category) { ?>
                                    <tr>
                                        <td><?php echo $category['value']; ?></td>
                                        <td>
                                            <a class="btn btn-default" href="<?php echo base_url('job_listing_categories') . '/edit/' . $category['sid']; ?>"><i class="fa fa-pencil"></i></a>
                                            <!--
                                            <button type="button" class="btn btn-default" data-sid="<?php echo $category['sid'];?>" onclick="fDeleteCategory(this);"><i class="fa fa-trash"></i></button>
                                            -->
                                            <form id="form_delete_category_<?php echo $category['sid'];?>" method="post">
                                                <input type="hidden" id="action" name="action" value="delete_category" />
                                                <input type="hidden" id="sid" name="sid" value="<?php echo $category['sid'];?>" />
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <?php } else { ?>
                                <div id="show_no_jobs" class="table-wrp">
                                    <span class="applicant-not-found">No Custom Job Categories Found!</span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12">
                    <?php echo $links;  ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css">
<script type="text/javascript">
    function fDeleteCategory(source){
        sid = $(source).attr('data-sid');
        alertify.confirm(
            'Are you sure?',
            'Are You Sure You Want To Delete This Category?',
            function () {
                console.log(sid);
                $('#form_delete_category_' + sid).submit();
            },
            function(){
                //Cancel
            }).set({
                labels : {
                    'ok' : 'Yes',
                    'cancel' : 'No'
                }
            });
    }
</script>