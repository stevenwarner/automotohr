<div class="job-detail-banner">
    <div class="container-fluid">
        <div class="detail-banner-caption default-banner"></div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="heading-title">
                <h2>Page not found!</h2>
            </div>
            <p><b>You can check the following pages from our career site.</b></p>
        </div>
    </div>
    <div class="row">          
        <ul class="sitemap_listing">
            <li><a href="/">Home Page</a></li>
            <?php   foreach($pages as $page) { 
                        $page_name = $page['page_name'];
                        $page_title = $page['page_title'];
                        $page_status = $page['page_status'];

                        if($page_status == 1){ ?>
                            <li><a href="<?php echo base_url().$page_name; ?>"><?php echo $page_title; ?></a></li>
            <?php       }
                    } ?>
        </ul>
    </div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
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
        text-transform: uppercase;
        width: 33%;
    }
    .sitemap_listing li a {
        color: #81b431;
        font-size: 16px;
        font-weight: 600;
        margin: 5px 0;
        text-transform: uppercase;
    }
    .sitemap_listing li a:hover{
        color:#000;
    }
    .heading-title h2 {
    color: #000;
    font-weight: bold;
    margin: 0;
    text-transform: capitalize;
}
.heading-title {
    border-bottom: 1px solid #d0d0d0;
    float: left;
    margin: 40px 0;
    padding: 0 0 20px;
    text-align: center;
    width: 100%;
}
</style>