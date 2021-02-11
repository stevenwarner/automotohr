<?php

    $prefixJS = '.min';
?>

<!--  -->
<script>
let
    baseURL = "<?=rtrim(base_url(), '/');?>/",
    handlerURL = "<?=rtrim(base_url(), '/');?>/timeoff/handler",
    companyId = <?=$company_sid;?>,
    employerId = <?=$employer_sid;?>,
    level = <?=$level;?>,
    employeeId = <?=isset($employee_sid) ? $employee_sid : 0;?>,
    employeeName = "<?=isset($employee_name) ? $employee_name : '';?>",
    requestId = <?=isset($request_sid) ? $request_sid : 0;?>,
    timeoffDateFormat = 'MMM DD YYYY, ddd',
    timeoffDateFormatD = 'YYYY-MM-DD',
    timeoffDateFormatDWT = 'YYYY-MM-DD H:i:s',
    timeoffDateFormatB = 'MMM D',
    timeoffDateFormatBD = 'dddd',
    timeoffDateFormatWithTime = 'MMM DD YYYY, ddd hh:mm a',
    awsURL = "<?=AWS_S3_BUCKET_URL;?>",
    holidayDates = <?=isset($holidayDates) ? json_encode($holidayDates) : '[]';?>,
    timeOffDays = <?=isset($timeOffDays) ? json_encode($timeOffDays) : '[]';?>,
    page = "<?=isset($page) ? $page : ''?>";
</script>

<script src="<?=base_url('assets/select2/select2.min.js');?>"></script>
<script src="<?=base_url('assets/js/select2.multi-checkboxes.js');?>"></script>
<script src="<?=base_url('assets/js/pagination.min.js');?>"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script type="text/javascript" src="<?=base_url('assets/js/moment.min.js');?>"></script>



<!-- Common functions -->
<script src="<?=base_url('assets/timeoff/js/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>

<?php if(in_array('policies', $this->uri->segment_array())): ?>
    <!-- For policies -->
<script src="<?=base_url('assets/timeoff/js/policy/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/policy/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/policy/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/policy/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<?php endif; ?>

<?php if(in_array('types', $this->uri->segment_array())): ?>
    <!-- For types -->
<script src="<?=base_url('assets/timeoff/js/type/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/type/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/type/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/type/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<?php endif; ?>

<?php if(in_array('holidays', $this->uri->segment_array())): ?>
    <!-- For holidays -->
<script src="<?=base_url('assets/timeoff/js/holidays/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/holidays/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/holidays/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/holidays/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<?php endif; ?>

<?php if(in_array('settings', $this->uri->segment_array())): ?>
    <!-- For settings -->
<script src="<?=base_url('assets/timeoff/js/setting/setting'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<?php endif; ?>

<?php if(in_array('approvers', $this->uri->segment_array())): ?>
    <!-- For approvers -->
<script src="<?=base_url('assets/timeoff/js/approvers/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/approvers/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/approvers/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/approvers/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<?php endif; ?>

<?php if(in_array('balance', $this->uri->segment_array())): ?>
    <!-- For balance -->
<script src="<?=base_url('assets/timeoff/js/balances/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/balances/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/mFileUploader/index.js');?>?v=<?=time();?>"></script>
<?php endif; ?>

<?php if(in_array('requests', $this->uri->segment_array())): ?>
    <!-- For requests -->
<script src="<?=base_url('assets/timeoff/js/requests/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/requests/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<?php endif; ?>

<?php if(in_array('lms', $this->uri->segment_array())): ?>
    <!-- For lms -->
<script src="<?=base_url('assets/timeoff/js/lms/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/lms/graph'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/lms/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/lms/policies'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<?php endif; ?>

<?php if(in_array('create_employee', $this->uri->segment_array())): ?>
    <!-- For employee -->
<script src="<?=base_url('assets/timeoff/js/employee/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/employee/graph'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/employee/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/employee/policies'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<?php endif; ?>

<?php if(in_array('employee_management_system', $this->uri->segment_array())): ?>
    <!-- For ems dashboard -->
<script src="<?=base_url('assets/timeoff/js/dashboard/dashboard'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/breakdown'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<?php endif; ?>

<?php if(in_array('dashboard', $this->uri->segment_array())): ?>
    <!-- For ems employee -->
<script src="<?=base_url('assets/timeoff/js/dashboard/dashboard'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<script src="<?=base_url('assets/timeoff/js/breakdown'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
<?php endif; ?>