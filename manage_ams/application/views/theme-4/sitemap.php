<div class="job-detail-banner">
    <div class="container">
        <div class="detail-banner-caption">
            <header class="heading-title">
                <h1 class="text-center" style="color:#fff;">Sitemap</h1>
            </header>
        </div>
    </div>
</div>
<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-md-12">
                <div class="amr-universal-section">
                    <div class="footer-widgets">
                        <div class="container-fluid">
                            <div class="row mt-3">
                                <ul class="sitemap_listing">
                                    <li class="col-md-6 col-xs-12"><a href="/">Home Page</a></li>
                                    <li class="col-md-6 col-xs-12"><a href="<?php echo base_url(strtolower(str_replace(' ', '_', $jobs_page_title))); ?>"><?php echo ucwords(strtolower(str_replace('-', ' ', $jobs_page_title))); ?></a></li>
                                    
<?php                               if(!empty($pages)) {
                                        foreach ($pages as $page) {
                                            if ($page['page_status'] == 1) { ?>
                                                <li class="col-md-6 col-xs-12 <?php echo($pageName == $page['page_name'] ? 'active' : ''); ?>" >
                                                    <a href="<?php echo base_url($page['page_name']); ?>"><?php echo $page['page_title']; ?></a>
                                                </li>
<?php                                       }
                                        }
                                    } ?>

                                    <li class="col-md-6 col-xs-12"><a href="<?php echo base_url('terms-of-use');?>">Terms of Use / Privacy Policy</a></li>
                                    
<?php                               if($contact_us_page) { ?>
                                        <li <?php if ($this->uri->segment(1) == 'contact_us') { ?>class="active" <?php } ?>><a href="<?php echo base_url('/contact_us'); ?>"></i>Contact Us</a></li>
<?php                               } ?>
                                    <?php if(!empty($companyJobs)): ?>
                                        <?php foreach($companyJobs as $companySlug):?>
                                            <li class="col-md-6 col-xs-12">
                                                <a href="<?=base_url($companySlug['slug']);?>"><?=$companySlug['title'];?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    ul.sitemap_listing {
        list-style-type: square;
        margin: 0 0 20px 15px;
    }
    .sitemap_listing li,.sitemap_listing li a {
        color:#81B431;
    }
    .sitemap_listing li{
        color: #81b431;
        float: left;
        font-size: 16px;
        font-weight: 600;
        margin: 5px 0;
    }
    .sitemap_listing li a {
        color: #81b431;
        font-size: 16px;
        font-weight: 600;
        margin: 5px 0;
    }
    .sitemap_listing li a:hover{
        color:#000;
    }
</style>