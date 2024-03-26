<?php $downloadLink = strpos($blogs['resource_type'], "eBooks"); ?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-right">
                <a href="<?= base_url("resources") ?>" class="btn btn-dark btn-lg">
                    <i class="fa fa-long-arrow-left"></i>
                    Back to resources
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <span class="blog-main-crumb">
                    Blog <span class="blog-main-crumb-arrow"> > </span> <?= strip_tags($blog['title']) ?>
                </span>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="blog-main-title ">
                    <?= strip_tags($blog['title']) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="blog-main-title-date ">
                    <?php
                    echo  formatDateToDB(
                        $blog["created_at"],
                        DB_DATE_WITH_TIME,
                        DATE_WITH_TIME
                    );
                    ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="blog-detail-div111">
                    <div class="para-text">
                        <div style="padding-bottom: 20px;">
                            <?= makeResourceView($blog["resources"]); ?>
                        </div>

                        <?php if ($downloadLink) { ?>
                            <div class="row">
                                <div class="col-xs-1s2">
                                    <div class="w-80 ">
                                        <div class="row resources-subscribe-row-download ">
                                            <div class="col-xs-12 col-xl-7 column-center">
                                                <div class="file-btn-div subscribe-input-div">
                                                    <input id="" class="upload-file-input subscribe-input" placeholder=" Download eBook " readonly />
                                                    <a class="custom-file-download" target="_blank" href="<?= AWS_S3_BUCKET_URL . $blog['resources']; ?>">
                                                        Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php echo $blog['description']; ?>

                    </div>
                </div>
            </div>
        </div>

    </div>

</main>