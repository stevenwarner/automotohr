<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                                        <a href="<?php echo base_url('manage_admin')?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Dashboard</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="heading-title">
                                                    <?php if(check_access_permissions_for_view($security_details, 'add_job_category')){ ?>
                                                        <a href="<?php echo base_url('manage_admin/job_categories_manager/add_job_category'); ?>" class="btn btn-success">Add New Job Listing Category</a>
                                                    <?php } ?>
                                                    &nbsp;
                                                    <a href="<?php echo base_url('manage_admin/job_categories_manager/job_category_industries'); ?>" class="btn btn-success">Job Category Industries</a>

                                                    <?php if(isset($appendix)){ ?>
                                                        <a href="<?php echo base_url('manage_admin/job_categories_manager'); ?>" class="black-btn text-right">All Categories</a>
                                                    <?php } ?>
                                                </div>
<!--                                                <div class="row">-->
                                                    <div class="col-xs-12">
                                                        <nav class="hr-pagination">
                                                            <ul>
                                                        <?php   for($i = 65; $i < 91; $i++) {
                                                                    $class = '';
                                                                    
                                                                    if(strtolower(chr($i)) == $letter) {
                                                                        $class = 'class = "active"';
                                                                    }
                                                                    
                                                                    echo '<li '.$class.'><a href="'.base_url('manage_admin/job_categories_manager/appendix/'.strtolower(chr($i))).'">'.chr($i).'</a></li>';
                                                                } ?>
                                                            </ul>
                                                        </nav>
                                                    </div>
<!--                                                </div>-->
                                                <div class="row">
                                                    <div class="col-xs-12">
<!--                                                        <h2 class="page-title text-center"><?php echo isset($appendix) ? 'Categories List' : 'All Job Listing Categories';?></h2>-->
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
                                                                <th class="col-xs-11">Category Name</th>
                                                                <?php if(check_access_permissions_for_view($security_details, 'add_job_category')){ ?>
                                                                        <th class="text-center col-xs-1" colspan="2">Actions</th>
                                                                <?php } ?>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if(!empty($categories)) { ?>
                                                                <?php foreach($categories as $key => $category) { ?>
                                                                    <tr>
                                                                        <td><?php echo $category['value']?></td>
                                                                        <?php if(check_access_permissions_for_view($security_details, 'add_job_category')){ ?>
                                                                            <td class="text-center">
                                                                                <a title="Edit" data-toggle="tooltip" data-placement="top" href="<?php echo base_url('manage_admin/job_categories_manager/edit_job_category/' . $category['sid']); ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <form id="form_delete_category_<?php echo $category['sid']; ?>" method="post" enctype="multipart/form-data">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="delete_category" />
                                                                                    <input type="hidden" id="category_sid" name="category_sid" value="<?php echo $category['sid']; ?>" />
                                                                                    <button onclick="func_delete_category(<?php echo $category['sid']; ?>, '<?php echo $category['value']; ?>');" type="button" title="Delete" data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                                                </form>
                                                                            </td>
                                                                        <?php } ?>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td class="text-center col-xs-1" colspan="2">No Job Categories Found!</td>
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
<?php if(check_access_permissions_for_view($security_details, 'add_job_category')){ ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle=tooltip]').tooltip();
    });

    function func_delete_category(category_sid, category_name) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete "' + category_name + '" ?',
            function () {
                $('#form_delete_category_' + category_sid).submit();
            }, function () {
                alertify.warning('Cancelled');
            });
    }
</script>
<?php } ?>