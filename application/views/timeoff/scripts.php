<?php

    $prefixJS = '';
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

<?php if (isset($theme) && $theme == 1) { ?>
    <!-- Common functions -->
    <script src="<?=base_url('assets/timeoff/js_theme_1/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <script src="<?=base_url('assets/timeoff/js_theme_1/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>

    <?php if(in_array('policies', $this->uri->segment_array())): ?>
        <!-- For policies -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/policy/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/policy/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/policy/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/policy/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('types', $this->uri->segment_array())): ?>
        <!-- For types -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/type/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/type/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/type/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/type/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('holidays', $this->uri->segment_array())): ?>
        <!-- For holidays -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/holidays/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/holidays/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/holidays/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/holidays/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('settings', $this->uri->segment_array())): ?>
        <!-- For settings -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/setting/setting'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('approvers', $this->uri->segment_array())): ?>
        <!-- For approvers -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/approvers/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/approvers/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/approvers/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/approvers/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('balance', $this->uri->segment_array())): ?>
        <!-- For balance -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/balances/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/balances/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/mFileUploader/index.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('requests', $this->uri->segment_array())): ?>
        <!-- For requests -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/requests/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/requests/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('lms', $this->uri->segment_array())): ?>
        <!-- For lms -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/lms/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/lms/graph'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/lms/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/lms/policies'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('create_employee', $this->uri->segment_array())): ?>
        <!-- For employee -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/employee/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/employee/graph'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/employee/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/employee/policies'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('employee_management_system', $this->uri->segment_array())): ?>
        <!-- For ems dashboard -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/dashboard/dashboard'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/breakdown'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('dashboard', $this->uri->segment_array())): ?>
        <!-- For ems employee -->
        <script src="<?=base_url('assets/timeoff/js_theme_1/dashboard/dashboard'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_1/breakdown'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>
<?php } else if (isset($theme) && $theme == 2) { ?>
    <!-- Common functions -->
    <script src="<?=base_url('assets/timeoff/js_theme_2/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <script src="<?=base_url('assets/timeoff/js_theme_2/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>

    <?php if(in_array('policies', $this->uri->segment_array())): ?>
        <!-- For policies -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/policy/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/policy/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/policy/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/policy/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('types', $this->uri->segment_array())): ?>
        <!-- For types -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/type/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/type/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/type/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/type/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('holidays', $this->uri->segment_array())): ?>
        <!-- For holidays -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/holidays/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/holidays/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/holidays/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/holidays/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('settings', $this->uri->segment_array())): ?>
        <!-- For settings -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/setting/setting'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('approvers', $this->uri->segment_array())): ?>
        <!-- For approvers -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/approvers/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/approvers/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/approvers/add'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/approvers/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('balance', $this->uri->segment_array())): ?>
        <!-- For balance -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/balances/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/balances/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/mFileUploader/index.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('requests', $this->uri->segment_array())): ?>
        <!-- For requests -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/requests/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/requests/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('lms', $this->uri->segment_array())): ?>
        <!-- For lms -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/lms/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/lms/graph'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/lms/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/lms/policies'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('create_employee', $this->uri->segment_array())): ?>
        <!-- For employee -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/employee/common'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/employee/graph'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/employee/view'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/employee/policies'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/edit'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('employee_management_system', $this->uri->segment_array())): ?>
        <!-- For ems dashboard -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/dashboard/dashboard'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/breakdown'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>

    <?php if(in_array('dashboard', $this->uri->segment_array())): ?>
        <!-- For ems employee -->
        <script src="<?=base_url('assets/timeoff/js_theme_2/dashboard/dashboard'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
        <script src="<?=base_url('assets/timeoff/js_theme_2/breakdown'.( $prefixJS ).'.js');?>?v=<?=time();?>"></script>
    <?php endif; ?>
<?php } ?>