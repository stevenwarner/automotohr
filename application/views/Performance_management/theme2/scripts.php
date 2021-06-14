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
        }
    };
</script>

<script type="text/javascript" src="<?=base_url('assets/select2/select2.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/pagination.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/moment.min.js');?>"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script type="text/javascript" src="<?=base_url('assets/lodash/loadash.min.js');?>"></script>

<!-- Common functions -->
<script src="<?=base_url('assets/performance_management/js/common'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>

<?php 
    if(strpos($this->uri->uri_string(), 'review/create') !== false){
        ?>
        <script type="text/javascript" src="<?=base_url('assets/performance_management/js/theme2/create'.( $prefixJS ).'.js');?>?v=<?=$version;?>"></script>
        <?php
    }
?>