<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>
        </div>
    </div>
</div>
<div class="main <?php echo ( $pageName == 'testimonial' ? 'main-testimonial' : ''); ?>">
    <div class="container">
        <div class="row">
            <div class="col-xs-4 pull-left">
                <a type="button" class="testimonial-next-btn pull-left" href="<?php echo ( $prev_testimonial == 0 ? 'javascript:void(0);': base_url('/testimonial/' . $prev_testimonial ));?>"><i class="fa fa-arrow-left"></i>&nbsp;Previous</a>
            </div>


            <div class="col-xs-4 pull-right">
                <a type="button" class="testimonial-next-btn pull-right" href="<?php echo  ( $next_testimonial == 0 ? 'javascript:void(0);': base_url('/testimonial/' . $next_testimonial ));?>">Next&nbsp;<i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                <div class="testimonials-detail">
                    <header class="heading-title">
                        <h1 class="section-title color">testimonial</h1>
                    </header>
                    <div class="testimonials-wrp">
                        <div class="wrp-inner">
                            <div class="testimonial-title">
                                <h2 class="section-title color"><?php echo html_entity_decode($testimonial['author_name']);?><small><?php echo $testimonial['designation'];?></small></h2>
                            </div>
                            <figure><img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $testimonial['resource_name'];?>"></figure>
                            <div class="testimonials-des">
                                <!-- <p><?php //echo html_entity_decode($testimonial['short_description']);?></p> -->
                                <p><?php echo html_entity_decode($testimonial['full_description']);?></p>
                                <div class="testimonials-share-on">
                                    <ul>
                                        <li><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(base_url('/testimonial/' . $testimonial['sid'])); ?>"><img alt="" src="<?php echo base_url('assets');?>/theme-1/images/social-2.png"></a></li>
                                        <li><a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo urlencode($testimonial['author_name']); ?>&amp;url=<?php echo urlencode(base_url('/testimonial/' . $testimonial['sid'])); ?>"><img alt="" src="<?php echo base_url('assets');?>/theme-1/images/social-3.png"></a></li>
                                        <li><a target="_blank" href="https://plusone.google.com/_/+1/confirm?hl=en&amp;url=<?php echo urlencode(base_url('/testimonial/' . $testimonial['sid'])); ?>"><img alt="" src="<?php echo base_url('assets');?>/theme-1/images/social-1.png"></a></li>
                                        <li><a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode(base_url('/testimonial/' . $testimonial['sid'])); ?>&amp;title=<?php echo urlencode($testimonial['author_name']); ?>&amp;summary=<?php echo urlencode($testimonial['short_description']); ?>&amp;source=<?php echo urlencode(base_url('/testimonial/' . $testimonial['sid'])); ?>"><img alt="" src="<?php echo base_url('assets');?>/theme-1/images/social-4.png"></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php if($testimonial['youtube_video_id'] != '') {?>
                            <div class="wrp-inner">
                                <div class="testimonials-video">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $testimonial['youtube_video_id'];?>"></iframe>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4 pull-left">
                <a type="button" class="testimonial-next-btn pull-left" href="<?php echo ( $prev_testimonial == 0 ? 'javascript:void(0);': base_url('/testimonial/' . $prev_testimonial ));?>"><i class="fa fa-arrow-left"></i>&nbsp;Previous</a>
            </div>


            <div class="col-xs-4 pull-right">
                <a type="button" class="testimonial-next-btn pull-right" href="<?php echo  ( $next_testimonial == 0 ? 'javascript:void(0);': base_url('/testimonial/' . $next_testimonial ));?>">Next&nbsp;<i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

