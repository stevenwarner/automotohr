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
                                        <a href="<?php echo base_url('manage_admin/job_categories_manager')?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Job Categories Manager</a>
                                    </div>
                                    <div class="add-new-promotions">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <a href="<?php echo base_url('manage_admin/job_categories_manager/add_job_category_industry'); ?>" class="btn btn-success" >Add New Category Industry</a>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-body hr-innerpadding">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-stripped table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-xs-4">Industry Name</th>
                                                                        <th class="col-xs-6">Description</th>
                                                                        <th class="col-xs-2 text-center" colspan="3">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if(!empty($job_category_industries)) { ?>
                                                                        <?php foreach($job_category_industries as $industry) { ?>
                                                                            <tr>
                                                                                <td><?php echo ucwords($industry['industry_name']); ?></td>
                                                                                <td><?php echo $industry['short_description']; ?></td>
                                                                                <td class="text-center">
                                                                                    <a class="btn btn-success btn-sm" href="<?php echo base_url('manage_admin/job_categories_manager/edit_job_category_industry/' . $industry['sid']); ?>"><i class="fa fa-pencil"></i></a>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <a class="btn btn-success btn-sm" href="<?php echo base_url('manage_admin/job_categories_manager/assign_categories/' . $industry['sid']); ?>">Assign Categories</a>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <a class="btn btn-danger btn-sm jsDeleteIndustry" title="Delete this job category industry" href="javascript:void(0)" data-id="<?=$industry['sid'];?>" data-name="<?php echo ucwords($industry['industry_name']); ?>">
                                                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
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
    </div>
</div>

<script>
    $(function(){
        //
        $('.jsDeleteIndustry').click(function(event){
            //
            event.preventDefault();
            //
            var industryId = $(this).data('id');
            var industryName = $(this).data('name');
            //
            alertify.confirm(
                'Do you really want to delete <strong>"'+(industryName)+'"</strong> industry?',
                function(){
                    DeleteIndustry(industryId, industryName);
                }
            ).setHeader('Confirm!');
        });

        //
        function DeleteIndustry(id, name){
            $('body').append('<style id="jsAlertify">.ajs-ok{display:none}</style>')
            //
            var ref = alertify.alert('Please wait while we are deleting "<strong>'+(name)+'</strong>" industry....').set('closable', false);
            //
            $.ajax({
                method: "delete",
                url: "<?=base_url('manage_admin/job_categories_manager/job_category_industries');?>/"+id,
            }).done(function(){
                //
                $('#jsAlertify').remove();
                ref.close();
                //
                alertify.alert('You have successfully deleted the industry.', function(){
                    window.location.reload();
                })
            });
        }
    });
</script>
