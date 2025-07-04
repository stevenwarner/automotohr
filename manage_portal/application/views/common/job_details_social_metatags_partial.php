<?php if ($this->uri->segment(1) == 'job_details') { ?>
    <?php if(!empty($job_details)) { ?>
        <meta property="og:locale" content="en_US">
        <meta property="og:type" content="website" />
        <meta property="og:title" content="<?php echo $job_details['Title']; ?>" />
        <meta property="og:description" content="<?php echo substr(strip_tags($job_details['JobDescription']) , 0, 125) . '...' ; ?>" />
        <meta property="og:url" content="https://<?php echo db_get_sub_domain($job_details['user_sid']) . '/job_details/' . $job_details['sid']; ?>" />
        <meta property="og:site_name" content="https://<?php echo db_get_sub_domain($job_details['user_sid']); ?>" />
        <?php if($job_details['pictures'] != '') { ?>
                <meta property="og:image" content="<?php echo AWS_S3_BUCKET_URL . $job_details['pictures']; ?>" />
                <meta property="og:image:secure_url" content="<?php echo AWS_S3_BUCKET_URL . $job_details['pictures']; ?>" />
        <?php } else if( isset($company_details['Logo'])) { ?>
                <meta property="og:image" content="<?php echo AWS_S3_BUCKET_URL . $company_details['Logo']; ?>" />
                <meta property="og:image:secure_url" content="<?php echo AWS_S3_BUCKET_URL . $company_details['Logo']; ?>" />
        <?php } else { ?>
                <meta property="og:image" content="<?php echo base_url('assets/theme-1/images/bg-logo.png'); ?>" />
                <meta property="og:image:secure_url" content="<?php echo base_url('assets/theme-1/images/bg-logo.png'); ?>" />
        <?php } ?>
        <meta itemprop="name" content="<?php echo $job_details['Title']; ?>" />
        <meta itemprop="description" content="<?php echo substr(strip_tags($job_details['JobDescription']) . ' - ' . strip_tags($job_details['JobRequirements']), 0, 125) . '...' ; ?>" />
        <meta itemprop="image" content="<?php echo AWS_S3_BUCKET_URL . $job_details['pictures']; ?>">
        <meta name="twitter:card" content="<?php echo substr(strip_tags($job_details['JobDescription']) . ' - ' . strip_tags($job_details['JobRequirements']), 0, 125) . '...' ; ?>">
        <meta name="twitter:site" content="<?php echo STORE_FULL_URL; ?>">
        <meta name="twitter:title" content="<?php echo $job_details['Title']; ?>">
        <meta name="twitter:description" content="<?php echo substr(strip_tags($job_details['JobDescription']) . ' - ' . strip_tags($job_details['JobRequirements']), 0, 125) . '...' ; ?>">
        <meta name="twitter:creator" content="<?php echo STORE_NAME; ?>">

        <?php if($job_details['pictures'] != '') { ?>
            <meta property="twitter:image:src" content="<?php echo AWS_S3_BUCKET_URL . $job_details['pictures']; ?>">
        <?php } else if( isset($company_details['Logo'])) { ?>
            <meta property="twitter:image:src" content="<?php echo AWS_S3_BUCKET_URL . $company_details['Logo']; ?>">
        <?php } else { ?>
            <meta property="twitter:image:src" content="<?php echo base_url('assets/theme-1/images/bg-logo.png'); ?>">
        <?php } ?>

        <meta name="twitter:player" content="https://www.youtube.com/watch?v=<?php echo $job_details['YouTube_Video']; ?>">
        <meta property="fb:app_id" content="1688129328143598">

    <?php } ?>
<?php } ?>