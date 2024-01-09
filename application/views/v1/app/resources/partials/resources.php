 <?php if (!empty($resources)) { ?>
     <?php foreach ($resources as $resource) { ?>
         <div class="col-xs-12 col-md-6 col-lg-4">
             <div class="card card-margin-bottom">
                 <img src="<?= AWS_S3_BUCKET_URL . $resource['feature_image']; ?>" class="resources-card-images-adjustment" alt="feature image" />

                 <div class="card-body">
                     <div class="card-type-div">
                         <?php
                            $types = explode(',', $resource['resource_type']);
                            //
                            foreach ($types as $type) {
                                echo '<span class="resources-card-type card-pills-resource-type">' . $type . '</span>';
                            }
                            ?>
                     </div>
                     <p class="card-text">
                         <?= strlen(strip_tags($resource['title'])) > 50 ?  substr(strip_tags($resource['title']), 0, 50) . '...' : strip_tags($resource['title']); ?>
                     </p>
                     <a href="<?php echo base_url("resources/" . $resource['slug']); ?>" class="card-anchor-text opacity-90">Read more</a>
                 </div>
             </div>
         </div>
     <?php } ?>
 <?php } ?>