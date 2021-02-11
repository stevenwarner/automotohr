<?php
if(isset($remarket_company_settings['status']) && $remarket_company_settings['status'] == 1 && $_GET['applied_by'] > 0){
?>  
<script>
    $(document).ready(function(){ 
        var remarket_url = '<?= REMARKET_PORTAL_SAVE_APPLICANT_URL ?>';
        
        console.log('<?= $remarket_company_settings['portal_applicant_jobs_list'] ?>');
        $.post(
            remarket_url, 
            {
                portal_applicant_jobs_list: '<?= $remarket_company_settings['portal_applicant_jobs_list'] ?>',
                portal_job_applications: '<?= $remarket_company_settings['portal_job_applications'] ?>',
                questionnaire: '<?= $remarket_company_settings['questionnaire'] ?>',
                talent_and_fair_data: '<?= $remarket_company_settings['talent_and_fair_data'] ?>'
            },
            function(data, status){
                var uri = window.location.toString();
                if (uri.indexOf("?") > 0) {
                    var clean_uri = uri.substring(0, uri.indexOf("?"));
                    window.history.replaceState({}, document.title, clean_uri);
                }
                console.log("applicant id sent to remarket jobs portal: "+data);
            }
        );
    });    
</script>

<?php
}
?>