<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="page-header-area">
                    <span class="page-heading down-arrow">
                        <a href="<?php echo base_url('organizational_hierarchy'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Back
                        </a>
                        <?php echo $title; ?>
                    </span>
                </div>

                <!-- main table view start -->
                <div class="dashboard-conetnt-wrp">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                    <?php if(check_access_permissions_for_view($security_details, 'add_vacancy')) { ?>
                    <div class="">
                        <a class="btn btn-success" href="<?php echo base_url('organizational_hierarchy/add_vacancy'); ?>">Add Vacancy</a>
                    </div>
                    <?php } ?>
                    <hr />
                    <div class="hr-box">
                        <div class="hr-box-header bg-header-green">
                            <h1 class="hr-registered pull-left">
                                <span class=""><?php echo $subtitle; ?></span>
                            </h1>
                        </div>
                        <div class="hr-box-body hr-innerpadding">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center col-xs-1">Date</th>
                                            <th class="text-center col-xs-2">Department</th>
                                            <th class="text-center col-xs-2">Position</th>
                                            <th class="text-center col-xs-2">Vacancies</th>
                                            <th class="text-center col-xs-2">Hired</th>
                                            <th class="text-center col-xs-1">Remaining</th>
                                            <?php if(check_access_permissions_for_view($security_details, array('edit_vacancy', 'delete_vacancy', 'manage_hires'))) { ?>
                                            <th class="text-center col-xs-2" colspan="3">Actions</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($vacancies)) { ?>
                                            <?php foreach($vacancies as $vacancy) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo convert_date_to_frontend_format($vacancy['created_date'] ); ?></td>
                                                    <td class="text-center"><?php echo ($vacancy['dept_name'] != '' ? $vacancy['dept_name'] : 'General' ); ?></td>
                                                    <td class="text-center"><?php echo ($vacancy['position_name'] ); ?></td>
                                                    <td class="text-center"><?php echo ($vacancy['vacancies_count'] ); ?></td>
                                                    <td class="text-center"><?php echo ($vacancy['hired_count'] ); ?></td>
                                                    <td class="text-center"><?php echo ($vacancy['vacancies_count'] - $vacancy['hired_count']); ?></td>
                                                    <?php if(check_access_permissions_for_view($security_details, 'edit_vacancy')) { ?>
                                                    <td class="text-center">
                                                        <a class="btn btn-success btn-sm" href="<?php echo base_url('organizational_hierarchy/edit_vacancy/' . $vacancy['sid']); ?>"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                                                    </td>
                                                    <?php } ?>
                                                    <?php if(check_access_permissions_for_view($security_details, 'manage_hires')) { ?>
                                                    <td class="text-center">
                                                        <?php if(($vacancy['vacancies_count'] - $vacancy['hired_count']) > 0) { ?>
                                                            <a class="btn btn-success btn-sm" href="<?php echo base_url('organizational_hierarchy/manage_hires/' . $vacancy['sid']); ?>"><i class="fa fa-user"></i>&nbsp;Manage Hires</a>
                                                        <?php }  else { ?>
                                                            <a class="btn btn-success btn-sm disabled" href="javascript:void(0);"><i class="fa fa-user"></i>&nbsp;Manage Hires</a>
                                                        <?php } ?>
                                                    </td>
                                                    <?php } ?>
                                                    <?php if(check_access_permissions_for_view($security_details, 'delete_vacancy')) { ?>
                                                    <td class="text-center">
                                                        <form id="form_delete_vacancy_<?php echo $vacancy['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="delete_vacancy" />
                                                            <input type="hidden" id="vacancy_sid" name="vacancy_sid" value="<?php echo $vacancy['sid']; ?>" />
                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $vacancy['company_sid']; ?>" />
                                                        </form>
                                                        <button onclick="func_delete_vacancy(<?php echo $vacancy['sid']; ?>);" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;Delete</button>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <span class="no-data">No Vacancies</span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- main table view end -->
            </div>
        </div>
    </div>
</div>

<script>
    function func_delete_vacancy(vacancy_sid){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this vacancy?',
            function () {
                $('#form_delete_vacancy_' + vacancy_sid).submit();
            }, function () {
                alertify.error('Cancelled!');
            });
    }
</script>