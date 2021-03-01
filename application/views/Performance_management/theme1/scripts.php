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
        employerId: <?=$employerId;?>,
        employee: {
            id: <?=isset($employeeId) ? $employeeId : 0;?>,
            name: "<?=isset($employeeName) ? $employeeName : '';?>",
            level: <?=$level;?>
        },
        page: "<?=isset($page) ? $page : ''?>",
        labels: <?=json_encode(getDefaultLabel());?>
    };
    <?php if(isset($dnt)): ?>
        const dnt = <?=json_encode($dnt);?>;
    <?php endif; ?>
</script>

<script type="text/javascript" src="<?=base_url('assets/select2/select2.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/pagination.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/moment.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/lodash/loadash.min.js');?>"></script>

<!-- Common functions -->
<script src="<?=base_url('assets/performance_management/js/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<script src="<?=base_url('assets/performance_management/js/goals/create'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>


<?php if(in_array('create', $this->uri->segment_array())): ?>
    <script src="<?=base_url('assets/performance_management/js/video_record'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
    <!-- Create Review -->
    <script src="<?=base_url('assets/performance_management/js/review_create/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
    <script src="<?=base_url('assets/performance_management/js/review_create/view'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<?php endif; ?>

<?php if(in_array('reviews', $this->uri->segment_array())): ?>
    <!-- Review Listing -->
    <script src="<?=base_url('assets/performance_management/js/reviews/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
    <script src="<?=base_url('assets/performance_management/js/reviews/view'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<?php endif; ?>

<?php if(in_array('reviews', $this->uri->segment_array())): ?>
    <!-- Review Single Listing -->
    <script src="<?=base_url('assets/performance_management/js/review/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
    <script src="<?=base_url('assets/performance_management/js/review/view'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<?php endif; ?>

<?php if(in_array('goals', $this->uri->segment_array())): ?>
    <!-- Review Single Listing -->
    <script src="<?=base_url('assets/performance_management/js/goals/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
    <script src="<?=base_url('assets/performance_management/js/goals/view'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
<?php endif; ?>