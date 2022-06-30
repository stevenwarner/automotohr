<?php $this->load->view('hr_documents_management/partials/test_approvers_section', ["appCheckboxIdx" => "jsHasApprovalFlow", "containerIdx" => "jsApproverFlowContainer", "addEmployeeIdx" => "jsAddDocumentApprovers", "intEmployeeBoxIdx" => "jsEmployeesadditionalBox", "extEmployeeBoxIdx" => "jsEmployeesadditionalExternalBox", "approverNoteIdx" => "jsApproversNote"]); ?>


<?php $this->load->view('hr_documents_management/partials/test_approvers_section', ["appCheckboxIdx" => "jsHasApprovalFlow1", "containerIdx" => "jsApproverFlowContainer1", "addEmployeeIdx" => "jsAddDocumentApprovers1", "intEmployeeBoxIdx" => "jsEmployeesadditionalBox1", "extEmployeeBoxIdx" => "jsEmployeesadditionalExternalBox1", "approverNoteIdx" => "jsApproversNote1", 'mainId' => 'testApprovers1']); ?>

<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
<link href="<?= base_url() ?>assets/css/select2.css" rel="stylesheet" />
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" />

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
<script src="<?= base_url('assets/approverDocument/index.js'); ?>"></script>
<script src="<?= base_url() ?>assets/js/select2.js"></script>
<script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>


<button onclick="getMeLatestData()">getMeLatestData</button>

<script>



        $("#jsAssignedModifyDocumentModal").documentApprovalFlow({
            appCheckboxIdx: '.jsHasApprovalFlow1',
            containerIdx: '.jsApproverFlowContainer1',
            addEmployeeIdx: '.jsAddDocumentApprovers1',
            intEmployeeBoxIdx: '.jsEmployeesadditionalBox1',
            extEmployeeBoxIdx: '.jsEmployeesadditionalExternalBox1',
            approverNoteIdx: '.jsApproversNote1',
            employeesList: <?= json_encode($employeesList); ?>,
            prefill:{
                isChecked: true,
                approverNote: "Sdasdas",
                approversList: [15746,15753]
            },
            documentId: 0
        });




        function getMeLatestData(){
            console.log($("#testApprovers1").documentApprovalFlow("get"))
        }
</script>