  <?php if (!empty($blogs)) { ?>
      <?php foreach ($blogs as $blog) { ?>
          <div class="col-xs-12 col-md-6 col-lg-4">
              <div class="card card-margin-bottom">
                  <img class="resources-card-images-adjustment" src="<?= !empty($blog['feature_image']) ? AWS_S3_BUCKET_URL . $blog['feature_image'] : image_url('no-img.jpg'); ?>" alt="smiling girl">
                  <div class="card-body">
                      <p class="card-text">
                          <?= strlen(strip_tags($blog['title'])) > 50 ?  substr(strip_tags($blog['title']), 0, 50) . '...' : strip_tags($blog['title']); ?>
                      </p>
                      <a href="<?php echo base_url("resources/" . $blog['slug']); ?>" class="card-text">Read more</a>
                  </div>
              </div>
          </div>
      <?php } ?>
  <?php } ?>