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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid);?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <span class="page-title">Company Name : <?php echo $company_name; ?> </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                    <?php           if(sizeof($incident_types) > 0) {
                                        foreach($incident_types as $type) { 
                                            if($type['safety_checklist'] == 0) { ?>
                                                <div class="table-outer">
                                                    <div class="info-row">
                                                        <ul>
                                                            <li>
                                                                <label><?php echo $type['incident_name'];?></label>
                                                                <label><?php echo $type['count'] > 0 ? 'Configured' : 'Not Configured'?></label>
                                                                <a href="<?php echo base_url('manage_admin/reports/incident_reporting/configure_incident/' . $company_sid .'/'. $type['id']); ?>" class="site-btn pull-right">Configure</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                    <?php                   } 
                                        }
                                    } else {
                                        echo    '<div class="table-outer">
                                                    <div class="info-row">
                                                        <ul>
                                                            <li>
                                                                No Incident Type Found
                                                            </li>
                                                        </ul>
                                                    </div>';
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>