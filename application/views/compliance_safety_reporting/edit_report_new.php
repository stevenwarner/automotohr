<div class="main jsmaincontent" style="position:relative">
    <?php $firstSegment = $this->uri->segment(1); ?>
    <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>


    <div class="container-fluid">
        <!--  -->
        <div style="border-bottom: 2px solid #00e; margin-bottom: 10px;">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                    <h2 class="text-info">
                        <?= $report["compliance_report_name"]; ?>
                    </h2>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 text-right">
                    <br>
                    <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('employee_management_system') : base_url('dashboard'); ?>"
                        class="btn btn-black">
                        <i class="fa fa-arrow-left"></i>
                        Dashboard
                    </a>
                    <a href="<?= base_url("compliance_safety_reporting/dashboard"); ?>" class="btn btn-orange">
                        <i class="fa fa-pie-chart"></i>
                        Compliance Dashboard
                    </a>
                    <a href="javascript:void(0)" class="btn btn-orange jsSendReminderEmails">
                        <i class="fa fa-send"></i>
                        Send Emails
                    </a>
                    <a class="btn btn-black" target="_blank"
                        href="<?= base_url("{$firstSegment}/download_report/" . $report["sid"]); ?>">
                        <i class="fa fa-download"></i>
                        Download
                    </a>
                </div>
            </div>
        </div>

        <!-- Bootstrap Tabs -->
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li
                        class="nav-item <?= $this->input->get("tab", true) == "overview" || !$this->input->get("tab", true) ? "active" : ""; ?>">
                        <a class="nav-link" data-toggle="tab" href="#tab-overview" role="tab">
                            <i class="fa fa-info-circle"></i> Overview
                        </a>
                    </li>
                    <li class="nav-item <?= $this->input->get("tab", true) == "issues" ? "active" : ""; ?>">
                        <a class="nav-link" data-toggle="tab" href="#tab-issues" role="tab">
                            <i class="fa fa-exclamation-triangle"></i> Issues
                        </a>
                    </li>
                    <li class="nav-item <?= $this->input->get("tab", true) == "questions" ? "active" : ""; ?>">
                        <a class="nav-link" data-toggle="tab" href="#tab-questions" role="tab">
                            <i class="fa fa-question-circle"></i> Questions
                        </a>
                    </li>
                    <li class="nav-item <?= $this->input->get("tab", true) == "files" ? "active" : ""; ?>">
                        <a class="nav-link" data-toggle="tab" href="#tab-files" role="tab">
                            <i class="fa fa-file"></i> Files
                        </a>
                    </li>
                    <li class="nav-item <?= $this->input->get("tab", true) == "emails" ? "active" : ""; ?>">
                        <a class="nav-link" data-toggle="tab" href="#tab-emails" role="tab">
                            <i class="fa fa-envelope"></i> Emails
                        </a>
                    </li>
                    <li class="nav-item <?= $this->input->get("tab", true) == "notes" ? "active" : ""; ?>">
                        <a class="nav-link" data-toggle="tab" href="#tab-notes" role="tab">
                            <i class="fa fa-sticky-note"></i> Notes
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <br />

        <div class="tab-content">
            <?php $this->load->view('compliance_safety_reporting/reporting/basic'); ?>
            <?php $this->load->view('compliance_safety_reporting/reporting/issues'); ?>
            <?php $this->load->view('compliance_safety_reporting/reporting/questions'); ?>
            <?php $this->load->view("compliance_safety_reporting/reporting/files"); ?>

            <?php $this->load->view("compliance_safety_reporting/partials/files/emails"); ?>
            <?php $this->load->view('compliance_safety_reporting/reporting/notes'); ?>
        </div>
    </div>
</div>