<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="job-feature-main m_job">
                    <div class="portalmid">
                        <div id="file_loader" style="display:none; height:1353px;"></div>
                        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: hidden;"></i>
                        <div class="loader_message" style="display:none; margin-top: 35px;">Please wait while referrals are exporting...</div>

                    </div>
                </div>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; $active = ($this->uri->segment(2)) ? $this->uri->segment(2) : '';?></span>
                    </div>


                        <div class="tabs-outer">
                            <ul class="nav nav-tabs" id="tabs">
                                <?php if(check_access_permissions_for_view($security_details, 'my_referrals')) { ?>
                                    <li class="referral-tabs" data-attr="all_referrals" <?php echo $active == 'all_referrals' || $active == '' ? 'class="active"' : ''?>><a data-toggle="tab" href="#all_referrals">My Referrals</a></li>
                                <?php } if(check_access_permissions_for_view($security_details, 'coworker')) { ?>
                                    <li class="referral-tabs" data-attr="coworker" <?php echo $active == 'coworker' ? 'class="active"' : ''?>><a data-toggle="tab" href="#coworker_referrals">Coworker Referrals</a></li>
                                <?php } if(check_access_permissions_for_view($security_details, 'referred_by_email')) { ?>
                                    <li class="referral-tabs" data-attr="via_email" <?php echo $active == 'via_email' ? 'class="active"' : ''?>><a data-toggle="tab" href="#email_referrals">Referred by Email</a></li>
                                <?php } if(check_access_permissions_for_view($security_details, 'applicant_referrals')) { ?>
                                    <li class="referral-tabs" data-attr="applicant_referrals" <?php echo $active == 'applicant_referrals' ? 'class="active"' : ''?>><a data-toggle="tab" href="#applicant_referrals">Applicant Provided Referrals</a></li>
                                <?php }?>
                            </ul>
                            <div class="tab-content">
                                <div id="all_referrals" class="tab-pane fade <?php echo $active == '' || $active == 'all_referrals' ? 'in active' : ''?>">
                                    <div class="message-action">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="hr-items-count">
                                                    <strong class="messagesCounter"><?php echo $references_count; //count($references); ?></strong> Jobs Referred
                                                </div>
                                            </div>

                                            <div class="col-xs-8 col-sm-8">
                                                <?php
                                                echo $links;
                                                ?>
                                            </div>

                                        </div>
                                        <?php if (check_access_permissions_for_view($security_details, 'export_referrals')) { ?>
                                            <?php if (isset($references) && sizeof($references) > 0) { ?>
                                                <div class="box-view reports-filtering">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <form method="post" id="export" name="export">
                                                                    <input type="submit" name="submit" class="submit-btn pull-right submit" value="Export" />
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <?php if ($references) { ?>
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp data-table">
                                                <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                                    <thead>
                                                    <tr>
                                                        <th class="col-xs-2">Referred On</th>
                                                        <th class="col-xs-2">Referred By</th>
                                                        <th class="col-xs-3">Job Title</th>
                                                        <th class="col-xs-2">Referred To</th>
                                                        <th class="col-xs-3">Reference Email</th>
                                                        <!--<th class="col-xs-1"></th>-->
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <!--All records-->
                                                    <?php foreach ($references as $reference) { ?>
                                                        <tr>
                                                            <td><?=reset_datetime(array('datetime' => $reference['referred_date'], '_this' => $this)); ?></td>
                                                            <td><?php echo $reference['user_name']; ?></td>
                                                            <td><?php echo $reference['Job_title']; ?></td>
                                                            <td><?php echo $reference['referred_to']; ?></td>
                                                            <td><?php echo $reference['reference_email']; ?></td>
                                                            <!--<td><a class="btn btn-default" href="<?php echo base_url('my_reference_network') . '/view/' . $reference['sid']; ?>"><i class="fa fa-eye"></i></a></td>-->
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div id="show_no_jobs" class="table-wrp">
                                            <span class="applicant-not-found">No Referrals found!</span>
                                        </div>
                                    <?php } ?>
                                    <div class="col-xs-12 col-sm-12">
                                        <?php echo $links;  ?>
                                    </div>
                                </div>
                                <div id="coworker_referrals" class="tab-pane fade <?php echo $active == 'coworker' ? 'in active' : ''?>">
                                    <div class="message-action">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="hr-items-count">
                                                    <strong class="messagesCounter"><?php echo $references_count; //count($references); ?></strong> Jobs Referred
                                                </div>
                                            </div>
                                            <div class="col-xs-8 col-sm-8">
                                                <?php
                                                echo $links;
                                                ?>
                                            </div>
                                        </div>
                                        <?php if (check_access_permissions_for_view($security_details, 'export_referrals')) { ?>
                                            <?php if (isset($coworkers) && sizeof($coworkers) > 0) { ?>
                                                <div class="box-view reports-filtering">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <form method="post" id="export" name="export">
                                                                    <input type="submit" name="submit" class="submit-btn pull-right submit" value="Export" />
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <?php if (!empty($coworkers)) { ?>
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp data-table">
                                                <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                                    <thead>
                                                    <tr>
                                                        <th class="col-xs-2">Referred On</th>
                                                        <th class="col-xs-2">Referred By</th>
                                                        <th class="col-xs-3">Job Title</th>
                                                        <th class="col-xs-2">Referred To</th>
                                                        <th class="col-xs-3">Referred Email</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <!--All records-->
                                                    <?php foreach ($coworkers as $coworker) { ?>
                                                        <tr>
                                                            <td><?=reset_datetime(array('datetime' => $coworker['date_time'], '_this' => $this)); ?></td>
                                                            <td><?php echo $coworker['referral_name']; ?></td>
                                                            <td><?php echo $coworker['Title']; ?></td>
                                                            <td><?php echo ucwords($coworker['first_name'] . " " . $coworker['last_name']); ?></td>
                                                            <td><?php echo $coworker['email']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div id="show_no_jobs" class="table-wrp">
                                            <span class="applicant-not-found">No Co-Worker Referrals found!</span>
                                        </div>
                                    <?php } ?>
                                    <div class="col-xs-12 col-sm-12">
                                        <?php echo $links;  ?>
                                    </div>
                                </div>
                                <div id="email_referrals" class="tab-pane fade <?php echo $active == 'via_email' ? 'in active' : ''?>">
                                    <div class="message-action">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="hr-items-count">
                                                    <strong class="messagesCounter"><?php echo $references_count; //count($references); ?></strong> Jobs Referred
                                                </div>
                                            </div>
                                            <div class="col-xs-8 col-sm-8">
                                                <?php
                                                echo $links;
                                                ?>
                                            </div>
                                        </div>
                                        <?php if (check_access_permissions_for_view($security_details, 'export_referrals')) { ?>
                                            <?php if (isset($via_emails) && sizeof($via_emails) > 0) { ?>
                                                <div class="box-view reports-filtering">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <form method="post" id="export" name="export">
                                                                    <input type="submit" name="submit" class="submit-btn pull-right submit" value="Export" />
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <?php if (!empty($via_emails)) { ?>
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp data-table">
                                                <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                                    <thead>
                                                    <tr>
                                                        <th class="col-xs-2">Referred On</th>
                                                        <th class="col-xs-2">Referred By</th>
                                                        <th class="col-xs-3">Job Title</th>
                                                        <th class="col-xs-2">Referred To</th>
                                                        <th class="col-xs-3">Referred Email</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <!--All records-->
                                                    <?php foreach ($via_emails as $coworker) { ?>
                                                        <tr>
                                                            <td><?=reset_datetime(array('datetime' => $coworker['date_time'], '_this' => $this)); ?></td>
                                                            <td><?php echo $coworker['referral_name']; ?></td>
                                                            <td><?php echo $coworker['Title']; ?></td>
                                                            <td><?php echo ucwords($coworker['share_name']); ?></td>
                                                            <td><?php echo $coworker['share_email']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div id="show_no_jobs" class="table-wrp">
                                            <span class="applicant-not-found">No Referrals via Email Found!</span>
                                        </div>
                                    <?php } ?>
                                    <div class="col-xs-12 col-sm-12">
                                        <?php echo $links;  ?>
                                    </div>
                                </div>
                                <div id="applicant_referrals" class="tab-pane fade <?php echo $active == 'applicant_referrals' ? 'in active' : ''?>">
                                    <div class="message-action">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="hr-items-count">
                                                    <strong class="messagesCounter"><?php echo $references_count; //count($references); ?></strong> Jobs Referred
                                                </div>
                                            </div>
                                            <div class="col-xs-8 col-sm-8">
                                                <?php
                                                echo $links;
                                                ?>
                                            </div>
                                        </div>
                                        <?php if (check_access_permissions_for_view($security_details, 'export_referrals')) { ?>
                                            <?php if (isset($applicant_provided) && sizeof($applicant_provided) > 0) { ?>
                                                <div class="box-view reports-filtering">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <form method="post" id="export" name="export">
                                                                    <input type="submit" name="submit" class="submit-btn pull-right submit" value="Export" />
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <?php if (!empty($applicant_provided)) { ?>
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp data-table">
                                                <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                                    <thead>
                                                    <tr>
<!--                                                        <th class="col-xs-2">Referred On</th>-->
                                                        <th class="col-xs-2">Referred By</th>
                                                        <th class="col-xs-3">Job Title</th>
                                                        <th class="col-xs-2">Referred To</th>
                                                        <th class="col-xs-3">Referred Email</th>
                                                        <th class="col-xs-3">IP Address</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <!--All records-->
                                                    <?php foreach ($applicant_provided as $coworker) { ?>
                                                        <tr>
<!--                                                            <td>--><?php //echo my_date_format($coworker['date_applied']); ?><!--</td>-->
                                                            <td><?php echo $coworker['referred_by_name']; ?></td>
                                                            <td>
                                                                <?php
                                                                    $title = $coworker['Title'] != '' ? $coworker['Title'] : $coworker['desired_job_title'];
                                                                    echo $title == '' ? 'Manually Added' : $title;
                                                                ?>

                                                            </td>
                                                            <td><?php echo ucwords($coworker['first_name']." ".$coworker['last_name']); ?></td>
                                                            <td><?php echo $coworker['email']; ?></td>
                                                            <td><?php echo $coworker['ip_address']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div id="show_no_jobs" class="table-wrp">
                                            <span class="applicant-not-found">No Referrals Found!</span>
                                        </div>
                                    <?php } ?>
                                    <div class="col-xs-12 col-sm-12">
                                        <?php echo $links;  ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
            </div>          
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css">

<script type="text/javascript">
    $(document).ready(function(){
        $('.referral-tabs').click(function(){
            var attr = $(this).attr('data-attr');
            window.location.href = '<?php echo base_url('referral_network/')?>' + '/' + attr;
        });

        $('.submit').click(function(){
//            $('#file_loader').css("display", "block");
//            $('.my_spinner').css("visibility", "visible");
//            $('.loader_message').css("display", "block");
        });
    });
//    $(document).ready(function () {
//        $('#reference_network_table').DataTable({
//            paging: true,
//            info: false,
//            stateSave: true,
//            order: [[ 0, 'desc' ]]
//        });
//    });
</script>
