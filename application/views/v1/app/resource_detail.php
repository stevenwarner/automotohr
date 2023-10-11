<main>

    <?php
    //  _e($blogs, true, true);

    $resourceType = explode(',', $blogs['resource_type']);
    //
    if (in_array("eBooks", $resourceType)) {
        $downloadLink = true;
    } else {
        $downloadLink = false;
    }
    ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <span class="blog-main-crumb">
                    Blog <span class="blog-main-crumb-arrow"> > </span> <?= strip_tags($blogs['title']) ?>
                </span>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="blog-main-title ">
                    <?= strip_tags($blogs['title']) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="blog-main-title-date ">
                    <?php
                    echo  date('M d Y', strtotime($blogs['created_at']));
                    ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="blog-main-image">
                    <?php $dcoument_extension = pathinfo($blogs['resources'], PATHINFO_EXTENSION); ?>
                    <?php if (in_array($dcoument_extension, ['mp4', 'm4a', 'm4v', 'f4v', 'f4a', 'm4b', 'm4r', 'f4b', 'mov'])) { ?>
                        <div class="resources-video-div">
                            <video poster="./assets/images/smillingGirl.png" src="<?php echo base_url() . 'assets/uploaded_videos/resourses/' . $blogs['resources']; ?>" controls="true" class="resources-video-detail" alt="smiling girl"> </video>
                        </div>
                    <?php } else if (in_array($dcoument_extension, ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) { ?>
                        <img src="<?= !empty($blogs['resources']) ? AWS_S3_BUCKET_URL . $blogs['resources'] : base_url('assets/images/no-img.jpg'); ?>" class="resources-card-images-adjustment-detail" alt="tablet with tea">
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php if ($downloadLink == true) { ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="w-80 ">
                        <div class="row resources-subscribe-row-download ">
                            <div class="col-xs-12 col-xl-7 column-center">
                                <div class="file-btn-div subscribe-input-div">
                                    <input id="jsSubscriberEmail" class="upload-file-input subscribe-input" placeholder=" Download eBook " readonly />
                                    <a for="file-input" id="jsSubscribeCommunity" class="custom-file-download " data-bs-toggle="modal" data-bs-target="#exampleModal" href="<?= !empty($blogs['resources']) ? AWS_S3_BUCKET_URL . $blogs['resources'] : base_url('assets/images/no-img.jpg'); ?>">
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="blog-detail-div ">
                    <div class="para-text">
                        <?php echo $blogs['description'];
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>