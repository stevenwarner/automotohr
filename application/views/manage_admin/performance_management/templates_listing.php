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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-envelope-square" aria-hidden="true"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="clearfix"></div>
                                    <p style="margin-top: 10px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Modify the default template questions for Perfomance Management.</p>
                                    <!-- Search Result table Start -->
                                    <form action="" method="post">
                                        <div class="table-responsive table-outer">
                                            <div class="hr-template-result">
                                                <table class="table table-bordered table-stripped table-hover">
                                                    <caption></caption>
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Template Name</th>
                                                            <th scope="col"># Of Questions</th>
                                                            <th scope="col">Last Updated On</th>
                                                            <th class="last-col" scope="col">Action</th>
                                                        </tr> 
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($data as $value) { ?>
                                                            <tr>
                                                                <td><?php echo $value['name']; ?></td>
                                                                <td><?php echo count(json_decode($value['questions'], true)); ?> Questions</td>
                                                                <td><?php echo formatDateToDB($value['updated_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></td>
                                                                <td class="text-center"><a class="hr-edit-btn" href="<?php echo base_url('manage_admin/performance_management/edit_performance_template').'/'.$value['sid']; ?>">Edit Template Question</a></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>