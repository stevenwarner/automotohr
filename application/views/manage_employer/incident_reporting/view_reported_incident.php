<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('incident_reported'); ?>">
                                        <i class="fa fa-chevron-left"></i>
                                        Back
                                    </a>
                                    <?php echo $title; ?>                               
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <p>Incident Name: <b><?php echo $sub_title; ?></b></p>
                            <p>Reported by:    <b>
                                                    <?php if ($report_type == 'anonymous') { ?>
                                                        Anonymous
                                                    <?php } else { ?>
                                                        <?php echo $reported_by; ?>
                                                    <?php } ?>
                                                </b>
                            </p>
                            <p>Reported Date: <b><?php echo  date_format (new DateTime($reported_on), 'M d Y h:m a'); ?></b></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th class="col-xs-10">Question</th>
                                            <th class="col-xs-2">Answer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($reported_incident as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $value['question']; ?></td>
                                                <td>
                                                    <?php if ($value['question'] == 'signature') { ?>
                                                        <img style="max-height: 50px;" src="<?php echo $value['answer'] ?>">
                                                    <?php } else { ?>
                                                        <?php if ( preg_match( "/^a:[0-9]+:.*[;}]\$/s", $value['answer'] ) ) {?>
                                                            <?php 
                                                                $array = unserialize($value['answer']); 
                                                                foreach ($array as $key => $value) {
                                                                    echo $value;
                                                                    if( next( $array ) ) {
                                                                        echo ', ';
                                                                    }
                                                                }
                                                            ?>
                                                        <?php } else  { ?>    
                                                            <?php echo $value['answer']; ?> 
                                                        <?php } ?>
                                                    <?php } ?>      
                                                </td>
                                            </tr>
                                        <?php } ?>   
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php if ($report_type != 'anonymous') { ?>
                        <div class="row"> 
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <form enctype="multipart/form-data" method="post" action="<?php echo base_url('incident_reporting_system/manager_report/'); ?>">
                                    <input type="hidden" name="incident_reporting_sid" value="<?php echo $incident_reporting_sid; ?>" />
                                    <input type="hidden" name="reported_type_sid" value="<?php echo $incident_type_id; ?>" />
                                    <input type="hidden" name="incident_name" value="<?php echo $sub_title; ?>" />
                                    <input type="hidden" name="reported_by_sid" value="<?php echo $reported_by_sid; ?>" />
                                    <input type="hidden" name="reported_date" value="<?php echo $reported_date; ?>" />
                                    <input type="hidden" name="reported_by" value="<?php echo $reported_by; ?>" />
                                    <input type="submit" value="Respond To Incident" class="form-btn">
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>