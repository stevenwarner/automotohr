<?php $this->load->view('hr_documents_management/partials/test_approvers_section', ["appCheckbox_idx" => "jsHasApprovalFlow", "container_idx" => "jsApproverFlowContainer", "addEmployee_idx" => "jsAddDocumentApprovers", "intEmployeeBox_idx" => "jsEmployeesadditionalBox", "extEmployeeBox_idx" => "jsEmployeesadditionalExternalBox", "approverNote_idx" => "jsApproversNote"]); ?>


<?php $this->load->view('hr_documents_management/partials/test_approvers_section', ["appCheckbox_idx" => "jsHasApprovalFlow1", "container_idx" => "jsApproverFlowContainer1", "addEmployee_idx" => "jsAddDocumentApprovers1", "intEmployeeBox_idx" => "jsEmployeesadditionalBox1", "extEmployeeBox_idx" => "jsEmployeesadditionalExternalBox1", "approverNote_idx" => "jsApproversNote1"]); ?>

<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
<link href="<?= base_url() ?>assets/css/select2.css" rel="stylesheet" />
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" />

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
<script src="<?= base_url('assets/approverDocument/index.js'); ?>"></script>
<script src="<?= base_url() ?>assets/js/select2.js"></script>
<script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>

<script>
    $(document).ready(function() {

        $("#testApprovers").documentApproval({
           appCheckbox_idx: '.jsHasApprovalFlow',
           container_idx: '.jsApproverFlowContainer',
           addEmployee_idx: '.jsAddDocumentApprovers',
           intEmployeeBox_idx: '.jsEmployeesadditionalBox',
           extEmployeeBox_idx: '.jsEmployeesadditionalExternalBox',
           approverNote_idx: '.jsApproversNote',
           employeesList: <?= json_encode($employeesList); ?>
        }); 


 

        $("#testApprovers1").documentApproval({
           appCheckbox_idx: '.jsHasApprovalFlow1',
           container_idx: '.jsApproverFlowContainer1',
           addEmployee_idx: '.jsAddDocumentApprovers1',
           intEmployeeBox_idx: '.jsEmployeesadditionalBox1',
           extEmployeeBox_idx: '.jsEmployeesadditionalExternalBox1',
           approverNote_idx: '.jsApproversNote1',
           employeesList: <?= json_encode($employeesList); ?>
        });

        setTimeout(function(){
        	console.log($("#testApprovers1").documentApproval("get"))
        	$("#testApprovers1").documentApproval("get");
        },5000)
        
    })
</script>


