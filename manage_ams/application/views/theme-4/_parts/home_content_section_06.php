<!-- Testimonials Section -->
<section id="testimonial" class="testimonial" data-stellar-background-ratio="0.1" data-stellar-vertical-offset="0">
    <div class="container">
        <div class="section-top text-center">
            <h2 class="section-title"><?php echo(isset($section_06_meta['title']) && $section_06_meta['title'] != '' ? $section_06_meta['title'] : ''); ?></h2><!-- /.section-title -->
            <p class="slide-description"><?php echo(isset($section_06_meta['tag_line']) && $section_06_meta['tag_line'] != '' ? $section_06_meta['tag_line'] : ''); ?></p>
        </div>
        <div class="section-details">
            <div id="testimonial-slider" class="testimonial-slider carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <?php $count = 0; ?>
                    <?php foreach($testimonials as $testimonial) {?>
                        <li class="<?php echo ( $count == 0 ? 'active' : '');?>" data-target="#testimonial-slider" data-slide-to="<?php echo $count; ?>"></li>
                        <?php $count++; ?>
                    <?php }?>
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <?php $count = 0; ?>
                    <?php foreach($testimonials as $testimonial) {?>
                        <div class="item <?php echo ( $count == 0 ? 'active' : '');?>">
                            <figure><img src="<?php echo AWS_S3_BUCKET_URL; echo ($testimonial['resource_name'] != '' ?  $testimonial['resource_name']  : '')?>"></figure>
                            <div class="client-quote">
                                <p><?php echo ($testimonial['short_description'] != '' ?  $testimonial['short_description']  : '')?></p>
                                <h2><?php echo ($testimonial['author_name'] != '' ?  $testimonial['author_name']  : '')?></h2>
                                <a class="readmore-link" href="<?php echo base_url('testimonial') . '/' . $testimonial['sid']?>" target="_blank">Read more</a>
                            </div>
                        </div>
                        <?php $count++; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Testimonials Section -->