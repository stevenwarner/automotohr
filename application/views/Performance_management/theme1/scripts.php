<?php
    $version = time();
    $prefixJS = '';
?>

<!--  -->
<script>
    /**
     * Add performance management object 
     * to window object
     */
    window.pm = {
        urls: {
            base: "<?=rtrim(base_url(), '/');?>/",
            pbase: "<?=rtrim(base_url(), '/');?>/performance-management/",
            handler: "<?=rtrim(base_url(), '/');?>/performance-management/handler/",
            aws: "<?=AWS_S3_BUCKET_URL;?>"
        },
        dateTimeFormats: {
            ymdf: 'MM/DD/YYYY',
            ymd: 'YYYY-MM-DD',
            ymdt: 'YYYY-MM-DD H:i:s',
            md: 'MMM D',
            d: 'dddd',
            mdy: 'MMM DD YYYY, ddd',
            mdyt: 'MMM DD YYYY, ddd hh:mm a'
        },
        companyId: <?=$companyId;?>,
        companyName: "<?=$companyDetails['CompanyName'];?>",
        companyLogo: "<?=$companyDetails['Logo'];?>",
        employerId: <?=$employerId;?>,
        employee: {
            id: <?=isset($employeeId) ? $employeeId : 0;?>,
            name: "<?=isset($employeeName) ? $employeeName : '';?>",
            level: <?=$level;?>
        },
        page: "<?=isset($page) ? $page : ''?>",
        Id: <?=isset($pid) ? $pid : 0; ?>,
        Pem: <?=isset($pem) ? $pem : 0; ?>,
        labels: <?=json_encode(getDefaultLabel());?>
    };
    <?php if(isset($dnt)): ?>
        var dnt = <?=json_encode($dnt);?>;
    <?php endif; ?>
    <?php if(isset($permission)): ?>
        window.pm.permission = <?=json_encode($permission);?>;
    <?php endif; ?>
    <?php if(isset($review)): ?>
        window.pm.review = <?=json_encode($review);?>;
    <?php endif; ?>
    <?php if(isset($employeeId)): ?>
        window.pm.employeeId = <?=json_encode($employeeId);?>;
    <?php endif; ?>
    <?php if(isset($isAllowed)): ?>
        window.pm.isAllowed = <?=$isAllowed?>;
    <?php endif; ?>
</script>

<script type="text/javascript" src="<?=base_url('assets/select2/select2.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/pagination.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/moment.min.js');?>"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script type="text/javascript" src="<?=base_url('assets/lodash/loadash.min.js');?>"></script>

<!-- Common functions -->
<script src="<?=base_url('assets/performance_management/js/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<script src="<?=base_url('assets/performance_management/js/goals/create'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>

<?php if(strpos($this->uri->uri_string(), 'review/create') !== false): ?>
    <script src="<?=base_url('assets/performance_management/js/video_record'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
    <!-- Create Review -->
    <script src="<?=base_url('assets/performance_management/js/review_create/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
    <script src="<?=base_url('assets/performance_management/js/review_create/view'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<?php endif; ?>
    
<?php if(strpos($this->uri->uri_string(), 'reviews') !== false): ?>
        <!-- Review Listing -->
        <script src="<?=base_url('assets/performance_management/js/reviews/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
        <script src="<?=base_url('assets/performance_management/js/reviews/view'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<?php endif; ?>
        
<?php if(strpos($this->uri->uri_string(), 'review') !== false): ?>
    <!-- Review Single Listing -->
    <script src="<?=base_url('assets/performance_management/js/review/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
    <script src="<?=base_url('assets/performance_management/js/review/view'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<?php endif; ?>
    
<?php if(strpos($this->uri->uri_string(), 'reviewer_feedback') !== false): ?>
    <!-- -->
    <script src="<?=base_url('assets/performance_management/js/reviewer_feedback/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
    <script src="<?=base_url('assets/performance_management/js/reviewer_feedback/view'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<?php endif; ?>
    
<?php if(strpos($this->uri->uri_string(), 'feedback') !== false): ?>
    <!-- -->
    <script src="<?=base_url('assets/performance_management/js/reviewer_feedback/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
    <script src="<?=base_url('assets/performance_management/js/reviewer_feedback/view'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<?php endif; ?>
    
<?php if(strpos($this->uri->uri_string(), 'goals') !== false): ?>
    <!-- Review Single Listing -->
    <script src="<?=base_url('assets/performance_management/js/goals/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
    <script src="<?=base_url('assets/performance_management/js/goals/view'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<?php endif; ?>